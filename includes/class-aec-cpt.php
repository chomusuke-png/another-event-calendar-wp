<?php
class AEC_CPT {
    
    public function __construct() {
        add_action( 'init', array( $this, 'register_cpt_and_tax' ) );
        
        // Hooks Categorías
        add_action( 'aec_category_add_form_fields', array( $this, 'add_category_fields' ) );
        add_action( 'aec_category_edit_form_fields', array( $this, 'edit_category_fields' ) );
        add_action( 'created_aec_category', array( $this, 'save_category_meta' ) );
        add_action( 'edited_aec_category', array( $this, 'save_category_meta' ) );

        // Hooks Fecha
        add_action( 'add_meta_boxes', array( $this, 'add_date_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_date_meta_box' ) );
    }

    private function get_corporate_icons() {
        return array(
            ''                            => '-- Seleccionar predefinido --',
            'fa-solid fa-users'           => 'Reunión / Asamblea',
            'fa-solid fa-chalkboard-user' => 'Taller / Capacitación',
            'fa-solid fa-microphone'      => 'Conferencia / Charla',
            'fa-solid fa-handshake'       => 'Acuerdo / Alianza',
            'fa-solid fa-file-contract'   => 'Firma / Legal',
            'fa-solid fa-chart-line'      => 'Finanzas / Reporte',
            'fa-solid fa-bullhorn'        => 'Anuncio Importante',
            'fa-solid fa-briefcase'       => 'Corporativo',
            'fa-solid fa-laptop'          => 'Virtual / Zoom',
        );
    }

    public function register_cpt_and_tax() {
        register_post_type( 'aec_event', array(
            'public' => true, 'label' => 'Eventos', 'supports' => array( 'title', 'editor' ), 'menu_icon' => 'dashicons-calendar-alt'
        ));
        register_taxonomy( 'aec_category', 'aec_event', array(
            'label' => 'Categorías de Evento', 'hierarchical' => true, 'show_ui' => true, 'show_admin_column' => true,
        ));
    }

    // --- Script para sincronizar Select con Input ---
    private function print_sync_script() {
        ?>
        <script>
        jQuery(document).ready(function($){
            $('#aec_icon_selector').on('change', function(){
                if($(this).val() !== '') {
                    $('#aec_icon_class').val($(this).val());
                }
            });
        });
        </script>
        <?php
    }

    // --- Campos "Añadir Nueva" ---
    public function add_category_fields() {
        $icons = $this->get_corporate_icons();
        $this->print_sync_script();
        ?>
        <div class="form-field">
            <label for="aec_icon_class">Icono (Clase FontAwesome)</label>
            <div style="display:flex; gap:10px;">
                <input type="text" name="aec_icon_class" id="aec_icon_class" placeholder="fa-solid fa-star">
                <select id="aec_icon_selector">
                    <?php foreach ( $icons as $class => $label ) : ?>
                        <option value="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <p>Escribe el código manual o elige uno de la lista para rellenarlo.</p>
        </div>
        <div class="form-field">
            <label for="aec_category_color">Color Identificativo</label>
            <input type="color" name="aec_category_color" id="aec_category_color" value="#2271b1">
        </div>
        <?php
    }

    // --- Campos "Editar" ---
    public function edit_category_fields( $term ) {
        $current_icon = get_term_meta( $term->term_id, 'aec_icon_class', true );
        $current_color = get_term_meta( $term->term_id, 'aec_category_color', true );
        $icons = $this->get_corporate_icons();
        $this->print_sync_script();
        
        if ( ! $current_color ) $current_color = '#2271b1';
        ?>
        <tr class="form-field">
            <th scope="row"><label for="aec_icon_class">Icono</label></th>
            <td>
                <div style="display:flex; gap:10px; align-items:center;">
                    <input type="text" name="aec_icon_class" id="aec_icon_class" value="<?php echo esc_attr( $current_icon ); ?>">
                    <select id="aec_icon_selector">
                        <?php foreach ( $icons as $class => $label ) : ?>
                            <option value="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="description">Usa el selector para rellenar rápido o escribe tu propio código.</p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="aec_category_color">Color</label></th>
            <td><input type="color" name="aec_category_color" value="<?php echo esc_attr( $current_color ); ?>"></td>
        </tr>
        <?php
    }

    public function save_category_meta( $term_id ) {
        if ( isset( $_POST['aec_icon_class'] ) ) update_term_meta( $term_id, 'aec_icon_class', sanitize_text_field( $_POST['aec_icon_class'] ) );
        if ( isset( $_POST['aec_category_color'] ) ) update_term_meta( $term_id, 'aec_category_color', sanitize_hex_color( $_POST['aec_category_color'] ) );
    }

    // --- Meta Box Fecha (Mantener intacto) ---
    public function add_date_meta_box() { add_meta_box( 'aec_event_date', 'Fecha del Evento', array( $this, 'render_meta_box' ), 'aec_event', 'side', 'high' ); }
    public function render_meta_box( $post ) { $val = get_post_meta( $post->ID, '_aec_event_date', true ); echo '<input type="date" name="aec_event_date" value="' . esc_attr( $val ) . '" style="width:100%;">'; }
    public function save_date_meta_box( $post_id ) { if ( isset( $_POST['aec_event_date'] ) ) update_post_meta( $post_id, '_aec_event_date', sanitize_text_field( $_POST['aec_event_date'] ) ); }
}