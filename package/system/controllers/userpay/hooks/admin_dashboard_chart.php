<?php

class onUserpayAdminDashboardChart extends cmsAction {

	public function run(){

        $data = array(
            'id' => 'userpay',
            'title' => 'UserPay',
            'sections' => array(
                'operation' => array(
                    'title' => 'Операции',
                    'table' => 'userpay_history',
                    'key' => 'date_pub'
                ),
                'transaction' => array(
                    'title' => 'Транзакции',
                    'table' => 'userpay_log',
                    'key' => 'date_pub'
                ),
                'partner_hit' => array(
                    'title' => 'Партнерские переходы',
                    'table' => 'userpay_partner_log',
                    'key' => 'date_pub'
                ),
            )
        );

        return $data;

    }

}
