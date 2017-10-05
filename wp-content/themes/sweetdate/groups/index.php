<?php

/**
 * BuddyPress - Groups Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

<?php do_action( 'bp_before_directory_groups_page' ); ?>

<?php get_template_part('page-parts/buddypress-before-wrap');?>

<?php do_action( 'bp_before_directory_groups' ); ?>

	<form action="" method="post" id="groups-directory-form" class="dir-form custom">

		<h2><?php _e( 'Groups directory', 'buddypress' ); ?></h2>

		<?php if ( is_user_logged_in() && bp_user_can_create_groups() ) : ?>&nbsp;<a style="display:inline-block;" class="button radius small" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ); ?>"><?php _e( 'Create a Group', 'buddypress' ); ?></a><?php endif; ?>
		<div style="margin-bottom: 10px;" class="clearfix"></div><br><br>
		<?php do_action( 'bp_before_directory_groups_content' ); ?>

		<div id="group-dir-search" class="dir-search" role="search">

			<?php bp_my_directory_groups_search_form(); ?>

		</div><!-- #group-dir-search -->

		<?php do_action( 'template_notices' ); ?>

		<div class="item-list-tabs" role="navigation">
			<ul class="sub-nav">
				<li class="selected" id="groups-all"><a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( __( 'All Groups %s', 'buddypress' ), '<span>' . bp_get_total_group_count() . '</span>' ); ?></a></li>

				<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

					<li id="groups-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/'; ?>"><?php printf( __( 'My Groups %s', 'buddypress' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>


				<?php endif; ?>

				<?php do_action( 'bp_groups_directory_group_filter' ); ?>

			</ul>
		</div><!-- .item-list-tabs -->

		<div class="item-list-tabs" id="subnav" role="navigation">
			<ul class="sub-nav">

				<?php do_action( 'bp_groups_directory_group_types' ); ?>

				<li id="groups-order-select" class="last filter">

					<label for="groups-order-by"><?php _e( 'Order By:', 'buddypress' ); ?></label>
					<select id="groups-order-by">
						<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
						<option value="popular"><?php _e( 'Most Members', 'buddypress' ); ?></option>
						<option value="newest"><?php _e( 'Newly Created', 'buddypress' ); ?></option>
						<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>

						<?php do_action( 'bp_groups_directory_order_options' ); ?>

					</select>
				</li>
			</ul>
		</div>

		<div id="groups-dir-list" class="groups dir-list">

			<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>

		</div><!-- #groups-dir-list -->

		<?php do_action( 'bp_directory_groups_content' ); ?>

		<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

		<?php do_action( 'bp_after_directory_groups_content' ); ?>

	</form><!-- #groups-directory-form -->

<?php do_action( 'bp_after_directory_groups_page' ); ?>

<?php do_action( 'bp_after_directory_groups' ); ?>

<?php get_template_part('page-parts/buddypress-after-wrap');?>

<?php get_footer( 'buddypress' ); ?>