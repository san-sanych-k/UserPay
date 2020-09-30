<?php

class actionUserpaySubscription extends cmsAction {

    public function run(){

        $grid = $this->loadDataGrid('subscription');

        return cmsTemplate::getInstance()->render('backend/subscription', array(
            'grid' => $grid,
        ));

    }

}
