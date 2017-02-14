<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_student extends CI_Capstone_Controller
{

        function __construct()
        {
                parent::__construct();
                $this->lang->load('ci_students');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span>');
                $this->breadcrumbs->unshift(2, 'Students', 'students');
        }

        public function index($id = NULL)
        {

                $this->load->library('student');
                $this->student->get($id = $this->input->get('student-id'));
                $this->breadcrumbs->unshift(3, 'Edit Student [ ' . $this->student->school_id . ' ]', 'edit-student?student-id=' . $this->student->id);
                /**
                 * setting up rules validations
                 */
                $this->form_validation->set_rules(array(
                    array(
                        'label' => lang('index_student_firstname_th'),
                        'field' => 'student_firstname',
                        'rules' => 'trim|required|human_name|min_length[1]|max_length[30]',
                    ),
                    array(
                        'label' => lang('index_student_middlename_th'),
                        'field' => 'student_middlename',
                        'rules' => 'trim|required|human_name|min_length[1]|max_length[30]',
                    ),
                    array(
                        'label' => lang('index_student_lastname_th'),
                        'field' => 'student_lastname',
                        'rules' => 'trim|required|human_name|min_length[1]|max_length[30]',
                    ),
                    array(
                        'label' => lang('index_student_gender_th'),
                        'field' => 'student_gender',
                        'rules' => 'trim|required|min_length[4]|max_length[6]',
                    ),
                    array(
                        'label' => lang('index_student_birthdate_th'),
                        'field' => 'student_birthdate',
                        'rules' => 'trim|required',
                    ),
                    array(
                        'label' => lang('index_student_permanent_address_th'),
                        'field' => 'student_permanent_address',
                        'rules' => 'trim|required|min_length[8]|max_length[100]',
                    ),
                    array(
                        'label' => lang('index_course_id_th'),
                        'field' => 'course_id',
                        'rules' => 'trim|required|is_natural_no_zero',
                    ),
                    array(
                        'label' => lang('index_student_birthplace_th'),
                        'field' => 'student_birthplace',
                        'rules' => 'trim|required|min_length[8]|max_length[100]',
                    ),
                    array(
                        'label' => lang('index_student_civil_status_th'),
                        'field' => 'student_civil_status',
                        'rules' => 'trim|required|min_length[3]|max_length[15]',
                    ),
                    array(
                        'label' => lang('index_student_nationality_th'),
                        'field' => 'student_nationality',
                        'rules' => 'trim|required|min_length[4]|max_length[20]',
                    ),
                    //--
                    array(
                        'label' => lang('index_student_guardian_fullname_th'),
                        'field' => 'student_guardian_fullname',
                        'rules' => 'trim|required|min_length[8]|max_length[100]',
                    ),
                    array(
                        'label' => lang('index_student_town_th'),
                        'field' => 'student_address_town',
                        'rules' => 'trim|min_length[3]|max_length[30]',
                    ),
                    array(
                        'label' => lang('index_student_region_th'),
                        'field' => 'student_address_region',
                        'rules' => 'trim|min_length[8]|max_length[100]',
                    ),
                    array(
                        'label' => lang('index_student_guardian_address_th'),
                        'field' => 'student_guardian_address',
                        'rules' => 'trim|min_length[8]|max_length[100]',
                    ),
                    array(
                        'label' => lang('index_student_personal_contact_th'),
                        'field' => 'student_personal_contact_number',
                        'rules' => 'trim|min_length[8]|max_length[100]',
                    ),
                    array(
                        'label' => lang('index_student_guardian_contact_th'),
                        'field' => 'student_guardian_contact_number',
                        'rules' => 'trim|min_length[8]|max_length[100]',
                    ),
                    //--------
                    array(
                        'label' => lang('index_student_year_level_th'),
                        'field' => 'enrollment_year_level',
                        'rules' => 'trim|required|is_natural_no_zero',
                    ),
                    array(
                        'label' => lang('index_student_school_year_th'),
                        'field' => 'enrollment_school_year',
                        'rules' => 'trim|required',
                    ), array(
                        'label' => lang('index_student_semesterl_th'),
                        'field' => 'enrollment_semester',
                        'rules' => 'trim|required',
                    ),
                    //-----email
                    array(
                        'label' => lang('index_student_personal_email_th'),
                        'field' => 'student_personal_email',
                        'rules' => 'trim|max_length[50]|valid_email' .
                        (($this->student->email != $this->input->post('student_personal_email', TRUE)) ?
                                '|is_unique[students.student_personal_email]' : ''),
                    ),
                    array(
                        'label' => lang('index_student_guardian_email_th'),
                        'field' => 'student_guardian_email',
                        'rules' => 'trim|max_length[50]|valid_email' .
                        (($this->student->guardian_email != $this->input->post('student_guardian_email', TRUE)) ?
                                '|is_unique[students.student_guardian_email]' : ''),
                    )
                ));


                /**
                 * preparing config for image upload
                 */
                $config = array(
                    'encrypt_name'  => TRUE,
                    'upload_path'   => $this->config->item('student_image_dir'),
                    'allowed_types' => 'jpg|png|jpeg',
                    'max_size'      => "1000KB",
                    'max_height'    => "768",
                    'max_width'     => "1024"
                );

                /**
                 * load upload library including configuration for upload
                 */
                $this->load->library('upload', $config);

                /**
                 * for image upload
                 */
                $uploaded            = TRUE;
                $update_imge         = FALSE;
                $image_error_message = '';
                $run__               = $this->form_validation->run();

                if (!empty($_FILES['student_image']['name']))
                {
                        /**
                         * check if has error in upload $_FILES[] and pass rule validation
                         */
                        if ($run__ && isset($_FILES['student_image']['error']) && $_FILES['student_image']['error'] != 4)
                        {
                                /**
                                 * now uploading, FALSE in failed
                                 */
                                $update_imge = $uploaded    = ($this->upload->do_upload('student_image'));

                                /**
                                 * if returned FALSE it means failed/error
                                 */
                                if (!$uploaded)
                                {
                                        /**
                                         * get error upload message
                                         * with error delimiter in ion_auth config
                                         */
                                        $image_error_message = $this->config->item('error_start_delimiter', 'ion_auth') .
                                                $this->upload->display_errors() .
                                                $this->config->item('error_end_delimiter', 'ion_auth');
                                }
                        }
                }

                /**
                 * checking for validation and upload
                 * if all rules pass, 
                 */
                if ($run__ && $uploaded)
                {

                        /**
                         * preparing data into array
                         */
                        $student__ = array(
                            'student_firstname'               => $this->input->post('student_firstname', TRUE),
                            'student_middlename'              => $this->input->post('student_middlename', TRUE),
                            'student_lastname'                => $this->input->post('student_lastname', TRUE),
                            'student_gender'                  => $this->input->post('student_gender', TRUE),
                            'student_permanent_address'       => $this->input->post('student_permanent_address', TRUE),
                            'student_birthdate'               => $this->input->post('student_birthdate', TRUE),
                            'student_birthplace'              => $this->input->post('student_birthplace', TRUE),
                            'student_civil_status'            => $this->input->post('student_civil_status', TRUE),
                            'student_nationality'             => $this->input->post('student_nationality', TRUE),
                            //==
                            'student_guardian_fullname'       => $this->input->post('student_guardian_fullname', TRUE),
                            'student_address_town'            => $this->input->post('student_address_town', TRUE),
                            'student_address_region'          => $this->input->post('student_address_region', TRUE),
                            'student_guardian_address'        => $this->input->post('student_guardian_address', TRUE),
                            'student_personal_contact_number' => $this->input->post('student_personal_contact_number', TRUE),
                            'student_guardian_contact_number' => $this->input->post('student_guardian_contact_number', TRUE),
                            'student_personal_email'          => $this->input->post('student_personal_email', TRUE),
                            'student_guardian_email'          => $this->input->post('student_guardian_email', TRUE),
                            /**
                             * who update the data
                             */
                            'updated_user_id'                 => $this->ion_auth->user()->row()->id,
                        );
                        if ($update_imge)
                        {
                                $student__['student_image'] = $this->upload->data()['file_name'];
                        }

                        $this->load->model(array('Student_model', 'Enrollment_model'));


                        /**
                         * check if id is ready
                         * else nothing to do
                         */
                        if ($this->Student_model->update($student__, $this->student->id))
                        {

                                /**
                                 * preparing data into array
                                 */
                                $enrollmet__ = array(
                                    'student_id'             => $this->student->id,
                                    'course_id'              => $this->input->post('course_id', TRUE),
                                    'enrollment_school_year' => $this->input->post('enrollment_school_year', TRUE),
                                    'enrollment_semester'    => $this->input->post('enrollment_semester', TRUE),
                                    'enrollment_year_level'  => $this->input->post('enrollment_year_level', TRUE),
                                    /**
                                     * who update the data
                                     */
                                    'updated_user_id'        => $this->ion_auth->user()->row()->id,
                                );

                                /**
                                 * get education by course id
                                 */
                                /**
                                 * on success will redirect in current page, to clear input
                                 * 
                                 * else on failed
                                 * exist student id will delete from student table to rollback data
                                 */
                                if ($this->Enrollment_model->update($enrollmet__, $this->student->enrollment_id))
                                {
                                        /**
                                         * setting flash data, (once pop out, it will delete) using session
                                         */
                                        $this->session->set_flashdata('message', $this->config->item('message_start_delimiter', 'ion_auth') . lang('update_student_succesfully_added_message') . $this->config->item('message_end_delimiter', 'ion_auth'));

                                        /**
                                         * redirecting in current_url
                                         */
                                        redirect(base_url('students'), 'refresh');
                                }
                                else
                                {

                                        /**
                                         * deleting student
                                         */
                                        //$this->Student_model->delete($returned_student_id);
                                }
                        }
                }

                /**
                 * if reach here, load the model, etc...
                 */
                $this->load->model('Course_model');
                $this->load->helper('combobox');


                $this->data['message'] = $image_error_message . ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'));

                $this->data['student_image'] = array(
                    'name' => 'student_image',
                );

                $this->data['student_firstname']  = array(
                    'name'  => 'student_firstname',
                    'id'    => 'student_firstname',
                    'value' => $this->form_validation->set_value('student_firstname', $this->student->firstname),
                );
                $this->data['student_middlename'] = array(
                    'name'  => 'student_middlename',
                    'id'    => 'student_middlename',
                    'value' => $this->form_validation->set_value('student_middlename', $this->student->middlename),
                );

                $this->data['student_lastname'] = array(
                    'name'  => 'student_lastname',
                    'id'    => 'student_lastname',
                    'value' => $this->form_validation->set_value('student_lastname', $this->student->lastname),
                );

                $this->data['student_school_id'] = array(
                    'student_school_id' => $this->student->school_id
                );

                $this->data['student_gender']    = array(
                    'name'  => 'student_gender',
                    'id'    => 'student_gender',
                    'value' => $this->form_validation->set_value('student_gender', $this->student->gender),
                );
                $this->data['student_birthdate'] = array(
                    'name'             => 'student_birthdate',
                    'data-date-format' => 'mm-dd-yyyy',
                    'class'            => 'datepicker',
                    //  'data-date'        => "01-02-2013",
                    'value'            => $this->form_validation->set_value('student_birthdate', $this->student->birthdate),
                );



                $this->data['student_birthplace'] = array(
                    'name'  => 'student_birthplace',
                    'id'    => 'student_birthplace',
                    'value' => $this->form_validation->set_value('student_birthplace', $this->student->birthplace),
                );

                $this->data['student_civil_status'] = array(
                    'name'  => 'student_civil_status',
                    'id'    => 'student_civil_status',
                    'value' => $this->form_validation->set_value('student_civil_status', $this->student->civil_status),
                );

                $this->data['student_nationality'] = array(
                    'name'  => 'student_nationality',
                    'id'    => 'student_nationality',
                    'value' => $this->form_validation->set_value('student_nationality', $this->student->nationality),
                );



                $this->data['student_permanent_address'] = array(
                    'name'  => 'student_permanent_address',
                    'id'    => 'student_permanent_address',
                    'value' => $this->form_validation->set_value('student_permanent_address', $this->student->address),
                );
                $this->data['student_school_id_temp']    = array(
                    'name'     => 'student_school_id_temp',
                    'id'       => 'student_school_id_temp',
                    'disabled' => '',
                    'value'    => $this->student->school_id,
                );
                $this->data['course_id_value']           = $this->Course_model->as_dropdown('course_code')->get_all();

                $this->data['enrollment_year_level_value']  = _numbers_for_drop_down(0, $this->config->item('max_year_level'));
                $this->data['enrollment_semester_value']    = my_semester_for_combo();
                $this->data['enrollment_school_year_value'] = my_schoolyear_for_combo();

                //++++++++++++++++++++++++++++++++++++++=
                $this->data['student_guardian_fullname'] = array(
                    'name'  => 'student_guardian_fullname',
                    'id'    => 'student_guardian_fullname',
                    'value' => $this->form_validation->set_value('student_guardian_fullname', $this->student->guardian_fullname),
                );


                $this->data['student_address_town'] = array(
                    'name'  => 'student_address_town',
                    'id'    => 'student_address_town',
                    'value' => $this->form_validation->set_value('student_address_town', $this->student->town),
                );



                $this->data['student_address_region'] = array(
                    'name'  => 'student_address_region',
                    'id'    => 'student_address_region',
                    'value' => $this->form_validation->set_value('student_address_region', $this->student->region),
                );



                $this->data['student_guardian_address'] = array(
                    'name'  => 'student_guardian_address',
                    'id'    => 'student_guardian_address',
                    'value' => $this->form_validation->set_value('student_guardian_address', $this->student->guardian_adrress),
                );



                $this->data['student_personal_contact_number'] = array(
                    'name'  => 'student_personal_contact_number',
                    'id'    => 'student_personal_contact_number',
                    'value' => $this->form_validation->set_value('student_personal_contact_number', $this->student->contact),
                );



                $this->data['student_guardian_contact_number'] = array(
                    'name'  => 'student_guardian_contact_number',
                    'id'    => 'student_guardian_contact_number',
                    'value' => $this->form_validation->set_value('student_guardian_contact_number', $this->student->guardian_contact),
                );



                $this->data['student_personal_email'] = array(
                    'name'  => 'student_personal_email',
                    'id'    => 'student_personal_email',
                    'value' => $this->form_validation->set_value('student_personal_email', $this->student->email),
                );


                $this->data['student_guardian_email'] = array(
                    'name'  => 'student_guardian_email',
                    'id'    => 'student_guardian_email',
                    'value' => $this->form_validation->set_value('student_guardian_email', $this->student->guardian_email),
                );

                $this->data['bootstrap'] = $this->bootstrap();
                $this->_render_admin_page('admin/edit_student', $this->data);
        }

        /**
         * 
         * @return array
         *  @author Lloric Garcia <emorickfighter@gmail.com>
         */
        private function bootstrap()
        {
                /**
                 * for header
                 */
                $header       = array(
                    'css' => array(
                        'css/bootstrap.min.css',
                        'css/bootstrap-responsive.min.css',
                        'css/fullcalendar.css',
                        'css/matrix-style.css',
                        'css/matrix-media.css',
                        'font-awesome/css/font-awesome.css',
                        'css/jquery.gritter.css',
                        'css/jquery.gritter.css',
                        'css/uniform.css',
                        'css/select2.css',
                        'http://fonts.googleapis.com/css?family=Open+Sans:400,700,800'
                    ),
                    'js'  => array(
                    ),
                );
                /**
                 * for footer
                 */
                $footer       = array(
                    'css' => array(
                    ),
                    'js'  => array(
                        'js/jquery.min.js',
                        'js/jquery.ui.custom.js',
                        'js/bootstrap.min.js',
                        'js/bootstrap-colorpicker.js',
                        'js/bootstrap-datepicker.js',
                        'js/jquery.toggle.buttons.js',
                        'js/masked.js',
                        'js/jquery.uniform.js',
                        'js/select2.min.js',
                        'js/matrix.js',
                        'js/matrix.form_common.js',
                        'js/wysihtml5-0.3.0.js',
                        'js/jquery.peity.min.js',
                        'js/bootstrap-wysihtml5.js',
                    ),
                );
                /**
                 * footer extra
                 */
                $footer_extra = '<script type="text/javascript">
        // This function is called from the pop-up menus to transfer to
        // a different page. Ignore if the value returned is a null string:
        function goPage(newURL) {

            // if url is empty, skip the menu dividers and reset the menu selection to default
            if (newURL != "") {

                // if url is "-", it is this page -- reset the menu:
                if (newURL == "-") {
                    resetMenu();
                }
                // else, send page to designated URL            
                else {
                    document.location.href = newURL;
                }
            }
        }

        // resets the menu selection upon entry to this page:
        function resetMenu() {
            document.gomenu.selector.selectedIndex = 2;
        }
</script>';
                return generate_link_script_tag($header, $footer, $footer_extra);
        }

}