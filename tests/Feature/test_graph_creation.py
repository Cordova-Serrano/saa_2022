# pylint: disable=missing-docstring
from unittest import IsolatedAsyncioTestCase
from unittest.mock import patch

import pandas as pd
import plotly

from graph.graph_api import Student, StudentList, bar_plot, get_dataframe, scatter_plot


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
        return super().setUp()

    def test_get_dataframe(self):
        data_frame = get_dataframe(self.student_list)

        self.assertIsInstance(data_frame, pd.DataFrame)
        self.assertEqual(data_frame.shape, (2, 10))
        self.assertEqual(
            data_frame.columns.tolist(),
            [
                "name",
                "uaslp_key",
                "large_key",
                "status",
                "generation",
                "semesters_completed",
                "creds_per_semester",
                "cred_aprob_acum",
                "cred_rezago",
                "nivel_rezago",
            ],
        )

    @patch("graph.graph_api.get_dataframe")
    def test_lag_graph(self, mock_get_dataframe):
        """Test the function that generates the lag graph."""
        mock_get_dataframe.return_value = get_dataframe(self.student_list)

        lag_graph = bar_plot(self.student_list)

        figure = plotly.io.from_json(lag_graph)

        self.assertIsInstance(figure, plotly.graph_objs.Figure)

    @patch("graph.graph_api.get_dataframe")
    def test_process_graph(self, mock_get_dataframe):
        """Test the function that processes the graph."""
        mock_get_dataframe.return_value = get_dataframe(self.student_list)

        process_graph = scatter_plot(self.student_list)

        figure = plotly.io.from_json(process_graph)

        self.assertIsInstance(figure, plotly.graph_objs.Figure)
