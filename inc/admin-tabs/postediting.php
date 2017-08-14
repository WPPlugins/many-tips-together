<?php
/**
 * POST EDITING options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'post_editing' );

$options_panel->Title( __( 'Post and Page Editing', 'mtt' ) );


$options_panel->addCheckbox( 
    'postpages_enable_page_excerpts', 
    array(
        'name' => __( 'Pages: enable Excerpt', 'mtt' ),
        'desc' => sprintf( 
				__( 'Tip via: %s', 'mtt' ), 
				B5F_MTT_Utils::make_tip_credit( 
						'Smashing Magazine', 
						'http://wp.smashingmagazine.com/2011/05/10/new-wordpress-power-tips-for-template-developers-and-consultants/' 
				) 
		),
        'std'  => false
    )
);

$options_panel->addParagraph( 
    sprintf( 
        '<b>Post-pages: revisions and auto-save</b><br>Number of revisions to maintain and auto-save interval must be set on the file wp-config.php, see %s', 
		B5F_MTT_Utils::make_tip_credit( 
				'tutorial', 
				'https://woorkup.com/disable-limit-wordpress-post-revisions/' 
		)
    ) 
);


$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __( 'POST CATEGORIES', 'mtt' ) 
    ) 
);

$options_panel->addCheckbox( 
    'postpages_enable_category_count', 
    array(
        'name' => __( 'Enable count', 'mtt' ),
        'desc' => sprintf( 
				__( 'Tip via: %s.%s. Also using CCT option bellow', 'mtt' ), 
				B5F_MTT_Utils::make_tip_credit( 
						'Stack Overflow', 
						'http://stackoverflow.com/a/15845723/1287812' 
				),
				"<div class='img-help'><img src='{$plugin_url}images/postediting-category-count.jpg' /></div>"
		),
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'postpages_enable_category_fixed', 
    array(
        'name' => __( 'Category Checklist Tree', 'mtt' ),
        'desc' => sprintf( 
				__( "This is the code from scribu's plugin: %s", 'mtt' ), 
				B5F_MTT_Utils::make_tip_credit( 
						'Category Checklist Tree', 
						'http://wordpress.org/plugins/category-checklist-tree/' 
				) 
		),
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'postpages_enable_category_noparent', 
    array(
        'name' => __( 'Disable selection of parent categories', 'mtt' ),
        'desc' => sprintf( 
				__( "Use the previous to use this one. Tip via: %s", 'mtt' ), 
				B5F_MTT_Utils::make_tip_credit( 
						'WordPress Answers', 
						'http://wordpress.stackexchange.com/a/58525/12615' 
				) 
		),
        'std'  => false
    )
);



$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>%s', 
        __( 'PUBLISH META BOX', 'mtt' ) ,
		"<div class='img-help desc-field'><img src='{$plugin_url}images/postediting-publish-metabox.jpg' /></div>"
    ) 
);


$options_panel->addCheckbox( 
    'postpages_move_author_metabox', 
    array(
        'name' => __( 'Posts-Pages: move the Author metabox into the Publish metabox', 'mtt' ),
        'desc' => sprintf( __( 'Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/1567/12615' ) ),
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'postpages_move_comments_metabox', 
    array(
        'name' => __( 'Posts-Pages: move the Discussion metabox into the Publish metabox', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


//$options_panel->addParagraph( 
//    sprintf( 
//        '<hr /><h4>%s</h4><p class="desc-field">%s %s</p>', 
//        __( 'HIDE FROM PUBLISH METABOX', 'mtt' ), 
//        __('Example with Comments and Author moved inside.', 'mtt' ),
//		"<div class='img-help'><img src='{$plugin_url}images/postediting-publish-metabox.jpg' /></div>"
//    ) 
//);


$options_panel->addCheckboxList( 
    'postpages_hide_from_publish', 
    array(
        'status'      => __( 'Status' ),
        'visibility'  => __( 'Visibility' ),
        'published'   => __( 'Published On' ),
		'revisions'   => __( 'Revisions' )
     ), 
     array(
        'name' => __( 'Hide elements'),
        'desc' => __( 'Affects ALL post types. Select items to hide:', 'mtt' ),
        'class' => 'no-toggle',
        'std'  => false
     )
);


$options_panel->addParagraph( 
    sprintf( 
        "<hr /><h4>%s</h4><p class='desc-field'><i>%s</i></p>", 
        __( 'REMOVE METABOXES : POSTS and PAGES<br />', 'mtt' ), 
        __("Although Adminimize can handle this filtering by roles, it only hides the meta box and it doen't removes it from the Screen Options", 'mtt') 
    ) 
);


$options_panel->addSelect( 
    'postpages_disable_mbox_author', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'AUTHOR', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addSelect( 
    'postpages_disable_mbox_comment_status', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'DISCUSSION', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addSelect( 
    'postpages_disable_mbox_comment', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'COMMENTS', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addSelect( 
    'postpages_disable_mbox_custom_fields', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'CUSTOM FIELDS', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addSelect( 
    'postpages_disable_mbox_featured_image', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'FEATURED IMAGE', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addSelect( 
    'postpages_disable_mbox_revisions', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'REVISIONS', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addSelect( 
    'postpages_disable_mbox_slug', 
    array(
        'none'          => 'none',
        'post'          => 'post',
        'page'          => 'page',
        'post_and_page' => 'post and page'
    ), 
    array(
        'name' => __( 'SLUG', 'mtt' ),
        'desc' => '',
        'class' => 'no-fancy',
        'std'  => null
    )
);


$options_panel->addParagraph( 
    sprintf(
        '<h4>%s</h4>',
        __( 'REMOVE METABOXES : PAGES ONLY', 'mtt' ) 
    ) 
);


$options_panel->addCheckbox( 
        'postpages_disable_mbox_attributes', 
        array(
        'name' => __( 'ATTRIBUTES', 'mtt' ),
        'desc' => '',
        'std'  => false
        )
);


$options_panel->addParagraph( sprintf('<h4>%s</h4>', __( 'REMOVE METABOXES : POSTS ONLY', 'mtt' ) ) );


$options_panel->addCheckbox( 
    'postpages_disable_mbox_format', 
    array(
        'name' => __( 'FORMAT', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'postpages_disable_mbox_category', 
    array(
        'name' => __( 'CATEGORY', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'postpages_disable_mbox_excerpt', 
    array(
        'name' => __( 'EXCERPT', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'postpages_disable_mbox_tags', 
    array(
        'name' => __( 'TAGS', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'postpages_disable_mbox_trackbacks', 
    array(
        'name' => __( 'TRACKBACKS', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->CloseTab();