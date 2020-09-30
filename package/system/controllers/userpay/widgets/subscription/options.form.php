<?php

class formWidgetUserpaySubscriptionOptions extends cmsForm {

    public function init() {

        return array(

            array(
                'type' => 'fieldset',
                'title' => LANG_OPTIONS,
                'childs' => array(

                    new fieldList('options:plan', array(
                        'title' => 'Тариф подписки',
                        'generator' => function(){
                            $items = array();
                            $model = cmsCore::getModel('userpay');
                            $tree = $model->getSubscription();
                            if ($tree){
                                foreach($tree as $item){
                                    $items[$item['id']] = $item['title'];
                                }
                            }

                            return $items;

                        },
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldString('options:button', array(
                        'title' => 'Текст на кнопке',
                        'default' => 'Подписаться',
                        'rules' => array(
                            array('required')
                        )
                    )),

                    new fieldText('options:content', array(
                        'title' => 'Описание',
                        'hint' => 'Если не указано - выводится описание из настроек тарифа'
                    )),

                )
            ),

        );

    }

}
