<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *

 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<svg class="tuxedo__logo" xmlns="http://www.w3.org/2000/svg" width="112" height="31" viewBox="0 0 112 31">
    <g fill="none" fill-rule="evenodd">
        <g fill="#1d2327">
            <g>
                <path d="M16.22 6v30.124H8.112V13.746H0V6h16.22zm7.18 20.989c2.514 0 4.56 2.029 4.56 4.523 0 2.494-2.046 4.523-4.56 4.523-2.516 0-4.562-2.03-4.562-4.523 0-2.494 2.046-4.523 4.561-4.523zm81.959-11.628c3.242 0 5.872 2.608 5.872 5.824 0 3.216-2.63 5.824-5.872 5.824-3.243 0-5.872-2.608-5.872-5.824 0-3.216 2.63-5.824 5.872-5.824zm-51.443.162v6.552c0 1.229.816 2.022 1.876 2.022s1.875-.793 1.875-2.022v-6.552h3.018v6.552c0 2.993-1.957 4.933-4.893 4.933s-4.893-1.94-4.893-4.933v-6.552h3.017zm12.535 0l1.794 2.912h.326l1.795-2.912h3.506l-3.506 5.5 3.75 5.824H70.61L68.57 23.61h-.326l-2.039 3.236H62.7l3.75-5.824-3.506-5.5h3.507zm18.035 0v2.75h-4.974v1.618h4.649v2.588h-4.649v1.618h4.974v2.75h-7.991V15.523h7.991zm7.328 0c3.18 0 5.627 2.427 5.627 5.662s-2.447 5.661-5.627 5.661h-4.322V15.523h4.322zm-43.58 0v2.912h-3.385v8.411h-3.017v-8.411h-3.384v-2.912h9.785zm-24.835 1.139c2.515 0 4.56 2.029 4.56 4.523 0 2.494-2.045 4.523-4.56 4.523-2.515 0-4.56-2.03-4.56-4.523 0-2.494 2.045-4.523 4.56-4.523zm81.96 1.577c-1.64 0-2.97 1.32-2.97 2.946 0 1.627 1.33 2.946 2.97 2.946 1.64 0 2.97-1.32 2.97-2.946 0-1.627-1.33-2.946-2.97-2.946zm-13.463.197H90.51v5.499h1.386c1.37 0 2.528-1.133 2.528-2.75s-1.159-2.75-2.528-2.75zM23.399 6.335c2.515 0 4.56 2.029 4.56 4.523 0 2.494-2.045 4.523-4.56 4.523-2.515 0-4.56-2.03-4.56-4.523 0-2.494 2.045-4.523 4.56-4.523z" transform="translate(-120 -46) translate(120 40)"></path>
            </g>
        </g>
    </g>
</svg>

<?php
if (isset($_POST['tuxedo_run']) && check_admin_referer('tuxedo_run')) {
    ?>
        <div class="tuxedo__notice">
            <p>L'importation a commencé. Visiter  <a href="<?= admin_url('admin.php?page=tuxedo_logs'); ?>" target="_blank">la page résultats</a> dans quelque minutes pour voir les résultats de l'importation.</p>
        </div>
    <?php
}

if (isset($_POST['tuxedo_delete_images']) && check_admin_referer('tuxedo_delete_images')) {
    ?>
        <div class="tuxedo__notice">
            <p>La suppression des images à commencé.</p>
        </div>
    <?php
}

if (isset($_POST['tuxedo_delete_shows']) && check_admin_referer('tuxedo_delete_shows')) {
    ?>
        <div class="tuxedo__notice">
            <p>La suppression des spectacles à commencé. Visiter  <a href="<?= admin_url('edit.php?post_type=tuxedo-show'); ?>" target="_blank">la section Spectacles</a> dans quelque minutes pour confirmer que tous les spectacles ont été supprimés.</p>
        </div>
    <?php
}

if (isset($_POST['tuxedo_delete_events']) && check_admin_referer('tuxedo_delete_events')) {
    ?>
        <div class="tuxedo__notice">
            <p>La suppression des représentations à commencé. Visiter  <a href="<?= admin_url('edit.php?post_type=tuxedo-event'); ?>" target="_blank">la section Représentations</a> dans quelque minutes pour confirmer que toutes les représentations ont été supprimées.</p>
        </div>
    <?php
}
?>
<div class="tuxedo tuxedo--forms">
    <div class="tuxedo--forms__parameters">
        <form action="options.php" method="post">
            <?php
            settings_fields( 'tuxedo_importer_plugin_options' );
            do_settings_sections( 'tuxedo_importer_plugin' ); ?>
            <input name="submit" class="button button-primary" type="submit" value="Sauvegarder" />
        </form>
    </div>
    <div class="tuxedo--forms__buttons">
        <form action="admin.php?page=tuxedo" method="post">
            <?php
                wp_nonce_field('tuxedo_run');
                echo '<input type="hidden" value="true" name="tuxedo_run" />';
                submit_button('Forcer l\'importation');
            ?>
        </form>

        <form action="admin.php?page=tuxedo" method="post">
            <?php
                wp_nonce_field('tuxedo_delete_images');
                echo '<input type="hidden" value="true" name="tuxedo_delete_images" />';
                submit_button('Supprimer toutes les photos importées');
            ?>
        </form>

        <form action="admin.php?page=tuxedo" method="post">
            <?php
                wp_nonce_field('tuxedo_delete_shows');
                echo '<input type="hidden" value="true" name="tuxedo_delete_shows" />';
                submit_button('Supprimer tous les spectacles');
            ?>
        </form>

        <form action="admin.php?page=tuxedo" method="post">
            <?php
                wp_nonce_field('tuxedo_delete_events');
                echo '<input type="hidden" value="true" name="tuxedo_delete_events" />';
                submit_button('Supprimer toutes les représentations');
            ?>
        </form>
    </div>
</div>

