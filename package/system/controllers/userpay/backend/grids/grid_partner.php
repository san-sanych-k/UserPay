<?php

function grid_partner($controller) {
    
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
            'title' => 'User',
			'filter' => 'exact',
			'width' => 30,
        ),
        'auth' => array(
            'title' => 'Auth',
            'filter' => 'exact',
            'width' => 30,
        ),
        'ip' => array(
            'title' => 'IP',
            'filter' => 'like',
        ),
        'link' => array(
            'title' => 'Ссылка',
            'filter' => 'like',
        ),
        'page' => array(
            'title' => 'Реф.страница',
            'filter' => 'like',
            'handler' => function ($field){
                return '<div style="word-break:break-all;">'.$field.'</div>';
            }
        ),
        'cookie' => array(
            'title' => 'Повтор',
            'filter' => 'exact',
            'flag' => true,
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
