<?php

class userpay extends cmsFrontend {

	protected $useOptions = true;
	
	public function getUserPaymentForm($param, $system_list=array(), $no_list=array()) {

		$user = cmsUser::getInstance();
		$core = cmsCore::getInstance();
		$request = $core->request->getAll();

		if(!isset($param['amount']) && isset($request['amount']))
			$param['amount'] = $request['amount'];

		if(!empty($param['fix_amount']) && isset($param['amount'])) {
			$fix_amount =  'disabled';
			$label = 'К оплате: '.$param['amount'].' '.$this->options['curr_short'];
			$hide = true;
		} else {
			$fix_amount =  false;
			$label = 'Введите сумму';
			$hide = false;
		}

		if(!$no_list && isset($param['no_list'])){
			$no_list = explode(',', $param['no_list']);
		}

		if(!$system_list && isset($param['systems'])){
			$system_list = explode(',', $param['systems']);
		}		
		
		$systems = $this->getSystemsList();

		$back_url = isset($param['back_url']) ? $param['back_url'] : cmsCore::getInstance()->uri_absolute;
		isset($param['success_url']) ? cmsUser::sessionSet('userpay_success_url', $param['success_url']) : false;
		isset($param['process_url']) ? cmsUser::sessionSet('userpay_process_url', $param['process_url']) : false;
		isset($param['fail_url']) ? cmsUser::sessionSet('userpay_fail_url', $param['fail_url']) : false;

		cmsUser::sessionSet('userpay_back_url', $back_url);
		
		$param['payments_list_style'] = !empty($param['payments_list_style']) ? $param['payments_list_style'] : $this->options['payments_list_style'];

		return array(
                'user'          => $user,
                'options'       => $this->options,
				'param'         => $param,
                'fix_amount'    => $fix_amount,
                'label'         => $label,
                'hide'          => $hide,
				'systems'       => $systems,
				'system_list'   => $system_list,
				'no_list'       => $no_list,
				'back_url'      => $back_url
        );

	}

	public function getSystemsList() {

		$user = cmsUser::getInstance();

		$list = array();

		$systems = files_tree_to_array('system/controllers/userpay/actions/');
		foreach ($systems as $key => $value) {
			$value = str_replace('.php', '', $value);
			if(in_array($value, array('payment','form'))) continue;
			if(!isset($this->options[$value.'_on'])) { continue; }
			if(!$user->id && $value == 'balance') continue;
			$list[$value] = !empty($this->options[$value.'_order']) ? $this->options[$value.'_order'] : 1000;
		}

		asort($list);
		
		return $list;

	}

	public function sendNoticeSubscriptionOut($user_id, $user_nickname, $plan_id, $plan_title){

        $messenger = cmsCore::getController('messages');
		
		$messenger->addRecipient($user_id);

		$notice = array(
            'content' => 'Подписка: "' . $plan_title . '" закончилась. ',
            'options' => array(
                'is_closeable' => true
            )
        );
		
		$messenger->ignoreNotifyOptions()->sendNoticePM($notice, 'userpay_subscription_out');
		
		$messenger->ignoreNotifyOptions()->sendNoticeEmail('userpay_subscription_out', array(
			'username' => $user_nickname,
			'plan' => $plan_title
		));

    }

    public function saveRefVisit($ref) {

		$user = cmsUser::getInstance();
		$core = cmsCore::getInstance();

		$item['user_id'] = $ref;
		$item['ip'] = $user->getIp();
		$item['link'] = $core->uri;
		$item['page'] = $_SERVER['HTTP_REFERER'];
		if ($item['page'])
			$item['domain'] = self::getDomain($item['page']);
		$item['auth'] = $user->id ? $user->id : null;
		$item['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		if ($user->hasCookie('ref_id'))
			$item['cookie'] = $user->getCookie('ref_id');

		return $this->model->addRefLog($item);

	}

	public function getDomain($url) {
   
		$parts = parse_url($url);
		return $parts['host'];
	
	}

	public function invoiceProcess($id, $payment){

		$invoice = $this->model->getInvoice($id);

		if(!$invoice || $invoice['amount'] != $payment['amount']) { return false; }

		$this->invoiceApprove($invoice);

		if(in_array('invoice', $this->options['ref_pay'])){
        	$this->model->addPartnerBonus($invoice['user_id'], $payment['amount'], !empty($this->options['ref_virtual_bonus']));
        }

	}
	
	public function invoiceApprove($invoice){

		if($invoice['data_item']['type'] == 'content') {

			$this->model->update('con_'.$invoice['data_item']['ctype_name'], $invoice['data_item']['item_id'], array('is_pub'=>1));

		}

		$this->model->invoiceUpdate($invoice['id'], array('is_active'=>0));

	}

	public function formModal($data){

		if(!empty($data['class'])){
			$data['class'] = $data['class'].' ';
		}

		return '<a style="'.@$data['style'].'" class="'.@$data['class'].'ajax-pay-modal" href="#" data-url="'.$data['url'].'" data-title="'.$data['title'].'">'.$data['button_text'].'</a>';

	}

}
