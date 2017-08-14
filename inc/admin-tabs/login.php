<?php
/**
 * LOGIN and LOGOUT options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'login_logout' );

$options_panel->Title( __( 'Login and Logout', 'mtt' ) );

/***********************************
 *          REDIRECTION
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __('REDIRECTION', 'mtt') ) );

$Login_redirect_fields[] = $options_panel->addText( 'url', 
    array( 
        'name'=> __('URL to redirect to', 'mtt'), 
        'std'=> '',
    ),
    true
);
$options_panel->addCondition( 'login_redirect_enable',
    array(
      'name'	=> __('Redirect login', 'mtt'),
      'desc' 	=> __('The default behavior is being redirected to the Dashboard (index.php).', 'mtt'),
      'fields' 	=> $Login_redirect_fields,
      'validate_func' => 'validate_loginout_url',
      'std' 	=> false
    ) 
);

$Logout_redirect_fields[] = $options_panel->addText( 'url', 
    array( 
        'name'=> __('URL to redirect to', 'mtt'), 
        'std'=> '',
    ),
    true
);
$options_panel->addCondition( 'logout_redirect_enable',
    array(
      'name'	=> __('Redirect logout', 'mtt'),
      'desc' 	=> __('The default behavior is being redirected to the login page...', 'mtt'),
      'fields' 	=> $Logout_redirect_fields,
      'validate_func' => 'validate_loginout_url',
      'std' 	=> false
    ) 
);


/***********************************
 *          ERRORS
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __('ERRORS', 'mtt') ) );

$Login_error_fields[] = $options_panel->addText( 'msg_login', 
    array( 
        'name'=> __('Custom error message', 'mtt'), 
        'desc'=> __("Don't use html code.", 'mtt'), 
        'std'=> ''
    ), true
);

$options_panel->addCondition( 'loginpage_errors',
    array(
        'name'=> __('Remove error message', 'mtt'), 
        'desc'=> __('Don\'t reveal what\'s the mistake, user or password', 'mtt'), 
		'fields' 	=> $Login_error_fields,
		'validate_func' => 'validate_html_text',
		'std' 	=> false
    ) 
);

$options_panel->addCheckbox( 'loginpage_disable_shaking', 
    array( 
        'name'=> __('Disable the login box shaking for the errors and other notices.', 'mtt'), 
        'desc'=> '', 
        'std' => false
    )
);


/***********************************
 *          LOGO
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __('LOGO', 'mtt') ) );

$options_panel->addText( 'loginpage_logo_tooltip', 
    array( 
        'name'=> __('Title for logo', 'mtt'), 
        'desc'=> __('Appears as tooltip.<br />The default text is: "Powered by WordPress"', 'mtt'),
        'std'=> '' ,
		'validate_func' => 'validate_html_text'
	)
);

$options_panel->addText( 'loginpage_logo_url', 
    array( 
        'name'=> __('Link for the logo (full URL)', 'mtt'), 
        'desc'=> __('Link for the logo, default: http://wordpress.org', 'mtt'),
        'validate_func' => 'validate_simple_full_url',
        'std'=> '' 
    ) 
);

$options_panel->addImage( 'loginpage_logo_img', 
    array( 
        'name'=> __('Logo image', 'mtt'),
        'desc'=> __('Select an image from your library or upload a new one', 'mtt'),
        'preview_height' => 'auto', 
        'preview_width' => '140px' 
    ) 
);

$options_panel->addText( 'loginpage_logo_height', 
    array( 
        'name'=> __('Logo height', 'mtt'), 
        'desc'=> __('Default: 67 - maximum value recomended:  300px', 'mtt'),
        'validate_func' => 'validate_number',
        'std'=> '' 
    ) 
);



/***********************************
 *          BOX
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __('BOX', 'mtt') ) );

$options_panel->addText( 'loginpage_form_width', 
    array( 
        'name'=> __('Width', 'mtt'), 
        'desc'=> __('The logo width is limited by this one', 'mtt'), 
        'validate_func' => 'validate_number',
        'std'=> '' 
    ) 
);

$options_panel->addText( 'loginpage_form_height', 
    array( 
        'name'=> __('Height', 'mtt'), 
        'desc'=> '', 
        'validate_func' => 'validate_number',
        'std'=> '' 
    ) 
);

$options_panel->addText( 'loginpage_form_rounded', 
    array( 
        'name'=> __('Rounded corners', 'mtt'), 
        'desc'=> '', 
        'validate_func' => 'validate_number',
        'std'=> '' 
    ) 
);

$options_panel->addCheckbox( 'loginpage_form_border', 
    array( 
        'name'=> __('Remove border', 'mtt'), 
        'desc'=> '', 
        'std' => false
    )
);


$options_panel->addColor( 'loginpage_form_bg_color', 
    array( 
        'name'=> __('Background color', 'mtt'), 
        'desc'=> ''
    ) 
);

$options_panel->addImage( 'loginpage_form_bg_img', 
    array( 
        'name'=> __('Background image', 'mtt'),
        'desc'=> '', 
        'preview_height' => 'auto', 
        'preview_width' => '140px' 
    ) 
);



/***********************************
 *          BACKGROUND
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __('BACKGROUND', 'mtt') ) );

$options_panel->addImage( 'loginpage_body_img', 
    array( 
        'name'=> __('Background image (full URL)', 'mtt'),
        'preview_height' => 'auto', 
        'preview_width' => '140px' 
    ) 
);

$options_panel->addColor( 'loginpage_body_color', array( 'name'=> __('Background color', 'mtt') ) );

$options_panel->addSelect( 'loginpage_body_position', 
    array( 
        'empty'         => '',
        'left_top'      => 'left top', 
        'left_center'   => 'left center', 
        'left_bottom'   => 'left bottom', 
        'right_top'     => 'right top', 
        'right_center'  => 'right center', 
        'right_bottom'  => 'right bottom', 
        'center_top'    => 'center top', 
        'center_center' => 'center center', 
        'center_bottom' => 'center bottom'
    ), 
    array( 
        'name'=> __('Background position', 'mtt'), 
        'class' => 'no-fancy',
        'std'=> array( 'selectkey1' ) 
    )
);

$options_panel->addSelect( 'loginpage_body_repeat', 
    array( 
        'empty'     => '',
        'repeat'    => 'repeat',
        'no-repeat' => 'no-repeat' 
    ), 
    array( 
        'name'=> __('Background repeat', 'mtt'), 
        'class' => 'no-fancy',
        'std'=> array( 'selectkey1' ) 
    )
);

$options_panel->addSelect( 'loginpage_body_attachment', 
    array( 
        'empty'  => '',
        'fixed'  => 'fixed', 
        'scroll' => 'scroll', 
    ), 
    array( 
        'name'=> __('Background scroll', 'mtt'), 
        'class' => 'no-fancy',
        'std'=> array( 'selectkey1' ) 
    )
);


 /***********************************
 *     PASSWORD/BACK-TO-SITE
 ***********************************/
$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __('PASSWORD AND BACK TO SITE', 'mtt') 
    ) 
);

$options_panel->addCheckbox( 'loginpage_backsite_hide', 
    array( 
        'name'=> sprintf( __('Hide link "Back to %s"', 'mtt'), get_bloginfo('name') ),
        'desc'=> __('You can use the logo for that.', 'mtt'), 
        'std' => false
    )
);


/***********************************
 *          CSS
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __('CSS', 'mtt') ) );

$options_panel->addCheckbox( 'loginpage_remove_css', 
    array( 
        'name'=> __('Completely remove WordPress styles in Login page.', 'mtt'), 
        'desc'=> __( 'Paste your full CSS bellow.', 'mtt' ), 
        'std' => false
    )
);

$options_panel->addCode( 'loginpage_extra_css', 
    array( 
        'name'=> __('Extra CSS for the final touches', 'mtt'),
        'desc'=> '', 
        'std'    => '.class-name {}',
        'theme'  => 'dark',
        'syntax' => 'css' 
    ) 
);



$options_panel->CloseTab();
