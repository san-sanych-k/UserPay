<?php

class actionUserpaySubscriptionList extends cmsAction {

    public function run(){

        $grid = $this->loadDataGrid('subscription_list');

        return cmsTemplate::getInstance()->render('backend/subscription_list', array(
            'grid' => $grid
        ));

    }

}
