<?php

class formWidgetUserpayDonateOptions extends cmsForm {

    public function init() {

        return array(

			array(
                'type' => 'fieldset',
                'title' => 'Форма',
                'childs' => array(
				
				    new fieldString('options:target_text', array(
                        'title' => 'Назначение платежа',
                        'default' => 'Оплата на сайте',
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldString('options:button_text', array(
                        'title' => 'Текст на кнопке',
                        'default' => 'Оплатить',
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldNumber('options:amount', array(
                        'title' => 'Сумма платежа'
                    )),

                    new fieldNumber('options:amount_summ', array(
                        'title' => 'Сумма сбора'
                    )),

                    new fieldCheckbox('options:fix_amount', array(
                        'title' => 'Фиксированная сумма'
                    )),

                    new fieldText('options:top_text', array(
                        'title' => 'Описание',
                    )),

                    new fieldNumber('options:id', array(
                        'title' => 'Числовой идентификатор сборщика',
                        'hint' => 'Подсказка: Разные виджеты с одинаковым идентификатором выводят одинаковые результаты',
                        'default' => 1,
                        'rules' => array(
                            array('required')
                        )
                    )),
					
					new fieldList('options:payments_list_style', array(
                        'title' => 'Шаблон выбора системы оплаты',
                        'items' => array(
                            'basic' => 'Список кнопок',
                            'default' => 'Кнопка оплатить с выбором',
                        )
                    )),	
					
					
				
				)
            ),
				 
				 
				 
				 
				 
				 
				array(
                'type' => 'fieldset',
                'title' => 'Список',
                'childs' => array(

           

                    new fieldNumber('options:log_days', array(
                        'title' => 'За какое кол-во дней выводить статистику платежей',
                        'hint' => 'Если не указано - не выводим',
                        'default' => 30,
                    )),

                    new fieldList('options:log_sort', array(
                        'title' => 'Сортировка статистики',
                        'items' => array(
                            'new' => 'Сначала новые платежи',
                            'max' => 'По наибольшему платежу',
                            'min' => 'По наименьшему платежу'
                        )
                    )),

                    new fieldCheckbox('options:ignore_id', array(
                        'title' => 'Игнорировать назначения платежа',
                        'hint' => 'Если отмечено - выводить все',
                    )),

                    new fieldColor('options:button_bg_color', array(
                        'title' => 'Цвет кнопки'
                    )),

                    new fieldColor('options:button_text_color', array(
                        'title' => 'Цвет текста кнопки'
                    )),
					
			
					
					
					
					
					
					

                )
            ),
			
			

        );

    }

}
