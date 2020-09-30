<?php

class actionUserpayPayment extends cmsAction {

    public function run(){

        if (!$this->request->isAjax()){ cmsCore::error404(); }
		
        $error = false;

		$system = $this->request->get('system');
		$amount = $this->request->get('amount');
		$order_id = $this->request->get('order_id');
		$order_name = $this->request->get('order_name');

		$amount = $amount * $this->options[$system.'_in'] + $this->options[$system.'_fee'];

		$param = array(
			'amount' => $amount,
			'order_id' => $order_id,
			'order_name' => $order_name
		);
		
		$data = $this->runExternalAction($system, array('payment', $param));

		$this->cms_template->renderJSON(array(
            'error' => $error,
            'data' => $data
        ));

    }

}
