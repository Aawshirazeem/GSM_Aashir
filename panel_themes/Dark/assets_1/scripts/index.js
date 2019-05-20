/**

 * @author Batch Themes Ltd.

 */

(function() {

    'use strict';



    $(function() {



        var config = $.localStorage.get('config');
		
		var pathname = window.location.pathname; // Returns path only
		var res = pathname.split("/");
		
		var def_layout = config.layout;
		if(jQuery.inArray("supplier", res) !== -1){
			def_layout = 'horizontal-navigation-1';
		}
		
		/*if(jQuery.inArray("chat.html", res) !== -1){
			def_layout = 'collapsed-sidebar';
		}*/

        $('body').attr('data-layout', def_layout);

        $('body').attr('data-palette', config.theme);

        $('body').attr('data-direction', config.direction);



        var colors = config.colors;

        var palette = config.palette;



        worldMap('analytics-vector-map', colors, palette);

        easyPieChart('.analytics-easy-pie-chart-1', 100, colors.warning, palette.oddColor);

        easyPieChart('.analytics-easy-pie-chart-2', 100, colors.success, palette.oddColor);

        easyPieChart('.analytics-easy-pie-chart-3', 100, colors.danger, palette.oddColor);

        easyPieChart('.analytics-easy-pie-chart-4', 100, colors.info, palette.oddColor);



        /*chartistLineChart('#analytics-line-chart-1');

        chartistPieChart4('#analytics-pie-chart-4');

        chartJsAreaChart('analytics-area-chart', colors, palette);

        chartJsBarChart('analytics-bar-chart', colors, palette);*/



        /*setTimeout(function() {

            notify('You have 5 unread messages', 'info');

        }, 2000);



        setTimeout(function() {

            notify('Someone posted something on facebook', 'danger');

        }, 30000);*/



        $('button[data-animate]').on('click', function() {

            var id = $(this).data('animate');

            animateButton('#' + id);

        });



    });



})();

