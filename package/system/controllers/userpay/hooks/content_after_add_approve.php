<?php

class onUserpayContentAfterAddApprove extends cmsAction {

    public function run($data){

    	$content_model = cmsCore::getModel('content');
	
		$ctype = $content_model->getContentTypeByName($data['ctype_name']);

		if(empty($ctype['options']['add_item_price'])) { return $data; }

		$this->model->update('con_'.$data['ctype_name'], $data['item']['id'], array('is_pub' => 0));

		$user = cmsUser::getInstance();

		$invoice = array(
			'user_id' => $data['user_id'],
			'data_item' => array(
				'type' => 'content',
				'ctype_name' => $data['ctype_name'],
				'item_id' => $data['item']['id'],
				'title' => 'Публикация записи: '.$data['item']['title']
			),
			'amount' => $ctype['options']['add_item_price']
		);

		$invoice_id = $this->model->addInvoice($invoice);

		if($data['item'] == $user->id){
			cmsUser::addSessionMessage('Для публикации записи оплатите счет №'.$invoice_id);
			$this->redirect(href_to('users', $user->id, 'pay'));
		} else {
			$messenger = cmsCore::getController('messages');
			$messenger->addRecipient($data['user_id']);
			$notice = array(
	            'content' => 'Ваша запись "'.$data['item']['title'].'" готова к публикации. Вам необходимо оплатить счет №'.$invoice_id.'. Все неоплаченные счета находятся в вашем профиле.',
	            'options' => array(
	                'is_closeable' => true
	            ),
	            'actions' => array(
	                'view' => array(
	                    'title' => 'Перейти в профиль',
	                    'href' => href_to('users', $data['user_id'], 'pay')
	                )
	            )
	        );
			$messenger->ignoreNotifyOptions()->sendNoticePM($notice, 'invoice_notice');
		}

        return $data;

    }

}
