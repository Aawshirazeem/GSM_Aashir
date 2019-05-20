var _docReady = _winLoad = multi_tab = false;
var _tTmpl , _cTmpl , _resizeTabs , widthOfList, widthOfHidden , getLeftPosi, reAdjust;
var _aClick;
function getDocHeight(doc) {
    doc = doc || document;
    // stackoverflow.com/questions/1145850/
    var body = doc.body, html = doc.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight );
    return height;
}


function isEventSupported(eventName) {
    var el = document.createElement('div');
    eventName = 'on' + eventName;
    var isSupported = (eventName in el);
    if (!isSupported) {
        el.setAttribute(eventName, 'return;');
        isSupported = typeof el[eventName] == 'function';
    }
    el = null;
    return isSupported;
};
var wheelEvent = isEventSupported('mousewheel') ? 'mousewheel' : 'wheel';

function inIframe () {
    try {
        return window.self !== window.top;
    } catch (e) {
        return true;
    }
}

function resizeIframe(_url,called){
	$('iframe').each(function(i,e){
		var ifrm = this;					
		var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
		var _docurl = $(doc).find('body').attr('data-curl');
		if(_docurl == _url){
			//console.log('url matches');
			$(doc).find("body").addClass("iframe-loaded");
			ifrm.style.height = "0px";
			//console.log('New height is '+getDocHeight( doc ) );
			ifrm.style.height = getDocHeight( doc ) + 10 + "px";
			return false;
		}
		
	});
	
}

function unloadIframe(_url){
	$('iframe').each(function(i,e){
		var 	ifrm = this;				
		var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
		var _docurl = $(doc).find('body').attr('data-curl');
		if(_docurl == _url){
			$(ifrm).css('height','auto');
			return false;
		}
		
	});
}

function hideChatBar(){
	$(".sidebarRight").removeClass('show-from-right');
}

function hideDropdown(){
	$(".btnCustomWidth").parent().removeClass('open');
}

function hideColorPanel(){
	$(".selColorTheme").removeClass('show-theme-selector');
}

function hideSearchPanel(){
	$(".search-result").hide();
}

$(window).load(function(e){
	if(inIframe()){
		$('body').attr('data-curl',document.URL);
		parent.resizeIframe(document.URL,document);	
	}
	_winLoad = true;
});

$( window ).unload(function() {
	if(inIframe()){
		parent.unloadIframe(document.URL);	
	}
});



