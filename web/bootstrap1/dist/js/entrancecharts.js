$(document).ready(function() {
    $('#form_eSelectdate_month').attr('readonly', 'readonly');
    $('#form_eSelectdate_month').css('display', 'none');
    $('#form_eSelectdate_day').attr('readonly', 'readonly');
    $('#form_eSelectdate_day').css('display', 'none');
    $('#form_heSelectdate_month').attr('readonly', 'readonly');
    $('#form_heSelectdate_month').css('display', 'none');
    $('#form_heSelectdate_day').attr('readonly', 'readonly');
    $('#form_heSelectdate_day').css('display', 'none');

    if($('ul.nav-tabs li.active').attr('id') == 'li-entrance'){
        a();
    } 

    $('#li-entrance').on('click', function(){
        a();
    });

    $('#li-hotentrance').on('click', function(){
        b();
    });



    function a(){
        $('#form_eSelectdate_year').change(function() {
            var year =  $('#form_eSelectdate_year :selected').val();
            drawGraph(year, 1);
            console.log(year);
        });
        drawGraph($('#form_eSelectdate_year :selected').val(), 1);
    }
    function b(){
         $('#form_heSelectdate_year').change(function() {
            var year =  $('#form_heSelectdate_year :selected').val();
            drawGraph(year, 2);
            console.log(year);
        });
        drawGraph($('#form_heSelectdate_year :selected').val(), 2);
    }

    function drawGraph(year, flag){
        $.ajax({
            type: 'POST',
            url: "/leaser/index/byyear",
            data:'year='+year+'&flag='+flag,
            dataType: 'json',
            success: function(res){            
                var series = { data: []};
                var formatter = new Intl.DateTimeFormat("ru", {
                      weekday: "long",
                      year: "numeric",
                      month: "long",
                      day: "numeric"
                    });
                $.each(res[1], function(key, val) {
                        var d = val[0].split("-");
                        var x = Date.UTC(d[0],d[1],d[2]);
                        series.data.push([x,val[1]]);
                    });
                entrancesbyyear(series.data, res[0]);    
                console.log(res[1]);       
            }
        });
    }




function entrancesbyyear(data, flag) {
    Highcharts.setOptions({
            lang: {
                loading: 'Загрузка...',
                months: ['Лол', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                weekdays: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
                shortMonths: ['Лол','Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сент', 'Окт', 'Нояб', 'Дек'],
                exportButtonTitle: "Экспорт",
                printButtonTitle: "Печать",
                rangeSelectorFrom: "С",
                rangeSelectorTo: "По",
                rangeSelectorZoom: "Период",
                downloadPNG: 'Скачать PNG',
                downloadJPEG: 'Скачать JPEG',
                downloadPDF: 'Скачать PDF',
                downloadSVG: 'Скачать SVG',
                printChart: 'Напечатать график',
                resetZoom: "Отменить увеличение"
            }
    });
    Highcharts.chart('container_'+flag, {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: 'Посещения во временной шкале'
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                    'Выделите область для увеличения' : 'Pinch the chart to zoom in'
        },
        xAxis: {
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: 'Посещения'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 2
                },
                lineWidth: 1,
                states: {
                    hover: {
                        lineWidth: 1
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: 'Количество',
            data: data
        }]
    });

}

});
