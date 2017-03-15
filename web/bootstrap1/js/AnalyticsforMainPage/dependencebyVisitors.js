
$(document).ready(function() {
function loadBuildings(){
    $('#builds').empty();
    $.ajax({
    type: "GET",
    url: "/adminbc/bybuildings",
    success: function(buildings){
    $.each(JSON.parse(buildings),function(k,v){
    $('#builds').append('<option value="'+v.id+'">'+v.name+'</option>');
    });
    }
    });
    }
  $(function (){
        loadBuildings();
    });

function loadCheckpoints(buildingId){
    $('#lchecks').empty();
    $.ajax({
        type: "GET",
        url: "/adminbc/allcheckpoints",
        data: {'id':buildingId},
        success: function(data){
$.each(JSON.parse(data),function(k,v){
$('#lchecks').append('<option value="'+v.id+'">'+v.name+'</option>');
});
}
});
$("#lchecks :first").attr("selected", "selected");
}
$(function (){
    loadCheckpoints($('#builds option:selected').val());
    $('#builds').change(function(){
        loadCheckpoints($('#builds option:selected').val());
        $("#lchecks :first").attr("selected", "selected");
        var ch = $('#lchecks :first').val();
        drawGraph(ch);
    });
});

function attendencebyOrders(qvEntrance, HotEntrance) {
Highcharts.chart('container3', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'График - посещаемости по заявкам и без заявок'
    },
    subtitle: {
        text: 'В разрезе КПП'
    },
    xAxis: {
        categories: [
           'Январь',
            'Февраль',
            'Март',
            'Апрель',
            'Май',
            'Июнь',
            'Июль',
            'Август',
            'Сентябрь',
            'Октябрь',
            'Ноябрь',
            'Декабрь'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Количество посетителей'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} посетителя(ей)</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name:'По заявкам',
        data: qvEntrance
    },
    {
         name:'Без заявок',
        data: HotEntrance
    }
    ]
});
}

$('#lchecks').change(function(){
    var checkpointId = ($('#lchecks :selected').val());
    drawGraph(checkpointId);
});
function drawGraph(checkpointId){
         $.ajax({
            type: 'POST',
            url: "/adminbc/byattendanceofOrders",
            data: {'checkpoint': checkpointId},
            dataType: 'json',
            success: function(res){

            attendencebyOrders(res[0],res[1]);             
        }
});
    }
drawGraph();
});