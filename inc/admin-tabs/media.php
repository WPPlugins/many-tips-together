<?php
/**
 * MEDIA options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'media' );

$options_panel->Title( __( 'Media', 'mtt' ) );


$options_panel->addParagraph( 
        sprintf( '<hr /><h4>%s</h4>%s', 
                __( 'MEDIA LIBRARY', 'mtt' ),
				"<div class='img-help desc-field'><a href='{$plugin_url}images/media-all.jpg' target='_blank'><img src='{$plugin_url}images/media-all.jpg' style='max-width:100%' /></a></div>"
        ) 
);


$options_panel->addCheckbox( 
        'media_image_bigger_thumbs', 
        array(
            'name' => __( 'Bigger thumbnails in the default column', 'mtt' ),
            'desc' => '',
            'std'  => false
        )
);


$options_panel->addCheckbox( 
        'media_image_id_column_enable', 
        array(
            'name' => __( 'Add ID column', 'mtt' ),
            'desc' => '',
            'std'  => false
        )
);


$options_panel->addCheckbox( 
        'media_image_size_column_enable', 
        array(
            'name' => __( 'Add image size column', 'mtt' ),
            'desc' => sprintf( 
                        __( 'Tip via: %s', 'mtt' ), 
                        B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/30894/12615' ) 
                    ),
            'std'  => false
        )
);


$options_panel->addCheckbox( 
        'media_image_thubms_list_column_enable', 
        array(
            'name' => __( 'Add a column that lists all thumbnails of the image, with direct link to it.', 'mtt' ),
            'desc' => sprintf( 
                    __( 'Tip via: %s', 'mtt' ), 
                    B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/7757/12615' ) 
                    ),
            'std'  => false
        )
);


$options_panel->addCheckbox( 
        'media_download_link', 
        array(
            'name' => __( 'Download link in row actions', 'mtt' ),
            'desc' => sprintf( 
                        __( 'Adds a download link to all items (Edit|Delete|View|Download). Tip via: %s. The difference here is that there is no external script for downloading, it has to be done with a mouse right click and selecting "Save as".', 'mtt' ), 
                        B5F_MTT_Utils::make_tip_credit( 
                                'WordPress Answers', 
                                'http://wordpress.stackexchange.com/q/30159/12615' 
                        ) 
                     ),
            'std'  => false
        )
);


$options_panel->addCheckbox( 
        'media_better_attachment', 
        array(
            'name' => __( 'Enables the re-attachment', 'mtt' ),
            'desc' => sprintf( 
                    __( 'Change the parent of the media file to another post/page<br />Unfortunately, this disables the capability to sort the column... Tip via: %s', 'mtt' ), 
                    B5F_MTT_Utils::make_tip_credit( 'WPEngineer', 'http://wpengineer.com/2165/small-extension-for-the-media-library/' ) 
                    ),
            'std'  => false
        )
);


$options_panel->addCheckbox( 
        'media_camera_exif', // leaving the old option name, funcionality changed
        array(
            'name' => __( 'Show media metadata (audio/image/video)', 'mtt' ),
            'desc' => __( 'Camera exif info, MP3 ID3 and video metadata are stored as post metadata, accessible via "_wp_attachment_metadata".', 'mtt' ),           
            'std'  => false
    )
);


$options_panel->addCheckboxList( 
        'media_remove_metaboxes', 
        array(
            'discussion'    => __( 'Discussion', 'mtt' ),
            'comments'      => __( 'Comments', 'mtt' ),
            'slug'          => __( 'Slug', 'mtt' ),
            'author'        => __( 'Author', 'mtt' )
        ), 
        array(
            'name' => __( 'Remove Meta Boxes', 'mtt' ),
            'desc' => '',
            'class' => 'no-toggle',                
            'std'  => false
    )
);


$options_panel->addParagraph( 
        sprintf( '<hr /><h4>%s</h4>', 
                __( 'IMAGES UPLOAD', 'mtt' ) 
        ) 
);

// MEDIA LIBRARY 3.5
global $wp_version;
if ( version_compare( $wp_version, '3.5', '>=' ) )
{
	$options_panel->addCheckbox( 
			'media_uploaded_to_this_post', 
			array(
				'name' => __( 'Make "Uploaded to this post" the default view', 'mtt' ),
				'desc' => sprintf( 
						__( 'Tip via: %s', 'mtt' ), 
						B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'https://wordpress.stackexchange.com/a/140284/12615' ) 
						),
				'std'  => false
		)
	);
}

/* BUGGY ATM.... NOT SURE IF ON MY SIDE OR WP SIDE * /
$options_panel->addCheckbox( 
        'media_include_extras_sizes', 
        array(
            'name' => __( 'Include all custom sizes', 'mtt' ),
            'desc' => sprintf( 
                    __( 'Lists all custom sizes in the Insert Media selector.<br />By default, it only displays <code>Thumbnail | Medium | Large | Full Size</code>. Tip via: %s', 'mtt' ), 
                    B5F_MTT_Utils::make_tip_credit( 'kucrut.org', 'https://kucrut.org/blog/add-custom-image-sizes-right-way' ) 
                    ),
            'std'  => false
    )
);
/**/
$options_panel->addCheckbox( 
        'media_sanitize_filename', 
        array(
            'name' => __( 'Sanitize filename', 'mtt' ),
            'desc' => sprintf( 
                    __( 'Removes symbols, spaces, latin and other languages characters from uploaded files and gives them "permalink" structure (clean characters, only lowercase and dahes)<br />Code by: %s', 'mtt' ), 
                    B5F_MTT_Utils::make_tip_credit( 'toscho', 'https://github.com/toscho/Germanix-WordPress-Plugin' ) 
                    ),
            'std'  => false
    )
);


$options_panel->CloseTab();