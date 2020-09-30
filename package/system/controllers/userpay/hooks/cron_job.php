<?php

class onUserpayCronJob extends cmsAction {

	public function run(){
		
		return $this->model->endSubscription();

    }
	
}
