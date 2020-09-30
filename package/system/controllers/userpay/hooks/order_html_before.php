<?php

class onUserpayOrderHtmlBefore extends cmsAction {

    public function run($data){

    	if($data['summ_items']<=$data['amount']){ return false; }

    	$html = '<a class="ajax-modal" href="'.href_to('userpay', 'form?order_id='.$data['id'].'_ucart&amount='.($data['summ_items']-$data['amount']).'&order_name=Оплата заказа №'.$data['id'].'&fix_amount=1').'"><button class="ui positive basic button"><i class="money icon"></i>Оплатить заказ</button></a>';

        return $html;

    }

}
