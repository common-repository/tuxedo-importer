<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.tuxedosolution.com/
 * @since             0.0.1
 * @package           Tuxedo_Importer
 *
 * @wordpress-plugin
 * Plugin Name:       Tuxedo Importer
 * Plugin URI:        https://www.tuxedosolution.com/
 * Description:       Plugin for daily importation of data from Tuxedo Ticketing Software
 * Version:           1.1.5
 * Author:            Tuxedo
 * Author URI:        https://www.tuxedosolution.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tuxedo-importer
 */

// If this file is called directly, abort.
if ( ! defined('WPINC') ) {
    die;
}

/**
 * Currently plugin version.
 */
define('TUXEDO_IMPORTER_VERSION', '1.1.5');

/**
 * Define various constants
 */
define('TUXEDO_IMPORTER_IMPORT_ACTION_NAME', 'tuxedo_importer_import_action');
define('TUXEDO_IMPORTER_CRON_SCHEDULE', 'tuxedo_importer_cron_schedule');
define('TUXEDO_IMPORTER_CRON_SCHEDULE_DURATION', 86400);


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function tuxedo_importer_activate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-tuxedo-importer-activator.php';
    Tuxedo_Importer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tuxedo-importer-deactivator.php
 */
function tuxedo_importer_deactivate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-tuxedo-importer-deactivator.php';
    Tuxedo_Importer_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'tuxedo_importer_activate');
register_deactivation_hook(__FILE__, 'tuxedo_importer_deactivate');

/**
 * Create a cron interval for this plugin
 */

// function tuxedo_importer_add_cron_interval($schedules)
// {
//     $schedules[TUXEDO_IMPORTER_CRON_SCHEDULE] = [
//         'interval'  => TUXEDO_IMPORTER_CRON_SCHEDULE_DURATION,
//         'display'   => "Every " . TUXEDO_IMPORTER_CRON_SCHEDULE_DURATION . " seconds"
//     ];
//     return $schedules;
// }

// add_filter('cron_schedules', 'tuxedo_importer_add_cron_interval');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-tuxedo-importer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.0.1
 */
function run_tuxedo_importer()
{
    $plugin = new Tuxedo_Importer();
    $plugin->run();
}

run_tuxedo_importer();
