<?php

// Include admin theme options if we are in admin
if (is_admin())
{
   require_once(FRAMEWORK_URL.'/theme_options.php');
}

// Include metabox functions if we are in admin
if (is_admin())
{
    require_once(FRAMEWORK_URL.'/metaboxes/metabox_functions.php');
}

// Include helpers
require_once(FRAMEWORK_URL.'/helpers.php');
// Include breadcrumb
if (!is_admin()) {
    require_once(FRAMEWORK_URL.'/functions/breadcrumb.php');
}

// Include main framework class
require_once(FRAMEWORK_URL.'/classes/SQueen.php');


/*
 * Configurable options on framework initialization
 */
$theme_args = array(
    'required_plugins' => array(
        // This is an example of how to include a plugin pre-packaged with a theme
        array(
                'name'                  => 'Buddypress', // The plugin name
                'slug'                  => 'buddypress', // The plugin slug (typically the folder name)
                'required'              => true, // If false, the plugin is only 'recommended' instead of required
                'version'               => '2.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
                'name'                  => 'bbPress', // The plugin name
                'slug'                  => 'bbpress', // The plugin slug (typically the folder name)
                'required'              => false, // If false, the plugin is only 'recommended' instead of required
                'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
          'name'                  => 'rtMedia', // The plugin name
          'slug'                  => 'buddypress-media', // The plugin slug (typically the folder name)
          'required'              => false, // If false, the plugin is only 'recommended' instead of required
          'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
          'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
          'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
          'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
                'name'                  => 'Woocommerce', // The plugin name
                'slug'                  => 'woocommerce', // The plugin slug (typically the folder name)
                'required'              => false, // If false, the plugin is only 'recommended' instead of required
                'version'               => '2.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
                'name'                  => 'Paid Memberships Pro', // The plugin name
                'slug'                  => 'paid-memberships-pro', // The plugin slug (typically the folder name)
                'required'              => false, // If false, the plugin is only 'recommended' instead of required
                'version'               => '1.7.2.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                //'source'                => get_template_directory() . '/framework/inc/paid-memberships-pro.zip',
                'external_url'          => ''
        ),
        array(
                'name'                  => 'Revolution Slider', // The plugin name
                'slug'                  => 'revslider', // The plugin slug (typically the folder name)
                'required'              => false, // If false, the plugin is only 'recommended' instead of required
                'version'               => '5.3.1.5', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'source'                => get_template_directory() . '/lib/revslider.zip',
                'external_url'          => ''
        ),
    )
);

//instance of our theme framework
$kleo_sweetdate = new SQueen($theme_args);

// Include frontend logic
require_once(FRAMEWORK_URL.'/frontend.php');
