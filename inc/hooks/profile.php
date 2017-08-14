<?php
/**
 * Profile hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Profile
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

		// CONTACT METHODS (FB, AIM..)
		if( !empty( $options['profile_social'] )  )
			add_filter(
					'user_contactmethods', array( $this, 'contact_metods' )
			);

		// COLOR PICKER
		if( !empty( $options['profile_slim'] ) )
			remove_action( "admin_color_scheme_picker", "admin_color_scheme_picker" );

		// REST OF CUSTOMIZATION VIA CSS
		add_action(
				'admin_head', array( $this, 'customize_profile' )
		);
	}


	/**
	 * Change or remove contact methods
	 * 
	 * @param type $contactmethods
	 * @return string
	 */
	public function contact_metods( $contactmethods ) {
		$contactmethods['facebook']	 = 'Facebook';
		$contactmethods['twitter']	 = 'Twitter';
		$contactmethods['linkedin']	 = 'LinkedIn';
		return $contactmethods;
	}


	/**
	 * CSS for Profile and User pages
	 * 
	 */
	public function customize_profile()
	{
		$style	 = $script	 = '';

		if( '.class-name {}' != $this->params['profile_css'] )
			$style .= $this->params['profile_css'];

		if( !empty( $this->params['adminbar_disable'] ) )
			$style .= '.show-admin-bar {display:none !important} ';


		if( !empty( $this->params['profile_h3_titles'] ) )
			$script .= '
                    $("#your-profile h2").each(function(name,value) { $(this).remove(); });';

		if( !empty( $this->params['profile_descriptions'] ) )
			$script .= '
                    $(".description:not(:contains(\"Gravatar\"))").css("display","none");';

		if( !empty( $this->params['profile_slim'] ) )
			$script .= '
                    $("#your-profile .form-table:first tr:lt(2)").remove();';

		if( !empty( $this->params['profile_bio'] ) )
			$script .= '
                    $("#your-profile .form-table:eq(3) tr:eq(0), #your-profile h3:eq(3)").remove();';

		elseif( !empty( $this->params['profile_hidden'] ) )
			$script .= '
                    $("#your-profile h3:first").remove();
                    $("#your-profile .form-table:first").remove();';

		if( !empty( $this->params['profile_display_name'] ) )
			$script .= '
                    $("#display_name").parents("tr").hide();';

		if( !empty( $this->params['profile_nickname'] ) )
			$script .= '
                    $("#nickname").parents("tr").hide();';

		if( !empty( $this->params['profile_website'] ) )
			$script .= '
                    $("#url").parents("tr").hide();';

		if( '' != $style )
			print '<style type="text/css">' . $style . '</style>';;

		if( '' != $script )
			print '<script type="text/javascript">
          jQuery(document).ready(function($){'
					. $script
					. '});
                </script>';
	}

}