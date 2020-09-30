<?php

class actionUserpayWebmoney extends cmsAction {

    public function run($do = false, $param = false) {

    	$core = cmsCore::getInstance();

    	if (!$do) {
    		cmsCore::error404();
    	}

    	//Ошибка платежа
		else if ($do == 'fail') {
			
			cmsUser::addSessionMessage('Ошибка при проведении платежа.', 'error');
			if($back_url = cmsUser::sessionGet('userpay_back_url', true))
				$this->redirect(href_to($back_url));
			else
				$this->redirectToHome();
			
		}
		
		//Оплата
		else if ($do == 'process') {

			$inner = $core->request->getAll();
			
			if($inner['LMI_PREREQUEST'] == 1) {
			
				header('Content-type: text/html; charset=iso-8859-1');
				echo 1;
				$this->halt();
			
			} else {
				
				$hash = $inner['LMI_PAYEE_PURSE'] . $inner['LMI_PAYMENT_AMOUNT'] . $inner['LMI_PAYMENT_NO'] . $inner['LMI_MODE'] . $inner['LMI_SYS_INVS_NO'] . $inner['LMI_SYS_TRANS_NO'] . $inner['LMI_SYS_TRANS_DATE'] . $this->options['webmoney_secret'] . $inner['LMI_PAYER_PURSE'] . $inner['LMI_PAYER_WM'];
				$hash = strtoupper(hash('sha256', $hash));
				
				if ($hash != $inner['LMI_HASH']) exit();

				$is_dubl = $this->model->isDubl('webmoney', $inner['LMI_PAYMENT_NO']);

				if(!$is_dubl) {

					$log = array(
						'system' => 'webmoney', 
						'tr_id' => $inner['LMI_PAYMENT_NO'], 
						'tr_subject' => cmsModel::arrayToYaml($inner)
					);
					$this->model->addLog($log);
						
					if($inner['LMI_PAYMENT_AMOUNT']) {

						$amount = round( $inner['LMI_PAYMENT_AMOUNT'] / $this->options['webmoney_in'], 2 ) - $this->options['webmoney_fee'];

						$param = array(
							'system' => 'webmoney',
							'order_id' => $inner['VALUE'],
							'amount' => $amount,
							'post' => $post
						);

						cmsEventsManager::hookAll('after_userpay', $param);
							
					}
					
					return true;

				}
				
			}
			
			cmsCore::error404();
			
		}
		
		//Оплата
		else if ($do == 'success') {
			
			cmsUser::addSessionMessage('Платеж успешно завершен.', 'success');
			if($back_url = cmsUser::sessionGet('userpay_back_url', true))
				$this->redirect(href_to($back_url));
			else
				$this->redirectToHome();
			
		}

     	else if ($do == 'payment') {

			$get_data = array(
				'LMI_PAYEE_PURSE' => $this->options['webmoney_wallet'],
				'LMI_PAYMENT_AMOUNT' => $param['amount'],
				'LMI_PAYMENT_NO' => time(),
				'VALUE' => $param['order_id'],
				'LMI_SIM_MODE' => !empty($param['webmoney_sim']) ? 1 : 0,
				'LMI_PAYMENT_DESC_BASE64' => base64_encode($param['order_name']),
			);

			$data['link'] = 'https://merchant.webmoney.ru/lmi/payment.asp';
			$data['result_amount'] = $param['amount'];
			$data['curr_short'] = $this->options['webmoney_curr_short'];
			$data['data'] = $get_data;
			$data['method'] = 'post';
			
			return $data;

		}

		else if ($do == 'options') {

			$options = array(
						new fieldString('webmoney_wallet', array(
	                        'title' => 'Кошелек Webmoney',
	                    )),
						new fieldString('webmoney_secret', array(
	                        'title' => 'Секретный ключ',
	                    )),
						new fieldCheckbox('webmoney_sim', array(
	                        'title' => 'Тестовый режим'
	                    ))
						
					);

			return $options;

		}

		else {

			cmsCore::error404();

		}

    }
 
}
