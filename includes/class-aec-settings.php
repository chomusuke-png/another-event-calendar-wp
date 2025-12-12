<?php

/**
 * Clase para manejar la configuración del plugin en el admin.
 */
class AEC_Settings {

    /**
     * Constructor: Inicia el menú de administración.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Agrega la página de opciones al menú Ajustes.
     *
     * @return void
     */
    public function add_admin_page() {
        add_options_page(
            'Configuración Calendario',
            'Calendario Eventos',
            'manage_options',
            'aec_settings',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Registra la configuración del color.
     *
     * @return void
     */
    public function register_settings() {
        register_setting( 'aec_settings_group', 'aec_highlight_color' );
    }

    /**
     * Renderiza el HTML de la página de configuración.
     *
     * @return void
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Configuración de Another Event Calendar</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'aec_settings_group' );
                do_settings_sections( 'aec_settings_group' );
                $color = get_option( 'aec_highlight_color', '#ff0000' );
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Color del indicador de evento</th>
                        <td><input type="color" name="aec_highlight_color" value="<?php echo esc_attr( $color ); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}