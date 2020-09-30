<?php

class modelUserpay extends cmsModel{

	private $options;

	public function __construct() {
        parent::__construct();
        $this->options = cmsController::loadOptions('userpay');
    }

	public function isDubl($system, $id) {

		$this->resetFilters();
		$this->filterEqual('system', $system);
		$this->filterEqual('tr_id', $id);

		return $this->getCount('userpay_log');

	}

	public function addLog($item) {

		return $this->insert('userpay_log', $item);

	}

	public function incrementUserBalance($user_id, $amount, $bonus=false) {

		$this->resetFilters();
		$this->filterEqual('id', $user_id);

		return $this->increment('{users}', !$bonus ? 'balance' : 'balance2', $amount);

	}

	public function decrementUserBalance($user_id, $amount) {

		$this->resetFilters();
		$this->filterEqual('id', $user_id);

		return $this->decrement('{users}', 'balance', $amount);

	}

	public function addUserpayHistory($item) {

		return $this->insert('userpay_history', $item);

	}

	public function getUserBalance($user_id) {

		$this->resetFilters();
		
		$user = $this->getItemById('{users}', $user_id);

		return $user['balance'];

	}

	public function getLog(){
		
		return $this->get('userpay_log');
		
	}
	
	public function getLogCount(){
		
		return $this->getCount('userpay_log');
		
	}

	public function getHistory(){
		
		return $this->get('userpay_history');
		
	}
	
	public function getHistoryCount(){
		
		return $this->getCount('userpay_history');
		
	}

	public function sendAdminNotice($notice){

        if($this->options['in_notice']) {
			foreach($this->options['in_notice'] as $value) {
				$this->insert('{users}_notices', array('user_id' => $value, 'content' => $notice));
			}
		}

	}

	public function sendUserNotice($user_id, $notice){

        $this->insert('{users}_notices', array('user_id' => $user_id, 'content' => $notice));

	}

	public function getSubscription($id=false, $is_active=false){
		
		if($id) {
		
			$sbs = $this->getItemById('userpay_subscription_list', $id);
		
			$sbs['groups'] = cmsModel::yamlToArray($sbs['groups']);
			
			switch($sbs['period_type']){
				
				case 'MINUTE': $sbs['period_title'] = 'Минут'; break;
				case 'HOUR': $sbs['period_title'] = 'Часов'; break;
				case 'DAY': $sbs['period_title'] = 'Дней'; break;
				case 'WEEK': $sbs['period_title'] = 'Недель'; break;
				case 'MONTH': $sbs['period_title'] = 'Месяцев'; break;
				case 'YEAR': $sbs['period_title'] = 'Лет'; break;
				
			}
		
			return $sbs;
			
		}
			
		if($is_active) $this->filterEqual('is_active', true);
		
		return $this->get('userpay_subscription_list', function($item, $model){
			
			$item['groups'] = cmsModel::yamlToArray($item['groups']);
			
			switch($item['period_type']){
				
				case 'MINUTE': $item['period_title'] = 'Минут'; break;
				case 'HOUR': $item['period_title'] = 'Часов'; break;
				case 'DAY': $item['period_title'] = 'Дней'; break;
				case 'WEEK': $item['period_title'] = 'Недель'; break;
				case 'MONTH': $item['period_title'] = 'Месяцев'; break;
				case 'YEAR': $item['period_title'] = 'Лет'; break;
				
			}
			
			return $item;
			
		});
		
	}

	public function addSubscription($item){
		
		return $this->insert('userpay_subscription_list', $item);
		
	}
	
	public function updateSubscription($id, $item){

     	return $this->update('userpay_subscription_list', $id, $item);

    }
	
	public function deleteSubscription($id){

     	return $this->delete('userpay_subscription_list', $id);

    }

    public function getUserSubscription($id=false){
		
		$this->joinUser();
		
		if($id) {
		
			$this->filterEqual('user_id', $id);
			$this->groupBy('date_pub');
		
		} else {
			
			$this->groupBy('pub_key');
			
		}
		
		$this->orderBy('end_date');
		
		return $this->get('userpay_subscriptions', function($item, $model){
			
			$item['subs'] = $model->getSubscription($item['plan_id']);
			
			return $item;
			
		});
		
	}
	
	public function getUserSubscriptionCount($id=false){
		
		if($id) {
		
			$this->filterEqual('user_id', $id);
			$this->groupBy('date_pub');
		
		} else {
			
			$this->groupBy('pub_key');
			
		}
				
		return $this->getCount('userpay_subscriptions');
		
	}

