<?php

/**
 * BuddyPress Delete Account
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

<?php get_template_part('page-parts/buddypress-profile-header');?>

<?php get_template_part('page-parts/buddypress-before-wrap');?>


        <!-- MAIN SECTION
        ================================================ -->
        <section>
            <div id="main">
                <div class="row">
                    <div class="<?php if (sq_option('buddypress_sidebar') == 'yes') echo 'eight'; else echo 'twelve'; ?> columns">

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

				<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/'; ?>" name="account-capabilities-form" id="account-capabilities-form" class="standard-form custom" method="post">

					<?php do_action( 'bp_members_capabilities_account_before_submit' ); ?>

					<label>
						<input type="checkbox" name="user-spammer" id="user-spammer" value="1" <?php checked( bp_is_user_spammer( bp_displayed_user_id() ) ); ?> />
						 <?php _e( 'This user is a spammer.', 'buddypress' ); ?>
					</label>

					<div class="submit">
						<input type="submit" class="button small radius" value="<?php _e( 'Save', 'buddypress' ); ?>" id="capabilities-submit" name="capabilities-submit" />
					</div>

					<?php do_action( 'bp_members_capabilities_account_after_submit' ); ?>

					<?php wp_nonce_field( 'capabilities' ); ?>

				</form>

				<?php do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_settings_template' ); ?>

    <?php get_template_part('page-parts/buddypress-after-wrap');?>
        
<?php get_footer( 'buddypress' ); ?>
