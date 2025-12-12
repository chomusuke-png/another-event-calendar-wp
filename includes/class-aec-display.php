<?php
class AEC_Display {

    /**
     * Renderiza el calendario.
     * @param int|null $year Año (null para actual)
     * @param int|null $month Mes (null para actual)
     */
    public static function get_calendar_html( $year = null, $month = null ) {
        $year  = $year ?: date('Y');
        $month = $month ?: date('m');
        $today = date('Y-m-d');

        // Calcular fechas
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $first_day_ts  = mktime(0, 0, 0, $month, 1, $year);
        $day_of_week   = date('w', $first_day_ts);
        
        // Datos para navegación
        $prev_month_ts = strtotime("-1 month", $first_day_ts);
        $next_month_ts = strtotime("+1 month", $first_day_ts);

        // Obtener eventos
        $events_data = self::get_events_data($year, $month);
        $events_by_date = $events_data['events'];
        $categories_used = $events_data['categories']; // Categorías presentes este mes para el filtro

        ob_start();
        ?>
        <div class="aec-wrapper" data-year="<?php echo $year; ?>" data-month="<?php echo $month; ?>">
            
            <div class="aec-controls">
                <select id="aec-filter-category" class="aec-filter-select">
                    <option value="all">Todas las categorías</option>
                    <?php foreach($categories_used as $cat_id => $cat_name): ?>
                        <option value="cat-<?php echo $cat_id; ?>"><?php echo esc_html($cat_name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="aec-header-nav">
                <button class="aec-nav-btn aec-prev" data-year="<?php echo date('Y', $prev_month_ts); ?>" data-month="<?php echo date('m', $prev_month_ts); ?>"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="aec-month-label"><?php echo date_i18n('F Y', $first_day_ts); ?></div>
                <button class="aec-nav-btn aec-next" data-year="<?php echo date('Y', $next_month_ts); ?>" data-month="<?php echo date('m', $next_month_ts); ?>"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

            <div class="aec-grid">
                <div class="aec-day-name">D</div><div class="aec-day-name">L</div><div class="aec-day-name">M</div>
                <div class="aec-day-name">M</div><div class="aec-day-name">J</div><div class="aec-day-name">V</div><div class="aec-day-name">S</div>

                <?php for ($i = 0; $i < $day_of_week; $i++): ?><div class="aec-day aec-empty"></div><?php endfor; ?>

                <?php for ($day = 1; $day <= $days_in_month; $day++): 
                    $date_str = sprintf('%s-%02d-%02d', $year, $month, $day);
                    $day_events = isset($events_by_date[$date_str]) ? $events_by_date[$date_str] : array();
                    $is_today = ($date_str === $today) ? 'is-today' : '';
                ?>
                    <div class="aec-day <?php echo $is_today; ?>">
                        <span class="aec-day-number"><?php echo $day; ?></span>
                        
                        <div class="aec-icons-container">
                        <?php if (!empty($day_events)): ?>
                            <?php foreach($day_events as $event): 
                                // Renderizar icono
                                $icon_class = $event['icon'] ?: 'fa-solid fa-circle'; // Default circle
                                $cat_class = 'cat-' . $event['cat_id'];
                            ?>
                                <i class="<?php echo esc_attr($icon_class . ' ' . $cat_class); ?> aec-event-icon" title="<?php echo esc_attr($event['title']); ?>"></i>
                            <?php endforeach; ?>

                            <div class="aec-tooltip">
                                <?php foreach($day_events as $event): ?>
                                    <div class="aec-tooltip-item <?php echo 'cat-' . $event['cat_id']; ?>">
                                        <i class="<?php echo esc_attr($event['icon']); ?>"></i> 
                                        <strong><?php echo esc_html($event['title']); ?></strong>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <div class="aec-loading"><i class="fa-solid fa-spinner fa-spin"></i></div>
        </div>
        <?php
        return ob_get_clean();
    }

    private static function get_events_data( $year, $month ) {
        $args = array(
            'post_type' => 'aec_event',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_aec_event_date',
                    'value' => array("$year-$month-01", "$year-$month-31"),
                    'compare' => 'BETWEEN', 'type' => 'DATE'
                )
            )
        );
        $query = new WP_Query($args);
        $events = array();
        $categories = array();

        while($query->have_posts()){
            $query->the_post();
            $id = get_the_ID();
            $date = get_post_meta($id, '_aec_event_date', true);
            
            // Obtener categorías del evento
            $terms = get_the_terms($id, 'aec_category');
            $icon = '';
            $cat_id = 0;
            $cat_name = 'General';

            if($terms && !is_wp_error($terms)){
                $term = current($terms); // Tomamos la primera categoría principal
                $cat_id = $term->term_id;
                $cat_name = $term->name;
                $icon = get_term_meta($term->term_id, 'aec_icon_class', true);
                $categories[$cat_id] = $cat_name; // Guardar para el filtro
            }

            $events[$date][] = array(
                'title' => get_the_title(),
                'icon' => $icon,
                'cat_id' => $cat_id
            );
        }
        wp_reset_postdata();
        return array('events' => $events, 'categories' => $categories);
    }
}