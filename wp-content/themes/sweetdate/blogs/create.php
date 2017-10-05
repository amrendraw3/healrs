<?php

/**
 * BuddyPress - Create Blog
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_blogs_content' ); ?>

    <?php get_template_part('page-parts/buddypress-before-wrap');?>

		
		<?php do_action( 'bp_before_create_blog_content_template' ); ?>

		<?php do_action( 'template_notices' ); ?>

			<h2 style="display: inline;"><?php _e( 'Create a Site', 'buddypress' ); ?></h2>
			 &nbsp;<a class="button radius small" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_blogs_root_slug() ); ?>"><?php _e( 'Site Directory', 'buddypress' ); ?></a>
			<div class="clearfix" style="margin-bottom: 10px;"></div>
		<?php do_action( 'bp_before_create_blog_content' ); ?>

		<?php if ( bp_blog_signup_enabled() ) : ?>

			<?php bp_show_blog_signup_form(); ?>

		<?php else: ?>

			<div id="message" class="info">
				<p><?php _e( 'Site registration is currently disabled', 'buddypress' ); ?></p>
			</div>

		<?php endif; ?>

		<?php do_action( 'bp_after_create_blog_content' ); ?>
		
		<?php do_action( 'bp_after_create_blog_content_template' ); ?>
     
        <?php get_template_part('page-parts/buddypress-after-wrap');?>
            
    <?php do_action( 'bp_after_directory_blogs_content' ); ?>

<?php get_footer( 'buddypress' ); ?>

