<?php
/**
 * Auxiliary Methods
 *
 * @package ManyTipsTogether
 */
class B5F_MTT_Utils {
	/**
	 * Initial values
	 *
	 * @type array
	 */
	public static $default_options = 
	array (
	  'multisite_active_plugins_widget' => false,
	  'multisite_blogname_column' => false,
	  'multisite_redirect_new_site' => false,
	  'multisite_site_id_column' => false,
	  'multisite_sort_sites_names' => false,
	  'multisite_user_role_column' => false,
	  'multisite_themes_column' => false,
	  'multisite_block_signup' => false,
	  'wpenable_custom_gravatars_enable' => 
		  array (
			'img' => 
			array (
			  'id' => '',
			  'src' => '',
			),
	  ),
	  'adminbar_completely_disable' => false,
	  'adminbar_disable' => false,
	  'adminbar_howdy_enable' => 
		  array (
			'howdy' => '',
		  ),
	  'adminbar_sitename_enable' => 
		  array (
			'title' => '',
			'icon' => 
			array (
			  'id' => '',
			  'src' => '',
			),
			'url' => '',
		  ),
	  'adminbar_custom_enable' => 
		  array (
			'bar_0_title' => '',
			'bar_0_url' => '',
		  ),
	  'admin_menus_hoverintent' => false,
	  'admin_menus_enable_link_manager' => false,
	  'admin_menus_sort_wordpress' => false,
	  'admin_menus_sort_plugins' => false,
	  'admin_menus_sort_cpts' => false,
	  'plugins_acf_show_only' => 
		  array (
			'for_user' => 'none',
		  ),
	  'plugins_acf_hide_options' => 
		  array (
			'for_user' => 'none',
		  ),
	  'posts_rename_enable' => 
		  array (
			'name' => '',
			'singular_name' => '',
			'add_new' => '',
			'edit_item' => '',
			'view_item' => '',
			'search_items' => '',
			'not_found' => '',
			'not_found_in_trash' => '',
		  ),
	  'appearance_hide_help_tab' => false,
	  'appearance_hide_screen_options_tab' => false,
	  'admin_notice_header_settings_enable' => 
		  array (
			'text' => '',
		  ),
	  'admin_notice_header_allpages_enable' => 
		  array (
			'text' => '',
		  ),
	  'admin_notice_footer_hide' => false,
	  'admin_notice_footer_message_enable' => 
		  array (
			'left' => '',
			'right' => '',
		  ),
	  'admin_global_css' => '.class-name {}',
	  'wpdisable_version_full' => false,
	  'wpdisable_version_number' => false,
	  'wpblock_update_wp' => false,
	  'wpblock_update_wp_all' => false,
	  'wpblock_update_screen' => false,
	  'wpdisable_nourl' => false,
	  'wpdisable_selfping' => false,
	  'wpdisable_redirect_disallow' => false,
	  'wptaxonomy_columns' => false,
	  'wprss_delay_publish_enable' => 
		  array (
			'time' => '',
			'period' => 'MINUTE',
		  ),
	  'wpdisable_smartquotes' => false,
	  'wpdisable_capitalp' => false,
	  'wpdisable_autop' => false,
	  'wpdisable_wptitle' => false,
	  'login_redirect_enable' => 
		  array (
			'url' => '',
		  ),
	  'logout_redirect_enable' => 
		  array (
			'url' => '',
		  ),
	  'loginpage_errors' => 
		  array (
			'msg_login' => '',
		  ),
	  'loginpage_disable_shaking' => false,
	  'loginpage_form_border' => false,
	  'loginpage_form_bg_color' => '#',
	  'loginpage_body_color' => '#',
	  'loginpage_body_position' => 'empty',
	  'loginpage_body_repeat' => 'empty',
	  'loginpage_body_attachment' => 'empty',
	  'loginpage_backsite_hide' => false,
	  'loginpage_extra_css' => '.class-name {}',
	  'maintenance_mode_enable' => 
		  array (
			'title' => '',
			'line0' => '',
			'line1' => '',
			'line2' => '',
			'html_img' => 
				array (
				  'id' => '',
				  'src' => '',
				),
			'body_img' => 
				array (
				  'id' => '',
				  'src' => '',
				),
			'level' => 'Administrator',
			'extra_css' => '.class-name {}',
		  ),
	  'media_image_bigger_thumbs' => false,
	  'media_image_id_column_enable' => false,
	  'media_image_size_column_enable' => false,
	  'media_image_thubms_list_column_enable' => false,
	  'media_download_link' => false,
	  'media_better_attachment' => false,
	  'media_uploaded_to_this_post' => false,
	  'media_include_extras_sizes' => false,
	  'media_sanitize_filename' => false,
	  'plugins_block_update_notice' => false,
	  'plugins_block_update_inactive_plugins' => false,
	  'plugins_remove_plugin_edit' => false,
	  'plugins_remove_description' => false,
	  'plugins_remove_plugin_notice' => false,
	  'plugins_add_last_updated' => false,
	  'plugins_inactive_bg_color' => '#',
	  'plugins_my_plugins_bg_color' => 
		  array (
			'names' => '',
			'color' => '#',
		  ),
	  'postpages_enable_page_excerpts' => false,
	  'postpages_enable_category_count' => false,
	  'postpages_enable_category_fixed' => false,
	  'postpages_enable_category_noparent' => false,
	  'postpages_move_author_metabox' => false,
	  'postpages_move_comments_metabox' => false,
	  'postpages_disable_mbox_author' => 'none',
	  'postpages_disable_mbox_comment_status' => 'none',
	  'postpages_disable_mbox_comment' => 'none',
	  'postpages_disable_mbox_custom_fields' => 'none',
	  'postpages_disable_mbox_featured_image' => 'none',
	  'postpages_disable_mbox_revisions' => 'none',
	  'postpages_disable_mbox_slug' => 'none',
	  'postpages_disable_mbox_attributes' => false,
	  'postpages_disable_mbox_format' => false,
	  'postpages_disable_mbox_category' => false,
	  'postpages_disable_mbox_excerpt' => false,
	  'postpages_disable_mbox_tags' => false,
	  'postpages_disable_mbox_trackbacks' => false,
	  'postpageslist_enable_category_count' => false,
	  'postpageslist_duplicate_del_revisions' => false,
	  'postpageslist_enable_id_column' => false,
	  'postpageslist_enable_thumb_column' => 
		  array (
			'proportion' => '',
			'width' => '',
		  ),
	  'postpageslist_status_draft' => '#',
	  'postpageslist_status_pending' => '#',
	  'postpageslist_status_future' => '#',
	  'postpageslist_status_private' => '#',
	  'postpageslist_status_password' => '#',
	  'postpageslist_status_others' => '#',
	  'profile_h3_titles' => false,
	  'profile_descriptions' => false,
	  'profile_slim' => false,
	  'profile_hidden' => false,
	  'profile_display_name' => false,
	  'profile_nickname' => false,
	  'profile_website' => false,
	  'profile_social' => false,
	  'profile_bio' => false,
	  'profile_css' => '.class-name {}',
	  'shortcodes_everywhere' => false,
	  'shortcodes_tube' => false,
	  'shortcodes_gdocs' => false,
	  'widgets_non_default' => array()
	);

