<?php

/**
 * Fired during plugin deactivation
 *

 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.0.1
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/includes

 */
class Tuxedo_Importer_Deactivator
{

  /**
   * Actions to be performed when the plugin is deactivated
   *
   * @since    0.0.1
   */
    public static function deactivate()
    {
        //Unschedules import cronjob
        $timestamp = wp_next_scheduled(TUXEDO_IMPORTER_IMPORT_ACTION_NAME);
        wp_unschedule_event($timestamp, TUXEDO_IMPORTER_IMPORT_ACTION_NAME);

        //Deletes all the imported content
        do_action("tuxedo_delete_shows");
        do_action("tuxedo_delete_events");
    }
}
