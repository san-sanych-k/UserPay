<?php

class widgetUserpayDonate extends cmsWidget {

    public function run(){

    	$model = cmsCore::getModel('userpay');

        $target_id = $this->getOption('target_id', 1);
        $log_days = $this->getOption('log_days', false);
        $log_sort = $this->getOption('log_sort', 'new');

        if($log_days){

            if($log_sort == 'new') $model->orderBy('id', 'desc');
            if($log_sort == 'max') $model->orderBy('amount', 'desc');
            if($log_sort == 'min') $model->orderBy('amount');

            if(empty($this->getOption('ignore_id'))) $model->filterEqual('target_id', $target_id);
            
            $model->filterDateYounger('date_pub', $log_days, 'day');
            $model->joinUserLeft();

            $items = $model->get('userpay_donate');

        }

		return array(
			'items' => isset($items) ? $items : false
		);

    }

}
