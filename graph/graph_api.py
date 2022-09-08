# pylint: disable=missing-docstring
# import json
from typing import Dict, List

import pandas as pd
import plotly.express as px
from fastapi import FastAPI, Request
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from starlette.responses import RedirectResponse

graphAPI = FastAPI()

graphAPI.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


@graphAPI.get("/")
async def root():
    """
    Return the documentation of the API."""
    return RedirectResponse(url="/docs")


def load_data(file_name):
    """
    Loads the data from the file_name and returns a pandas dataframe."""
    data_frame = pd.read_csv(file_name)
    data_frame["generation"] = data_frame["generation"].astype(str)
    return data_frame


class File(BaseModel):
    """
    Path to the file to be loaded
    """

    file_name: str



@graphAPI.post("/graph/scatter")
async def scatter_plot(data: Request):


    entries = await data.json()

    data_frame = pd.DataFrame()

    data_frame = data_frame.append(entries["entries"])
        
    print(data_frame)

    data_frame["generation"] = data_frame["generation"].astype(str)

    data_frame["cred_aprob_acum"] = (
        data_frame["semesters_completed"] * data_frame["creds_per_semester"]
    )

    fig = px.scatter(
        data_frame,
        x="cred_aprob_acum",
        y="creds_per_semester",
        color="generation",
        color_discrete_sequence=px.colors.qualitative.G10,
        hover_name="name",
        hover_data=["uaslp_key", "large_key", "status"],
    )

    print(fig.to_json())

    return fig.to_json()



@graphAPI.post("/file/graph/scatter")
async def plot_scatter(file: File):
    """
    Create a scatter plot from the data in the file.

    Returns a plotly figure on json format to be rendered in the browser.
    """

    data = load_data(file.file_name)
    fig = px.scatter(
        data,
        x="cred_aprob_acum",
        y="promedio_aprobatorio",
        color="generation",
        color_discrete_sequence=px.colors.qualitative.G10,
        hover_name="nombre",
        hover_data=["cve_uaslp", "cve_larga", "situación"],
    )

    return fig.to_json()