$(document).ready(function(e) {
	var hidWidth;
	var scrollBarWidths = 40;
	if(inIframe()){
		$('body').attr('data-curl',document.URL);
		parent.resizeIframe(document.URL,document);	
	}
	_docReady = true;	
	
	if(inIframe()){
		$(document).on('click','.parentOpen',function(e){
			parent._aClick(this,e);
		});
	}
	
	if(multi_tab == true && !inIframe()){
		if($('.multi-tabs').length > 0){
			setInterval(function(){
				$('iframe').each(function(i,e){
					$(this).css('min-height',window.innerHeight);
					var _lastHeight = $(this).attr('data-height');
					var ifrm = this;
					var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
					var _curHeight = Math.max($(this).contents().find('body > .container-fluid').height(),window.innerHeight);
					if ( _curHeight != _lastHeight ) {
						$(this).css('height', (_curHeight) + 'px' );
						$(this).attr('data-height',_curHeight);
					}
				});
			},500);
			
			_tTmpl = '<div class="tab active" data-url="URL"><div class="tab-box"></div><div class="tab-info"><span class="tab-title">TITLE</span> <span class="mtClose"><i class="zmdi zmdi-close"></i></span><span class="mtReload"><i class="zmdi zmdi-refresh"></i></span></div></div>';
			_cTmpl = '<div data-url="URL" class="multiTabCont active">Loading.....</div>';
			
			var _switchtab = function(_this){
				var _href = _this.attr('data-url');
				$('.multi-tabs .tab').removeClass('active');
				_this.addClass('active');
				$('.multiTabCont').removeClass('active');
				$('.multiTabCont[data-url="'+_href+'"]').addClass('active');
				reAdjust();
			}
			_resizeTabs = function(){
				var _tWidth = 0;
				$('.multi-tabs .tab').each(function(index, element) {
                    _tWidth += $(this).width() - 15;
                });
				$('.multi-tabs').css('width',_tWidth);
			}
			$(document).on('click','.multi-tabs .tab',function(e){
				_switchtab($(this));
			});
			
			$(document).on('click','.multi-tabs .mtClose',function(e){
				e.preventDefault();
				e.stopPropagation();
				var _href = $(this).closest('.tab').attr('data-url');
				var _active = '';
				var _prevT = $(this).closest('.tab').prev('.tab');
				if($(this).closest('.tab').hasClass('active')){
					_switchtab(_prevT);
				}
				$(this).closest('.tab').remove();
				$('.multiTabCont[data-url="'+_href+'"]').remove();
				_resizeTabs();
				reAdjust();
			});
			
			$(document).on('click','.multi-tabs .mtReload',function(e){
				e.preventDefault();
				e.stopPropagation();
				var _href = $(this).closest('.tab').attr('data-url');
				
				//$('.multiTabCont[data-url="'+_href+'"]').reload(true);
				/*$('.multiTabCont[data-url="'+_href+'"]').find('iframe').load(function(e) {
					var ifrm = this;
					setTimeout(function(){
						var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
						ifrm.style.visibility = 'hidden';
						ifrm.style.height = "10px"; // reset to minimal height ...
						// IE opt. for bing/msn needs a bit added or scrollbar appears
						ifrm.style.height = getDocHeight( doc ) + 4 + "px";
						ifrm.style.visibility = 'visible';
					},100);
					$(this).contents().find("body").addClass("iframe-loaded");
				});*/
				
				$('.multiTabCont[data-url="'+_href+'"]').find('iframe')[0].contentWindow.location.reload(true);
			});
			
			$(document).on('click','a',function(e){
				_aClick(this,e);
			});
			
			var widthOfList = function(){
				var itemsWidth = 0;
				$('.multi-tabs .tab').each(function(){
					var itemWidth = $(this).outerWidth() - 15;
					itemsWidth+=itemWidth;
				});
				return itemsWidth;
			};
			
			var widthOfHidden = function(){
				
				var widthH = (($('.multi-tabs-outer').outerWidth())-widthOfList()-getLeftPosi())-scrollBarWidths;
				console.log((getLeftPosi() - 125) + ' -- '+ widthH);
				console.log(Math.min(0,Math.max((getLeftPosi() - 125),widthH)))
				return Math.min(0,Math.max((getLeftPosi() - 125),widthH));
			};
			
			var getLeftPosi = function(){
				return $('.multi-tabs').position().left;
			};
			
			var toggleArrows = function(){
				if (($('.multi-tabs .tab:last-child').offset().left + 140) > ($('.multi-tabs-outer').outerWidth() + $('.multi-tabs-outer').offset().left)) {
					$('.scroller-right').show();
				} else {
					$('.scroller-right').hide();
				}
				if (getLeftPosi()<0) {
					$('.scroller-left').show();
				} else {
					$('.scroller-left').hide();
				}
			}
			
			var reAdjust = function(){
				var _tLorR = -1;
				if($('.multi-tabs .tab.active').offset().left < $('.multi-tabs-outer').offset().left ){
					_tLorR = 0;
				}else if(($('.multi-tabs .tab.active').offset().left + 125) >  ($('.multi-tabs-outer').offset().left + $('.multi-tabs-outer').outerWidth())){
					_tLorR = 1;
				}
				
				var _maxLeft = widthOfList() - ($('.multi-tabs-outer').outerWidth() - 20);
				var _nLeft = getLeftPosi() - 125;
				
				
			
				
				if(_tLorR == -1){ toggleArrows(); return false;  }
				
				var _actOfLeft = $('.multi-tabs .tab.active').offset().left - $('.multi-tabs-outer').offset().left;
				var _nLeft = false;
				if(_tLorR == 0){
					_nLeft =  getLeftPosi() - ($('.multi-tabs .tab.active').offset().left - $('.multi-tabs-outer').outerWidth() - 80);
				}else if(_tLorR == 1){
					_nLeft =  getLeftPosi() - ($('.multi-tabs .tab.active').offset().left - $('.multi-tabs-outer').outerWidth() - 80);
				}
				
				
				var _nLeft = Math.max(_nLeft,-_maxLeft);
				console.log(_nLeft)
				if(_nLeft != false){
					$('.multi-tabs').stop().animate({left:Math.min(_nLeft,0)+"px"},'slow',function(){
						toggleArrows();
					});					
				}else{
					toggleArrows();
				}
				
			}
			reAdjust();
			
			$(window).on('resize',function(e){
				reAdjust();
			});
			
			$('.scroller-right').click(function() {
				if(!$('.multi-tabs').is(':animated')){
					$('.scroller-left').fadeIn('slow');
					var _maxLeft = widthOfList() - ($('.multi-tabs-outer').outerWidth() - 20);
					var _nLeft = getLeftPosi() - 125;
					if(getLeftPosi() == 0) _nLeft = _nLeft - 20;
					$('.multi-tabs').stop().animate({left:Math.max(_nLeft,-_maxLeft)+"px"},200,function(){
						toggleArrows();
					});
				}
			});
			
			$('.scroller-left').click(function() {
				if(!$('.multi-tabs').is(':animated')){
					var _nLeft = getLeftPosi() + 125;
					$('.scroller-right').fadeIn('slow');
					$('.multi-tabs').stop().animate({left:Math.min(_nLeft,0)+"px"},200,function(){
						toggleArrows();
					});
				}
			});
			
			$('.multi-tabs-outer').on(wheelEvent, function(e) {
				var oEvent = e.originalEvent,
				delta  = oEvent.deltaY || oEvent.wheelDelta;
				if (($('.multi-tabs-outer').outerWidth()) < widthOfList()) {
					if (delta > 0) {
						if($('.scroller-left').is(':visible')){
							e.preventDefault();
							e.stopPropagation();
							$('.scroller-left').trigger('click');
						}
					} else {
						if($('.scroller-right').is(':visible')){
							e.preventDefault();
							e.stopPropagation();
							$('.scroller-right').trigger('click');
						}
					}
				}
			});
		}
	}
	$('.change_tab_setting').change(function(e) {
        //console.log(this.checked)
		$.ajax({
			url: '/admin/dashboard.html',
			data: {__action:'tab-switch',__value:this.checked},
			success: function(data){
				window.location.reload(true);
			}
		});
    });	
	
	$(document).on('focus','.searchGlobal',function(e){
		$(".search-result").show();
	});	
	
	$(document).on('click','.btnCustomWidth',function(e){
		$(this).parent().removeClass('open');
	});
	
	$(document).mouseup(function (e){
    	var container = $(".search-result, .dropdown-menu, .searchGlobal");
		var chatContainer = $(".icnChat, .sidebarRight");
		var colorContainer = $(".colorPic, .selColorTheme");
		var dropContainer = $(".btnCustomWidth");

		if (!container.is(e.target) && container.has(e.target).length === 0){
			if(inIframe()){
				parent.hideSearchPanel();
			}else{
				$(".search-result").hide();
			}
		}
		
		//for header navigation drop down
		if (!dropContainer.is(e.target) && dropContainer.has(e.target).length === 0){
			if(inIframe()){
				parent.hideDropdown();
			}else{
			 	$(".btnCustomWidth").parent().removeClass('open');
			}
		}
		
		// for chat sidebar
		if (!chatContainer.is(e.target) && chatContainer.has(e.target).length === 0){
			if(inIframe()){
				parent.hideChatBar();
			}else{
			 	$(".sidebarRight").removeClass('show-from-right');
			}
		}
		
		//for color panel
		if (!colorContainer.is(e.target) && colorContainer.has(e.target).length === 0){
			if(inIframe()){
				parent.hideColorPanel();
			}else{
			 	$(".selColorTheme").removeClass('show-theme-selector');
			}
		}
	});
	
	
	window._aClick = function(_this,e){
            
        console.log(_this);
         var texttoshow = $(e.target).text();
       // var somedata=_this.toString();
       // alert(somedata);
     //   var templabel = somedata.substr(val.indexOf("<title>") + 1);
        //console.log(text);
        var url_target = '';
        var _href = $(_this).attr('href');
        var url_target = $(_this).attr('target');
        var download = $(_this).attr('download');

		if(typeof download == 'undefined' && typeof _href != 'undefined' && _href.indexOf('#') != 0 && _href.indexOf('java') != 0 && !$(_this).hasClass('tab0') && (_href != '' || _href != '#')){
			e.preventDefault();
			if(url_target == '_blank'){
				alert(111);
				var win = window.open(_href, '_blank');
				return false;
			}
			
			if($('.tab[data-url="'+_href+'"]').length > 0){
				_switchtab($('.tab[data-url="'+_href+'"]'));
				//$('.multiTabCont[data-url="'+_href+'"]').html('Refreshing.....');
				$('.multiTabCont[data-url="'+_href+'"] iframe').attr( 'src', function () {
					return $('.multiTabCont[data-url="'+_href+'"] iframe')[0].src;
				});
			}else if($(_this).hasClass('tab-current')){
				$('.tab.active,.multiTabCont.active').attr('data-url',_href);
				$('.multiTabCont.active').html('Loading.....');
			}else{
				$('.multi-tabs .tab,.multiTabCont').removeClass('active');
				var _t_tTmpl = _tTmpl;
                               
                                var arr = _href.split('/');
                              //  alert(arr);
                                var last_element = arr[arr.length - 1];
				var _t_TaTi = last_element.replace('.html','');
                             //   _t_TaTi = _t_TaTi.replace('/admin/','');
			//	_t_TaTi = 
				_t_TaTi = _t_TaTi.replace(/\_/g,' ');
				_t_tTmpl = _t_tTmpl.replace('TITLE',texttoshow);
				_t_tTmpl = _t_tTmpl.replace('URL',_href);
				var _t__cTmpl = _cTmpl;
				 _t__cTmpl = _t__cTmpl.replace('URL',_href);
				$('.multi-tabs').append(_t_tTmpl);
				$('.multiTabContent').append(_t__cTmpl);
				_resizeTabs();
				reAdjust();
				var _ifrmurl = '';
				if (_href.indexOf("?") > -1){
					_ifrmurl = _href + '&ifrm=1';
				}else{
					_ifrmurl = _href + '?ifrm=1';
				}
				$('.multiTabCont[data-url="'+_href+'"]').html('<iframe class="cnt-iframe" src="'+_ifrmurl+'"></iframe>');
				$('.multiTabCont[data-url="'+_href+'"]').find('iframe').load(function(e) {
					var ifrm = this;
					setTimeout(function(){
						var doc = ifrm.contentDocument? ifrm.contentDocument: ifrm.contentWindow.document;
						ifrm.style.visibility = 'hidden';
						ifrm.style.height = "10px"; // reset to minimal height ...
						// IE opt. for bing/msn needs a bit added or scrollbar appears
						ifrm.style.height = getDocHeight( doc ) + 4 + "px";
						ifrm.style.visibility = 'visible';
					},100);
					$(_this).contents().find("body").addClass("iframe-loaded");
				});
			}
			/*$.ajax({url:_href,method:"get",data:{'_ajax':true},success:function(data){
				$('.multiTabCont[data-url="'+_href+'"]').html($(data).find('.multiTabCont.active').html());
				$('.multiTabCont[data-url="'+_href+'"]').find('.ckeditor').each(function(index, element) {
					//CKEDITOR.replace(this);
				});
			}});*/
		}
				
	};

	
});