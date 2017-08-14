<?php
/**
 * Shortcodes hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Shortcodes {
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

		if( !empty( $this->params['shortcodes_everywhere'] ) )
			$this->enable_shortcodes_everywhere();

		if( !empty( $this->params['shortcodes_tube'] ) ) {
			add_shortcode( 'poptube', array( $this, 'youtube_thumb' ) );
			add_shortcode( 'mttube', array( $this, 'youtube_thumb' ) );
		}

		if( !empty( $this->params['shortcodes_gdocs'] ) )
			add_shortcode( 'gdocs', array( $this, 'googledocs' ) );
	}


	/**
	 * Enable Shortcodes everywhere.
	 *
	 * Found in: https://github.com/toscho/WordPress-Shortcodes/
	 * For Details
	 * @see http://sillybean.net/?p=2719
	 * of Stephanie C. Leary.
	 *
	 * @return void
	 */
	public function enable_shortcodes_everywhere() {
		$where = array( 'the_excerpt', 'widget_text', 'term_description', 'the_content' );
		foreach( $where  as $filter ) {
			add_filter( $filter, 'shortcode_unautop' );
			add_filter( $filter, 'do_shortcode', 11 );
		}
	}


	/**
	 * Youtube
	 * 
	 * @param type $atts
	 * @param type $content
	 * @return type
	 */
	public function youtube_thumb( $atts, $content ){
		if( empty($atts['id']) ) return '';
		wp_enqueue_style(
				'mtt_yt_css', 
				B5F_MTT_Init::get_instance()->plugin_url . 'css/youtube.css'
		);
		wp_enqueue_script(
				'mtt_yt_js', 
				B5F_MTT_Init::get_instance()->plugin_url . 'js/youtube.js'
		);
		$id = $this->yt_id($atts['id']);
		if( !$id ) return '';
		return "<div class='youtube-player' data-id='$id'></div>";
	}

	/**
	 * Google document preview
	 * 
	 * @param type $atts
	 * @param type $content
	 * @return type
	 */
	public function googledocs( $atts, $content ) {
		$class = (empty($att['class'])) ? 'google-docs-viewer' : $atts['class'];
		$text = (empty($att['text'])) ? 'View document' : $atts['text'];
		$width = (empty($att['width'])) ? '480px' : $atts['width'];
		$height = (empty($att['height'])) ? '680px' : $atts['height'];
		if( empty($atts['url']) )
			return '';
		$iframe = sprintf(
			'<iframe src="https://docs.google.com/viewer?url=%s&embedded=true" style="width:%s; height:%s;" frameborder="0"></iframe>
',
			$atts['url'], $width, $height
		);
		$link = sprintf(
			'<a class="%s" href="http://docs.google.com/viewer?url=%s" target="_blank">%s</a>',
			$class, $atts['url'], $text
		);
		return ( empty($atts['embed']) ) ? $link : $iframe;
	}

	/* http://stackoverflow.com/a/17030234/1287812 */
	private function yt_id($url) {
		if ( strlen($url) == 11 )
			return $url;
		
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
		return empty($matches) ? false : $matches[1];
	}
}