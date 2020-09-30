<?php

class actionUserpaySubscriptionItemDelete extends cmsAction {

    public function run($user_id, $plan_id) {
		
        $this->model->endSubscription($user_id, $plan_id);
		
		cmsUser::addSessionMessage('Подписка успешно отменена', 'success');
		
        $this->redirectBack();
		
    }

}
