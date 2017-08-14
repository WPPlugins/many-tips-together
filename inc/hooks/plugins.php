<?php
/**
 * Plugins hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Plugins {
	// store the options
	protected $params;

	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct( array $options, $is_multisite ) {
		global $pagenow;
		$this->params = $options;

		// DISABLE PLUGIN UPDATE NOTICES
		if( !empty( $this->params['plugins_block_update_notice'] ) )
			add_filter(
					'pre_site_transient_update_plugins', 
					array( $this, 'hide_plugin_update_notice' )
			);

		// DISABLE PLUGIN UPDATE NOTICES
		if( !empty( $this->params['plugins_block_update_inactive_plugins'] ) && !is_multisite() )
			add_filter(
					'site_transient_update_plugins', 
					array( $this, 'remove_update_nag_for_deactivated' )
			);

		// The rest of hooks are only for its page
		if( 'plugins.php' != $pagenow )
			return;

		// REMOVE QUICK EDIT ACTIONS
		if( !empty( $options['plugins_remove_plugin_edit'] ) )
		{
			add_action(
					'plugin_action_links', 
					array( $this, 'remove_action_links' )
			);

			if( $is_multisite )
				add_action(
						'network_admin_plugin_action_links', 
						array( $this, 'remove_action_links' )
				);
		}

		// ADD LAST UPDATED INFORMATION
		if( !empty( $options['plugins_add_last_updated'] ) )
			add_filter(
					'plugin_row_meta', 
					array( $this, 'last_updated' ), 10, 2
			);

		add_action(
				'admin_head-plugins.php', 
				array( $this, 'plugins_list_modify' )
		);
	}


	/**
	 * Hide plugins update notice
	 * 
	 * @return null
	 */
	public function hide_plugin_update_notice()
	{
		return null;
	}


	/**
	 * Remove update notice for desactived plugins
	 * Tip via: http://wordpress.stackexchange.com/a/77155/12615
	 * 
	 * @param type $value
	 * @return type
	 */
	public function remove_update_nag_for_deactivated( $value ) {
		if( empty( $value ) || empty( $value->response ) )
			return $value;
		
		foreach( $value->response as $key => $val ) {
			if( !is_plugin_active( $val->plugin ) )
				unset( $value->response[$key] );
		}
		return $value;
	}


	/**
	 * Hide Advanced Custom Fields Options page
	 * 
	 */
	public function hide_acf_options() {
		if( !current_user_can( 'delete_plugins' ) )
			remove_menu_page( 'acf-options' );
	}


	/**
	 * Remove Action Links
	 * 
	 * @return empty
	 */
	public function remove_action_links() {
		return;
	}


	/**
	 * Add Last Updated information to the Meta row (author, plugin url)
	 * 
	 * @param string $plugin_meta
	 * @param type $plugin_file
	 * @return string
	 */
	public function last_updated( $plugin_meta, $plugin_file ) {
		// If Multisite, only show in network admin
		if( is_multisite() && !is_network_admin() )
			return $plugin_meta;

		list( $slug ) = explode( '/', $plugin_file );

		$slug_hash = md5( $slug );
		$last_updated = get_transient( "range_plu_{$slug_hash}" );
		if( false === $last_updated )
		{
			$last_updated = $this->get_last_updated( $slug );
			set_transient( "range_plu_{$slug_hash}", $last_updated, 86400 );
		}

		if( $last_updated )
			$plugin_meta['last_updated'] = __( 'Last Updated', 'mtt' )
					. esc_html( ': ' . $last_updated );

		return $plugin_meta;
	}


	/**
	 * Custom CSS for Plugins page
	 * 
	 * @return string Echo 
	 */
	public function plugins_list_modify()
	{
		// USER FOR HIGHLIGHTING EXCLUSIVE PLUGINS
		$reset_bg = '.plugins .inactive, .plugins .inactive th, .plugins .inactive td, tr.inactive + tr.plugin-update-tr .plugin-update, .plugins .active td, .plugins .active th, tr.active + tr.plugin-update-tr .plugin-update, .plugins .active.update td, .plugins .active.update th, tr.active.update + tr.plugin-update-tr .plugin-update {background-color:transparent;}';
		$exclusive_plugins = !empty( $this->params['plugins_my_plugins_bg_color']['enabled'] );
		$display_count = !empty( $this->params['plugins_my_plugins_bg_color']['display_count'] );

		// GENERAL OUTPUT
		$output = '';

		// UPDATE NOTICE
		if( !empty( $this->params['plugins_remove_plugin_notice'] ) )
			$output .= '.update-message{display:none;} ';

		// DESCRIPTION
		if( !empty( $this->params['plugins_remove_description'] ) )
			$output .= '.plugin-description{display:none;} ';

		// INACTIVE
		if( !empty( $this->params['plugins_inactive_bg_color'] ) && '#' != $this->params['plugins_inactive_bg_color'] )
			$output .= 'tr.inactive {background-color:' . $this->params['plugins_inactive_bg_color'] . ' !important;}';

		if( '' != $output || $exclusive_plugins )
			echo '<style type="text/css">' . $reset_bg . $output . ' </style>' . "\r\n";


		// YOUR PLUGINS COLOR
		if( apply_filters( 'mtt_disable_plugins_coloring', $exclusive_plugins ) ) {
			if( // not correct, bail out
					empty( $this->params['plugins_my_plugins_bg_color']['names'] ) || empty( $this->params['plugins_my_plugins_bg_color']['color'] ) || '#' == $this->params['plugins_my_plugins_bg_color']['color']
			)
				return;
					
			$authors = explode( ',', $this->params['plugins_my_plugins_bg_color']['names'] );
			$jq = array( );
			foreach( $authors as $author ) {
				$jq[] = "tr:Contains('{$author}')";
			}
			$jq_ok = implode( ',', $jq );
			$by_author = __( 'by selected author(s)', 'mtt' );
			?>
			<script type="text/javascript">
				// https://css-tricks.com/snippets/jquery/make-jquery-contains-case-insensitive/
				jQuery.expr[':'].Contains = function(a, i, m) {
				  return jQuery(a).text().toUpperCase()
				      .indexOf(m[3].toUpperCase()) >= 0;
				};
				jQuery(document).ready(function($) {
					// Display author count
					<?php if( $display_count ): ?>
					var atual = $('.displaying-num').html();
					$('.displaying-num').html( atual+' : '+$("#the-list").children("<?php echo $jq_ok; ?>").length + ' ' + '<?php echo $by_author; ?>' );
					<?php endif; ?>
					
					// Modify the plugin rows background
					$("#the-list").children("<?php echo $jq_ok; ?>").each(function() {
						if ($(this).hasClass('inactive'))
							opac = '0.7';
						else
							opac = '1';
						$(this).removeClass('inactive');
						$(this).css('background-color', '<?php echo $this->params['plugins_my_plugins_bg_color']['color']; ?>');
						$(this).css('opacity', opac);
					});
				});
			</script>
			<?php
		}
	}


	/**
	 * Query WP API
	 * from the plugin http://wordpress.org/extend/plugins/plugin-last-updated/
	 * 
	 * @param type $slug
	 * @return boolean|string
	 */
	private function get_last_updated( $slug )
	{
		$request = wp_remote_post(
				'http://api.wordpress.org/plugins/info/1.0/', array(
			'body' => array(
				'action'	 => 'plugin_information',
				'request'	 => serialize(
						(object) array(
							'slug'	 => $slug,
							'fields' => array( 'last_updated' => true )
						)
				)
			)
				)
		);
		if( 200 != wp_remote_retrieve_response_code( $request ) )
			return false;

		$response = unserialize( wp_remote_retrieve_body( $request ) );
		// Return an empty but cachable response if the plugin isn't in the .org repo
		if( empty( $response ) )
			return '';
		if( isset( $response->last_updated ) )
			return sanitize_text_field( $response->last_updated );

		return false;
	}


}