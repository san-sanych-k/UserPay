<?php

class onUserpayUserRegistered extends cmsAction {
	
	function run($user) {

		//Баланс при регистрации
		if(!empty($this->options['reg_bonus']) && $this->options['reg_bonus']>0) {
			$this->model->incrementUserBalance($user['id'], $this->options['reg_bonus']);
			$this->model->addUserpayHistory(array('user_id'=>$user['id'], 'amount'=>$this->options['reg_bonus'], 'title'=>'Баланс при регистрации'));
		}
		
		$ref_id = cmsUser::getCookie('ref_id');

		if ($ref_id) {
					
			$ref_user = cmsCore::getModel('users')->getUser($ref_id);

			if ($ref_user) {
						
				cmsUser::unsetCookie('ref_id');
				
				$this->model->update('{users}', $user['id'], array('ref_id' => $ref_id));

				if ($this->options['ref_reg_notify']) {

					$messenger = cmsCore::getController('messages');
					$messenger->addRecipients($this->options['new_reg_notice']);

					$content = 'По вашей партнерской ссылке зарегистрирован новый пользователь: <a href="'.href_to('users', $user['id']).'">'.$user['nickname'].'</a>.';

					$messenger->ignoreNotifyOptions()->sendNoticePM(array(
			            'content' => $content,
			            'options' => array(
			                'is_closeable' => true
			            ),
			            'actions' => array(
			                'view' => array(
			                    'title' => 'Профиль',
			                    'href' => href_to('users', $user['id'])
			                )
			            )
			        ), 'new_ref_reg_notice');

				}

				$this->model->addPartner($user['id']);

				//Баланс при регистрации по реф.ссылке
				if(!empty($this->options['ref_reg_bonus']) && $this->options['ref_reg_bonus']>0) {
					$this->model->incrementUserBalance($user['id'], $this->options['ref_reg_bonus']);
					$this->model->addUserpayHistory(array('user_id'=>$user['id'], 'amount'=>$this->options['ref_reg_bonus'], 'title'=>'Начисление за региcтрации по партнерской ссылке'));
				}

				//Бонус рефереру за регистрацию партнера
				if(!empty($this->options['ref_bonus']) && $this->options['ref_bonus']>0) {
					$this->model->incrementUserBalance($ref_id, $this->options['ref_bonus']);
					$this->model->addUserpayHistory(array('user_id'=>$ref_id, 'amount'=>$this->options['ref_bonus'], 'title'=>'Начисление за регистрацию партнера'));
				}
				
			}
			
		}

		return $user;
		
	}
	
}
