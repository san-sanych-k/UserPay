<?php

class onUserpayDiskDownloadLink extends cmsAction {

    public function run($data){

        list($file, $link) = $data;

        if(empty($file['price']) || !is_numeric($file['price']) || $file['price'] <= 0) {
        	return $data;
        }

        $user = cmsUser::getInstance();

        if(!$user->id){
        	$link = '<a class="bot1 ajax-modal" href="'.href_to('auth', 'login').'">Авторизоваться и скачать за '.$file['price'].' '.$this->options['curr_short'].'</a>';
        	return array($file, $link);
        }

        $item['id'] = $file['id'];
        $item['user_id'] = $user->id;
        $item['controller'] = 'disk';

        if(!empty($file['price_time'])){
        	$item['time'] = $file['price_time'];
        }

        $is_paid = $this->model->fieldIsPaid($item);

        if(!$is_paid){
        	
            $link = $this->controller->formModal( array('class' => 'bot1', 'url' => href_to('userpay', 'form').'?order_id=disk_'.$file['id'].'_'.$user->id.'&amp;order_name=Доступ к файлу&amp;amount='.$file['price'].'&amp;fix_amount=1', 'title' => 'Доступ к файлу', 'button_text' => 'Скачать за '.$file['price'].' '.$this->options['curr_short']) );
        
        }

        return array($file, $link);

    }

}
