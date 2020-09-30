<?php

class onUserpayContentBeforeList extends cmsAction {

	public function run($data){

        list($ctype, $items) = $data;

        if(empty($items)) return $data;
		
		$user = cmsUser::getInstance();

		$content_model = cmsCore::getModel('content');
		$ctype_fields = $content_model->getContentFields($ctype['name']);
		
		foreach($items as $key => $item) {

			if($user->id){
				$user_paid_fields = $this->model->getContentUserPaidFields($user->id, $ctype['name'], $item['id']);
			}

			foreach($ctype_fields as $field) {
				
				if($field['type'] == 'paid'){

					if( !empty($field['options']['field_name']) && 
						!empty($item[$field['options']['field_name']]) && 
						!empty($item[$field['name']]) ){

						if($user_paid_fields)
							$user_paid_item = array_collection_to_list($user_paid_fields, 'field_id');

						if( !isset($user_paid_item[$ctype_fields[$field['options']['field_name']]['id']]) ){
							
							$title = 'Доступ к контенту';
							if($user->id){
								$link = '<a title="'.$title.'" class="ajax-modal paid_field" href="'.href_to('userpay', 'form').'?order_id=pf_'.$ctype['name'].'_'.$item['id'].'_'.$ctype_fields[$field['options']['field_name']]['id'].'_'.$user->id.'&amount='.$item[$field['name']].'&fix_amount=1&order_name='.$title.'">Открыть за '.$item[$field['name']].' '.$this->options['curr_short'].'</a>';
							} else {
								$link = '<a title="'.$title.'" class="ajax-modal" href="'.href_to('auth', 'login').'">Авторизоваться и открыть за '.$item[$field['name']].' '.$this->options['curr_short'].'</a>';
							}
							
							$items[$key]['fields'][$field['options']['field_name']]['html'] = $link;
							
						}

					}

				}
				
			}
			
		}

        return array($ctype, $items);

    }

}
