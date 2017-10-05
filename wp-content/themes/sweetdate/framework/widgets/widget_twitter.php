<?php

 class SQueen_Twitter_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __('Add a customized twitter widget to your site.','kleo_framework') );
		parent::__construct( 'squeen_twitter', __('[Kleo] Twitter Widget','kleo_framework'), $widget_ops );
                
                
                //enque twitter script
                add_action( 'wp_enqueue_scripts', array( &$this , 'twitter_javascript') );
	}

        function twitter_javascript()
        {
            wp_enqueue_script('jquery-tweet');
        }
        
        
	function widget($args, $instance) {

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] .'<i class="icon-twitter"></i> '. $instance['title'] . $args['after_title'];

                echo "
                    <script type='text/javascript'>
                   jQuery(document).ready(function() {
                        if (jQuery('#tweet').length) {
                             jQuery('#tweet').tweet({
                                username: '".(!empty($instance['twitter_username'])?$instance['twitter_username']:"SeventhQueen")."',\n
                                join_text: 'auto',\n
                                //avatar_size: 32,\n
                                count:".(!empty($instance['twitter_count'])?$instance['twitter_count']:3).",\n
                                auto_join_text_default: ' ".__("we said", 'kleo_framework').", ',\n
                                auto_join_text_ed: ' ".__("we", 'kleo_framework')." ',\n
                                auto_join_text_ing: ' ".__("we were", 'kleo_framework')." ',\n
                                auto_join_text_reply: ' ".__("we replied to", 'kleo_framework')." ',\n
                                auto_join_text_url: ' ".__("we were checking out", 'kleo_framework')." ',\n
                                loading_text: '".__("Loading twitts...",'kleo_framework')." '\n
                         });};
                     });
                     </script>";
		echo	'<div id="twitter_wrap">';
		echo	'<div id="tweet"> </div>';
		echo	'</div><!-- end twitter_wrap -->';
		
		
		echo $args['after_widget'];
		
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['twitter_username'] =  stripslashes($new_instance['twitter_username']);
                $instance['twitter_count'] =  stripslashes($new_instance['twitter_count']);
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$twitter_username = isset( $instance['twitter_username'] ) ? $instance['twitter_username'] : '';
                $twitter_count = isset( $instance['twitter_count'] ) ? $instance['twitter_count'] : 3;

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','kleo_framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter_username'); ?>"><?php _e('Twitter Username:','kleo_framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('twitter_username'); ?>" name="<?php echo $this->get_field_name('twitter_username'); ?>" value="<?php echo $twitter_username; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('twitter_count'); ?>"><?php _e('How many twitts:','kleo_framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('twitter_count'); ?>" name="<?php echo $this->get_field_name('twitter_count'); ?>" value="<?php echo $twitter_count; ?>" />
		</p>
                
		<?php
	}
}


add_action( 'widgets_init', create_function( '', 'register_widget( "SQueen_Twitter_Widget" );' ) );
?>
