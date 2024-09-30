<?php
/**
 * 
 * Theme function
 */
function Redq_enqueue_scripts(){
    wp_register_style('style-css', get_stylesheet_uri(), [], filemtime( get_template_directory() . '/style.css' ), 'all');
    wp_register_script('hello-js', get_template_directory_uri() . '/js/hello.js', [], filemtime( get_template_directory() . '/js/hello.js' ), true);

    wp_enqueue_style('style-css');
   wp_enqueue_script('hello-js');



}
add_action( 'wp_enqueue_scripts', 'Redq_enqueue_scripts' );
// Register RedQ Menu
function register_redq_menu() {
    register_nav_menu('redq-menu', __('RedQ Menu'));
}
add_action('init', 'register_redq_menu');

// Creating the RedQ Widget
class RedQ_Widget extends WP_Widget {

    // Constructor
    function __construct() {
        parent::__construct(
            'redq_widget', // Base ID
            __('RedQ Widget', 'text_domain'), // Name
            array( 'description' => __( 'A Custom RedQ Widget', 'text_domain' ), ) // Arguments
        );
    }

    // The widget output (front-end)
    public function widget( $args, $instance ) {
        echo $args['before_widget']; // Output before the widget (like <div> or <li>)

        // Get widget settings from admin
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        if ( ! empty( $instance['description'] ) ) {
            echo '<p>' . $instance['description'] . '</p>';
        }

        // Custom widget content can be added here
        echo '<p>This is your custom RedQ Widget content!</p>';
        
        echo $args['after_widget']; // Output after the widget (like closing </div> or </li>)
    }

    // Widget backend (admin form)
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : __( 'Enter description', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php _e( 'Description:' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr( $description ); ?></textarea>
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';

        return $instance;
    }
}

// Register the RedQ Widget
function register_redq_widget() {
    register_widget( 'RedQ_Widget' );
}
add_action( 'widgets_init', 'register_redq_widget' );
// Register a Sidebar (Widget Area)
function redq_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'RedQ Sidebar', 'text_domain' ),
        'id'            => 'redq-sidebar',
        'before_widget' => '<div class="redq-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'redq_widgets_init' );

function redq_customizer_settings($wp_customize) {
    // Add a new panel
    $wp_customize->add_panel('redq_options_panel', array(
        'title'       => __('RedQ Options', 'text_domain'),
        'description' => __('Customize RedQ settings', 'text_domain'),
        'priority'    => 160, // Set the priority (lower numbers appear first)
    ));

    // Add a new section inside the panel
    $wp_customize->add_section('redq_general_section', array(
        'title'    => __('General Settings', 'text_domain'),
        'priority' => 1,
        'panel'    => 'redq_options_panel', // Assign to RedQ Options panel
    ));

    // Add a text control
    $wp_customize->add_setting('redq_text_setting', array(
        'default'   => __('Default text', 'text_domain'),
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('redq_text_control', array(
        'label'    => __('Custom Text', 'text_domain'),
        'section'  => 'redq_general_section',
        'settings' => 'redq_text_setting',
        'type'     => 'text',
    ));

    // Add a color picker control
    $wp_customize->add_setting('redq_color_setting', array(
        'default'   => '#ff0000',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'redq_color_control', array(
        'label'    => __('Accent Color', 'text_domain'),
        'section'  => 'redq_general_section',
        'settings' => 'redq_color_setting',
    )));

    // Add an image upload control
    $wp_customize->add_setting('redq_image_setting', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'redq_image_control', array(
        'label'    => __('Upload Logo', 'text_domain'),
        'section'  => 'redq_general_section',
        'settings' => 'redq_image_setting',
    )));
}
add_action('customize_register', 'redq_customizer_settings');

function redq_envato_custom_post_type() {

    $labels = array(
        'name'               => _x( 'Envato', 'post type general name', 'text_domain' ),
        'singular_name'      => _x( 'Envato', 'post type singular name', 'text_domain' ),
        'menu_name'          => _x( 'Envato', 'admin menu', 'text_domain' ),
        'name_admin_bar'     => _x( 'Envato', 'add new on admin bar', 'text_domain' ),
        'add_new'            => _x( 'Add New', 'envato', 'text_domain' ),
        'add_new_item'       => __( 'Add New Envato', 'text_domain' ),
        'new_item'           => __( 'New Envato', 'text_domain' ),
        'edit_item'          => __( 'Edit Envato', 'text_domain' ),
        'view_item'          => __( 'View Envato', 'text_domain' ),
        'all_items'          => __( 'All Envato', 'text_domain' ),
        'search_items'       => __( 'Search Envato', 'text_domain' ),
        'parent_item_colon'  => __( 'Parent Envato:', 'text_domain' ),
        'not_found'          => __( 'No Envato found.', 'text_domain' ),
        'not_found_in_trash' => __( 'No Envato found in Trash.', 'text_domain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'envato' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ), // Editor, Excerpt, and Featured Image
    );

    register_post_type( 'envato', $args );
}
add_action( 'init', 'redq_envato_custom_post_type' );

function redq_register_envato_taxonomies() {

    // Register Themeforest Taxonomy
    $labels_themeforest = array(
        'name'              => _x( 'Themeforest', 'taxonomy general name', 'text_domain' ),
        'singular_name'     => _x( 'Themeforest', 'taxonomy singular name', 'text_domain' ),
        'search_items'      => __( 'Search Themeforest', 'text_domain' ),
        'all_items'         => __( 'All Themeforest', 'text_domain' ),
        'parent_item'       => __( 'Parent Themeforest', 'text_domain' ),
        'parent_item_colon' => __( 'Parent Themeforest:', 'text_domain' ),
        'edit_item'         => __( 'Edit Themeforest', 'text_domain' ),
        'update_item'       => __( 'Update Themeforest', 'text_domain' ),
        'add_new_item'      => __( 'Add New Themeforest', 'text_domain' ),
        'new_item_name'     => __( 'New Themeforest Name', 'text_domain' ),
        'menu_name'         => __( 'Themeforest', 'text_domain' ),
    );

    $args_themeforest = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels_themeforest,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'themeforest' ),
    );

    register_taxonomy( 'themeforest', array( 'envato' ), $args_themeforest );


    // Register Codecanyon Taxonomy
    $labels_codecanyon = array(
        'name'              => _x( 'Codecanyon', 'taxonomy general name', 'text_domain' ),
        'singular_name'     => _x( 'Codecanyon', 'taxonomy singular name', 'text_domain' ),
        'search_items'      => __( 'Search Codecanyon', 'text_domain' ),
        'all_items'         => __( 'All Codecanyon', 'text_domain' ),
        'parent_item'       => __( 'Parent Codecanyon', 'text_domain' ),
        'parent_item_colon' => __( 'Parent Codecanyon:', 'text_domain' ),
        'edit_item'         => __( 'Edit Codecanyon', 'text_domain' ),
        'update_item'       => __( 'Update Codecanyon', 'text_domain' ),
        'add_new_item'      => __( 'Add New Codecanyon', 'text_domain' ),
        'new_item_name'     => __( 'New Codecanyon Name', 'text_domain' ),
        'menu_name'         => __( 'Codecanyon', 'text_domain' ),
    );

    $args_codecanyon = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels_codecanyon,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'codecanyon' ),
    );

    register_taxonomy( 'codecanyon', array( 'envato' ), $args_codecanyon );
}
add_action( 'init', 'redq_register_envato_taxonomies' );


