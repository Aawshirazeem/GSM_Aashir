/**
Template Name: Ubold Dashboard
Author: CoderThemes
Email: coderthemes@gmail.com
File: Chartjs
*/


!function($) {
    "use strict";

    var ChartJs = function() {};

    ChartJs.prototype.respChart = function respChart(selector,type,data, options) {
        // get selector by context
        var ctx = selector.get(0).getContext("2d");
        // pointing parent container to make chart js inherit its width
        var container = $(selector).parent();

        // enable resizing matter
        $(window).resize( generateChart );

        // this function produce the responsive Chart JS
        function generateChart(){
            // make chart width fit with its container
            var ww = selector.attr('width', $(container).width() );
            switch(type){
                case 'Line':
                    new Chart(ctx).Line(data, options);
                    break;
            }
            // Initiate new chart or Redraw

        };
        // run function - render chart at first load
        generateChart();
    },
    //init
    ChartJs.prototype.init = function() {
        //creating lineChart
        var LineChart = {
            labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
            datasets : [
                {
                    fillColor : "rgba(93, 156, 236, 0.5)",
                    strokeColor : "rgba(93, 156, 236, 1)",
                    pointColor : "rgba(93, 156, 236, 1)",
                    pointStrokeColor : "#fff",
                    data : [23,0,0,0,0,0,0,0,0,0,0,0]
                },
                {
                    fillColor : "rgba(95, 190, 170, 0.5)",
                    strokeColor : "rgba(95, 190, 170, 1)",
                    pointColor : "rgba(95, 190, 170, 1)",
                    pointStrokeColor : "#fff",
                    data : [9,0,0,0,0,0,0,0,0,0,0,0]
                },
                  {
                    fillColor : "rgba(14, 23, 21, 0.5)",
                    strokeColor : "rgba(14, 23, 21, 1)",
                    pointColor : "rgba(14, 23, 21, 1)",
                    pointStrokeColor : "#fff",
                    data : [2,0,0,0,0,0,0,0,0,0,0,0]
                }
                
            ]
        };
        
        this.respChart($("#lineChart"),'Line',LineChart);      
    },
    $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.ChartJs.init()
}(window.jQuery);

