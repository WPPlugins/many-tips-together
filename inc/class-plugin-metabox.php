<?php
/**
 * Meta Box
 *
 * @package ManyTipsTogether
 */
class B5F_MTT_MetaBox
{

	public static function mtt_meta_box()
	{
		$logo         = B5F_MTT_Init::get_instance()->plugin_url . 'images/mtt-logo.png';
		$mtt_tb_title = B5F_MTT_Init::$version . ' ' . B5F_MTT_Admin::$mtt_tb_title;
		$version      = B5F_MTT_Init::$version;
		$multisite   = is_multisite() ? ( is_super_admin() ) : true;
		?>

		<script>

		</script>
		<div class="mtt-box">
			<div class="inner">
				<div id="icon-options-mtt" class="icon32">
					<a href="http://www.rodbuaiz.com">
						<img src="<?php echo $logo; ?>" alt="rodbuaiz.com" title="rodbuaiz.com"/>
					</a>
				</div>
				<h2>Admin Tweaks<br/>
					<em style="font-size:.5em;"><?php _e( 'version', 'mtt' ); ?>
						<?php if( $multisite ): ?>
							<a id="open-tb" class="thickbox" title="<?php echo "Admin Tweaks " . __( 'version', 'mtt' ) . $version; ?>" href="javascript:void(0);">
						<?php endif; ?>
						<?php echo $version; ?>
						<?php if( $multisite ): ?>
							</a>
						<?php endif; ?>
					</em>

				</h2>
				<div id="mtt-other-info">
				<ul class="left hl" style="margin: -12px 0 6px 0;text-align: right;">
					<li id="bsf-link"><?php _e( "by", 'mtt' ); ?> brasofilo</li>
				</ul>
				<?php B5F_MTT_Utils::print_repository_info(); ?>
				<hr style="opacity:.3"/>

				<br style="clear:both"/>
				</div>

				<label for="mtt_verbose_plugin_helper" class="no-class">
					<input name="mtt_verbose_plugin_helper" id="mtt_verbose_plugin_helper" type="checkbox" class="no-toggle"> <?php _e( 'Hide the plugin help texts', 'mtt' ); ?>
				</label>

				<p class="desc-field">
					<span style="color:#C5C5C5">
						<?php _e( '(some settings need a second refresh for being visible)', 'mtt' ); ?>
					</span>
				</p>
				<br style="clear:both"/>

				<div class="submit update-button mtt-update">
					<button class="button-primary" id="mtt-submit" title="<?php _e( 'Update settings', 'mtt' ) ?>"/><?php _e( 'Update settings', 'mtt' ) ?></button>
				</div>

				<br style="clear:both"/>


			</div>


			<?php
//			if( check_admin_referer( 'admin-page-class.php', 'BF_Admin_Page_Class_nonce' ) )
			if(
					isset( $_POST['BF_Admin_Page_Class_nonce'] )
					&& wp_verify_nonce( $_POST['BF_Admin_Page_Class_nonce'], 'admin-page-class.php' )
			)
			{
				if( isset( $_POST['mtt_reset_plugin'] ) && in_array( 'do_it', $_POST['mtt_reset_plugin'] ) )
					delete_option( B5F_MTT_Init::$opt_name );
				if( isset( $_POST['action'] ) && $_POST['action'] == 'save' )
				{
					echo '<div id="alert_bar" class="footer">';
					echo '<script type="text/javascript">jQuery(document).ready(function ($) {close_update_msg();});</script>';
					echo '<p><strong>' . __( 'Settings updated.', 'mtt' ) . '</strong></p></div>';
				}
			}
			/*
			  if ( $msg_revisions == 'yes' )
			  {
			  echo '<script type="text/javascript">jQuery(document).ready( function($) {	$("#alert_bar").slideDown(); window.setTimeout(function(){$("#alert_bar").slideUp()},7500);});</script>';
			  ?>
			  <p><strong><?php _e( 'Revisions deleted from database.', 'mtt' ); ?></strong></p><?php
			  }

			  if ( $msg_reset == 'yes' )
			  {
			  echo '<script type="text/javascript">jQuery(document).ready( function($) {	$("#alert_bar").slideDown(); window.setTimeout(function(){$("#alert_bar").slideUp()},7500);});</script>';
			  ?>
			  <p><strong><?php _e( 'Settings reset.', 'mtt' ); ?></strong></p><?php
			  }
			  ?>
			  </div> */
			?>
			<div class="footer">

				<ul class="right hl">
					<li><a href="http://wordpress.org/support/view/plugin-reviews/many-tips-together"
						   target="_blank"><?php _e( "Rate Admin Tweaks!", 'mtt' ); ?></a></li>
					<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=JNJXKWBYM9JP6&lc=ES&item_name=Admin%20Tweaks%20%3a%20Rodolfo%20Buaiz&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted"
						   target="_blank"><?php _e( "Invite me a beer :o)]", 'mtt' ); ?></a></li>
				</ul>
			</div>
		</div>
		<?php
	}


}