	public function updateUserSubscription($user_id, $sbs){
		
		$user_model = cmsCore::getModel('users');
		$user = $user_model->getUser($user_id);
		
		$add = array();
			
		foreach($sbs['groups'] as $ug){
			//Получаем массив групп которых у пользователя нет
			if(!in_array($ug, $user['groups'])) $add[] = $ug;		
		}
			
		//Общий массив групп пользователя и приобретаемых
		$plus = array_merge($user['groups'], $add);
		
		//Добавляем обновляем группы у пользователя
		$user_model->updateUser($user_id, array('groups' => $plus));
		
		$end_date = date('Y-m-d H:i:s', strtotime('+'.$sbs['period'].' '.$sbs['period_type']));
		
		$sbsExist = $this->sbsExist($user_id, $sbs['id']);
		
		if(!$sbsExist) {
			
			//Добавляем подписки в БД
			foreach($sbs['groups'] as $item){

				$insert = array('user_id' => $user_id, 
								'groups' => $item, 
								'plan_id' => $sbs['id'], 
								'end_date' => $end_date, 
								'pub_key'=>rand(1000,9999).time()
							);
					
				$this->insert('userpay_subscriptions', $insert);
			
			}

			return $end_date;
		
		} else {
			
			if($sbs['period_type'] == 'MINUTE') $nn = 60;
			if($sbs['period_type'] == 'HOUR') $nn = 60*60;
			if($sbs['period_type'] == 'DAY') $nn = 60*60*24;
			if($sbs['period_type'] == 'WEEK') $nn = 60*60*24*7;
			if($sbs['period_type'] == 'MONTH') $nn = 60*60*24*7*30;
			if($sbs['period_type'] == 'YEAR') $nn = 60*60*24*7*365; 
			
			$new_date = date('Y-m-d H:i:s', strtotime($sbsExist['end_date'])+($sbs['period']*$nn));
			
			$this->resetFilters()->filterEqual('user_id', $user_id)->filterEqual('plan_id', $sbs['id'])->updateFiltered('userpay_subscriptions', array('end_date' => $new_date));

			return $new_date;
			
		}
		
	}

	public function sbsExist($user_id, $id){
		
		$this->resetFilters();
		$this->filterEqual('user_id', $user_id);
		$this->filterEqual('plan_id', $id);
		
		$sbs = $this->get('userpay_subscriptions');
		
		if(!$sbs) return false;
		
		return array_shift($sbs);
		
	}

	public function endSubscription($user_id=false, $plan_id=false){
		
		if($user_id && $plan_id) {
			
			$this->filterEqual('user_id', $user_id);
			$this->filterEqual('plan_id', $plan_id);
			
		} else {
			
			$this->filterDateOlder('end_date', 1, 'SECOND');
			
		}
		
		$this->select( 's.title', 'title' );
		$this->join( 'userpay_subscription_list', 's', 's.id = i.plan_id' );
		
		$end = $this->get('userpay_subscriptions');
		
		$users_model = cmsCore::getModel('users');

		$repeat = false;
		$s = false;
		
		if($end) {
			
			foreach($end as $item) {
				
				$gFree = $this->gFree($item['id'], $item['user_id'], $item['groups']);

				if($s != $item['pub_key']) $repeat = false;
				
				$user = $users_model->getUser($item['user_id']);
				
				if(!$gFree) {
					
					//Удаляем у пользователя группу
					$user['groups'] = array_flip($user['groups']);
					unset($user['groups'][$item['groups']]);
					$user['groups'] = array_flip($user['groups']);
						
					$users_model->updateUser($user['id'], array('groups' => $user['groups']));
					
				}
				
				$this->deleteSubscriptionItem($item['id']);
				
				if(!$repeat) cmsCore::getController('userpay')->sendNoticeSubscriptionOut($user['id'], $user['nickname'], $item['plan_id'], $item['title']);

				$repeat = true;
				$s = $item['pub_key'];
				
			}
			
		}
		
		return true;
		
	}
	
	public function gFree($id, $user_id, $groups){
		
		$this->resetFilters();
		$this->filterNotEqual('id', $id);
		$this->filterEqual('user_id', $user_id);
		$this->filterEqual('groups', $groups);
		
		return $this->getCount('userpay_subscriptions');
		
	}
	
	public function deleteSubscriptionItem($id){
		
		$this->resetFilters();
		
		return $this->delete('userpay_subscriptions', $id);
		
	}

	public function fieldIsPaid($item){

		$this->filterEqual('item_id', $item['id']);

		if(isset($item['user_id'])){
			$this->filterEqual('user_id', $item['user_id']);
		}

		if(isset($item['controller'])){
			$this->filterEqual('controller', $item['controller']);
		}

		if(isset($item['time'])){
			$this->filterDateYounger('date_pub', $item['time'], 'second');
		}

		return $this->getCount('userpay_paid_fields');

	}

	public function addPaidItem($item){

		$this->insert('userpay_paid_fields', $item);

	}

	public function addRefLog($item) {

        return $this->insert('userpay_partner_log', $item);

    }

    public function getUserRefLog($user_id, $limit=25) {

    	$this->resetFilters();
    	$this->filterEqual('user_id', $user_id);
    	$this->orderBy('id', 'desc');
    	$this->limit($limit);

        return $this->get('userpay_partner_log');

    }

