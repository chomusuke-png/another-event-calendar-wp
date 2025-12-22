<?php
/**
 * Maneja el registro y la lógica del Shortcode del calendario.
 */
class AEC_Shortcode {
    public function __construct() {
        add_shortcode( 'aec_calendar', array( $this, 'render_shortcode' ) );
    }
    public function render_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'year'  => null,
            'month' => null,
        ), $atts, 'aec_calendar' );

        if ( class_exists( 'AEC_Display' ) ) {
            return AEC_Display::get_calendar_html( $atts['year'], $atts['month'] );
        }

        return '';
    }
}