<?php
$options = get_option( 'tuxedo_importer_plugin_options' );
if(isset($options['show_url']) && $options['show_url']){
    $show_url = true;
}else{
    $show_url = false;
}

register_post_type('tuxedo-show', array(
    'menu_icon' => 'dashicons-clipboard',
    'public' => $show_url,
    'publicly_queryable' => $show_url,
    'exclude_from_search' => false,
    'show_ui' => true,
    'show_in_menu' => false,
    'show_in_nav_menus' => false,
    'hierarchical' => false,
    'rewrite' => ($show_url) ? array('slug' => "spectacles") : false,
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
        'name' => __("Spectacles", 'tuxedo-importer'),
        'singular_name' => __("Spectacle", 'tuxedo-importer'),
        'menu_name' => __("Spectacles", 'tuxedo-importer'),
        'add_new' => __("", 'tuxedo-importer'),
        'add_new_item' => __("", 'tuxedo-importer'),
        'edit_item' => __('', 'tuxedo-importer'),
        'new_item' => __("", 'tuxedo-importer'),
        'all_items' => __("Tous les spectacles", 'tuxedo-importer'),
        'view_item' => __('Afficher', 'tuxedo-importer'),
        'search_items' => __('Rechercher', 'tuxedo-importer'),
        'not_found' =>  __("Aucun spectacles.", 'tuxedo-importer'),
        'not_found_in_trash' => __("Aucun spectacles dans la corbeille.", 'tuxedo-importer')
    )
));

add_filter('parent_file', 'fix_admin_parent_file_show');
function fix_admin_parent_file_show($parent_file){
    global $submenu_file, $current_screen;

    if($current_screen->post_type == 'tuxedo-show') {
        $submenu_file = 'edit.php?post_type=tuxedo-show';
        $parent_file = 'tuxedo';
    }
    return $parent_file;
}

