<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Lloric Mayuga Garcia <emorickfighter@gmail.com>
 */
class Migration_Curriculum_subjects extends CI_Migration
{


        const CI_DB_TABLE = 'curriculum_subjects';

        public function __construct($config = array())
        {
                parent::__construct($config);
        }

        private function _semesters()
        {
                $return = '';
                $this->load->helper('school');
                foreach (semesters() as $k => $v)
                {
                        $return .= '"' . $k . '",';
                }
                return trim($return, ',');
        }

        public function up()
        {
                $this->down();
                $fields = array(
                    'curriculum_subject_id'               => array(
                        'type'           => 'INT',
                        'constraint'     => 8,
                        'unsigned'       => TRUE,
                        'null'           => FALSE,
                        'auto_increment' => TRUE
                    ),
                    'curriculum_subject_year_level'       => array(
                        'type'       => 'TINYINT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => FALSE
                    ),
                    'curriculum_subject_semester'         => array(
                        'type' => 'ENUM(' . $this->_semesters() . ')',
                        'null' => FALSE
                    ),
//                    'curriculum_subject_units'            => array(
//                        'type'       => 'TINYINT',
//                        'constraint' => '11',
//                        'unsigned'   => TRUE,
//                        'null'       => FALSE
//                    ),
//                    'curriculum_subject_lecture_hours'    => array(
//                        'type'       => 'TINYINT',
//                        'constraint' => '11',
//                        'unsigned'   => TRUE,
//                        'null'       => FALSE
//                    ),
//                    'curriculum_subject_laboratory_hours' => array(
//                        'type'       => 'TINYINT',
//                        'constraint' => '11',
//                        'unsigned'   => TRUE,
//                        'null'       => FALSE
//                    ),
                    /**
                     * foreign
                     */
                    'curriculum_id'                       => array(//
                        'type'       => 'INT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => FALSE
                    ),
                    'subject_id'                          => array(//current subject
                        'type'       => 'INT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => FALSE
                    ),
                    'unit_id'     => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => TRUE
                    ),
//                    'subject_id_pre'                      => array(//pre-requisite
//                        'type'       => 'INT',
//                        'constraint' => '11',
//                        'unsigned'   => TRUE,
//                        'null'       => TRUE
//                    ),
//                    'subject_id_co'                       => array(//co-requisite
//                        'type'       => 'INT',
//                        'constraint' => '11',
//                        'unsigned'   => TRUE,
//                        'null'       => TRUE
//                    ),
                    //------------------------------------
                    'created_at'                          => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'null'       => FALSE
                    ),
                    'created_user_id'                     => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => FALSE
                    ),
                    'deleted_at'                          => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'null'       => TRUE
                    ),
                    'deleted_user_id'                     => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => TRUE
                    ),
                    'updated_at'                          => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'null'       => TRUE
                    ),
                    'updated_user_id'                     => array(
                        'type'       => 'INT',
                        'constraint' => '11',
                        'unsigned'   => TRUE,
                        'null'       => TRUE
                    )
                );



                $this->dbforge->add_key('curriculum_subject_id', TRUE);

                $this->dbforge->add_field($fields);
                $this->dbforge->create_table(self::CI_DB_TABLE, TRUE);
        }

        public function down()
        {
                $this->dbforge->drop_table(self::CI_DB_TABLE, TRUE);
        }

}
