<?php

class actionUserpayForm extends cmsAction {

    public function run(){

        if (!$this->request->isAjax()){ cmsCore::error404(); }

        $core = cmsCore::getInstance();

    	$get = $core->request->getAll();

    	if(empty($get['amount'])){ $get['amount'] = false; }

        $get['amount'] = str_replace(',', '.', $get['amount']);

        $payment_form = array();

        foreach ($get as $key => $value) {
            $payment_form[$key] = $value;
        }

        $back = parse_url($this->getBackURL());
        $back = substr($back['path'], 1);

        $payment_form['back_url'] = isset($payment_form['back_url']) ? $payment_form['back_url'] : $back;

        $form_array = $this->getUserPaymentForm($payment_form);
		
        return $this->cms_template->render('form', $form_array);

    }

}
