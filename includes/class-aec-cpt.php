<?php
class AEC_CPT {
    public function __construct() {
        add_action( 'init', array( $this, 'register_cpt_and_tax' ) );
        
        // Hooks para Categorías (Iconos)
        add_action( 'aec_category_add_form_fields', array( $this, 'add_icon_field' ) );
        add_action( 'aec_category_edit_form_fields', array( $this, 'edit_icon_field' ) );
        add_action( 'created_aec_category', array( $this, 'save_icon_meta' ) );
        add_action( 'edited_aec_category', array( $this, 'save_icon_meta' ) );

        // Hooks para Fecha del Evento
        add_action( 'add_meta_boxes', array( $this, 'add_date_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_date_meta_box' ) );
    }

    public function register_cpt_and_tax() {
        // CPT Eventos
        register_post_type( 'aec_event', array(
            'public'      => true,
            'label'       => 'Eventos',
            'supports'    => array( 'title', 'editor' ),
            'menu_icon'   => 'dashicons-calendar-alt',
            'has_archive' => false,
        ));

        // Taxonomía: Categoría de Evento
        register_taxonomy( 'aec_category', 'aec_event', array(
            'label'             => 'Categorías de Evento',
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
        ));
    }

    // --- Lógica de Iconos (Categorías) ---

    public function add_icon_field() {
        ?>
        <div class="form-field">
            <label for="aec_icon_class">Clase FontAwesome</label>
            <input type="text" name="aec_icon_class" id="aec_icon_class" placeholder="ej: fa-solid fa-mug-hot">
            <p>Introduce las clases del icono (versión 6.x).</p>
        </div>
        <?php
    }

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

    public function save_icon_meta( $term_id ) {
        if ( isset( $_POST['aec_icon_class'] ) ) {
            update_term_meta( $term_id, 'aec_icon_class', sanitize_text_field( $_POST['aec_icon_class'] ) );
        }
    }

    // --- Lógica de Fecha (Meta Box) ---

    public function add_date_meta_box() {
        add_meta_box(
            'aec_event_date',
            'Fecha del Evento',
            array( $this, 'render_meta_box' ),
            'aec_event',
            'side',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        $value = get_post_meta( $post->ID, '_aec_event_date', true );
        echo '<input type="date" name="aec_event_date" value="' . esc_attr( $value ) . '" style="width:100%;">';
    }

    public function save_date_meta_box( $post_id ) {
        if ( array_key_exists( 'aec_event_date', $_POST ) ) {
            update_post_meta(
                $post_id,
                '_aec_event_date',
                sanitize_text_field( $_POST['aec_event_date'] )
            );
        }
    }
}