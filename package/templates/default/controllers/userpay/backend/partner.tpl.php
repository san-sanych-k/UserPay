<?php
    $this->addJS('templates/default/js/jquery-ui.js');
    $this->addJS('templates/default/js/jquery-cookie.js');
    $this->addJS('templates/default/js/datatree.js');
    $this->addCSS('templates/default/css/datatree.css');
    $this->addJS('templates/default/js/admin-content.js');

	$this->addBreadcrumb('Переходы по партнерским ссылкам');
	$this->setPageTitle('Переходы по партнерским ссылкам');

$this->renderGrid($this->href_to('partner_ajax'), $grid);