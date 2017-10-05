<?php

/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' )."&per_page=". apply_filters('kleo_bp_groups_pp', 12) ) ) : ?>


	<?php do_action( 'bp_before_directory_groups_list' ); ?>

	<div id="groups-list" class="item-list search-list" role="main">

	<?php while ( bp_groups() ) : bp_the_group(); ?>
                
        <div class="four columns">
          <div class="search-item">
              
			<div class="avatar">
				<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=94&height=94&class=' ); ?></a>
			</div>

			<div class="search-meta">
				<h5 class="author"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h5>
                <p class="date"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></p>
            </div>
            <div class="search-body">
                <div class="item-desc"><?php bp_group_description_excerpt(); ?></div>
                
				<?php do_action( 'bp_directory_groups_item' ); ?>
            </div>
            <br>
            <div class="action">
                <?php do_action( 'bp_directory_groups_actions' ); ?>
                <div class="meta">
                        <?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>
                </div>
            </div>
            <br>
          </div>
        </div>

	<?php endwhile; ?>

	</div>

	<?php do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" data-alert class="alert-box">
		<?php _e( 'There were no groups found.', 'buddypress' ); ?>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_groups_loop' ); ?>
