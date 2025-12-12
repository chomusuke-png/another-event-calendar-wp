<?php
class AEC_Display {

    public static function get_calendar_html( $year = null, $month = null ) {
        $year  = $year ?: date('Y');
        $month = $month ?: date('m');
        $today = date('Y-m-d');

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $first_day_ts  = mktime(0, 0, 0, $month, 1, $year);
        $day_of_week   = date('w', $first_day_ts);
        
        $prev_ts = strtotime("-1 month", $first_day_ts);
        $next_ts = strtotime("+1 month", $first_day_ts);

        $data = self::get_events_data($year, $month);
        $events_by_date = $data['events'];
        $categories_used = $data['categories'];

        ob_start();
        ?>
        <div class="aec-wrapper" data-year="<?php echo $year; ?>" data-month="<?php echo $month; ?>">
            
            <div class="aec-header-nav">
                <button class="aec-nav-btn aec-prev" data-year="<?php echo date('Y', $prev_ts); ?>" data-month="<?php echo date('m', $prev_ts); ?>"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="aec-month-label"><?php echo date_i18n('F Y', $first_day_ts); ?></div>
                <button class="aec-nav-btn aec-next" data-year="<?php echo date('Y', $next_ts); ?>" data-month="<?php echo date('m', $next_ts); ?>"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

            <div class="aec-body-row">
                
                <div class="aec-grid-container">
                    <div class="aec-grid">
                        <div class="aec-day-name">D</div><div class="aec-day-name">L</div><div class="aec-day-name">M</div>
                        <div class="aec-day-name">M</div><div class="aec-day-name">J</div><div class="aec-day-name">V</div><div class="aec-day-name">S</div>

                        <?php for ($i = 0; $i < $day_of_week; $i++): ?><div class="aec-day aec-empty"></div><?php endfor; ?>

                        <?php for ($day = 1; $day <= $days_in_month; $day++): 
                            $date_str = sprintf('%s-%02d-%02d', $year, $month, $day);
                            $day_events = isset($events_by_date[$date_str]) ? $events_by_date[$date_str] : array();
                            $is_today = ($date_str === $today) ? 'is-today' : '';
                            $has_event_class = !empty($day_events) ? 'has-events' : '';
                            $events_json = htmlspecialchars(json_encode($day_events), ENT_QUOTES, 'UTF-8');
                        ?>
                            <div class="aec-day <?php echo "$is_today $has_event_class"; ?>" 
                                 data-date="<?php echo $date_str; ?>"
                                 data-events="<?php echo $events_json; ?>">
                                 
                                <span class="aec-day-number"><?php echo $day; ?></span>
                                
                                <div class="aec-icons-container">
                                <?php if (!empty($day_events)): ?>
                                    <?php foreach($day_events as $event): 
                                        $color_style = 'color: ' . esc_attr($event['color']) . ';';
                                        $cat_class = 'cat-' . $event['cat_id'];
                                    ?>
                                        <i class="<?php echo esc_attr($event['icon'] . ' ' . $cat_class); ?> aec-event-icon" 
                                           style="<?php echo $color_style; ?>"></i>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="aec-controls">
                        <select id="aec-filter-category" class="aec-filter-select">
                            <option value="all">Ver todo el comité</option>
                            <?php foreach($categories_used as $cat_id => $cat_name): ?>
                                <option value="cat-<?php echo $cat_id; ?>"><?php echo esc_html($cat_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div id="aec-day-details" class="aec-day-details">
                    <div class="aec-details-placeholder">Selecciona un día para ver los eventos.</div>
                </div>

            </div> <div class="aec-loading"><i class="fa-solid fa-spinner fa-spin"></i></div>
        </div>
        <?php
        return ob_get_clean();
    }

    private static function get_events_data( $year, $month ) {
        // La lógica de obtención de datos sigue igual
        $args = array(
            'post_type' => 'aec_event', 'posts_per_page' => -1,
            'meta_query' => array( array( 'key' => '_aec_event_date', 'value' => array("$year-$month-01", "$year-$month-31"), 'compare' => 'BETWEEN', 'type' => 'DATE' ) )
        );
        $query = new WP_Query($args);
        $events = array();
        $categories = array();

        while($query->have_posts()){
            $query->the_post();
            $id = get_the_ID();
            $date = get_post_meta($id, '_aec_event_date', true);
            $terms = get_the_terms($id, 'aec_category');
            
            $cat_id = 0; $cat_name = ''; $icon = 'fa-solid fa-circle'; $color = '#555';
            if($terms && !is_wp_error($terms)){
                $term = current($terms);
                $cat_id = $term->term_id;
                $cat_name = $term->name;
                $icon = get_term_meta($term->term_id, 'aec_icon_class', true) ?: 'fa-solid fa-circle';
                $color = get_term_meta($term->term_id, 'aec_category_color', true) ?: '#555';
                $categories[$cat_id] = $cat_name;
            }
            $events[$date][] = array(
                'title'   => get_the_title(),
                'content' => get_the_excerpt(),
                'icon'    => $icon,
                'color'   => $color,
                'cat_id'  => $cat_id
            );
        }
        wp_reset_postdata();
        return array('events' => $events, 'categories' => $categories);
    }
}