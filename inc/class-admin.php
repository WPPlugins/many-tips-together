<?php
/**
 * Main Class
 *
 * @package ManyTipsTogether
 */
class B5F_MTT_Admin {
	/**
	 * Options default
	 * Configuration of Admin Class
	 * 
	 * @type array 
	 */
	public $config = array(
		'menu'			 => 'settings'
		, 'page_title'	 => 'Admin Tweaks'
		, 'capability'	 => 'import'
		, 'option_group'	 => 'ManyTipsTogether'
		, 'id'			 => 'mtt_page'
		, 'fields'		 => array( )
		, 'local_images'	 => true
		, 'use_with_theme' => false
	);
	
	/**
	 * File names for hooks and tabs
	 * 
	 * @var array 
	 */
	private $plugin_sections = array(
		  'adminbar'
		, 'adminmenus'
		, 'appearance'
		, 'dashboard'
		, 'general'
		, 'login'
		, 'maintenance'
		, 'media'
		, 'plugins'
		, 'postediting'
		, 'postlisting'
		, 'profile'
		, 'shortcodes'
		, 'widgets'
	);
	
	/** 
	 * Control extra menus to be removed
	 * @var array 
	 */
	private $default_menus = array( 'index.php', 'edit.php', 'upload.php', 'link-manager.php', 'edit.php?post_type=page', 'edit-comments.php', 'themes.php', 'plugins.php', 'users.php', 'tools.php', 'options-general.php',  'acf-options',  'edit.php?post_type=acf' );


	
	/** 
	 * Control extra widgets to be removed
	 * @var array 
	 */
	private $default_widgets = array( 
		'pages'           => 'WP_Widget_Pages', 
		'calendar'        => 'WP_Widget_Calendar', 
		'archives'        => 'WP_Widget_Archives', 
		'meta'            => 'WP_Widget_Meta', 
		'links'			  => 'WP_Widget_Links',
		'search'          => 'WP_Widget_Search', 
		'text'            => 'WP_Widget_Text', 
		'categories'      => 'WP_Widget_Categories', 
		'recent_posts'    => 'WP_Widget_Recent_Posts', 
		'recent_comments' => 'WP_Widget_Recent_Comments', 
		'rss'             => 'WP_Widget_RSS', 
		'tag_cloud'       => 'WP_Widget_Tag_Cloud', 
		'nav_menu'        => 'WP_Nav_Menu_Widget'
	);


	
	/**
	 * Admin Page Class instance
	 * @var object 
	 */
	public $options_class;

	
	/**
	 * Options internal holder
	 * 
	 * @type array 
	 */
	public $params = array( );
	
	
	/**
	 * Multisite special condition for this plugin
	 * 
	 * @var boolean 
	 */
	public $multisite = false;

	
	/**
	 * Plugin title for Thickbox
	 *
	 * @since 1.0
	 * @type string
	 */
	public static $mtt_tb_title	 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :o[)';

	/**
	 * Used when building the Options page
	 * 
	 * @var boolean 
	 */
	private $is_link_manager;
	
	
	/**
	 * Constructor
	 *
	 * @wp-hook plugins_loaded
	 * @return  void
	 */
	public function __construct( $options ) {
		global $wp_version;
		$this->params		 = $options;
		# Handle link manager inclusion
		$this->link_manager  = get_option('link_manager_enabled');
		if( version_compare( $wp_version, '3.5', '<' ) )
				$this->link_manager = true;
        # Our ms conditions
		$this->multisite	 = is_multisite() 
				? ( is_super_admin() && is_main_site() ) 
				: false;
		if( $this->multisite ) {
			$this->plugin_sections = array_merge( 
					$this->plugin_sections, 
					array( 'multisite' ) 
			);
			$this->config['capability'] = 'manage_network';
		}
		# Hooks in MTT page
		if( $this->our_page() ) {
			# MTT custom sidebar
			add_action( 
					'admin_page_class_before_page', 
					array( 'B5F_MTT_MetaBox', 'mtt_meta_box' ) 
			);
			# Validation
			add_filter( 
					'apc_validattion_class_name', 
					array( $this, 'set_validation_class' ), 
					10, 2 
			);
			add_action( 'admin_menu',array( $this, 'get_menu_information' ), 999999 );
			add_action( 'widgets_init',array( $this, 'get_widgets_information' ), 100 );
            # Remove backwpup from roles list
            global $wpdb;
            $opt = $wpdb->get_blog_prefix().'user_roles';
            add_filter( "option_$opt", array( $this, 'filter_backwpup_roles' ) );
		}
		# Enqueue scripts and other hooks
		add_action( 'admin_init', array( $this, 'mtt_admin_init' ) );
		# Admin page
		$this->build_admin();
		# Load all classes
		foreach( $this->plugin_sections as $section )
			require_once ('hooks/' . $section . '.php');
        # Fire hooks
		$this->build_hooks();
	}


