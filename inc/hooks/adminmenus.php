<?php
/**
 * Admin Menu hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Adminmenus
{
	// store the options
	protected $params;

	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct( $options )
	{

		$this->params = $options;

		// DISABLE HOVER INTENT / MP6 DISABLED
		if( 
			!empty( $options['admin_menus_hoverintent'] ) 
			&& !defined( 'MP6' )
			)
			add_action( 'admin_head', array( $this, 'hoverintent' ) );

		// ENABLE LINK MANAGER
		if( !empty( $options['admin_menus_enable_link_manager'] ) )
			add_filter( 'pre_option_link_manager_enabled', '__return_true' );

		// REMOVE ITEMS
		if( !empty( $options['admin_menus_remove'] ) )
			add_action( 'admin_menu', array( $this, 'remove_items' ), 999999 );

		// BUBBLES
		if( !empty( $options['admin_menus_bubbles']['enabled'] )
				&& !empty( $options['admin_menus_bubbles']['cpt'] ) )
			add_action( 'admin_menu', array( $this, 'add_bubbles' ) );

		// SORT SETTINGS
		if( 
			!empty( $options['admin_menus_sort_wordpress'] )
			|| !empty( $options['admin_menus_sort_plugins'] ) 
			)
			add_action( 'admin_menu', array( $this, 'sort_settings' ), 15 );

		// SORT CPTS
		if( !empty( $options['admin_menus_sort_cpts'] ) )
			add_action( 'admin_menu', array( $this, 'sort_cpts' ), 9999 );

		// RENAME POSTS
		if( !empty( $options['posts_rename_enable']['enabled'] ) )
		{
			add_action( 'init', array( $this, 'object_label' ), 0 );
			add_action( 'admin_menu', array( $this, 'menu_label' ), 0 );
		}

		// HIDE ADVANCED CUSTOM FIELDS
		if ( isset( $options['plugins_acf_show_only']['enabled'] ) )
		{
			add_action( 'admin_menu', array( $this, 'acf_hide_main' ), 15 );
			
			// TODO: recheck this, there are a couple of fixes in #18857
			// http://core.trac.wordpress.org/ticket/23297
			//foreach( array( 'edit.php', 'post.php' ) as $hook )
			add_action( 
					'admin_head', //-' . $hook, 
					array( $this, 'acf_prevent_main_access' )
			);
		}

		// HIDE ADVANCED CUSTOM FIELDS OPTIONS ADD-ON
		if ( isset( $options['plugins_acf_hide_options']['enabled'] ) )
		{
			add_action( 'admin_menu', array( $this, 'acf_hide_options' ), 15 );
			add_action( 
					'admin_head-toplevel_page_acf-options', 
					array( $this, 'acf_prevent_options_access'), 
					15 
			);
		}
	}

	
	public function add_bubbles()
	{
		global $menu;
		$bubles = $this->params['admin_menus_bubbles'];
		foreach( $bubles['cpt'] as $pt )
		{
			$cpt_count = wp_count_posts( $pt );

			if( isset( $cpt_count->$bubles['status'] ) )
			{
				$suffix = ( 'post' == $pt ) ? '' : "?post_type=$pt";
				$key = B5F_MTT_Utils::recursive_array_search( "edit.php$suffix", $menu );

				if( !$key )
					return;

				$menu[$key][0] .= sprintf(
						'<span class="update-plugins count-%1$s">
						<span class="plugin-count">%1$s</span>
					</span>', $cpt_count->$bubles['status']
				);
			}
		}
	}
	
	
	
	/**
	 * TODO: docs
	 * @global type $current_user
	 * @return type
	 */
	public function acf_hide_options()
	{
		global $current_user;
		get_currentuserinfo();

		if( !current_user_can('edit_users') )
		{
			remove_menu_page( 'acf-options' ); 
			return;
		}

		if( 'none' == $this->params['plugins_acf_hide_options']['for_user'] )
			return;


		if( $this->params['plugins_acf_hide_options']['for_user'] != $current_user->user_login )
		{
			remove_menu_page( 'acf-options' ); 
		}

	}
	
	
	/**
	 * TODO: docs
	 * @return type
	 */
	function acf_prevent_options_access()
	{
		if( !current_user_can('edit_users') )
		{
			wp_redirect( admin_url() ); 
			exit;
		}

		if( 'none' == $this->params['plugins_acf_hide_options']['for_user'] )
			return;
		elseif( $current_user->user_login != $this->params['plugins_acf_hide_options']['for_user'] )
		{
			wp_redirect( admin_url() ); 
			exit;
		}
	}
	
	
	/**
	 * TODO: docs
	 * @global type $current_user
	 */
	public function acf_hide_main()
	{
		global $current_user;
		get_currentuserinfo();

		if( $this->params['plugins_acf_show_only']['for_user'] != $current_user->user_login )
		{
				remove_menu_page( 'edit.php?post_type=acf' ); 
		}

	}
	
	
	/**
	 * TODO: docs
	 * @global type $current_screen
	 * @global type $current_user
	 * @return type
	 */
	function acf_prevent_main_access()
	{

		global $current_screen, $current_user;

		$find_settings_page = strpos( $current_screen->id, 'page_acf-settings' );

		// Only if correct pages
		if( 'acf' == $current_screen->post_type || $find_settings_page )
		{
			// Authorized user, exit earlier
			if( $current_user->user_login == $this->params['plugins_acf_show_only']['for_user'] )
				return;

			// User not authorized to access page, redirect to dashboard
			wp_redirect( admin_url() ); 
			exit;
		}
	}
	
	
	/**
	 * Block menu hover intent, speeds up the menu
	 */
	public function hoverintent()
	{
		wp_enqueue_script(
				'disable-admin-hoverintent', B5F_MTT_Init::get_instance()->plugin_url . 'js/disableadminhi.js', array( ), time()
		);
	}


	/**
	 * Remove menu items
	 */
	public function remove_items()
	{
        $items = array( 
            'index' => 'index.php', 
            'posts' => 'edit.php', 
            'media' => 'upload.php', 
            'links' => 'link-manager.php', 
            'pages' => 'edit.php?post_type=page', 
            'comments' => 'edit-comments.php', 
            'appearence' => 'themes.php', 
            'plugins' => 'plugins.php', 
            'users' => 'users.php', 
            'tools' => 'tools.php'
        );
        foreach( $items as $key => $page )
            if( in_array( $key, $this->params['admin_menus_remove'] ) )
                remove_menu_page( $page );
		
		if( isset( $this->params['admin_menu_removable_items'] ) && !empty( $this->params['admin_menu_removable_items'] ))
		{
			foreach( $this->params['admin_menu_removable_items'] as $k => $v )
			{
				
				if( in_array( $k, $this->params['admin_menus_remove'] )) {
					remove_menu_page( $k );
				}
			}
		}
	}


	/**
	 * Sort items in Settings menu
	 * - WordPress and Plugins are dealed separatedly
	 * http://wordpress.stackexchange.com/q/2331/12615
	 * 
	 */
	public function sort_settings() 
	{
		global $submenu;

		if( !isset( $submenu['options-general.php'] ) )
			return;

		// Sort default items
		$default = array_slice( $submenu['options-general.php'], 0, 6, true );
		if( !empty( $this->params['admin_menus_sort_wordpress'] ) )
			usort( $default, array( $this, 'sort_arra_asc' ) );

		// Sort rest of items
		$length = count( $submenu['options-general.php'] );
		$extra = array_slice( $submenu['options-general.php'], 6, $length, true );

		if( !empty( $this->params['admin_menus_sort_plugins'] ) )
			usort( $extra, array( $this, 'sort_arra_asc' ) );

		// Apply
		$sep = array( array( '<b style="opacity:.3;">. . . . . . . . . . . . .</b>',  'manage_options', '#'));
		$submenu['options-general.php'] = array_merge( $default, $sep, $extra );
	}


	/**
	 * Sort menu: first post types, then links, media and comments
	 * 
	 * Since 2.1
	 * @global type $menu
	 */
	public function sort_cpts() 
	{
		global $menu;

		$separator = B5F_MTT_Utils::recursive_array_search( 'separator2', $menu );

		$mod_menu = array();

		$links = B5F_MTT_Utils::recursive_array_search( 'link-manager.php', $menu );
		if( $links )
		{
			$mod_menu['links'] = $menu[ $links ];
			unset( $menu[ $links ] );
		}

		$upload = B5F_MTT_Utils::recursive_array_search( 'upload.php', $menu );
		if( $upload )
		{
			$mod_menu['upload'] = $menu[ $upload ];
			unset( $menu[ $upload ] );
		}

		$comments = B5F_MTT_Utils::recursive_array_search( 'edit-comments.php', $menu );
		if( $comments )
		{
			$mod_menu['comments'] = $menu[ $comments ];
			unset( $menu[ $comments ] );
		}

		$position_menu = (int)$separator - count( $mod_menu );
		foreach( $mod_menu as $m )
		{
			$menu[ $position_menu ] = $m;
			$position_menu++;
		}
	}
	
	/**
	 * Sort array by sub-value
	 * http://stackoverflow.com/a/1597788/1287812
	 * 
	 */
	private function sort_arra_asc( $item1, $item2 )
	{
		if ($item1[0] == $item2[0]) return 0;
		return ( $item1[0] > $item2[0] ) ? 1 : -1;
	}


	/**
	 * Rename "Posts" in the global scope
	 * 
	 * @global type $wp_post_types
	 */
	public function object_label()
	{
		global $wp_post_types;

		$labels = &$wp_post_types['post']->labels;

		if ( !empty( $this->params['posts_rename_enable']['name'] ) )
			$labels->name = $this->params['posts_rename_enable']['name'];

		if ( !empty( $this->params['posts_rename_enable']['singular_name'] ) )
			$labels->singular_name = $this->params['posts_rename_enable']['singular_name'];

		if ( !empty( $this->params['posts_rename_enable']['add_new'] ) )
		{
			$labels->add_new      = $this->params['posts_rename_enable']['add_new'];
			$labels->add_new_item = $this->params['posts_rename_enable']['add_new'];
		}

		if ( !empty( $this->params['posts_rename_enable']['edit_item'] ) )
			$labels->edit_item = $this->params['posts_rename_enable']['edit_item'];

		if ( !empty( $this->params['posts_rename_enable']['name'] ) )
			$labels->new_item = $this->params['posts_rename_enable']['name'];

		if ( !empty( $this->params['posts_rename_enable']['view_item'] ) )
			$labels->view_item = $this->params['posts_rename_enable']['view_item'];

		if ( !empty( $this->params['posts_rename_enable']['search_items'] ) )
			$labels->search_items = $this->params['posts_rename_enable']['search_items'];

		if ( !empty( $this->params['posts_rename_enable']['not_found'] ) )
			$labels->not_found = $this->params['posts_rename_enable']['not_found'];

		if ( !empty( $this->params['posts_rename_enable']['not_found_in_trash'] ) )
			$labels->not_found_in_trash = $this->params['posts_rename_enable']['not_found_in_trash'];
	}


	/**
	 * Rename "Posts" in the Admin Menu
	 * 
	 * @global type $menu
	 * @global type $submenu
	 */
	public function menu_label()
	{
		global $menu, $submenu;

		if ( !empty( $this->params['posts_rename_enable']['name'] ) )
			$menu[5][0]                 = $this->params['posts_rename_enable']['name'];

		if ( !empty( $this->params['posts_rename_enable']['name'] ) )
			$submenu['edit.php'][5][0]  = $this->params['posts_rename_enable']['name'];

		if ( !empty( $this->params['posts_rename_enable']['add_new'] ) )
			$submenu['edit.php'][10][0] = $this->params['posts_rename_enable']['add_new'];       
	}
	
}