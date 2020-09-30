<?php

class formUserpayOptions extends cmsForm {
	
	public $is_tabbed = true;
	
	public function init(){
		
		$options = array(
		
			array(
				'type' => 'fieldset',
				'title' => 'Настройки<script>$(function(){$("form").before("<div style=\"padding:5px 10px;background:#fff0de;border:solid 1px #f9d5d5;border-radius:4px;margin-bottom:10px;color:darksalmon;text-align:center;\">Ответы на вопросы по настройке и работе компонента, а так же по установке и настройке дополнительных платежных систем вы можете найти на сайте <a href=\"//instantcms.su\" target=\"_blank\">InstantCMS.su</a></div>")});</script>',
				'childs' => array(

					new fieldString('curr_short', array(
                        'title' => 'Валюта сайта',
                        'hint' => 'Сокращенное обозначение',
                        'default' => 'руб.',
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldString('curr_name', array(
                        'title' => 'Валюта сайта (склонения)',
                        'hint' => 'Пример: "рубль|рубля|рублей"',
                        'default' => 'рубль|рубля|рублей',
                        'rules' => array(
                            array('required')
                        )
                    )),

					new fieldList('in_notice', array( 
						'title' => 'Оповещать о входящих платежах',
						'is_multiple' => true,
						'generator' => function ($data) {
							$admins = array();
							$users_model = cmsCore::getModel('users');
							$users_model->filterEqual('is_admin', 1);
							$users_list = $users_model->getUsers();
							$admins = array_collection_to_list($users_list, 'id', 'nickname');														
							return $admins;
						}
					)),
					
					new fieldList('payments_list_style', array(
                        'title' => 'Шаблон выбора системы оплаты',
                        'items' => array(
                            'basic' => 'Список кнопок',
                            'default' => 'Кнопка оплатить с выбором',
                        )
                    )),

                    new fieldNumber('reg_bonus', array(
                        'title' => 'Пополнить баланс пользователя при регистрации',
                        'default' => 0
                    )),

                    new fieldNumber('invoice_day_limit', array(
                        'title' => 'За какое кол-во дней выводить счета на оплату?',
                        'hint' => 'По истечении этого времени счета скрываются из списка',
                        'default' => 1
                    )),
					
				)
				
			)
			
		);

		$systems = files_tree_to_array('system/controllers/userpay/actions/');
		if ($systems) {
			foreach ($systems as $value) {
				$value = str_replace('.php', '', $value);
				if(in_array($value, array('payment', 'form'))) continue;
				$childs = cmsCore::getController('userpay')->runExternalAction($value, array('options'));

				$mass_opt_start = array(
						new fieldCheckbox($value.'_on', array(
	                        'title' => 'Платежная система включена',
	                    )),
						new fieldString($value.'_name', array(
							'title' => 'Название',
							'default' => ucfirst($value),
							'rules' => array(
	                            array('required')
	                        )
						)),
						new fieldString($value.'_hint', array(
							'title' => 'Подсказка. Краткая информация о платежной системе',
							'default' => ''
						)),
						new fieldString($value.'_curr_short', array(
							'title' => 'Сокращенное название валюты платежной системы',
							'default' => 'руб.',
							'rules' => array(
	                            array('required')
	                        )
						))
					);

				$mass_opt_end = array(
						new fieldString($value.'_in', array(
							'title' => 'Курс внутренней валюты',
							'hint' => 'Сумма пополнения на одну единицу внутренней валюты', 
							'prefix' => '1 = ',
							'default' => 1
						)),
						new fieldNumber($value.'_fee', array(
							'title' => 'Фиксированная комиссия',
							'hint' => 'Прибавляется к сумме платежа, но не зачисляется на баланс'
						)),
						new fieldNumber($value.'_order', array(
							'title' => 'Порядковый номер для сортировки в списке платежных систем',
						))
					);

				$options[] = array(
					'type' => 'fieldset',
					'title' => ucfirst($value),
					'childs' => array_merge($mass_opt_start, $childs, $mass_opt_end)
				);
			}
		}

		$options[] = array(
				'type' => 'fieldset',
				'title' => 'Партнерская программа',
				'childs' => array(

					new fieldString('ref_links', array(
                        'title' => 'Партнерские ссылки',
                        'hint' => 'Можно указывать списком, через запятую. Например: ref,referal,r',
                        'default' => 'ref',
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldString('ref_inner_text', array(
                        'title' => 'Системное уведомление о переходе по партнерской ссылке',
                        'hint' => 'Не указывайте, если уведомление не требуется'
                    )),

                    new fieldString('ref_step', array(
                        'title' => 'Уровни партнерской программы',
						'hint' => 'Необходимо указать % вознаграждения за партнеров.<br />Каждый партнерский уровень через запятую.<br />Например 3-х уровневая партнерская программа: 10,5,2',
						'default' => 10,
						'rules' => array(
                            array('required'),
                        )
                    )),

                    new fieldNumber('ref_cookie_life', array(
                        'title' => 'Время жизни реферальной куки',
						'hint' => 'Кол-во дней',
						'default' => 30,
						'rules' => array(
                            array('required'),
                        )
                    )),
					
					new fieldString('ref_page', array(
                        'title' => 'Реферальная страница.',
						'hint' => 'Страница, которая откроется после перехода по реферальной ссылке.<br />Если не заполнено - главная страница.<br />Также страницу назначения можно передать в ссылке, передав вместе с партнерской ссылкой параметр page. Например: /ref/1?page=pages/about.html. Необходимо указать относительную ссылку внутри сайта, без начального "/" слеш.',
                    )),

                    new fieldCheckbox('ref_reg_notify', array(
                        'title' => 'Уведомлять пользователя о новом партнере?',
						'default' => true,
                    )),

                    new fieldText('ref_intro', array(
                        'title' => 'Текст на странице партнерской программы',
						'hint' => 'Выводится над содержимым части партнерской программы. Можно использовать html.',
                    )),

                    new fieldList('ref_pay', array(
                        'title' => 'За какие действия производить партнерские начисления?',
                        'is_multiple' => true,
                        'items' => array(
                        	'balance' => 'Пополнение баланса',
                        	'donate' => 'Оплата в поле или виджете Donate',
                        	'sbs' => 'Подписки',
                        	'pf' => 'Контент (поля)',
                        	'invoice' => 'Оплата счетов'
                        )
                    )),

                    new fieldNumber('ref_reg_bonus', array(
                        'title' => 'Пополнить баланс пользователя при регистрации по реф.ссылке',
                        'default' => 0
                    )),

                    new fieldNumber('ref_bonus', array(
                        'title' => 'Вознаграждение пользователя за регистрацию реферала',
                        'default' => 0
                    )),

                    new fieldCheckbox('ref_virtual_bonus', array(
                        'title' => 'Начислять партнерское вознаграждение на виртуальный счет, а не на основной',
                    )),
					
				)
				
			);

		return $options;
	
	}
	
}
