$(document).ready(function(){
var dat = new Date();
var mdat = dat.setDate(-30);
document.getElementById("bd").valueAsDate = dat;
document.getElementById("ed").valueAsDate = new Date(); 
function visitorData(item) {
  Highcharts.chart('container1', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'График посещаемости БЦ'
    },
    subtitle: {
        text: 'Данные за последние 30 дней'
    },
    xAxis: {
        type: 'datetime',
        title: {
            text: 'Период времени'
        }
    },
    yAxis: {
        title: {
            text: 'Количество посетителей'
        },
        min: 0
    },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:%e. %b}: {point.y:.2f}'
    },

    plotOptions: {
        spline: {
            marker: {
                enabled: true
            }
        }
    },
    series: [{   
        name: 'Посещаемость',
        data: item,
        }],
});
}
function drawGraph(){
    $date_begin = ($('#bd').val());
    $date_end = ($('#ed').val());
        $.ajax({
            type: 'POST',
            url: "/adminbc/byattendance",
            data: {dateBegin:$date_begin, dateEnd:$date_end},
            dataType: 'json',
           success: function(res){
            var series = { data: []};
             $.each(res, function(key, val) {
                        var d = val[0].split("-");
                        var x = Date.UTC(d[0],d[1],d[2]);
                        series.data.push([x,val[1]]);
                    });
            visitorData(series.data);
        }
    });
}

drawGraph();

  $('#date_send').submit(function(){
    drawGraph();
    return false;
});
});