    public function getUserRefLogCount($user_id) {

    	$this->resetFilters();
    	$this->filterEqual('user_id', $user_id);

        return $this->getCount('userpay_partner_log');

    }

    public function getAllRefLog() {

    	return $this->get('userpay_partner_log');

    }

    public function getAllRefLogCount() {

    	return $this->getCount('userpay_partner_log');

    }

    public function getUserPartnerCount($user_id) {

    	if(empty($this->options['ref_step'])){
    		return false;
    	}

    	$this->filterEqual('ref_id', $user_id);
    	$this->filterLtEqual('level', count(explode(',', str_replace(' ', '', $this->options['ref_step']))));

    	return $this->getCount('userpay_partner_users');

    }

    public function deletePartnerUsers($user_id) {

        $this->resetFilters();
        $this->filterEqual('user_id', $user_id);
        
        return $this->deleteFiltered('userpay_partner_users');

    }

    public function updatePartnerFields($user_id) {

        $this->resetFilters();
        $this->filterEqual('ref_id', $user_id);
        
        return $this->updateFiltered('{users}', array('ref_id'=>null));

    }

    public function addPartner($user_id, $refer=false, $level=1) {

    	if(!$refer)
    		$refer = $user_id;

    	$users_model = cmsCore::getModel('users');

    	$user = $users_model->getUser($user_id);

    	if (!empty($user['ref_id'])) {

    		$this->insert('userpay_partner_users', array('user_id' => $refer, 'ref_id' => $user['ref_id'], 'level' => $level));

    		if (count(explode(',', str_replace(' ', '', $this->options['ref_step']))) > $level) {

    			$referer = $users_model->getUser($user['ref_id']);

	    		if (!empty($referer['ref_id'])) {

	    			$level++;

	    			self::addPartner($user['ref_id'], $refer, $level);

	    		}

    		}

    	}

    }

    public function getUserPartner($user_id) {

    	$this->orderBy('date_reg', 'desc');
    	$this->filterEqual('ref_id', $user_id);
    	$this->filterLtEqual('level', count(explode(',', str_replace(' ', '', $this->options['ref_step']))));

        $this->join('{users}', 'u', 'u.id = i.user_id');
        $this->select('u.nickname', 'user_nickname');

    	return $this->get('userpay_partner_users', function($item, $model) {

    		$item['ref_amount'] = $model->getPartnerAmount($item['ref_id'], $item['user_id']);

    		return $item;

    	});

    }

    public function getPartnerAmount($user_id, $ref_id) {

        $this->resetFilters();
        $this->filterEqual('ref', $ref_id);
        $this->filterEqual('user_id', $user_id);

        $res = $this->get('userpay_history');

        if (!$res)
            return 0;

        $res = array_collection_to_list($res, 'id', 'amount');
        $res = array_sum($res);

		return $res;

    }

    public function addPartnerBonus($user_id, $amount, $bonus=false) {

        $referers = $this->getUserPartner($user_id);

        if (!$referers)
            return false;

        $levels = explode(',', str_replace(' ', '', $this->options['ref_step']));

        foreach ($referers as $key => $value) {

            $am = round($amount / 100 * $levels[$value['level']-1], 2);
            
            $this->incrementUserBalance($value['ref_id'], $am, $bonus);

            $this->addUserpayHistory(array('user_id' => $value['ref_id'], 'amount' => $am, 'title' => 'Доход от партнера: <a href="'.href_to('users', $user_id).'">'.$value['partner_nickname'].'</a>', 'ref' => $user_id));

            $notice = '<h3>Доход от партнера</h3>
            			<div>Зачислено: <b>'.$am.' '.$this->options['curr_short'].'</b></div>';
            $this->sendUserNotice($value['ref_id'], $notice);
        
        }

    }

    public function getContentUserPaidFields($user_id, $ctype_name, $item_id=false){

    	$this->resetFilters();
    	$this->filterEqual('controller', $ctype_name);
    	$this->filterEqual('user_id', $user_id);

    	if($item_id)
    		$this->filterEqual('item_id', $item_id);

    	return $this->get('userpay_paid_fields');

    }

    public function addInvoice($item){

    	return $this->insert('userpay_invoice', $item);

    }

    public function getInvoice($id){

    	$this->resetFilters();

    	$invoice = $this->getItemById('userpay_invoice', $id);

    	if(!$invoice) { return false; }

    	$invoice['data_item'] = cmsModel::yamlToArray($invoice['data_item']);

    	return $invoice;

    }

    public function getUserInvoiceList($user_id){

    	$this->filterEqual('user_id', $user_id);
    	$this->filterEqual('is_active', 1);

    	return $this->get('userpay_invoice', function($item, $model){

    		$item['data_item'] = cmsModel::yamlToArray($item['data_item']);

    		return $item;

    	});

    }

    public function invoiceUpdate($id, $item) {

    	return $this->update('userpay_invoice', $id, $item);

    }

}
