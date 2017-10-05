<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>


<?php if ( bp_has_members( bp_ajax_querystring( 'members' ). '&per_page='.sq_option('buddypress_perpage') ) ) : ?>


	<?php do_action( 'bp_before_directory_members_list' ); ?>
	
  <div class="item-list search-list" id="members-list">
    <?php while ( bp_members() ) : bp_the_member(); ?>

      <div class="four columns">
        <div class="search-item">
          <div class="avatar">
            <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&width=94&height=94&class='); ?></a>
            <?php do_action('bp_members_inside_avatar');?>
          </div>
            <?php do_action('bp_members_meta');?>
          <div class="search-body">
              <?php do_action( 'bp_directory_members_item' ); ?>

              <?php
              /***
               * If you want to show specific profile fields here you can,
               * but it'll add an extra query for each member in the loop
               * (only one regardless of the number of fields you show):
               *
               * bp_member_profile_data( 'field=the field name' );
               */
              ?>
	          
          </div>
          <div class="bp-member-dir-buttons">
          <?php do_action('bp_directory_members_item_last');?>
          </div>
        </div>
      </div>

    <?php endwhile; ?>
  </div>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

  <!-- Pagination -->
  <div class="row">
      <div  class="twelve columns pagination-centered">
          <div class="pagination" id="pag-bottom">
              <div id="member-dir-pag-bottom" class="pagination-links">
              <?php bp_members_pagination_links(); ?>
              </div>
          </div>
      </div>
  </div>
  <!--end  Pagination-->
<?php else: ?>

	<div id="message" class="alert-box">
		<?php _e( "Sorry, no members were found.", 'kleo_framework'); ?>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
