<?php
if ( !have_posts() ) {
    get_template_part('404');
    return;
}

get_header();

the_post();
?>

<article <?php post_class('loop-single'); ?>>

    <header class="loop-header">
        <?php the_title( '<h1 class="loop-title">', '</h1>' ); ?>
    </header>

    <div class="loop-body">

        <?php if ( has_post_thumbnail() ) { ?>
            <div class="loop-image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( ); ?></a>
            </div>
        <?php } ?>

        <div class="loop-content">
            <?php the_content(); ?>
        </div>

    </div>

</article>

<?php get_sidebar(); ?>

<?php
get_footer();