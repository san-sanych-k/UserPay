<?php

	$this->addControllerCSS('subscription', 'userpay');

	$user = cmsUser::getInstance();

	$link = href_to('userpay', 'form').'?order_id=sbs_'.$plan['id'].'_'.$user->id.'&amp;amount='.$plan['price'].'&amp;fix_amount=1&amp;order_name=Подписка: '.$plan['title'];

?>

<div class="sbs sbs_<?php echo $widget->id; ?>">

	<div class="description"><?php echo !empty($widget->options['content']) ? $widget->options['content'] : $plan['desc']; ?></div>

	<div class="s_button">
		<?php if($user->id){ ?>
			<?php echo $userpay->formModal(array('url'=>$link, 'title'=>'Подписка: '.$plan['title'], 'button_text'=>'Подписаться')); ?>
		<?php } else { ?>
			<a class="ajax-modal" href="<?php echo href_to('auth', 'login'); ?>">Подписаться</a>
		<?php } ?>
	</div>

</div>