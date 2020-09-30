<?php

class onUserpayMenuUserpay extends cmsAction {

    public function run($item){

    	$action = $item['action'];

        $user = cmsUser::getInstance();
		
		if ( !$user->id || @$user->balance === false) { return false; }		

		if ($action == 'balance'){

			$balance = (@$user->balance == null || @$user->balance == 0)  ? '0.00' : $user->balance;

	        return array(
	            'url' => href_to('users', $user->id, 'pay'),
	            'counter' => $balance
	        );

    	}

    }

}
