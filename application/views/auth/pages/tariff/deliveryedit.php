<script>

	$(document).ready(function() {
        $('.date').datepicker({
            numberOfMonths: 2,
            showButtonPanel: true,
            dateFormat:'yy-mm-dd'});
	});

</script>

<div id="form">
	<div class="form_box">
			<form method="post" action="<?php echo site_url('admin/tariff/editdelivery/'.$user['id'].'/'.$app_id)?>">

			Sequence:<br />
			<input type="text" name="seq" size="50" class="form" value="<?php echo set_value('seq',$user['seq']); ?>" /><?php echo form_error('seq'); ?><br /><br />

			Kg From:<br />
			<input type="text" name="kg_from" size="50" class="form" value="<?php echo set_value('kg_from',$user['kg_from']); ?>" /><?php echo form_error('kg_from'); ?><br /><br />

			Kg To:<br />
			<input type="text" name="kg_to" size="50" class="form" value="<?php echo set_value('kg_to',$user['kg_to']); ?>" /><?php echo form_error('kg_to'); ?><br /><br />

			Calculated Kg:<br />
			<input type="text" name="calculated_kg" size="50" class="form" value="<?php echo set_value('calculated_kg',$user['calculated_kg']); ?>" /><?php echo form_error('calculated_kg'); ?><br /><br />

			Tariff / To:<br />
			<input type="text" name="tariff_kg" size="50" class="form" value="<?php echo set_value('tariff_kg',$user['tariff_kg']); ?>" /><?php echo form_error('tariff_kg'); ?><br /><br />

			Total:<br />
			<input type="text" name="total" size="50" class="form" value="<?php echo set_value('total',$user['total']); ?>" /><?php echo form_error('total'); ?><br /><br />

			Period From:<br />
			<input type="text" name="period_from" size="50" class="form date" value="<?php echo set_value('period_from',$user['period_from']); ?>" /><?php echo form_error('period_from'); ?><br /><br />

			Period To:<br />
			<input type="text" name="period_to" size="50" class="form date" value="<?php echo set_value('period_to',$user['period_to']); ?>" /><?php echo form_error('period_to'); ?><br /><br />

			<input type="submit" value="Update" name="register" />
			<?php
				print anchor('admin/tariff/cod/'.$user['app_id'],'Cancel');
			?>
			</form>
	</div>
</div>