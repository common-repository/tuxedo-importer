<?php

register_post_type('tuxedo-event', array(
    'menu_icon' => 'dashicons-clipboard',
    'public' => false,
    'publicly_queryable' => false,
    'exclude_from_search' => false,
    'show_ui' => true,
    'show_in_menu' => false,
    'show_in_nav_menus' => false,
    'hierarchical' => false,
    'rewrite' => false,
    'show_in_rest' => true,
    'supports' => array('title', 'revisions', 'thumbnail'),
    'has_archive' => false,
    'capability_type' => 'post',
    'capabilities' => array(
        'edit_post' => 'tuxedo_cap',
        'edit_posts' => 'tuxedo_cap',
        'edit_others_posts' => 'tuxedo_cap',
        'publish_posts' => 'do_not_allow',
        'read_post' => 'do_not_allow',
        'read_private_posts' => 'do_not_allow',
        'delete_post' => 'tuxedo_cap',
        'create_posts' => 'do_not_allow'
    ),
    'labels' => array(
        'name' => __("Représentations", 'tuxedo-importer'),
        'singular_name' => __("Représentation", 'tuxedo-importer'),
        'menu_name' => __("Représentations", 'tuxedo-importer'),
        'add_new' => __("", 'tuxedo-importer'),
        'add_new_item' => __("", 'tuxedo-importer'),
        'edit_item' => __('', 'tuxedo-importer'),
        'new_item' => __("", 'tuxedo-importer'),
        'all_items' => __("Toutes les représentations", 'tuxedo-importer'),
        'view_item' => __('Afficher', 'tuxedo-importer'),
        'search_items' => __('Rechercher', 'tuxedo-importer'),
        'not_found' =>  __("Aucune représentation.", 'tuxedo-importer'),
        'not_found_in_trash' => __("Aucune représentation dans la corbeille.", 'tuxedo-importer')
    )
));

add_filter('parent_file', 'fix_admin_parent_file_event');
function fix_admin_parent_file_event($parent_file){
    global $submenu_file, $current_screen;

    if($current_screen->post_type == 'tuxedo-event') {
        $submenu_file = 'edit.php?post_type=tuxedo-event';
        $parent_file = 'tuxedo';
    }
    return $parent_file;
}

