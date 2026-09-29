<?php
/**
 * Plugin Name: Tavernenfest Hero Controls
 * Description: Verwaltet Hero-Text, Farben, Größen und ein optionales SVG-Logo für das Tavernenfest-Theme.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Tavernenfest
 * Text Domain: tavernenfest-hero-controls
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const TAVERNENFEST_HERO_OPTION = 'tavernenfest_hero_options';

function tavernenfest_hero_defaults() {
    return array(
        'eyebrow' => 'Ein Fest für alle Sinne',
        'title' => 'Tavernenfest Trautmannsdorf',
        'copy' => 'Drei Tage voller Musik, Geschichten, guter Gesellschaft und dem Duft aus der Festküche.',
        'eyebrow_color' => '#c58a3a',
        'title_color' => '#ffffff',
        'copy_color' => '#ffffff',
        'title_size' => 6.8,
        'title_size_mobile' => 4.6,
        'copy_size' => 1.08,
        'copy_size_mobile' => .92,
        'eyebrow_size' => .76,
        'eyebrow_size_mobile' => .68,
        'countdown_date' => '2027-06-11T18:00',
        'logo_url' => '',
    );
}

function tavernenfest_hero_options() {
    return wp_parse_args( get_option( TAVERNENFEST_HERO_OPTION, array() ), tavernenfest_hero_defaults() );
}

function tavernenfest_hero_register_settings() {
    register_setting( 'tavernenfest_hero', TAVERNENFEST_HERO_OPTION, array( 'sanitize_callback' => 'tavernenfest_hero_sanitize' ) );
}
add_action( 'admin_init', 'tavernenfest_hero_register_settings' );

function tavernenfest_hero_sanitize( $input ) {
    $defaults = tavernenfest_hero_defaults();
    $current = tavernenfest_hero_options();
    $output = array();
    $output['eyebrow'] = sanitize_text_field( $input['eyebrow'] ?? $defaults['eyebrow'] );
    $output['title'] = sanitize_text_field( $input['title'] ?? $defaults['title'] );
    $output['copy'] = sanitize_textarea_field( $input['copy'] ?? $defaults['copy'] );
    $output['eyebrow_color'] = sanitize_hex_color( $input['eyebrow_color'] ?? '' ) ?: $defaults['eyebrow_color'];
    $output['title_color'] = sanitize_hex_color( $input['title_color'] ?? '' ) ?: $defaults['title_color'];
    $output['copy_color'] = sanitize_hex_color( $input['copy_color'] ?? '' ) ?: $defaults['copy_color'];
    foreach ( array( 'title_size', 'title_size_mobile', 'copy_size', 'copy_size_mobile', 'eyebrow_size', 'eyebrow_size_mobile' ) as $key ) {
        $output[ $key ] = max( .5, min( 12, (float) ( $input[ $key ] ?? $defaults[ $key ] ) ) );
    }
    $output['countdown_date'] = preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $input['countdown_date'] ?? '' ) ? sanitize_text_field( $input['countdown_date'] ) : $defaults['countdown_date'];
    $output['logo_url'] = array_key_exists( 'logo_url', $input ) ? esc_url_raw( $input['logo_url'] ) : $current['logo_url'];
    return $output;
}

function tavernenfest_hero_menu() {
    add_menu_page( 'Hero bearbeiten', 'Tavernenfest Hero', 'manage_options', 'tavernenfest-hero', 'tavernenfest_hero_page', 'dashicons-format-image', 30 );
}
add_action( 'admin_menu', 'tavernenfest_hero_menu' );

function tavernenfest_hero_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $options = tavernenfest_hero_options();
    $upload_error = '';
    if ( isset( $_POST['tavernenfest_logo_delete'] ) ) {
        check_admin_referer( 'tavernenfest_delete_logo', 'tavernenfest_delete_logo_nonce' );
        if ( ! empty( $options['logo_url'] ) ) {
            $upload_dir = wp_upload_dir();
            $logo_path = wp_normalize_path( str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $options['logo_url'] ) );
            $upload_base = trailingslashit( wp_normalize_path( $upload_dir['basedir'] ) );
            if ( 0 === strpos( $logo_path, $upload_base ) && file_exists( $logo_path ) ) {
                wp_delete_file( $logo_path );
            }
        }
        $options['logo_url'] = '';
        update_option( TAVERNENFEST_HERO_OPTION, $options );
        echo '<div class="notice notice-success is-dismissible"><p>SVG-Logo wurde gelöscht.</p></div>';
    }
    if ( isset( $_POST['tavernenfest_logo_upload'] ) ) {
        check_admin_referer( 'tavernenfest_upload_logo', 'tavernenfest_logo_nonce' );
        if ( ! empty( $_FILES['tavernenfest_logo']['name'] ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            $file = $_FILES['tavernenfest_logo'];
            $extension = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
            $svg_contents = ( ! empty( $file['tmp_name'] ) && is_uploaded_file( $file['tmp_name'] ) ) ? file_get_contents( $file['tmp_name'] ) : '';
            if ( 'svg' !== $extension || false === $svg_contents || ! preg_match( '/<svg[\s>]/i', $svg_contents ) ) {
                $upload_error = 'Bitte eine gültige SVG-Datei auswählen.';
            } else {
                add_filter( 'upload_mimes', 'tavernenfest_hero_svg_mime' );
                $uploaded = wp_handle_upload( $file, array( 'test_form' => false, 'test_type' => false, 'mimes' => array( 'svg' => 'image/svg+xml' ) ) );
                remove_filter( 'upload_mimes', 'tavernenfest_hero_svg_mime' );
                if ( isset( $uploaded['url'] ) ) {
                    $options['logo_url'] = esc_url_raw( $uploaded['url'] );
                    update_option( TAVERNENFEST_HERO_OPTION, $options );
                    echo '<div class="notice notice-success is-dismissible"><p>SVG-Logo wurde hochgeladen.</p></div>';
                } else {
                    $upload_error = $uploaded['error'] ?? 'Der Upload ist fehlgeschlagen.';
                }
            }
        } elseif ( isset( $_FILES['tavernenfest_logo']['error'] ) && UPLOAD_ERR_OK !== (int) $_FILES['tavernenfest_logo']['error'] ) {
            $upload_error = 'Der Upload ist fehlgeschlagen (Fehlercode ' . (int) $_FILES['tavernenfest_logo']['error'] . ').';
        } else {
            $upload_error = 'Bitte zuerst eine SVG-Datei auswählen.';
        }
    }
    if ( $upload_error ) {
        echo '<div class="notice notice-error"><p>' . esc_html( $upload_error ) . '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Tavernenfest Hero</h1>
        <p>Hier pflegst du den Text und die Darstellung des Hero-Bereichs. Das SVG-Logo ersetzt den großen Titel, sobald eines hochgeladen wurde.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'tavernenfest_hero' ); ?>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_eyebrow">Kleine Überschrift</label></th><td><input class="regular-text" id="tavernenfest_eyebrow" name="<?php echo esc_attr( TAVERNENFEST_HERO_OPTION ); ?>[eyebrow]" value="<?php echo esc_attr( $options['eyebrow'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_title">Hero-Titel</label></th><td><input class="large-text" id="tavernenfest_title" name="<?php echo esc_attr( TAVERNENFEST_HERO_OPTION ); ?>[title]" value="<?php echo esc_attr( $options['title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_copy">Beschreibung</label></th><td><textarea class="large-text" rows="3" id="tavernenfest_copy" name="<?php echo esc_attr( TAVERNENFEST_HERO_OPTION ); ?>[copy]"><?php echo esc_textarea( $options['copy'] ); ?></textarea></td></tr>
                <tr><th>Textfarben</th><td><?php tavernenfest_hero_color_input( 'eyebrow_color', 'Kleine Überschrift', $options ); ?><?php tavernenfest_hero_color_input( 'title_color', 'Titel', $options ); ?><?php tavernenfest_hero_color_input( 'copy_color', 'Beschreibung', $options ); ?></td></tr>
                <tr><th>Schriftgrößen</th><td><?php tavernenfest_hero_number_input( 'title_size', 'Titel Desktop', $options, 'rem' ); ?><?php tavernenfest_hero_number_input( 'title_size_mobile', 'Titel Mobil', $options, 'rem' ); ?><br><?php tavernenfest_hero_number_input( 'copy_size', 'Beschreibung Desktop', $options, 'rem' ); ?><?php tavernenfest_hero_number_input( 'copy_size_mobile', 'Beschreibung Mobil', $options, 'rem' ); ?></td></tr>
                <tr><th>Countdown</th><td><label for="tavernenfest_countdown_date">Enddatum und -zeit</label> <input type="datetime-local" id="tavernenfest_countdown_date" name="<?php echo esc_attr( TAVERNENFEST_HERO_OPTION ); ?>[countdown_date]" value="<?php echo esc_attr( $options['countdown_date'] ); ?>"><p class="description">Der Countdown läuft bis zu diesem Zeitpunkt.</p></td></tr>
                <tr><th>Kleine Überschrift</th><td><?php tavernenfest_hero_number_input( 'eyebrow_size', 'Desktop', $options, 'rem' ); ?><?php tavernenfest_hero_number_input( 'eyebrow_size_mobile', 'Mobil', $options, 'rem' ); ?></td></tr>
            </table>
            <?php submit_button( 'Hero-Einstellungen speichern' ); ?>
        </form>
        <hr>
        <h2>SVG-Logo</h2>
        <?php if ( $options['logo_url'] ) : ?>
            <p><img src="<?php echo esc_url( $options['logo_url'] ); ?>" alt="Aktuelles SVG-Logo" style="max-width:360px;max-height:140px;background:#2f5f91;padding:16px"></p>
            <form method="post" style="margin-bottom:16px">
                <?php wp_nonce_field( 'tavernenfest_delete_logo', 'tavernenfest_delete_logo_nonce' ); ?>
                <input type="submit" name="tavernenfest_logo_delete" class="button button-link-delete" value="SVG-Logo löschen">
            </form>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field( 'tavernenfest_upload_logo', 'tavernenfest_logo_nonce' ); ?>
            <input type="file" name="tavernenfest_logo" accept=".svg,image/svg+xml" required>
            <input type="submit" name="tavernenfest_logo_upload" class="button button-secondary" value="SVG-Logo hochladen">
        </form>
    </div>
    <?php
}

function tavernenfest_hero_color_input( $key, $label, $options ) {
    echo '<label style="display:inline-flex;align-items:center;gap:6px;margin:0 18px 8px 0">' . esc_html( $label ) . ' <input type="color" name="' . esc_attr( TAVERNENFEST_HERO_OPTION ) . '[' . esc_attr( $key ) . ']" value="' . esc_attr( $options[ $key ] ) . '"></label>';
}

function tavernenfest_hero_number_input( $key, $label, $options, $unit ) {
    echo '<label style="display:inline-block;margin:0 18px 8px 0">' . esc_html( $label ) . ' <input type="number" step=".1" min=".5" max="12" name="' . esc_attr( TAVERNENFEST_HERO_OPTION ) . '[' . esc_attr( $key ) . ']" value="' . esc_attr( $options[ $key ] ) . '" style="width:80px"> ' . esc_html( $unit ) . '</label>';
}

function tavernenfest_hero_svg_mime( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

function tavernenfest_hero_render_data() {
    $options = tavernenfest_hero_options();
    return array(
        'eyebrow' => $options['eyebrow'],
        'title' => $options['title'],
        'copy' => $options['copy'],
        'countdown_date' => $options['countdown_date'],
        'logo_url' => $options['logo_url'],
        'style' => '--hero-eyebrow-color:' . $options['eyebrow_color'] . ';--hero-title-color:' . $options['title_color'] . ';--hero-copy-color:' . $options['copy_color'] . ';--hero-eyebrow-size:' . $options['eyebrow_size'] . 'rem;--hero-eyebrow-size-mobile:' . $options['eyebrow_size_mobile'] . 'rem;--hero-title-size:' . $options['title_size'] . 'rem;--hero-title-size-mobile:' . $options['title_size_mobile'] . 'rem;--hero-copy-size:' . $options['copy_size'] . 'rem;--hero-copy-size-mobile:' . $options['copy_size_mobile'] . 'rem;',
    );
}
