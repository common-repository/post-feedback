
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

var chartElements = jQuery(".pf_chart");

function drawChart() {
  for (var i = 0, len = chartElements.length; i < len; i++) {
    var jsonData = jQuery(chartElements[i]).attr('data-feedback');
    var data = google.visualization.arrayToDataTable(JSON.parse(jsonData));

    var options = {
      legend: 'none',
      width: 150,
      vAxis: {
        gridlineColor: 'transparent',
        baselineColor: '#ccc',
        textPosition: 'none'
      },
      backgroundColor: 'transparent'
    };

    var chart = new google.visualization.ColumnChart(chartElements[i]);
    chart.draw(data, options);
  }
}
