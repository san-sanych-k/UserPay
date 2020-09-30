<?php

class onUserpayUserTabInfo extends cmsAction {

    public function run($profile, $tab_name){
		
		
    	if($tab_name == 'pay'){

			$user = cmsUser::getInstance();
			
			if ($user->id != $profile['id'] && !$user->is_admin)
				return false;

			return array('counter' => $profile['balance']);

		} elseif ($tab_name == 'partner'){

			$user = cmsUser::getInstance();
		
			if ($user->id != $profile['id'] && !$user->is_admin)
				return false;
			
			$partner_count = $this->model->getUserPartnerCount($profile['id']);

			return array('counter' => $partner_count);

		}

    }

}
