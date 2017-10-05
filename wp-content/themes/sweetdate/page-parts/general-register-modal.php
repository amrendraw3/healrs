<div id="register_panel" class="reveal-modal">
  <div class="row">
    <div class="twelve columns">
      <h5><i class="icon-magic icon-large"></i> <?php _e("CREATE ACCOUNT", 'kleo_framework');?> <span class="subheader right small-link"><a href="#" data-reveal-id="login_panel" class="radius secondary small button"><?php _e("ALREADY HAVE AN ACCOUNT?", 'kleo_framework'); ?></a></span></h5>
    </div>
      <form id="register_form" action="<?php if (function_exists('bp_is_active')) bp_signup_page(); else echo get_bloginfo('url')."/wp-login.php?action=register"; ?>" name="signup_form" method="post">
	      <?php if (function_exists( 'bp_is_active' ) &&  bp_is_active( 'xprofile' ) && bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) )  ) { ?>

		      <div class="six columns">
		        <input type="text" id="reg-username" name="signup_username" class="inputbox" required placeholder="<?php _e("Username", 'kleo_framework');?>">
		      </div>
		      <div class="six columns">
		        <input type="text" id="fullname" name="field_1" class="inputbox" required placeholder="<?php _e("Your full name", 'kleo_framework');?>">
		      </div>
		      <div class="twelve columns">
		        <input type="text" id="reg-email" name="signup_email" class="inputbox" required placeholder="<?php _e("Your email", 'kleo_framework');?>">
		      </div>
		      <div class="six columns">
		        <input type="password" id="reg-password" name="signup_password" class="inputbox" required placeholder="<?php _e("Desired password", 'kleo_framework');?>">
		      </div>
		      <div class="six columns">
		        <input type="password" id="confirm_password" name="signup_password_confirm" class="inputbox" required placeholder="<?php _e("Confirm password", 'kleo_framework');?>">
		      </div>

			  <input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_field_ids(); ?>" />
		      <?php wp_nonce_field( 'bp_new_signup' ); ?>

	      <?php } else { ?>
	      <div class="twelve columns">
	        <input type="text" id="reg-username" name="user_login" class="inputbox" required placeholder="<?php _e("Username", 'kleo_framework');?>">
	      </div>
	      <div class="twelve columns">
	        <input type="text" id="reg-email" name="user_email" class="inputbox" required placeholder="<?php _e("Your email", 'kleo_framework');?>">
	      </div>
	      <?php } ?>

	      <div class="twelve columns">

			<?php if( sq_option('terms_page', '#') != "#") {
	            $terms_page_id = sq_option( 'terms_page' );
	            /* WPML compatibility */
	            if ( function_exists('icl_object_id') ) {
	                $terms_page_id = icl_object_id( $terms_page_id, 'page', true );
	            }
	            $terms_link = get_permalink( $terms_page_id );
	            ?>
	            <p>
	                <label>
	                    <input type="checkbox" name="tos_register" id="tos_register" class="tos_register">
	                    <small>
	                        <?php printf( __( 'I agree with the <a href="%s" target="_blank"><strong>terms and conditions</strong></a>.', 'kleo_framework' ), $terms_link ); ?>
	                    </small>
	                </label>
	            </p>
	        <?php } ?>

			  <?php
			  if ( sq_option('bp_plugins_hook', 0 ) ) {
				  do_action( 'bp_before_registration_submit_buttons' );
			  }
			  ?>

			<button type="submit" id="signup" name="signup_submit" class="radius alert button"><i class="icon-<?php echo apply_filters('kleo_register_button_icon','heart'); ?>"></i> &nbsp;<?php _e("CREATE MY ACCOUNT", 'kleo_framework');?></button> &nbsp;
	        <?php do_action('fb_popup_register_button'); ?>
	      </div>
      </form>
  </div><!--end row-->
  <a href="#" class="close-reveal-modal">×</a>
</div>