<?php
class AEC_Ajax {
    public function __construct() {
        add_action( 'wp_ajax_aec_load_calendar', array( $this, 'load_calendar' ) );
        add_action( 'wp_ajax_nopriv_aec_load_calendar', array( $this, 'load_calendar' ) );
    }

    public function load_calendar() {
        check_ajax_referer( 'aec_nonce', 'nonce' );

        $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
        $month = isset($_POST['month']) ? intval($_POST['month']) : date('m');

        echo AEC_Display::get_calendar_html( $year, $month );
        wp_die();
    }
}