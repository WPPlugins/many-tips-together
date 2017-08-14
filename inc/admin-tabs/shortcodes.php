<?php
/**
 * SHORTCODES options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'shortcodes' );

$options_panel->Title( __( 'Shortcodes', 'mtt' ) );


$options_panel->addCheckbox( 
    'shortcodes_everywhere', 
    array(
        'name' => __( 'Enable shortcodes everywhere', 'mtt' ),
        'desc' => sprintf( __( 'In the text widget, excerpts, content and category/tag/taxonomy descriptions. Code by: %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'Thomas Scholz', 'https://github.com/toscho/WordPress-Shortcodes/' ) ),
        'std'  => false
    )
);


$tubedesc = '<div class="desc">' . __( 'Usage:', 'mtt' ); 		
$tubedesc .= __('<code>[mttube id="VIDEO-ID/VIDEO-URL"]</code><br />(old shortcode ([poptube]) parses with this one)<br /><br />', 'mtt' );
$tubedesc .= __('This will show the video thumbnail and only load YouTube player <b>after</b> clicking<br />', 'mtt' );
$tubedesc .= sprintf( __( 'Code by Amit Agarwal, from the awesome blog %s', 'mtt' ), B5F_MTT_Utils::make_tip_credit( 'Digital Inspiration', 'https://www.labnol.org/internet/light-youtube-embeds/27941/' ) );
$tubedesc .= '</div>';
$options_panel->addCheckbox( 'shortcodes_tube', array(
    'name' => __( 'Enable YouTube shortcode', 'mtt' ),
    'desc' => $tubedesc,
    'std'  => false
        )
);

$usage = __('Use Google Docs to preview PDF, Word, Excel documents online. <a href="http://docs.google.com/viewer?url=https://wordpress.org/about/history/chapter3.pdf" target="_blank">Example</a>.<br /><br />', 'mtt');
$usage .= __('Usage 1: <code>[gdocs url="http://www.domain.com/document.pdf" class="my-doc-class" text="View Document"]</code><br /><br />', 'mtt');
$usage .= __('Usage 2: <code>[gdocs url="http://www.domain.com/document.pdf" embed="true" width="680px" height="800px"]</code>.<br /><br />', 'mtt');
$usage .= __('<b>url</b>: required<br /><b>class</b>: optional (default: google-docs-viewer)<br /><b>text</b>: renders a link (default: View document)<br /><b>embed</b>: makes the document viewable on site.<br /><b>width</b>: iframe width, in pixels (default: 480px)<br /><b>height</b>: iframe height (default: 680px)', 'mtt');
$options_panel->addCheckbox( 
    'shortcodes_gdocs',
    array(
        'name' => __('Enable Google Docs Preview Document shortcode', 'mtt'),
        'desc' => $usage,
         'std' => false
    )
);


$options_panel->CloseTab();