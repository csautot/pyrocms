<script type="text/javascript">
	(function($) {
		$(function() {
			
			$('input#other_subject').hide();
			$('select#subject').change(function()
			{
				if(this.value == 'other')
				{
					$('#other_subject').show().val('').focus();
				}
				else
				{
					$('#other_subject').hide();
				}
			});
			
		});
	})(jQuery);
</script>

<h2><?php echo lang('contact_title');?></h2>
<div class="error">	
	<?php echo @$this->validation->error_string; ?>
</div>
	<?php echo form_open('contact', array('class' => 'simple-form'));?>
			
		<fieldset>
			<legend><?php echo lang('contact_details_label');?></legend>
			<div class="field-block">
			    <label for="contact_email"><?php echo lang('contact_name_label');?></label>
			    <?php echo form_input('contact_name', $form_values->contact_name);?>
			</div>
			
			<div class="field-block">
				<label for="contact_email"><?php echo lang('contact_email_label');?></label>
				<?php echo form_input('contact_email', $form_values->contact_email);?>
			</div>
			
			<div class="field-block">
				<label for="contact_email"><?php echo lang('contact_company_name_label');?></label>
				<?php echo form_input('company_name', $form_values->company_name);?>
			</div>
			
			<div class="field-block">
				<label for="contact_email"><?php echo lang('contact_subject_label');?></label>			
				<?php echo form_dropdown('subject', $subjects, $form_values->subject, 'id="subject"');?>
				<input id="other_subject" name="other_subject" type="text" />
			</div>
		</fieldset>
		
		<fieldset>
			<legend><?php echo lang('contact_message_label');?></legend>
			
			<?php echo form_textarea(array('id'=>'message', 'name'=>'message', 'value'=>$form_values->message, 'rows'=>8, 'style'=>'width:100%'));?>
		</fieldset>
		
		<div class="actions">
			<?php echo form_submit('submit', lang('contact_send_label'));?>
		</div>
<?php echo form_close(); ?>