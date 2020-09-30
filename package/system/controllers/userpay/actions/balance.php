<?php

class actionUserpayBalance extends cmsAction {

    public function run($do = false, $param = false) {

    	$core = cmsCore::getInstance();

    	if (!$do) {
    		cmsCore::error404();
    	}

    	else if ($do == 'success') {

     		$user = cmsUser::getInstance();

     		if (!$user->id)
     			cmsUser::goLogin();

     		$post = $core->request->getAll();

			if (!$post['amount'] || $post['amount']<0 || !is_numeric($post['amount']) || !$post['order_id'] || !$post['hash']) {
     			cmsUser::addSessionMessage('Отсутствуют данные платежа', 'error');
     			$this->goBackUrl();
     		}

     		if ($post['hash'] != cmsUser::sessionGet('userpay_hash', true)) {
     			cmsUser::addSessionMessage('Ошибка платежа', 'error');
     			$this->goBackUrl();
     		}

     		if($this->model->getUserBalance($user->id) < $post['amount']) {
     			cmsUser::addSessionMessage('На вашем счете недостаточно средств.', 'error');
     			$this->goBackUrl();
     		}

     		$this->model->decrementUserBalance($user->id, $post['amount']);

     		$log = array(
				'system' => 'balance', 
				'tr_id' => time(), 
				'tr_subject' => cmsModel::arrayToYaml($post)
			);
					
			$this->model->addLog($log);
					
			$amount = round( $post['amount'] / $this->options['balance_in'], 2 ) - $this->options['balance_fee'];

			$param = array(
				'system' => 'balance',
				'order_id' => $post['order_id'],
				'amount' => $amount,
				'post' => $post
			);

			cmsEventsManager::hookAll('after_userpay', $param);

			cmsUser::addSessionMessage('Платеж успешно завершен', 'success');
     		$this->goBackUrl();

		}

    	else if ($do == 'payment') {

    		$link = href_to_abs('userpay', 'balance', 'success');

    		$get_data = array(
				'order_id' => $param['order_id'],
				'amount' => $param['amount']
			);

			
			$data['result_amount'] = $param['amount'];
			$data['curr_short'] = $this->options['balance_curr_short'];

			$user = cmsUser::getInstance();

			if($this->model->getUserBalance($user->id) < $param['amount']) {
				$data['error'] = 'На вашем счете недостаточно средств.';
			} else {
				$userpay_hash = md5(microtime().rand(0,9999));
				cmsUser::sessionSet('userpay_hash', $userpay_hash);
				$get_data['hash'] = $userpay_hash;
			}

			$data['link'] = $link.'?'.http_build_query($get_data);

			return $data;

		}

		else if ($do == 'options') {
			return array();
		}

		else {

			cmsCore::error404();

		}

    }

    public function goBackUrl() {

    	if($back_url = cmsUser::sessionGet('userpay_back_url', true))
			$this->redirect(href_to($back_url));
		else
			$this->redirectToHome();

    }
 
}
