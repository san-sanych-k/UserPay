<?php

function grid_subscription($controller) {
    
    $options = array(
        'is_pagination' => false,
		'is_sortable' => false,
        'is_filter' => false,
    );

    $columns = array(
        'id' => array(
            'title' => '#',
            'width' => 30,
        ),
		'title' => array(
            'title' => 'Название',
        ),
		'desc' => array(
            'title' => 'Описание',
        ),
		'groups' => array(
            'title' => 'Группы',
			'handler' => function ($field){
				$list = false;
				if($field) {
					foreach($field as $group){
						$gr = cmsCore::getModel('users')->getGroup($group);
						$list .= $gr['title'] . '<br /> ';
					}
				}
				return $list;
			}
        ),
		'period' => array(
            'title' => 'Время',
        ),
		'period_title' => array(
            'title' => 'Период',
        ),
		'price' => array(
            'title' => 'Цена',
        ),
		'is_active' => array(
            'title' => 'Активна',
			'width' => 50,
			'flag' => true,
			'flag_toggle' => href_to($controller->root_url, 'toggle_item', array('{id}', 'userpay_subscription_list', 'is_active')),
        ),
    );

    $actions = array(
		array(
            'title' => 'Редактировать',
            'class' => 'edit',
            'href' => href_to($controller->root_url, 'subscription_edit', array('{id}')),
        ),
		array(
            'title' => 'Удалить',
            'class' => 'delete',
            'href' => href_to($controller->root_url, 'subscription_delete', array('{id}')),
            'confirm' => 'Удалить подписку?',
        )
    );

    return array(
        'options' => $options,
        'columns' => $columns,
        'actions' => $actions
    );

}
