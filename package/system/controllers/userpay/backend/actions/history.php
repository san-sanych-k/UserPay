<?php

class actionUserpayHistory extends cmsAction {

    public function run(){

        $grid = $this->loadDataGrid('history');

        return cmsTemplate::getInstance()->render('backend/history', array(
            'grid' => $grid
        ));

    }

}
