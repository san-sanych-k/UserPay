<?php

class onUserpayCtypeBasicForm extends cmsAction {

    public function run($form){

    	$core = cmsCore::getInstance();

        // проверяем разрешенные типы контента
        if($core->uri_params[0] != 'edit'){
            return $form;
        }

        $form->addFieldset('UserPay', 'userpay', array('is_collapsed' => true));
		
        $form->addFieldToBeginning('userpay', new fieldText('options:add_item_notice', array(
			'title' => 'Текст уведомления при публикации оплачиваемой записи'
		)));
		
		$form->addFieldToBeginning('userpay', new fieldNumber('options:add_item_price', array(
			'title' => 'Стоимость размещения записи'
		)));
		
		return $form;

    }

}
