<div class="arca-pg arca-pg-pay-button">
	<form action="<?php echo esc_url(arca_pg_checkOutPagePermalink()); ?>" method="post">
		<input type="hidden" name="productId" value="<?php echo esc_attr($productId); ?>">
		<input type="hidden" name="language" value="<?php echo esc_attr($language); ?>">
		<input type="hidden" name="description" value="<?php echo esc_attr($description); ?>">
		<input type="hidden" name="currency" value="<?php echo esc_attr($currency); ?>">
		<input type="submit" value="<?php _e( "Pay", 'apg' ) ?>">
	</form>
</div>