google.charts.load("current", { packages: ["corechart"] });

google.charts.setOnLoadCallback(drawChart);

let appointmentsData = []; // Array to hold the data
let startIndex = 0;
const range = 7; // Number of days to show at once

// Fetch data from the PHP backend

function drawChart() {
  fetch("../charts/AppointmentChart.php")
    .then(response => response.json())
    .then( data => {
      console.log("data =>   ", data);
      appointmentsData = data; // Skip the header row
      console.log("data.slice(1) ", appointmentsData);
      console.log("data AppointmentChart drawChart ", appointmentsData);
      let data_ = new google.visualization.arrayToDataTable(appointmentsData);
      let options = {
        title: "",
        curveType:'none',
        legend: { position: "bottom" },
        vAxis: { minValue: 0 }  ,
        height: 500,
        width: 900,
      };

      let chart = new google.visualization.LineChart(
        document.getElementById("appointmentDate")
      );
      chart.draw(data_, options);
    })
    .catch((error) => console.error("Error loading data:", error));
}
