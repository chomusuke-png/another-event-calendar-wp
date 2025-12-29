<?php
/**
 * Componente Reutilizable: Simple Icon Picker
 * Descripción: Selector de iconos visual con buscador.
 */

if ( ! class_exists( 'Simple_Icon_Picker' ) ) {

    class Simple_Icon_Picker {

        /**
         * Encola los scripts y estilos necesarios.
         * Llama a esto desde el hook 'admin_enqueue_scripts' de tu plugin/tema.
         * * @param string $base_url URL absoluta a la carpeta de este componente.
         */
        public static function enqueue_assets( $base_url ) {
            // Asegurarse de que la URL termine en slash
            $base_url = trailingslashit( $base_url );

            wp_enqueue_style( 'sip-style', $base_url . 'icon-picker-style.css', array(), '1.0' );
            wp_enqueue_script( 'sip-script', $base_url . 'icon-picker-script.js', array('jquery'), '1.0', true );
            
            // FontAwesome es necesario. Si tu proyecto ya lo tiene, puedes comentar esto.
            wp_enqueue_style( 'sip-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
        }

        /**
         * Lista de iconos (Puedes editarla o pasarla por filtro si quieres más flexibilidad)
         */
        public static function get_icons() {
            return array(
                // General
                'fa-solid fa-users'           => 'Reunión Equipo',
                'fa-solid fa-briefcase'       => 'Negocios',
                'fa-solid fa-building'        => 'Oficina',
                'fa-solid fa-handshake'       => 'Acuerdo',
                'fa-solid fa-file-contract'   => 'Contrato',
                'fa-solid fa-signature'       => 'Firma',
                
                // Comunicación
                'fa-solid fa-bullhorn'        => 'Anuncio',
                'fa-solid fa-microphone'      => 'Conferencia',
                'fa-solid fa-comments'        => 'Chat',
                'fa-solid fa-envelope'        => 'Correo',
                'fa-solid fa-phone'           => 'Llamada',
                'fa-solid fa-video'           => 'Videollamada',
                'fa-solid fa-wifi'            => 'Online',
                
                // Educación
                'fa-solid fa-graduation-cap'  => 'Graduación',
                'fa-solid fa-chalkboard-user' => 'Taller',
                'fa-solid fa-book'            => 'Libro',
                'fa-solid fa-lightbulb'       => 'Idea',
                
                // Finanzas
                'fa-solid fa-chart-line'      => 'Finanzas',
                'fa-solid fa-money-bill'      => 'Dinero',
                'fa-solid fa-credit-card'     => 'Pago',
                
                // Fechas / Eventos
                'fa-solid fa-cake-candles'    => 'Cumpleaños',
                'fa-solid fa-champagne-glasses' => 'Fiesta',
                'fa-solid fa-calendar-check'  => 'Evento',
                'fa-solid fa-clock'           => 'Horario',
                'fa-solid fa-bell'            => 'Recordatorio',
                'fa-solid fa-star'            => 'Importante',
                'fa-solid fa-exclamation'     => 'Urgente',
                'fa-solid fa-check'           => 'Completado',
                'fa-solid fa-plane'           => 'Viaje',
                'fa-solid fa-location-dot'    => 'Ubicación',
                'fa-solid fa-laptop'          => 'Remoto',
                'fa-solid fa-rocket'          => 'Lanzamiento',
            );
        }

        /**
         * Renderiza el HTML del selector.
         */
        public static function render( $field_name, $current_value = '' ) {
            $icons = self::get_icons();
            ?>
            <div class="aec-icon-picker-wrapper">
                <input type="hidden" 
                       name="<?php echo esc_attr( $field_name ); ?>" 
                       class="aec-icon-input-target"
                       value="<?php echo esc_attr( $current_value ); ?>">

                <div class="aec-picker-header">
                    <input type="text" 
                           class="aec-icon-search" 
                           placeholder="Buscar icono..." 
                           autocomplete="off">
                    
                    <button type="button" class="aec-remove-icon-btn" title="Quitar icono">
                        <span class="dashicons dashicons-no-alt"></span> Quitar
                    </button>
                </div>

                <div class="aec-icon-grid-container">
                    <div class="aec-icon-grid">
                        <?php foreach ( $icons as $icon_class => $label ) : 
                            $is_selected = ( $current_value === $icon_class ) ? 'selected' : '';
                        ?>
                            <div class="aec-icon-option <?php echo $is_selected; ?>" 
                                 data-value="<?php echo esc_attr( $icon_class ); ?>" 
                                 data-keywords="<?php echo esc_attr( strtolower( $label ) ); ?>"
                                 title="<?php echo esc_attr( $label ); ?>">
                                <i class="<?php echo esc_attr( $icon_class ); ?>"></i>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="aec-no-results" style="display:none;">No se encontraron iconos.</div>
                </div>
                
                <p class="description">Selecciona un icono de la lista.</p>
            </div>
            <?php
        }
    }
}