if( function_exists('acf_add_local_field_group') ){

    acf_add_local_field_group(array(
        'key' => 'group_614ce1292c2f9',
        'title' => 'Représentation',
        'fields' => array(
            array(
                'key' => 'field_614ce13049b84',
                'label' => 'Spectacle',
                'name' => 'event_show',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'tuxedo-show',
                ),
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
                'ui' => 1,
                'disabled' => 1
            ),
            array(
                'key' => 'field_614ce3e0311d6',
                'label' => 'Status',
                'name' => 'event_status',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'event_is_sold_out',
                'label' => 'Complet',
                'name' => 'event_is_sold_out',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 0,
                'ui_on_text' => '',
                'ui_off_text' => '',
                'disabled' => 1,
                'readonly' => 1

            ),
            array(
                'key' => 'field_614ce19749b85',
                'label' => 'Date et heure',
                'name' => 'event_datetime',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'display_format' => 'Y-m-d H:i:s',
                'return_format' => 'U',
                'first_day' => 1,
                'disabled' => 1
            ),
            array(
                'key' => 'field_614ce2d649b8a',
                'label' => 'Salle',
                'name' => 'event_venue',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'field_614ce23b49b88',
                'label' => 'Lien vers la billetterie',
                'name' => 'event_link',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'event_price_categories',
                'label' => 'Prix des catégories',
                'name' => 'event_price_categories',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'disabled' => 0,
                'min' => 0,
                'max' => 0,
                'sub_fields' => [
                    [
                        'key' => 'category_id',
                        'label' => 'ID Tuxedo de catégorie',
                        'name' => 'category_id',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => '',
                        'max' => '',
                        'step' => '',
                        'disabled' => 1
                    ],
                    [
                        'key' => 'category',
                        'label' => 'Nom de la catégorie',
                        'name' => 'category',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'disabled' => 1
                    ],
                    [
                        'key' => 'category_en',
                        'label' => 'Nom de la catégorie anglais',
                        'name' => 'category_en',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'maxlength' => '',
                        'disabled' => 1
                    ],
                    [
                        'key' => 'price',
                        'label' => 'Prix',
                        'name' => 'price',
                        'type' => 'number',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'message' => '',
                        'default_value' => 0,
                        'disabled' => 1,
                        'min' => 0,
                        'max' => 0,
                        'step' => 0.01
                    ]
                ]
            ),
            array(
                'key' => 'field_614ce27249b89',
                'label' => 'ID Tuxedo',
                'name' => 'tuxedo_id',
                'type' => 'number',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'event_last_updated',
                'label' => 'Date de modification',
                'name' => 'event_modified_date',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'display_format' => 'Y-m-d H:i:s',
                'return_format' => 'U',
                'first_day' => 1,
                'disabled' => 1
            ),
            array(
                'key' => 'event_last_seen',
                'label' => 'Dernier contact avec l\'api',
                'name' => 'event_last_seen',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo-hidden',
                    'id' => '',
                ),
                'display_format' => 'Y-m-d H:i:s',
                'return_format' => 'U',
                'first_day' => 1,
                'disabled' => 1
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tuxedo-event',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
            0 => 'permalink',
            1 => 'the_content',
            2 => 'excerpt',
            3 => 'discussion',
            4 => 'comments',
            5 => 'revisions',
            6 => 'slug',
            7 => 'author',
            8 => 'format',
            9 => 'page_attributes',
            10 => 'featured_image',
            11 => 'categories',
            12 => 'tags',
            13 => 'send-trackbacks',
        ),
        'active' => true,
        'description' => '',
    ));
}


/**
*  Adds custom columns in the cms to order by date
*/
add_filter( 'manage_tuxedo-event_posts_columns', 'set_custom_edit_tuxedo_event_columns' );
function set_custom_edit_tuxedo_event_columns($columns) {
    $columns['added_date'] = __( 'Ajouté le', 'tuxedo-importer' );

    $columns['last_modified'] = __( 'Dernière Modification', 'tuxedo-importer' );

    unset($columns['date']);
    return $columns;
}

add_action( 'manage_tuxedo-event_posts_custom_column' , 'custom_tuxedo_event_column', 10, 2 );
function custom_tuxedo_event_column( $column, $post_id ) {
    if($column == 'last_modified'){
        echo get_post_modified_time("Y/m/d h:i:s a", false, $post_id);
    }elseif($column == 'added_date'){
        echo get_the_date("Y/m/d h:i:s a", $post_id);
    }
}

add_filter( 'manage_edit-tuxedo-event_sortable_columns', 'my_sortable_event_column' );
function my_sortable_event_column( $columns ) {
	$columns['last_modified'] = 'last_modified';

	$columns['added_date'] = 'added_date';

	return $columns;
}

add_action( 'add_meta_boxes', 'tuxedo_importer_add_meta_box_event' );
function tuxedo_importer_add_meta_box_event() {
    add_meta_box(
        'tuxedo_importer_meta_box',
        'Importer le spectacle de l\'API',
        'tuxedo_importer_event_meta_box',
        'tuxedo-event',
        'side',
        'high'
    );
}

function tuxedo_importer_event_meta_box()
{
    global $post;
    $tuxedo_id = get_field('tuxedo_id', $post->ID);
    $import_date = get_field('event_last_seen', $post->ID);
    if($import_date){
        $import_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $import_date ), 'F j, Y H:i:s' );
        echo '<p>Dernière importation : ' . $import_date . '</p>';
    }
    echo '<a href="' . admin_url('admin-post.php?action=tuxedo_single_import&post_id=' . $post->ID) . '" class="button button-primary">Importer</a>';
}