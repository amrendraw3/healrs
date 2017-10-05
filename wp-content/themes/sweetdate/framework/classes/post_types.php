<?php

/*
 * Post types creation class
 * 
 */


class Post_types {
	
    private $labels;

    public function __construct() {
        $this->labels = array('testimonials');
        $this->labels['testimonials'] = array( 'singular' => __( 'Testimonial', 'kleo_framework' ), 'plural' => __( 'Testimonials', 'kleo_framework' ), 'menu' => __( 'Testimonials', 'kleo_framework' ) );
    
       add_action( 'init', array( &$this, 'setup_testimonials_post_type' ), 100 ); 
    }
    
    /**
     * Setup testimonials post type
     * @since  1.0
     * @return void
     */
    public function setup_testimonials_post_type () {

        $args = array(
            'labels' => $this->get_labels( 'testimonials', $this->labels['testimonials']['singular'], $this->labels['testimonials']['plural'], $this->labels['testimonials']['menu'] ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => TRUE,
            'query_var' => true,
            'rewrite' => array( 'slug' => esc_attr( apply_filters( 'kleo_testimonials_slug', 'testimonials' ) )),
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20, // Below "Pages"
            'supports' => array( 'title', 'editor' )
        );

        register_post_type( 'kleo-testimonials', $args );
    } // End setup_testimonials_post_type()

    
    /**
     * Create the labels to be used in post type creation
     * @since  1.0
     * @param  string $token    The post type for which to setup labels
     * @param  string $singular Label for singular post type
     * @param  string $plural   Label for plural post type
     * @param  string $menu     Menu item label
     * @return array            Labels array
     */
    private function get_labels ( $token, $singular, $plural, $menu ) {
        $labels = array(
            'name' => sprintf( _x( '%s', 'post type general name', 'kleo_framework' ), $plural ),
            'singular_name' => sprintf( _x( '%s', 'post type singular name', 'kleo_framework' ), $singular ),
            'add_new' => sprintf( _x( 'Add New %s', $token, 'kleo_framework' ), $singular ),
            'add_new_item' => sprintf( __( 'Add New %s', 'kleo_framework' ), $singular ),
            'edit_item' => sprintf( __( 'Edit %s', 'kleo_framework' ), $singular ),
            'new_item' => sprintf( __( 'New %s', 'kleo_framework' ), $singular ),
            'all_items' => sprintf( __( 'All %s', 'kleo_framework' ), $plural ),
            'view_item' => sprintf( __( 'View %s', 'kleo_framework' ), $singular ),
            'search_items' => sprintf( __( 'Search %s', 'kleo_framework' ), $plural ),
            'not_found' =>  sprintf( __( 'No %s found', 'kleo_framework' ), strtolower( $plural ) ),
            'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'kleo_framework' ), strtolower( $plural ) ),
            'parent_item_colon' => '',
            'menu_name' => sprintf( __( '%s', 'kleo_framework' ), $menu )
          );

        return $labels;
    } // End get_labels()

}


?>
