<?php

function grid_subscription_list($controller) {
	
    $options = array(
        'is_pagination' => true,
		'is_sortable' => true,
		'order_by' => 'id',
        'order_to' => 'desc',
    );

    $columns = array(
        'id' => array(
            'title' => 'id',
            'width' => 30,
			'filter' => 'exact'
        ),
		'user_nickname' => array(
            'title' => 'Пользователь',
			'filter' => 'like',
			'href' => href_to('users', "{user_id}"),
			'filter_by' => 'u.nickname',
        ),
		'date_pub' => array(
            'title' => 'Дата',
			'width' => 110,
			'filter' => 'date',
			'handler' => function ($field){
				return html_date($field, true);
			}
        ),
		'end_date' => array(
            'title' => 'Действует до',
			'width' => 110,
			'filter' => 'date',
			'handler' => function ($field){
				return html_date($field, true);
			}
        ),
		'subs' => array(
            'title' => 'Подписка',
			'filter' => 'like',
			'handler' => function ($field){
				return $field['title'];
			}
			
        ),
    );
	
	$actions = array(
		array(
            'title' => 'Отменить подписку',
            'class' => 'delete',
            'href' => href_to($controller->root_url, 'subscription_item_delete', array('{user_id}', '{plan_id}')),
            'confirm' => 'Отменить подписку пользователя?',
        )
    );

    return array(
        'options' => $options,
        'columns' => $columns,
		'actions' => $actions
    );
    
}
