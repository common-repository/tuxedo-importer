<div class="tuxedo tuxedo--liste style-<?php echo esc_attr($atts['style']);?>">
    <?php foreach($shows as $post):
        $fields = get_fields($post->ID);
        $ticket_urls = [];
        if ($fields['show_next_event']) {
            foreach (get_field('event_price_categories', $fields['show_next_event']) as $evc) {
                foreach ($channels as $channel) {
                    // look for visibleOn
                    $show = get_field('event_show', $fields['show_next_event']);
                    $show_channels = get_field('channels', $show);
                    $skip = false;
                    foreach ($show_channels as $sc) {
                        if ($channel['id'] != $sc->ID) {
                            continue;
                        }
                        if (isset($sc->visibleOn) && $sc->visibleOn > strtotime('now')) {
                            $skip = true;
                        }
                    }
                    if ($skip) {
                        continue;
                    }
                    foreach ($channel['price_categories'] as $price_category) {
                        if ($price_category['tuxedo_id'] == $evc['category_id']) {
                            $ticket_urls[$channel['name']] = $channel['url'];
                        }
                    }
                }
            }
        }
        ?>
        <div id="<?php echo esc_attr($post->ID); ?>" class="tuxedo tuxedo--liste__spectacle <?php echo (isset($fields['show_is_sold_out']) && $fields['show_is_sold_out'] == "complet") ?  'complet' : ""; ?>">
            <?php if($show_url && $atts['liens_internes'] && $atts['lien'] && $fields['show_link']): ?>
                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
            <?php elseif(count($ticket_urls)==1 && $atts['lien'] && $fields['show_link']): ?>
                <a href="<?php echo esc_url($ticket_urls[array_key_first( $ticket_urls )] . $fields['show_link']); ?>" target="_blank">
            <?php endif; ?>
            <?php if($atts['image'] && isset($fields['show_image_1']) && $fields['show_image_1']): ?>
                <div class="tuxedo--liste__image">
                    <?php if($atts['etiquette'] && $fields['show_tag']): ?>
                        <span class="tuxedo--spectacle__tag"><?php echo esc_html($fields['show_tag']); ?></span>
                    <?php endif; ?>
                    <?php echo wp_get_attachment_image($fields['show_image_1'], $atts['taille_image'] , false, array('class' => 'tuxedo--spectacle__image', 'alt' => $post->post_title, 'loading' => false)); ?>
                </div>
            <?php endif; ?>

            <div class="tuxedo--liste__content">
                <h2><?php echo esc_html($post->post_title); ?></h2>
                <?php if(isset($fields['show_is_sold_out']) && $fields['show_is_sold_out'] == "complet"): ?>
                    <span class="tuxedo--spectacle__complet">Complet</span>
                <?php endif; ?>
                <?php if($atts['soustitre'] && isset($fields['show_subtitle']) && $fields['show_subtitle']):?>
                    <span class="tuxedo--spectacle__subtitle"><?php echo esc_html($fields['show_subtitle']); ?></span>
                <?php endif; ?>
                <?php
                    $categories = "";
                    if($atts['categories'] && $fields['show_categories']){
                        $categories = $fields['show_categories'];
                    }
                    if($atts['categorie_rapport_1'] && $fields['show_report_categories_one']){
                        if(!empty($categories)){
                            $categories .= ", ";
                        }
                        $categories .= $fields['show_report_categories_one'];
                    }
                    if($atts['categorie_rapport_2'] && $fields['show_report_categories_two']){
                        if(!empty($categories)){
                            $categories .= ", ";
                        }
                        $categories .= $fields['show_report_categories_two'];
                    }
                    if($atts['categorie_rapport_3'] && $fields['show_report_categories_three']){
                        if(!empty($categories)){
                            $categories .= ", ";
                        }
                        $categories .= $fields['show_report_categories_three'];
                    }
                ?>
                <?php if(!empty($categories)):?>
                    <span class="tuxedo--spectacle__categories">
                        <?php
                            echo esc_html($categories);
                        ?>
                    </span>
                <?php endif; ?>
                <div class="tuxedo--spectacle__prochaine">
                    <?php if($atts['prochaine_date'] && $fields['show_next_event']): ?>
                        <div class="tuxedo--spectacle__date">
                            <span><?php echo esc_html(wp_date('j F Y', get_field('event_datetime', $fields['show_next_event']))); ?></span>
                            <span class="tuxedo--spectacle__separator"> - </span>
                            <span><?php echo esc_html(wp_date('G:i', get_field('event_datetime', $fields['show_next_event']))); ?></span>
                            <?php if($atts['style'] != "blocs" && $atts['prochaine_salle'] && $fields['show_next_event_venue']):?>
                                <span class="tuxedo--spectacle__separator"> | </span>
                                <span class="tuxedo--spectacle__salle"><?php echo esc_html($fields['show_next_event_venue']); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if($atts['style'] == "blocs" && $atts['prochaine_salle'] && $fields['show_next_event_venue']):?>
                            <span class="tuxedo--spectacle__salle"><?php echo esc_html($fields['show_next_event_venue']); ?></span>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
                <?php if((count($ticket_urls)==1 || $atts['liens_internes']) && $atts['lien'] && $fields['show_link']): ?>
                    <span class="tuxedo--spectacle__cta"><?php echo esc_html($atts['lien']); ?></span>
                <?php elseif(count($ticket_urls)>1 && $atts['lien'] && $fields['show_link'] && !$atts['liens_internes']): ?>
                    <?php foreach ($ticket_urls as $channel => $url) : ?>
                        <a href="<?php echo esc_url($url . $fields['show_link']); ?>" target="_blank"><span class="tuxedo--spectacle__cta"><?php echo esc_html($atts['lien']); ?> <?=$channel?></span></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if($atts['lien'] && $fields['show_link']): ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php if($atts['style'] == 'liste'): ?>
    <style>
        <?php include __DIR__ . '/../css/tuxedo-liste.min.css'; ?>
    </style>
<?php elseif($atts['style'] == 'blocs'): ?>
    <style>
        <?php include __DIR__ . '/../css/tuxedo-blocs.min.css'; ?>
    </style>
<?php endif; ?>