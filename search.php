<?php get_header(); ?>

<main id="primary" class="site-main">

    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php printf( esc_html__( 'Search Results for: %s', 'text_domain' ), '<span>' . get_search_query() . '</span>' ); ?>
            </h1>
        </header><!-- .page-header -->

        <?php
        // Start the loop
        while ( have_posts() ) :
            the_post();

            // Include template for displaying search results content
            get_template_part( 'template-parts/content', 'search' );

        endwhile;

        // Display pagination
        the_posts_navigation();

    else :

        get_template_part( 'template-parts/content', 'none' ); // Display message when no results found

    endif;
    ?>

</main><!-- #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
