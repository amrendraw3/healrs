<?php do_action( 'bp_before_profile_edit_content' );

if ( bp_has_profile( 'profile_group_id=' . bp_get_current_profile_group_id() ) ) :
	while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<form action="<?php bp_the_profile_group_edit_form_action(); ?>" method="post" id="profile-edit-form" class="custom standard-form <?php bp_the_profile_group_slug(); ?>">

			<?php do_action( 'bp_before_profile_field_content' ); ?>

			<h4><?php printf( __( "Editing '%s' Profile Group", "buddypress" ), bp_get_the_profile_group_name() ); ?></h4>

			<ul class="button-nav">

				<?php bp_profile_group_tabs(); ?>

			</ul>

			<div class="clearfix"></div>
			<div class="row">
				<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

					<div class="<?php if('selectbox' == bp_get_the_profile_field_type() || 'multiselectbox' == bp_get_the_profile_field_type() ) echo 'six'; else echo 'twelve';?> columns">
						<div<?php bp_field_css_class( 'editfield' ); ?>>

							<?php
							$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
							$field_type->edit_field_html();

							do_action( 'bp_custom_profile_edit_fields_pre_visibility' );
							?>

							<?php if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
								<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
									<?php
									printf(
										__( 'This field can be seen by: %s', 'buddypress' ),
										'<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
									);
									?>
								</p>

								<div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
									<fieldset>
										<legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>

										<?php bp_profile_visibility_radio_buttons() ?>

									</fieldset>
									<a class="field-visibility-settings-close" href="#"><?php _e( 'Close', 'buddypress' ) ?></a>
								</div>
							<?php else : ?>
								<p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
									<?php
									printf(
										__( 'This field can be seen by: %s', 'buddypress' ),
										'<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
									);
									?>
								</p>
							<?php endif ?>

							<?php do_action( 'bp_custom_profile_edit_fields' ); ?>

							<p class="description"><?php bp_the_profile_field_description(); ?></p>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
			<?php do_action( 'bp_after_profile_field_content' ); ?>

			<div class="submit">
				<input class="button small radius" type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?php _e( 'Save Changes', 'buddypress' ); ?> " />
			</div>

			<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />

			<?php wp_nonce_field( 'bp_xprofile_edit' ); ?>

		</form>

	<?php endwhile; endif; ?>

<?php do_action( 'bp_after_profile_edit_content' ); ?>
