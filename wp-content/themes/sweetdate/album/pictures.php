<?php get_header('buddypress'); ?>

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

					<?php if ( bp_album_has_pictures() ) : ?>

				<div class="picture-pagination">
					<?php bp_album_picture_pagination(); ?>
				</div>

				<div class="picture-gallery">
                    <?php while ( bp_album_has_pictures() ) : bp_album_the_picture(); ?>
                    
                    <?php if  (bp_is_my_profile() || is_super_admin()): ?>
                    <div class="picture-thumb-box">
                        <a href="<?php bp_album_picture_url() ?>" class="picture-thumb"><img src='<?php bp_album_picture_thumb_url() ?>' alt='<?php bp_album_picture_title() ?>' /></a>
                        <a href="<?php bp_album_picture_url() ?>" class="picture-title" alt='<?php bp_album_picture_title() ?>'><?php bp_album_picture_title_truncate() ?></a>
                    </div>
                    
                    <?php else: ?>
                    <div class="picture-thumb-box">
	                <a href="<?php bp_album_picture_original_url(); ?>" data-rel="prettyPhoto[gallery3]" class="picture-thumb"><img alt='<?php bp_album_picture_title() ?>' src='<?php bp_album_picture_thumb_url() ?>' /></a>
                    <a href="<?php bp_album_picture_original_url(); ?>" class="picture-title"><?php bp_album_picture_title_truncate() ?></a>
                    </div>
                    <?php endif;?>

                    <?php endwhile; ?>
				</div>
					<?php else : ?>

				<div id="message" data-alert class="alert-box">
					<?php echo bp_word_or_name( __("You don't have any photos yet. Why not upload some!", 'bp-album' ), __( "Either %s hasn't uploaded any pictures yet or they have restricted access", 'bp-album' )  ,false,false) ?>
				</div>

				<?php endif; ?>

			</div><!-- #item-body -->


<?php get_template_part('page-parts/buddypress-after-wrap');?>
        
<?php get_footer( 'buddypress' ); ?>
