<?php

/**
 * BP Checkins - Place Home
 *
 *
 * @package BP Checkins
 */
/* BP Theme compat feature needs to be used so let's adapt templates to it */
?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_home_bp_checkins_page' ); ?>

	<?php get_template_part('page-parts/buddypress-before-wrap'); ?>
			
		<?php do_action( 'template_notices' ); ?>
		
		<div id="places-src-container">
			
			<h3><?php _e('Search Places', 'bp-checkins');?></h3>
			


			<form id="form-places-search" class="standard-form">
				<input type="text" id="places-search" name="places_search">
			</form>
			
		</div>
		
		<div id="places-brz-categories">
			
			<h3><?php _e('Or browse places by category', 'bp-checkins');?></h3>
			
			<?php bp_checkins_places_browse_cats();?>
			
		</div>
		
		<?php do_action( 'bp_after_home_bp_checkins_page_content' ); ?>

		
<?php get_template_part('page-parts/buddypress-after-wrap');?>

<?php do_action( 'bp_after_home_bp_checkins_page' ); ?>


<?php get_footer( 'buddypress' ); ?>
