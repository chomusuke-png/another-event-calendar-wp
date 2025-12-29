<?php
/**
 * Componente Reutilizable: Simple Icon Picker
 * Descripción: Selector de iconos con pestañas y categorías.
 */

if ( ! class_exists( 'Simple_Icon_Picker' ) ) {

    class Simple_Icon_Picker {

        public static function enqueue_assets( $base_url ) {
            $base_url = trailingslashit( $base_url );
            wp_enqueue_style( 'sip-style', $base_url . 'icon-picker-style.css', array(), '1.2' );
            wp_enqueue_script( 'sip-script', $base_url . 'icon-picker-script.js', array('jquery'), '1.2', true );
            wp_enqueue_style( 'sip-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
        }

        public static function get_icons() {
            $json_file = plugin_dir_path( __FILE__ ) . 'icons.json';
            
            if ( file_exists( $json_file ) ) {
                $content = file_get_contents( $json_file );
                $icons = json_decode( $content, true );
                if ( json_last_error() === JSON_ERROR_NONE && is_array( $icons ) ) {
                    return $icons;
                }
            }
            
            // Fallback
            return array( 'General' => array( 'fa-solid fa-star' => 'Estrella' ) );
        }

        public static function render( $field_name, $current_value = '' ) {
            $categories = self::get_icons();
            ?>
            <div class="sip-icon-picker-wrapper">
                <input type="hidden" 
                       name="<?php echo esc_attr( $field_name ); ?>" 
                       class="sip-icon-input-target"
                       value="<?php echo esc_attr( $current_value ); ?>">

                <div class="sip-picker-header">
                    <input type="text" class="sip-icon-search" placeholder="Buscar..." autocomplete="off">
                    <button type="button" class="sip-remove-icon-btn" title="Quitar icono">
                        <span class="dashicons dashicons-no-alt"></span>
                    </button>
                </div>

                <div class="sip-picker-tabs">
                    <?php 
                    $index = 0;
                    foreach ( $categories as $cat_name => $icons ) : 
                        $tab_id = sanitize_title( $cat_name );
                        $active_class = ($index === 0) ? 'active' : '';
                    ?>
                        <button type="button" class="sip-tab-btn <?php echo $active_class; ?>" data-target="<?php echo $tab_id; ?>">
                            <?php echo esc_html( $cat_name ); ?>
                        </button>
                    <?php $index++; endforeach; ?>
                </div>

                <div class="sip-icon-grid-container">
                    
                    <?php foreach ( $categories as $cat_name => $icons ) : 
                        $section_id = sanitize_title( $cat_name );
                    ?>
                        <div class="sip-icon-section" id="sip-section-<?php echo $section_id; ?>">
                            <h4 class="sip-section-title"><?php echo esc_html( $cat_name ); ?></h4>
                            <div class="sip-icon-grid">
                                <?php foreach ( $icons as $icon_class => $label ) : 
                                    $is_selected = ( $current_value === $icon_class ) ? 'selected' : '';
                                ?>
                                    <div class="sip-icon-option <?php echo $is_selected; ?>" 
                                         data-value="<?php echo esc_attr( $icon_class ); ?>" 
                                         data-keywords="<?php echo esc_attr( strtolower( $label ) ); ?>"
                                         title="<?php echo esc_attr( $label ); ?>">
                                        <i class="<?php echo esc_attr( $icon_class ); ?>"></i>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="sip-no-results" style="display:none;">No se encontraron iconos.</div>
                </div>
                
                <p class="description">Haz clic en las pestañas para navegar rápido.</p>
            </div>
            <?php
        }
    }
}