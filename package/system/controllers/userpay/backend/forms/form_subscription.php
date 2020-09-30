<?php

class formUserpaySubscription extends cmsForm {
	
	public function init(){
	
		return array(
		
			array(
				'type' => 'fieldset',
				'title' => 'Подписка',
				'childs' => array(
					
					new fieldCheckbox('is_active', array(
						'title' => 'Активна',
						'default' => false,
					)),
				
					new fieldString('title', array(
						'title' => 'Название подписки',
						'rules' => array(
							array('required'),
						)
					)),
					
					new fieldText('desc', array(
						'title' => 'Описание подписки',
						'rules' => array(
							array('required'),
						)
					)),
					
					new fieldListMultiple('groups', array(
						'title' => 'Присвоить группу пользователей',
						'generator' => function ($data) {
							
							$groups = cmsCore::getModel('users')->getGroups();

							if ($groups) {
                                foreach ($groups as $groups) {
                                    $items[$groups['id']] = $groups['title'];
                                }
                            }

                            return $items;
							
						},
						
					)),
					
					new fieldNumber('period', array(
						'title' => 'Длительность подписки',
						'default' => 7,
						'rules' => array(
							array('required'),
						)
					)),
					
					new fieldList('period_type', array(
						'title' => 'Период',
						'items' => array(
							'MINUTE' => 'Минут',
							'HOUR' => 'Часов',
							'DAY' => 'Дней',
							'WEEK' => 'Недель',
							'MONTH' => 'Месяцев',
							'YEAR' => 'Лет',
						),
						'default' => 'DAY'
						
					)),
					
					new fieldNumber('price', array(
						'title' => 'Стоимость',
						'default' => 100,
						'rules' => array(
							array('required'),
						)
					)),
 					
				)
				
			),
			
		);
		
	}	
	
}
