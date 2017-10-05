<?php

/**
 * BuddyPress Member Settings
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

<?php get_template_part('page-parts/buddypress-profile-header');?>

<?php get_template_part('page-parts/buddypress-before-wrap');?>

			<?php do_action( 'bp_before_member_settings_template' ); ?>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->

			<div id="item-body" role="main">

				<?php do_action( 'bp_before_member_body' ); ?>

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>

						<?php bp_get_options_nav(); ?>

						<?php do_action( 'bp_member_plugin_options_nav' ); ?>

					</ul>
				</div><!-- .item-list-tabs -->

				<?php do_action( 'bp_template_content' ); ?>
                                
				<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>" method="post" class="standard-form custom" id="settings-form">
                <div class="row">
                                        
					<?php if ( !is_super_admin() ) : ?>
                        <div class="seven columns">
                            <br><br>
                            <p><?php _e( 'Current Password <span>(required to update email or change current password)</span>', 'buddypress' ); ?></p>
                            <input type="password" name="pwd" id="pwd" size="16" value="" class="settings-input small " /> &nbsp;<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e( 'Password Lost and Found', 'buddypress' ); ?>"><?php _e( 'Lost your password?', 'buddypress' ); ?></a>
                        </div>
					<?php endif; ?>
                        <div class="seven columns">
                            <label for="email"><?php _e( 'Account Email', 'buddypress' ); ?></label>
                            <input type="text" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" />
                        </div>
                        <div class="twelve columns">
                            <br><br>
                            <?php _e( 'Change Password <span>(leave blank for no change)</span>', 'buddypress' ); ?>
                        </div>
                        <div class="seven columns">
                            <label>
                                <?php _e( 'New Password', 'buddypress' ); ?>
                            </label>
                            <input type="password" name="pass1" id="pass1" size="16" value="" class="settings-input small password-entry" />
                            <div id="pass-strength-result"></div>
                            <label> 
                                <?php _e( 'Repeat New Password', 'buddypress' ); ?>
                            </label>
                            <input type="password" name="pass2" id="pass2" size="16" value="" class="settings-input small password-entry-confirm" />
                        </div>
					<?php do_action( 'bp_core_general_settings_before_submit' ); ?>
                        <div class="clearfix"></div>
                        <br/>
					<div class="submit seven columns">
						<input type="submit" name="submit" value="<?php _e( 'Save Changes', 'buddypress' ); ?>" id="submit" class="auto button small radius" />
					</div>
                                       
					<?php do_action( 'bp_core_general_settings_after_submit' ); ?>

					<?php wp_nonce_field( 'bp_settings_general' ); ?>
				</div>
				</form>

				<?php do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_settings_template' ); ?>

    <?php get_template_part('page-parts/buddypress-after-wrap');?>
        
<?php get_footer( 'buddypress' ); ?>
