<?php

	$this->setPageTitle('Партнерская программа', $profile['nickname']);
	$this->addBreadcrumb(LANG_USERS, href_to('users'));
	$this->addBreadcrumb($profile['nickname'], href_to('users', $profile['id']));
	$this->addBreadcrumb('Партнерская программа');

?>

<?php echo $this->controller->options['ref_intro']; ?>

<?php if (isset($referer)) { ?>
	<h3>Вы приглашены на сайт пользователем: <a href="<?php echo href_to('users', $referer['id']); ?>"><?php echo html($referer['nickname']); ?></a></h3>
<?php } ?>

<h3>Cсылка для привлечения патнеров:</h3>
<?php if(count($links)>1) { ?>
	<p class="up_pre_link">Вы можете использовать любую из этих ссылок.</p>
<?php } ?>

<?php foreach ($links as $key => $value) { ?>
	<div>
		<a href="<?php echo href_to($value, $profile['id']); ?>"><?php echo href_to_abs($value, $profile['id']); ?></a>
	</div>
<?php } ?>

<p class="up_post_link">Также, после ссылки вы можете добавить автопереход на любую нужную сраницу сайта.<br />Пример: <?php echo href_to_abs($value, $profile['id'].'?page=auth/register'); ?> - на страницу регистрации.</p>

<h3>Уровни партнерской программы:</h3>

<?php foreach ($step as $key => $value) { ?>
	<div><?php echo $key+1; ?>-ый уровень: <?php echo $value; ?>%</div>	
<?php } ?>

<h3>Привлеченные вами пользователи:</h3>

<table class="up_partner_tbl">
	<tr>
		<th width="120">Регистрация</th>
		<th>Пользователь</th>
		<th width="80">Уровень</th>
		<th width="80">Ваш доход</th>
	</tr>
	<?php if($partner) { ?>
	<?php foreach ($partner as $key => $value) { ?>
	<tr>
		<td align="center"><?php echo html_date($value['date_reg']); ?></td>
		<td><a href="<?php echo href_to('users', $value['user_id']); ?>"><?php echo $value['user_nickname']; ?></a></td>
		<td align="center"><?php echo $value['level']; ?></td>
		<td align="center"><?php echo $value['ref_amount']; ?></td>
	</tr>
	<?php } ?>
	<?php } else { ?>
	<tr>
		<td align="center" colspan="4">У вас привлеченных пользователей пока нет.</td>
	</tr>
	<?php } ?>
</table>

<?php if($total > $perpage) { ?>
	<?php echo html_pagebar($page, $perpage, $total); ?>	
<?php } ?>

<h3>25 последних переходов по вашей партнерской ссылке. Всего: <?php echo $partner_hits_count; ?></h3>

<table class="up_partner_tbl">
	<tr>
		<th width="120">Дата</th>
		<th>Ссылка</th>
		<th>Источник</th>
	</tr>
	<?php if($partner_hits) { ?>
	<?php foreach ($partner_hits as $key => $value) { ?>
	<tr>
		<td align="center"><?php echo html_date($value['date_pub'], true); ?></td>
		<td><?php echo $value['link'] ? $value['link'] : '-'; ?></td>
		<td style="word-break:break-all;"><?php echo $value['page'] ? string_short($value['page'], 500, ' ...') : '-'; ?></td>
	</tr>
	<?php } ?>
	<?php } else { ?>
	<tr>
		<td align="center" colspan="3">Переходов пока нет.</td>
	</tr>
	<?php } ?>
</table>