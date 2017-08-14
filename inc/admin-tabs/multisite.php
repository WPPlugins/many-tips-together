<?php
/**
 * MULTISITE options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'multisite' );

$options_panel->Title( __( 'Multisite', 'mtt' ) );


$options_panel->addParagraph( 
    sprintf( 
        '%s<br /><strong>%s</strong>',
        __( 'This section only appears in the main site and for super-admins.', 'mtt'),
        __( 'All settings applied in the Plugins section are also applied in the network screen.', 'mtt') 
    ) 
);

$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __( 'SITE COLUMNS', 'mtt' ) 
    ) 
);

$options_panel->addCheckbox( 
    'multisite_site_id_column', 
    array(
        'name' => __('Site ID', 'mtt'),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'multisite_blogname_column', 
    array(
        'name' => __('Site Name', 'mtt'),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'multisite_themes_column', 
    array(
        'name' => __('Show theme used by site.', 'mtt'),
        'desc' => __('Also shows parent/child.', 'mtt'),
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'multisite_blog_size_column', 
    array(
        'name' => __('Show the space used by each site.', 'mtt'),
        'desc' => '',
        'std'  => false
    )
);

$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __( 'UTILS', 'mtt' ) 
    ) 
);



$quick_edit[] = $options_panel->addCheckboxList( 
    'remove', 
    array(
        'backend' => __( 'Dashboard', 'mtt' ),
        'visit' => __( 'Visit', 'mtt' ),
        'edit' => __( 'Edit', 'mtt' ),
        'spam' => __( 'Spam', 'mtt' ),
        'delete' => __( 'Delete', 'mtt' ),
        'activate' => __( 'Activate', 'mtt'),
        'deactivate' => __( 'Deactivate', 'mtt'),
        'archive' => __( 'Archive', 'mtt' ),
        'unarchive' => __( 'Unarchive', 'mtt' )
    ), 
    array(
       'name' => __( 'Also remove these actions:', 'mtt' ),
       'desc' => __( 'This feature also blocks attempts to access the URL directly', 'mtt' ),
       'class' => 'no-toggle',
       'std'  => false
    ), true
);

$options_panel->addCondition( 
    'multisite_extra_quick_edit', 
    array(
        'name' => __( 'Add Users/Themes/Settings to each site quick links', 'mtt' ),
        'desc' => __( 'Fast access to the site details edit tabs.', 'mtt' ),
        'fields' 	=> $quick_edit,
        'std' 	=> false
    )
);



$options_panel->addCheckbox( 
    'multisite_user_role_column', 
    array(
        'name' => __( 'Modify Users column to display the role of each user in each site.', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);
$options_panel->addCheckbox( 
		'multisite_redirect_new_site', 
        array(
        'name' => __('Redirect to site details after new site creation.', 'mtt'),
        'desc' => __( 'The default behavior is to stay in the same screen', 'mtt'),
        'std'  => false
        )
);


$options_panel->addCheckbox( 
    'multisite_sort_sites_names', 
    array(
        'name' => __( 'Sort sites by name and domain.', 'mtt' ),
        'desc' => sprintf( __( 'Sorted by name in the Admin Bar, and by domain in Sites of User. This is a hook into get_blogs_of_user, and I\'m not sure if it has adverse effects elsewhere. Feedback is welcome, use the WPSE post. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/77812/12615' ) ),
        'std'  => false
    )
);

$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __( 'DASHBOARD WIDGETS', 'mtt' ) 
    ) 
);

$options_panel->addCheckbox( 
    'multisite_active_plugins_widget', 
    array(
        'name' => __('Active theme and plugins widget', 'mtt'),
        'desc' => __('Lists all network activated plugins, and all sites with their plugins.','mtt'),
        'std'  => false
    )
);

if( is_multisite() ){
	//$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'EXTRA WIDGETS', 'mtt' ) ) );
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

$options_panel->addCheckboxList( 
    'multisite_dashboard_remove', 
    array(
        'right_now'       => __( 'Right now', 'mtt' ),
        'primary'         => __( 'WordPress News', 'mtt' )
    ), 
    array(
        'name' => __( 'Remove dashboard widgets', 'mtt' ),
        'desc' => sprintf( __( 'WordPress Blog and Other WP News titles and feed addresses can be configured. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'Helen on WordPress', 'http://helen.wordpress.com/2011/08/01/customizing-the-special-multisite-dashboards/' ) ),
        'class' => 'no-toggle',                
        'std'  => false
    )
);



$options_panel->CloseTab();