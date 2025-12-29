<?php
/**
 * Componente reutilizable para selección de iconos (Estilo Notion).
 */
class AEC_Icon_Picker {

    /**
     * Devuelve una lista extensa de iconos FontAwesome útiles para calendarios.
     */
    public static function get_icons() {
        return array(
            // General / Oficina
            'fa-solid fa-users'           => 'Reunión Equipo',
            'fa-solid fa-user-tie'        => 'Ejecutivo',
            'fa-solid fa-briefcase'       => 'Negocios',
            'fa-solid fa-building'        => 'Oficina',
            'fa-solid fa-handshake'       => 'Acuerdo',
            'fa-solid fa-file-contract'   => 'Contrato',
            'fa-solid fa-signature'       => 'Firma',
            'fa-solid fa-id-card'         => 'Identificación',
            'fa-solid fa-sitemap'         => 'Organigrama',
            
            // Comunicación
            'fa-solid fa-bullhorn'        => 'Anuncio',
            'fa-solid fa-microphone'      => 'Conferencia',
            'fa-solid fa-headset'         => 'Soporte',
            'fa-solid fa-comments'        => 'Chat',
            'fa-solid fa-envelope'        => 'Correo',
            'fa-solid fa-phone'           => 'Llamada',
            'fa-solid fa-video'           => 'Videollamada',
            'fa-solid fa-wifi'            => 'Conectividad',
            
            // Educación / Formación
            'fa-solid fa-graduation-cap'  => 'Graduación',
            'fa-solid fa-chalkboard-user' => 'Taller',
            'fa-solid fa-book'            => 'Libro',
            'fa-solid fa-certificate'     => 'Certificado',
            'fa-solid fa-lightbulb'       => 'Idea',
            
            // Finanzas / Legal
            'fa-solid fa-chart-line'      => 'Finanzas',
            'fa-solid fa-chart-pie'       => 'Estadísticas',
            'fa-solid fa-money-bill'      => 'Dinero',
            'fa-solid fa-credit-card'     => 'Pago',
            'fa-solid fa-scale-balanced'  => 'Legal',
            'fa-solid fa-gavel'           => 'Jurídico',
            
            // Eventos Sociales / Fechas
            'fa-solid fa-cake-candles'    => 'Cumpleaños',
            'fa-solid fa-champagne-glasses' => 'Celebración',
            'fa-solid fa-calendar-check'  => 'Evento',
            'fa-solid fa-calendar-xmark'  => 'Cancelado',
            'fa-solid fa-clock'           => 'Horario',
            'fa-solid fa-hourglass'       => 'Plazo',
            
            // Estado / Varios
            'fa-solid fa-star'            => 'Destacado',
            'fa-solid fa-heart'           => 'Favorito',
            'fa-solid fa-bell'            => 'Recordatorio',
            'fa-solid fa-exclamation'     => 'Importante',
            'fa-solid fa-check'           => 'Hecho',
            'fa-solid fa-plane'           => 'Viaje',
            'fa-solid fa-car'             => 'Transporte',
            'fa-solid fa-location-dot'    => 'Ubicación',
            'fa-solid fa-lock'            => 'Privado',
            'fa-solid fa-globe'           => 'Global',
            'fa-solid fa-laptop'          => 'Remoto',
            'fa-solid fa-rocket'          => 'Lanzamiento',
        );
    }

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
            
            <p class="description">Selecciona un icono para identificar esta categoría.</p>
        </div>
        <?php
    }
}