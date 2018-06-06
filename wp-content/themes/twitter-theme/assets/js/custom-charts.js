const MONTH_NAMES = ["Січ", "Лют", "Бер", "Кві", "Тра", "Чер",
    "Лип", "Серп", "Вер", "Жов", "Лис", "Гру"
];


google.charts.load("current", {packages: ["corechart"]});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {

    var dataTable = Object.values(window.regionsWithTweets).map(function (region) {
        return [region.name, region.tweetNum];
    });

    dataTable.unshift(['Області', 'Кількість твітів']);

    var data = google.visualization.arrayToDataTable(
        dataTable
    );

    var options = {
        title: 'Кількість твітів по областях України',
        legend: {position: 'none'},
        colors: ['#433080'],
        histogram: {lastBucketPercentile: 5},
        vAxis: {scaleType: 'mirrorLog'}
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('histogram1'));
    chart.draw(data, options);


    console.log(window.regions);

    for (let regionId in window.regions) {
        let region = regions[regionId],
            tweets = [];

        for (let circle of region.circles)
            tweets = tweets.concat(circle.tweets);

        let tweetsByDay = {};
        let minDate = new Date();
        let today = new Date();
        minDate.setDate(minDate.getDate() + 1);

        for (let tweet of tweets) {
            let date = new Date(tweet.date);
            let dateLabel = MONTH_NAMES[date.getMonth()] + " " + date.getDate();
            if (date < minDate) {
                minDate = date;
            }

            if (!tweetsByDay.hasOwnProperty(dateLabel))
                tweetsByDay[dateLabel] = [];

            tweetsByDay[dateLabel].push(tweet);
        }

        for(let dateIterator = minDate; dateIterator <= today; dateIterator.setDate(dateIterator.getDate() + 1)){
            let dateLabel = MONTH_NAMES[dateIterator.getMonth()] + " " + dateIterator.getDate();
            if (!tweetsByDay.hasOwnProperty(dateLabel))
                tweetsByDay[dateLabel] = [];
        }

        console.log(tweetsByDay);

    }
}