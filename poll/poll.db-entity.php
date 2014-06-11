<?php 
require_once NF_CORE . '/libs/db/db-manager.class.php';

class NF_DBE_Polls extends NF_DB_Entity {
    protected $name ='polls';
    protected $version = 1;
    protected $fields = array(
                            'id' => array(
                                'data_type'      => 'int',
                                'data_length'    => '11',
                                'primary_key'    => true,
                                'not_null'       => true,
                                'auto_increment' => true, 
                            ),
                            'user_id' => array(
                                'data_type'      => 'int',
                                'data_length'    => '11',
                                'not_null'       => true,
                            ),
                            'poll_id' => array(
                                'data_type'      => 'int',
                                'data_length'    => '11',
                                'not_null'       => true,
                            ),
                            'answer_id' => array(
                                'data_type'      => 'char',
                                'data_length'    => '50',
                            ),
                        );
}


global $DB_Manager;
global $db_entity_polls;
$db_entity_polls = new NF_DBE_Polls();
$DB_Manager->register_entity($db_entity_polls);
$DB_Manager->check_structure();


/*$entity_Message->update_entries(
                            array(
                                'id' => 15
                            ),
                            array(
                                'date' => 'NOW()',
                                'title' => 'U are "548"',
                            )
                        );
*/
/*$entity_Message->delete_entries(
                            array(
                                'id' => 15
                            )
                    );*/

/*$entity_Message->add_entries(array(
                                'date' => 'NOW()',
                                'title' => 'U are "548"',
                            ));*/

//var_dump($entity_Message->get_entry_by_id(16));

/*
$res = $entity_polls->get_entries(
                            false,
                            $orders = array(
                                'net_name' => 'ASC'
                            )
                        );


print_r($res);
*/
?>