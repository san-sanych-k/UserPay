<?php

class actionUserpayPartnerAjax extends cmsAction {

    public function run(){

        if (!$this->request->isAjax()) { cmsCore::error404(); }
		
		$grid = $this->loadDataGrid('partner');
		
		$model = cmsCore::getModel('userpay');
		
		$perpage = 100;
		
		$model->setPerPage($perpage);
		
		$filter     = array();
        $filter_str = $this->request->get('filter', '');

        if ($filter_str){
            parse_str($filter_str, $filter);
            $model->applyGridFilter($grid, $filter);
        }
		
		$total = $model->getAllRefLogCount();
		
		$perpage = isset($filter['perpage']) ? $filter['perpage'] : $perpage;
		
		$pages = ceil($total / $perpage);

        $item = $model->getAllRefLog();
        
        cmsTemplate::getInstance()->renderGridRowsJSON($grid, $item, $total, $pages);

        $this->halt();

    }

}
