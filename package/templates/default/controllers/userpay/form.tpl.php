<div class="userpay_donate_form userpay_donate_form_modal" id="userpay_payment_form" data-order-id="<?php echo @$param['order_id']; ?>" data-order-name="<?php echo @$param['order_name']; ?>">
		
	<?php if(!empty($param['order_name'])){ ?>
		
		<h4><?php echo @$param['order_name']; ?></h4>
	<?php } ?>
	
	<?php if(!empty($label)){ ?>
		<h5><?php echo $label; ?></h5>
	<?php } ?>
	
	<div style="<?php echo $hide ? 'display:none' : ''; ?>" class="userpay_payments_inputs">
		<div class="input-has-suffix">
			<span class="prefix"><i class="upi-money-1"></i></span>
			<?php echo html_input('text', 'pre-amount', @$param['amount'], array('placeholder'=>'Введите сумму', 'disabled'=>$fix_amount)); ?>
			<span class="suffix"><?php echo $options['curr_short']; ?></span>
		</div>	
	</div>
		
	<?php if($systems){ ?>
		
		<?php if($param['payments_list_style']=='basic'){ ?>
		
			<h5 class="userpay_payments_change_label">Выберите способ оплаты</h5>
			<div class="userpay_payments_buttons">
				<?php $count=1; foreach ($systems as $key => $value) { ?>
					<?php if(count($systems) > 3 && $count==4){ ?>
						<div class="userpay_payments_buttons_hidden" id="userpay_payments_buttons_hidden">
					<?php } ?>
					<?php if( (!count($system_list) || in_array($key, $system_list)) && !in_array($key, $no_list) ) { ?>
						<a href="#" data-system="<?php echo $key; ?>" class="userpay_payment_button <?php echo $key; ?>" title="<?php echo $options[$key.'_name'].': '.$options[$key.'_hint']; ?>"></a>
					<?php } ?>
				<?php $count++; } ?>
		
				<?php if(count($systems) > 3){ ?>
					</div>
					<a href="#" onclick="$('#userpay_payments_buttons_hidden').slideToggle();$(this).hide();" class="userpay_payments_show_hidden">Показать ещё</a>
				<?php } ?>
			</div>
		
		<?php } ?>
		
		<?php if($param['payments_list_style'] == 'default'){ ?>
		
			<div class="userpay_payments_panel_wrapper is_hidden">
				<div class="userpay_payments_panel_list">
					<?php $count = 1; foreach ($systems as $key => $value) { ?>
						<?php if($count == 1){ $default_payment = $key; } ?>
						<?php if( (!count($system_list) || in_array($key, $system_list)) && !in_array($key, $no_list) ) { ?>
							<a href="#" data-system="<?php echo $key; ?>" class="userpay_payment_button <?php echo $key; ?>" title="<?php echo $options[$key.'_name'].': '.$options[$key.'_hint']; ?>"></a>
						<?php } ?>
					<?php $count++; } ?>
        		</div>
       		</div>
	   
	   		<div style="display:none">
				<?php foreach ($systems as $key => $value) { ?>
					<span id="<?php echo $key; ?>_hint"><?php echo $options[$key.'_hint']; ?></span>
				<?php $count++; } ?>
			</div>
		
			<div class="userpay_payments_change_wrapper">
				<a href="#" class="userpay_payments_change"><i class="upi-arrow-combo"></i> Выбрать</a>
				<div class="userpay_payment_button <?php echo $default_payment; ?>" title="<?php echo $options[$default_payment.'_name']; ?>"></div>		
			</div>
		
			<div class="userpay_payments_button_wrapper">
				<a href="#" data-system="<?php echo $default_payment; ?>" class="userpay_payment_button_puy <?php echo array_keys($systems)[0]; ?>"><?php echo !empty($param['target_text']) ? $param['target_text'] : 'Оплатить'; ?></a>
			</div>
		
			<div class="userpay_payments_change_hint"><?php echo $options[$default_payment.'_hint']; ?></div>
		
		<?php } ?>
			
	<?php } ?>
		
</div>