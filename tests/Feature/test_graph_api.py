# pylint: disable=missing-docstring
from unittest import IsolatedAsyncioTestCase
from unittest.mock import patch

import pandas as pd
import plotly
from fastapi.testclient import TestClient

from graph.graph_api import Student, StudentList, get_dataframe, graphAPI


class GraphsAPI(IsolatedAsyncioTestCase):
    def setUp(self) -> None:
        self.student_list = StudentList(
            records=[
                Student(
                    name="Juan Perez",
                    uaslp_key="123456",
                    large_key="123456",
                    status="Regular",
                    generation="2018",
                    semesters_completed=8,
                    creds_per_semester=45,
                ),
                Student(
                    name="Maria Lopez",
                    uaslp_key="654321",
                    large_key="654321",
                    status="Regular",
                    generation="2018",
                    semesters_completed=8,
                    creds_per_semester=45,
                ),
            ]
        )

        self.client = TestClient(graphAPI)

        return super().setUp()

    @patch("graph.graph_api.get_dataframe")
    def test_bar_plot(self, mock_get_dataframe):
        """Test the function that generates the lag graph."""

        mock_get_dataframe.return_value = get_dataframe(self.student_list)

        graph = self.client.post("/lag_graph", json=self.student_list.dict())

        self.assertIsInstance(graph, plotly.graph_objs.Figure)
