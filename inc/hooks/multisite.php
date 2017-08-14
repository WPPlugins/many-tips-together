<?php
/**
 * Multisite hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Multisite {
	// store the options
	protected $params;

	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct( $options ) {
		$this->params = $options;

		# ACTIVE PLUGINS WIDGET
		if( !empty( $options['multisite_active_plugins_widget'] ) ) {
			require_once( 'class-widget-active-plugins.php' );
            new MTT_Active_Plugins_Dashboard_Widget();
		}

		# SITES COLUMNS - ID, BLOGNAME, USER+ROLE
		if(
				!empty( $options['multisite_site_id_column'] ) 
				or !empty( $options['multisite_blogname_column'] )
				or !empty( $options['multisite_user_role_column'] )
				or !empty( $options['multisite_blog_size_column'] )
				or isset( $options['multisite_extra_quick_edit']['enabled'] )
		) {
			require_once( 'class-multisite-custom-columns.php' );
            new MTT_Multisite_Custom_Columns( $options );
		}

		# REDIRECT TO SITE SETTINGS AFTER CREATING A NEW SITE
		if( !empty( $options['multisite_redirect_new_site'] ) )
			add_action(
					'load-site-new.php', array( $this, 'redirect_after_site_creation' )
			);

		# REMOVE DASHBOARD WIDGETS
		if( !empty( $options['multisite_dashboard_remove'] ) )
			add_action(
					'wp_network_dashboard_setup', array( $this, 'dashboard_widgets' )
			);

		# SORT SITES NAMES IN ADMIN MENU AND USERS SITES
		if( !empty( $options['multisite_sort_sites_names'] ) )
			add_filter(
					'get_blogs_of_user', array( $this, 'reorder_users_sites' ), 0
			);		
	}


	/**
	 * Redirect after site creation
	 * 
	 */
	public function redirect_after_site_creation() {
		if( !isset( $_GET['update'] ) || 'added' != $_GET['update'] )
			return;

		wp_redirect( network_admin_url( 'site-info.php?id=' . $_GET['id'] ) );
		exit;
	}


	/**
	 * Redirect archived/inactive sites
	 * 
	 */
	public function redirect_archived_sites() {
		if( current_user_can( 'manage_network' ) )
			return;
			
		$blog = get_blog_details();
		if(
			'1' == $blog->deleted 
			or '2' == $blog->deleted
			or '1' == $blog->archived 
			or '1' == $blog->spam
		) {
		    wp_redirect( network_site_url() );
			exit();
		}
	}


	/**
	 * Remove dashboar widgets
	 * 
	 * http://helen.wordpress.com/2011/08/01/customizing-the-special-multisite-dashboards/
	 */
	public function dashboard_widgets() {
		if( in_array( 'right_now', $this->params['multisite_dashboard_remove'] ) )
			remove_meta_box( 'network_dashboard_right_now', 'dashboard-network', 'normal' );

		if( in_array( 'plugins', $this->params['multisite_dashboard_remove'] ) )
			remove_meta_box( 'dashboard_plugins', 'dashboard-network', 'normal' );

		if( in_array( 'primary', $this->params['multisite_dashboard_remove'] ) )
			remove_meta_box( 'dashboard_primary', 'dashboard-network', 'side' );

		if( in_array( 'secondary', $this->params['multisite_dashboard_remove'] ) )
			remove_meta_box( 'dashboard_secondary', 'dashboard-network', 'side' );
	}


	/**
	 * Reorder sites listing in menu and sites of user
	 * 
	 * @param type $blogs
	 * @return type
	 */
	public function reorder_users_sites( $blogs ) {
		if( !did_action( 'wp_before_admin_bar_render' ) )
			uasort( $blogs, array( $this, 'bf_uasort_by_blogname' ) );
		else
			uasort( $blogs, array( $this, 'bf_uasort_by_domain' ) );

		return $blogs;
	}


	/**
	 * Sort by domain
	 * 
	 * @param type $a
	 * @param type $b
	 * @return type
	 */
	private function bf_uasort_by_domain( $a, $b ) {
		return strcasecmp( $a->domain, $b->domain );
	}


	/**
	 * Sort by blogname
	 * @param type $a
	 * @param type $b
	 * @return type
	 */
	private function bf_uasort_by_blogname( $a, $b ) {
		return strcasecmp( $a->blogname, $b->blogname );
	}


}