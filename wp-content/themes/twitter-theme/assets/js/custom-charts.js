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

        tweets.sort((a ,b) => {
            a = new Date(a.date);
            b = new Date(b.date);
            if (a < b) 
                return -1;
            if (a>b) 
                return 1;
            return 0;
        });

        region.tweetsByDay = {};
        let minDate = new Date();
        let today = new Date();
        let maxDate = new Date();
        //maxDate.setDate(minDate.getDate() - 10000);
        minDate.setDate(minDate.getDate() + 1);

        for (let tweet of tweets) {
            dateObj = new Date(tweet.date);
            //console.log(tweet.id, tweet.date, '||' ,dateObj);
            if (dateObj < minDate) 
                minDate = dateObj;
            if(dateObj > maxDate)
                maxDate = dateObj;
        }

        for(let dateIterator = minDate; dateIterator <= maxDate; dateIterator.setDate(dateIterator.getDate() + 1)){
            let dateLabel = MONTH_NAMES[dateIterator.getMonth()] + " " + dateIterator.getDate();
            region.tweetsByDay[dateLabel] = [];
        }
        
        for (let tweet of tweets) {
            dateObj = new Date(tweet.date);
            let dateLabel = MONTH_NAMES[dateObj.getMonth()] + " " + dateObj.getDate();
            //console.log(tweet.id,dateLabel,MONTH_NAMES[dateObj.getMonth()],region.tweetsByDay,tweet)
            region.tweetsByDay[dateLabel].push(tweet);
        }

        

        
        let dailyData = [];
        let lineChartData = [
            ['Число', 'Твіти']
        ];

        let lineChartTweetTotalCount = 0;
        let counter = 0;
        let datesNum = Object.keys(region.tweetsByDay).length;
        for (let dateLabelIndex in region.tweetsByDay){
            
            let lineChartTweetDailyCount = region.tweetsByDay[dateLabelIndex].length;
            lineChartTweetTotalCount += lineChartTweetDailyCount

            console.log(region.tweetsByDay.length, counter, counter === 0, counter === region.tweetsByDay.length -1,counter === parseInt(region.tweetsByDay.length/2));

            let label = counter === 0 || 
                    counter === datesNum -1 || 
                    counter === parseInt(datesNum/2)?
                        dateLabelIndex:
                        "";

            lineChartData.push([dateLabelIndex, lineChartTweetTotalCount]);
            dailyData.push([dateLabelIndex, lineChartTweetDailyCount]);
            counter++;
        }


        let lineChartOptions = {
            title: '',
            curveType: 'function',
            legend: { position: 'none' },
            hAxis: {showTextEvery: 5},
            vAxis: {
                minValue: 0,
                viewWindow: {
                    min: 0
                }
                },
            colors: ['#433080'],
          };
        
        var lineChart = new google.visualization.LineChart(document.getElementById(`line-chart-1-region-${region.id}`));
        lineChart.draw(google.visualization.arrayToDataTable(lineChartData), lineChartOptions);

        console.log(region.label,dailyData,lineChartData);

        regions[regionId] = region;
    }

   

}