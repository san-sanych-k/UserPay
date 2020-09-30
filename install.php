<?php

function install_package(){

    $core = cmsCore::getInstance();

    if(!isFieldExists('users', 'balance')){
        $core->db->query("ALTER TABLE `{#}users` ADD `balance` DECIMAL(10,2) UNSIGNED DEFAULT '0.00';");
    }

    if(!isFieldExists('users', 'balance2')){
        $core->db->query("ALTER TABLE `{#}users` ADD `balance2` DECIMAL(10,2) UNSIGNED DEFAULT '0.00';");
    }

    if(!isFieldExists('users', 'ref_id')){
        $core->db->query("ALTER TABLE `{#}users` ADD `ref_id` int(11) UNSIGNED DEFAULT NULL;");
    }

    return true;

}

function isFieldExists($table_name, $field) {
    
    $table_fields = getTableFields($table_name);
    
    return in_array($field, $table_fields, true);

}

function getTableFields($table) {
    
    $db = cmsDatabase::getInstance();
    $fields = array();
    $result = $db->query("SHOW COLUMNS FROM `{#}{$table}`");
    
    while($data = $db->fetchAssoc($result)){
        $fields[] = $data['Field'];
    }
    
    return $fields;

}