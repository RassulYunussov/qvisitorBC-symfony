$(document).ready(function(){
var dat = new Date();
dat.setDate(-30);
document.getElementById("bd").valueAsDate = dat;
document.getElementById("ed").valueAsDate = new Date(); 
function LeasersData (data) {
   Highcharts.chart('container2', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Доля посетителей по каждому арендатору'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: '',
        colorByPoint: true,
        data: data,
    }]
});
}
function drawGraph()
{
    $date_begin = ($('#bd').val());
    $date_end = ($('#ed').val());
        $.ajax({
            type: 'POST',
            url: "/adminbc/byleasers",
            data: {dateBegin:$date_begin, dateEnd:$date_end},
            dataType: 'json',
            success: function(res){ 
            LeasersData(res);
        }
    });
}
drawGraph();
});