<?php
/**
 * WIDGETS options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'widgets' );

$options_panel->Title( __( 'Widgets', 'mtt' ) );


$options_panel->addText( 
    'widget_rss_update_timer', 
    array(
        'name' => __( 'RSS Widget: update timer (in minutes)', 'mtt' ),
        'desc' => sprintf( 
            __( 'Default is 12 hours (720 minutes), leave blank for not activating<br />Tip via: %s', 'mtt' ), 
            B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/1567/12615' ) 
        ),
        'std'  => '',
		'validate_func' => 'validate_rss_time'
    )
);


$options_panel->addCheckbox( 
    'widget_hide_description', 
    array(
        'name'   => __( 'Hide widgets descriptions', 'mtt' ),
        'desc'   => '',
//        'class' => 'no-toggle',
        'std'    => false
    )
);


$options_panel->addParagraph( 
    sprintf( 
        '<hr /><h4>%s</h4>', 
        __( 'REMOVE WIDGETS', 'mtt' ) 
    ) 
);


$widgets_defaults = array(
        'pages'           => __( 'Pages', 'mtt' ),
        'calendar'        => __( 'Calendar', 'mtt' ),
        'archives'        => __( 'Archives', 'mtt' ),
        'links'           => __( 'Links', 'mtt' ),
        'meta'            => __( 'Meta', 'mtt' ),
        'search'          => __( 'Search', 'mtt' ),
        'text'            => __( 'Text', 'mtt' ),
        'categories'      => __( 'Categories', 'mtt' ),
        'recent_posts'    => __( 'Recent Posts', 'mtt' ),
        'recent_comments' => __( 'Recent Comments', 'mtt' ),
        'rss'             => __( 'RSS', 'mtt' ),
        'tag_cloud'       => __( 'Tag Cloud', 'mtt' ),
        'nav_menu'        => __( 'Custom Menu', 'mtt' )
        );
$non_defaults = isset( $this->params['widgets_non_default'] ) ? $this->params['widgets_non_default']  : array();
$widgets_array = array_merge( $widgets_defaults, $non_defaults );

$options_panel->addCheckboxList( 
    'widget_remove', 
    $widgets_array, 
    array(
        'name' => '',
        'desc' => '',
        'class' => 'no-toggle',
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'widget_remove_role', 
    array(
        'name'   => __( 'Remove widgets only for non-administrators', 'mtt' ),
        'desc'   => __( 'You have to grant edit_theme_options capabilities to your editors for this to work.', 'mtt' ),
//        'class' => 'no-toggle',
        'std'    => false
    )
);


$options_panel->CloseTab();