	/**
	 * Validation Class to be used by APC
	 * 
	 * @param string $class
	 * @return string
	 */
	public function set_validation_class( $validationClassName, $obj ) {
		if( strpos($obj->_Slug,'admin_tweaks') !== false ) 
			 return 'B5F_MTT_Validate';
		return $validationClassName;
	}


	/**
	 * Build admin page
	 *
	 * @wp-hook plugins_loaded
	 * @since   2012.12.21
	 * @return  void
	 */
	public function build_admin() {
		# Initiate the admin page
		$options_panel = new BF_Admin_Page_Class( $this->config );
		$options_panel->OpenTabs_container( '' );
		# Define tabs listing
		$tabs_links = array(
			'appearance'	 => __( 'Appearance', 'mtt' ),
			'admin_bar'		 => __( 'Admin Bar', 'mtt' ),
			'admin_menus'	 => __( 'Admin Menus' ),
			'dashboard'		 => __( 'Dashboard', 'mtt' ),
			'post_listing'	 => __( 'Post and Page Listing', 'mtt' ),
			'post_editing'	 => __( 'Post and Page Editing', 'mtt' ),
			'media'			 => __( 'Media', 'mtt' ),
			'widgets'		 => __( 'Widgets', 'mtt' ),
			'plugins'		 => __( 'Plugins', 'mtt' ),
			'user_profile'	 => __( 'Users and Profile', 'mtt' ),
			'shortcodes'	 => __( 'Shortcodes', 'mtt' ),
			'general'		 => __( 'General Settings', 'mtt' ),
			'login_logout'	 => __( 'Login', 'mtt' ),
			'maintenance'	 => __( 'Maintenance Mode', 'mtt' ),
		);
		# MS support
		if( $this->multisite )
			$tabs_links['multisite'] = __( 'Multisite', 'mtt' );
		# Finish tabs listing
		$tabs_links['credits'] = __( 'Credits', 'mtt' );
		$tabs_links['import_export'] = __( 'Import Export Reset', 'mtt' );
		# Declare tabs listing
		$options_panel->TabsListing( array( 'links' => $tabs_links ) );
		# Include Admin Tabs
        $plugin_url = B5F_MTT_Init::get_instance()->plugin_url;
		foreach( $this->plugin_sections as $section )
			require_once ('admin-tabs/' . $section . '.php');
		# Special Admin Section
		require_once ('admin-tabs/credits.php');		
		# Import Export Admin Tabs
		$options_panel->OpenTab( 'import_export' );
		$options_panel->Title( __( 'Import Export Reset', 'mtt' ) );
		$options_panel->addImportExport();		
		$options_panel->addParagraph( "<hr><h3> Reset</h3>" );		
		$options_panel->addCheckboxList( 'mtt_reset_plugin', array(
			'do_it' => 'Select this to reset all plugin data.',
				), array(
			'name'	 => 'Reset all plugin data.',
			'desc'	 => '',
			'class'  => 'no-toggle',
			'std'	 => false
		) );
		$options_panel->addCheckboxList( 'mtt_verbose_plugin', array(
			'visible' => '',
				), array(
			'name'	 => '',//Hide help texts
			'class'  => 'mtt-hidden',
			'desc'	 => '', //THIS FIELD IS INVISIBLE, manipulated by MTT meta box
			'std'	 => false
		) );		
		$options_panel->CloseTab();	
		# End Admin Tabs Container
		$options_panel->CloseTab();
		# Create Admin Interface
		$this->options_class = $options_panel;
	}


