<?php

class onUserpayAfterUserpay extends cmsAction {

    public function run($data){

    	$notice = '<h3>Произведен платеж</h3>
    				<div>Платежная система: <b>'.ucfirst($data['system']).'</b></div>
    				<div>Сумма платежа: <b>'.$data['amount'].' '.$this->options[$data['system'].'_curr_short'].'</b></div>
    				<div>ID платежа: <b>'.$data['order_id'].'</b></div>';
        $this->model->sendAdminNotice($notice);

        $order_id = explode('_', $data['order_id']);

        if (is_numeric($data['order_id'])) {

        	//Обработка патежа с числовым идентификатором
        	//array
        	//order_id - id пользователя
        	//amount - сумма платежа
        	$this->model->incrementUserBalance($data['order_id'], $data['amount']);

        	if($data['order_id'] && in_array('balance', $this->options['ref_pay'])){
        		$this->model->addPartnerBonus($data['order_id'], $data['amount'], !empty($this->options['ref_virtual_bonus']));
        	}

        	//Запись операции в историю
        	//array
        	//user_id - id пользователя
        	//amount - сумма платежа
        	//title - заголовок платежа
        	//is_pay - bool - входяий платеж
        	$in = $data['system'] == 'balance' ? false : true;
        	$this->model->resetFilters()->addUserpayHistory(array('user_id'=>$data['order_id'], 'amount'=>$data['amount'], 'title'=>'Пополнение счета через: '.ucfirst($data['system']), 'is_pay'=>$in));

            $notice = '<h3>Входящий платеж</h3>
            			<div>Зачислено: <b>'.$data['amount'].' '.$this->options[$data['system'].'_curr_short'].'</b></div>';
            $this->model->sendUserNotice($data['order_id'], $notice);

            //Интеграция с компонентом билинг
            $billing = cmsCore::isControllerExists('billing') ? cmsCore::getController('billing') : false;
           	if ($billing) {
           		$this->model->insert('billing_log', array('type'=>1,'amount'=>$data['amount'],'summ'=>$data['amount'],'user_id'=>$data['order_id'],'sender_id'=>$data['order_id'],'status'=>1,'description'=>'Пополнение баланса через: '.ucfirst($data['system'])));
           	}

        } else if($order_id[0] == 'uwd'){ //Обработка виджета Donate

        	if($order_id[2]!=0){
        		$sbs = $this->model->getSubscription(8);
        		$this->model->updateUserSubscription($order_id[2], $sbs);
        	}

            $this->model->insert('userpay_donate', array('target_id'=> $order_id[1], 'user_id' => $order_id[2], 'amount' => $data['amount']));

            $this->model->addUserpayHistory(array('user_id'=>$order_id[2], 'amount'=>$data['amount'], 'title'=>'Оплата счета #'.$data['order_id']));

            if($order_id[2] && in_array('donate', $this->options['ref_pay'])){
        		$this->model->addPartnerBonus($order_id[2], $data['amount'], !empty($this->options['ref_virtual_bonus']));
        	}

        } elseif($order_id[0] == 'sbs'){

        	if( $sbs = $this->model->getSubscription($order_id[1]) ) {
	        	
	        	$result = $this->model->updateUserSubscription($order_id[2], $sbs);

				$this->model->addUserpayHistory( array( 'user_id' => $order_id[2], 'amount' => -$data['amount'], 'title'=>'Оплата подписки: '.$sbs['title'] ) );
				$this->model->sendUserNotice($order_id[2], 'Подписка "'.$sbs['title'].'" успешно оформлена до '.html_date($result, true) );

				if($order_id[2] && in_array('sbs', $this->options['ref_pay'])){
	        		$this->model->addPartnerBonus($order_id[2], $data['amount'], !empty($this->options['ref_virtual_bonus']));
	        	}
			
			}

        } elseif($order_id[0] == 'disk'){

        	if( $file = cmsCore::getModel('disk')->getDiskFile($order_id[1]) ) {

                $valid = $file['price']-$data['amount'] == 0;

        		if($valid) {
	        	
		        	$this->model->addPaidItem(array('controller'=>'disk','item_id'=>$order_id[1],'user_id'=>$order_id[2]));
					$this->model->addUserpayHistory( array( 'user_id' => $order_id[2], 'amount' => -$data['amount'], 'title'=>'Доступ к файлу' ) );
					$this->model->sendUserNotice($order_id[2], 'Доступ к файлу получен');

				}
			
			}

        } elseif($order_id[0] == 'pf'){

        	$this->model->addPaidItem( array('controller' => $order_id[1], 'item_id' => $order_id[2], 'field_id' => $order_id[3], 'user_id' => $order_id[4]) );
			$this->model->addUserpayHistory( array( 'user_id' => $order_id[4], 'amount' => -$data['amount'], 'title'=>'Доступ к контенту' ) );
			$this->model->sendUserNotice($order_id[4], 'Доступ к контенту получен');

			if($order_id[4] && in_array('pf', $this->options['ref_pay'])){
        		$this->model->addPartnerBonus($order_id[4], $data['amount'], !empty($this->options['ref_virtual_bonus']));
        	}

        } elseif($order_id[0] == 'inv'){

        	$this->invoiceProcess($order_id[1], $data);

        }

    }

}
