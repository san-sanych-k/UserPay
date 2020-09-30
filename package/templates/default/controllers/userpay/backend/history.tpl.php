<?php
    $this->addJS('templates/default/js/jquery-ui.js');
    $this->addJS('templates/default/js/jquery-cookie.js');
    $this->addJS('templates/default/js/datatree.js');
    $this->addCSS('templates/default/css/datatree.css');
    $this->addJS('templates/default/js/admin-content.js');

	$this->addBreadcrumb('Список операций пользователей');
	$this->setPageTitle('Список операций пользователей');

$this->renderGrid($this->href_to('history_ajax'), $grid);