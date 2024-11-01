<?php

use Tuxedo_Importer\Tuxedo;

/**
 * The admin-specific functionality of the plugin.
 *

 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/admin

 */
class Tuxedo_Importer_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    0.0.1
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


    /**
     * Initialize the class and set its properties.
     *
     * @since    0.0.1
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $saved_version = get_option('tuxedo_importer_version');

        add_action('init', array($this, 'tuxedo_importer_register_custom_post_types'));
        add_action('admin_menu', array($this, 'tuxedo_importer_admin_menu'));
        add_action('admin_init', array($this, 'tuxedo_register_settings'));
        add_action('tuxedo_force_import', array($this, 'wp_cron_tuxedo_import'), 10, 1);
        add_action('tuxedo_delete_shows', array($this, 'wp_cron_tuxedo_delete_shows'), 10, 1);
        add_action('tuxedo_delete_images', array($this, 'wp_cron_tuxedo_delete_images'), 10, 1);
        add_action('tuxedo_delete_events', array($this, 'wp_cron_tuxedo_delete_events'), 10, 1);

        add_action('admin_post_tuxedo_single_import', array($this, 'tuxedo_single_import'));

        if (!$saved_version || $saved_version != $this->version) {
            $this->tuxedo_importer_upgrade($saved_version);
            update_option('tuxedo_importer_version', $this->version);
        }
    }

    /**
     * Adds the admin menu
     *
     * @param   void
     * @return  void
     */
    function tuxedo_importer_admin_menu()
    {
        add_menu_page(__("Tuxedo", 'tuxedo-importer'), __("Tuxedo", 'tuxedo-importer'), 'manage_options', 'tuxedo', array($this, 'tuxedo_importer_options_form'), 'data:image/svg+xml;base64,' . base64_encode('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve">
            <g>
            <polygon points="6.9,18 11,18 11,2 2.6,2 2.6,5.8 6.9,5.8 	" fill="black"/>
            <path d="M15.1,13.3c-1.3,0-2.3,1-2.3,2.3s1,2.3,2.3,2.3s2.3-1,2.3-2.3S16.3,13.3,15.1,13.3z" fill="black"/>
            <circle cx="15.1" cy="4.4" r="2.3" fill="black"/>
            <path d="M15.1,7.7c-1.3,0-2.3,1-2.3,2.3s1,2.3,2.3,2.3s2.3-1,2.3-2.3S16.3,7.7,15.1,7.7z" fill="black"/>
            </g>
        </svg>'));
        add_submenu_page('tuxedo', __("Canaux", 'tuxedo-importer'), __("Canaux", 'tuxedo-importer'), 'manage_options', 'edit.php?post_type=tuxedo-channel');
        add_submenu_page('tuxedo', __("Spectacles", 'tuxedo-importer'), __("Spectacles", 'tuxedo-importer'), 'manage_options', 'edit.php?post_type=tuxedo-show');
        add_submenu_page('tuxedo', __("Représentations", 'tuxedo-importer'), __("Représentations", 'tuxedo-importer'), 'manage_options', 'edit.php?post_type=tuxedo-event');
        add_submenu_page('tuxedo', __("Résultats", 'tuxedo-importer'), __("Résultats", 'tuxedo-importer'), 'manage_options', 'tuxedo_logs', array($this, 'tuxedo_importer_logs'));
        add_submenu_page('tuxedo', __("Instructions", 'tuxedo-importer'), __("Instructions", 'tuxedo-importer'), 'manage_options', 'tuxedo_instructions', array($this, 'tuxedo_importer_instructions'));
    }

    function tuxedo_register_settings()
    {
        $this->tuxedo_importer_enqueue_styles();
        $this->tuxedo_importer_enqueue_scripts();

        register_setting('tuxedo_importer_plugin_options', 'tuxedo_importer_plugin_options', 'tuxedo_importer_plugin_options_validate');
        add_settings_section('tuxedo_api_settings', 'Paramètres', array($this, 'tuxedo_plugin_section_text'), 'tuxedo_importer_plugin');

        add_settings_field('tuxedo_plugin_setting_account_name', 'Nom du compte', array($this, 'tuxedo_plugin_setting_account_name'), 'tuxedo_importer_plugin', 'tuxedo_api_settings');
        add_settings_field('tuxedo_plugin_setting_username', 'Nom d\'utilisateur', array($this, 'tuxedo_plugin_setting_username'), 'tuxedo_importer_plugin', 'tuxedo_api_settings');
        add_settings_field('tuxedo_plugin_setting_password', 'Mot de passe', array($this, 'tuxedo_plugin_setting_password'), 'tuxedo_importer_plugin', 'tuxedo_api_settings');
        add_settings_field('tuxedo_plugin_setting_channel', 'Canal', array($this, 'tuxedo_plugin_setting_channel'), 'tuxedo_importer_plugin', 'tuxedo_api_settings');
        //add_settings_field('tuxedo_plugin_setting_domain', 'Url de la billetterie', array($this, 'tuxedo_plugin_setting_domain'), 'tuxedo_importer_plugin', 'tuxedo_api_settings');
        add_settings_field('tuxedo_plugin_setting_image', 'Télécharger les images?', array($this, 'tuxedo_plugin_setting_image'), 'tuxedo_importer_plugin', 'tuxedo_api_settings', array('class' => 'image_checkbox'));
        add_settings_field('tuxedo_plugin_setting_show_url', 'Créer un url pour chaque spectacle', array($this, 'tuxedo_plugin_setting_show_url'), 'tuxedo_importer_plugin', 'tuxedo_api_settings', array('class' => 'show_url'));
        add_settings_field('tuxedo_plugin_setting_singles', 'Utiliser le gabarit du plugin pour les spectacles<span class="tuxedo__instructions">(Si cette case n\'est pas cochée vous devez créer un gabarit single-tuxedo-show.php dans votre thème)</span>', array($this, 'tuxedo_plugin_setting_singles'), 'tuxedo_importer_plugin', 'tuxedo_api_settings', array('class' => 'singles_checkbox'));
    }

    function tuxedo_plugin_setting_account_name()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_account_name' name='tuxedo_importer_plugin_options[account_name]' type='text' value='" . esc_attr($options['account_name']) . "' required/>";
    }

    function tuxedo_plugin_setting_username()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_username' name='tuxedo_importer_plugin_options[username]' type='text' value='" . esc_attr($options['username']) . "' required/>";
    }

    function tuxedo_plugin_setting_password()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_password' name='tuxedo_importer_plugin_options[password]' type='password' value='" . esc_attr($options['password']) . "' required/>";
    }

    function tuxedo_plugin_setting_channel()
    {
        // Ensure that we have the required options to make a proper request
        $options = get_option('tuxedo_importer_plugin_options');
        $selectedChannels =  $options['channel'];
        if (is_string($selectedChannels)) {
            $selectedChannels = explode(',', $selectedChannels);
        }

        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->updateChannels();

        if (empty($options['account_name']) || empty($options['username']) || empty($options['password'])) {
            echo "Sauvegardez un nom de compte, un nom<br/>d'utilisateur et un mot de passe valide pour voir<br/>la liste des canaux";
            return;
        }
        // Get channels from API
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $channels = json_decode($tuxedo->request('channels', 'GET'));

        if (is_object($channels)) {
            echo "Aucun canal n'a été trouvé";
            return;
        }

        // Show channels
        foreach ($channels as $channel) {
            $show_url = false;
            echo '<div class="channel_item" style="margin-bottom: 3px;"><input class="channel_checkbox" id="tuxedo_plugin_setting_channel_' . $channel->id . '"';
            if (in_array($channel->id, $selectedChannels)) {
                echo 'checked="checked"';
                $show_url = true;
            }
            echo ' type="checkbox" name="tuxedo_importer_plugin_options[channel][]" value="' . $channel->id . '" />';
            echo '<label for="tuxedo_plugin_setting_channel_' . $channel->id . '">' . $channel->name . '</label>';
            //echo '<input class="channel_url" style="margin-bottom: 10px;'. (!$show_url ? 'display:none':'display:block;').'" type="text" name="tuxedo_importer_plugin_options[channel_url][' . $channel->id . ']" value="' . (isset($options['channel_url'][$channel->id]) ? $options['channel_url'][$channel->id] : '') . '" placeholder="Url de la billeterie" />';
            echo '</div>';
        }

        $args = [
            'post_type' => 'tuxedo-channel',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ];
        $query = new WP_Query($args);
        $showWarningUrl = false;
        if ($query->have_posts()) {
            foreach ($query->posts as $post_id) {
                $url = get_field('url_ticket', $post_id);
                if (!$url) {
                    $showWarningUrl = true;
                    break;
                }
            }
        }
        if ($showWarningUrl) {
            echo '<div class="tuxedo__warning" style="color:red;">Attention, vous devez entrer une url de billeterie pour chaque canal dans la section <a href="' . admin_url('edit.php?post_type=tuxedo-channel') . '">Canaux</a></div>';
        }
    }

    function tuxedo_plugin_setting_domain()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_domain' name='tuxedo_importer_plugin_options[domain]' type='text' value='" . esc_attr($options['domain']) . "' required/>";
    }

    function tuxedo_plugin_setting_image()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_image' name='tuxedo_importer_plugin_options[image]' type='checkbox' value='1' " .  (isset($options['image']) && $options['image'] == 1 ? 'checked' : '') . "/>";
    }

    function tuxedo_plugin_setting_show_url()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_show_url' name='tuxedo_importer_plugin_options[show_url]' type='checkbox' value='1' " .  (isset($options['show_url']) && $options['show_url'] == 1 ? 'checked' : '') . "/>";
    }

    function tuxedo_plugin_setting_singles()
    {
        $options = get_option('tuxedo_importer_plugin_options');
        echo "<input id='tuxedo_plugin_setting_singles' name='tuxedo_importer_plugin_options[singles]' type='checkbox' value='1' " .  (isset($options['singles']) && $options['singles'] == 1 ? 'checked' : '') . "/>";
    }

    function tuxedo_importer_options_form()
    {
        if (isset($_POST['tuxedo_run']) && check_admin_referer('tuxedo_run')) {
            wp_schedule_single_event(time(), "tuxedo_force_import", array(time()));
        }

        if (isset($_POST['tuxedo_delete_images']) && check_admin_referer('tuxedo_delete_images')) {
            wp_schedule_single_event(time(), "tuxedo_delete_images", array(time()));
        }

        if (isset($_POST['tuxedo_delete_shows']) && check_admin_referer('tuxedo_delete_shows')) {
            wp_schedule_single_event(time(), "tuxedo_delete_shows", array(time()));
        }

        if (isset($_POST['tuxedo_delete_events']) && check_admin_referer('tuxedo_delete_events')) {
            wp_schedule_single_event(time(), "tuxedo_delete_events", array(time()));
        }
        require_once 'partials/tuxedo-importer-admin-display.php';
    }

    function tuxedo_importer_logs()
    {
        require_once 'partials/tuxedo-importer-admin-logs.php';
    }
    function tuxedo_importer_instructions()
    {
        require_once 'partials/tuxedo-importer-admin-instructions.php';
    }

    function tuxedo_plugin_section_text()
    {
        echo '';
    }

    function tuxedo_importer_plugin_options_validate($input)
    {
        // $newinput['api_key'] = trim( $input['api_key'] );
        // if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
        //     $newinput['api_key'] = '';
        // }

        // return $newinput;
        return $input;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.0.1
     */
    public function tuxedo_importer_enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/tuxedo-importer-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    0.0.1
     */
    public function tuxedo_importer_enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/tuxedo-importer-admin.js', array('jquery'), $this->version, true);
    }

    /**
     * Register cron jobs
     */
    public function wp_cron_tuxedo_import()
    {
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->run();
    }

    /**
     * Runs the script to delete all the images associated with shows
     */
    public function wp_cron_tuxedo_delete_images()
    {
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->deleteAllImages();
    }

    /**
     * Runs the script to delete all the shows
     */
    public function wp_cron_tuxedo_delete_shows()
    {
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->deleteAllShows();
    }

    /**
     * Runs the script to delete all the events
     */
    public function wp_cron_tuxedo_delete_events()
    {
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->deleteAllEvents();
    }


    /**
     * Registers custom post types
     */
    public function tuxedo_importer_register_custom_post_types()
    {
        foreach (glob(__DIR__ . "/cpt/*.php") as $filename) {
            require_once $filename;
        }
    }

    public function tuxedo_importer_upgrade($saved_version)
    {
        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->upgrade($saved_version);
    }

    public function tuxedo_single_import()
    {
        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;

        if (!$post_id) {
            wp_redirect(admin_url('admin.php?page=tuxedo'));
            exit;
        }

        if (!in_array(get_post_type( $post_id ), ['tuxedo-show', 'tuxedo-event'])) {
            wp_redirect(admin_url('admin.php?page=tuxedo'));
            exit;
        }

        $tuxedo = new \Tuxedo_Importer\Tuxedo\Tuxedo_API();
        $tuxedo->single_import($post_id);

        wp_redirect(admin_url('post.php?post=' . $post_id . '&action=edit'));
        exit;
    }
}
