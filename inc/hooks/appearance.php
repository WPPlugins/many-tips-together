<?php
/**
 * Appearance hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Appearance
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

		// HELP TAB
		if( !empty( $options['appearance_hide_help_tab'] ) )
			add_filter( 'contextual_help', array( $this, 'contextual_help_remove' ), 999, 3 );

		// SCREEN TAB
		if( !empty( $options['appearance_hide_screen_options_tab'] ) )
			add_filter( 'screen_options_show_screen', array( $this, 'remove_screen_options' ), 999, 2 );

		// DASHBOARD HELP TEXTS
		if( !empty( $options['appearance_help_texts_enable']['enabled'] ) && is_admin() )
		{
			$ucan = 
					empty( $this->params['appearance_help_texts_enable']['level'] ) 
					? true 
					: B5F_MTT_Utils::current_user_has_role_array( $this->params['appearance_help_texts_enable']['level'] );
			if( $ucan )
			{
				add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ), 5 );
			}
		}

		// ADMIN NOTICES
		if(
				!empty( $this->params['admin_notice_header_settings_enable']['enabled'] ) 
				|| !empty( $this->params['admin_notice_header_allpages_enable']['enabled'] )
		)
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 1 );

		// FOOTER MESSAGES
		if( !empty( $options['admin_notice_footer_message_enable']['enabled'] ) )
		{
			add_filter( 'admin_footer_text', array( $this, 'footer_left' ) );
			add_filter( 'update_footer', array( $this, 'footer_right' ), 11 );
		}

		// FOOTER HIDE
		if( !empty( $options['admin_notice_footer_hide'] ) )
			add_filter( 'admin_print_styles', array( $this, 'footer_hide' ) );

		// FOOTER HIDE
		if( isset( $options['admin_global_css'] ) && '.class-name {}' != $options['admin_global_css'] && '' != $options['admin_global_css'] )
			add_action(
				'admin_head', array( $this, 'admin_css' )
			);

	}


	/**
	 * Hide help tab
	 * 
	 * @param object $old_help
	 * @param object $screen_id
	 * @param object $screen
	 * @return object
	 */
	public function contextual_help_remove( $old_help, $screen_id, $screen )
	{
		$screen->remove_help_tabs();
		return $old_help;
	}


	/**
	 * Hide screen tab
	 * @param type $show_screen
	 * @param type $thiz
	 * @return boolean
	 */
	public function remove_screen_options( $show_screen, $thiz )
	{
		return false;
	}

	/**
	 * Stylesheet for hiding dashboard help elements
	 */
	public function admin_print_scripts()
	{
		$cache = B5F_MTT_Init::$disable_scripts_cache ? time() : null;
		wp_register_style( 
				'mtt-hide-help', 
				B5F_MTT_Init::get_instance()->plugin_url . 'css/hide-help.css', 
				array(), 
				$cache 
		);
		wp_enqueue_style( 'mtt-hide-help' );
	}


	public function admin_notices()
	{
		global $current_screen;
		if( 
			!empty( $this->params['admin_notice_header_settings_enable']['enabled'] ) 
			&& 'options-general' == $current_screen->parent_base 
		)
		{
			print '<div  class="updated">'
					. $this->params['admin_notice_header_settings_enable']['text']
					. '</div>';
		}

		// enable general notice
		if( !empty( $this->params['admin_notice_header_allpages_enable']['enabled'] ) )
		{
			$ucan = 
					empty( $this->params['admin_notice_header_allpages_enable']['level'] ) 
					? true 
					: B5F_MTT_Utils::current_user_has_role_array( $this->params['admin_notice_header_allpages_enable']['level'] );
			if( $ucan )
			{
				echo '<div  class="updated">' . $this->params['admin_notice_header_allpages_enable']['text'] . '</div>';
			}
		}		
	}

	
	/**
	 * Hide footer
	 */
	public function footer_hide()
	{
		echo '<style type="text/css">#wpfooter { display: none; }</style>';
	}


	/**
	 * Print custom text at Footer Left
	 * 
	 * @param string $default_text
	 * @return string
	 */
	public function footer_left( $default_text )
	{
		return html_entity_decode( stripslashes( $this->params['admin_notice_footer_message_enable']['left'] ) );
	}


	/**
	 * Print custom text at Footer Right
	 * 
	 * @param string $default_text
	 * @return string
	 */
	public function footer_right( $default_text )
	{
		return html_entity_decode( stripslashes( $this->params['admin_notice_footer_message_enable']['right'] ) );
	}


	/**
	 * Print custom CSS in all Admin pages
	 */
	public function admin_css() 
	{
		print '<style type="text/css">' . $this->params['admin_global_css'] . '</style>';;

	}
}