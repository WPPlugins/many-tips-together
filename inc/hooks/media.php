<?php # -*- coding: utf-8 -*-

/**
 * Media Library hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Media {
	# store the options
	protected $params;

	# current upload.php view
	private $current_view;
	
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct( $options ) {

		$this->params = $options;
		$this->current_display = get_user_option( 'media_library_mode', get_current_user_id() );
		$no_grid = ( 'grid' !== $this->current_display );
		# BIGGER THUMBS
		if( !empty( $options['media_image_bigger_thumbs'] ) && $no_grid )
			add_action(
					'admin_head-upload.php', array( $this, 'bigger_thumbs' )
			);

		# SANITIZE FILENAME
		if( !empty( $options['media_sanitize_filename'] ) )
			add_filter(
					'sanitize_file_name', array( $this, 'sanitize_filename' ), 10
			);

        # MANAGE CUSTOM COLUMNS
        require_once 'class-media-custom-columns.php';
        new MTT_Media_Custom_Columns( $options );     

		# DOWNLOAD LINK
		if( !empty( $options['media_download_link'] ) && $no_grid ) {
			add_action(
					'admin_footer-upload.php', 
					array( $this, 'print_download_js' )
			);
			add_filter(
					'media_row_actions', 
					array( $this, 'row_download_link' ), 10, 3
			);
			add_action(
					'admin_head-upload.php', 
					array( $this, 'download_button_css' )
			);
		}

		# DEFAULT TO "ATTACHED TO THIS POST"
		if( !empty( $options['media_uploaded_to_this_post'] ) ) {
			add_action( 'admin_footer-post-new.php', array( $this, 'default_upload_script' ) );
			add_action( 'admin_footer-post.php', array( $this, 'default_upload_script' ) );
		}

		# CUSTOM SIZES IN INSERT MEDIA
		if( !empty( $options['media_include_extras_sizes'] ) )
			add_filter(
					'image_size_names_choose', array( $this, 'include_extras_sizes' )
			);

		# REMOVE META BOXES
		if( !empty( $options['media_remove_metaboxes'] ) )
			add_action(
					'add_meta_boxes', array( $this, 'all_metabox_remove' )
			);

		# CAMERA EXIF
		if( !empty( $options['media_camera_exif'] ) ) {
			/*add_filter( 
					'wp_generate_attachment_metadata', 
					array( $this, 'manipulate_metadata' ), 
					10, 2 
			);*/ # OBSOLETE, METADATA IS FULLY STORED
			add_filter( 
					'manage_upload_columns', 
					array( $this, 'camera_info_column' ) 
			);
			add_action( 
					'manage_media_custom_column', 
					array( $this, 'camera_info_display' ), 
					10, 2 
			);
			add_post_type_support( 'attachment', 'custom-fields' );
		}
	}


	/**
	 * Manipulates thumbnails attributes and properties in wp-admin/upload.php
	 */
	public function bigger_thumbs() {	
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('.wp-list-table img').each(function(){
					$(this)
						.attr('width','100').css('max-width','100%')
						.attr('height','100').css('max-height','100%');
				});
				$('.media-icon').css('width', '100px');
			});     
		</script>
		<?php
	}


	/**
	 * Clean up uploaded file names
	 * 
     * Sanitization test done with the filename:
     * ÄäÆæÀàÁáÂâÃãÅåªₐāĆćÇçÐđÈèÉéÊêËëₑƒğĞÌìÍíÎîÏïīıÑñⁿÒòÓóÔôÕõØøₒÖöŒœßŠšşŞ™ÙùÚúÛûÜüÝýÿŽž¢€‰№$℃°C℉°F⁰¹²³⁴⁵⁶⁷⁸⁹₀₁₂₃₄₅₆₇₈₉±×₊₌⁼⁻₋–—‑․‥…‧.png
	 * @author toscho
	 * @url    https://github.com/toscho/Germanix-WordPress-Plugin
	 */
	public function sanitize_filename( $filename ) {

		$filename	 = html_entity_decode( $filename, ENT_QUOTES, 'utf-8' );
		$filename	 = $this->translit( $filename );
		$filename	 = $this->lower_ascii( $filename );
		$filename	 = $this->remove_doubles( $filename );
		return $filename;
	}


	/**
	 * Add download link to row actions in wp-admin/upload.php
	 * 
	 * @param array $actions
	 * @param type $post
	 * @param type $detached
	 * @return string
	 */
	public function row_download_link( $actions, $post, $detached ) {
		$the_file			 = get_attached_file( $post->ID );
		$actions['Download'] = '<a href="'
				. wp_get_attachment_url( $post->ID )
				. '" class="mtt-downloader" alt="Download link" title="'
				. __( 'Right click and choose Save As', 'mtt' )
				. '">Download</a>';

		return $actions;
	}


	/**
	 * Enqueue download script
	 */
	public function print_download_js() {
		?>
		<script>
			jQuery(document).ready( function($) { 
				$('.mtt-downloader').click( function(e) {        
					e.preventDefault();
					window.open($(this).attr('href'));
				}); 
			});
		</script>
		<?php
	}


	/**
	 * Print custom columns CSS
	 * 
	 */
	public function download_button_css() {
		echo '<style type="text/css">.mtt-downloader{cursor:pointer}</style>' . "\r\n";
	}


	/**
	 * Add custom sizes to Insert Media selector
	 * 
	 * @author http://kucrut.org/insert-image-with-custom-size-into-post/
	 * 
	 */
	public function include_extras_sizes( $sizes ) {
		global $_wp_additional_image_sizes;
		if( empty( $_wp_additional_image_sizes ) )
			return $sizes;
		foreach( $_wp_additional_image_sizes as $id => $data )
		{
			if( !isset( $sizes[$id] ) )
				$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
		}
		
		return $sizes;
	}




	/**
	 * Make Uploaded to this post the defaut state on Post Media
	 * source: https://wordpress.stackexchange.com/a/140284
	 */
	public function default_upload_script() {
		?>
		<script type="text/javascript">
		jQuery(document).on("DOMNodeInserted", function(){
            jQuery('select.attachment-filters [value="uploaded"]').attr( 'selected', true ).parent().trigger('change');
 		});
		</script>
		<?php
	}
	

	/**
	 * Remove meta boxes in wp-admin/upload.php
	 * 
	 * @global type $current_screen
	 * @return type
	 */
	public function all_metabox_remove() {

		global $current_screen;
		if( 'attachment' != $current_screen->post_type )
			return;

		/* Author meta box. */
		if( in_array( 'author', $this->params['media_remove_metaboxes'] ) )
			remove_meta_box( 'authordiv', 'attachment', 'normal' );

		/* Comment status meta box. */
		if( in_array( 'discussion', $this->params['media_remove_metaboxes'] ) )
			remove_meta_box( 'commentstatusdiv', 'attachment', 'normal' );

		/* Comments meta box. */
		if( in_array( 'comments', $this->params['media_remove_metaboxes'] ) )
			remove_meta_box( 'commentsdiv', 'attachment', 'normal' );


		/* Slug meta box. */
		if( in_array( 'slug', $this->params['media_remove_metaboxes'] ) )
			remove_meta_box( 'slugdiv', 'attachment', 'normal' );
	}


	
	public function manipulate_metadata( $metadata, $id ) {
		if ( !wp_attachment_is( 'image',$id ) )
			return $metadata;
		
		foreach( $metadata['image_meta'] as $meta => $value ) {
			if( !empty( $value ) ) {
				if( 'created_timestamp' == $meta )
					$value = gmdate( 'Y-m-d H:i:s', $value );
				update_post_meta( $id, "photo_$meta", $value );
			}
		}
		return $metadata;
	}

	public function camera_info_column( $columns ) {
		$columns['cam_info'] = 'Metadata';
		return $columns;
	}

	public function camera_info_display( $column_name, $post_id ) {
		if( 'cam_info' != $column_name )
			return;
		
		$meta = get_post_meta( $post_id, '_wp_attachment_metadata', true );
		$return = array();
		
		if( wp_attachment_is( 'image', $post_id ) ) {
			$default_exif = array( 'title', 'camera', 'aperture', 'focal_length', 'iso', 'shutter_speed', 'caption', 'credit', 'copyright', 'created_timestamp' );
			$exif = $meta['image_meta'];
			foreach( $default_exif as $v ) {
				if( !empty($exif[$v] ) ){
					$title = ( $v == 'created_timestamp' ) ? 'Created' : ucwords( str_replace('_', ' ', $v) );
					$value = ( $v == 'created_timestamp' ) ? date('Y-m-d', $exif[$v] ) : $exif[$v];
					$return[] = '<small>' . $title . ':</small> <b>' . $value . '</b>';
				}
			}
			echo implode( '<br />', $return );
		}
		if( wp_attachment_is( 'audio', $post_id ) ) {
			$default_id3 = array( 'title', 'artist', 'album', 'year', 'genre', 'filesize', 'length_formatted' );
			foreach( $default_id3 as $v ) {
				if( !empty($meta[$v] ) ){
					$title = ( $v == 'length_formatted' ) ? 'Length' : ucwords($v);
					$value = ( $v == 'filesize' ) ? B5F_MTT_Utils::format_size( $meta[$v] ) : $meta[$v];
					$return[] = '<small>' . $title . ':</small> <b>' . $value . '</b>';
				}
			}
			echo implode( '<br />', $return );
		}
		if( wp_attachment_is( 'video', $post_id ) ) {
			$default_v = array( 'width', 'height', 'filesize', 'length_formatted' );
			foreach( $default_v as $v ) {
				if( !empty($meta[$v] ) ){
					$title = ( $v == 'length_formatted' ) ? 'Length' : ucwords($v);
					$value = ( $v == 'filesize' ) ? B5F_MTT_Utils::format_size( $meta[$v] ) : $meta[$v];
					$return[] = '<small>' . $title . ':</small> <b>' . $value . '</b>';
				}
			}
			echo implode( '<br />', $return );
		}
	}
	

	/**
	 * Converts uppercase characters to lowercase and removes the rest.
	 * https://github.com/toscho/Germanix-WordPress-Plugin
	 *
	 * @uses   apply_filters( 'germanix_lower_ascii_regex' )
	 * @param  string $str Input string
	 * @return string
	 */
	private function lower_ascii( $str ) {
		$str	 = strtolower( $str );
		$regex	 = array(
			'pattern'		 => '~([^a-z\d_.-])~'
			, 'replacement'	 => ''
		);
		// Leave underscores, otherwise the taxonomy tag cloud in the
		// backend won’t work anymore.
		return preg_replace( $regex['pattern'], $regex['replacement'], $str );
	}


	/**
	 * Reduces repeated meta characters (-=+.) to one.
	 * https://github.com/toscho/Germanix-WordPress-Plugin
	 *
	 * @uses   apply_filters( 'germanix_remove_doubles_regex' )
	 * @param  string $str Input string
	 * @return string
	 */
	private function remove_doubles( $str ) {
		$regex = apply_filters(
				'germanix_remove_doubles_regex',
				array(
					'pattern'		 => '~([=+.-])\\1+~',
					'replacement'	 => "\\1"
				)
		);
		return preg_replace( $regex['pattern'], $regex['replacement'], $str );
	}

    
	/**
	 * Replaces non ASCII chars.
	 * https://github.com/toscho/Germanix-WordPress-Plugin
	 *
	 * wp-includes/formatting.php#L531 is unfortunately completely inappropriate.
	 * Modified version of Heiko Rabe’s code.
	 *
	 * @author Heiko Rabe http://code-styling.de
	 * @link   http://www.code-styling.de/?p=574
	 * @param  string $str
	 * @return string
	 */
	private function translit( $str ) {
		$utf8 = array(
			'Ä'	 => 'Ae'
			, 'ä'	 => 'ae'
			, 'Æ'	 => 'Ae'
			, 'æ'	 => 'ae'
			, 'À'	 => 'A'
			, 'à'	 => 'a'
			, 'Á'	 => 'A'
			, 'á'	 => 'a'
			, 'Â'	 => 'A'
			, 'â'	 => 'a'
			, 'Ã'	 => 'A'
			, 'ã'	 => 'a'
			, 'Å'	 => 'A'
			, 'å'	 => 'a'
			, 'ª'	 => 'a'
			, 'ₐ'	 => 'a'
			, 'ā'	 => 'a'
			, 'Ć'	 => 'C'
			, 'ć'	 => 'c'
			, 'Ç'	 => 'C'
			, 'ç'	 => 'c'
			, 'Ð'	 => 'D'
			, 'đ'	 => 'd'
			, 'È'	 => 'E'
			, 'è'	 => 'e'
			, 'É'	 => 'E'
			, 'é'	 => 'e'
			, 'Ê'	 => 'E'
			, 'ê'	 => 'e'
			, 'Ë'	 => 'E'
			, 'ë'	 => 'e'
			, 'ₑ'	 => 'e'
			, 'ƒ'	 => 'f'
			, 'ğ'	 => 'g'
			, 'Ğ'	 => 'G'
			, 'Ì'	 => 'I'
			, 'ì'	 => 'i'
			, 'Í'	 => 'I'
			, 'í'	 => 'i'
			, 'Î'	 => 'I'
			, 'î'	 => 'i'
			, 'Ï'	 => 'Ii'
			, 'ï'	 => 'ii'
			, 'ī'	 => 'i'
			, 'ı'	 => 'i'
			, 'I'	 => 'I' // turkish, correct?
			, 'Ñ'	 => 'N'
			, 'ñ'	 => 'n'
			, 'ⁿ'	 => 'n'
			, 'Ò'	 => 'O'
			, 'ò'	 => 'o'
			, 'Ó'	 => 'O'
			, 'ó'	 => 'o'
			, 'Ô'	 => 'O'
			, 'ô'	 => 'o'
			, 'Õ'	 => 'O'
			, 'õ'	 => 'o'
			, 'Ø'	 => 'O'
			, 'ø'	 => 'o'
			, 'ₒ'	 => 'o'
			, 'Ö'	 => 'Oe'
			, 'ö'	 => 'oe'
			, 'Œ'	 => 'Oe'
			, 'œ'	 => 'oe'
			, 'ß'	 => 'ss'
			, 'Š'	 => 'S'
			, 'š'	 => 's'
			, 'ş'	 => 's'
			, 'Ş'	 => 'S'
			, '™'	 => 'TM'
			, 'Ù'	 => 'U'
			, 'ù'	 => 'u'
			, 'Ú'	 => 'U'
			, 'ú'	 => 'u'
			, 'Û'	 => 'U'
			, 'û'	 => 'u'
			, 'Ü'	 => 'Ue'
			, 'ü'	 => 'ue'
			, 'Ý'	 => 'Y'
			, 'ý'	 => 'y'
			, 'ÿ'	 => 'y'
			, 'Ž'	 => 'Z'
			, 'ž'	 => 'z'
			// misc
			, '¢'	 => 'Cent'
			, '€'	 => 'Euro'
			, '‰'	 => 'promille'
			, '№'	 => 'Nr'
			, '$'	 => 'Dollar'
			, '℃'	 => 'Grad Celsius'
			, '°C' => 'Grad Celsius'
			, '℉'	 => 'Grad Fahrenheit'
			, '°F' => 'Grad Fahrenheit'
			// Superscripts
			, '⁰'	 => '0'
			, '¹'	 => '1'
			, '²'	 => '2'
			, '³'	 => '3'
			, '⁴'	 => '4'
			, '⁵'	 => '5'
			, '⁶'	 => '6'
			, '⁷'	 => '7'
			, '⁸'	 => '8'
			, '⁹'	 => '9'
			// Subscripts
			, '₀'	 => '0'
			, '₁'	 => '1'
			, '₂'	 => '2'
			, '₃'	 => '3'
			, '₄'	 => '4'
			, '₅'	 => '5'
			, '₆'	 => '6'
			, '₇'	 => '7'
			, '₈'	 => '8'
			, '₉'	 => '9'
			// Operators, punctuation
			, '±'	 => 'plusminus'
			, '×'	 => 'x'
			, '₊'	 => 'plus'
			, '₌'	 => '='
			, '⁼'	 => '='
			, '⁻'	 => '-'	// sup minus
			, '₋'	 => '-'	// sub minus
			, '–'	 => '-'	// ndash
			, '—'	 => '-'	// mdash
			, '‑'	 => '-'	// non breaking hyphen
			, '․'	 => '.'	// one dot leader
			, '‥'	 => '..'  // two dot leader
			, '…'	 => '...'  // ellipsis
			, '‧'	 => '.'	// hyphenation point
			, ' '	 => '-'   // nobreak space
			, ' '	 => '-'   // normal space
		);

		$str = strtr( $str, $utf8 );
		return trim( $str, '-' );
	}


}