<?php
/**
 * General hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_General {
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

		// REMOVE/MODIFY WP VERSION
		if(
				!empty( $options['wpdisable_version_full'] )
				or !empty( $options['wpdisable_version_number'] )
		)
			add_filter(
					'the_generator', array( $this, 'remove_version' )
			);

		// HIDE UPDATE BUBLE IN DASHBOARD MENU
		if( !empty( $options['wpblock_update_wp'] ) && !empty( $options['wpblock_update_plugins'] ) )
			add_action(
					'admin_menu', array( $this, 'hide_update_bubble' )
			);

		// HIDE WP UPDATE NOTICE FOR NON-ADMINS
		if( !empty( $options['wpblock_update_wp'] ) )
			add_action(
					'admin_init', array( $this, 'hide_wp_update_nag' )
			);

		// HIDE WP UPDATE NOTICE
		if( !empty( $options['wpblock_update_wp_all'] ) )
			add_action(
					'admin_init', array( $this, 'hide_wp_update_nag_all' )
			);

		// REDIRECT FROM UPDATED SCREEN
		if( !empty( $this->params['wpblock_update_screen'] ) )
			add_action(
					'admin_head-about.php', array( $this, 'redirect_update_screen' )
			);

		// HIDE BLOG URL FROM WORDPRESS 'PHONE HOME'
		if( !empty( $options['wpdisable_nourl'] ) ) {
			add_filter(
					'http_headers_useragent', array( $this, 'phone_home_disable' )
			);
			add_filter(
					'http_request_args', array( $this, 't5_anonymize_ua_string' )
			);
		}

		// ADD ID AND POST COUNT TO TAXONOMIES
		if( !empty( $options['wptaxonomy_columns'] ) )
			add_action(
					'admin_init', array( $this, 'tax_ids_make' )
			);

		// REMOVE SMART QUOTES
		if( !empty( $options['wpdisable_smartquotes'] ) ) {
			remove_filter( 'comment_text', 'wptexturize' );
			remove_filter( 'the_content', 'wptexturize' );
			remove_filter( 'the_excerpt', 'wptexturize' );
			remove_filter( 'the_title', 'wptexturize' );
			remove_filter( 'the_content_feed', 'wptexturize' );
		}

		// REMOVE CAPITAL P
		if( !empty( $options['wpdisable_capitalp'] ) ) {
			remove_filter( 'the_content', 'capital_P_dangit' );
			remove_filter( 'the_title', 'capital_P_dangit' );
			remove_filter( 'comment_text', 'capital_P_dangit' );
		}

		// REMOVE AUTO P
		if( !empty( $options['wpdisable_autop'] ) )
			remove_filter( 'the_content', 'wpautop' );

		// REMOVE WP FROM TITLE
		if( !empty( $options['wpdisable_wptitle'] ) )
			add_filter( 'admin_title', array( $this, 'remove_admin_title' ), 10, 2 );

		// DISABLE SELF PING
		if( !empty( $options['wpdisable_selfping'] ) )
			add_action(
					'pre_ping', array( $this, 'no_self_ping' )
			);

		// REDIRET HOME ON ACCESS DENIED
		if( !empty( $options['wpdisable_redirect_disallow'] ) )
			add_action(
					'admin_page_access_denied', array( $this, 'access_denied' )
			);

		// DELAY RSS PUBLISH UPDATE
		if( !empty( $this->params['wprss_delay_publish_enable']['enabled'] ) ) {
			if( !empty( $this->params['wprss_delay_publish_enable']['time'] ) )
				add_filter(
						'posts_where', array( $this, 'rss_delay_publish' )
				);
		}

		// REMOVE SUFFIX FROM ENQUEUED STYLES AND SCRIPTS
		if( !empty( $options['wpdisable_scripts_versioning'] ) ) {
            add_filter( 'style_loader_src', array( $this, 't5_remove_version' ) );
            add_filter( 'script_loader_src', array( $this, 't5_remove_version' ) );
        }

	}


	/**
	 * Modify site generator
	 * 
	 * @return string
	 */
	public function remove_version() {
		if( !empty( $this->params['wpdisable_version_full'] ) )
			return;
		elseif( !empty( $this->params['wpdisable_version_number'] ) )
			return '<meta name="generator" content="WordPress" />';

		return;
	}


	/**
	 * Hide update bubble
	 * 
	 * @global string $submenu
	 */
	public function hide_update_bubble() {
		global $submenu; //$menu,

		if( isset( $submenu['index.php'][10] ) )
			$submenu['index.php'][10][0] = 'Updates';
	}


	/**
	 * Hide WordPress update notice for non-admins
	 * http://wordpress.stackexchange.com/a/13002/12615
	 * 
	 * @return void
	 */
	public function hide_wp_update_nag() {
		! current_user_can( 'install_plugins' ) 
			and remove_action( 'admin_notices', 'update_nag', 3 );
	}


	/**
	 * Hide WordPress update notice for everyone
	 */
	public function hide_wp_update_nag_all() {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}


	/**
	 * Redirect from update screen
	 * 
	 * @return void
	 */
	public function redirect_update_screen() {
		if( !isset( $_GET['updated'] ) )
			return;

		wp_redirect( admin_url('update-core.php') );
		exit;
	}


	/**
	 * Disable phone home
	 * 
	 * @global type $wp_version
	 * @param type $default
	 * @return string
	 */
	public function phone_home_disable( $default ) {
		global $wp_version;
		return 'WordPress/' . $wp_version;
	}


	/**
	 * Change admin <title>
	 * http://wordpress.stackexchange.com/a/17034/12615
	 * 
	 * @param string $admin_title
	 * @param string $title
	 * @return strin
	 */
	public function remove_admin_title( $admin_title, $title ) {
		if ( is_network_admin() )
			$adm_title = __( 'Network Admin' );
		elseif ( is_user_admin() )
			$adm_title = __( 'Global Dashboard' );
		else
			$adm_title = get_bloginfo( 'name' );

		if ( $adm_title == $title )
			$adm_title = $title;
		else
			$adm_title = sprintf( 
				__( '%1$s &lsaquo; %2$s' ), 
				$title, 
				$adm_title 
			);

		return $adm_title;
	}


	/**
	 * Replace the UA string.
	 * http://wordpress.stackexchange.com/a/64053/12615
	 *
	 * @param  array $args Request arguments
	 * @return array
	 */
	function t5_anonymize_ua_string( $args ) {
		global $wp_version;
		$args['user-agent'] = 'WordPress/' . $wp_version;

		// catch data set by wp_version_check()
		if( isset( $args['headers']['wp_install'] ) ) {
			$args['headers']['wp_install']	 = 'http://example.com';
			$args['headers']['wp_blog']		 = 'http://example.com';
		}
		return $args;
	}


	/**
	 * No self-ping
	 * 
	 * @param type $links
	 * @return void
	 */
	public function no_self_ping( &$links ) {
		$home = home_url();
		foreach( $links as $l => $link )
			if( 0 === strpos( $link, $home ) )
				unset( $links[$l] );
	}


	/**
	 * Modify RSS update period
	 * 
	 * @global object $wpdb
	 * @param string $where
	 * @return string
	 */
	public function rss_delay_publish( $where ) {
		global $wpdb;
		
		if( is_feed() )
		{
			$now	 = gmdate( 'Y-m-d H:i:s' );
			$wait	 = $this->params['wprss_delay_publish_enable']['time']; // integer
			// http://dev.mysql.com/doc/refman/5.0/en/date-and-time-public functions.html#public function_timestampdiff
			$device	 = $this->params['wprss_delay_publish_enable']['period']; // MINUTE, HOUR, DAY, WEEK, MONTH, YEAR
			// add SQL-syntax to default $where
			$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
		}
		return $where;
	}


	/**
	 * Redirect unauthorized access attempts
	 * 
	 * @return void
	 */
	public function access_denied() {
		wp_redirect( home_url() );
		exit();
	}



	/**
	 * Add hook for taxonomy ID columns
	 */
	public function tax_ids_make() {
		foreach( get_taxonomies() as $taxonomy )
		{
			add_action( "manage_edit-${taxonomy}_columns", array( $this, 't5_add_col' ) );
			add_filter( "manage_edit-${taxonomy}_sortable_columns", array( $this, 't5_add_col' ) );
			add_filter( "manage_${taxonomy}_custom_column", array( $this, 't5_show_id' ), 10, 3 );
		}
		add_action( 'admin_print_styles-edit-tags.php', array( $this, 't5_tax_id_style' ) );
	}


	/**
	 * Register custom ID column
	 * @param type $columns
	 * @return type
     * 
     * @author toscho
	 */
	public function t5_add_col( $columns ) {
		$in = array( "tax_id" => "ID" );
		$columns = B5F_MTT_Utils::array_push_after( $columns, $in, 0 );
		return $columns;
	}


	/**
	 * Display custom ID/Post column
	 * 
	 * @global type $wp_list_table
	 * @param type $v
	 * @param type $name
	 * @param type $id
	 * @return type
     * 
     * @author toscho
	 */
	public function t5_show_id( $v, $name, $id ) {
		global $wp_list_table;
		return 'tax_id' === $name ? $id : $v;
	}


	/**
	 * Print taxonomy columns style
     * 
     * @author toscho
	 */
	public function t5_tax_id_style() {
		print '<style>#tax_id{width:4em}</style>';
	}

    /**
     * Remove versioning from enqueued scripts
     * 
     * @param string $url
     * @return string
     * 
     * @author toscho
     */
    public function t5_remove_version( $url ) {
        return remove_query_arg( 'ver', $url );
    }
}
