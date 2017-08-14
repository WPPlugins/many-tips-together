<?php
/**
 * Custom columns for Multisite Sites screen
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Multisite_Custom_Columns {
    private $params;
    
	public function __construct( $options ) {
        $this->params = $options;
        # ID
        if( !empty( $options['multisite_site_id_column'] ) ) {
            add_filter(
                    'wpmu_blogs_columns', array( $this, 'add_id_column' )
            );
            add_action(
                'admin_head-sites.php', 
                array( $this, 'print_id_column_style' )
            );
        }

        # BLOGNAME
        if( !empty( $options['multisite_blogname_column'] ) )
            add_filter(
                'wpmu_blogs_columns', 
                array( $this, 'add_blogname_column' )
            );

        # USER+ROLE
        if( !empty( $options['multisite_user_role_column'] ) )
            add_filter(
                'wpmu_blogs_columns', 
                array( $this, 'add_userrole_column' )
            );

        # FOLDER SIZE
        if( !empty( $options['multisite_blog_size_column'] ) ) {
            add_filter( 'wpmu_blogs_columns', array( $this, 'rename_size_column' ) );
            add_action( 'wpmublogsaction', array( $this, 'upload_space_used' ) );
        }

        # QUICK EDIT LINKS
        if( isset( $options['multisite_extra_quick_edit']['enabled'] ) )  {
            add_filter(
                'manage_sites_action_links', 
                array( $this, 'extra_quick_edit_sites' ), 10, 3
            );
            if( !empty( $options['multisite_extra_quick_edit']['remove'] ) )
                add_action( 
                    'load-sites.php', 
                    array( $this, 'block_quick_edit_actions' ) 
                );
        }

		# ADD THEME COLUMN TO SITES LIST
		if( !empty( $options['multisite_themes_column'] ) ) {
			add_filter( 'wpmu_blogs_columns', array( $this, 'theme_get_id' ) );
			add_action( 'manage_sites_custom_column', array( $this, 'theme_add_columns' ), 10, 2 );
			add_action( 'manage_blogs_custom_column', array( $this, 'theme_add_columns' ), 10, 2 );
			// donnow why, but this doesn't print on 'admin_head-sites.php'
			add_action( 'admin_footer-sites.php', array( $this, 'theme_add_style' ) );
		}

        # ALL
        add_action(
            'manage_sites_custom_column', 
            array( $this, 'render_columns' ), 10, 2
        );
    }


	/**
	 * Add id column header
	 * 
	 * @param type $columns
	 * @return type 
	 */
	public function add_id_column( $columns ) {
		$in = array( 'blogid' => 'ID' );
		$columns = B5F_MTT_Utils::array_push_after( $columns, $in, 0 );
		return $columns;
	}


    /**
	 * Print id column style
	 * 
	 * @global type $current_screen
	 */
	public function print_id_column_style() {
		global $current_screen;
		echo '<style type="text/css">#blogid { width:6%; }</style>';
	}


	/**
	 * Add blogname column header
	 * 
	 * @param type $columns
	 * @return type
	 */
	public function add_blogname_column( $columns ) {
		$in = array( 'blog_name' => 'Name' );
		$columns = B5F_MTT_Utils::array_push_after( $columns, $in, 2 );
		return $columns;
	}


	/**
	 * Add blogname column header
	 * 
	 * @param type $columns
	 * @return type
	 */
	public function add_userrole_column( $columns ) {
		$columns['full_users'] = __( 'Users', 'mtt' );
		unset( $columns['users'] );
		return $columns;
	}


    public function upload_space_used( $b_id ) {
		
     	
        if( $b_id == 1 ) {
            $dirName = WP_CONTENT_DIR . '/uploads/';
			$main_folder = B5F_MTT_Utils::folderSize($dirName);
			$folder_size = $this->other_sizes($main_folder);
		} 
		else {
			$dirName = $this->get_blogs_dir($b_id);
			$folder_size = B5F_MTT_Utils::folderSize($dirName);
		}
		$size = B5F_MTT_Utils::format_size( $folder_size );
		echo "<strong>$size</strong>";
    }
    
    public function rename_size_column( $sites_columns ) {
        if( isset( $sites_columns['plugins'] ) )
            $sites_columns['plugins'] = __( 'Space used', 'mtt' );
        return $sites_columns;
    }

    private function other_sizes($main_folder){
		$dir = $main_folder;
    	if( !file_exists(WP_CONTENT_DIR . '/blogs.dir/')){
			$folder = WP_CONTENT_DIR . '/uploads/sites/';
			$sites_size = B5F_MTT_Utils::folderSize($folder);
			$dir = $main_folder - $sites_size;
		} 
		return $dir;
    }

    private function get_blogs_dir($b_id){
		$dir = '';
    	if( file_exists(WP_CONTENT_DIR . '/blogs.dir/')){
    		$dir = WP_CONTENT_DIR . '/blogs.dir/' . $b_id . '/';
    	} 
		else {
			$dir = WP_CONTENT_DIR . '/uploads/sites/' . $b_id . '/';
		} 
		return $dir;
    }

    /**
	 * Add and Remove quick actions from the Sites screen
     * 
     * @param type $actions
     * @param type $blog_id
     * @param type $blogname
     * @return type
     */
	public function extra_quick_edit_sites( $actions, $blog_id, $blogname ) {
		if( isset( $this->params['multisite_extra_quick_edit']['remove'] ) )
            foreach( $this->params['multisite_extra_quick_edit']['remove'] as $remove )
                unset( $actions[ $remove ] );
		
		$actions['users'] = sprintf(
			"<a href='%s'>%s</a>",
			network_admin_url( "site-users.php?id=$blog_id" ),
			__('Users')
		);
		$actions['theme'] = sprintf(
			"<a href='%s'>%s</a>",
			network_admin_url( "site-themes.php?id=$blog_id" ),
			__('Themes')
		);
		$actions['settings'] = sprintf(
			"<a href='%s'>%s</a>",
			network_admin_url( "site-settings.php?id=$blog_id" ),
			__('Settings')
		);
		return $actions;
	}


    public function block_quick_edit_actions() {
        if ( !isset( $_REQUEST['action'] ) && !isset( $_REQUEST['action2'] ) )
            return;
        
        if( 'confirm' == $_REQUEST['action'] ) {
            $actions = array();
            foreach( $this->params['multisite_extra_quick_edit']['remove'] as $action )
                $actions[] = $action . 'blog';
            if( in_array( $_REQUEST['action2'], $actions ) )
            wp_die(
                __( 'Forbidden action.', 'mtt' ), 
                'Denied',  
                array( 
                    'response' => 500, 
                    'back_link' => true 
                )
            );    
        }	
    }
    

	/**
	 * Render columns in sites listing (id and blogname)
	 * 
	 * @param type $column_name
	 * @param type $blog_id
	 * @return type
	 */
	public function render_columns( $column_name, $blog_id ) {
		if( 'blogid' === $column_name )
			echo $blog_id;
		elseif( 'blog_name' === $column_name )
			echo get_blog_option( $blog_id, 'blogname' );
		elseif( 'full_users' === $column_name )
			$this->render_userrole_row( $blog_id );
		return $column_name;
	}

    
	/**
	 * TODO: docs
	 * @param type $blog_id
	 */
	private function render_userrole_row( $blog_id ) {
		$total_users = apply_filters( 'brsfl_filter_ms_users', 6 );
		$blogusers = get_users( array( 
			'blog_id' => $blog_id, 
			'number' => $total_users) 
		);
		if ( is_array( $blogusers ) ) {
			$blogusers_warning = '';
			if ( count( $blogusers ) > $total_users-1 ) {
				$blogusers = array_slice( $blogusers, 0, 5 );
				$blogusers_warning = sprintf(
					'%s <a href="%s">%s</a>',
					sprintf( __( 'Only showing first %s users.'), $total_users),
					esc_url( network_admin_url( 'site-users.php?id=' . $blog_id ) ),
					 __( 'More' )
				);
			}
			foreach ( $blogusers as $user_object ) {
				$super_admin = is_super_admin($user_object->ID) ? array('superadmin') : array_keys( $user_object->caps );
				printf(
					'<a href="%s"> %s </a><span style="color:#999;">&nbsp;&nbsp;&nbsp; (%s)</span><br />',
					esc_url( network_admin_url( 'user-edit.php?user_id=' . $user_object->ID ) ),
					esc_html( $user_object->user_login ),
					implode( ',', $super_admin )
					);
			}
			if ( $blogusers_warning != '' )
				echo '<strong>' . $blogusers_warning . '</strong><br />';
		}
	}
	
	/**
	 * Register theme column
	 * @param array $columns
	 * @return array
	 */
	public function theme_get_id( $columns ) {
		$columns['the_template'] = __('Theme');
		return $columns;
	}

	
	/**
	 * Echo theme column content
	 * @param string $column_name
	 * @param number $blog_id
	 * @return string
	 */
	public function theme_add_columns( $column_name, $blog_id ) {
		if ( 'the_template' === $column_name ) {
			B5F_MTT_Utils::get_blog_theme( $blog_id, true );
		}
		return $column_name;
	}

	
	/**
	 * Echo theme column CSS
	 */
	public function theme_add_style() {
		echo '<style>#the_template { width:10%; }</style>';
	}
		    
}