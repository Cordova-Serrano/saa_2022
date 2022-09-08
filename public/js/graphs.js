//const Plotly = require('plotly.js/lib/core');

//import Plotly from 'plotly.js'

// load from local file

function RenderGraphs(data) {
    const object_data = {
        entries: data,
    };
    console.log(object_data);
    const URL = "http://127.0.0.1:8000/graph/scatter";
    const request = {
        method: "POST",
        headers: {"content-type": "application/json", "accept": "application/json"},
        body: JSON.stringify(object_data),
    }
    fetch(URL, request)
        .then(response => response.json())
        .then(data => {
            json_graph = JSON.parse(data);

            Plotly.newPlot('graph', json_graph);
        }
    );
}
