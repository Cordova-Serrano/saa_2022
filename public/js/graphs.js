//const Plotly = require('plotly.js/lib/core');

//import Plotly from 'plotly.js'

// load from local file
fetch('../json/data.json').then(response => response.json()).then(data => {
    console.log(data);
    var grap_HTML_div = document.getElementById('graph');
    Plotly.newPlot(grap_HTML_div, data);
}).catch(error => {
    console.log(error);
}).finally(() => {
    console.log('finally');
});

