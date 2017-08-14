<?php
/**
 * USERS and PROFILE options
 *
 * @package    ManyTipsTogether
 * @subpackage B5F_MTT_Admin
 */

$options_panel->OpenTab( 'user_profile' );

$options_panel->Title( __( 'User profile', 'mtt' ) );

$options_panel->addCheckbox( 
    'profile_h3_titles', 
    array(
        'name' => __( 'Remove All Titles', 'mtt' ),
        'desc' => __( 'Removes the titles: "Personal Options", "Name", "Contact Info" and "About Yourself"', 'mtt' ),
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'profile_descriptions', 
    array(
        'name' => __( 'Remove All Descriptions', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'profile_slim', 
    array(
        'name' => __( 'Remove Visual Editor, Admin Color Scheme and Keyboard Shortcuts', 'mtt' ),
        'desc' => __( 'My guess is that or you want them all, or you don\'t want them at all :)', 'mtt' ),
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'profile_hidden', 
    array(
        'name' => __( 'Completely hide the Personal Options block', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'profile_display_name', 
    array(
        'name' => __( 'Remove Display Name', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'profile_nickname', 
    array(
        'name' => __( 'Remove Nickname', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);

$options_panel->addCheckbox( 
    'profile_website', 
    array(
        'name' => __( 'Remove Website', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'profile_social', 
    array(
        'name' => __( 'Add <strong>Twitter</strong>-<strong>Facebook</strong>-<strong>LinkedIn</strong>', 'mtt' ),
        'desc' => __( 'New profile fields.', 'mtt' ),
        'std'  => false
    )
);


$options_panel->addCheckbox( 
    'profile_bio', 
    array(
        'name' => __( 'Hide the About Yourself title and Biographical Info box', 'mtt' ),
        'desc' => '',
        'std'  => false
    )
);


$options_panel->addCode( 
    'profile_css', 
    array(
        'name'   => __( 'Custom CSS', 'mtt' ),
        'desc'   => sprintf(
            __( 'Add your custom css to further stylize the Profile page.<br />Test <code>#wpwrap{}</code> with %s', 'mtt' )
            , B5F_MTT_Utils::make_tip_credit( __('some gradients', 'mtt'), 'http://www.colorzilla.com/gradient-editor/' )
        ),
        'std'    => '.class-name {}',
        'theme'  => 'dark',
        'syntax' => 'css'
    )
);


$options_panel->CloseTab();