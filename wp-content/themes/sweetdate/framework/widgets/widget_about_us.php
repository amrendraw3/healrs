<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class SQueen_About_Us_widget extends WP_Widget {

	/**
	 * Widget setup
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Text and contact information.', 'kleo_framework' ) 
		);
		parent::__construct( 'kleo_about_us', __('[Kleo] About us','kleo_framework'), $widget_ops );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', $instance['title'] );

		$textarea = $instance['textarea'];
        $social = $instance['social'];
        $contact = $instance['contact'];

		echo $before_widget;
 
		if ( ! empty( $title ) )
			echo $before_title . '<i class="icon-'.apply_filters('kleo_widget_aboutus_icon', 'heart').'"></i> ' .$title . $after_title;
   ?>

		<?php if ($textarea) : ?>	
			<p><?php echo $textarea; ?></p>
			<?php endif; ?>

			<?php if($contact == true && (sq_option('owner_email') || sq_option('owner_phone'))) :?>
			<p>
				<?php if (sq_option('owner_email')): ?>
				<i class="icon-envelope"></i> &nbsp;<a href="mailto:<?php echo sq_option('owner_email');?>"><?php echo sq_option('owner_email');?></a><br>&nbsp;
				<?php endif; ?>
				<?php if (sq_option('owner_phone')): ?>
				<i class="icon-mobile-phone icon-large"></i> &nbsp;&nbsp;<a href="#"><?php echo sq_option('owner_phone');?></a>
				<?php endif; ?>
			</p>
			<?php endif; ?>

			<?php if($social == true) :?>
			<p class="footer-social-icons"><?php _e("Stay in touch", 'kleo_framework'); ?>:<br>
				<?php if (sq_option('twitter')): ?>
				<a href="<?php echo sq_option('twitter');?>" class="has-tip tip-bottom" data-width="210" title="<?php _e("Follow us on", "kleo_framework");?> Twitter"><i class="icon-twitter-sign icon-2x"></i></a>
				<?php endif;?>
				<?php if (sq_option('facebook')): ?>
				<a href="<?php echo sq_option('facebook');?>" class="has-tip tip-bottom" data-width="210" title="<?php _e("Find us on", "kleo_framework");?> Facebook"><i class="icon-facebook-sign icon-2x"></i></a>
				<?php endif;?>
				<?php if (sq_option('googleplus')): ?>
				<a href="<?php echo sq_option('googleplus');?>" class="has-tip tip-bottom" data-width="210" title="<?php _e("Find us on", "kleo_framework");?> Google+"><i class="icon-google-plus-sign icon-2x"></i></a>
				<?php endif; ?>
				<?php if (sq_option('pinterest')): ?>
				<a href="<?php echo sq_option('pinterest');?>" class="has-tip tip-bottom" data-width="210" title="<?php _e("Pin us on", "kleo_framework");?> Pinterest"><i class="icon-pinterest-sign icon-2x"></i></a>
				<?php endif; ?>
				<?php if (sq_option('linkedin')): ?>
				<a href="<?php echo sq_option('linkedin');?>" class="has-tip tip-bottom" data-width="210" title="<?php _e("Find us on", "kleo_framework");?> LinkedIn"><i class="icon-linkedin-sign icon-2x"></i></a>
				<?php endif; ?>

				<?php do_action('kleo_extra_social_icons'); ?>
			</p>
			<?php endif; ?>
   
		<?php

		echo $after_widget;
		
	}

	/**
	 * Update widget
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
        $instance['title'] = esc_attr( $new_instance['title'] );
		$instance['textarea'] = $new_instance['textarea'];
		$instance['social'] = $new_instance['social'];
		$instance['contact'] = $new_instance['contact'];
		return $instance;

	}

	/**
	 * Widget setting
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => '',
			'textarea' => '',
			'social' => true,
			'contact' => true,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = esc_attr( $instance['title'] );
		if( defined( 'ICL_SITEPRESS_VERSION') && function_exists('icl_translate') ) {
			$textarea = icl_translate( 'kleo_framework', 'about_us_widget_text', $instance['textarea'] );
		} else {
			$textarea = $instance['textarea'];
		}
		$social = $instance['social'];
		$contact = $instance['contact'];
	?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'kleo_framework' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<textarea class='widefat' name="<?php echo $this->get_field_name( 'textarea' ); ?>"><?php echo $textarea; ?></textarea>
		</p>
                
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'contact' ) ); ?>"><?php _e( 'Display Email/Phone?', 'kleo_framework' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'contact' ); ?>" name="<?php echo $this->get_field_name( 'contact' ); ?>" type="checkbox" value="1" <?php checked( '1', $contact ); ?> />&nbsp;
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'social' ) ); ?>"><?php _e( 'Display Social icons?', 'kleo_framework' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'social' ); ?>" name="<?php echo $this->get_field_name( 'social' ); ?>" type="checkbox" value="1" <?php checked( '1', $social ); ?> />&nbsp;
		</p>

	<?php
	}

}

/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "SQueen_About_Us_widget" );' ) );
