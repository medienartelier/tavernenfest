<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function tavernenfest_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    register_nav_menus( array( 'primary' => __( 'Hauptnavigation', 'tavernenfest' ) ) );
}
add_action( 'after_setup_theme', 'tavernenfest_setup' );

function tavernenfest_menu_fallback() {
    echo '<nav class="main-nav" aria-label="Hauptnavigation"><ul id="primary-menu" class="menu"><li><a href="#top">Home</a></li><li><a href="#impressionen">Impressionen</a></li><li><a href="#programm">Programm</a></li><li><a href="#kontakt">Kontakt</a></li></ul></nav>';
}

function tavernenfest_assets() {
    wp_enqueue_style( 'tavernenfest-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'tavernenfest-style', get_stylesheet_uri(), array( 'tavernenfest-fonts' ), '1.0.41' );
    wp_enqueue_script( 'tavernenfest-script', get_template_directory_uri() . '/assets/js/countdown.js', array(), '1.0.8', true );
}
add_action( 'wp_enqueue_scripts', 'tavernenfest_assets' );

function tavernenfest_page_url( $slug ) {
    $page = get_page_by_path( $slug );
    return $page ? get_permalink( $page ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}

function tavernenfest_upload_mimes( $mimes ) {
    if ( current_user_can( 'upload_files' ) ) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter( 'upload_mimes', 'tavernenfest_upload_mimes' );

function tavernenfest_check_svg_filetype( $data, $file, $filename, $mimes ) {
    if ( 'svg' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
        return $data;
    }

    if ( ! current_user_can( 'upload_files' ) ) {
        return $data;
    }

    $data['ext'] = 'svg';
    $data['type'] = 'image/svg+xml';
    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'tavernenfest_check_svg_filetype', 10, 4 );

function tavernenfest_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'tavernenfest_event', array( 'title' => 'Tavernenfest', 'priority' => 30 ) );
    $wp_customize->add_setting( 'tavernenfest_date', array( 'default' => '2027-06-11T18:00', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'tavernenfest_date', array( 'label' => 'Enddatum und -zeit', 'description' => 'Der Countdown läuft bis zu diesem Zeitpunkt.', 'section' => 'tavernenfest_event', 'type' => 'datetime-local' ) );
    $wp_customize->add_setting( 'tavernenfest_hero_image', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tavernenfest_hero_image', array( 'label' => 'Hero-Bild', 'section' => 'tavernenfest_event' ) ) );
    $wp_customize->add_setting( 'tavernenfest_sticky_logo', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tavernenfest_sticky_logo', array( 'label' => 'Logo für Sticky-Navigation', 'description' => 'SVG oder Bild hochladen. Es erscheint links in der Sticky-Navigation.', 'section' => 'tavernenfest_event' ) ) );
}
add_action( 'customize_register', 'tavernenfest_customize_register' );
