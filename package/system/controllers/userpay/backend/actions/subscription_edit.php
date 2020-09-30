<?php

class actionUserpaySubscriptionEdit extends cmsAction {

    public function run($id) {
		
        if (!$id) { cmsCore::error404(); }
		
		$errors = false;
		
        $form = $this->getForm('subscription');
        
		$is_submitted = $this->request->has('submit');
		
		$item = $this->model->getSubscription($id);
		
        if ($is_submitted) {
			
            $item = $form->parse($this->request, $is_submitted);
            
			$errors = $form->validate($this, $item);
			
			if (!$errors) {
				
                $this->model->updateSubscription($id, $item);
				
				cmsUser::addSessionMessage('Подписка отредактирована', 'success');
				
                $this->redirectToAction('subscription');
				
            } else {
				
                cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
				
            }
			
        }

        return cmsTemplate::getInstance()->render('backend/subscription_add', array(
            'item' => $item,
            'form' => $form,
            'errors' => $errors
        ));

    }
}
