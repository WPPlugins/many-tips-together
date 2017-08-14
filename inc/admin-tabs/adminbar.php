<?php
/**
 * Admin Bar options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'admin_bar' );

$options_panel->Title( __( 'Admin Bar', 'mtt' ) );

$options_panel->addParagraph( sprintf( 
        '<p class="menu-refresh-notice">%s</p>', 
        __( "The options to hide/show/modify menu items need a second refresh of the plugin's page.", 'mtt' )
        )
);

$options_panel->addCheckbox( 
    'adminbar_completely_disable', 
    array(
        'name' => __( 'Completely remove the Admin Bar', 'mtt' ),
        'desc' => sprintf( __( 'Remove from back and front end. Creates a "Visit Site" link in the Dashboard menu item. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/77648/12615' ) ),
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'adminbar_disable', 
    array(
        'name' => __( 'Disable Admin Bar for all users in Frontend', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);

$yoast = array();
if( defined('WPSEO_URL') )
    $yoast = array( 'seo_by_yoast' => __( 'SEO by Yoast', 'mtt' ) );


$adminbar_remove_defaults = array(
    'wp_logo'       => __( 'WP logo', 'mtt' ),
    'site_name'     => __( 'Site name', 'mtt' ),
    'updates'       => __( 'Updates', 'mtt' ),
    'comments'      => __( 'Comments', 'mtt' ),
    'new_content'   => __( 'New content', 'mtt' ),
    'theme_options' => __( 'Theme options', 'mtt' ),
    'my_account'    => __( 'My account', 'mtt' ),
);

$adminbar_remove_array = array_merge( $adminbar_remove_defaults, $yoast );

$options_panel->addCheckboxList( 
    'adminbar_remove', 
    $adminbar_remove_array, 
    array(
        'name' => __( 'Remove default items', 'mtt' ),
        'desc' => '',
        'class' => 'no-toggle',
        'std'  => false
    )
);


$Howdy_change[] = $options_panel->addText( 
    'howdy', 
    array(
        'name' => __( 'Replace with', 'mtt' ),
        'desc' => __( 'Leave empty for complete removal', 'mtt' ),
        'std'  => ''
    ), true
);


$options_panel->addCondition( 
    'adminbar_howdy_enable', 
    array(
        'name'   => __( 'Remove or change "Howdy"', 'mtt' ),
        'desc'   => '',
        'fields' => $Howdy_change,
        'std'    => false,
	    'validate_func' => 'validate_adminbar'
    )
);

$Adminbar_sitename_fields[] = $options_panel->addText( 
    'title', 
    array(
        'name' => __( 'Title', 'mtt' ),
        'desc' => '',
        'std'  => ''
    ), true
);
$Adminbar_sitename_fields[] = $options_panel->addImage( 
    'icon', 
    array(
        'name'           => __( 'Icon (between 16x16 and 22x22 pixels)', 'mtt' ),
        'std'            => '',
        'desc'           => '',
        'preview_height' => 'auto',
        'preview_width'  => '140px'
    ), true
);
$Adminbar_sitename_fields[] = $options_panel->addText( 
    'url', 
    array(
        'name' => __( 'URL', 'mtt' ),
        'desc' => '',
        'std'  => ''
    ), true
);
$options_panel->addCondition( 
    'adminbar_sitename_enable', 
    array(
        'name'   => __( 'Add Site Name with Icon', 'mtt' ),
        'desc'   => __( 'Add a custom link with title and icon', 'mtt' ),
        'fields' => $Adminbar_sitename_fields,
        'validate_func' => 'validate_adminbar',
        'std'    => false
    )
);

$Adminbar_custom_menu_fields[] = $options_panel->addText( 
    'bar_0_title', 
    array(
        'name' => __( 'Menu name', 'mtt' ),
        'desc' => __( '*Required', 'mtt' ),
        'std'  => ''
    ), true
);
$Adminbar_custom_menu_fields[] = $options_panel->addText( 
    'bar_0_url', 
    array(
        'name' => __( 'Menu link', 'mtt' ),
        'desc' => __( 'If empty, makes a null link', 'mtt' ),
        'std'  => ''
    ), true
);


$options_panel->addCondition( 
    'adminbar_custom_enable', 
    array(
        'name'   => __( 'Enable Custom Menu', 'mtt' ),
        'desc'   => '',
        'fields' => $Adminbar_custom_menu_fields,
        'validate_func' => 'validate_adminbar',
        'std'    => false
    )
);

$repeater_menus[] = $options_panel->addText( 'title', array( 
	'name' => __( 'Title', 'mtt' ) 
	), true 
);
$repeater_menus[] = $options_panel->addText( 'url', array(
	'name' => __( 'URL ', 'mtt' ) 
	), true 
);
$repeater_menus[] = $options_panel->addRoles('roles', 
		array('type' => 'checkbox_list'), 
		array( 
			'name'=> __('Show to roles','apc').'<br>', 
			'class' => 'no-fancy',
			'desc'  => __( 'Leave empty to show to all.', 'mtt' )
			), 
			
		true
);

$options_panel->addRepeaterBlock( 'adminbar_custom_submenus', array( 
	'sortable'	 => true, 
	'inline'	 => false,
	'name'		 => __( 'Add submenus', 'mtt' ), 
	'fields'	 => $repeater_menus,
	'desc'		 => __( 'Add as many as you want.', 'mtt' )
));

$options_panel->CloseTab();