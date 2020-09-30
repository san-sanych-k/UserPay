<?php

class actionUserpayHistoryAjax extends cmsAction {

    public function run(){

        if (!$this->request->isAjax()) { cmsCore::error404(); }
		
		$grid = $this->loadDataGrid('history');
		
		$model = cmsCore::getModel('userpay');
		
		$perpage = 100;
		
		$model->setPerPage($perpage);
		
		$filter     = array();
        $filter_str = $this->request->get('filter', '');

        if ($filter_str){
            parse_str($filter_str, $filter);
            $model->applyGridFilter($grid, $filter);
        }
		
		$total = $model->getHistoryCount();
		
		$perpage = isset($filter['perpage']) ? $filter['perpage'] : $perpage;
		
		$pages = ceil($total / $perpage);

        $item = $model->getHistory();
        
        cmsTemplate::getInstance()->renderGridRowsJSON($grid, $item, $total, $pages);

        $this->halt();

    }

}
