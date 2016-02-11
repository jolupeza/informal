<?php
/**
 * The file responsible for starting the Informal Admin plugin.
 *
 * The Informal Admin is a plugin that manage content type custom. This particular file is responsible for
 * including the necessary dependencies and starting the plugin.
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Informal Admin
 * Plugin URI:        https://github.com/jolupeza/informal
 * Description:       Informal Admin manage content types custom.
 * Version:           1.0.0
 * Author:            José Pérez
 * Author URI:        http://watson.pe
 * Text Domain:       informal-admin-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, then abort execution.
if (!defined('WPINC')) {
    die;
}

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path(__FILE__).'includes/class-informal-manager.php';

/**
 * Instantiates the Informal Manager class and then
 * calls its run method officially starting up the plugin.
 */
function run_informal_manager()
{
    $spmm = new Informal_Manager();
    $spmm->run();
}

// Call the above function to begin execution of the plugin.
run_informal_manager();