function redq_add_custom_meta_box() {
    add_meta_box(
        'redq_analytics_meta_box',         // Unique ID for the meta box
        'RedQ Analytics',                  // Box title
        'redq_analytics_meta_box_callback',// Callback to display the form fields
        'envato',                          // Post type where it appears (change 'envato' to any other post type if needed)
        'normal',                          // Location (normal or side)
        'high'                             // Priority
    );
}
add_action( 'add_meta_boxes', 'redq_add_custom_meta_box' );

function redq_analytics_meta_box_callback( $post ) {
    // Nonce field for security
    wp_nonce_field( 'redq_analytics_nonce_action', 'redq_analytics_nonce' );

    // Retrieve the current values for both fields, if available
    $sales_count = get_post_meta( $post->ID, '_redq_sales_count', true );
    $featured = get_post_meta( $post->ID, '_redq_featured', true );

    ?>
    <p>
        <label for="redq_sales_count"><?php _e( 'Sales Count:', 'text_domain' ); ?></label>
        <input type="text" id="redq_sales_count" name="redq_sales_count" value="<?php echo esc_attr( $sales_count ); ?>" size="25" />
    </p>
    <p>
        <label for="redq_featured"><?php _e( 'Featured:', 'text_domain' ); ?></label>
        <select name="redq_featured" id="redq_featured">
            <option value="no" <?php selected( $featured, 'no' ); ?>><?php _e( 'No', 'text_domain' ); ?></option>
            <option value="yes" <?php selected( $featured, 'yes' ); ?>><?php _e( 'Yes', 'text_domain' ); ?></option>
        </select>
    </p>
    <?php
}

function redq_save_meta_box_data( $post_id ) {

    // Check if nonce is set
    if ( ! isset( $_POST['redq_analytics_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid
    if ( ! wp_verify_nonce( $_POST['redq_analytics_nonce'], 'redq_analytics_nonce_action' ) ) {
        return;
    }

    // Check if the user has permission to edit the post
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Make sure the fields are set
    if ( isset( $_POST['redq_sales_count'] ) ) {
        $sales_count = sanitize_text_field( $_POST['redq_sales_count'] );
        update_post_meta( $post_id, '_redq_sales_count', $sales_count );
    }

    if ( isset( $_POST['redq_featured'] ) ) {
        $featured = sanitize_text_field( $_POST['redq_featured'] );
        update_post_meta( $post_id, '_redq_featured', $featured );
    }
}
add_action( 'save_post', 'redq_save_meta_box_data' );

function redq_enqueue_contact_scripts() {
    // Enqueue jQuery (if not already enqueued)
    wp_enqueue_script( 'jquery' );

    // Enqueue custom AJAX script
    wp_enqueue_script( 'redq-contact-ajax', get_template_directory_uri() . '/js/redq-contact.js', array('jquery'), null, true );

    // Localize script to pass AJAX URL and nonce
    wp_localize_script( 'redq-contact-ajax', 'redq_contact_params', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'redq_contact_nonce' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'redq_enqueue_contact_scripts' );

function redq_handle_contact_form() {
    // Verify the nonce for security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'redq_contact_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce.' );
    }

    // Sanitize form data
    $name  = sanitize_text_field( $_POST['name'] );
    $email = sanitize_email( $_POST['email'] );

    // Here you can do additional actions like saving to the database or sending an email

    // Respond with success
    wp_send_json_success( 'Form submitted successfully.' );
}

// Add AJAX action for logged-in users
add_action( 'wp_ajax_redq_contact_form', 'redq_handle_contact_form' );

// Add AJAX action for non-logged-in users
add_action( 'wp_ajax_nopriv_redq_contact_form', 'redq_handle_contact_form' );


?>