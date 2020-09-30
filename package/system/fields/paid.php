<?php

class fieldPaid extends cmsFormField {

    public $title       = 'Оплачиваемое поле (UserPay)';
	public $sql			= 'decimal(10,2) null DEFAULT null';
    public $filter_type = false;

    public function getOptions(){
        return array(
            new fieldString('field_name', array(
                'title'   => 'Системное имя поля, для которого необходимо установить оплату',
            )),
        );
    }
	
	public function parse($value){
		return '';
    }

    public function parseTeaser($value){
        return '';
    }

    public function getInput($value){
    	$userpay = cmsCore::getController('userpay');
    	$this->data['units'] = $userpay->options['curr_short'];
    	return parent::getInput($value);
    }

}
