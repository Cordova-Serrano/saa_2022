# pylint: disable=missing-docstring
# import json

import pandas as pd
import plotly.express as px
from fastapi import FastAPI
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


class Student(BaseModel):
    name: str
    uaslp_key: str
    large_key: str
    status: str
    generation: str
    semesters_completed: int
    creds_per_semester: float


class StudentList(BaseModel):
    records: list[Student]


def get_dataframe(student_list: StudentList) -> pd.DataFrame:

    data_frame = pd.DataFrame.from_records(
        map(lambda student: student.dict(), student_list.records)
    )

    data_frame["cred_aprob_acum"] = (
        data_frame["semesters_completed"] * data_frame["creds_per_semester"]
    )

    data_frame["cred_rezago"] = (
        data_frame["semesters_completed"] * 45 - data_frame["cred_aprob_acum"]
    )

    data_frame["nivel_rezago"] = data_frame["cred_rezago"].apply(
        lambda cred_rezago: (
            "Sin rezago"
            if cred_rezago <= 0
            else "Rezago leve"
            if cred_rezago <= 10
            else "Rezago grave"
        )
    )

    return data_frame


@graphAPI.post("/graph/scatter")
async def scatter_plot(data: StudentList):

    data_frame = get_dataframe(data)

    fig = px.scatter(
        data_frame,
        x="cred_aprob_acum",
        y="creds_per_semester",
        color="generation",
        color_discrete_sequence=px.colors.qualitative.G10,
        hover_name="name",
        hover_data=["uaslp_key", "large_key", "status"],
    )

    return fig.to_json()


@graphAPI.post("/graph/bar")
async def bar_plot(data: StudentList):

    data_frame = get_dataframe(data)

    fig = px.histogram(
        data_frame,
        x="generation",
        color="nivel_rezago",
        color_discrete_map={
            "Sin rezago": "rgb(0, 255, 0)",
            "Rezago leve": "rgb(255, 255, 0)",
            "Rezago grave": "rgb(255, 0, 0)",
        },
        hover_name="name",
        hover_data=["uaslp_key", "large_key", "status"],
    )

    return fig.to_json()
