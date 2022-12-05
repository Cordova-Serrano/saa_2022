/**
 * 
 * Sends a POST request to the server to get a json with necessary data to render the graphs,
 * uses the value of component select-graph to determine which graph to render. It also sends
 * the value of the modal-var input if the graph type is "Rezago Academico".
 * 
 * @param {array} records - The alumn records to be used to render the graphs, as defined in the
 *                         controller and in the @ref graph.graph_api.Student model.
 */
 function RenderGraphs(records) {
    const graphType = document.getElementById('select-graph').value;
    const varValue = document.getElementById('modal-var').getAttribute('data-var');
    const URL = "http://127.0.0.1:8000/graph/" + graphType + "?var=" + varValue;
    const request = {
        method: "POST",
        headers: {"content-type": "application/json", "accept": "application/json"},
        body: JSON.stringify({
            records: records
        }),
    }
    console.log(request);
    fetch(URL, request)
        .then(response => response.json())
        .then(data => {
            json_graph = JSON.parse(data);

            Plotly.newPlot('graph', json_graph);
        }
    );
}