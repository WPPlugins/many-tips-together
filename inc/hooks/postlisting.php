<?php
/**
 * Post Listing hooks
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

class MTT_Hook_Post_Listing {
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
		
		// CATEGORY COUNT
		if( !empty( $options['postpageslist_enable_category_count'] ) )
			add_action(
					'load-edit.php', array( $this, 'category_count_load' )
			);


		// MAKE DUPLICATE AND DELETE REVISIONS
		if( !empty( $options['postpageslist_duplicate_del_revisions'] ) ) {
			// Class for removing revisions and making duplicates (posts and pages)
			include_once plugin_dir_path( __FILE__ ) . 'class-posts-revisions-duplicate.php';

			add_action(
					'admin_init', array( 'MTT_Manage_Duplicates_Revisions', 'init' )
			);
		}

		// ADD ID COLUMN
		if( !empty( $options['postpageslist_enable_id_column'] ) )
			add_action(
					'admin_init', array( $this, 'init_id_column' ), 999
			);

		// ADD THUMBNAIL COLUMN
		if( !empty( $options['postpageslist_enable_thumb_column']['enabled'] ) ) {
			add_action(
					'admin_init', array( $this, 'init_thumb_column' ), 999
			);
		}

		// CSS FOR CUSTOM COLUMNS
		add_action(
				'admin_head-edit.php', array( $this, 'id_width_and_status_colors' )
		);
		
	}


	/**
	 * Add category count to the dropdown selector
	 */
	public function category_count_load() {
		global $typenow;
		if( !in_array( $typenow, apply_filters( 'mtt_category_counts_cpts', array( 'post' ) ) ) )
			return;

		add_filter( 'wp_dropdown_cats', array( $this, 'category_count_do' ) );
	}
	
	/**
	 * Cloned from /wp-includes/category-template.php#321
	 * 
	 * @global type $cat
	 * @param type $output
	 * @return string
	 */
	public function category_count_do( $output ) {
		global $cat;
		$args = array(
			'show_option_all' => get_taxonomy( 'category' )->labels->all_items,
			'hide_empty' => 0,
			'hierarchical' => 1,
			'show_count' => 1,
			'orderby' => 'name',
			'selected' => $cat
		);
		$defaults = array(
			'show_option_all'   => '',
			'show_option_none'  => '',
			'orderby'           => 'id',
			'order'             => 'ASC',
			'show_count'        => 0,
			'hide_empty'        => 1,
			'child_of'          => 0,
			'exclude'           => '',
			'echo'              => 0,
			'selected'          => 0,
			'hierarchical'      => 0,
			'name'              => 'cat',
			'id'                => '',
			'class'             => 'postform',
			'depth'             => 0,
			'tab_index'         => 0,
			'taxonomy'          => 'category',
			'hide_if_empty'     => false,
			'option_none_value' => -1,
			'value_field'       => 'term_id',
			'required'          => false,
		);

		$defaults['selected'] = ( is_category() ) ? get_query_var( 'cat' ) : 0;
		$r = wp_parse_args( $args, $defaults );

		if ( !isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
			$r['pad_counts'] = true;
		}

		$tab_index = $r['tab_index'];
		$tab_index_attribute = '';
		if ( (int) $tab_index > 0 ) {
			$tab_index_attribute = " tabindex=\"$tab_index\"";
		}

		// Avoid clashes with the 'name' param of get_terms().
		$get_terms_args = $r;
		unset( $get_terms_args['name'] );
		$categories = get_terms( $r['taxonomy'], $get_terms_args );

		$name = esc_attr( $r['name'] );
		$class = esc_attr( $r['class'] );
		$id = $r['id'] ? esc_attr( $r['id'] ) : $name;
		$required = $r['required'] ? 'required' : '';

		if ( ! $r['hide_if_empty'] || ! empty( $categories ) ) {
			$output = "<select $required name='$name' id='$id' class='$class' $tab_index_attribute>\n";
		} else {
			$output = '';
		}
		if ( empty( $categories ) && ! $r['hide_if_empty'] && ! empty( $r['show_option_none'] ) ) {
			$show_option_none = $r['show_option_none'];
			$output .= "\t<option value='" . esc_attr( $option_none_value ) . "' selected='selected'>$show_option_none</option>\n";
		}

		if ( ! empty( $categories ) ) {

			if ( $r['show_option_all'] ) {
				$show_option_all = $r['show_option_all'];
				$selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
				$output .= "\t<option value='0'$selected>$show_option_all</option>\n";
			}

			if ( $r['show_option_none'] ) {
				$show_option_none = $r['show_option_none'];
				$selected = selected( $option_none_value, $r['selected'], false );
				$output .= "\t<option value='" . esc_attr( $option_none_value ) . "'$selected>$show_option_none</option>\n";
			}

			if ( $r['hierarchical'] ) {
				$depth = $r['depth'];  // Walk the full depth.
			} else {
				$depth = -1; // Flat.
			}
			$output .= walk_category_dropdown_tree( $categories, $depth, $r );
		}

		if ( ! $r['hide_if_empty'] || ! empty( $categories ) ) {
			$output .= "</select>\n";
		}

		return $output;
	}
	
	
	/**
	 * Dispatch ID custom column
	 * 
	 */
	public function init_id_column() {
		add_filter( 
				'manage_pages_columns', array( $this, 'id_column_define' )
		);
		add_filter( 
				'manage_posts_columns', array( $this, 'id_column_define' )
		);
		add_action( 
				'manage_pages_custom_column', array( $this, 'id_column_display' ), 10, 2
		);
		add_action( 
				'manage_posts_custom_column', array( $this, 'id_column_display' ), 10, 2
		);
	}


	/**
	 * Dispatch Thumbnail custom column
	 * 
	 */
	public function init_thumb_column() {
		add_filter(
				'manage_posts_columns', array( $this, 'thumb_column_define' )
		);
		add_filter(
				'manage_pages_columns', array( $this, 'thumb_column_define' )
		);
		add_action(
				'manage_posts_custom_column', array( $this, 'thumb_column_display' ), 10, 2
		);
		add_action(
				'manage_pages_custom_column', array( $this, 'thumb_column_display' ), 10, 2
		);
	}


	/**
	 * Add ID column
	 * 
	 * @param type $cols
	 * @return type
	 */
	public function id_column_define( $cols ) {
		$in = array( "id" => "ID" );
		$cols = B5F_MTT_Utils::array_push_after( $cols, $in, 0 );
		return $cols;
	}


	/**
	 * Add Thumbnail column
	 * 
	 * @param type $col_name
	 * @param type $id
	 */
	public function id_column_display( $col_name, $id ) {
		if( $col_name == 'id' )
			echo $id;
	}


	/**
	 * Register Thumbnail column
	 * 
	 * @param array $cols
	 * @return type
	 */
	public function thumb_column_define( $cols ) {
		$cols['thumbnail'] = __( 'Thumbnail', 'mtt' );
		return $cols;
	}


	/**
	 * Render Thumbnail column
	 * 
	 * @param type $column_name
	 * @param type $post_id
	 */
	public function thumb_column_display( $column_name, $post_id ) {
		$width = $height = 
				!empty( $this->params['postpageslist_enable_thumb_column']['proportion'] ) 
				? $this->params['postpageslist_enable_thumb_column']['proportion'] : '50';

		if( 'thumbnail' == $column_name ) {
			// FEATURED IMAGE
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

			// ATTACHED IMAGE
			$attachments = get_children( array(
				'post_parent'	 => $post_id,
				'post_type'		 => 'attachment',
				'post_mime_type' => 'image',
				'numberposts'	 => -1,
				'orderby'		 => 'menu_order' )
			);
			$count = '';
			// Show only if option is set
			if( $attachments && count($attachments)>1 && !empty( $this->params['postpageslist_enable_thumb_column']['count'] ) )
				$count = '<br><small>total: '. count($attachments) . '</small>';
			if( $thumbnail_id ) {
				$thumb = sprintf(
						'%s<br>%s %s',
						__( 'Featured', 'mtt' ),
						wp_get_attachment_image( $thumbnail_id, array( $width, $height ), true ),
						$count
				);
				
			}
			elseif( $attachments ) {
				$att_id = key( $attachments );
				$thumb = sprintf(
						'%s<br>%s %s',
						__( 'Attached', 'mtt' ),
						wp_get_attachment_image( $att_id, array( $width, $height ), true ),
						$count
				); 
			}

			if( isset( $thumb ) )
				echo $thumb;
		}
	}


	/**
	 * Print CSS to Post listing screen
	 * 
	 */
	public function id_width_and_status_colors() {
		$output = '';
		if( !empty( $this->params['postpageslist_enable_id_column'] ) )
			$output .= "\t" . '.column-id{width:3em} ' . "\r\n";

		if( !empty( $this->params['postpageslist_enable_thumb_column']['enabled'] ) )
			$output .= "\t" 
				. '.column-thumbnail{width:' 
				. $this->params['postpageslist_enable_thumb_column']['width'] 
				. '} .thumbnail.column-thumbnail img { max-width: '
				. $this->params['postpageslist_enable_thumb_column']['proportion'] 
				.'px; }' . "\r\n";

		if( !empty( $this->params['postpageslist_title_column_width'] ) )
			$output .= "\t" 
				. '.column-title {width: ' 
				. $this->params['postpageslist_title_column_width'] 
				. '} ' . "\r\n";

		if( 
				!empty( $this->params['postpageslist_status_draft'] ) 
				&& '#' != $this->params['postpageslist_status_draft'] 
			)
			$output .= "\t" 
				. '.status-draft {background: ' 
				. $this->params['postpageslist_status_draft'] 
				. ' !important} ' . "\r\n";

		if( 
				!empty( $this->params['postpageslist_status_pending'] ) 
				&& '#' != $this->params['postpageslist_status_pending'] 
			)
			$output .= "\t" 
				. '.status-pending {background: ' 
				. $this->params['postpageslist_status_pending'] 
				. ' !important} ' . "\r\n";

		if( 
				!empty( $this->params['postpageslist_status_future'] ) 
				&& '#' != $this->params['postpageslist_status_future'] 
			)
			$output .= "\t" 
				. '.status-future {background: ' 
				. $this->params['postpageslist_status_future'] 
				. ' !important} ' . "\r\n";

		if( 
				!empty( $this->params['postpageslist_status_private'] ) 
				&& '#' != $this->params['postpageslist_status_private'] 
			)
			$output .= "\t" 
				. '.status-private {background: ' 
				. $this->params['postpageslist_status_private'] 
				. ' !important} ' . "\r\n";

		if( 
				!empty( $this->params['postpageslist_status_password'] ) 
				&& '#' != $this->params['postpageslist_status_password'] 
			)
			$output .= "\t" 
				. '.post-password-required {background: ' 
				. $this->params['postpageslist_status_password'] 
				. ' !important} ' . "\r\n";

		if( 
				!empty( $this->params['postpageslist_status_others'] ) 
				&& '#' != $this->params['postpageslist_status_others'] 
			)
			$output .= "\t" 
				. '.author-other {background: ' 
				. $this->params['postpageslist_status_others'] 
				. ' !important} ' . "\r\n";

		if( '' != $output )
			echo '<style type="text/css">' . "\r\n" . $output . '</style>' . "\r\n";
	}


}