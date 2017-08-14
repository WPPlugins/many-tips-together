<?php
/**
 * MAINTENANCE MODE options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'maintenance' );

$options_panel->Title( __( 'Maintenance Mode', 'mtt' ) );

$Maintenance_mode_fields[] = $options_panel->addCheckboxList( 'other_options', array(
    'only_admin'      => __( 'Only block backend access' ),
     ), 
     array(
        'name' => __( 'Other options', 'mtt' ),
        'desc' => '',
        'class' => 'no-toggle',
        'std'  => false
     ), true
);

$Maintenance_mode_fields[] = $options_panel->addRoles( 'level', array( 
        'type' => 'select'
        ), 
        array(
        'name' => __( 'Define minimum user level to access site.', 'mtt' ),
        'desc' => '',
        'class'=> 'no-fancy'
        ), true
);


$Maintenance_mode_fields[] = $options_panel->addText( 'title', array(
        'name' => __( 'Browser Title &lt;title&gt;', 'mtt' ),
        'desc' => '',
        'std'  => ''
        ), true
);

$Maintenance_mode_fields[] = $options_panel->addText( 'line0', array(
        'name' => __( 'Text for the first line', 'mtt' ),
        'desc' => '',
        'std'  => ''
        ), true
);

$Maintenance_mode_fields[] = $options_panel->addText( 'line1', array(
        'name' => __( 'Text for the second line', 'mtt' ),
        'desc' => '',
        'std'  => ''
        ), true
);

$Maintenance_mode_fields[] = $options_panel->addText( 'line2', array(
        'name' => __( 'Link for the third line, without http://', 'mtt' ),
        'desc' => '',
        'std'  => ''
        ), true
);

$Maintenance_mode_fields[] = $options_panel->addImage( 'html_img', array(
        'name'           => __( 'Page background image (full URL)', 'mtt' ),
        'desc'           => __( 'Use a pattern or a big image, or enter 0 (zero) to disable', 'mtt' ),
        'preview_height' => 'auto',
        'preview_width'  => '140px'
        ), true
);

$Maintenance_mode_fields[] = $options_panel->addImage( 'body_img', array(
        'name'           => __( 'Box background image (full URL)', 'mtt' ),
        'desc'           => __( 'Use a pattern or a big image', 'mtt' ),
        'preview_height' => 'auto',
        'preview_width'  => '140px'
        ), true
);

$Maintenance_mode_fields[] = $options_panel->addCode( 'extra_css', array(
        'name'   => __( 'Custom CSS', 'mtt' ),
        'desc'   => __( 'Add your custom css to further stylize the Maintenance page', 'mtt' ),
        'std'    => '.class-name {}',
        'theme'  => 'dark',
        'syntax' => 'css'
        ), true
);

$options_panel->addCondition( 'maintenance_mode_enable', array(
        'name'   => __( 'Enable maintenance mode', 'mtt' ),
        'desc'   => __( 'Block the site to visitors and the dashboard to non administrators.', 'mtt' ),
        'fields' => $Maintenance_mode_fields,
        'std'    => false,
		'validate_func' => 'validate_html_text'
        )
);


$options_panel->CloseTab();