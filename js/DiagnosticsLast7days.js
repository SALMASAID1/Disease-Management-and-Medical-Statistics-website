google.charts.load("current", { packages: ["bar"] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
  fetch("../charts/DiagnosticsLast7days.php")
    .then((response) => response.json())
    .then((data) => {
      var chartData = google.visualization.arrayToDataTable(data);
      //   console.log("data DiagnosticsLast7days : ", data);
      var options = {
        title: "",
        // subtitle: 'Number of diagnostics performed each day'
        bars: "horizontal", // Display bars horizontally
        height: 500,
        width: 900,

        titleTextStyle: {
                    color: "#000",
       
          bold: true
        },
        legend: { position: "bottom" },
      };

      var chart = new google.charts.Bar(
        document.getElementById("DiagnosticsLast7days")
      );
      chart.draw(chartData, google.charts.Bar.convertOptions(options));
    })
    .catch((error) => console.error("Error loading data:", error));
}
