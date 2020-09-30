<?php

class actionUserpayYandex extends cmsAction {

    public function run($do = false, $param = false) {

    	$core = cmsCore::getInstance();

    	if (!$do) {
    		cmsCore::error404();
    	}

    	else if ($do == 'process') {

    		$post = $_POST;

    		$secret_key = $this->options['yandex_secret'];
	 
			$sha1 = sha1($post['notification_type'].'&'.$post['operation_id'].'&'.$post['amount'].'&643&'.$post['datetime'].'&'.$post['sender'].'&'. $post['codepro'].'&'.$secret_key.'&'.$post['label']);
			
			if ($sha1 != $post['sha1_hash'])
				exit();
			
			$is_dubl = $this->model->isDubl('yandex', $post['operation_id']);
		
			if(!$is_dubl) {
			
				if ($post['unaccepted']=='false') {

					$log = array(
						'system' => 'yandex', 
						'tr_id' => $post['operation_id'], 
						'tr_subject' => cmsModel::arrayToYaml($post)
					);
					
					$this->model->addLog($log);
					
					if ($post['label']) {

						$amount = round( $post['withdraw_amount'] / $this->options['yandex_in'], 2 ) - $this->options['yandex_fee'];

						$param = array(
							'system' => 'yandex',
							'order_id' => $post['label'],
							'amount' => $amount,
							'post' => $post
						);

						cmsEventsManager::hookAll('after_userpay', $param);
						
					}
					
				}
				
			}

		}

		else if ($do == 'success') {
			
			cmsUser::addSessionMessage('Платеж успешно завершен.', 'success');
			if($back_url = cmsUser::getSession('userpay_back_url', true))
				$this->redirectTo($back_url);
			else
				$this->redirectToHome();
			
		}

     	else if ($do == 'payment') {

     		//$param['success_url'] - страница для возврата пользователя
			//$param['wallet'] - номер ЯД
			//$param['order_name'] - заголовок платежа
			//$param['order_id'] - идентификатор платежа
			//$param['amount'] - сумма платежа

			$success_url = isset($param['success_url']) ? $param['success_url'] : false;
			$wallet = isset($param['wallet']) ? $param['wallet'] : $this->options['yandex_wallet'];

			$get_data = array(
				'receiver' => $wallet,
				'formcomment' => $param['order_name'],
				'short-dest' => $param['order_name'],
				'label' => $param['order_id'],
				'quickpay-form' => 'shop',
				'targets' => $param['order_name'],
				'paymentType' => 'PC',
				'sum' => $param['amount'],
				'need-fio' => 'false',
				'need-email' => 'false',
				'need-phone' => 'false',
				'need-address' => 'false'
			);

			if ($success_url)
				$get_data['successURL'] = $success_url;

			$data['link'] = 'https://money.yandex.ru/quickpay/confirm.xml';
			$data['result_amount'] = $param['amount'];
			$data['curr_short'] = $this->options['yandex_curr_short'];
			$data['data'] = $get_data;
			$data['method'] = 'post';
			
			return $data;

		}

		else if ($do == 'options') {

			$options = array(
						new fieldString('yandex_wallet', array(
							'title' => 'Номер Яндекс.Кошелька',
						)),
						new fieldString('yandex_secret', array(
							'title' => 'Секретный ключ',
							'hint' => 'См. <a target="_blank" href="https://money.yandex.ru/myservices/online.xml">HTTP Уведомления</a>'
						))
					);

			return $options;

		}

		else {

			cmsCore::error404();

		}

    }
 
}
