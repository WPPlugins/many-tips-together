<?php
/**
 * DASHBOARD options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'dashboard' );

$options_panel->Title( __( 'Dashboard', 'mtt' ) );

$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'REMOVE DASHBOARD WIDGETS', 'mtt' ) ) );

$options_panel->addCheckboxList( 'dashboard_remove', array(
    'quick_press'     => __( 'QuickPress', 'mtt' ),
    'activity'        => __( 'Activity', 'mtt' ),
    'right_now'       => __( 'At a Glance', 'mtt' ),
    'primary'         => __( 'WordPress Blog', 'mtt' ),
    'welcome'         => __( 'Welcome Panel', 'mtt' ),
        ), array(
    'name' => __( 'Remove default items', 'mtt' ),
    'desc' => '',
    'class' => 'no-toggle',                
    'std'  => false
        )
);



$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'ADD CUSTOM WIDGETS', 'mtt' ) ) );
$repeater_fields[] = $options_panel->addText( 'title', array( 
	'name' => __( 'Title', 'mtt' ) 
	), true 
);
$repeater_fields[] = $options_panel->addTextarea( 'content', array(
	'name' => __( 'Content ', 'mtt' ) 
	), true 
);
$repeater_fields[] = $options_panel->addRoles('roles', 
		array('type' => 'checkbox_list'), 
		array( 
			'name'=> __('Show to roles','apc'), 
			'class' => 'no-fancy',
			'desc'  => __( 'Leave empty to show to all.', 'mtt' )
			), 
			
		true
);

$repeater_fields[] = $options_panel->addCheckbox( 'enabled', array(
	'name'=> __( 'Enable this widget', 'mtt' )
	),true
);

$options_panel->addRepeaterBlock( 'dashboard_add_widgets', array( 
	'sortable'	 => true, 
	'inline'	 => false,
	'name'		 => __( 'Custom widgets', 'mtt' ), 
	'fields'	 => $repeater_fields,
	'desc'		 => __( 'Add as many as you want.', 'mtt' )
));

if( !is_multisite() ){
	$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'EXTRA WIDGETS', 'mtt' ) ) );
	$options_panel->addCheckboxList( 
	    'dashboard_folder_size', 
	    array(
	        'root'     => __( 'Root', 'mtt' ),
	        'wpcontent'  => __( 'WP Content', 'mtt' )
	    ), 
	    array(
	        'name' => __( 'Calculate Folders Size', 'mtt' ),
	        'desc' => __( 'The root calculation is usefull when WP is installed at the root of the server.', 'mtt' ),
	        'class' => 'no-toggle',                
	        'std'  => false
	    )
	);
}

$options_panel->CloseTab();