	/**
	 * Instantiates all hook classes
	 * 
	 * @wp-hook plugins_loaded
	 * @since   2012.12.21
	 * @return  void
	 */
	public function build_hooks() {
		# Shortcircuit the instantiation of specific classes
		global $pagenow;

		# POST LISTING
		# Only this need to run in Ajax
		# http://wordpress.stackexchange.com/q/31154/12615
		if(
            ( in_array( $pagenow, array( 'edit.php', 'upload.php' ) ) && is_admin() )
            or
            ( 'admin-ajax.php' == $pagenow && isset( $_POST['action'] ) && 'inline-save' == $_POST['action'] )
		)
			new MTT_Hook_Post_Listing( $this->params );
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return;
		
		# ADMIN BAR
		new MTT_Hook_Adminbar( $this->params );
		# ADMIN MENUS
		if( is_admin() )
			new MTT_Hook_Adminmenus( $this->params );
		# DASHBOARD
		if( 'index.php' == $pagenow && is_admin() )
			new MTT_Hook_Dashboard( $this->params );
		# APPEARENCE
		if( is_admin() )
			new MTT_Hook_Appearance( $this->params );
		# GENERAL
		new MTT_Hook_General( $this->params );
		# LOGIN
		if( 'wp-login.php' == $pagenow )
			new MTT_Hook_Login( $this->params );
		# MAINTENANCE
		new MTT_Hook_Maintenance( $this->params );
		# MEDIA
		if( is_admin() )
			new MTT_Hook_Media( $this->params );
		# PLUGINS
		if( is_admin() )
			new MTT_Hook_Plugins( $this->params, $this->multisite );
		# POST EDITING
		if( ( 'post.php' == $pagenow || 'post-new.php' == $pagenow ) && is_admin() )
			new MTT_Hook_Post_Editing( $this->params );
		# PROFILE
		if( ( 'profile.php' == $pagenow || 'user-edit.php' == $pagenow ) && is_admin() )
			new MTT_Hook_Profile( $this->params );
		# SHORTCODES
		new MTT_Hook_Shortcodes( $this->params );
		# WIDGETS
		new MTT_Hook_Widgets( $this->params );
		# MULTISITE
		if( $this->multisite )
			new MTT_Hook_Multisite( $this->params );        
	}


	/**
	 * Admin Init Hooks
	 * 
	 */
	public function mtt_admin_init()
	{
		if( defined( 'DOING_AJAX' ) && DOING_AJAX )
			return;
		
		$page = $this->options_class->_Slug;		
		$cache = B5F_MTT_Init::$disable_scripts_cache ? time() : null;
		# Basic stuff
		wp_register_style(
				'mtt_admin_css', 
				B5F_MTT_Init::get_instance()->plugin_url . 'css/admin.css', 
				array( ), 
				$cache
		);
		wp_register_script(
				'mtt_admin_js', 
				B5F_MTT_Init::get_instance()->plugin_url . 'js/mtt.js', 
				array( ), 
				$cache, 
				true
		);
		add_action( 
				'admin_print_scripts-' . $page, 
				array( $this, 'admin_print_scripts' ), 
				5 
		);
		add_filter( 
				'media_upload_default_tab', 
				array( $this, 'upload_default_tab' ) 
		);
		add_action( 
				'admin_print_styles-media-upload-popup', 
				array( $this, 'upload_popup_style' ) 
		);
	}


	/**
	 * Print plugin style and script
	 */
	public function admin_print_scripts()
	{
		wp_enqueue_style( 'mtt_admin_css' );
		wp_enqueue_script( 'mtt_admin_js' );
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');

		$network = $this->multisite ? 'network/' : '';
		$title = "Admin Tweaks " 
				. __( 'version', 'mtt' ) . ' '
				. B5F_MTT_Init::$version  . ' ' 
				. B5F_MTT_Admin::$mtt_tb_title;
		
		wp_localize_script(
				'mtt_admin_js'
				, 'mtt'
				, array(
					'title'		 => $title,
					'network'	 => $network
				)
		);
	}


	/**
	 * Default tab for Thickbox
	 * 
	 * @param type $tab
	 * @return string
	 */

	public function upload_default_tab( $tab )
	{
		if( !isset( $_GET['apc'] ) )
			return $tab;
		return 'library';
	}


	/**
	 * Hide items in Thickbox
	 * 
	 */
	public function upload_popup_style()
	{
		if( !isset( $_GET['apc'] ) )
			return;
		?>
		<style> tr.post_title, tr.image_alt, tr.post_excerpt, tr.post_content, tr.url, tr.align {display:none;} </style>
		<?php

	}


