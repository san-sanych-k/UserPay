<?php

class fieldDonate extends cmsFormField {

    public $title       = 'Donate (UserPay)';
	public $sql			= 'int(11) default 1';
    public $filter_type = false;

    public function getOptions(){
        return array(
        	new fieldString('target_text', array(
                'title' => 'Назначение платежа',
                'default' => 'Оплата на сайте',
                'rules' => array(
                    array('required')
                )
            )),
            new fieldString('button_text', array(
                'title' => 'Текст на кнопке',
                'default' => 'Оплатить',
                'rules' => array(
                    array('required')
                )
            )),
            new fieldNumber('amount', array(
                'title' => 'Сумма платежа'
            )),
            new fieldCheckbox('fix_amount', array(
                'title' => 'Фиксированная сумма'
            )),
            new fieldText('top_text', array(
                'title' => 'Описание',
            )),
            new fieldNumber('target_id', array(
                'title' => 'Числовой идентификатор сборщика',
                'hint' => 'Подсказка: Разные виджеты с одинаковым идентификатором выводят одинаковые результаты. Если не указано идентиикатор будет автоматически назначен по схеме: имяконтентаidконтента, например: news56',
            )),
            new fieldNumber('log_days', array(
                'title' => 'За какое кол-во дней выводить статистику платежей',
                'hint' => 'Если не указано - не выводим',
                'default' => 30,
            )),
            new fieldList('log_sort', array(
                'title' => 'Сортировка статистики',
                'items' => array(
                    'new' => 'Сначала новые платежи',
                    'max' => 'По наибольшему платежу',
                    'min' => 'По наименьшему платежу'
                )
            )),
            new fieldNumber('log_count', array(
                'title' => 'Количество записей в списке',
                'hint' => 'Если не указано - не ограничено',
            )),
            new fieldCheckbox('ignore_id', array(
                'title' => 'Игнорировать назначения платежа',
                'hint' => 'Если не отмечено - выводить все',
            )),
            new fieldColor('button_bg_color', array(
                'title' => 'Цвет кнопки'
            )),
            new fieldColor('button_text_color', array(
                'title' => 'Цвет текста кнопки'
            )),
			
				     new fieldList('payments_list_style', array(
                        'title' => 'Шаблон выбора системы оплаты',
                        'items' => array(
                            'basic' => 'Список кнопок',
                            'default' => 'Кнопка оплатить с выбором',
                        )
                    )),	
					
        );
    }
	
	public function parse($value){
		return self::renderDonateField($value);
    }

    public function parseTeaser($value){
       return self::renderDonateField($value, 'list');
    }

    public function getInput($value){
    	$user = cmsUser::getInstance();
        if(!$user->is_admin) { return ''; }
        else { return parent::getInput($value); }
    }

    public function renderDonateField($value, $type='item') {

        if (!$value || empty($this->item['id'])) { return ''; }

    	if(empty($this->item['ctype_name'])) {
    		$this->item['ctype_name'] = cmsCore::getInstance()->uri_controller;
    	}

    	$user = cmsUser::getInstance();
    	$template = cmsTemplate::getInstance();
    	$userpay = cmsCore::getController('userpay');

    	$target_id = !empty($this->getOption('target_id')) ?: $this->item['ctype_name'].$this->item['id'];
        $log_days = $this->getOption('log_days', false);
        $log_sort = $this->getOption('log_sort', 'new');

        $button_bg_color = empty($this->getOption('button_bg_color')) ? '' : 'background:'.$this->getOption('button_bg_color').';';
        $button_text_color = empty($this->getOption('button_text_color')) ? '' : 'color:'.$this->getOption('button_text_color').';';

        if($log_days){

            if($log_sort == 'new') $userpay->model->orderBy('id', 'desc');
            if($log_sort == 'max') $userpay->model->orderBy('amount', 'desc');
            if($log_sort == 'min') $userpay->model->orderBy('amount');

            if(empty($this->getOption('ignore_id'))) $userpay->model->filterEqual('target_id', $target_id);
            
            $userpay->model->filterDateYounger('date_pub', $log_days, 'day');
            $userpay->model->joinUserLeft();

            $items = $userpay->model->get('userpay_donate');

        }

    	$link_href = href_to('userpay', 'form?order_id=uwd_'.$target_id.'_'.$user->id.'&amount='.$this->getOption('amount').'&fix_amount='.$this->getOption('fix_amount').'&payments_list_style='.$this->getOption('payments_list_style').'&order_name='.$this->getOption('target_text'));

		$tpl_file = $template->getTemplateFileName('controllers/userpay/fields/donate/index');
        
        ob_start();
        
        include($tpl_file);
        
        $html = ob_get_clean();
		
		return $html;

    }

    public function store($value, $is_submitted, $old_value = null){
        if(!is_numeric($value) || $value <= 1) { return 1; }
        else { return $value; }
    }

}
