/*
	http://stackoverflow.com/questions/13029090/jquery-equal-height-responsive-div-rows
	-User: dclawson
*/

$.fn.eqHeights = function(options) {

    var defaults = {  
        child: false 
    };  
    var options = $.extend(defaults, options); 

    var el = $(this);
    if (el.length > 0 && !el.data('eqHeights')) {
        $(window).bind('resize.eqHeights', function() {
            el.eqHeights();
        });
        el.data('eqHeights', true);
    }

    if( options.child && options.child.length > 0 ){
        var elmtns = $(options.child, this);
    } else {
        var elmtns = $(this).children();
    }

    var prevTop = 0;
    var max_height = 0;
    var elements = [];
    elmtns.height('auto').each(function() {
        $(this).removeClass('align-bottom');
        var thisTop = this.offsetTop;

        if (prevTop > 0 && prevTop != thisTop) {
            $(elements).height(max_height);
            max_height = $(this).height();
            elements = [];
        }
        max_height = Math.max(max_height, $(this).height());

        prevTop = this.offsetTop;
        elements.push(this);
    });

    $(elements).height(max_height);
    $(elements).addClass('align-bottom');
};