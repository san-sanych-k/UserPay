<?php

class onUserpayUserTabShow extends cmsAction {

    public function run($profile, $tab_name){

    	if($tab_name == 'pay') {

	    	$user = cmsUser::getInstance();

	    	if ($user->id != $profile['id'] && !$user->is_admin){ cmsCore::error404(); }

	    	$page = $this->request->get('page', 1);
			$perpage = 25;

	    	$this->model->limitPage($page, $perpage);

			$items = $this->model->resetFilters()->orderBy('id', 'desc')->filterEqual('user_id', $profile['id'])->getHistory();

			$total = $this->model->filterEqual('user_id', $profile['id'])->getHistoryCount();

			$subscriptions = $this->model->getUserSubscription($profile['id']);

			if(!empty($this->options['invoice_day_limit']) && $this->options['invoice_day_limit'] >= 1) {
				$this->model->filterDateYounger('date_pub', $this->options['invoice_day_limit'], 'day');
			}

			$invoices = $this->model->getUserInvoiceList($profile['id']);

			return $this->cms_template->renderInternal($this, 'profile_tab_pay', array(
				'profile' => $profile,
				'tab_name' => $tab_name,
				'items' => $items,
				'page' => $page,
				'perpage' => $perpage,
				'total' => $total,
				'subscriptions' => $subscriptions,
				'invoices' => $invoices,
				'user' => $user
			));

		} elseif($tab_name == 'partner') {

			$user = cmsUser::getInstance();

	    	if ($user->id != $profile['id'] && !$user->is_admin)
	    		cmsCore::error404();

	    	$page = $this->request->get('page', 1);		
			$perpage = 25;

	    	$this->model->limitPage($page, $perpage);

	    	$step = explode(',',str_replace(' ','',$this->options['ref_step']));
			$links = explode(',',str_replace(' ','',$this->options['ref_links']));
			
			$partner = $this->model->getUserPartner($profile['id']);

			$total = $this->model->getUserPartnerCount($profile['id']);

			if($profile['ref_id']) {
				$referer = cmsCore::getModel('users')->getUser($profile['ref_id']);
			}

			$partner_hits = $this->model->getUserRefLog($profile['id']);
			$partner_hits_count = $this->model->getUserRefLogCount($profile['id']);

			return $this->cms_template->renderInternal($this, 'profile_tab_partner', array(
				'profile' => $profile,
				'tab_name' => $tab_name,
				'step' => $step,
				'links' => $links,
				'partner' => $partner,
				'page' => $page,
				'perpage' => $perpage,
				'total' => $total,
				'refer' => isset($referer) ? $referer : false,
				'partner_hits' => $partner_hits,
				'partner_hits_count' => $partner_hits_count
			));

		}

    }

}