	/**
	 * Get all users by user_login=>display_name
	 * 
	 * @param  many $field Value of the custom field
	 * @param  str  $name  Name of property
	 * @param  str  $type  Type of custom field
	 * @return prints Html/JS code
	 */
	public static function get_users_array() {
		$default = array( 'none'		 => 'None' );
		$user_arr	 = array( );
		$users = get_users();
		if( count( $users ) > 1 ) {
			foreach( $users as $user ) {
				$u_login = isset( $user->data ) ? $user->data->user_login : $user->user_login;
				$u_name = isset( $user->data ) ? $user->data->display_name : $user->display_name;
				$user_arr[$u_login] = ucwords( $u_name );
			}
		}
		else {
			return false;
		}

		return array_merge( $default, $user_arr );
	}


	/**
	 * Validate URLs and '#'
	 * 
	 * @param string $url
	 * @param boolean $allow_dash
	 * @return boolean
	 */
	public static function check_url( $url, $allow_dash = false ) {
		if( $allow_dash && $url == "#" )
			return true;

		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}


	/**
	 * Position element at the end of array
	 * 
	 * @param array $src
	 * @param array $in
	 * @param number $pos
	 * @return array
	 */
	public static function array_push_after( $src, $in, $pos ) {
		if( is_int( $pos ) )
			$R = array_merge( array_slice( $src, 0, $pos + 1 ), $in, array_slice( $src, $pos + 1 ) );
		else
		{
			foreach( $src as $k => $v )
			{
				$R[$k]	 = $v;
				if( $k == $pos )
					$R		 = array_merge( $R, $in );
			}
		}
		return $R;
	}


	/**
	 * Get capability from role name
	 * 
	 * @param string $opt
	 * @return string
	 */
	public static function maintenance_user_level( $opt = null ) {
		$level	 = 'delete_plugins';
		if( $opt == 'Editor' )
			$level	 = 'delete_pages';
		elseif( $opt == 'Author' )
			$level	 = 'publish_posts';
		elseif( $opt == 'Contributor' )
			$level	 = 'delete_posts';
		elseif( $opt == 'Subscriber' )
			$level	 = 'read';
		return $level;
	}


