// Load Google Charts Library
google.charts.load('current', { 'packages': ['corechart'] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    fetch('../charts/AgeDistribution.php')
        .then(response => response.json())
        .then(data => {
            var chartData = google.visualization.arrayToDataTable(data);
            var options = {
                title: '',
                legend: { position: 'relative' },
                bars: 'horizontal' ,// Ensures the chart is vertical
                height : 500,
                width : 900,
                padding : 0,
                margin : 0,
            };
            var chart = new google.visualization.ColumnChart(document.getElementById('AgeDistribution'));
            chart.draw(chartData, options);
        })
        .catch(error => console.error('Error loading data:', error));
}