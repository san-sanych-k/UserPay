<?php
    $this->addJS('templates/default/js/jquery-ui.js');
    $this->addJS('templates/default/js/jquery-cookie.js');
    $this->addJS('templates/default/js/datatree.js');
    $this->addCSS('templates/default/css/datatree.css');
    $this->addJS('templates/default/js/admin-content.js');

	$this->addBreadcrumb('Подписки');

	$this->addToolButton(array(
		'class' => 'tree_folder',
		'title' => 'Активные подписки',
		'href'  => $this->href_to('subscription_list')
	));
	
	$this->addToolButton(array(
		'class' => 'add',
		'title' => 'Добавить подписку',
		'href'  => $this->href_to('subscription_add')
	));

$this->renderGrid($this->href_to('subscription_ajax'), $grid);