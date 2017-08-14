/**
 * Not Enough jQuery :)
 *
 * @package ManyTipsTogether
 */


/**
 * Close update message
 * 
 * @returns void
 */
function close_update_msg()
{
    window.setTimeout( function()
    {
        jQuery( "#alert_bar" ).slideUp( "slow" )
    },15000
    );

}

jQuery(document).ready( function( $ ) 
{
    /**
     * Used for window resize
     * @type number
     */
    var origWidth;

    /**
     * Swap visibility of help fields
     * 
     * @param bool how
     * @returns void
     */
    var toggle_help = function( how )
    {
        if( 'none' == how)
            $(".desc-field").fadeOut();
        else
            $( ".desc-field" ).fadeIn( 'slow' );
    }


    /**
     * Prepare resize
     * /
    origWidth = $(window).width();  //store the window's width when the document loads
    $( 'div.mtt-box' ).delay(900).fadeTo( 'slow',1 );
    window.setTimeout(function()
    {
        $(window).resize();
    },800
    );*/
    

    /**
     * Resize function
     * /
    $(window).resize(function() 
    {
        var curWidth = $('#wpcontent').width();//$(window).width(); //store the window's current width
        var delta = ( curWidth- origWidth );

        if( curWidth < 1140 )
        {
            if ($("#ozhmenu_wrap").length > 0){
                // do something here
                $( 'div.mtt-box' ).css( {
                    'position':'initial', 
                    'float':'left', 
                    'width':'800px', 
                    'margin':'0 0 10px 14px'
                } );
            } else {
                $( 'div.mtt-box' ).css( {
                    'position':'initial', 
                    'float':'left', 
                    'width':'800px', 
                    'margin':'0 0 10px 8px',
                    'right': 'auto'
                } );
                $('div.wrap').css({'margin-top':'155px'});
            }
            $( 'div.mtt-box .footer ul.right, #mtt-other-info' ).hide();
        }
        else
        {
            $( 'div.mtt-box' ).css({
                'position':'fixed', 
                'float':'right', 
                'width':'23%', 
                'margin':'36px 0 0 0',
                'right': '1%'
            });
            $('div.wrap').css({'margin-top':'auto'});
            $( 'div.mtt-box .footer ul.right, #mtt-other-info' ).show();
        }
        origWidth = curWidth;
    });*/
    
    /**
     * Initial check for help visibility
     */
    if ($( 'input[name="mtt_verbose_plugin[]"]' ).is( ':checked' ) ) 
    {
        toggle_help( 'none' );
        $( '#mtt_verbose_plugin_helper' ).attr( 'checked', true );
    } 
    else 
    {
        toggle_help( 'block' );
        $( '#mtt_verbose_plugin_helper' ).attr( 'checked', false);
    }
         
    /**
     * Control checkbox for plugin help visibility
     */
    $("#mtt_verbose_plugin_helper").change(function () 
    {
        if ($(this).is( ':checked')) 
            toggle_help( 'none' );
        else 
            toggle_help( 'block' );

        $( 'input[name="mtt_verbose_plugin[]"]' ).trigger( 'click' );
    });
      
    /**
     * Initial check for AdminBar Submenus visibility
     */
    if ($( '#adminbar_custom_enable' ).is( ':checked' ) ) 
    {
        $( '#adminbar_custom_submenus' ).parent().show();
    } 
    else 
    {
        $( '#adminbar_custom_submenus' ).parent().hide();
    }
         
    /**
     * Control checkbox for AdminBar Submenus visibility
     */
    $("#adminbar_custom_enable").change(function () 
    {
        if ($(this).is( ':checked')) 
            $( '#adminbar_custom_submenus' ).parent().show();
        else 
            $( '#adminbar_custom_submenus' ).parent().hide();
    });
      
    /**
     * Admin Page Class : Scroll to top when swapping tabs
     */ 
    $(".panel_menu li").on("click", function(event)
    {
            $("html,body").animate({scrollTop:0}, 500);
    });
          
    /**
     * Custom submit form button outside of APC
     */
    $( '#mtt-submit' ).click(function()  
    {
        document.admin_page_class.submit();
        return false;
    });
    
    /**
     * Go to brasofilo ;)
     */
    $("#bsf-link").click(function () 
    {
        window.open( 'http://brasofilo.com' );
        return false;
    });
    
    /**
     * Thickbox with plugin info
     */
    $("#open-tb").click(function() 
    {  
        tb_show( mtt.title, mtt.network + "plugin-install.php?tab=plugin-information&plugin=many-tips-together&section=changelog&TB_iframe=true" );
        return false;
    });
       
    /**
     * TODO: remove this fix after Issue#32 has been solved in bainternet's APC
     */
    function b5f_fixCodeMirror() { $(window).resize(); }

    $(".panel_menu li").on("click", function(event){
            var who = $(this).attr('class');
            if( 
                who.indexOf("profile") === -1 
                && who.indexOf("logout") === -1 
                && who.indexOf("appearance") === -1 
                && who.indexOf("maintenance") === -1 
            )
                    return;

            setTimeout( b5f_fixCodeMirror, 1000 );
    });

});