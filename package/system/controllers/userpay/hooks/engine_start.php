<?php

class onUserpayEngineStart extends cmsAction {

    public function run(){
		
		$core = cmsCore::getInstance();
		if($core->uri_controller == 'admin') return false;

		$template = cmsTemplate::getInstance();
		$template->addControllerCSS('styles', $this->name);
		$template->addControllerCSS('vex', $this->name);
		$template->addControllerCSS('vex-theme-plain', $this->name);
		$template->addControllerJS('script', $this->name);
		$template->addControllerJS('redirect', $this->name);
		$template->addControllerJS('vex.combined.min', $this->name);

		$systems = $this->getSystemsList();
		foreach ($systems as $key => $value) {
			$template->addControllerCSS($key, $this->name);
		}

    }

}
