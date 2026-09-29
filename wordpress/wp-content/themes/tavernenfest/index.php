<?php get_header(); ?>
<main class="section"><div class="section-inner">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); the_title( '<h1>', '</h1>' ); the_content(); endwhile; endif; ?>
</div></main>
<?php get_footer(); ?>
