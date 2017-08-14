<?php
/**
 * Admin Menu options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'admin_menus' );

$options_panel->Title( __( 'Admin Menus', 'mtt' ) );

$options_panel->addParagraph( sprintf( 
        '<p class="menu-refresh-notice">%s</p>', 
        __( "The options to hide/show/modify menu items need a second refresh of the plugin's page.", 'mtt' )
        ) 
);


if( !$this->link_manager )
{
	$options_panel->addParagraph( sprintf( 
	        '<h4 class="h3-mtt">%s</h4>', 
	        __( 'OLD LINKS MANAGER', 'mtt' ))
		);
	$options_panel->addCheckbox( 'admin_menus_enable_link_manager', array(
			'name' => __('Enable Link Manager', 'mtt'),
			'desc' => __( 'The link manager was disabled by default in WordPress 3.5, this will bring it back.', 'mtt' ),
			'std'  => false
			)
	);

}


$options_panel->addParagraph( sprintf( 
        '<hr /><h4 class="h3-mtt">%s</h4><p style="font-weight:normal;font-style:italic">%s</p>', 
        __( 'REMOVE MENU ITEMS', 'mtt' ),
        __( 'The following simply removes the menus for all users.<br>Note that this doesn\'t prevent the access using the actual url address of the item.<br/>For a better fine tuning use <a target="_blank" href="http://wordpress.org/extend/plugins/adminimize/">Adminimize</a>. And to really block the access use <a target="_blank" href="http://wordpress.org/extend/plugins/members/">Members</a> or <a target="_blank" href="http://wordpress.org/extend/plugins/user-role-editor/">User-Role-Editor</a>', 'mtt' )
        ) 
);


$remove_menus = isset( $this->params['admin_menu_removable_items'] ) 
	? $this->params['admin_menu_removable_items'] 
	: array();
$base_array = array(
    'index'      => __( 'Dashboard' ),
    'posts'      => __( 'Posts' ),
    'media'      => __( 'Media' ),
    'links'      => __( 'Links' ),
    'pages'      => __( 'Pages' ),
    'comments'   => __( 'Comments' ),
    'appearence' => __( 'Appearence' ),
    'plugins'    => __( 'Plugins' ),
    'users'      => __( 'Users' ),
    'tools'      => __( 'Tools' ),
);
if( !$this->link_manager )
	unset ( $base_array['links'] );

$base_array = array_merge( $base_array, $remove_menus );
$options_panel->addCheckboxList( 'admin_menus_remove', $base_array, 
     array(
        'name' => __( 'Select items to remove:', 'mtt' ),
        'desc' => '',
        'class' => 'no-toggle',
        'std'  => false
     )
);


$options_panel->addCheckbox( 'admin_menus_sort_wordpress', array(
        'name' => __( 'Sort WordPress items in Settings menu [A-Z]', 'mtt' ),
        'desc' => '',
        'std'  => false
        )
);

$options_panel->addCheckbox( 'admin_menus_sort_plugins', array(
        'name' => __( 'Sort Plugins items in Settings menu [A-Z]', 'mtt' ),
        'desc' => '',
        'std'  => false
        )
);

$options_panel->addCheckbox( 'admin_menus_sort_cpts', array(
        'name' => __( 'Sort Post Types', 'mtt' ),
        'desc' => __( 'Join all post types first, then show Links, Media and Comments', 'mtt' ),
        'std'  => false
        )
);


$menu_post_types = isset( $this->params['admin_menu_post_types'] ) 
	? $this->params['admin_menu_post_types'] 
	: false;
$menu_post_status = isset( $this->params['admin_menu_post_status'] ) 
	? $this->params['admin_menu_post_status'] 
	: array();

if( $menu_post_types )
{
	foreach( $menu_post_types as $pt )
		$cpt_list[$pt] = ucwords( $pt );
	foreach( $menu_post_status as $ps )
		$cps_list[$ps] = ucwords( $ps );
	
	$options_panel->addParagraph( 
		sprintf( '<hr /><h4 class="h3-mtt">%s</h4>',
			__( 'POST STATUS BUBBLES', 'mtt' )
		)
	);
	$bubbles[] = $options_panel->addCheckboxList( 'cpt', $cpt_list, 
		array(
		   'name' => __( 'Select types:', 'mtt' ),
		   'desc' => '',
		   'class' => 'no-toggle',
		   'std'  => false
		), true
    );
	$bubbles[] = $options_panel->addSelect( 'status', $cps_list, 
		array(
		   'name' => __( 'Select status:', 'mtt' ),
		   'desc' => '',
		   'std'  => false
		), true
    );
	$options_panel->addCondition( 
		'admin_menus_bubbles', 
		array(
			'name' => __( 'Enable', 'mtt' ),
			'desc' => sprintf(
		          __( 'Tip via: %s.%s', 'mtt' ), 
	              B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/95058/12615' ),
				"<div class='img-help'><img src='{$plugin_url}images/adminmenus-bubbles.jpg' /></div>"
             ),
			'fields' 	=> $bubbles,
			'std' 	=> false
		)
	);
}


if ( function_exists( 'get_fields' )  ):

    $options_panel->addParagraph( 
        sprintf( '<hr /><h4 class="h3-mtt">%s</h4>',
            __( 'ADVANCED CUSTOM FIELDS', 'mtt' )
        )
    );


    $users_arr = B5F_MTT_Utils::get_users_array();
	
	// ACF CPT PAGE
    if( $users_arr )
    {
        $ACF_hide_from_users[] = $options_panel->addSelect( 
            'for_user', 
            $users_arr, 
            array(
                'name' => __( 'Select authorized user.', 'mtt' ),
                'desc' => '',
                'std'  => array( 'none' )
            ),
            true
        );        
        $options_panel->addCondition( 
            'plugins_acf_show_only', 
            array(
                'name' => __( 'Advanced Custom Fields: show ACF menu only for one user.', 'mtt' ),
                'desc' => '',
                'fields' 	=> $ACF_hide_from_users,
                'std' 	=> false
            )
        );
    }        

    // ACF OPTIONS ADDON PAGE
    if( class_exists( 'acf_options_page_plugin' ) )
    {
        if( $users_arr )
        {
            $ACF_hide_options_from_users[] = $options_panel->addSelect( 
                'for_user', 
                $users_arr, 
                array(
                    'name' => __( 'If you want to show only for one user, select bellow.', 'mtt' ),
                    'desc' => __( 'If none is selected, the Options are only hidden from non-administrators.', 'mtt' ),
                    'std'  => array( 'none' )
                ),
                true
            );        
            $options_panel->addCondition( 
                'plugins_acf_hide_options', 
                array(
                    'name' => __( 'Advanced Custom Fields: hide "Options" from non-administrators', 'mtt' ),
                    'desc' => '',
                    'fields' 	=> $ACF_hide_options_from_users,
                    'std' 	=> false
                )
            );
        }        
    }
endif;


$options_panel->addParagraph( 
        sprintf( '<hr /><h4 class="h3-mtt">%s</h4><p>%s</p>',
            __( 'RENAME POSTS', 'mtt' ), 
            __( 'to whatever you want (i.e. news, articles)', 'mtt' ) 
        )
);


$Post_rename_fields[] = $options_panel->addText( 'name', 
    array( 
        'name'=> __('Name', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 'singular_name', 
    array( 
        'name'=> __('Singular Name', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 'add_new', 
    array( 
        'name'=> __('Add New', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 'edit_item', 
    array( 
        'name'=> __('Edit Posts', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 'view_item', 
    array( 
        'name'=> __('View Posts', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 'search_items', 
    array( 
        'name'=> __('Search Posts', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 'not_found', 
    array( 
        'name'=> __('No Posts found', 'mtt'), 
        'std'=> ''
    ),
    true
);
$Post_rename_fields[] = $options_panel->addText( 
    'not_found_in_trash', 
    array( 
        'name'=> __('No Posts found in trash', 'mtt'), 
        'std'=> ''
    ),
    true
);
$options_panel->addCondition( 
    'posts_rename_enable',
    array(
        'name'	=> __('Enable renaming the word "Posts"', 'mtt'),
        'desc' 	=> sprintf(
              __( 'Maybe you prefer it to be called News or Articles and don\'t want to create a Custom Post Type for that.<br />Tip via: %s', 'mtt' ), 
              B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/9224/12615' )
              ),
        'fields' 	=> $Post_rename_fields,
        'std' 	=> false,
		'validate_func' => 'validate_post_renaming'
    )
);  


$options_panel->CloseTab();