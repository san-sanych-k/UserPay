<?php

class actionUserpaySubscriptionAjax extends cmsAction {

    public function run(){

        if (!$this->request->isAjax()) { cmsCore::error404(); }
		
		$grid = $this->loadDataGrid('subscription');
		
		$item = $this->model->getSubscription();
        
        cmsTemplate::getInstance()->renderGridRowsJSON($grid, $item);

        $this->halt();

    }

}
