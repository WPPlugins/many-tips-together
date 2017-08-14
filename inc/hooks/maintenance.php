<?php
/**
 * Maintenance Mode hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Maintenance
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

		if( !empty( $options['maintenance_mode_enable']['enabled'] ) )
		{
			// BACKEND MAINTENANCE
			if( !empty( $options['maintenance_mode_enable']['other_options'] ) )
			{
				if( in_array( 'only_admin', $options['maintenance_mode_enable']['other_options'] ) ) 
					add_action(
							'admin_head', array( $this, 'do_maintenance' )
					);
			}
			
			// FRONTEND MAINTENANCE
			else
			{
				add_action(
						'admin_head', array( $this, 'do_maintenance' )
				);
				add_action(
						'get_header', array( $this, 'do_maintenance' )
				);
			}
		}
	}


	/**
	 * Build Html and die() with response 503
	 * 
	 */
	public function do_maintenance()
	{
		$level = 
				empty( $this->params['maintenance_mode_enable']['level'] ) 
				? null : $this->params['maintenance_mode_enable']['level'];
		
		$ucan = B5F_MTT_Utils::maintenance_user_level( $level );

		if( !current_user_can( $ucan ) )
		{
			// BROWSER TITLE
			$title = 
					(!empty( $this->params['maintenance_mode_enable']['title'] ) ) 
					? $this->params['maintenance_mode_enable']['title'] 
					: get_bloginfo( 'name' ) . __( ' | Maintenance Mode', 'mtt' );

			// IMAGES
			$theUrl = B5F_MTT_Init::get_instance()->plugin_url;
			$custom_stripes = $theUrl . 'images/pattern.png';
			$custom_bg = $theUrl . 'images/kub-locked.png';

			// LINE 0
			$siteName = 
					!empty( $this->params['maintenance_mode_enable']['line0'] ) 
					? $this->params['maintenance_mode_enable']['line0'] 
					: __( 'Site in maintenance', 'mtt' );

			// LINE 1
			$line1Text = 
					!empty( $this->params['maintenance_mode_enable']['line1'] ) 
					? $this->params['maintenance_mode_enable']['line1'] 
					: '<b>' . get_bloginfo( 'name' ) . '</b><br> ' . get_bloginfo( 'description' );

			// LINE 2
			$line2Text = 
					!empty( $this->params['maintenance_mode_enable']['line2'] ) 
					? $this->params['maintenance_mode_enable']['line2'] 
					: str_replace( 'http://', '', get_bloginfo( 'url' ) );

			// HTML BACKGROUND
			$stripes = 
					!empty( $this->params['maintenance_mode_enable']['html_img']['src'] ) 
					? $this->params['maintenance_mode_enable']['html_img']['src'] : '';

			if( $stripes != '' )
				$stripes = 'html{background:url(' . $stripes . ') repeat}';
			else
				$stripes = 'html{background:url(' . $custom_stripes . ') repeat}';

			// BOX ("body") BACKGROUND
			$box_bg = 
					!empty( $this->params['maintenance_mode_enable']['body_img']['src'] ) 
					? $this->params['maintenance_mode_enable']['body_img']['src'] : '';

			$box_shadow = '-webkit-border-radius: 23px; border-radius: 23px; -moz-box-shadow: 5px 5px 8px #DCDCDC; -webkit-box-shadow: 5px 5px 8px #DCDCDC; box-shadow: 5px 5px 8px #DCDCDC;';

			if( '' != $box_bg )
				$box_bg = 'background:url(' . $box_bg . ') no-repeat;';
			else
				$box_bg = 'background: rgba(51, 102, 153, 0.75) url(' . $custom_bg . ') no-repeat 30px 30px;';

			// CUSTOM CSS
			if( !empty( $this->params['maintenance_mode_enable']['extra_css'] ) )
				$extraCss = 
					('.class-name {}' != $this->params['maintenance_mode_enable']['extra_css'] ) 
					? $this->params['maintenance_mode_enable']['extra_css'] : '';
			else
				$extraCss = '';

			// CSS of this file
			$msg = <<<CSS
<style type="text/css">
	*{padding:0;margin:0}
	$stripes
	body{
		border:0;
		width:900px;
		max-width:900px;
		height:560px;
		$box_bg ;
		font-family:'Myriad Pro',Arial,Helvetica,sans-serif;
		margin: 0 auto;
		$box_shadow;
	}
	#header{height:397px;margin-bottom:-200px}
	#wrapper{width:467px;margin:80px auto}
	h1{padding-top:180px;color:#fff;font-size:2em;font-weight:bold;text-align:center;white-space:nowrap;text-shadow: 0.1em 0.1em 0.2em black;border-bottom:0px}
	h2{color:#fff;font-size:12px;letter-spacing: 0.1em;font-weight:bold;text-align:center;text-shadow: 0.1em 0.1em 1.2em black;margin-top:.5em}
	#when,.textwidget{color:#000; font-size:1.2em;text-align:center;margin-top:1.5em;}
        a { color: #fff; }
        a:hover { color: #000; }
	$extraCss
</style>
CSS;


// html of this file
			$msg .= <<<HTML
<div id="wrapper">
<div id="header" class="blank">
<h1>{$siteName}</h1>
</div>
<div id="when">
{$line1Text}
<h2><a href="http://{$line2Text}">{$line2Text}</a></h2> 
</div>

</div>
HTML;
			wp_die( $msg, $title, array( 'response' => 503 ) );
		}
	}


}