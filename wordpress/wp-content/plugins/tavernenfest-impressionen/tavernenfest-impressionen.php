<?php
/**
 * Plugin Name: Tavernenfest Impressionen
 * Description: Verwaltet das Video der Impressionen-Section.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Tavernenfest
 */

if ( ! defined( 'ABSPATH' ) ) exit;

const TAVERNENFEST_IMPRESSIONS_OPTIONS = 'tavernenfest_impressions_options';

function tavernenfest_impressions_defaults() {
    return array( 'title' => 'Impressionen', 'video_url' => '', 'video_file' => '' );
}

function tavernenfest_impressions_options() {
    $current = get_option( TAVERNENFEST_IMPRESSIONS_OPTIONS, array() );
    $legacy = get_option( 'tavernenfest_program_options', array() );
    return wp_parse_args( $current, array(
        'title' => $legacy['impressions_title'] ?? 'Impressionen',
        'video_url' => $legacy['video_url'] ?? '',
        'video_file' => $legacy['video_file'] ?? '',
    ) );
}

function tavernenfest_impressions_settings() {
    register_setting( 'tavernenfest_impressions_settings', TAVERNENFEST_IMPRESSIONS_OPTIONS, array(
        'sanitize_callback' => function ( $input ) {
            return array(
                'title' => sanitize_text_field( $input['title'] ?? '' ),
                'video_url' => esc_url_raw( $input['video_url'] ?? '' ),
                'video_file' => esc_url_raw( $input['video_file'] ?? '' ),
            );
        },
    ) );
}
add_action( 'admin_init', 'tavernenfest_impressions_settings' );

function tavernenfest_impressions_menu() {
    add_menu_page( 'Impressionen', 'Impressionen', 'edit_posts', 'tavernenfest-impressionen', 'tavernenfest_impressions_page', 'dashicons-format-video', 30 );
}
add_action( 'admin_menu', 'tavernenfest_impressions_menu' );

function tavernenfest_impressions_assets( $hook ) {
    if ( 'toplevel_page_tavernenfest-impressionen' !== $hook ) return;
    wp_enqueue_media();
    wp_enqueue_script( 'tavernenfest-impressions-media', plugins_url( 'assets/impressionen-admin.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'tavernenfest_impressions_assets' );

function tavernenfest_impressions_page() {
    if ( ! current_user_can( 'edit_posts' ) ) return;
    $options = tavernenfest_impressions_options();
    ?>
    <div class="wrap">
        <h1>Impressionen</h1>
        <p>Verwalte hier ausschließlich das Video der Impressionen-Section.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'tavernenfest_impressions_settings' ); ?>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_impressions_title">Überschrift</label></th><td><input class="regular-text" id="tavernenfest_impressions_title" name="<?php echo esc_attr( TAVERNENFEST_IMPRESSIONS_OPTIONS ); ?>[title]" value="<?php echo esc_attr( $options['title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_impressions_video_url">Video-Link</label></th><td><input class="large-text" type="url" id="tavernenfest_impressions_video_url" name="<?php echo esc_attr( TAVERNENFEST_IMPRESSIONS_OPTIONS ); ?>[video_url]" value="<?php echo esc_attr( $options['video_url'] ); ?>" placeholder="https://... "><p class="description">Direkter MP4-Link oder kompatibler Video-Link.</p></td></tr>
                <tr><th><label for="tavernenfest_impressions_video_file">Video aus Mediathek</label></th><td><input class="large-text" type="url" id="tavernenfest_impressions_video_file" name="<?php echo esc_attr( TAVERNENFEST_IMPRESSIONS_OPTIONS ); ?>[video_file]" value="<?php echo esc_attr( $options['video_file'] ); ?>"><button type="button" class="button tavernenfest-select-impressions-video" style="margin-top:6px">Video auswählen oder hochladen</button></td></tr>
            </table>
            <?php submit_button( 'Impressionen speichern' ); ?>
        </form>
    </div>
    <?php
}

function tavernenfest_impressions_render() {
    $options = tavernenfest_impressions_options();
    $video = $options['video_file'] ?: $options['video_url'];
    $media = '<p class="impressions-empty">Das Video wird hier bald ergänzt.</p>';
    if ( $video ) {
        $extension = strtolower( pathinfo( wp_parse_url( $video, PHP_URL_PATH ) ?: '', PATHINFO_EXTENSION ) );
        $direct_video = in_array( $extension, array( 'mp4', 'webm', 'm4v', 'ogv' ), true );
        if ( $direct_video ) {
            $media = '<div class="impressions-video"><video controls preload="metadata" playsinline src="' . esc_url( $video ) . '"></video></div>';
        } else {
            $embed = wp_oembed_get( $video, array( 'width' => 1600, 'height' => 900 ) );
            if ( ! $embed && preg_match( '~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{6,})~', $video, $matches ) ) {
                $embed = '<iframe src="https://www.youtube-nocookie.com/embed/' . esc_attr( $matches[1] ) . '" title="Impressionen-Video" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
            }
            $allowed_embed = array( 'iframe' => array( 'src' => true, 'title' => true, 'loading' => true, 'allow' => true, 'allowfullscreen' => true, 'width' => true, 'height' => true ) );
            $media = $embed ? '<div class="impressions-video impressions-embed">' . wp_kses( $embed, $allowed_embed ) . '</div>' : '<p class="impressions-empty">Dieser Video-Link konnte nicht eingebettet werden. Bitte einen YouTube-, Vimeo- oder direkten MP4-Link verwenden.</p>';
        }
    }
    return '<section class="section impressions-section" id="impressionen" aria-labelledby="impressionen-title"><div class="section-inner"><h2 id="impressionen-title">' . esc_html( $options['title'] ) . '</h2>' . $media . '</div></section>';
}
