<?php
/* Template Name: RedQ Page */

get_header();

// Global wpdb object
global $wpdb;

// Custom query to fetch all posts from the 'envato' post type using wpdb
$envato_posts = $wpdb->get_results("
    SELECT ID, post_title
    FROM $wpdb->posts
    WHERE post_type = 'envato'
    AND post_status = 'publish'
");

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        if ( !empty( $envato_posts ) ) :
            foreach ( $envato_posts as $post ) :
                // Get post meta values
                $sales_count = get_post_meta( $post->ID, '_redq_sales_count', true );
                $featured = get_post_meta( $post->ID, '_redq_featured', true );

                // Get Themeforest and Codecanyon taxonomy terms
                $themeforest_terms = wp_get_post_terms( $post->ID, 'themeforest' );
                $codecanyon_terms = wp_get_post_terms( $post->ID, 'codecanyon' );
                ?>

                <article id="post-<?php echo esc_attr( $post->ID ); ?>" class="post">
                    <header class="entry-header">
                        <h2 class="entry-title"><?php echo esc_html( $post->post_title ); ?></h2>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <p><strong>Sales Count:</strong> <?php echo esc_html( $sales_count ); ?></p>

                        <p><strong>Featured:</strong> <?php echo esc_html( ucfirst( $featured ) ); ?></p>

                        <?php if ( !empty( $themeforest_terms ) ) : ?>
                            <p><strong>Themeforest:</strong> 
                            <?php
                            foreach ( $themeforest_terms as $term ) {
                                echo esc_html( $term->name ) . ' ';
                            }
                            ?>
                            </p>
                        <?php endif; ?>

                        <?php if ( !empty( $codecanyon_terms ) ) : ?>
                            <p><strong>Codecanyon:</strong> 
                            <?php
                            foreach ( $codecanyon_terms as $term ) {
                                echo esc_html( $term->name ) . ' ';
                            }
                            ?>
                            </p>
                        <?php endif; ?>

                        <?php
                        // Show the post's featured image if it exists
                        if ( has_post_thumbnail( $post->ID ) ) {
                            echo get_the_post_thumbnail( $post->ID, 'full' );
                        }
                        ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-## -->

            <?php endforeach;

        else :
            echo '<p>No Envato posts found.</p>';
        endif;
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
