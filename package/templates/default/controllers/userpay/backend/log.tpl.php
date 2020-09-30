<?php
    $this->addJS('templates/default/js/jquery-ui.js');
    $this->addJS('templates/default/js/jquery-cookie.js');
    $this->addJS('templates/default/js/datatree.js');
    $this->addCSS('templates/default/css/datatree.css');
    $this->addJS('templates/default/js/admin-content.js');

	$this->addBreadcrumb('Список входящих транзакций платежей');
	$this->setPageTitle('Список входящих транзакций платежей');

$this->renderGrid($this->href_to('log_ajax'), $grid);