<?php
	$this->addBreadcrumb('Перевод пользователю');
	$this->setPageTitle('Перевод пользователю');
?>

<h2>Сделать перевод пользователю</h2>

<?php

	$this->renderForm($form, $item, array(
        'action' => '',
        'method' => 'post'
    ), $errors);