	/**
	 * Make rating like in the Repo
	 * cents: 0=never, 1=if needed, 2=always
	 * 
	 * @param number $number
	 * @param number $cents
	 * @return number
	 */
	public static function formatRating( $number, $cents = 1 ) {
		if( is_numeric( $number ) ) {
			if( !$number ) {
				$rating = ($cents == 2) ? '0.00' : '0';
			}
			else {
				if( floor( $number ) == $number ) {
					$rating = number_format( $number, ($cents == 2 ? 2 : 0 ) );
				}
				else {
					$rating = number_format( round( $number, 2 ), ($cents == 0 ? 0 : 2 ) );
				}
			}
			return $rating;
		}
	}


	/**
	 * Function name grabbed from: http://core.trac.wordpress.org/ticket/22624
	 * 2 lines of code from TutPlus: http://goo.gl/X4lmf
	 * 
	 * Usage: current_user_has_role( 'editor' );
	 * 
	 * @param string $role
	 * @return boolean
	 */
	public static function current_user_has_role( $role ) {
		$current_user	 = new WP_User( wp_get_current_user()->ID );
		$user_roles		 = $current_user->roles;
		$is_or_not		 = in_array( $role, $user_roles );
		return $is_or_not;
	}


	/**
	 * Current user has role
	 * Modified to work with Array
	 * http://wordpress.stackexchange.com/q/53675/12615
	 * 
	 * @param array $role
	 * @return boolean
	 */
	public static function current_user_has_role_array( $role ) {
		$current_user	 = new WP_User( wp_get_current_user()->ID );
		$user_roles		 = $current_user->roles;
		$arrtolower		 = array_map( 'strtolower', $role );
		$search			 = array_intersect( $user_roles, $arrtolower );
		if( count( $search ) > 0 )
			return true;
		return false;
	}


	/**
	 * Helper for making external links
	 *
	 * @param str  $name  Name of the link
	 * @param str  $url   Address of the link
	 * @param bool $blank Open in new window
	 * @return str Html content
	 */
	public static function make_tip_credit( $name, $url, $blank = true ) {
		$target	 = $blank ? 'target="_blank"' : '';
		$html	 = '<a href="' . $url . '" ' . $target . '>' . $name . '</a>';
		return $html;
	}


	/**
	 * Consult Repo for plugin info
	 * 
	 * @return object/boolean
	 */
	public static function get_repository_info() {
		$plugin_url = 'http://wpapi.org/api/plugin/many-tips-together.json';
		$cache = get_transient( 'mttdlcounter2' );
		
		if( false !== $cache )
			return $cache;

		// Fetch the data
        $get = wp_remote_get( $plugin_url );
		if( $response = wp_remote_retrieve_body( $get ) ) {
			// Decode the json response
			if( $response = json_decode( $response, true ) ) {
				// Double check we have all our data
				if( !empty( $response['added'] ) ) {
					set_transient( 'mttdlcounter2', $response, 720 );
					return $response;
				}
			}
		}
		return false;
	}


	/**
	 * Print HTML with Repo info
	 * 
	 * @param boolean $echo
	 * @return string
	 */
	public static function print_repository_info( $echo = true ) {
		$mttotal = self::get_repository_info();
		if( false === $mttotal )
			return;
		
		$rat			 = $mttotal['rating'] . '%';
		$num_rating		 = number_format_i18n( $mttotal['num_ratings'] );
		$totr			 = sprintf( __( '%s votes', 'mtt' ), $num_rating );
		$tot			 = __( 'Downloads', 'mtt' );
		$upd			 = __( 'Updated', 'mtt' );
		$img1			 = plugins_url( 'many-tips-together' ) . '/images/star_x_grey.gif';
		$img2			 = plugins_url( 'many-tips-together' ) . '/images/star_x_orange.gif';

		if( isset($mttotal['total_downloads']) ) {
			$total_base = number_format_i18n( $mttotal['total_downloads'] );
			$total_downloads = "<span style='color:#b0b0b0'>$tot:</span> <em style='color:#ccbb8d;'>$total_base</em><br>";
		}
		else {
			$total_downloads = '';
		}
		
		//$rating          = self::formatRating( $mttotal['rating'] / 20 );
		$updated		 = date_i18n( get_option( 'date_format' ), strtotime( $mttotal['updated'] ) );

		if( !$echo )
			return $mttotal;

		$print = <<<HTML
		    <div style="float:right;text-align:right"><div style="width:55px;background:url($img1) 0 0 repeat-x;">
<div style="height: 12px; background: url($img2) repeat-x scroll 0px 0px transparent; width: $rat "></div>$totr</div>
</div>
			$total_downloads
		    <span style="color:#b0b0b0">$upd:</span> <em style="color:#ccbb8d;">$updated</em>
HTML;
		echo $print;
	}


