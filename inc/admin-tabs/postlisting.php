<?php
/**
 * POST LISTING options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'post_listing' );

$options_panel->Title( __( 'Post, Page and Custom Post Types listing', 'mtt' ) );


/*******************************************************************************
 * GENERAL
 ******************************************************************************/

$options_panel->addCheckbox( 
    'postpageslist_enable_category_count', 
    array(
        'name' => __( 'Posts: enable Category count', 'mtt' ),
        'desc' => sprintf( 
				__( 'Inspired by: %s.%s', 'mtt' ), 
				B5F_MTT_Utils::make_tip_credit( 
						'Stack Overflow', 
						'http://stackoverflow.com/a/15845723/1287812' 
				),
				"<div class='img-help'><img src='{$plugin_url}images/postlisting-category-count.jpg' /></div>"
		),
        'std'  => false
    )
);


$options_panel->addCheckbox(
    'postpageslist_duplicate_del_revisions', 
    array(
        'name' => __( 'All types: enable Duplicate Post and Delete Revisions', 'mtt' ),
        'desc' => sprintf(
                __( 'Based on: %s.%s', 'mtt' )
                , B5F_MTT_Utils::make_tip_credit( 'GD Press Tools', 'http://wordpress.org/extend/plugins/gd-press-tools/' ),
				"<div class='img-help'><img src='{$plugin_url}images/postlisting-duplicate-revision.jpg' /></div>"
        ),
        'std'  => false
    )
);
 

/*******************************************************************************
 * CUSTOM COLUMNS
 ******************************************************************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'CUSTOM COLUMNS', 'mtt' ) ) );


$options_panel->addCheckbox( 
    'postpageslist_enable_id_column', 
    array(
        'name' => __( 'All types: add ID column', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addText( 
    'postpageslist_title_column_width', 
    array(
        'name' => __( 'All types: width of the Title column', 'mtt' ),
        'desc' => __( 'Sometimes the Title column gets shrinked by other columns, you may change this here. Use px, em or %, i.e. 200px, 50%', 'mtt' ),
        'std'  => '',
		'validate_func' => 'validate_css_num_value',
    )
);


$Thumbnail_column_fields[] = $options_panel->addText( 
    'proportion', 
    array(
        'name' => __( 'Proportion of the thumbnails', 'mtt' ),
        'desc' => __( 'Used for width and height. The scale is proportional, this value is used for the bigger side.', 'mtt' ),
        'std'  => ''
    ), 
    true
);

$Thumbnail_column_fields[] = $options_panel->addText( 
    'width', 
    array(
        'name' => __( 'Width of the column', 'mtt' ),
        'desc' => __( 'Depending on the proportion you may need this. Use px, em or %, i.e. 200px, 50%', 'mtt' ),
        'std'  => ''
    ), 
    true
);

$Thumbnail_column_fields[] = $options_panel->addCheckbox( 
    'count', 
    array(
        'name' => __( 'Show total number of attachments', 'mtt' ),
        'desc' => sprintf(
				__( 'If greater than 1.%s', 'mtt' ),
				"<div class='img-help'><img src='{$plugin_url}images/postlisting-attach-column.jpg' /></div>"
		),
        'std'  => false
    ),
	true
);

$options_panel->addCondition( 
    'postpageslist_enable_thumb_column', 
    array(
        'name' => __( 'All types: add Thumbnail column', 'mtt' ),
        'desc' => __( 'Shows the featured image or, if not set, the first attached.', 'mtt' ),
        'fields' => $Thumbnail_column_fields,
        'std'    => false,
        'validate_func' => 'validate_postlisting'
    )
);


/*******************************************************************************
 * CONTENT COLORS
 ******************************************************************************/
$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __( 'CUSTOM COLORS FOR DIFFENT TYPES OF CONTENT', 'mtt' ) 
    ) 
);


$options_panel->addColor( 
    'postpageslist_status_draft', 
    array(
        'name' => __('Posts-Pages Draft color', 'mtt'),
        'desc' => ''
    )
);


$options_panel->addColor( 
    'postpageslist_status_pending', 
    array(
        'name' => __('Posts-Pages Pending color', 'mtt'),
        'desc' => ''
    )
);


$options_panel->addColor( 
    'postpageslist_status_future', 
    array(
        'name' => __('Posts-Pages Future color', 'mtt'),
        'desc' => ''
    )
);


$options_panel->addColor( 
    'postpageslist_status_private', 
    array(
        'name' => __('Posts-Pages Private color', 'mtt'),
        'desc' => ''
    )
);


$options_panel->addColor( 
    'postpageslist_status_password', 
    array(
        'name' => __('Posts-Pages Password Protected color', 'mtt'),
        'desc' => ''
    )
);


$options_panel->addColor( 
    'postpageslist_status_others', 
    array(
        'name' => __('Posts-Pages Other Author\'s color', 'mtt'),
        'desc' => ''
    )
);


$options_panel->CloseTab();