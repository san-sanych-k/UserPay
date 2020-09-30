<?php

	$template->addControllerCSS('donate', 'userpay');

	if($value > 1){
		$amount_summ = $items ? array_sum(array_collection_to_list($items, 'id', 'amount')) : 0;
		$percent = floor($amount_summ  / $value * 100);
	}

?>

<?php echo $this->getOption('top_text'); ?>

<?php if(isset($amount_summ)) { ?>

	<div class="meter">
		<span style="width: <?php echo $percent; ?>%"></span>
		<div><?php echo $percent; ?>% / <?php echo $amount_summ; ?> из <?php echo $value; ?></div>
	</div>

<?php } ?>

<div class="donate">

	<?php echo $userpay->formModal(array('url'=>$link_href, 'title'=>$this->getOption('target_text'), 'button_text'=>$this->getOption('button_text'), 'style'=>$button_bg_color.$button_text_color)); ?>

	<?php if(!empty($this->getOption('log_days')) && $items) { ?>

	<?php $n = 0; ?>

		<?php foreach ($items as $key => $value) { ?>
			<?php $n++; ?>
			<div class="item">
				<span><?php echo isset($value['user_nickname']) ? '<a href="'.href_to('users', $value['user_id']).'">'.html_avatar_image($value['user_avatar'], 'micro', '') . $value['user_nickname'].'</a>' : 'Гость'; ?></span>
				<span class="amount">+<?php echo ceil($value['amount']); ?> <?php echo $userpay->options['curr_short']; ?></span>
			</div>
			<? if(!empty($this->getOption('log_count')) && $n >= $this->getOption('log_count')) { break; } ?>
		<?php } ?>

	<?php } ?>

</div>
<div style="clear:both;"></div>