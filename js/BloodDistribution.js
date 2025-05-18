 // Load the Visualization API and the corechart package.
 google.charts.load('current', {'packages':['corechart']});

 // Set a callback to run when the Google Visualization API is loaded.
 google.charts.setOnLoadCallback(drawChart);

 function drawChart(){
    fetch("../charts/BloodDistribution.php")
    .then(response => response.json())
    .then(data => 
    {
        var chartDAta = google.visualization.arrayToDataTable (data);
        var options = {
            title  : '',
            width  : 900,
            height : 500 
        };
        var chart = new google.visualization.PieChart(document.getElementById("BloodDistribution"));
        chart.draw(chartDAta , options);
    }
    ).catch(error => console.error('Error loading data' , error));
 }