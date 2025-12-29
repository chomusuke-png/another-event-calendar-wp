<?php
/**
 * Plugin Name: Another Event Calendar
 * Description: Widget de calendario configurable con tooltips de eventos.
 * Version: 2.2
 * Author: Zumito
 * Text Domain: another-event-calendar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'AEC_PATH', plugin_dir_path( __FILE__ ) );
define( 'AEC_URL', plugin_dir_url( __FILE__ ) );

// 1. Cargar el componente portable
require_once AEC_PATH . 'includes/simple-icon-picker/class-simple-icon-picker.php';

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

// Assets del Frontend (Calendario)
function aec_enqueue_assets() {
    wp_enqueue_style( 'aec-style', AEC_URL . 'assets/css/style.css', array(), '1.1.0' );
    wp_enqueue_style( 'aec-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
    wp_enqueue_script( 'aec-script', AEC_URL . 'assets/js/script.js', array('jquery'), '1.1.0', true );
    wp_localize_script( 'aec-script', 'aec_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'aec_nonce' )
    ));
}
add_action( 'wp_enqueue_scripts', 'aec_enqueue_assets' );

// Assets del Admin (Usando el componente modular)
function aec_enqueue_admin_assets() {
    if ( class_exists( 'Simple_Icon_Picker' ) ) {
        // Le pasamos la URL donde se encuentra la carpeta del componente
        Simple_Icon_Picker::enqueue_assets( AEC_URL . 'includes/simple-icon-picker/' );
    }
}
add_action( 'admin_enqueue_scripts', 'aec_enqueue_admin_assets' );