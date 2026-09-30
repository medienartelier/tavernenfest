<?php
/**
 * Plugin Name: Tavernenfest Programm
 * Description: Verwaltet Festtage und sortierbare Programmpunkte für das Tavernenfest.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Tavernenfest
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const TAVERNENFEST_PROGRAM_OPTIONS = 'tavernenfest_program_options';

function tavernenfest_program_defaults() {
    return array(
        'eyebrow' => 'Tage & Zeiten',
        'title' => 'Festtage & Programm',
        'intro' => 'Ein Abend, ein Lied, noch eine Runde.',
    );
}

function tavernenfest_program_options() {
    return wp_parse_args( get_option( TAVERNENFEST_PROGRAM_OPTIONS, array() ), tavernenfest_program_defaults() );
}

function tavernenfest_program_register_settings() {
    register_setting( 'tavernenfest_program_settings', TAVERNENFEST_PROGRAM_OPTIONS, array( 'sanitize_callback' => function ( $input ) {
        return array(
            'eyebrow' => sanitize_text_field( $input['eyebrow'] ?? '' ),
            'title' => sanitize_text_field( $input['title'] ?? '' ),
            'intro' => sanitize_text_field( $input['intro'] ?? '' ),
        );
    } ) );
}
add_action( 'admin_init', 'tavernenfest_program_register_settings' );

function tavernenfest_program_register_types() {
    register_post_type( 'festtag', array(
        'labels' => array( 'name' => 'Festtage', 'singular_name' => 'Festtag', 'add_new_item' => 'Festtag hinzufügen', 'edit_item' => 'Festtag bearbeiten' ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'tavernenfest-programm',
        'supports' => array( 'title', 'page-attributes' ),
        'menu_icon' => 'dashicons-calendar-alt',
        'show_in_rest' => true,
    ) );
    register_post_type( 'programmpunkt', array(
        'labels' => array( 'name' => 'Programmpunkte', 'singular_name' => 'Programmpunkt', 'add_new_item' => 'Programmpunkt hinzufügen', 'edit_item' => 'Programmpunkt bearbeiten' ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'tavernenfest-programm',
        'supports' => array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
        'menu_icon' => 'dashicons-tickets-alt',
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'tavernenfest_program_register_types' );

function tavernenfest_program_menu() {
    add_menu_page( 'Festprogramm', 'Festprogramm', 'edit_posts', 'tavernenfest-programm', 'tavernenfest_program_admin_page', 'dashicons-calendar-alt', 29 );
    add_submenu_page( 'tavernenfest-programm', 'Bereichstexte', 'Bereichstexte', 'manage_options', 'tavernenfest-programm-texte', 'tavernenfest_program_texts_page' );
}
add_action( 'admin_menu', 'tavernenfest_program_menu' );

function tavernenfest_program_texts_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    $options = tavernenfest_program_options();
    ?>
    <div class="wrap">
        <h1>Bereichstexte</h1>
        <p>Hier kannst du die Texte der Programm-Section ändern oder leeren.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'tavernenfest_program_settings' ); ?>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_program_eyebrow">Kleine Überschrift</label></th><td><input class="regular-text" id="tavernenfest_program_eyebrow" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[eyebrow]" value="<?php echo esc_attr( $options['eyebrow'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_program_title">Hauptüberschrift</label></th><td><input class="regular-text" id="tavernenfest_program_title" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[title]" value="<?php echo esc_attr( $options['title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_program_intro">Beschreibung</label></th><td><input class="large-text" id="tavernenfest_program_intro" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[intro]" value="<?php echo esc_attr( $options['intro'] ); ?>"></td></tr>
            </table>
            <?php submit_button( 'Bereichstexte speichern' ); ?>
        </form>
    </div>
    <?php
}

function tavernenfest_program_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    $options = tavernenfest_program_options();
    ?>
    <div class="wrap">
        <h1>Bereichstexte</h1>
        <p>Hier kannst du die Überschriften und den Beschreibungstext des Festprogramm-Bereichs ändern oder leeren.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'tavernenfest_program_settings' ); ?>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_program_eyebrow">Kleine Überschrift</label></th><td><input class="regular-text" id="tavernenfest_program_eyebrow" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[eyebrow]" value="<?php echo esc_attr( $options['eyebrow'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_program_title">Hauptüberschrift</label></th><td><input class="regular-text" id="tavernenfest_program_title" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[title]" value="<?php echo esc_attr( $options['title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_program_intro">Beschreibung</label></th><td><input class="large-text" id="tavernenfest_program_intro" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[intro]" value="<?php echo esc_attr( $options['intro'] ); ?>"></td></tr>
            </table>
            <?php submit_button( 'Bereichstexte speichern' ); ?>
        </form>
    </div>
    <?php
}

function tavernenfest_program_admin_assets( $hook ) {
    if ( 'toplevel_page_tavernenfest-programm' !== $hook && 'tavernenfest-programm_page_tavernenfest-programm-texte' !== $hook && 'post.php' !== $hook && 'post-new.php' !== $hook ) return;
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'tavernenfest-program-admin', plugins_url( 'assets/program-admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ), '1.0.0', true );
    wp_localize_script( 'tavernenfest-program-admin', 'tavernenfestProgram', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'tavernenfest_program_order' ) ) );
    wp_enqueue_style( 'tavernenfest-program-admin', plugins_url( 'assets/program-admin.css', __FILE__ ), array(), '1.0.0' );
    if ( 'tavernenfest-programm_page_tavernenfest-programm-texte' === $hook || in_array( get_current_screen()->post_type ?? '', array( 'programmpunkt' ), true ) ) {
        wp_enqueue_media();
        wp_enqueue_script( 'tavernenfest-program-media', plugins_url( 'assets/program-media.js', __FILE__ ), array( 'jquery' ), '1.0.1', true );
    }
}
add_action( 'admin_enqueue_scripts', 'tavernenfest_program_admin_assets' );

function tavernenfest_program_front_assets() {
    wp_enqueue_style( 'tavernenfest-program-front', plugins_url( 'assets/program-front.css', __FILE__ ), array(), '1.0.1' );
}
add_action( 'wp_enqueue_scripts', 'tavernenfest_program_front_assets' );

function tavernenfest_program_admin_page() {
    if ( ! current_user_can( 'edit_posts' ) ) return;
    $days = get_posts( array( 'post_type' => 'festtag', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
    $selected_day = isset( $_GET['festtag'] ) ? absint( $_GET['festtag'] ) : ( $days[0]->ID ?? 0 );
    $items = $selected_day ? get_posts( array( 'post_type' => 'programmpunkt', 'posts_per_page' => -1, 'meta_key' => '_tavernenfest_day', 'meta_value' => $selected_day, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) : array();
    $options = tavernenfest_program_options();
    ?>
    <div class="wrap tavernenfest-program-admin">
        <h1>Festprogramm</h1>
        <p>Lege zuerst unter „Festtage“ die Tage an. Wähle danach einen Tag aus und ziehe die Programmpunkte in die gewünschte Reihenfolge.</p>
        <details class="tavernenfest-program-copy-settings">
            <summary>Texte der Programm-Section bearbeiten</summary>
            <form method="post" action="options.php">
                <?php settings_fields( 'tavernenfest_program_settings' ); ?>
                <table class="form-table" role="presentation">
                    <tr><th><label for="tavernenfest_program_eyebrow">Kleine Überschrift</label></th><td><input class="regular-text" id="tavernenfest_program_eyebrow" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[eyebrow]" value="<?php echo esc_attr( $options['eyebrow'] ); ?>"></td></tr>
                    <tr><th><label for="tavernenfest_program_title">Hauptüberschrift</label></th><td><input class="regular-text" id="tavernenfest_program_title" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[title]" value="<?php echo esc_attr( $options['title'] ); ?>"></td></tr>
                    <tr><th><label for="tavernenfest_program_intro">Beschreibung</label></th><td><input class="large-text" id="tavernenfest_program_intro" name="<?php echo esc_attr( TAVERNENFEST_PROGRAM_OPTIONS ); ?>[intro]" value="<?php echo esc_attr( $options['intro'] ); ?>"></td></tr>
                </table>
                <?php submit_button( 'Section-Texte speichern' ); ?>
            </form>
        </details>
        <p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=festtag' ) ); ?>">Festtag hinzufügen</a> <a class="button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=programmpunkt' ) ); ?>">Programmpunkt hinzufügen</a></p>
        <?php if ( $days ) : ?>
            <nav class="tavernenfest-day-tabs">
                <?php foreach ( $days as $day ) : ?><a class="<?php echo $selected_day === $day->ID ? 'is-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=tavernenfest-programm&festtag=' . $day->ID ) ); ?>"><?php echo esc_html( $day->post_title ); ?></a><?php endforeach; ?>
            </nav>
            <ul id="tavernenfest-program-list" data-day="<?php echo esc_attr( $selected_day ); ?>">
                <?php foreach ( $items as $item ) : ?><li data-id="<?php echo esc_attr( $item->ID ); ?>"><span class="dashicons dashicons-menu"></span><strong><?php echo esc_html( $item->post_title ); ?></strong><span><?php echo esc_html( get_post_meta( $item->ID, '_tavernenfest_time', true ) ); ?></span><a href="<?php echo esc_url( get_edit_post_link( $item->ID ) ); ?>">Bearbeiten</a></li><?php endforeach; ?>
            </ul>
            <p id="tavernenfest-order-status" aria-live="polite"></p>
        <?php else : ?><p>Noch keine Festtage angelegt.</p><?php endif; ?>
    </div>
    <?php
}

function tavernenfest_program_save_order() {
    check_ajax_referer( 'tavernenfest_program_order', 'nonce' );
    if ( ! current_user_can( 'edit_posts' ) ) wp_send_json_error();
    foreach ( (array) ( $_POST['order'] ?? array() ) as $position => $post_id ) {
        wp_update_post( array( 'ID' => absint( $post_id ), 'menu_order' => (int) $position ) );
    }
    wp_send_json_success();
}
add_action( 'wp_ajax_tavernenfest_save_order', 'tavernenfest_program_save_order' );

function tavernenfest_program_meta_boxes() {
    add_meta_box( 'tavernenfest_program_details', 'Programmdetails', 'tavernenfest_program_details_html', 'programmpunkt', 'side' );
}
add_action( 'add_meta_boxes', 'tavernenfest_program_meta_boxes' );

function tavernenfest_program_details_html( $post ) {
    wp_nonce_field( 'tavernenfest_save_program', 'tavernenfest_program_nonce' );
    $day_id = (int) get_post_meta( $post->ID, '_tavernenfest_day', true );
    $time = get_post_meta( $post->ID, '_tavernenfest_time', true );
    $place = get_post_meta( $post->ID, '_tavernenfest_place', true );
    $image = get_post_meta( $post->ID, '_tavernenfest_image', true );
    $days = get_posts( array( 'post_type' => 'festtag', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
    echo '<p><label for="tavernenfest_day">Festtag</label><select id="tavernenfest_day" name="tavernenfest_day" class="widefat"><option value="">Bitte auswählen</option>';
    foreach ( $days as $day ) echo '<option value="' . esc_attr( $day->ID ) . '" ' . selected( $day_id, $day->ID, false ) . '>' . esc_html( $day->post_title ) . '</option>';
    echo '</select></p><p><label for="tavernenfest_time">Uhrzeit</label><input id="tavernenfest_time" name="tavernenfest_time" type="text" value="' . esc_attr( $time ) . '" placeholder="18:00 Uhr" class="widefat"></p><p><label for="tavernenfest_place">Ort / Bühne</label><input id="tavernenfest_place" name="tavernenfest_place" type="text" value="' . esc_attr( $place ) . '" placeholder="Hauptbühne" class="widefat"></p>';
    echo '<p><label for="tavernenfest_image">Logo-/Programmbild</label><input id="tavernenfest_image" name="tavernenfest_image" type="url" value="' . esc_attr( $image ) . '" class="widefat"><button type="button" class="button tavernenfest-select-image" style="margin-top:6px">Bild aus Mediathek wählen</button></p>';
}

function tavernenfest_program_save_meta( $post_id ) {
    if ( ! isset( $_POST['tavernenfest_program_nonce'] ) || ! wp_verify_nonce( $_POST['tavernenfest_program_nonce'], 'tavernenfest_save_program' ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) return;
    update_post_meta( $post_id, '_tavernenfest_day', absint( $_POST['tavernenfest_day'] ?? 0 ) );
    update_post_meta( $post_id, '_tavernenfest_time', sanitize_text_field( $_POST['tavernenfest_time'] ?? '' ) );
    update_post_meta( $post_id, '_tavernenfest_place', sanitize_text_field( $_POST['tavernenfest_place'] ?? '' ) );
    update_post_meta( $post_id, '_tavernenfest_image', esc_url_raw( $_POST['tavernenfest_image'] ?? '' ) );
}
add_action( 'save_post_programmpunkt', 'tavernenfest_program_save_meta' );

function tavernenfest_program_render() {
    $options = tavernenfest_program_options();
    $days = get_posts( array( 'post_type' => 'festtag', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ) ) );
    if ( ! $days ) return '<p class="empty-state">Das Festprogramm wird gerade vorbereitet.</p>';
    ob_start();
    echo '<div class="fest-program" data-fest-program><div class="fest-day-tabs" role="tablist">';
    foreach ( $days as $index => $day ) echo '<button class="fest-day-tab ' . ( 0 === $index ? 'is-active' : '' ) . '" type="button" role="tab" aria-selected="' . ( 0 === $index ? 'true' : 'false' ) . '" data-day-tab="' . esc_attr( $day->ID ) . '">' . esc_html( $day->post_title ) . '</button>';
    echo '</div><div class="fest-day-panels">';
    foreach ( $days as $index => $day ) {
        $items = get_posts( array( 'post_type' => 'programmpunkt', 'posts_per_page' => -1, 'meta_key' => '_tavernenfest_day', 'meta_value' => $day->ID, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        echo '<div class="fest-day-panel ' . ( 0 === $index ? 'is-active' : '' ) . '" data-day-panel="' . esc_attr( $day->ID ) . '" role="tabpanel">';
        if ( $items ) {
            echo '<div class="fest-program-carousel">';
            foreach ( $items as $item ) {
                $image = get_post_meta( $item->ID, '_tavernenfest_image', true );
                echo '<article class="program-card">' . ( $image ? '<img class="program-image" src="' . esc_url( $image ) . '" alt="">' : '' ) . '<span class="program-time">' . esc_html( get_post_meta( $item->ID, '_tavernenfest_time', true ) ) . '</span><h3>' . esc_html( $item->post_title ) . '</h3><div>' . wp_kses_post( apply_filters( 'the_content', $item->post_content ) ) . '</div><span class="program-meta">' . esc_html( get_post_meta( $item->ID, '_tavernenfest_place', true ) ) . '</span></article>';
            }
            echo '</div>';
        } else echo '<p class="empty-state">Für diesen Festtag ist noch kein Programmpunkt angelegt.</p>';
        echo '</div>';
    }
    echo '</div></div>';
    return ob_get_clean();
}
