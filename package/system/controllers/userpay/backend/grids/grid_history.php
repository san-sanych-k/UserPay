<?php

function grid_history($controller) {
    
	$options = array(
        'is_pagination' => true,
		'is_sortable' => true,
		'order_by' => 'id',
        'order_to' => 'desc',
    );

    $columns = array(
        'id' => array(
            'title' => '#',
            'width' => 30,
			'filter' => 'exact',
        ),
		'user_id' => array(
            'title' => 'ID',
			'filter' => 'exact',
			'width' => 30,
        ),
		'amount' => array(
            'title' => 'Сумма',
			'filter' => 'exact',
			'width' => 100,
			'handler' => function ($field){
				return '<span style="color: '.(html_signed_class($field) == 'positive' ? 'green' : 'red') . '">' . html_signed_num($field) . '</span>';
			}
        ),
        'is_pay' => array(
            'title' => 'Оплата',
			'filter' => 'exact',
			'flag' => true,
			'width' => 100,
        ),
		'title' => array(
            'title' => 'Описание операции',
			'filter' => 'like',
        ),
		'date_pub' => array(
            'title' => 'Дата',
			'width' => 150,
			'filter' => 'date',
			'handler' => function ($field){
				return html_date($field, true);
			}
        ),
    );

    return array(
        'options' => $options,
        'columns' => $columns,
        'actions' => false
    );

}
