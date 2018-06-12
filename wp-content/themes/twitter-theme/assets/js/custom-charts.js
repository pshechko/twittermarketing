const MONTH_NAMES = ["Січ", "Лют", "Бер", "Кві", "Тра", "Чер",
    "Лип", "Серп", "Вер", "Жов", "Лис", "Гру"
];


google.charts.load("current", {packages: ["corechart", "bar"]});

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
        maxDate.setDate(maxDate.getDate() + 1);

        for (let tweet of tweets) {
            dateObj = new Date(tweet.date);
            //console.log(tweet.id, tweet.date, '||' ,dateObj);
            if (dateObj < minDate) 
                minDate = dateObj;
            if(dateObj > maxDate)
                maxDate = dateObj;
        }

        for(let dateIterator = minDate; dateIterator < maxDate; dateIterator.setDate(dateIterator.getDate() + 1)){
            let dateLabel = MONTH_NAMES[dateIterator.getMonth()] + " " + dateIterator.getDate();
            region.tweetsByDay[dateLabel] = [];
        }
        
        for (let tweet of tweets) {
            dateObj = new Date(tweet.date);
            let dateLabel = MONTH_NAMES[dateObj.getMonth()] + " " + dateObj.getDate();
            region.tweetsByDay[dateLabel].push(tweet);
        }
        
        region.dailyData = [];
        region.lineChartData = [];
        //region.dailyDataObj = {};
        region.totalTweetsByDay = {};

        let lineChartTweetTotalCount = 0;

        for (let dateLabelIndex in region.tweetsByDay){
            
            let lineChartTweetDailyCount = region.tweetsByDay[dateLabelIndex].length;
            lineChartTweetTotalCount += lineChartTweetDailyCount

            //console.log(region.tweetsByDay.length, counter, counter === 0, counter === region.tweetsByDay.length -1,counter === parseInt(region.tweetsByDay.length/2));


            region.lineChartData.push([dateLabelIndex, lineChartTweetTotalCount]);
            region.dailyData.push([dateLabelIndex, lineChartTweetDailyCount]);
            region.totalTweetsByDay[dateLabelIndex] = lineChartTweetTotalCount;
            //region.dailyDataObj[dateLabelIndex] = lineChartTweetDailyCount;
        }

        let lineChartData = [['Число', 'Твіти']].concat(region.lineChartData);
        let colChartData = [['Число', 'Твіти']].concat(region.dailyData);


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
            width: 400,
          };
        
        var lineChart = new google.visualization.LineChart(document.getElementById(`line-chart-1-region-${region.id}`));
        lineChart.draw(google.visualization.arrayToDataTable(lineChartData), lineChartOptions);

        


        let colChartOptions = {
            legend: { position: 'none' },
            colors: ['#433080'],
            width: 400
          };

        var colChart = new google.charts.Bar(document.getElementById(`column-chart-1-region-${region.id}`));

        colChart.draw(google.visualization.arrayToDataTable(colChartData), google.charts.Bar.convertOptions(colChartOptions));


        regions[regionId] = region;
    }

    window.compareLineChartOptions = {
        title: '',
        curveType: 'function',
        legend: { position: 'bottom', alignment: 'start' },
        hAxis: {showTextEvery: 5},
        vAxis: {
                minValue: 0,
                viewWindow: {
                    min: 0
                }
            },
        chartArea: {
            width: '100%'
        }
    };

    window.compareColChartOptions = {
        'legend': 'bottom',
        hAxis: {showTextEvery: 5},
    };

    window.comparePieChartOptions = {};
    
    window.compareLineChart = new google.visualization.LineChart(document.getElementById(`line-chart-1-region-compare`));
    window.compareColChart = new google.charts.Bar(document.getElementById(`column-chart-1-region-compare`));
    window.comparePieChart = new google.visualization.PieChart(document.getElementById(`pie-chart-1-region-compare`));


    //compareLineChart.draw(google.visualization.arrayToDataTable([]), compareLineChartOptions);
    //compareColChart.draw(google.visualization.arrayToDataTable([]), compareColChartOptions);

   

}


var prepareRegionsToCompare = (...regionIds) =>{

    if(!Array.isArray(regionIds) || !regionIds.length || regionIds[0].hasOwnProperty('target'))
        regionIds = jQuery("[name='region-select-item']:checked").map(function(){
            return this.value;
        }).get();

    let minDate = new Date();
    let today = new Date();
    let maxDate = new Date();
    minDate.setDate(minDate.getDate() + 1);
    maxDate.setDate(maxDate.getDate() + 1);
    let compareDataForLineChart = [];
    //let tweets = [];
    console.log(regionIds);

    let totalPercentage = 0;

    for(let regionId of regionIds){
        let region = window.regions[regionId];
        totalPercentage += region.percentage;
        for(let circle of window.regions[regionId].circles)
            for(let tweet of circle.tweets){
                dateObj = new Date(tweet.date);
                if (dateObj < minDate) 
                    minDate = dateObj;
                if(dateObj > maxDate)
                    maxDate = dateObj;
            }
    }
   
    let lastResults = {};
    let lineChartTable = [["Число"]];
    let colChartTable = [["Число"]];
    let pieChartTable = [["Регіон", "Відсоток твітів"]];


    for(let dateIterator = minDate, i = 0; dateIterator < maxDate; dateIterator.setDate(dateIterator.getDate() + 1), i++){
        let dateLabel = MONTH_NAMES[dateIterator.getMonth()] + " " + dateIterator.getDate();
        let lineChartRow = [dateLabel];
        let colChartRow = [dateLabel];
       // let lastResult = 0;
        
        for(let regionId of regionIds){
            
            let region = window.regions[regionId];
            let lineChartVal = region.totalTweetsByDay.hasOwnProperty(dateLabel) ? region.totalTweetsByDay[dateLabel] : false;
            let colChartVal = region.tweetsByDay.hasOwnProperty(dateLabel) ? region.tweetsByDay[dateLabel].length : 0;

            if(!i){
                lineChartTable[0].push(region.name); 
                colChartTable[0].push(region.name); 
                lastResults[region.id] = 0;
                pieChartTable.push([region.name, region.percentage * 100 / totalPercentage]);
            }

            if(false === lineChartVal){
                lineChartVal = lastResults[region.id]
            }else{
                lastResults[region.id] = lineChartVal;
            }
            
            lineChartRow.push(lineChartVal);
            colChartRow.push(colChartVal);
            
        }
        lineChartTable.push(lineChartRow);
        colChartTable.push(colChartRow);
        //compareDataForLineChart.push();
        //region.tweetsByDay[dateLabel] = [];
    }

    if(regionIds.length>5){
        comparePieChartOptions.sliceVisibilityThreshold = .04
    }
    else{
        delete comparePieChartOptions.sliceVisibilityThreshold;
    }

    comparePieChart.draw(google.visualization.arrayToDataTable(pieChartTable), google.charts.Bar.convertOptions(comparePieChartOptions));
    compareLineChart.draw(google.visualization.arrayToDataTable(lineChartTable), google.charts.Bar.convertOptions(compareLineChartOptions));
    compareColChart.draw(google.visualization.arrayToDataTable(colChartTable), google.charts.Bar.convertOptions(window.compareColChartOptions));

    
    
    return [lineChartTable, colChartTable, pieChartTable];
}

jQuery(document).on('change', "[name='region-select-item']", prepareRegionsToCompare);