<?php

class onUserpayUserDelete extends cmsAction {

    public function run($user){

        $this->model->deletePartnerUsers($user['id']);
        $this->model->updatePartnerFields($user['id']);

        return $user;

    }

}
