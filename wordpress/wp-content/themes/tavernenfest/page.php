<?php get_header(); ?>
<main>
    <section class="section content-section">
        <div class="section-inner content-inner">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <h1><?php the_title(); ?></h1>
                <div class="content-body"><?php the_content(); ?></div>
            <?php endwhile; endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
