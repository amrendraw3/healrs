<?php

/**
 * BuddyPress Docs Directory
 *
 * @package BuddyPress_Docs
 * @since 1.2
 */

?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_docs_page' ); ?>

    <?php get_template_part('page-parts/buddypress-before-wrap');?>

		<?php do_action( 'template_notices' ) ?>

		<?php do_action( 'bp_before_directory_docs' ); ?>

		<h3><?php _e( 'Docs Directory', 'bp-docs' ); ?></h3>

		<?php include( bp_docs_locate_template( 'docs-loop.php' ) ) ?>

		<?php do_action( 'bp_after_directory_docs' ); ?>

	<?php do_action( 'bp_after_directory_docs_page' ); ?>

<?php get_template_part('page-parts/buddypress-after-wrap');?>

<?php get_footer( 'buddypress' ); ?>
