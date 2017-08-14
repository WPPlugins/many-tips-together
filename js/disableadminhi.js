/**
 * Disable hoverintent
 * 
 * Author: @t3los
 * Author URI: http://wordpress.stackexchange.com/a/36360/12615
 */

jQuery(document).ready(function($){
    $('#wpadminbar').find('li.menu-top').hover( function(){
        $(this).toggleClass('hover');
    });
    // Bring menu into scope(defined by common.js in wordpress)
    var menu;
    // Copy of the function from common.js, just without hoverIntent
    $('li.wp-has-submenu', menu).hover(
        function(e){
            var b, h, o, f, m = $(this).find('.wp-submenu'), menutop, wintop, maxtop;

            if ( !$(document.body).hasClass('folded') && $(this).hasClass('wp-menu-open') )
                return;

            menutop = $(this).offset().top;
            wintop = $(window).scrollTop();
            maxtop = menutop - wintop - 30; // max = make the top of the sub almost touch admin bar

            b = menutop + m.height() + 1; // Bottom offset of the menu
            h = $('#wpwrap').height(); // Height of the entire page
            o = 60 + b - h;
            f = $(window).height() + wintop - 15; // The fold

            if ( f < (b - o) )
                o = b - f;

            if ( o > maxtop )
                o = maxtop;

            if ( o > 1 )
                m.css({'marginTop':'-'+o+'px'});
            else if ( m.css('marginTop') )
                m.css({'marginTop':''});

            m.addClass('sub-open');
        },
        function(){
            $(this).find('.wp-submenu').removeClass('sub-open');
        }
    );
});