<?php

/**
 * Fired during plugin activation
 *

 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/includes
 */


/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.0.1
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/includes

 */
class Tuxedo_Importer_Activator
{

  /**
   *  Actions to be performed when the plugin is activated
   *
   * @since    0.0.1
   */
    public static function activate()
    {
        // Schedules the import cronjob at midnight everyday
        if (!wp_next_scheduled(TUXEDO_IMPORTER_IMPORT_ACTION_NAME)) {
            wp_schedule_event(strtotime('00:00:00'), 'daily', TUXEDO_IMPORTER_IMPORT_ACTION_NAME);
        }
    }
}
