<?php
/**
 * Media Library custom columns
 *
 * @package    ManyTipsTogether
 * @subpackage MTT_Hook_Media
 */

class MTT_Media_Custom_Columns
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

		// COLUMN ID
		if( !empty( $options['media_image_id_column_enable'] ) )
		{
			add_filter(
					'manage_upload_columns', array( $this, 'id_column_define' )
			);
			add_action(
					'manage_media_custom_column', array( $this, 'id_column_display' ), 10, 2
			);
		}

		// COLUMN IMAGE SIZE
		if( !empty( $options['media_image_size_column_enable'] ) )
		{
			add_filter(
					'manage_upload_columns', 
					array( $this, 'size_column_define' )
			);
			add_action(
					'manage_media_custom_column', 
					array( $this, 'size_column_display' ), 10, 2
			);
		}

		// COLUMNS ID AND SIZE
		if(
				!empty( $this->params['media_image_id_column_enable'] )
				or !empty( $this->params['media_image_size_column_enable'] )
			)
			add_action(
					'admin_head-upload.php', array( $this, 'id_and_size_columns' )
			);


		// COLUMN LIST OF THUMBNAILS
		if( !empty( $options['media_image_thubms_list_column_enable'] ) )
		{
			add_filter(
					'manage_upload_columns', 
					array( $this, 'all_thumbs_column_define' )
			);
			add_action(
					'manage_media_custom_column', 
					array( $this, 'all_thumbs_column_display' ), 10, 2
			);
		}

		//BETTER ATTACHMENT
		if( !empty( $options['media_better_attachment'] ) )
		{
            require_once 'class-media-detach-reattach.php';
            new MTT_Media_Detach_Reattach();
		}

	}

	/**
	 * Add ID colum to wp-admin/upload.php
	 * 
	 * @param type $cols
	 * @return type
	 */
	public function id_column_define( $cols )
	{
		$in = array( "id"	 => "ID" );
		$cols	 = B5F_MTT_Utils::array_push_after( $cols, $in, 0 );
		return $cols;
	}


	/**
	 * Display ID column in wp-admin/upload.php
	 * 
	 * @param type $col_name
	 * @param type $post_id
	 */
	public function id_column_display( $col_name, $post_id )
	{
		if( $col_name == 'id' )
			echo $post_id;
	}


	/**
	 * Add size column to wp-admin/upload.php
	 * 
	 * @param array $columns
	 * @return type
	 */
	public function size_column_define( $columns )
	{
		$columns['dimensions'] = __( 'Dimensions', 'mtt' );
		return $columns;
	}


	/**
	 * Display size column in wp-admin/upload.php
	 * 
	 * @param type $column_name
	 * @param type $post_id
	 * @return type
	 */
	public function size_column_display( $column_name, $post_id )
	{
		if( 'dimensions' != $column_name || !wp_attachment_is_image( $post_id ) )
			return;
		
		list($url, $width, $height) = wp_get_attachment_image_src( $post_id, 'full' );
		
		echo "{$width}<span style=\"color:#aaa\"> &times; </span>{$height}";
	}


	/**
	 * Print custom columns CSS
	 * 
	 */
	public function id_and_size_columns()
	{
		$output = '';
		if( !empty( $this->params['media_image_id_column_enable'] ) )
			$output .= "\t" . '.column-id{width:5%} ' . "\r\n";

		if( !empty( $this->params['media_image_size_column_enable'] ) )
			$output .= "\t" . '.column-dimensions{width:10%} ' . "\r\n";


		if( '' != $output )
			echo '<style type="text/css">' . "\r\n" . $output . '</style>' . "\r\n";
	}


	/**
	 * Add all thumbs column to wp-admin/upload.php
	 * 
	 * @param array $columns
	 * @return string
	 */
	public function all_thumbs_column_define( $columns )
	{
		$columns['all_thumbs'] = 'All Thumbs';

		return $columns;
	}


	/**
	 * Display all thumbs column in wp-admin/upload.php
	 * 
	 * @param type $column_name
	 * @param type $post_id
	 * @return type
	 */
	public function all_thumbs_column_display( $column_name, $post_id )
	{
		if( 'all_thumbs' != $column_name || !wp_attachment_is_image( $post_id ) )
			return;

		$full_size = wp_get_attachment_image_src( $post_id, 'full' );
		echo '<div style="clear:both">FULL SIZE : ' . $full_size[1] . ' x ' . $full_size[2] . '</div>';

		$size_names = get_intermediate_image_sizes();

		foreach( $size_names as $name )
		{
			// TODO: CHECK THIS: http://wordpress.org/support/topic/wp_get_attachment_image_src-problem
			$the_list = wp_get_attachment_image_src( $post_id, $name );
//
			if( $the_list[3] )
				echo '<div style="clear:both"><a href="' . $the_list[0] . '" target="_blank">' . $name . '</a> : ' . $the_list[1] . ' x ' . $the_list[2] . '</div>';
		}
	}


}