	/**
	 * Validate CSS numbers, strips 'px', 'em' and '%' from string
	 * 
	 * @param string $val
	 * @return int/boolean False or Integer without decimals
	 */
	public static function validate_css_number( $val ) {
		$number = preg_replace( '/[^0-9]/', '', $val );
		if( is_numeric( $number ) || !empty( $number ) ) {
			return (int) $number;
		}
		
		return false;
	}


	/**
	 * Validate -1, 0, etc number
	 * 
	 * @param string $val
	 * @return int/boolean False or Integer without decimals
	 */
	public static function validate_pos_neg_number( $val ) {
		$number = preg_replace( '/[^0-9\-]/', '', $val );
		
		if( is_numeric( $number ) || !empty( $number ) ) {
			return (int) $number;
		}
		
		return false;
	}

	
	/**
	 * Validate CSS numbers, keeps 'px' and '%' in the string
	 * 
	 * @param string $val
	 * @return int/boolean False or Integer without decimals and sign
	 */
	public static function validate_css_px_percent( $val ) {

		if( self::endswith( $val, '%' ) ) {
			$num = str_replace( '%', '', $val );
			if( is_numeric( $num ) )
				return $num . '%';
			else
				return false;
		}
		elseif( self::endswith( $val, 'px' ) ) {
			$num = str_replace( 'px', '', $val );
			if( is_numeric( $num ) )
				return $num . 'px';
			else
				return false;
		}
		elseif( self::endswith( $val, 'em' ) ) {
			$num = str_replace( 'em', '', $val );
			if( is_numeric( $num ) )
				return $num . 'em';
			else
				return false;
		}
		else {
			return false;
		}
	}

	
	/**
	 * 
	 * @param string $needle
	 * @param array $haystack
	 * @return boolean or current_key
	 */
	public static function recursive_array_search( $needle, $haystack ) {
		foreach( $haystack as $key => $value ) {
			$current_key = $key;
			if( 
				$needle === $value 
				OR ( 
					is_array( $value )
					&& self::recursive_array_search( $needle, $value ) !== false 
				)
			) {
				return $current_key;
			}
		}
		return false;
	}


	/**
	 * Check ending of string
	 *  
	 * @param type $string
	 * @param type $test
	 * @return boolean
	 */
	private static function endswith( $string, $test ) {
		$strlen	 = strlen( $string );
		$testlen = strlen( $test );
		if( $testlen > $strlen )
			return false;
		return substr_compare( $string, $test, -$testlen ) === 0;
	}

	/**
	 * Convert letter code into role name
	 * 
	 * @param string $opt
	 * @return string
	 */
	private static function get_user_level( $opt ) {
		$ritorna = array();
		switch ( $opt ) {
			case 'a':
				$ritorna[] = 'Administrator';
				break;
			case 'e':
				$ritorna[] = 'Editor';
				break;
			case 't':
				$ritorna[] = 'Author';
				break;
			case 'c':
				$ritorna[] = 'Contributor';
				break;
			case 's':
				$ritorna[] = 'Subscriber';
				break;
		}
		return $ritorna;
	}

    /**
     * Formats the size into human readable
     * 
     * @param integer $size
     * @return string
     * 
     * @since 2.3.7
     * @access public
     */
    public static function format_size( $size ) {
        $units = explode( ' ', 'B KB MB GB TB PB' );
        $mod = 1024;
        for( $i = 0; $size > $mod; $i++ )
            $size /= $mod;

        $endIndex = strpos( $size, "." ) + 3;
        return substr( $size, 0, $endIndex ) . ' ' . $units[$i];
    }
	
	/**
	 * Get site theme
	 */
    public static function get_blog_theme( $blog_id, $echo ) {
		$the_template = get_blog_option( $blog_id, 'template' ); 
		$the_style = get_blog_option( $blog_id, 'stylesheet' ); 
		$check_child = '';

		if( $the_style != $the_template ) {
			$child = explode( '/', $the_style ); // if themes in folders
			$check_child = sprintf(
				'<b>%s</b><br /><i>Parent: </i>',
				isset( $child[1] ) ? $child[1] : $child[0] // inside folder or not
			);
		}

		$head = wp_get_theme( $the_template );
		$result = $check_child . $head->Name;
		if( $echo )
			echo $result;
		else
			return $result;
	}
	
	public static function folderSize($dir){
	    $size = 0;
	    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
	        $size += is_file($each) ? filesize($each) : self::folderSize($each);
	    }
	    return $size;
	}
}