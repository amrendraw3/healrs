<?php get_header( 'buddypress' ); ?>

<?php get_template_part('page-parts/buddypress-profile-header');?>

<?php get_template_part('page-parts/buddypress-before-wrap');?>

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_displayed_user_nav(); ?>
					</ul>
				</div>
			</div>

			<div id="item-body">

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul class="sub-nav">
						<?php bp_get_options_nav(); ?>
					</ul>
				</div>

					<?php if (bp_album_has_pictures() ) : bp_album_the_picture();?>

				<div class="picture-single activity">
					<h3><?php bp_album_picture_title(); ?></h3>

                	<div class="picture-outer-container">
                		<div class="picture-inner-container">
			                <div class="picture-middle">
				                <img alt='<?php bp_album_picture_title() ?>' src="<?php bp_album_picture_middle_url(); ?>" />
				                <?php //bp_album_adjacent_links(); ?>
			                </div>
		                </div>
	                </div>

					<p class="picture-description"><?php bp_album_picture_desc(); ?></p>
	                <p class="picture-meta">
	                <?php bp_album_picture_edit_link();  ?>
	                <?php bp_album_picture_delete_link();  ?></p>

				<?php bp_album_load_subtemplate( apply_filters( 'bp_album_template_screen_comments', 'album/comments' ) ); ?>
			
				</div>
					<?php else : ?>

				<div id="message" data-alert class="alert-box">
					<?php echo bp_word_or_name( __( "This url is not valid.", 'bp-album' ), __( "Either this url is not valid or picture has restricted access.", 'bp-album' ),false,false ) ?>
				</div>

				<?php endif; ?>

			</div><!-- #item-body -->

<?php get_template_part('page-parts/buddypress-after-wrap');?>
        
<?php get_footer( 'buddypress' ); ?>