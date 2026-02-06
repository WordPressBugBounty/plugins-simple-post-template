<?php
global $post, $act_plugin_admin;
$templates = $this->get_templates();

if ( empty( $templates ) ) {
	echo esc_html( "You don't have any content templates." );
	return;
}
?>
<div>
	<label for="act_template">Load a Content Template:</label>
	<select name="act_template" id="act_template" style="width: calc( 100% - 48px ); margin-top: 8px;">
		<option disabled selected value="nada">Choose one</option>
		<?php foreach ( $templates as $template ) : ?>
			<option value="<?php echo esc_attr( $template->ID ); ?>"><?php echo esc_html( $template->post_title ); ?></option>
		<?php endforeach; ?>
	</select>

	<input class="button-primary" style="margin-top: 10px;" name="act_load_template" id="act_load_template" type="button" value="Load Template" />
	<input type="hidden" id="act_template_nonce_value" value="<?php echo esc_attr( wp_create_nonce('act_load_template') ); ?>" />
	<input type="hidden" id="act_post_id" value="<?php echo esc_attr($post->ID); ?>" />
</div>

<div class="" style="padding: 12px; border-radius: 4px; background-color:#ffe01a; margin-top: 12px;">
	<h4 style="margin-bottom: 4px; margin-top: 0px;">Advanced Content Templates</h4>
	<p>Want more powerful features like custom fields, featured images, and taxonomies? Upgrade today!</p>
	<p style="font-style: italic;">P.S. You can save 25% on your pro upgrade with code <b>LITE25</b></p>
	<a class="button-secondary" target="_blank" href="https://www.advancedcontenttemplates.com/?utm_campaign=free&utm_source=wprepo">Check Out ACT</a>
</div>

<script>
jQuery(document).ready(function() {
	jQuery("#act_load_template").click(function(e) {
		e.preventDefault();

		var template = jQuery("#act_template option:selected").val();
		var nonce = jQuery('#act_template_nonce_value').val();
		var post_id = jQuery('#act_post_id').val();

		if ( 'nada' === template ) {
			alert( "You need to actually select a content template to load, otherwise we would just be guessing!");
			return;
		}

		if ( confirm('Are you sure? Loading this template may wipe out existing changes.') ) {
			window.location = 'post-new.php?post_id=' + post_id + '&act_template_load=' + template + '&act_nonce=' + encodeURIComponent(nonce);
		}

	})
});
</script>
