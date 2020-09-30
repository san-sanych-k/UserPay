<?php

class formUserpayEditBalance extends cmsForm {
	
	public function init(){
	
		return array(
		
			array(
				'type' => 'fieldset',
				'childs' => array(
				
					new fieldNumber('amount', array(
						'title' => 'Сумма перевода',
						'hint' => 'При отрицательной сумме средства будут списаны со счета',
					)),
				
					new fieldNumber('user_id', array(
						'title' => 'ID пользователя'
					)),
					
					new fieldList('group_id', array(
						'title' => 'Группе пользователей',
						'hint' => 'Если преддущее поле ID заполнено - данное поле будет проигнорировано',
						'generator' => function ($data) {
							
							$groups = cmsCore::getModel('users')->getGroups();
							
							$items[''] = 'Любая группа';

							if ($groups) {
                                foreach ($groups as $groups) {                                    
                                    $items[$groups['id']] = $groups['title'];
                                }
                            }

                            return $items;
							
						}
						
						)
					),
					
					new fieldString('comment', array(
						'title' => 'Комментарий для платежа',
						'default' => 'Изменение баланса счета',
						'rules' => array(
							array('required'),
						)
					)),
 					
				)
				
			),
			
		);
		
	}
	
}
