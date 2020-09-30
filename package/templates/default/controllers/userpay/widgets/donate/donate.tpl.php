<?php

	$this->addControllerCSS('donate', 'userpay');
	
	$user = cmsUser::getInstance();

	$link_href = href_to('userpay', 'form?order_id=uwd_'.$widget->options['id'].'_'.$user->id.'&amount='.$widget->options['amount'].'&fix_amount='.$widget->options['fix_amount'].'&payments_list_style='.$widget->options['payments_list_style'].'&order_name='.$widget->options['target_text']);

	$userpay = cmsCore::getController('userpay');

	$button_bg_color = empty($widget->options['button_bg_color']) ? '' : 'background:'.$widget->options['button_bg_color'].';';
	$button_text_color = empty($widget->options['button_text_color']) ? '' : 'color:'.$widget->options['button_text_color'].';';

	if($widget->options['amount_summ']){
		$amount_summ = $items ? array_sum(array_collection_to_list($items, 'id', 'amount')) : 0;
		$percent = floor($amount_summ  / $widget->options['amount_summ'] * 100);
	}

?>

<div class="uwd uwd_<?php echo $widget->id; ?>">

	<?php if(!empty($widget->options['top_text'])) { ?>
		<div class="description"><?php echo $widget->options['top_text']; ?></div>
	<?php } ?>

	<?php echo $userpay->formModal(array('class'=>'donate', 'url'=>$link_href, 'title'=>$widget->title, 'button_text'=>$widget->options['button_text'], 'style'=>$button_bg_color.$button_text_color)); ?>

	<?php if(isset($amount_summ)) { ?>

		<div class="meter">
			<span style="width: <?php echo $percent; ?>%"></span>
			<div>Собрано <?php echo $percent; ?>% / <?php echo $amount_summ; ?> из <?php echo $widget->options['amount_summ']; ?></div>
		</div>

	<?php } ?>

	<?php if(!empty($widget->options['log_days']) && $items) { ?>

	<div class="list">
		<?php foreach ($items as $key => $value) { ?>
			<div class="item">
				<span><?php echo isset($value['user_nickname']) ? '<a href="'.href_to('users', $value['user_id']).'">'.$value['user_nickname'].'</a>' : 'Гость'; ?></span>
				<span class="amount"><?php echo ceil($value['amount']); ?> <?php echo $userpay->options['curr_short']; ?></span>
				<span class="dates"><?php echo date('d.m', strtotime($value['date_pub'])); ?></span>
			</div>
		<?php } ?>
	</div>

	<?php } ?>

</div>