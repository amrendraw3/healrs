<?php
/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
 class SQueen_Mailchimp_Widget extends WP_Widget {
 
        var $api_key;
     
	function __construct() {
		$widget_ops = array( 'description' => __('Mailchimp newsletter subscribe form.','kleo_framework') );
		parent::__construct( 'kleo_mailchimp', __('[Kleo] Mailchimp Newsletter','kleo_framework'), $widget_ops );
		$this->api_key = sq_option('mailchimp_api');
	}
	
	function widget($args, $instance) {

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		//ajax fallback
		if ( isset ( $_POST['mc_email']) && $instance['mailchimp_list'] == $_POST['list']) {
			
			if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {
			
				include_once (FRAMEWORK_URL . '/widgets/mailchimp/MCAPI.class.php');
			
				$mcapi = new MCAPI($this->api_key);
				
				$merge_vars = array(
					"YNAME" => $_POST['yname']
				);
				
				$list_id = $instance['mailchimp_list'];
				$opt_in = sq_option('mailchimp_opt_in', 'yes') == 'yes' ? true : false;
				
				if($mcapi->listSubscribe($list_id, $_POST['mc_email'], $merge_vars, 'html', $opt_in) ) {
					// Everything ok 
					$msg = '<span style="color:green;">'.__('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.','kleo_framework').'</span>';
				}else{
					// An error ocurred, return error message   
					$msg = '<span style="color:red;"><b>'.__('Error:','kleo_framework').'</b>&nbsp; ' . $mcapi->errorMessage.'</span>';
					
				}
				
			}

		}
		
		echo $args['before_widget'];
		echo '<div class="panel">';
		if ( !empty($instance['title']) )
			echo $args['before_title'] .'<i class="icon-thumbs-up"></i> '. $instance['title'] . $args['after_title'];
                
                //Before text
		if ( ! empty( $instance['mailchimp_before_text'] ) ) :
			echo	'<p>'.$instance['mailchimp_before_text'].'</p>';
		endif;
                

		echo '
		  <!--Newsletter form-->
		  <form id="newsletter-form" name="newsletter-form" data-url="'.trailingslashit(home_url()).'" method="post" class="row newsletter-form">
			<input type="hidden" id="list" class="mc_list" name="list" value="'.$instance['mailchimp_list'].'" />
			<div class="'.(isset($args['id']) && (strpos($args['id'], 'footer') !== false)?"four":"twelve").' columns">
			  <div class="row collapse">
				<div class="two mobile-one columns">
						<span class="prefix"><i class="icon-user"></i></span>
				</div>
				<div class="ten mobile-three columns">
						<input type="text" class="mc_yname" name="yname" id="yname" placeholder="'. __("Your name","kleo_framework").'" required>
				</div>
			  </div>
			</div>
			<div class="'.(isset($args['id']) && (strpos($args['id'], 'footer') !== false)?"five":"twelve").' columns">
			  <div class="row collapse">
				<div class="two mobile-one columns">
						<span class="prefix"><i class="icon-envelope"></i></span>
				</div>
				<div class="ten mobile-three columns">
						<input type="email" name="mc_email" class="mc_email" id="mc_email" placeholder="'. __("Your email","kleo_framework").'" required>
				</div>
			  </div>
			</div>
			<div class="'.(isset($args['id']) && (strpos($args['id'], 'footer') !== false)?"three":"six").' columns">
				<p><button type="submit" id="newsletter-submit" name="newsletter-submit" class="small radius button expand">'.__("JOIN US",'kleo_framework').'</button></p>
			</div>
			<div class="twelve column">

			  <div><small id="result" class="mc_result">'.(isset ( $msg )?$msg:'' ).'</small></div>';

		// After text
		if ( ! empty( $instance['mailchimp_after_text'] ) )	{
			echo	$instance['mailchimp_after_text'];
		}

		echo '</div>
		  </form><!--end newsletter-form-->';
               
                
		$nonce = wp_create_nonce("mc_mail");
		echo "<script type='text/javascript'>
			jQuery(document).ready(function($) {  
				// Prepare the Newsletter and send data to Mailchimp
				
				$('.newsletter-form').each(function() {
					$(this).submit(function() {
						var container = $(this);
							$.ajax({
									url: ajaxurl,
									type: 'POST',
									data: {
											action: 'mc_action',
											mc_email: $('.mc_email', container).attr('value'),
											yname: $('.mc_yname', container).attr('value'),
											list: $('.mc_list', container).attr('value'),
											nonce: '".$nonce."'
									},
									success: function(data){
											$('.mc_result', container).html(data).css('color', 'green');
									},
									error: function() {
											$('.mc_result', container).html('Sorry, an error occurred.').css('color', 'red');
									}

							});
							return false;
					});
				});


			});
		</script>";
		echo '</div><!--end panel-->';
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['mailchimp_before_text'] =  stripslashes($new_instance['mailchimp_before_text']) ;
		$instance['mailchimp_after_text'] =  stripslashes($new_instance['mailchimp_after_text']) ;
		$instance['mailchimp_list'] = $new_instance['mailchimp_list'];
		$instance['opt_in'] = $new_instance['opt_in'];
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$mailchimp_before_text = isset( $instance['mailchimp_before_text'] ) ? $instance['mailchimp_before_text'] : '';
		$mailchimp_after_text = isset( $instance['mailchimp_after_text'] ) ? $instance['mailchimp_after_text'] : '';
		$mailchimp_list = isset( $instance['mailchimp_list'] ) ? $instance['mailchimp_list'] : '';
		$opt_in = isset( $instance['opt_in'] ) ? $instance['opt_in'] : '';

		if ( !function_exists('curl_init') ) {
			echo __('Curl is not enabled. Please contact your hosting company and ask them to enable CURL.','kleo_framework');
			return;
		}
		
		if ( !isset ( $this->api_key ) && empty ( $this->api_key ) ) {
			echo __('You need to enter your MailChimp API_KEY in theme options before using this widget.','kleo_framework');
			return;
		}
		
		
		if ( isset ( $this->api_key ) && !empty ( $this->api_key ) ) {
			include_once (FRAMEWORK_URL . '/widgets/mailchimp/MCAPI.class.php');
			$api_key = sq_option('mailchimp_api');
			
			$mcapi = new MCAPI($api_key);
			
			$lists = $mcapi->lists();
		}
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','kleo_framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('mailchimp_list'); ?>"><?php _e('Select List:','kleo_framework'); ?></label>
			<select id="<?php echo $this->get_field_id('mailchimp_list'); ?>" name="<?php echo $this->get_field_name('mailchimp_list'); ?>">
				<?php	
				if ( isset($lists) && !empty($lists) ) {
					foreach ($lists['data'] as $key => $value) {
						$selected = (isset($mailchimp_list) && $mailchimp_list == $value['id']) ? ' selected="selected" ' : '';
						?>	
						<option <?php echo $selected; ?>value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('opt_in'); ?>"><?php _e('Double opt-in:','kleo_framework'); ?></label>
			<select id="<?php echo $this->get_field_id('opt_in'); ?>" name="<?php echo $this->get_field_name('opt_in'); ?>">
				<option <?php if (isset($opt_in) && $opt_in == 'yes') { echo 'selected="selected"'; }?> value="yes"><?php _e("Yes");?></option>
				<option <?php if (isset($opt_in) && $opt_in == 'no') { echo 'selected="selected"'; }?> value="no"><?php _e("No");?></option>
			</select>
		</p>
		
		<p>
			<div><label for="<?php echo $this->get_field_id('mailchimp_before_text'); ?>"><?php echo __('Text before form :','kleo_framework'); ?></label></div>
			<div><textarea class="widefat" id="<?php echo $this->get_field_id('mailchimp_before_text'); ?>" name="<?php echo $this->get_field_name('mailchimp_before_text'); ?>" rows="5"><?php echo $mailchimp_before_text; ?></textarea></div>
		</p>
		<p>
			<div><label for="<?php echo $this->get_field_id('mailchimp_after_text'); ?>"><?php echo __(' Text after form:','kleo_framework'); ?></label></div>
			<div><textarea class="widefat" id="<?php echo $this->get_field_id('mailchimp_after_text'); ?>" name="<?php echo $this->get_field_name('mailchimp_after_text'); ?>" rows="5"><?php echo $mailchimp_after_text; ?></textarea></div>
		</p>

		<?php
	}
}


add_action( 'widgets_init', create_function( '', 'register_widget( "SQueen_Mailchimp_Widget" );' ) );


/*
 * Ajax helper
*/
add_action('wp_ajax_mc_action', 'mc_action');
add_action('wp_ajax_nopriv_mc_action', 'mc_action');

function mc_action(){

    $api_key = sq_option('mailchimp_api');

    if ( isset ( $_POST['mc_email']) && wp_verify_nonce( $_POST['nonce'], "mc_mail")) {

        if ( isset ( $api_key ) && !empty ( $api_key ) ) {

                include_once (FRAMEWORK_URL . '/widgets/mailchimp/MCAPI.class.php');
                $api_key = sq_option('mailchimp_api');

                $mcapi = new MCAPI($api_key);

                $name = explode(" ", $_POST['yname']);
                $fname = (!empty($name[0])?$name[0]:"");
                unset($name[0]);
                $lname = (!empty($name)?join(" ", $name):"");
                    
                $merge_vars = array( 
					'FNAME' => $fname,
					'LNAME' => $lname
                );

                $list_id = $_POST['list'];
				$opt_in = sq_option('mailchimp_opt_in', 'yes') == 'yes' ? true : false;
				
                $answer = $mcapi->listSubscribe($list_id, $_POST['mc_email'], $merge_vars, 'html', $opt_in);
                 if($mcapi->errorCode) {
                    // An error ocurred, return error message   
                    $msg = '<span style="color:red;"><b>'.__('Error:','kleo_framework').'</b>&nbsp; ' . $mcapi->errorMessage.'</span>';
                }else{
                    // It worked!   
					if ($opt_in === true) {
						$msg = __('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.','kleo_framework');
					}
					else {
						$msg = __('You have successfully subscribed.','kleo_framework');
					}
                }
            echo ($msg);
        }
    }
    die();
}

?>
