//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

//jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top-one").addClass("top-nav-collapse-one");
    } else {
        $(".navbar-fixed-top-one").removeClass("top-nav-collapse-one");
    }
});

//jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
	
	$('.nav-item.dropdown').hover(function(){
		$(this).addClass('open');	
	},function(){
		$(this).removeClass('open');	
	});
	
});

$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top-perrot").addClass("top-nav-collapse-perrot");
    } else {
        $(".navbar-fixed-top-perrot").removeClass("top-nav-collapse-perrot");
    }
});

$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top-four").addClass("top-nav-collapse-four");
    } else {
        $(".navbar-fixed-top-four").removeClass("top-nav-collapse-four");
    }
});
