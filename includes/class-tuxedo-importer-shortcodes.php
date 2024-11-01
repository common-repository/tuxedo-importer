<?php

/**
 * Register all shortcodes
 *
 * @since      0.0.1
 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/includes
 */

/**
 * Register all shortcodes
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Tuxedo_Importer
 * @subpackage Tuxedo_Importer/includes
 */
class Tuxedo_Importer_Shortcodes
{

    /**
     * Constructor
     *
     * @since    0.0.1
     */
    public function __construct()
    {
        add_shortcode( 'tuxedo-liste', array($this, 'shortcodeTuxedoList'));
        add_shortcode( 'tuxedo-spectacle', array($this, 'shortcodeTuxedoSingle'));
    }

    /**
     * Returns the output of the shortcode
     * @return string output
     */
    public function shortcodeTuxedoList( $atts ) {

        $atts = shortcode_atts( array(
            'style' => "liste",
            'nombre' => -1,
            'filtre_salle' => null,
            'filtre_categorie' => null,
            'filtre_categorie_rapport_1' => null,
            'filtre_categorie_rapport_2' => null,
            'filtre_categorie_rapport_3' => null,
            'filtre_etiquette' => null,
            'decalage' => 0,
            'trier' => 'date',
            'soustitre' => true,
            'etiquette' => true,
            'image' => true,
            'taille_image' => "tuxedo-image-listing",
            'lien' => "Réserver",
            'liens_internes' => 0,
            'categories' => true,
            'categorie_rapport_1' => false,
            'categorie_rapport_2' => false,
            'categorie_rapport_3' => false,
            'prochaine_date' => true,
            'prochaine_salle' => true,
        ), $atts, 'tuxedo-liste' );

        $args = array(
            'numberposts' => $atts['nombre'],
            'offset' => $atts['decalage'],
            'post_status' => 'publish',
            'post_type' => 'tuxedo-show'
        );

        //Order by date of the next event or by alphabetical order
        if($atts['trier'] == "date"){
            $args['meta_key'] = 'show_next_event_date';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
        }elseif($atts['trier'] == "a-z"){
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
        }

        //Only display the shows that have an upcoming event
        $meta_query = array(
            'relation' => 'AND',
            array(
                'key'     => 'show_next_event',
                'value'   => '',
                'compare' => '!='
            )
        );

        //Filters by venue
        if($atts['filtre_salle']){
            $meta_query[] = array(
                'key'     => 'show_next_event_venue',
                'value'   => $atts['filtre_salle'],
                'compare' => 'LIKE'
            );
        }
        //Filters by categorie
        if($atts['filtre_categorie']){
            $meta_query[] = array(
                'key'     => 'show_categories',
                'value'   => $atts['filtre_categorie'],
                'compare' => 'LIKE'
            );
        }
        if($atts['filtre_categorie_rapport_1']){
            $meta_query[] = array(
                'key'     => 'show_report_categories_one',
                'value'   => $atts['filtre_categorie_rapport_1'],
                'compare' => 'LIKE'
            );
        }
        if($atts['filtre_categorie_rapport_2']){
            $meta_query[] = array(
                'key'     => 'show_report_categories_two',
                'value'   => $atts['filtre_categorie_rapport_2'],
                'compare' => 'LIKE'
            );
        }
        if($atts['filtre_categorie_rapport_3']){
            $meta_query[] = array(
                'key'     => 'show_report_categories_three',
                'value'   => $atts['filtre_categorie_rapport_3'],
                'compare' => 'LIKE'
            );
        }
        if($atts['filtre_etiquette']){
            $meta_query[] = array(
                'key'     => 'show_tag',
                'value'   => $atts['filtre_etiquette'],
                'compare' => 'LIKE'
            );
        }

        // var_dump($meta_query);
        $args['meta_query'] = $meta_query;
        $shows = get_posts($args);
        if(!empty($shows)){
            $args = [
                'post_type' => 'tuxedo-channel',
                'posts_per_page' => -1,
                'fields' => 'ids'
            ];
            $channels = [];
            $query = new WP_Query($args);
            foreach ($query->posts as $channel_id) {
                $channels[] = [
                    'id' => $channel_id,
                    'name' => get_the_title($channel_id),
                    'url' => get_field('url_ticket', $channel_id),
                    'price_categories' => get_field('price_categories', $channel_id)
                ];
            }

            $domain = get_option( 'tuxedo_importer_plugin_options' )['domain'];
            $show_url = get_option( 'tuxedo_importer_plugin_options' )['show_url'];
            ob_start();
            require_once 'templates/tuxedo-liste.php';

            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }else{
            return "";
        }
    }
    /**
     * Returns the output of the shortcode
     * @return string output
     */
    public function shortcodeTuxedoSingle( $atts ) {
        $atts = shortcode_atts( array(
            'style' => true,
            'spectacle_id' => null,
            'spectacle' => null,
            'soustitre' => true,
            'description' => true,
            'distribution' => true,
            'image' => true,
            'lien' => "Réserver",
            'prix' => false,
            'duree' => true,
            'taille_image' => "large",
            'categories' => true,
            'etiquette' => true,
            'categorie_rapport_1' => false,
            'categorie_rapport_2' => false,
            'categorie_rapport_3' => false,
            'prochaine_date' => true,
            'prochaine_salle' => true,
            'representations' => -1,
            'langue' => 'fr',
        ), $atts, 'tuxedo-spectacle' );

        //Only returns output if a show is specified
        if($atts['spectacle_id'] || $atts['spectacle']){
            if($atts['spectacle_id']){
                $post = get_post($atts['spectacle_id']);
                if(!$post){
                    return;
                }
                $post_id = $post->ID;
            }

            if($atts['spectacle']){
                $posts = get_posts([
                    'post_type'  => 'tuxedo-show',
                    'title' => $atts['spectacle'],
                ]);
                if(!$posts){
                    return;
                }
                $post = $posts[0];
                $post_id = $posts[0]->ID;
            }

            $fields = get_fields($post_id);
            //$domain = get_option( 'tuxedo_importer_plugin_options' )['domain'];

            //Gets the events for this show
            if(!empty($atts['representations']) && $atts['representations'] != 0){
                $events = get_posts(
                    array(
                        'numberposts' => $atts['representations'],
                        'post_status' => 'publish',
                        'post_type' => 'tuxedo-event',
                        'meta_query' => array(
                            array(
                                'key'  => 'event_show',
                                'compare'   => '=',
                                'value'     => $post_id,
                            ),
                        ),
                        'meta_key' => 'event_datetime',
                        'orderby' => 'meta_value_num',
                        'order' => 'ASC'
                    )
                );
            }

            // Get channels tickets url
            $args = [
                'post_type' => 'tuxedo-channel',
                'posts_per_page' => -1,
                'fields' => 'ids'
            ];
            $channels = [];
            $query = new WP_Query($args);
            foreach ($query->posts as $channel_id) {
                $channels[] = [
                    'id' => $channel_id,
                    'name' => get_the_title($channel_id),
                    'url' => get_field('url_ticket', $channel_id),
                    'price_categories' => get_field('price_categories', $channel_id)
                ];
            }

            $ticket_urls = [];
            foreach (get_field('event_price_categories', $fields['show_next_event']) as $evc) {
                foreach ($channels as $channel) {
                    foreach ($channel['price_categories'] as $price_category) {
                        if ($price_category['tuxedo_id'] == $evc['category_id']) {
                            $ticket_urls[$channel['name']] = $channel['url'];
                        }
                    }
                }
            }

            ob_start();
            require_once 'templates/tuxedo-spectacle.php';

            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }else{
            return '<p style="color:red;font-weight: bold;font-family: 	Times New Roman;">Erreur avec le shortcode [tuxedo-spectacle]: Veuillez utiliser le paramètre <em>spectacle_id</em> ou le paramètre <em>spectacle</em> pour spécifier le spectacle que vous souhaitez afficher.</p>';
        }

    }


}
