google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {

    var dataTable =  Object.values(window.regionsWithTweets).map(function(region){
        return [region.name, region.tweetNum];
    });

    dataTable.unshift(['Області', 'Кількість твітів']);

  var data = google.visualization.arrayToDataTable(
     dataTable
    );

    var options = {
        title: 'Кількість твітів по областях України',
        legend: { position: 'none' },
        colors: ['#433080'],
        histogram: { lastBucketPercentile: 5 },
        vAxis: { scaleType: 'mirrorLog' }
      };

  var chart = new google.visualization.ColumnChart(document.getElementById('histogram1'));
  chart.draw(data, options);
}