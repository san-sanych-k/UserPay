<?php

class actionUserpaySubscriptionListAjax extends cmsAction {

    public function run(){

        if (!$this->request->isAjax()) { cmsCore::error404(); }
		
		$grid = $this->loadDataGrid('subscription_list');
		
		$perpage = 25;
		
		$this->model->setPerPage($perpage);
		
		$filter     = array();
        $filter_str = $this->request->get('filter', '');

        if ($filter_str){
            parse_str($filter_str, $filter);
            $this->model->applyGridFilter($grid, $filter);
        }
		
		$item = $this->model->getUserSubscription();
		
		$total = $this->model->getUserSubscriptionCount();
		
		$perpage = isset($filter['perpage']) ? $filter['perpage'] : $perpage;
		
		$pages = ceil($total / $perpage);
        
        cmsTemplate::getInstance()->renderGridRowsJSON($grid, $item, $total, $pages);

        $this->halt();

    }

}
