<?php

class onUserpayError404 extends cmsAction {
	
	function run($data) {

		if(empty($this->options['ref_links'])){
			return $data;
		}

		$ref_links = explode(',', str_replace(' ', '', $this->options['ref_links']));
		
		$item = explode('/', $data);
		$link_is_ref = in_array($item[0], $ref_links) && count($item) == 2;
			
		if($link_is_ref) {
				
			$ref = (int)$item[1];
				
			if($ref) {

				//Записываем реферальное посещение в log
				$this->saveRefVisit($ref);

				$core = cmsCore::getInstance();
					
				cmsUser::setCookie('ref_id', $ref, $this->options['ref_cookie_life']*60*60*24);

				$get = $core->request->get('page');

				if ($core->request->get('page'))
					$page = $core->request->get('page');

				if (!isset($page))
					$page = $this->options['ref_page'] ? $this->options['ref_page'] : href_to_home();
				
				if (substr($page,0,1) != '/')
					$page = '/'.$page;

				if(!empty($this->options['ref_inner_text'])){
					cmsUser::addSessionMessage($this->options['ref_inner_text']);
				}

				$this->redirect(href_to($page));
				
			}
		
		}

		return $data;
	
	}

}
