<?php if ( bp_group_has_members( 'exclude_admins_mods=0' ) ) : ?>

	<?php do_action( 'bp_before_group_members_content' ); ?>

	<div class="item-list-tabs" id="subnav" role="navigation">
		<ul class="sub-nav">

			<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

		</ul>
	</div>


	<?php do_action( 'bp_before_group_members_list' ); ?>

	<ul id="member-list" class="item-list" role="main">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<li class="message">
				<div class="avatar">
					<a href="<?php bp_group_member_domain(); ?>">

					<?php bp_group_member_avatar_thumb(); ?>

					</a>
				</div>
				<div class="item">
					<h5><?php bp_group_member_link(); ?></h5>
					<span class="activity"><?php bp_group_member_joined_since(); ?></span>

					<?php do_action( 'bp_group_members_list_item' ); ?>
				</div>
			</li>

		<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination no-ajax">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" data-alert class="alert-box">
		<?php _e( 'This group has no members.', 'buddypress' ); ?>
	</div>

<?php endif; ?>
