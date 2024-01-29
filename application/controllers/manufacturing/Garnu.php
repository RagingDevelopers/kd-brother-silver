<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Garnu extends CI_Controller
{
    public $form_validation, $input, $db;

    const View = "admin/manufacturing/garnu_report";
    const ADD = "admin/manufacturing/garnu";
    public function __construct()
    {
        parent::__construct();
        check_login();
        library("dbh");
        library("Joinhelper");
    }

    public function index($action = "", $id = null)
    {
        $page_data['page_title'] = 'Garnu';
        switch ($action) {
            case "":
                $page_data['data'] = $this->dbh->getResultArray('garnu');
                $page_data['metal_type'] = $this->dbh->findAll('metal_type');

                return view(self::View, $page_data);
            case "add":
                $page_data['data'] = $this->dbh->getResultArray('garnu');
                return view(self::ADD, $page_data);

            case "edit":
                $this->validateId($id);
                $garnu = $this->dbh->find('garnu', $id);
                if (!$garnu) {
                    flash()->withError("Garnu type Not Found")->to('manufacturing/garnu');
                }
                // $page_data['data'] = $this->joinhelper->fetchJoinedTable('customer', ['city', 'account_type']);
                $page_data['items'] = $this->dbh->getWhereResultArray('garnu_item', ['garnu_id' => $id]);
                $page_data['update'] = $garnu;

                // pre($page_data, true);
                return view(self::ADD, $page_data);

            case "store":
                // $post = xss_clean($this->input->post());
                // pre($post);
                // die;
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required')
                    ->set_rules('garnu_weight', 'garnu_weight', 'required')
                    ->set_rules('touchs', 'touch', 'required')
                    ->set_rules('silvers', 'silver', 'required')
                    ->set_rules('coppers', 'copper', 'required')
                    ->set_rules('total_used_weight', 'total_used_weight', 'required')
                    ->set_rules('total_unused_weight', 'total_unused_weight', 'required')
                    ->set_rules('total_used_silver', 'total_used_silver', 'required')
                    ->set_rules('remaining_silver', 'remaining_silver', 'required')
                    ->set_rules('total_used_copper', 'total_used_copper', 'required')
                    ->set_rules('remaining_copper', 'remaining_copper', 'required')
                    ->set_rules('metal_type_id[]', 'metal_type_id', 'required')
                    ->set_rules('weight[]', 'weight', 'required')
                    ->set_rules('touch[]', 'touch', 'required')
                    ->set_rules('copper[]', 'copper', 'required')
                    ->set_rules('silver[]', 'silver', 'required');

                if (!$validation->run()) {
                    return flash()->withError(validation_errors())->back();
                }
                $post = xss_clean($this->input->post());
                $garnu = array();
                $garnu['name'] = $post['name'];
                $garnu['garnu_weight'] = $post['garnu_weight'];
                $garnu['touch'] = $post['touchs'];
                $garnu['silver'] = $post['silvers'];
                $garnu['copper'] = $post['coppers'];
                $garnu['total_used_weight'] = $post['total_used_weight'];
                $garnu['total_unused_weight'] = $post['total_unused_weight'];
                $garnu['total_used_silver'] = $post['total_used_silver'];
                $garnu['remaining_silver'] = $post['remaining_silver'];
                $garnu['total_used_copper'] = $post['total_used_copper'];
                $garnu['remaining_copper'] = $post['remaining_copper'];
                $garnu['creation_date'] = date('Y-m-d');

                $this->db->insert('garnu', $garnu);
                $garnu_id = $this->db->insert_id();


                $length = count($post['metal_type_id']);

                $garnu_item = $new = array();
                for ($i = 0; $i < $length; $i++) {
                    $garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
                    $garnu_item['weight'] = $post['weight'][$i];
                    $garnu_item['touch'] = $post['touch'][$i];
                    $garnu_item['silver'] = $post['silver'][$i];
                    $garnu_item['copper'] = $post['copper'][$i];
                    $garnu_item['garnu_id'] = $garnu_id;
                    $new[] = $garnu_item;
                }

                $this->db->insert_batch('garnu_item', $new);

                flash()->withSuccess("Garnu type Added Successfully")->to("manufacturing/garnu");
                break;

            // case "delete":
            //     die("not permission to delete");
            //     // checkPrivilege(privilege["garnu_delete"]);
            //     $this->validateId($id);
            //     $this->dbh->deleteRow('garnu', $id);
            //     flash()->withSuccess("garnu type Deleted Successfully")->back();
            //     break;
            case "update":
                // checkPrivilege(privilege["garnu_edit"]);
                $validation = $this->form_validation;
                $validation->set_rules('name', 'Name', 'required')
                    ->set_rules('garnu_weight', 'garnu_weight', 'required')
                    ->set_rules('touchs', 'touch', 'required')
                    ->set_rules('silvers', 'silver', 'required')
                    ->set_rules('coppers', 'copper', 'required')
                    ->set_rules('total_used_weight', 'total_used_weight', 'required')
                    ->set_rules('total_unused_weight', 'total_unused_weight', 'required')
                    ->set_rules('total_used_silver', 'total_used_silver', 'required')
                    ->set_rules('remaining_silver', 'remaining_silver', 'required')
                    ->set_rules('total_used_copper', 'total_used_copper', 'required')
                    ->set_rules('remaining_copper', 'remaining_copper', 'required')
                    ->set_rules('metal_type_id[]', 'metal_type_id', 'required')
                    ->set_rules('weight[]', 'weight', 'required')
                    ->set_rules('touch[]', 'touch', 'required')
                    ->set_rules('copper[]', 'copper', 'required')
                    ->set_rules('silver[]', 'silver', 'required');


                if ($validation->run() == false) {
                    return flash()->withError(validation_errors())->back();
                }
                $post = xss_clean($this->input->post());

                // pre($post);
                // die;
                $update = array();
                $update['name'] = $post['name'];
                $update['garnu_weight'] = $post['garnu_weight'];
                $update['touch'] = $post['touchs'];
                $update['silver'] = $post['silvers'];
                $update['copper'] = $post['coppers'];
                $update['total_used_weight'] = $post['total_used_weight'];
                $update['total_unused_weight'] = $post['total_unused_weight'];
                $update['total_used_silver'] = $post['total_used_silver'];
                $update['remaining_silver'] = $post['remaining_silver'];
                $update['total_used_copper'] = $post['total_used_copper'];
                $update['remaining_copper'] = $post['remaining_copper'];
                $update['creation_date'] = date('Y-m-d');

                $this->db->where('id', $id)->update('garnu', $update);

                $oldIds = $this->db->select('id')->get_where('garnu_item', ['garnu_id' => $id])->result_array();
                $deleteIds = [];
                foreach ($oldIds as $row) {
                    if (!in_array($row['id'], $post['rowid'])) {
                        $deleteIds[] = $row['id'];
                    }
                }
                
                if (count($deleteIds) > 0) {
                    $this->dbh->deleteRow('garnu_item', $deleteIds);
                }
                $length = count($post['metal_type_id']);

                for ($i = 0; $i < $length; $i++) {
                    $garnu_item = array();
                    $garnu_item['metal_type_id'] = $post['metal_type_id'][$i];
                    $garnu_item['weight'] = $post['weight'][$i];
                    $garnu_item['touch'] = $post['touch'][$i];
                    $garnu_item['silver'] = $post['silver'][$i];
                    $garnu_item['copper'] = $post['copper'][$i];
                    if ($post['rowid'][$i] > 0) {
                        if ($this->dbh->isDataExists('garnu_item', ['id' => $post['rowid'][$i], 'garnu_id' => $id])) {
                            $this->db->where(['garnu_id' => $id, 'id' => $post['rowid'][$i]])->update('garnu_item', $garnu_item);
                        }
                    } else if ($post['rowid'][$i] == 0) {
                        $garnu_item['garnu_id'] = $id;
                        $this->db->insert('garnu_item', $garnu_item);
                    }
                }
                flash()->withSuccess("Garnu Updated Successfully")->to("manufacturing/garnu");
                break;
            default:
                return flash()->withError("Invalid Arguments")->back();
        }
    }
    private function validateId($id)
    {
        (!is_numeric($id) || empty($id)) && flash()->withError("invalid id please enter valid Id")->to("manufacturing/garnu");
    }
}
