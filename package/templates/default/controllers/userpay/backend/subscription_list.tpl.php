<?php

    $this->addJS('templates/default/js/jquery-ui.js');
    $this->addJS('templates/default/js/jquery-cookie.js');
    $this->addJS('templates/default/js/datatree.js');
    $this->addCSS('templates/default/css/datatree.css');
    $this->addJS('templates/default/js/admin-content.js');

    $this->setPageTitle('Активные подписки');
	$this->addBreadcrumb('Активные подписки');

	$this->addToolButton(array(
		'class' => 'tree_folder',
		'title' => 'Типы подписок',
		'href'  => $this->href_to('subscription')
	));

?>

<h2>Активные подписки</h2>

<?php $this->renderGrid($this->href_to('subscription_list_ajax'), $grid);