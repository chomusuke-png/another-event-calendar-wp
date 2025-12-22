<?php
/**
 * Plugin Name: Another Event Calendar
 * Description: Widget de calendario configurable con tooltips de eventos.
 * Version: 2.0
 * Author: Tu Nombre
 * Text Domain: another-event-calendar
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'AEC_PATH', plugin_dir_path( __FILE__ ) );
define( 'AEC_URL', plugin_dir_url( __FILE__ ) );

require_once AEC_PATH . 'includes/class-aec-cpt.php';
require_once AEC_PATH . 'includes/class-aec-settings.php';
require_once AEC_PATH . 'includes/class-aec-display.php';
require_once AEC_PATH . 'includes/class-aec-widget.php';
require_once AEC_PATH . 'includes/class-aec-ajax.php';
require_once AEC_PATH . 'includes/class-aec-shortcode.php';

function aec_init_plugin() {
    new AEC_CPT();
    new AEC_Settings();
    new AEC_Ajax(); 
    new AEC_Shortcode();
    
    add_action( 'widgets_init', function() {
        register_widget( 'AEC_Widget' );
    });
}
add_action( 'plugins_loaded', 'aec_init_plugin' );

function aec_enqueue_assets() {
    // CSS Propio
    wp_enqueue_style( 'aec-style', AEC_URL . 'assets/css/style.css', array(), '1.1.0' );
    
    // Font Awesome (CDN Gratuito)
    wp_enqueue_style( 'aec-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

    // JS Propio
    wp_enqueue_script( 'aec-script', AEC_URL . 'assets/js/script.js', array('jquery'), '1.1.0', true );

    // Pasar variables a JS (AJAX URL y Nonce)
    wp_localize_script( 'aec-script', 'aec_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'aec_nonce' )
    ));
}
add_action( 'wp_enqueue_scripts', 'aec_enqueue_assets' );