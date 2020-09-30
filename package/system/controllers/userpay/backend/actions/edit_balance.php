<?php

class actionUserpayEditBalance extends cmsAction {

    public function run(){

    	$errors = false;

    	$form = $this->getForm('edit_balance');

    	$is_submitted = $this->request->has('submit');
		
		$item = $form->parse($this->request, $is_submitted);

		if ($is_submitted){
			
			$errors = $form->validate($this, $item);
			
			if(!$item['user_id'] && !$item['group_id']) {
				
				$errors['user_id'] = $errors['group_id'] = 'Одно из полей должно быть заполнено';
				
			}
			
			//Если указан Id пользователя, проверяем наличие пользователя
			if($item['user_id']) {
				
				$user = cmsCore::getModel('users')->getUser($item['user_id']);
				
				if(!$user) $errors['user_id'] = 'Пользователь с данным ID не найден';
				
			} else {

				$users_model = cmsCore::getModel('users');

				$users = $users_model->filterGroup($item['group_id'])->getUsers();

				if(!$users) $errors['group_id'] = 'В выбранной группе нет пользователей';

			}
			
			if (!$errors){
				
				if($item['amount']) {

					if($item['user_id']){

						//Изменяем баланс
						$this->model->incrementUserBalance($item['user_id'], $item['amount']);
						//Делаем запись в историю
						$this->model->addUserpayHistory( array( 'user_id' => $item['user_id'], 'amount' => $item['amount'], 'title' => $item['comment']));

					} else {

						foreach ($users as $key => $value) {
							
							//Изменяем баланс
							$this->model->incrementUserBalance($value['id'], $item['amount']);
							//Делаем запись в историю
							$this->model->addUserpayHistory( array( 'user_id' => $value['id'], 'amount' => $item['amount'], 'title' => $item['comment']));

						}

					}
				
				}
				
				cmsUser::addSessionMessage('Операция выполнена успешно', 'success');
					
				if($back_to_profile = $this->request->get('user_id')) {
						
					$this->redirectTo('users/' . $back_to_profile, 'pay');
						
				} else {
					$this->redirectToAction('edit_balance');
				}
			
			}
			
			if ($errors){
				cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
			}
			
		}

        return cmsTemplate::getInstance()->render('backend/edit_balance', array(
            'errors' => $errors,
            'form' => $form,
            'item' => $item
        ));

    }

}
