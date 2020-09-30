<?php

class onUserpayContentAdd extends cmsAction {

    public function run($item){
		
		$is_submitted = isset($_POST['submit']);

		if(!empty($item['options']['add_item_notice']) && !$is_submitted) {
			cmsUser::addSessionMessage($item['options']['add_item_notice']);
		}
		
		return $item;

    }

}