    /** 
     * Remove BackWPup from roles
     */
    public function filter_backwpup_roles( $roles ) 
	{  
		foreach( $roles as $role => $value )
		{
			if( strpos( $role, 'backwpup') !== false )
				unset($roles[$role]);
		}
	    return $roles;
	}
    
    
	/**
	 * Used to get the Options values that startWith
	 * 
	 * @param type $val
	 * @param type $needle
	 * @return type
	 */
	private function filter( $val, $needle )
	{
		$arr = array( );
		foreach( $val as $key => $value )
		{
			if( $this->startsWith( $needle, $key ) )
				$arr[$key] = $val[$key];
		}
		return $arr;
	}


	/**
	 * Search for needle at first position of haystack
	 * 
	 * @param type $needle
	 * @param type $haystack
	 * @return type
	 */
	private function startsWith( $needle, $haystack )
	{
		return !strncmp( $haystack, $needle, strlen( $needle ) );
	}


	/**
	 * Help Tab Callback
	 */
	public function help_tab_callback_two()
	{
		echo '<p><pre>';
		$getty = B5F_MTT_Utils::print_repository_info( false );
		unset( $getty['changelog'], $getty['sections'], $getty['stats'] );
		print_r( $getty );
		echo '</pre></p>';
	}


	/**
	 * Avoid spilling in other pages
	 * 
	 * @global type $pagenow
	 * @return boolean
	 */
	private function our_page()
	{
		global $pagenow;
		if( 'options-general.php' != $pagenow || !isset( $_GET['page'] ) )
			return false;
		if( strpos( $_GET['page'], $this->our_base_slug() ) === false )
			return false;
		return true;
	}


	/**
	 * Same code APC uses to build the Slug
	 */
	private function our_base_slug()
	{
		$slug	 = str_replace( 
				' ', 
				'_', 
				strtolower( $this->config['page_title'] ) 
		);
		return $slug;
	}

	
	/**
	 * Control extra menus removal. Check if option set or updatable.
	 * 
	 * @global type $menu
	 */
	public function get_menu_information()
	{
	 	global $menu;
		$update = false;
		
		# REMOVABLE MENUS
		$remove_menus = array();
		foreach( $menu as $m ) 
		{
			if( !in_array( $m[2], $this->default_menus ) 
				&& false === strpos( $m[2], 'separator' ) 
			)
			{
				$remove_menus[ $m[2] ] = $m[0];  
			}
		}
		if( 
            !isset( $this->params['admin_menu_removable_items'] ) 
            || $this->params['admin_menu_removable_items'] != $remove_menus 
            ) 
		{
			$this->params['admin_menu_removable_items'] = $remove_menus;
			$update = true;
		}
        
		# AVAILABLE POST TYPES
		$args = array( 'public'	 => true, 'show_ui'	 => true );
		$cpts = get_post_types( $args );
		unset( $cpts['attachment'] );
		if( 
            !isset( $this->params['admin_menu_post_types'] ) 
            || $this->params['admin_menu_post_types'] != $cpts 
            ) 
		{
			$this->params['admin_menu_post_types'] = $cpts;
			$update = true;
		}
		
		# AVAILABLE STATUS
		$statuses = get_post_stati();
		unset(
				$statuses['auto-draft'], $statuses['inherit']
		);
		if( 
            !isset( $this->params['admin_menu_post_status'] ) 
            || $this->params['admin_menu_post_status'] != $statuses 
            ) 
		{
			$this->params['admin_menu_post_status'] = $statuses;
			$update = true;
		}
		
		# FINALLY, UPDATE DIRECTLY
		if( $update )
			update_option( B5F_MTT_Init::$opt_name, $this->params );
	}


	/**
	 * Control extra menus removal. Check if option set or updatable.
	 * 
	 * @global type $menu
	 */
	public function get_widgets_information()
	{
	 	global $wp_widget_factory;
		$get = array();
		
		foreach($wp_widget_factory->widgets as $k => $v)
			if(!in_array($k, $this->default_widgets))
				$get[$k] = $v->name;
			
		$this->params['widgets_non_default'] = $get;
		update_option( B5F_MTT_Init::$opt_name, $this->params );
			
	}

}