<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header" data-site-header>
    <div class="nav-wrap">
        <?php $sticky_logo = get_theme_mod( 'tavernenfest_sticky_logo' ); ?>
        <?php if ( $sticky_logo ) : ?>
            <a class="sticky-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                <img src="<?php echo esc_url( $sticky_logo ); ?>" alt="">
            </a>
        <?php endif; ?>
        <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
        <button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false" data-menu-toggle>
            <span class="menu-toggle-line"></span>
            <span class="menu-toggle-line"></span>
            <span class="menu-toggle-line"></span>
            <span class="screen-reader-text">Menü öffnen</span>
        </button>
        <?php
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'container' => 'nav',
            'container_class' => 'main-nav',
            'container_aria_label' => 'Hauptnavigation',
            'fallback_cb' => 'tavernenfest_menu_fallback',
            'menu_class' => 'menu',
            'menu_id' => 'primary-menu',
            'depth' => 2,
        ) );
        ?>
    </div>
</header>
