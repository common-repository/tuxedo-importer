<?php

register_post_type('tuxedo-channel', array(
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
    'supports' => array('title'),
    'has_archive' => false,
    'capability_type' => 'post',
    'capabilities' => array(
        'edit_post' => 'tuxedo_cap',
        'edit_posts' => 'tuxedo_cap',
        'edit_others_posts' => 'tuxedo_cap',
        'publish_posts' => 'tuxedo_cap',
        'read_post' => 'do_not_allow',
        'read_private_posts' => 'do_not_allow',
        'delete_post' => 'tuxedo_cap',
        'create_posts' => 'do_not_allow'
    ),
    'labels' => array(
        'name' => __("Canaux", 'tuxedo-importer'),
        'singular_name' => __("Canal", 'tuxedo-importer'),
        'menu_name' => __("Canaux", 'tuxedo-importer'),
        'add_new' => __("", 'tuxedo-importer'),
        'add_new_item' => __("", 'tuxedo-importer'),
        'edit_item' => __('', 'tuxedo-importer'),
        'new_item' => __("", 'tuxedo-importer'),
        'all_items' => __("Tous les canaux", 'tuxedo-importer'),
        'view_item' => __('Afficher', 'tuxedo-importer'),
        'search_items' => __('Rechercher', 'tuxedo-importer'),
        'not_found' =>  __("Aucun canal.", 'tuxedo-importer'),
        'not_found_in_trash' => __("Aucun canal dans la corbeille.", 'tuxedo-importer')
    )
));

add_filter('parent_file', 'fix_admin_parent_file_channel');
function fix_admin_parent_file_channel($parent_file){
    global $submenu_file, $current_screen;

    if($current_screen->post_type == 'tuxedo-channel') {
        $submenu_file = 'edit.php?post_type=tuxedo-channel';
        $parent_file = 'tuxedo';
    }
    return $parent_file;
}

if( function_exists('acf_add_local_field_group') ){

    acf_add_local_field_group(array(
        'key' => 'group_tuxchannel',
        'title' => 'Channel',
        'fields' => array(
            array(
                'key' => 'url_ticket',
                'label' => 'Url de la billeterie',
                'name' => 'url_ticket',
                'type' => 'url',
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
                'disabled' => 0
            ),
            array(
                'key' => 'price_categories',
                'label' => 'Catégories de prix',
                'name' => 'price_categories',
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
                        'key' => 'tuxedo_id',
                        'label' => 'ID Tuxedo',
                        'name' => 'tuxedo_id',
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
                        'key' => 'name',
                        'label' => 'Nom',
                        'name' => 'name',
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
                        'key' => 'show_category',
                        'label' => 'Afficher la catégorie',
                        'name' => 'show_category',
                        'type' => 'true_false',
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
                        'ui' => 0,
                        'ui_on_text' => '',
                        'ui_off_text' => '',
                        'disabled' => 1
                    ],
                    [
                        'key' => 'restriction',
                        'label' => 'Restriction',
                        'name' => 'restriction',
                        'type' => 'text',
                        'instructions' => '',
                        'required' => 0,
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
                    ]
                ]
            ),
            array(
                'key' => 'channel_tuxedo_id',
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
                'key' => 'channel_modified_date',
                'label' => 'Date de modification',
                'name' => 'channel_modified_date',
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
                'key' => 'channel_last_seen',
                'label' => 'Dernier contact avec l\'api',
                'name' => 'channel_last_seen',
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
                    'value' => 'tuxedo-channel',
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