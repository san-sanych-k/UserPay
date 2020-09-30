<?php

class actionUserpaySubscriptionAdd extends cmsAction {
	
	public function run(){
		
		$errors = false;
		
		$form = $this->getForm('subscription');
		
		$is_submitted = $this->request->has('submit');
		
		$item = $form->parse($this->request, $is_submitted);
		
		if ($is_submitted){
			
			$errors = $form->validate($this, $item);
			
			if (!$errors){
				
				$this->model->addSubscription($item);
				cmsUser::addSessionMessage('Подписка добавлена', 'success');
				$this->redirectToAction('subscription');
				
			}
			
			if ($errors){
				cmsUser::addSessionMessage(LANG_FORM_ERRORS, 'error');
			}
			
		}
		
		return cmsTemplate::getInstance()->render('backend/subscription_add', array(
            'form' => $form,
			'errors' => $errors,
			'item' => $item
        ));

    }

}
