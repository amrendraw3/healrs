<?php do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>
	<ul class="accordion">
	<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php do_action( 'bp_before_profile_field_content' ); ?>

				<li>
				  <h5 class="accordion-title <?php bp_the_profile_group_slug(); ?>"><?php bp_the_profile_group_name(); ?><span class="accordion-icon"></span></h5>
				  <div class="accordion-content">
						<dl class="dl-horizontal">

							<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

								<?php if ( bp_field_has_data() ) : ?>

									<dt><?php bp_the_profile_field_name(); ?></dt>
									<dd><?php bp_the_profile_field_value(); ?></dd>

								<?php endif; ?>

								<?php do_action( 'bp_profile_field_item' ); ?>

							<?php endwhile; ?>
						</dl>

				  </div>
				</li>
			<?php do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>
	</ul>
	<?php do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_profile_loop_content' ); ?>