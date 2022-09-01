# pylint: disable=missing-docstring
import json

import pandas as pd
import plotly.express as px
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from starlette.responses import RedirectResponse

graphAPI = FastAPI()


origins = [
    "http://localhost",
    "http://localhost:5000",
    "null",
]

graphAPI.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
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
    data_frame["generación"] = data_frame["generación"].astype(str)
    return data_frame


class File(BaseModel):
    """
    Path to the file to be loaded
    """

    file_name: str


@graphAPI.post("/graph/scatter")
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
        color="generación",
        color_discrete_sequence=px.colors.qualitative.G10,
        hover_name="nombre",
        hover_data=["cve_uaslp", "cve_larga", "situación"],
    )

    return json.loads(fig.to_json())
