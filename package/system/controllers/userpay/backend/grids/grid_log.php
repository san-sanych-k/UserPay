<?php

function grid_log($controller) {
    
	$options = array(
        'is_pagination' => true,
		'is_sortable' => true,
		'order_by' => 'id',
        'order_to' => 'desc',
    );

    $columns = array(
        'id' => array(
            'title' => 'ID',
            'width' => 30,
			'filter' => 'exact',
        ),
		'system' => array(
            'title' => 'Система',
			'filter' => 'like',
			'width' => 100,
			'handler' => function($field) {
				return ucfirst($field);
			}
        ),
		'tr_id' => array(
            'title' => 'ID Платежа',
			'filter' => 'exact',
			'width' => 100,
        ),
		'tr_subject' => array(
            'title' => 'Входящие данные',
			'filter' => 'like',
			'handler' => function ($field){
				$field = cmsModel::yamlToArray($field);
				$list = '';
				if($field) {
					foreach($field as $key=>$field){
						if(is_array($field)) $field = json_encode($field);
						$list .= '<b style="color: #999;">' . $key . ':</b> ' . $field . '<br />';
					}
				}
				return $list;
			}
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
