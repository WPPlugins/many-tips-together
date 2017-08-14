<?php
/**
 * PLUGINS options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'plugins' );

$options_panel->Title( __( 'Plugins', 'mtt' ) );


$options_panel->addCheckbox( 
    'plugins_block_update_notice', 
    array(
        'name' => __( 'Block plugins upgrade check', 'mtt' ),
        'desc' => __( 'Not recommended, use by your own.', 'mtt' ),
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'plugins_block_update_inactive_plugins', 
    array(
        'name' => __( 'Block inactive plugins upgrade check', 'mtt' ),
        'desc' => __( "You'd probably be better deleting them alltogether. Doesn't work in Multisite.", 'mtt' ),
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'plugins_remove_plugin_edit', 
    array(
        'name' => __( 'Remove plugins quick edit links (activate/deactivate, edit, settings)', 'mtt' ),
        'desc' => __( 'You will have to work only with batch actions, but you can achieve the smallest plugin listing,', 'mtt' ),
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'plugins_remove_description', 
    array(
        'name' => __( 'Remove descriptions but leave Version and Author information.', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'plugins_remove_plugin_notice', 
    array(
        'name' => __( 'Remove extra plugins notices (normally in yellow)', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'plugins_add_last_updated', 
    array(
        'name' => __( 'Adds information about last update of the plugins', 'mtt' ),
        'desc' => sprintf( 
				__( 'Incorporation of the plugin: %s. After enabling this, the first load of the plugins page may take a while for the information be retrieved. After the first load, the information is cached for 24 hours.', 'mtt' ), 
				B5F_MTT_Utils::make_tip_credit( 'Plugin Last Updated', 'http://wordpress.org/extend/plugins/plugin-last-updated/' ) 
		) ,
        'std'  => false
    )
);


$options_panel->addColor( 
    'plugins_inactive_bg_color', 
    array(
        'name' => __( 'Inactive Plugins background color', 'mtt' ),
        'desc' => '' 
    )
);


$My_plugins_color[] = $options_panel->addText( 
    'names', 
    array( 
        'name'=> __("Enter a list of keywords separated by comma, no spaces. Normally, you'll use the plugin authors.", 'mtt'), 
        'std'=> '',
    ),
    true
);
$My_plugins_color[] = $options_panel->addColor( 
    'color', 
    array(
        'name' => __( 'Your Plugins background color ;o)', 'mtt' ),
        'desc' => '' 
    ), 
    true
);
$My_plugins_color[] = $options_panel->addCheckbox( 
    'display_count', 
    array(
        'name' => __( 'Display count', 'mtt' ),
        'desc' => '',
        'std'  => false
    ),
	true
);
$options_panel->addCondition( 
    'plugins_my_plugins_bg_color',
    array(
      'name'	=> __('Colorize specific plugins.', 'mtt'),
      'desc' 	=> __('Use to display some plugins (yours!) with other color.', 'mtt'),
      'fields' 	=> $My_plugins_color,
      'std' 	=> false,
	  'validate_func' => 'validate_colorize_plugins'
    ) 
);



$options_panel->CloseTab();
