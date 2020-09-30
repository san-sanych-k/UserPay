<?php

class actionUserpaySubscriptionDelete extends cmsAction {

    public function run($id) {
		
        if (!$id) { cmsCore::error404(); }
		
        $this->model->deleteSubscription($id);
		
		cmsUser::addSessionMessage('Подписка удалена', 'success');
		
        $this->redirectToAction('subscription');
		
    }

}
