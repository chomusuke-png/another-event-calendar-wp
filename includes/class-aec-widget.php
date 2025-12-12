<?php

/**
 * Clase que define el Widget de WordPress.
 */
class AEC_Widget extends WP_Widget {

    /**
     * Constructor del widget.
     */
    public function __construct() {
        parent::__construct(
            'aec_widget',
            'Another Event Calendar',
            array( 'description' => 'Muestra un calendario mensual con eventos.' )
        );
    }

    /**
     * Renderiza el contenido del widget en el frontend.
     *
     * @param array $args Argumentos del widget (before_widget, etc).
     * @param array $instance Instancia guardada del widget.
     * @return void
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        // Renderizar el calendario desde la clase Display
        if ( class_exists( 'AEC_Display' ) ) {
            echo AEC_Display::get_calendar_html();
        }

        echo $args['after_widget'];
    }

    /**
     * Formulario de configuración en el panel de Widgets.
     *
     * @param array $instance Configuración actual.
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Calendario de Eventos';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Título:</label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Guarda las opciones del widget.
     *
     * @param array $new_instance Nuevos valores.
     * @param array $old_instance Viejos valores.
     * @return array Instancia saneada.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }
}