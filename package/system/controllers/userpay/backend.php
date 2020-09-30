<?php

class backendUserpay extends cmsBackend {
	
	public $useDefaultOptionsAction = true;

	public function actionIndex(){
		
		$this->redirectToAction('options');
		
	}

	public function getBackendMenu() {
		
		return array(
			array(
				'title' => 'Настройки',
				'url' => href_to($this->root_url, 'options')
			),
			array(
				'title' => 'Перевод',
				'url' => href_to($this->root_url, 'edit_balance')
			),
			array(
				'title' => 'Подписки',
				'url' => href_to($this->root_url, 'subscription')
			),
			array(
				'title' => 'Транзакции',
				'url' => href_to($this->root_url, 'log')
			),
			array(
				'title' => 'Операции',
				'url' => href_to($this->root_url, 'history')
			),
			array(
				'title' => 'Реф.переходы',
				'url' => href_to($this->root_url, 'partner')
			),
		);
		
	}
	
}
