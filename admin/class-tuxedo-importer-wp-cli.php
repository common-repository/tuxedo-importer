<?php

use Tuxedo_Importer\Tuxedo;

/**
 * Perform Tuxedo Import
 */
class Tuxedo_Importer_CLI
{

    /**
     * Import Tuxedo data
     *
     * ## EXAMPLE
     *
     *    wp tuxedo import
     */
    public function import()
    {
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->run();
    }
}

WP_CLI::add_command('tuxedo', 'Tuxedo_Importer_CLI');