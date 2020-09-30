<?php

	$this->setPageTitle('История платежей', $profile['nickname']);
	$this->addBreadcrumb(LANG_USERS, href_to('users'));
	$this->addBreadcrumb($profile['nickname'], href_to('users', $profile['id']));
	$this->addBreadcrumb('История платежей');

	if ($user->is_admin) {

    	$this->addToolButton(array(
	        'class' => 'edit',
	        'title' => 'Изменить баланс',
	        'href'  => href_to('admin', 'controllers', array('edit', 'userpay', 'edit_balance')).'?user_id='.$profile['id']
	    ));

    }

?>

<div class="userpay_balance_block">
	<span class="field">Баланс: <span class="count"><?php echo $profile['balance']; ?></span> <?php echo $this->controller->options['curr_short']; ?></span>
	<span class="add_balance">
		<?php echo $this->controller->formModal( array('url' => href_to('userpay', 'form').'?order_id='.$profile['id'].'&amp;order_name=Пополнение баланса: '.$profile['nickname'].'&no_list=balance', 'title' => 'Пополнение: '.$profile['nickname'], 'button_text' => '+ Пополнить') ); ?>
	</span>
</div>

<?php if($invoices){ ?>

	<h2>Неоплаченные счета</h2>

	<table class="history">
		<tr>
			<th>Назначение оплаты</th>
			<th width="100">К оплате</th>
			<th width="100">&nbsp;</th>
		</tr>
			<?php foreach ($invoices as $key => $value) { ?>
			<tr>
				<td><?php echo $value['data_item']['title']; ?></td>
				<td><?php echo $value['amount']; ?></td>
				<td>
					<?php echo $this->controller->formModal(array('url'=>href_to('userpay', 'form').'?order_id=inv_'.$key.'&amp;amount='.$value['amount'].'&amp;fix_amount=1&amp;order_name='.$value['data_item']['title'], 'title'=>$value['data_item']['title'], 'button_text'=>'Оплатить')); ?>
				</td>
			</tr>
			<?php } ?>
	</table>

<?php } ?>

<?php if($subscriptions){ ?>

	<h2>Мои подписки</h2>

	<table class="history">
		<tr>
			<th>Заголовок</th>
			<th>Описание</th>
			<th width="120">Действует до:</th>
			<th width="100">&nbsp;</th>
		</tr>
			<?php foreach ($subscriptions as $key => $value) { ?>
			<tr>
				<td><?php echo $value['subs']['title']; ?></td>
				<td><?php echo $value['subs']['desc']; ?></td>
				<td><?php echo html_date($value['end_date'], true); ?></td>
				<td align="right">
					<?php echo $this->controller->formModal(array('url'=>href_to('userpay', 'form').'?order_id=sbs_'.$value['subs']['id'].'_'.$profile['id'].'&amp;amount='.$value['subs']['price'].'&amp;fix_amount=1&amp;order_name=Подписка: '.$value['subs']['title'], 'title'=>'Подписка: '.$value['subs']['title'], 'button_text'=>'Продлить')); ?>
				</td>
			</tr>
			<?php } ?>
	</table>

<?php } ?>

<?php if($items) { ?>

	<h2>История платежей</h2>

	<table class="history">
		<tr>
			<th width="32">#</th>
			<th width="120">Дата</th>
			<th>Операция</th>
			<th width="100">Сумма</th>
		</tr>
		<?php foreach ($items as $key => $value) { ?>
		<tr>
			<td><?php echo $key; ?></td>
			<td><?php echo html_date($value['date_pub'], true); ?></td>
			<td><?php echo html($value['title']); ?></td>
			<td class="<?php echo html_signed_class($value['amount']); ?>"><?php echo html_signed_num($value['amount']); ?></td>
		</tr>
		<?php } ?>
	</table>

	<?php if($total > $perpage) { ?>
		<?php echo html_pagebar($page, $perpage, $total); ?>	
	<?php } ?>

<?php } ?>