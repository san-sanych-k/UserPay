<?php

    $this->setPageTitle('Добавление/Изменение подписки');
    $this->addBreadcrumb('Добавление/Изменение подписки');

?>

<h2>Добавление/Изменение подписки</h2>

<?php $this->renderForm($form, $item, array(
        'action' => '',
        'method' => 'post'
    ), $errors);