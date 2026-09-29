<?php
/**
 * Plugin Name: Tavernenfest Kontaktblock
 * Description: Verwaltet Kontaktformular, Veranstalteranschrift und Tischreservierung.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Tavernenfest
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

const TAVERNENFEST_CONTACT_OPTIONS = 'tavernenfest_contact_options';

function tavernenfest_contact_defaults() {
    return array(
        'eyebrow' => 'Kontakt',
        'title' => 'Wir freuen uns auf deine Nachricht.',
        'intro' => 'Weitere Informationen zum Tavernenfest und zur Mitwirkung erhältst du direkt beim Veranstalter.',
        'recipient_email' => get_option( 'admin_email' ),
        'name_label' => 'Name',
        'required_label' => 'Pflichtfeld',
        'phone_label' => 'Telefon/Mobil',
        'message_label' => 'Nachricht',
        'submit_label' => 'Nachricht senden',
        'sending_label' => 'Wird gesendet...',
        'success_message' => 'Danke, deine Nachricht wurde gesendet.',
        'validation_message' => 'Bitte prüfe deine Eingaben.',
        'name_error' => 'Bitte gib deinen Namen ein.',
        'phone_error' => 'Bitte gib eine gültige Telefonnummer ein.',
        'message_error' => 'Bitte schreibe eine kurze Nachricht.',
        'link_error' => 'Bitte sende maximal einen Link mit.',
        'organizer_title' => 'Veranstalter',
        'organizer_name' => 'TSV Preying 1931 e. V.',
        'organizer_address' => "Preying\n94104 Tittling",
        'reservation_title' => 'Tischreservierung',
        'reservation_name' => 'Ansprechpartner',
        'reservation_phone' => '',
    );
}

function tavernenfest_contact_options() {
    return wp_parse_args( get_option( TAVERNENFEST_CONTACT_OPTIONS, array() ), tavernenfest_contact_defaults() );
}

function tavernenfest_contact_sanitize_options( $input ) {
    return array(
        'eyebrow' => sanitize_text_field( $input['eyebrow'] ?? '' ),
        'title' => sanitize_text_field( $input['title'] ?? '' ),
        'intro' => sanitize_textarea_field( $input['intro'] ?? '' ),
        'recipient_email' => sanitize_email( $input['recipient_email'] ?? '' ),
        'name_label' => sanitize_text_field( $input['name_label'] ?? '' ),
        'required_label' => sanitize_text_field( $input['required_label'] ?? '' ),
        'phone_label' => sanitize_text_field( $input['phone_label'] ?? '' ),
        'message_label' => sanitize_text_field( $input['message_label'] ?? '' ),
        'submit_label' => sanitize_text_field( $input['submit_label'] ?? '' ),
        'sending_label' => sanitize_text_field( $input['sending_label'] ?? '' ),
        'success_message' => sanitize_text_field( $input['success_message'] ?? '' ),
        'validation_message' => sanitize_text_field( $input['validation_message'] ?? '' ),
        'name_error' => sanitize_text_field( $input['name_error'] ?? '' ),
        'phone_error' => sanitize_text_field( $input['phone_error'] ?? '' ),
        'message_error' => sanitize_text_field( $input['message_error'] ?? '' ),
        'link_error' => sanitize_text_field( $input['link_error'] ?? '' ),
        'organizer_title' => sanitize_text_field( $input['organizer_title'] ?? '' ),
        'organizer_name' => sanitize_text_field( $input['organizer_name'] ?? '' ),
        'organizer_address' => sanitize_textarea_field( $input['organizer_address'] ?? '' ),
        'reservation_title' => sanitize_text_field( $input['reservation_title'] ?? '' ),
        'reservation_name' => sanitize_text_field( $input['reservation_name'] ?? '' ),
        'reservation_phone' => sanitize_text_field( $input['reservation_phone'] ?? '' ),
    );
}

function tavernenfest_contact_register_settings() {
    register_setting( 'tavernenfest_contact_settings', TAVERNENFEST_CONTACT_OPTIONS, array(
        'sanitize_callback' => 'tavernenfest_contact_sanitize_options',
    ) );
}
add_action( 'admin_init', 'tavernenfest_contact_register_settings' );

function tavernenfest_contact_menu() {
    add_menu_page( 'Kontaktblock', 'Kontaktblock', 'manage_options', 'tavernenfest-kontaktblock', 'tavernenfest_contact_admin_page', 'dashicons-email-alt2', 31 );
}
add_action( 'admin_menu', 'tavernenfest_contact_menu' );

function tavernenfest_contact_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $options = tavernenfest_contact_options();
    ?>
    <div class="wrap">
        <h1>Kontaktblock</h1>
        <p>Verwalte hier Kontaktformular, Veranstalteranschrift und Tischreservierung.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'tavernenfest_contact_settings' ); ?>
            <h2>Bereichstexte</h2>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_contact_eyebrow">Kleine Überschrift</label></th><td><input class="regular-text" id="tavernenfest_contact_eyebrow" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[eyebrow]" value="<?php echo esc_attr( $options['eyebrow'] ); ?>"><p class="description">Wird aktuell im Frontend nicht angezeigt.</p></td></tr>
                <tr><th><label for="tavernenfest_contact_title">Hauptüberschrift</label></th><td><input class="large-text" id="tavernenfest_contact_title" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[title]" value="<?php echo esc_attr( $options['title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_intro">Beschreibung</label></th><td><textarea class="large-text" rows="3" id="tavernenfest_contact_intro" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[intro]"><?php echo esc_textarea( $options['intro'] ); ?></textarea></td></tr>
            </table>
            <h2>Formular</h2>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_contact_recipient_email">Empfänger-E-Mail</label></th><td><input class="regular-text" type="email" id="tavernenfest_contact_recipient_email" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[recipient_email]" value="<?php echo esc_attr( $options['recipient_email'] ); ?>"><p class="description">An diese Adresse werden Kontaktanfragen gesendet.</p></td></tr>
                <tr><th><label for="tavernenfest_contact_name_label">Label Name</label></th><td><input class="regular-text" id="tavernenfest_contact_name_label" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[name_label]" value="<?php echo esc_attr( $options['name_label'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_required_label">Text Pflichtfeld</label></th><td><input class="regular-text" id="tavernenfest_contact_required_label" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[required_label]" value="<?php echo esc_attr( $options['required_label'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_phone_label">Label Telefon</label></th><td><input class="regular-text" id="tavernenfest_contact_phone_label" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[phone_label]" value="<?php echo esc_attr( $options['phone_label'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_message_label">Label Nachricht</label></th><td><input class="regular-text" id="tavernenfest_contact_message_label" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[message_label]" value="<?php echo esc_attr( $options['message_label'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_submit_label">Buttontext</label></th><td><input class="regular-text" id="tavernenfest_contact_submit_label" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[submit_label]" value="<?php echo esc_attr( $options['submit_label'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_sending_label">Buttontext beim Senden</label></th><td><input class="regular-text" id="tavernenfest_contact_sending_label" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[sending_label]" value="<?php echo esc_attr( $options['sending_label'] ); ?>"></td></tr>
            </table>
            <h2>Meldungen</h2>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_contact_success_message">Erfolgsmeldung</label></th><td><input class="large-text" id="tavernenfest_contact_success_message" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[success_message]" value="<?php echo esc_attr( $options['success_message'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_validation_message">Allgemeine Fehlermeldung</label></th><td><input class="large-text" id="tavernenfest_contact_validation_message" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[validation_message]" value="<?php echo esc_attr( $options['validation_message'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_name_error">Fehler Name</label></th><td><input class="large-text" id="tavernenfest_contact_name_error" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[name_error]" value="<?php echo esc_attr( $options['name_error'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_phone_error">Fehler Telefon</label></th><td><input class="large-text" id="tavernenfest_contact_phone_error" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[phone_error]" value="<?php echo esc_attr( $options['phone_error'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_message_error">Fehler Nachricht</label></th><td><input class="large-text" id="tavernenfest_contact_message_error" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[message_error]" value="<?php echo esc_attr( $options['message_error'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_link_error">Fehler Links</label></th><td><input class="large-text" id="tavernenfest_contact_link_error" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[link_error]" value="<?php echo esc_attr( $options['link_error'] ); ?>"></td></tr>
            </table>
            <h2>Rechte Spalte</h2>
            <table class="form-table" role="presentation">
                <tr><th><label for="tavernenfest_contact_organizer_title">Titel Anschrift</label></th><td><input class="regular-text" id="tavernenfest_contact_organizer_title" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[organizer_title]" value="<?php echo esc_attr( $options['organizer_title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_organizer_name">Veranstalter</label></th><td><input class="regular-text" id="tavernenfest_contact_organizer_name" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[organizer_name]" value="<?php echo esc_attr( $options['organizer_name'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_organizer_address">Anschrift</label></th><td><textarea class="large-text" rows="4" id="tavernenfest_contact_organizer_address" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[organizer_address]"><?php echo esc_textarea( $options['organizer_address'] ); ?></textarea></td></tr>
                <tr><th><label for="tavernenfest_contact_reservation_title">Titel Reservierung</label></th><td><input class="regular-text" id="tavernenfest_contact_reservation_title" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[reservation_title]" value="<?php echo esc_attr( $options['reservation_title'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_reservation_name">Name Reservierung</label></th><td><input class="regular-text" id="tavernenfest_contact_reservation_name" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[reservation_name]" value="<?php echo esc_attr( $options['reservation_name'] ); ?>"></td></tr>
                <tr><th><label for="tavernenfest_contact_reservation_phone">Telefon Reservierung</label></th><td><input class="regular-text" id="tavernenfest_contact_reservation_phone" name="<?php echo esc_attr( TAVERNENFEST_CONTACT_OPTIONS ); ?>[reservation_phone]" value="<?php echo esc_attr( $options['reservation_phone'] ); ?>"></td></tr>
            </table>
            <?php submit_button( 'Kontaktblock speichern' ); ?>
        </form>
    </div>
    <?php
}

function tavernenfest_contact_front_assets() {
    wp_enqueue_style( 'tavernenfest-contact-front', plugins_url( 'assets/contact-front.css', __FILE__ ), array(), '1.0.2' );
    wp_enqueue_script( 'tavernenfest-contact-front', plugins_url( 'assets/contact-front.js', __FILE__ ), array(), '1.0.1', true );
    wp_localize_script( 'tavernenfest-contact-front', 'tavernenfestContact', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'tavernenfest_contact_submit' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'tavernenfest_contact_front_assets' );

function tavernenfest_contact_render() {
    $options = tavernenfest_contact_options();
    $started = time();
    ob_start();
    ?>
    <section class="section contact-section" id="kontakt" aria-labelledby="kontakt-title">
        <div class="section-inner contact-inner">
            <div class="contact-copy">
                <h2 id="kontakt-title"><?php echo esc_html( $options['title'] ); ?></h2>
                <p><?php echo esc_html( $options['intro'] ); ?></p>
            </div>
            <div class="contact-layout">
                <form class="contact-form" data-contact-form data-submit-label="<?php echo esc_attr( $options['submit_label'] ); ?>" data-sending-label="<?php echo esc_attr( $options['sending_label'] ); ?>" novalidate>
                    <input type="hidden" name="action" value="tavernenfest_contact_submit">
                    <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'tavernenfest_contact_submit' ) ); ?>">
                    <input type="hidden" name="started" value="<?php echo esc_attr( $started ); ?>">
                    <p class="contact-field contact-field-hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>
                    <div class="contact-field">
                        <label for="contact-name"><?php echo esc_html( $options['name_label'] ); ?> <span><?php echo esc_html( $options['required_label'] ); ?></span></label>
                        <input id="contact-name" name="name" type="text" autocomplete="name" required>
                    </div>
                    <div class="contact-field">
                        <label for="contact-phone"><?php echo esc_html( $options['phone_label'] ); ?></label>
                        <input id="contact-phone" name="phone" type="tel" autocomplete="tel">
                    </div>
                    <div class="contact-field">
                        <label for="contact-message"><?php echo esc_html( $options['message_label'] ); ?></label>
                        <textarea id="contact-message" name="message" rows="6" required></textarea>
                    </div>
                    <button class="contact-submit" type="submit"><?php echo esc_html( $options['submit_label'] ); ?></button>
                    <p class="contact-status" data-contact-status aria-live="polite"></p>
                </form>
                <aside class="contact-details" aria-label="Kontaktinformationen">
                    <div>
                        <h3><?php echo esc_html( $options['organizer_title'] ); ?></h3>
                        <p><strong><?php echo esc_html( $options['organizer_name'] ); ?></strong><br><?php echo nl2br( esc_html( $options['organizer_address'] ) ); ?></p>
                    </div>
                    <div>
                        <h3><?php echo esc_html( $options['reservation_title'] ); ?></h3>
                        <p><strong><?php echo esc_html( $options['reservation_name'] ); ?></strong><?php if ( $options['reservation_phone'] ) : ?><br><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $options['reservation_phone'] ) ); ?>"><?php echo esc_html( $options['reservation_phone'] ); ?></a><?php endif; ?></p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'tavernenfest_kontaktblock', 'tavernenfest_contact_render' );

function tavernenfest_contact_spam_fail( $message = 'Die Nachricht konnte nicht gesendet werden.' ) {
    wp_send_json_error( array( 'message' => $message ), 400 );
}

function tavernenfest_contact_text_length( $value ) {
    return function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );
}

function tavernenfest_contact_submit() {
    $options = tavernenfest_contact_options();
    $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );
    if ( ! wp_verify_nonce( $nonce, 'tavernenfest_contact_submit' ) ) {
        tavernenfest_contact_spam_fail( 'Bitte lade die Seite neu und versuche es noch einmal.' );
    }

    $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? 'unknown' ) );
    $rate_key = 'tavernenfest_contact_rate_' . md5( $ip );
    if ( get_transient( $rate_key ) ) {
        tavernenfest_contact_spam_fail( 'Bitte warte kurz, bevor du noch eine Nachricht sendest.' );
    }

    $started = absint( $_POST['started'] ?? 0 );
    if ( ! $started || time() - $started < 4 ) {
        tavernenfest_contact_spam_fail();
    }

    if ( ! empty( $_POST['website'] ) ) {
        tavernenfest_contact_spam_fail();
    }

    $name = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

    $errors = array();
    if ( '' === $name || tavernenfest_contact_text_length( $name ) < 2 ) {
        $errors['name'] = $options['name_error'];
    }
    if ( $phone && ! preg_match( '/^[0-9+()\/\s.-]{5,30}$/', $phone ) ) {
        $errors['phone'] = $options['phone_error'];
    }
    if ( '' === $message || tavernenfest_contact_text_length( $message ) < 10 ) {
        $errors['message'] = $options['message_error'];
    }
    if ( preg_match_all( '~https?://|www\.~i', $message, $matches ) > 1 ) {
        $errors['message'] = $options['link_error'];
    }
    if ( $errors ) {
        wp_send_json_error( array( 'message' => $options['validation_message'], 'errors' => $errors ), 422 );
    }

    $recipient = is_email( $options['recipient_email'] ) ? $options['recipient_email'] : get_option( 'admin_email' );
    $subject = 'Neue Nachricht vom Tavernenfest-Kontaktformular';
    $body = "Name: {$name}\nTelefon/Mobil: {$phone}\n\nNachricht:\n{$message}";
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );

    set_transient( $rate_key, 1, 5 * MINUTE_IN_SECONDS );
    if ( ! wp_mail( $recipient, $subject, $body, $headers ) ) {
        wp_send_json_error( array( 'message' => 'Die Nachricht konnte gerade nicht gesendet werden. Bitte versuche es später erneut.' ), 500 );
    }

    wp_send_json_success( array( 'message' => $options['success_message'] ) );
}
add_action( 'wp_ajax_tavernenfest_contact_submit', 'tavernenfest_contact_submit' );
add_action( 'wp_ajax_nopriv_tavernenfest_contact_submit', 'tavernenfest_contact_submit' );
