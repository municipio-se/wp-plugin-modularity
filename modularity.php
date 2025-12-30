<?php

/*
 * Plugin Name: Modularity
 * Plugin URI: -
 * Description: Modular component system for WordPress
 * Version: 6.16.5
 * Author: Kristoffer Svanmark, Sebastian Thulin
 * Author URI: -
 * Text domain: modularity
 *
 * Copyright (C) 2016
 */

define('MODULARITY_PATH', plugin_dir_path(__FILE__));
define('MODULARITY_URL', plugins_url('', __FILE__));

define('MODULARITY_TEMPLATE_PATH', MODULARITY_PATH . 'templates/');
define('MODULARITY_MODULE_PATH', MODULARITY_PATH . 'source/php/Module/');
define('MODULARITY_MODULE_URL', MODULARITY_URL . '/source/php/Module/');
define('MODULARITY_IS_MU', strpos(MODULARITY_PATH, WPMU_PLUGIN_DIR) === 0);
define("MODULARITY_LANGUAGES_PATH", plugin_basename(dirname(__FILE__)) . "/languages");

add_action('init', function () {
    if(MODULARITY_IS_MU) {
        load_muplugin_textdomain('modularity', MODULARITY_LANGUAGES_PATH);
    } else {
        load_plugin_textdomain('modularity', false, MODULARITY_LANGUAGES_PATH);
    }
});

// Autoload from plugin
if (file_exists(MODULARITY_PATH . 'vendor/autoload.php')) {
    require_once MODULARITY_PATH . 'vendor/autoload.php';
}
require_once MODULARITY_PATH . 'Public.php';

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('modularity');
    $acfExportManager->setExportFolder(MODULARITY_PATH . 'source/php/AcfFields/');
    $acfExportManager->autoExport(array(
        'mod-booking'               => 'group_56a89f42b432b',
        'mod-contact-info'          => 'group_56a0a3928c017',
        'mod-contact-contacts'      => 'group_5757b93da8d5c',
        'mod-contacts'              => 'group_5805e5dc0a3be',
        'mod-files'                 => 'group_5756ce3e48782',
        'mod-fileslist'             => 'group_5756ce3e48783',
        'mod-gallery'               => 'group_5666af6d26b7c',
        'mod-iframe'                => 'group_56c47016ea9d5',
        'mod-image'                 => 'group_570770ab8f064',
        'mod-inheritpost'           => 'group_56a8b4fd3567b',
        'mod-inlaylist'             => 'group_569e054a7f9c2',
        'mod-latest'                => 'group_56a8c4581d906',
        'mod-mainnews'              => 'group_569e401dd4422',
        'mod-notice'                => 'group_575a842dd1283',
        'mod-posts-displau'         => 'group_571dfd3c07a77',
        'mod-posts-filtering'       => 'group_571e045dd555d',
        'mod-posts-sorting'         => 'group_571dffc63090c',
        'mod-posts-source'          => 'group_571dfaabc3fc5',
        'mod-posts-taxonomydisplay' => 'group_630645d822841',
        'mod-rss'                   => 'group_59535d940706c',
        'mod-script'                => 'group_56a8b9eddfced',
        'mod-slider'                => 'group_56a5e99108991',
        'mod-table'                 => 'group_5666a2a71d806',
        'mod-text'                  => 'group_5891b49127038',
        'mod-video'                 => 'group_57454ae7b0e9a',
        'mod-map'                   => 'group_602400d904b59',
        'mod-curator'               => 'group_609b788ad04bb',
        'mod-spacer'                => 'group_611cffa40276a',
        'mod-table-block'           => 'group_60b8bf5bbc4d7',
        'mod-text-block'            => 'group_60ab6d6ba3621',
        'mod-hero'                  => 'group_614b3f1a751bf',
        'mod-hero-display-as'       => 'group_63ca5ed0cb7f4',
        'mod-logogrid'              => 'group_61bc951d73494',
        'mod-divider'               => 'group_62816d604ae46',
        'mod-all'                   => 'group_636e424039120',
        'mod-subscribe'             => 'group_641c51b765f4b',
        'mod-modal'                 => 'group_64a29154aa972',
        'mod-manual-input'          => 'group_64ff22b117e2c',

        # Deactivated
        'mod-social'           => 'group_56dedc26e5327',
        'mod-wpwidget'         => 'group_5729f4d3e7c7a',
        'mod-sites'            => 'group_58ecb6b6330f4',
        'mod-index'            => 'group_569ceab2c16ee',


    ));
    $acfExportManager->import();
});

// Start application
add_action('plugins_loaded', function () {
    if (class_exists('acf_pro') || class_exists('ACF')) {
        new Modularity\App();
    } else {
        if (file_exists(MODULARITY_PATH . 'plugins/acf/acf.php')) {
            require_once MODULARITY_PATH . 'plugins/acf/acf.php';
            new Modularity\App();
        } else {
            add_action('admin_notices', function () {
                echo '<div class="notice error"><p>' .
                __('To get the full expirience of the <strong>Modularity</strong> plugin, please activate the <a href="http://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields Pro</a> plugin.', 'modularity') .
                '</p></div>';
            });

            // Dummy ACF functions to prevent crash
            if (!function_exists('get_field')) { function get_field() { return false; } }
            if (!function_exists('the_field')) { function the_field() { } }
            if (!function_exists('get_fields')) { function get_fields() { return false; } }
            if (!function_exists('have_rows')) { function have_rows() { return false; } }
            if (!function_exists('the_row')) { function the_row() { return false; } }
            if (!function_exists('get_sub_field')) { function get_sub_field() { return false; } }
            if (!function_exists('acf_add_local_field_group')) { function acf_add_local_field_group() { } }
            if (!function_exists('acf_add_local_field')) { function acf_add_local_field() { } }
            if (!function_exists('acf_add_options_page')) { function acf_add_options_page() { } }
            if (!function_exists('acf_add_options_sub_page')) { function acf_add_options_sub_page() { } }
            if (!function_exists('acf_register_block_type')) { function acf_register_block_type() { } }
            if (!function_exists('get_field_object')) { function get_field_object() { return false; } }
            if (!function_exists('acf_get_setting')) { function acf_get_setting() { return false; } }
            if (!function_exists('acf_register_location_type')) { function acf_register_location_type() { } }
            if (!function_exists('acf_maybe_get_field')) { function acf_maybe_get_field() { return false; } }
        }
    }
}, 20);
