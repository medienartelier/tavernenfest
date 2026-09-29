<footer class="site-footer">
    <div class="footer-inner">
        <span>&copy; Tavernenfest Preying <?php echo esc_html( wp_date( 'Y' ) ); ?></span>
        <nav class="footer-links" aria-label="Rechtliches">
            <a href="<?php echo esc_url( tavernenfest_page_url( 'impressum' ) ); ?>">Impressum</a>
            <a href="<?php echo esc_url( tavernenfest_page_url( 'datenschutz' ) ); ?>">Datenschutz</a>
        </nav>
    </div>
    <img class="footer-brewery-logo" src="<?php echo esc_url( get_theme_file_uri( '/assets/images/tavernenfest-brauerei.svg' ) ); ?>" alt="Brauerei">
</footer>
<div class="site-socket">
    <span>Made with <span class="socket-heart" aria-label="love"></span> from medienaRtelier</span>
</div>
<a class="back-to-top" href="#top" aria-label="Zum Anfang der Seite" data-back-to-top>
    <span aria-hidden="true"></span>
</a>
<?php wp_footer(); ?>
</body>
</html>