if( function_exists('acf_add_local_field_group') ){
    acf_add_local_field_group(array(
        'key' => 'group_614ce30db271a',
        'title' => 'Spectacle',
        'fields' => array(
            array(
                'key' => 'show_title_en',
                'label' => 'Titre anglais',
                'name' => 'show_title_en',
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
                'key' => 'field_614ce311170c1',
                'label' => 'Sous-titre',
                'name' => 'show_subtitle',
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
                'key' => 'show_subtitle_en',
                'label' => 'Sous-titre anglais',
                'name' => 'show_subtitle_en',
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
                'key' => 'show_tag',
                'label' => 'Étiquette',
                'name' => 'show_tag',
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
                'key' => 'show_tag_en',
                'label' => 'Étiquette anglaise',
                'name' => 'show_tag_en',
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
                'key' => 'show_is_sold_out',
                'label' => 'Complet',
                'name' => 'show_is_sold_out',
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
                'key' => 'show_image_1',
                'label' => 'Image 1',
                'name' => 'show_image_1',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_2',
                'label' => 'Image 2',
                'name' => 'show_image_2',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_3',
                'label' => 'Image 3',
                'name' => 'show_image_3',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_4',
                'label' => 'Image 4',
                'name' => 'show_image_4',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_5',
                'label' => 'Image 5',
                'name' => 'show_image_5',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_6',
                'label' => 'Image 6',
                'name' => 'show_image_6',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_7',
                'label' => 'Image 7',
                'name' => 'show_image_7',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_image_8',
                'label' => 'Image 8',
                'name' => 'show_image_8',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'tuxedo__image',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'medium',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'misc_value_1',
                'label' => 'Misc. value 1',
                'name' => 'misc_value_1',
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
                'key' => 'misc_value_2',
                'label' => 'Misc. value 2',
                'name' => 'misc_value_2',
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
                'key' => 'misc_value_3',
                'label' => 'Misc. value 3',
                'name' => 'misc_value_3',
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
                'key' => 'field_614ce36f170c2',
                'label' => 'Description',
                'name' => 'show_description',
                'type' => 'textarea',
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
                'key' => 'show_description_en',
                'label' => 'Description anglaise',
                'name' => 'show_description_en',
                'type' => 'textarea',
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
                'key' => 'show_distribution',
                'label' => 'Distribution',
                'name' => 'show_distribution',
                'type' => 'textarea',
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
                'key' => 'show_distribution_en',
                'label' => 'Distribution anglaise',
                'name' => 'show_distribution_en',
                'type' => 'textarea',
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
                'key' => 'field_614ce3c0170c3',
                'label' => 'Durée',
                'name' => 'show_duration',
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
                'key' => 'field_614ce46e170c5',
                'label' => 'Lien vers la billetterie',
                'name' => 'show_link',
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
                'key' => 'field_614ce480170c6',
                'label' => 'Catégories',
                'name' => 'show_categories',
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
                'key' => 'show_categories_en',
                'label' => 'Catégories',
                'name' => 'show_categories_en',
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
                'key' => 'show_report_categories_one',
                'label' => 'Catégories de rapport - 1',
                'name' => 'show_report_categories_one',
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
                'key' => 'show_report_categories_one_en',
                'label' => 'Catégories de rapport - 1 anglaise',
                'name' => 'show_report_categories_one_en',
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
                'key' => 'show_report_categories_two',
                'label' => 'Catégories de rapport - 2',
                'name' => 'show_report_categories_two',
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
                'key' => 'show_report_categories_two_en',
                'label' => 'Catégories de rapport - 2 anglaise',
                'name' => 'show_report_categories_two_en',
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
                'key' => 'show_report_categories_three',
                'label' => 'Catégories de rapport - 3',
                'name' => 'show_report_categories_three',
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
                'key' => 'show_report_categories_three_en',
                'label' => 'Catégories de rapport - 3 anglaise',
                'name' => 'show_report_categories_three_en',
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
                'key' => 'show_external_link',
                'label' => 'Lien externe',
                'name' => 'show_external_link',
                'type' => 'text',
                'instructions' => 'Lien vers un site externe (ex: site de l\'artiste)',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'https://',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'disabled' => 1
            ),
            array(
                'key' => 'show_video_link',
                'label' => 'Lien vidéo',
                'name' => 'show_video_link',
                'type' => 'oembed',
                'instructions' => 'Lien vers une vidéo (ex: YouTube)',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'https://',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'disabled' => 0
            ),
            array(
                'key' => 'field_614ce99e170c7',
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
                'key' => 'show_last_updated',
                'label' => 'Date de modification',
                'name' => 'show_modified_date',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 0,
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
                'key' => 'show_last_seen',
                'label' => 'Dernier contact avec l\'api',
                'name' => 'show_last_seen',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 0,
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
            array(
                'key' => 'show_next_event',
                'label' => 'Prochaine représentation',
                'name' => 'show_next_event',
                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'tuxedo-event',
                ),
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'id',
                'ui' => 1,
                'disabled' => 1
            ),
            array(
                'key' => 'show_next_event_date',
                'label' => 'Prochaine représentation - Date',
                'name' => 'show_next_event_date',
                'type' => 'date_time_picker',
                'instructions' => '',
                'required' => 0,
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
                'key' => 'show_next_event_venue',
                'label' => 'Salle de la prochaine représentation',
                'name' => 'show_next_event_venue',
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
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tuxedo-show',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(
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
*  Prevents form submissions to edit the post
*/
function stop_submit_tuxedo() {
    if (!empty($_POST) && isset($_POST['post_type']) && in_array($_POST['post_type'], array('tuxedo-show', 'tuxedo-event'))) {
      if (true === DOING_AJAX) {
        exit;
      }
      if (!empty($_POST['post_ID'])) {
        wp_safe_redirect(admin_url('post.php?post='.$_POST['post_ID'].'&action=edit'));
        exit;
      } else {
        wp_safe_redirect(admin_url('edit.php?post_type=book'));
        exit;
      }
    }
}
add_action('admin_init','stop_submit_tuxedo');

/**
*  Adds custom columns in the cms to order by date
*/
function add_tuxedo_caps_admin() {
    $role = get_role( 'administrator' );
    $role->add_cap( 'tuxedo_cap' );

}
add_action( 'admin_init', 'add_tuxedo_caps_admin');


add_action( 'do_meta_boxes', 'remove_publish_mbox_tuxedo', 10, 3 );

function remove_publish_mbox_tuxedo( $post_type, $position, $post )
{
    if(in_array($post_type, array('tuxedo-show', 'tuxedo-event'))){
        remove_meta_box( 'submitdiv',$post_type, 'side' );
    }
}

add_filter( 'manage_tuxedo-show_posts_columns', 'set_custom_edit_tuxedo_show_columns' );
function set_custom_edit_tuxedo_show_columns($columns) {
    $columns['added_date'] = __( 'Ajouté le', 'tuxedo-importer' );

    $columns['last_modified'] = __( 'Dernière Modification', 'tuxedo-importer' );

    unset($columns['date']);
    return $columns;
}

add_action( 'manage_tuxedo-show_posts_custom_column' , 'custom_tuxedo_show_column', 10, 2 );
function custom_tuxedo_show_column( $column, $post_id ) {
    if($column == 'last_modified'){
        echo get_post_modified_time("Y/m/d h:i:s a", false, $post_id);
    }elseif($column == 'added_date'){
        echo get_the_date("Y/m/d h:i:s a", $post_id);
    }
}

add_filter( 'manage_edit-tuxedo-show_sortable_columns', 'tuxedo_importer_my_sortable_show_column' );
function tuxedo_importer_my_sortable_show_column( $columns ) {
	$columns['last_modified'] = 'last_modified';

	$columns['added_date'] = 'added_date';

	return $columns;
}


add_action( 'wp_trash_post', 'tuxedo_importer_deleteImageBeforeDeletePost' );

function tuxedo_importer_deleteImageBeforeDeletePost( $postid ) {
    if ( 'tuxedo-show' !== get_post_type( $postid )) {
        return;
    }
    foreach (array('1', '2', '3', '4', '5', '6', '7', '8') as $i) {
        $image = get_field('show_image_' . $i, $postid);
        if($image){
            wp_delete_attachment( $image, true);
        }
    }
    $events = get_posts(
        array(
            'numberposts' => -1,
            'post_status' => 'any',
            'post_type' => 'tuxedo-event',
            'meta_query' => array(
                array(
                    'key'  => 'event_show',
                    'compare'   => '=',
                    'value'     => $postid,
                ),
            ),
        )
    );
    foreach($events as $event){
        wp_delete_post($event->ID, true);
    }
}
add_image_size( 'tuxedo-image-listing', 300, 200, true );

//forces the use of the plugin's template
if(isset($options['singles']) && $options['singles']){
    /* Filter the single_template with our custom function*/
    add_filter('single_template', 'add_tuxedo_plugin_template');

}

function add_tuxedo_plugin_template($single) {

    global $post;


    /* Checks for single template by post type */
    if ( $post->post_type == 'tuxedo-show' && empty(locate_template('single-tuxedo-show.php'))) {

        if ( file_exists( plugin_dir_path(dirname(dirname(__FILE__))) . 'includes/templates/single-tuxedo-show.php' ) ) {

            return plugin_dir_path(dirname(dirname(__FILE__))) . 'includes/templates/single-tuxedo-show.php';
        }
    }

    return $single;

}

add_action( 'add_meta_boxes', 'tuxedo_importer_add_meta_box_show' );
function tuxedo_importer_add_meta_box_show() {
    add_meta_box(
        'tuxedo_importer_meta_box',
        'Importer le spectacle de l\'API',
        'tuxedo_importer_show_meta_box',
        'tuxedo-show',
        'side',
        'high'
    );
}

function tuxedo_importer_show_meta_box()
{
    global $post;
    $tuxedo_id = get_field('tuxedo_id', $post->ID);
    $import_date = get_field('show_last_seen', $post->ID);
    if($import_date){
        $import_date = get_date_from_gmt( date( 'Y-m-d H:i:s', $import_date ), 'F j, Y H:i:s' );
        echo '<p>Dernière importation : ' . $import_date . '</p>';
    }
    echo '<a href="' . admin_url('admin-post.php?action=tuxedo_single_import&post_id=' . $post->ID) . '" class="button button-primary">Importer</a>';
}