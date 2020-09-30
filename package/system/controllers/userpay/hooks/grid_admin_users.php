<?php

class onUserpayGridAdminUsers extends cmsAction {
	
	function run($grid) {
			
		$grid['columns']['balance'] = array('title' => 'Баланс');
		
		return $grid;
		
	}
	
}
