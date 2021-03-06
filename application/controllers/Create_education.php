<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Contributor: Jinkee Po <pojinkee1@gmail.com>
 *         
 */
class Create_education extends CI_Capstone_Controller
{

        function __construct()
        {
                parent::__construct();
                show_404();
                $this->load->model('Education_model');
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('<span class="help-inline">', '</span> ');
                $this->breadcrumbs->unshift(2, lang('index_utility_label'), '#');
                $this->breadcrumbs->unshift(3, lang('create_education_heading'), 'create-education');
        }

        public function index()
        {
                if ($this->input->post('submit'))
                {
                        $id = $this->Education_model->from_form()->insert();
                        if ($id)
                        {
                                $this->session->set_flashdata('message', bootstrap_success('Education added!'));
                                redirect(site_url('educations'), 'refresh');
                        }
                }
                $this->_form_view();
        }

        private function _form_view()
        {
                $inputs['education_code']        = array(
                    'name'  => 'code',
                    'value' => $this->form_validation->set_value('code'),
                    'type'  => 'text',
                    'lang'  => 'create_education_code_label'
                );
                $inputs['education_description'] = array(
                    'name'  => 'description',
                    'value' => $this->form_validation->set_value('description'),
                    'type'  => 'text',
                    'lang'  => 'create_education_description_label'
                );

                $data['education_form'] = $this->form_boostrap('create-education', $inputs, 'create_education_heading', 'create_education_submit_button_label', 'info-sign', NULL, TRUE);
                $data['bootstrap']      = $this->_bootstrap();
                $this->render('admin/create_education', $data);
        }

        private function _bootstrap()
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
