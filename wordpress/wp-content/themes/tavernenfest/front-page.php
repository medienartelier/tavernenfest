<?php
get_header();
$hero_person = get_theme_file_path( '/assets/images/tavernenfest-hero.png' );
$hero_heart = get_theme_file_path( '/assets/images/tavernenfest-heart.png' );
$hero_data = function_exists( 'tavernenfest_hero_render_data' ) ? tavernenfest_hero_render_data() : array(
    'eyebrow' => 'Ein Fest für alle Sinne',
    'title' => get_bloginfo( 'name' ),
    'copy' => 'Drei Tage voller Musik, Geschichten, guter Gesellschaft und dem Duft aus der Festküche.',
    'countdown_date' => get_theme_mod( 'tavernenfest_date', '2027-06-11T18:00' ),
    'logo_url' => '',
    'style' => '',
);
$program = new WP_Query( array( 'post_type' => 'programmpunkt', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
?>
<main>
    <section class="hero" style="<?php echo esc_attr( $hero_data['style'] ); ?>">
        <div class="hero-inner">
            <div class="hero-content">
                <p class="eyebrow"><?php echo esc_html( $hero_data['eyebrow'] ); ?></p>
                <?php if ( ! empty( $hero_data['logo_url'] ) ) : ?>
                    <div class="hero-logo"><img src="<?php echo esc_url( $hero_data['logo_url'] ); ?>" alt="<?php echo esc_attr( $hero_data['title'] ); ?>"></div>
                <?php else : ?>
                    <h1><?php echo esc_html( $hero_data['title'] ); ?></h1>
                <?php endif; ?>
                <p class="hero-copy"><?php echo esc_html( $hero_data['copy'] ); ?></p>
                <div class="countdown" data-countdown="<?php echo esc_attr( $hero_data['countdown_date'] ); ?>">
                    <div class="count-unit"><span class="count-number" data-unit="days">000</span><span class="count-label">Tage</span></div>
                    <span class="count-separator">:</span>
                    <div class="count-unit"><span class="count-number" data-unit="hours">00</span><span class="count-label">Stunden</span></div>
                    <span class="count-separator">:</span>
                    <div class="count-unit"><span class="count-number" data-unit="minutes">00</span><span class="count-label">Minuten</span></div>
                    <span class="count-separator">:</span>
                    <div class="count-unit"><span class="count-number" data-unit="seconds">00</span><span class="count-label">Sekunden</span></div>
                </div>
            </div>
        </div>
        <?php if ( file_exists( $hero_person ) ) : ?>
            <div class="hero-figure" data-parallax-figure aria-hidden="true">
                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/tavernenfest-hero.png' ) ); ?>" alt="">
            </div>
        <?php endif; ?>
        <?php if ( file_exists( $hero_heart ) ) : ?>
            <div class="hero-heart" data-parallax-heart aria-hidden="true">
                <img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/tavernenfest-heart.png' ) ); ?>" alt="">
            </div>
        <?php endif; ?>
    </section>
    <?php echo function_exists( 'tavernenfest_impressions_render' ) ? tavernenfest_impressions_render() : ''; ?>
    <div class="program-section program-block" id="programm" aria-labelledby="festtage-title">
        <div class="section-inner">
            <?php $program_copy = function_exists( 'tavernenfest_program_options' ) ? tavernenfest_program_options() : array( 'eyebrow' => 'Tage & Zeiten', 'title' => 'Festtage & Programm', 'intro' => 'Ein Abend, ein Lied, noch eine Runde.' ); ?>
            <div class="program-header"><div><span class="section-kicker"><?php echo esc_html( $program_copy['eyebrow'] ); ?></span><h2 id="festtage-title"><?php echo esc_html( $program_copy['title'] ); ?></h2></div><p><?php echo esc_html( $program_copy['intro'] ); ?></p></div>
            <?php echo function_exists( 'tavernenfest_program_render' ) ? tavernenfest_program_render() : '<p class="empty-state">Das Festprogramm wird gerade vorbereitet.</p>'; ?>
        </div>
    </div>
    <?php echo function_exists( 'tavernenfest_contact_render' ) ? tavernenfest_contact_render() : '<section class="section contact-section" id="kontakt" aria-labelledby="kontakt-title"><div class="section-inner contact-inner"><span class="section-kicker">Kontakt</span><h2 id="kontakt-title">Wir freuen uns auf deine Nachricht.</h2><p>Weitere Informationen zum Tavernenfest und zur Mitwirkung erhältst du direkt beim Veranstalter.</p></div></section>'; ?>
</main>
<?php get_footer(); ?>
