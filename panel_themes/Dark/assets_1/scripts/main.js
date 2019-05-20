/**
 * @author Batch Themes Ltd.
 */
(function() {
    'use strict';

    $(function() {
		//
		var pathname = window.location.pathname; // Returns path only
		var url      = window.location.href;     // Returns full URL
		var res = pathname.split("/");
		var def_layout = 'default-sidebar';
		
		//if(jQuery.inArray("supplier", res) !== -1) def_layout = 'horizontal-navigation-1';
		//if(jQuery.inArray("chat.html", res) !== -1) def_layout = 'collapsed-sidebar';
		
        var config = {
            name: 'Marino',
            theme: 'palette-5',
            palette: getPalette('palette-5'),
            layout: def_layout,
            direction: 'ltr', //ltr or rtl
            colors: getColors()
        };

        //$.removeAllStorages();
        if ($.localStorage.isEmpty('config') || !($.localStorage.get('config'))) {
            $.removeAllStorages();
            $.localStorage.set('config', config);
        }
        //var config = $.localStorage.get('config');

        var el = $('.main');
        var wh = $(window).height();
        el.css('min-height', wh + 'px');

        var el2 = $('.main-view');
        el2.css('min-height', (wh - 54) + 'px');


    });
})();
