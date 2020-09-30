<?php

class actionUserpayPartner extends cmsAction {

    public function run(){

        $grid = $this->loadDataGrid('partner');

        return cmsTemplate::getInstance()->render('backend/partner', array(
            'grid' => $grid
        ));

    }

}
