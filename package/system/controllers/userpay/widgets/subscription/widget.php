<?php

class widgetUserpaySubscription extends cmsWidget {

    public function run(){

        $userpay = cmsCore::getController('userpay');

        $user = cmsUser::getInstance();

        $plan_id = $this->getOption('plan', '');

        if($user->id){

            $exist = $userpay->model->filterEqual('plan_id', $plan_id)->getUserSubscriptionCount($user->id);

            if($exist)
                return false;

        }

        $plan = $userpay->model->resetFilters()->getSubscription($plan_id);

        if(!$plan) return false;

		return array(
			'plan' => $plan,
            'userpay' => $userpay
		);

    }

}
