<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://objectiv.co
 * @since      1.0.0
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/admin/partials
 */

$post_type_objects      = $this->plugin->get_post_types();
$templates              = $this->get_templates();
$act_post_type_settings = $this->plugin->get_setting( 'act_post_type_settings' );
?>
<div class="wrap">

	<?php global $wp_tabbed_navigation; ?>
	<?php $wp_tabbed_navigation->display_tabs(); ?>

	<h3><?php esc_html_e( 'Settings', 'simple-post-template' ); ?></h3>
	<p>Do you like Simple Content Templates? Help us out by <a target="_blank" href="https://wordpress.org/plugins/simple-post-template/">leaving us a review</a>!</p>

	<form method="post" action="<?php echo esc_url( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ); ?>">
		<?php $this->plugin->the_nonce(); ?>

		<table class="form-table">
			<tbody>
				<?php
				foreach ( $post_type_objects as $pto ) :
					if ( ! in_array( $pto->name, array( 'post', 'page' ) ) ) {
					continue;}
					?>
					<tr>
						<th scope="row" valign="top"><?php echo esc_html( $pto->labels->name ); ?></th>
						<td>
							<label><input type="checkbox" name="<?php echo esc_attr( $this->plugin->get_field_name( 'act_post_type_settings' ) ); ?>[<?php echo esc_attr( $pto->name ); ?>][show_ui]" value="true" 
							<?php
							if ( isset( $act_post_type_settings[ $pto->name ]['show_ui'] ) && $act_post_type_settings[ $pto->name ]['show_ui'] == 'true' ) {
							checked( $act_post_type_settings[ $pto->name ]['show_ui'], 'true' );
						}
							?>
							/> Show UI</label><br />
							<label>Auto Load Template:
							<select name="<?php echo esc_attr( $this->plugin->get_field_name( 'act_post_type_settings' ) ); ?>[<?php echo esc_attr( $pto->name ); ?>][auto_load]">
								<option value="false">None</option>
								<?php foreach ( $templates as $t ) : ?>
									<?php
										$key_exists        = $act_post_type_settings[ $pto->name ] ?? false;
										$auto_load_id      = $act_post_type_settings[ $pto->name ]['auto_load'] ?? false;
										$auto_load_current = $auto_load_id == $t->ID;

										$selected_text = '';
									if ( $key_exists && $auto_load_id && $auto_load_current ) {
										$selected_text = 'selected';
									}
									?>
									<option value="<?php echo esc_attr( $t->ID ); ?>" <?php echo esc_attr( $selected_text ); ?>><?php echo esc_html( $t->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
							</label><br />
						</td>
					</tr>
				<?php endforeach; ?>

				<?php do_action_ref_array( 'act_admin_page_after_row', array( $this->plugin ) ); ?>
			</tbody>
		</table>

			<p class="submit"><input type="submit" class="button-primary" name="save" value="<?php esc_attr_e( 'Save Changes', 'simple-post-template' ); ?>" /></p>
		<div style="clear:both"></div>
	</form>
</div>
