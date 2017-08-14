<?php
/**
 * GENERAL SETTINGS options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'general' );

$options_panel->Title( __( 'General Settings', 'mtt' ) );


/***********************************
 *          FRONTEND
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'FRONTEND', 'mtt' ) ) );
$options_panel->addCheckbox( 'wpdisable_version_full', array(
        'name' => __( 'Completely eliminate WordPress version in &lt;head&gt;', 'mtt' ),
        'desc' => '',
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_version_number', array(
        'name' => __( 'Eliminate only the WordPress version number in &lt;head&gt;', 'mtt' ),
        'desc' => '',
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_scripts_versioning', array(
        'name' => __( 'Remove versioning from scripts and styles', 'mtt' ),
		'desc' => sprintf( __( 'Disables the suffix ?ver=NUMBER that WP appends to enqueued styles and scripts. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/96325/12615' ) ),
        'std'  => false
        )
);


/***********************************
 *          UPDATE NOTICES
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'UPDATES', 'mtt' ) ) );
$options_panel->addCheckbox( 'wpblock_update_wp', array(
        'name' => __( 'Block WordPress upgrade notice for non-admins', 'mtt' ),
        'desc' => sprintf( __( 'Do not bug non-admins, please! Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/13000/12615' ) ),
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpblock_update_wp_all', array(
        'name' => __( 'Block WordPress upgrade notice for everyone', 'mtt' ),
        'desc' => '',
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpblock_update_screen', array(
        'name' => __( 'Redirect About page after upgrading.', 'mtt' ),
        'desc' => __( 'After upgrading WordPress, the default behavior is redirecting to the About page. This option changes that to going back to the very Upgrade screen.', 'mtt' ),
        'std'  => false
        )
);


/***********************************
 *    PRIVACY and RESTRICTIONS
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'PRIVACY and RESTRICTIONS', 'mtt' ) ) );
$options_panel->addCheckbox( 'wpdisable_nourl', array(
        'name' => __( 'Hide blog URL from WordPress "phone home"', 'mtt' ),
		'desc' => sprintf( __( 'Filter out the blog URL from the data that is sent to wordpress.org - Check this %s to learn more.', 'mtt' ), B5F_MTT_Utils::make_tip_credit( __( 'article', 'mtt') , 'http://lynnepope.net/wordpress-privacy' ) ),
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_selfping', array(
        'name' => __( 'Disable Self Ping', 'mtt' ),
        'desc' => __( 'Prevents WordPress from sending pings/trackbacks to your own site.', 'mtt' ),
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_redirect_disallow', array(
        'name' => __( 'Redirect unauthorized attempts.', 'mtt' ),
		'desc' => sprintf( __( 'If the user tries to access an admin page directly via URL, e.g.: /wp-admin/plugins.php, instead of showing "you do not have persmissions", the browser is redirected to the frontend. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/57206/12615' ) ),
        'std'  => false
        )
);


/***********************************
 *          TAXONOMY COLUMNS
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'TAXONOMIES', 'mtt' ) ) );
$options_panel->addCheckbox( 'wptaxonomy_columns', array(
        'name' => __( 'Enable ID column', 'mtt' ),
        'desc' => sprintf( __( 'Show column ID in taxonomies screens. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/q/77532/12615' ) ),
        'std'  => false
        )
);


/***********************************
 *          RSS
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'RSS', 'mtt' ) ) );
$Enable_rss_delay[] = $options_panel->addText( 'time', array(
        'name' => __( 'Number of delay', 'mtt' ),
        'desc' => '',
        'std'  => ''
        ), true
);
$Enable_rss_delay[] = $options_panel->addSelect( 'period', array(
        'MINUTE' => __( 'MINUTE', 'mtt' ),
        'HOUR'   => __( 'HOUR', 'mtt' ),
        'DAY'    => __( 'DAY', 'mtt' ),
        'WEEK'   => __( 'WEEK', 'mtt' ),
        'MONTH'  => __( 'MONTH', 'mtt' ),
        'YEAR'   => __( 'YEAR', 'mtt' )
        ), array(
        'name' => __( 'Period of delay', 'mtt' ),
        'desc' => '',
        'class'=> 'no-fancy',
        'std'  => array( 'MINUTE' )
        ), true
);
$options_panel->addCondition( 'wprss_delay_publish_enable', array(
        'name'   => __( 'Delay RSS feed update', 'mtt' ),
        'desc'   => sprintf( __( 'This can give you time to make corrections after publishing a post, delaying the update in your RSS feed. Or you can make your content web exclusive for a larger period. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/1896/12615' ) ),
        'fields' => $Enable_rss_delay,
        'std'    => false,
		'validate_func' => 'validate_general_tab'
        )
);


/***********************************
 *          ASSORTED
 ***********************************/
//$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'ASSORTED', 'mtt' ) ) );

/***********************************
 *          AUTOCOWERKS
 ***********************************/
$options_panel->addParagraph( sprintf( '<hr /><h4>%s</h4>', __( 'AUTOCOWERKS', 'mtt' ) ) );
$options_panel->addCheckbox( 'wpdisable_smartquotes', array(
        'name' => __( 'Disable SmartQuotes', 'mtt' ),
        'desc' => __( 'Prevent the conversion of straight quotes into directional quotes.', 'mtt' ),
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_capitalp', array(
        'name' => __( 'Disable Capital P', 'mtt' ),
        'desc' => __( 'Prevents WordPress of auto-correcting mispellings of its name. Check this <a href="http://justintadlock.com/archives/2010/07/08/lowercase-p-dangit">article</a>', 'mtt' ),
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_autop', array(
        'name' => __( 'Disable Auto P', 'mtt' ),
        'desc' => __( 'Prevents WordPress from inserting automatic &lt;p&gt; tags in your code.', 'mtt' ),
        'std'  => false
        )
);
$options_panel->addCheckbox( 'wpdisable_wptitle', array(
        'name' => __( 'Remove WordPress from admin page titles', 'mtt' ),
		'desc' => sprintf( __( 'The browser title has "- WordPress" appended to it. This will remove it. Tip via: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'WordPress Answers', 'http://wordpress.stackexchange.com/a/17034/12615' ) ),
        'std'  => false
        )
);


/**
 * END
 */
$options_panel->CloseTab();