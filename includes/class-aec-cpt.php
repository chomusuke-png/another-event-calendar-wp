<?php
class AEC_CPT {
    public function __construct() {
        add_action( 'init', array( $this, 'register_cpt_and_tax' ) );
        // Hooks para añadir campo en "Añadir nueva" y "Editar" categoría
        add_action( 'aec_category_add_form_fields', array( $this, 'add_icon_field' ) );
        add_action( 'aec_category_edit_form_fields', array( $this, 'edit_icon_field' ) );
        add_action( 'created_aec_category', array( $this, 'save_icon_meta' ) );
        add_action( 'edited_aec_category', array( $this, 'save_icon_meta' ) );
    }

    public function register_cpt_and_tax() {
        // CPT Eventos (Igual que antes)
        register_post_type( 'aec_event', array(
            'public' => true, 'label' => 'Eventos', 'supports' => array('title','editor'), 'menu_icon' => 'dashicons-calendar-alt'
        ));

        // Taxonomía: Categoría de Evento
        register_taxonomy( 'aec_category', 'aec_event', array(
            'label' => 'Categorías de Evento',
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
        ));
    }

    // Campo en formulario "Añadir Nueva"
    public function add_icon_field() {
        ?>
        <div class="form-field">
            <label for="aec_icon_class">Clase FontAwesome</label>
            <input type="text" name="aec_icon_class" id="aec_icon_class" placeholder="ej: fa-solid fa-mug-hot">
            <p>Introduce las clases del icono (versión 6.x).</p>
        </div>
        <?php
    }

    // Campo en formulario "Editar"
    public function edit_icon_field( $term ) {
        $icon = get_term_meta( $term->term_id, 'aec_icon_class', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="aec_icon_class">Clase FontAwesome</label></th>
            <td>
                <input type="text" name="aec_icon_class" id="aec_icon_class" value="<?php echo esc_attr( $icon ); ?>">
                <p class="description">Ejemplo: <code>fa-solid fa-star</code></p>
            </td>
        </tr>
        <?php
    }

    // Guardar el meta
    public function save_icon_meta( $term_id ) {
        if ( isset( $_POST['aec_icon_class'] ) ) {
            update_term_meta( $term_id, 'aec_icon_class', sanitize_text_field( $_POST['aec_icon_class'] ) );
        }
    }
    
    // (Mantener aquí métodos add_date_meta_box y save_date_meta_box del paso anterior...)
     public function add_date_meta_box() { /* ... */ } // Asegúrate de incluir esto
     public function save_date_meta_box($post_id) { /* ... */ } // Asegúrate de incluir esto
}