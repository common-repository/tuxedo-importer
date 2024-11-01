<?php
$title = $post->post_title;

if ($atts['langue']=='en' && $fields['show_title_en']) {
    $title = $fields['show_title_en'];
}
?>
<div id="<?php echo esc_attr($post->ID) ?>" class="tuxedo tuxedo--spectacle <?php echo (isset($fields['show_is_sold_out']) && $fields['show_is_sold_out'] == "complet") ?  'complet' : ""; ?>">

    <?php if($atts['image'] && isset($fields['show_image_1']) && $fields['show_image_1']): ?>
        <div class="tuxedo--spectacle__image">
            <?php if($atts['langue']=='en' && $atts['etiquette'] && $fields['show_tag_en']): ?>
                <span class="tuxedo--spectacle__tag"><?php echo esc_html($fields['show_tag_en']); ?></span>
            <?php elseif($atts['etiquette'] && $fields['show_tag']): ?>
                <span class="tuxedo--spectacle__tag"><?php echo esc_html($fields['show_tag']); ?></span>
            <?php endif; ?>
            <?php echo wp_get_attachment_image($fields['show_image_1'], $atts['taille_image'] , false, array('class' => 'tuxedo--spectacle__image', 'alt' => $title)); ?>
        </div>
    <?php endif; ?>

    <h2><?php echo esc_html($title); ?></h2>
    <?php if($atts['langue']=='en' && $atts['soustitre'] && $fields['show_subtitle_en']):?>
        <span class="tuxedo--spectacle__subtitle"><?php echo esc_html($fields['show_subtitle_en']); ?></span>
    <?php elseif($atts['soustitre'] && $fields['show_subtitle']):?>
        <span class="tuxedo--spectacle__subtitle"><?php echo esc_html($fields['show_subtitle']); ?></span>
    <?php endif; ?>
    <?php
        $categories = "";
        if($atts['langue']=='en' && $atts['categories'] && $fields['show_categories_en']){
            $categories = $fields['show_categories_en'];
        } else if($atts['categories'] && $fields['show_categories']){
            $categories = $fields['show_categories'];
        }
        if($atts['langue']=='en' && $atts['categorie_rapport_1'] && $fields['show_report_categories_one_en']){
            if(!empty($categories)){
                $categories .= ", ";
            }
            $categories .= $fields['show_report_categories_one_en'];
        } else if($atts['categorie_rapport_1'] && $fields['show_report_categories_one']){
            if(!empty($categories)){
                $categories .= ", ";
            }
            $categories .= $fields['show_report_categories_one'];
        }
        if($atts['langue']=='en' && $atts['categorie_rapport_2'] && $fields['show_report_categories_two_en']){
            if(!empty($categories)){
                $categories .= ", ";
            }
            $categories .= $fields['show_report_categories_two_en'];
        } else if($atts['categorie_rapport_2'] && $fields['show_report_categories_two']){
            if(!empty($categories)){
                $categories .= ", ";
            }
            $categories .= $fields['show_report_categories_two'];
        }
        if($atts['langue']=='en' && $atts['categorie_rapport_3'] && $fields['show_report_categories_three_en']){
            if(!empty($categories)){
                $categories .= ", ";
            }
            $categories .= $fields['show_report_categories_three_en'];
        } else if($atts['categorie_rapport_3'] && $fields['show_report_categories_three']){
            if(!empty($categories)){
                $categories .= ", ";
            }
            $categories .= $fields['show_report_categories_three'];
        }

    ?>
    <?php if(!empty($categories)):?>
        <div class="tuxedo--spectacle__categories">
            <span>
                <?php
                    echo esc_html($categories);
                ?>
            </span>
        </div>
    <?php endif; ?>
    <div>
        <?php if($atts['prochaine_date'] && $fields['show_next_event']):
             ?>
            <div class="tuxedo--spectacle__date">
                <span><?php echo esc_html(wp_date('j F Y', get_field('event_datetime', $fields['show_next_event']))); ?></span>
                <span class="tuxedo--spectacle__separator"> - </span>
                <span><?php echo esc_html(wp_date('G:i', get_field('event_datetime', $fields['show_next_event']))); ?></span>

                <?php if($atts['duree'] && $fields['show_duration']):?>
                    <span><?php echo esc_html($fields['show_duration']); ?> min</span>
                <?php endif; ?>
                <?php if($atts['prochaine_salle'] && $fields['show_next_event_venue']):?>
                    <span class="tuxedo--spectacle__separator"> | </span>
                    <span><?php echo esc_html($fields['show_next_event_venue']); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if($atts['langue']=='en' && $atts['description'] && $fields['show_description_en']):?>
        <div class="tuxedo--spectacle__description"><?php echo wp_kses_post($fields['show_description_en']); ?></div>
    <?php elseif($atts['description'] && $fields['show_description']):?>
        <div class="tuxedo--spectacle__description"><?php echo wp_kses_post($fields['show_description']); ?></div>
    <?php endif; ?>
    <?php if($atts['langue']=='en' && $atts['distribution'] && $fields['show_distribution_en']):?>
        <div class="tuxedo--spectacle__description"><?php echo wp_kses_post($fields['show_distribution_en']); ?></div>
    <?php elseif($atts['distribution'] && $fields['show_distribution']):?>
        <div class="tuxedo--spectacle__description"><?php echo wp_kses_post($fields['show_distribution']); ?></div>
    <?php endif; ?>
    <?php if($atts['prix'] && $atts['prochaine_date'] && $fields['show_next_event']):
        $categories = get_field('event_price_categories', $fields['show_next_event'], false); ?>
        <div class="tuxedo--spectacle__prix">
            <table>
                <thead>
                    <tr>
                        <th><?php _e("Catégorie", "tuxedo"); ?></th>
                        <th><?php _e("Prix", "tuxedo"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $category): ?>
                        <tr>
                            <?php if ($atts['langue']=='en' && $category['category_en']) : ?>
                            <td><?php echo esc_html($category['category_en']); ?></td>
                            <?php else : ?>
                            <td><?php echo esc_html($category['category']); ?></td>
                            <?php endif; ?>
                            <td><?php echo number_format_i18n( $category['price'], 2 ) ?> $</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php if($ticket_urls && $atts['lien'] && $fields['show_link'] && (!isset($fields['show_is_sold_out']) || $fields['show_is_sold_out'] != "complet") && $fields['show_next_event']): ?>
        <?php if (count($ticket_urls)==1) : ?>
        <a class="tuxedo--spectacle__cta" href="<?php echo esc_url($ticket_urls[array_key_first($ticket_urls)] . $fields['show_link']); ?>" target="_blank"/><?php echo esc_html($atts['lien']); ?></a>
        <?php else : ?>
        <div class="tuxedo--spectacle__cta">
            <ul class="tuxedo--spectacle__cta__list">
                <?php foreach ($ticket_urls as $channel => $url) : ?>
                <li>
                    <a href="<?php echo esc_url($url . $fields['show_link']); ?>" target="_blank"><?php echo esc_html($atts['lien']); ?> <?php echo esc_html($channel); ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if(isset($fields['show_is_sold_out']) && $fields['show_is_sold_out'] == "complet"): ?>
        <span class="tuxedo--spectacle__complet">Complet</span>
    <?php endif; ?>
    <?php if($atts['representations'] && !empty($events)): ?>
        <div class="tuxedo--spectacle__representations">
            <div class="tuxedo--spectacle__representations__wrapper">
                <?php
                    foreach($events as $event){
                        $eventFields = get_fields($event->ID);
                        ?>
                            <div class="tuxedo--representation">
                                <div>
                                    <div class="tuxedo--representation__date">
                                        <span><?php echo esc_html(wp_date('j F Y', $eventFields['event_datetime'])); ?></span>
                                        <span><?php echo esc_html(wp_date('H:i', $eventFields['event_datetime'])); ?></span>
                                    </div>
                                    <?php if($eventFields['event_venue']):?>
                                        <div class="tuxedo--representation__salle">
                                            <span><?php echo esc_html($eventFields['event_venue']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if($atts['lien'] && $eventFields['event_link'] && $eventFields['event_is_sold_out'] != "complet"): ?>
                                    <?php if (count($channels)==1) :
                                        $domain = get_field('url_ticket', $channels[0]['id']);?>
                                        <a class="tuxedo--representation__cta" href="<?php echo esc_url($domain . $eventFields['event_link']); ?>" target="_blank"><?php echo esc_html($atts['lien']); ?></a>
                                        <?php else : ?>
                                        <div class="tuxedo--spectacle__cta">
                                            <ul class="tuxedo--spectacle__cta__list">
                                                <?php foreach ($channels as $channel) :
                                                    $domain = get_field('url_ticket', $channel['id']);?>
                                                <li>
                                                    <a class="tuxedo--representation__cta" href="<?php echo esc_url($domain . $eventFields['event_link']); ?>" target="_blank"><?php echo esc_html($atts['lien']); ?>  <?php echo esc_html($channel['name']); ?></a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>

                                <?php endif; ?>
                                <?php if(isset($eventFields['event_is_sold_out']) && $eventFields['event_is_sold_out'] == "complet"): ?>
                                    <span class="tuxedo--representation__complet"><?php _e("Complet", "tuxedo"); ?></a>
                                <?php endif; ?>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if($atts['style']): ?>
    <style>
        <?php include __DIR__ . '/../css/tuxedo-spectacle.css'; ?>
    </style>
<?php endif; ?>
