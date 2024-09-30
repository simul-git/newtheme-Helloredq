<?php
/* Template Name: RedQ Full Width */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        // WP_Query to fetch posts from the 'envato' custom post type
        $args = array(
            'post_type' => 'envato',
            'posts_per_page' => 5, // Get 5 posts
        );

        $envato_query = new WP_Query( $args );

        // The Loop
        if ( $envato_query->have_posts() ) : 
            while ( $envato_query->have_posts() ) : $envato_query->the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h2 class="entry-title"><?php the_title(); ?></h2>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_excerpt(); ?>

                        <p><strong>Sales Count:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_redq_sales_count', true ) ); ?></p>

                        <p><strong>Featured:</strong> <?php echo esc_html( ucfirst( get_post_meta( get_the_ID(), '_redq_featured', true ) ) ); ?></p>

                        <?php
                        // Show terms for Themeforest taxonomy
                        $themeforest_terms = get_the_terms( get_the_ID(), 'themeforest' );
                        if ( $themeforest_terms && ! is_wp_error( $themeforest_terms ) ) : ?>
                            <p><strong>Themeforest:</strong>
                            <?php foreach ( $themeforest_terms as $term ) {
                                echo esc_html( $term->name ) . ' ';
                            } ?>
                            </p>
                        <?php endif; ?>

                        <?php
                        // Show terms for Codecanyon taxonomy
                        $codecanyon_terms = get_the_terms( get_the_ID(), 'codecanyon' );
                        if ( $codecanyon_terms && ! is_wp_error( $codecanyon_terms ) ) : ?>
                            <p><strong>Codecanyon:</strong>
                            <?php foreach ( $codecanyon_terms as $term ) {
                                echo esc_html( $term->name ) . ' ';
                            } ?>
                            </p>
                        <?php endif; ?>

                        <?php the_post_thumbnail(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-## -->

            <?php endwhile;

            wp_reset_postdata(); // Reset the query

        else :
            echo '<p>No Envato posts found.</p>';
        endif;
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
