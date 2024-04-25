<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Process extends CI_Controller
{
    const View = "admin/manufacturing/process";
    const receiveGarnu = "admin/manufacturing/receive_garnu";
    const printGivenData = "admin/manufacturing/print/print_given_data";

    public function __construct()
    {
        parent::__construct();
        check_login();
        $this->load->model('manufacturing/Process_model', "modal");
    }

    public function manage($id = null, $pid = null)
    {
        $page_data['id']   = $id;
        $page_data['data'] = $this->db->select('*')->from('garnu')->where('id', $id)->get()->row_array();
        if (empty($page_data['data'])) {
            $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('dashboard');
            flash()->withError("Data Not Found .")->to($referrer);
        }
        $page_data['process_data']       = $this->db->select('*')->from('given')->where('id', $pid)->get()->row_array();
        $page_data['given_row_material'] = $this->db->select('*')->from('given_row_material')->where(array('given_id' => $pid, 'garnu_id' => $id ))->get()->result_array();
        $page_data['row_material']       = $this->db->select('id,name')->from('row_material')->where('status', "ACTIVE")->get()->result_array();
        $page_data['metal_type']         = $this->db->select('id,name')->from('metal_type')->get()->result_array();
        if (!empty($pid)) {
            $page_data['receiveCode'] = $this->db->select('id, code')->from('receive')->where(array( 'garnu_id' => $id ))->order_by('code', 'DESC')->get()->result_array();
        } else {
            $page_data['receiveCode'] = $this->db->select('id, code')->from('receive')->where(array( 'garnu_id' => $id, 'is_full' => 'NO' ))->order_by('code', 'DESC')->get()->result_array();
        }
        if (!empty($pid)) {
            $page_data['lot_wise_rm'] = $this->db->select('id, code,rem_weight,rem_quantity')->from('lot_wise_rm')->order_by('id', 'DESC')->get()->result_array();
        } else {
            $page_data['lot_wise_rm'] = $this->db->select('id, code,rem_weight,rem_quantity')->from('lot_wise_rm')->where(array( 'is_complated' => 'NO' ))->order_by('id', 'DESC')->get()->result_array();
        }
        $page_data['receiveLot_wise_rm'] = $this->db->select('id, code,weight,quantity,rem_weight,rem_quantity')->from('lot_wise_rm')->order_by('id', 'DESC')->get()->result_array();
        $page_data['table']              = $this->db->select('given.*,customer.name AS customer_name, process.name AS process_name')->from('given')->where('garnu_id', $id)->join('process', 'given.process_id = process.id', 'left')->join('customer', 'given.worker_id = customer.id', 'left')->get()->result();
        $page_data['page_title']         = 'Process';
        $page_data['process']            = $this->modal->fetch_process();
        return view(self::View, $page_data);
    }

    public function getWorkers()
    {
        $post = xss_clean($this->input->post());
        $data = $this->db->select('id,name')->from('customer')->where(array( 'process_id' => $post['process_id'], 'account_type_id' => 2 ))->get()->result_array();
        echo json_encode($data);
    }

    public function fechWeight()
    {
        $post = xss_clean($this->input->post());
        $data = $this->db->select('total_weight')->get_where('receive', array( 'code' => $post['code'] ))->row_array();
        if (!empty($data)) {
            $response = [ 'success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data ];
        } else {
            $response = [ 'success' => false, 'message' => 'Data Fetched Failed.' ];
        }
        echo json_encode($response);
        return;
    }

    public function fechCode()
    {
        $post = xss_clean($this->input->post());
        $id   = $post['garnu_id'];
        if (!empty($pid)) {
            $data = $this->db->select('id, code')->from('receive')->where(array( 'garnu_id' => $id ))->order_by('code', 'DESC')->get()->result_array();
        } else {
            $data = $this->db->select('id, code')->from('receive')->where(array( 'garnu_id' => $id, 'is_full' => 'NO' ))->order_by('code', 'DESC')->get()->result_array();
        }
        if (!empty($data)) {
            $response = [ 'success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data ];
        } else {
            $response = [ 'success' => false, 'message' => 'Data Fetched Failed.' ];
        }
        echo json_encode($response);
    }

    public function add()
    {
        $validation = $this->form_validation;
        $validation->set_rules('name', 'Garnu Name', 'required')
            ->set_rules('weight', 'Garnu Weight', 'required')
            ->set_rules('process', 'Process', 'required')
            ->set_rules('workers', 'Workers', 'required')
            ->set_rules('remarks', 'Remark', 'trim')
            ->set_rules('given_qty', 'Given Quantity', 'required')
            ->set_rules('given_weight', 'Given Weight', 'required')
            ->set_error_delimiters('<div class="text-danger">', '</div>');

        if (!$validation->run()) {
            return flash()->withError(validation_errors())->back();
        }

        $post = xss_clean($this->input->post());

        $data                        = array();
        $data['user_id']             = session('id');
        $data['garnu_id']            = $post['garnu_id'];
        $data['process_id']          = $post['process'];
        $data['worker_id']           = $post['workers'];
        $data['remarks']             = $post['remarks'];
        $data['given_qty']           = $post['given_qty'];
        $data['given_weight']        = $post['given_weight'];
        $data['row_material_weight'] = $post['total-rm_weight'];
        $data['total_weight']        = $post['total_weight'];
        $data['receive_code']        = isset($post['receive_code']) ? $post['receive_code'] : '';
        $data['creation_date']       = date('Y-m-d');
        $this->db->insert('given', $data);
        $given_id = $this->db->insert_id();

        // if (isset($post['receive_code']) && !empty($post['receive_code'])) {
        // 	$totalWeight = $this->db->select('given_weight')->from('given')->where(array('receive_code' => $post['receive_code']))->get()->result_array();
        // 	$total_weight = 0;
        // 	foreach ($totalWeight as $weight) {
        // 		$total_weight += $weight['given_weight'];
        // 	}

        // 	$receiveWeight = $this->db->select('weight')->from('receive')->where('code', $post['receive_code'])->get()->row_array();

        // 	if ($total_weight >= $receiveWeight['weight']) {
        // 		$this->db->where('code', $post['receive_code'])->update('receive', ['is_full' => "YES"]);
        // 	}
        // }

        $batchData = [];
        foreach ($post['rowid'] as $key => $rowid) {
            if (!empty($post['row_material'][$key]) && !empty($post['rmTouch'][$key]) || !empty($post['rmWeight'][$key]) || !empty($post['rmQuantity'][$key])) {
                $rmData = [
                    'user_id'         => session('id'),
                    'given_id'        => $given_id,
                    'garnu_id'        => isset($post['garnu_id']) ? $post['garnu_id'] : null,
                    'row_material_id' => isset($post['row_material'][$key]) ? $post['row_material'][$key] : null,
                    'lot_wise_rm_id'  => isset($post['lot_wise_rm_id'][$key]) ? $post['lot_wise_rm_id'][$key] : 0,
                    'touch'           => isset($post['rmTouch'][$key]) ? $post['rmTouch'][$key] : null,
                    'weight'          => isset($post['rmWeight'][$key]) ? $post['rmWeight'][$key] : null,
                    'quantity'        => isset($post['rmQuantity'][$key]) ? $post['rmQuantity'][$key] : null,
                    'creation_date'   => date('Y-m-d'),
                ];
                if (!empty($post['lot_wise_rm_id'][$key])) {
                    $this->db->where('id', $post['lot_wise_rm_id'][$key])
                        ->set('given_weight', 'given_weight + ' . $rmData['weight'], false)
                        ->set('given_quantity', 'given_quantity + ' . $rmData['quantity'], false)
                        ->set('rem_weight', 'rem_weight - ' . $rmData['weight'], false)
                        ->set('rem_quantity', 'rem_quantity - ' . $rmData['quantity'], false);
                    $this->db->update('lot_wise_rm');
                    $weight = $this->db->select('rem_weight')->get_where('lot_wise_rm', array( 'id' => $post['lot_wise_rm_id'][$key] ))->row_array();
                    if ($weight['rem_weight'] == 0) {
                        // Update is_complated to YES
                        $this->db->where('id', $post['lot_wise_rm_id'][$key])->update('lot_wise_rm', [ 'is_complated' => 'YES' ]);
                    }
                }
                $batchData[] = $rmData;
            }
        }
        // echo "<pre>";
        // print_r($batchData);exit;
        if (!empty($batchData)) {
            $this->db->insert_batch('given_row_material', $batchData);
        }
        flash()->withSuccess("Garnu Added Successfully.")->back();
    }

    public function update()
    {
        $id = $this->input->post('given_id');

        $validation = $this->form_validation;
        $validation->set_rules('name', 'Garnu Name', 'required')
            ->set_rules('weight', 'Garnu Weight', 'required')
            ->set_rules('process', 'Process', 'required')
            ->set_rules('workers', 'Workers', 'required')
            ->set_rules('remarks', 'Remark', 'trim')
            ->set_rules('given_qty', 'Given Quantity', 'required')
            ->set_rules('given_weight', 'Given Weight', 'required')
            ->set_rules('total-rm_weight', 'Row Material Weight', 'trim')
            ->set_rules('total_weight', 'Final Weight', 'trim')
            ->set_error_delimiters('<div class="text-danger">', '</div>');

        if (!$validation->run()) {
            return flash()->withError(validation_errors())->back();
        }
        $post = xss_clean($this->input->post());

        $data                        = array();
        $data['garnu_id']            = $post['garnu_id'];
        $data['process_id']          = $post['process'];
        $data['worker_id']           = $post['workers'];
        $data['remarks']             = $post['remarks'];
        $data['given_qty']           = $post['given_qty'];
        $data['given_weight']        = $post['given_weight'];
        $data['row_material_weight'] = $post['total-rm_weight'];
        $data['total_weight']        = $post['total_weight'];
        $data['receive_code']        = $post['receive_code'];
        $this->db->where('id', $id)->update('given', $data);

        // if (isset($post['receive_code']) && !empty($post['receive_code'])) {
        // 	$totalWeight = $this->db->select('given_weight')->from('given')->where(array('receive_code' => $post['receive_code']))->get()->result_array();
        // 	$total_weight = 0;
        // 	foreach ($totalWeight as $weight) {
        // 		$total_weight += $weight['given_weight'];
        // 	}

        // 	$receiveWeight = $this->db->select('weight')->from('receive')->where('code', $post['receive_code'])->get()->row_array();

        // 	if ($total_weight >= $receiveWeight['weight']) {
        // 		$this->db->where('code', $post['receive_code'])->update('receive', ['is_full' => "YES"]);
        // 	} else {
        // 		$this->db->where('code', $post['receive_code'])->update('receive', ['is_full' => "NO"]);
        // 	}
        // }

        $insertBatch = [];
        $updateBatch = [];
        $existingIds = isset($post['rowid']) ? $post['rowid'] : [];
        $allids      = isset($post['ids']) ? $post['ids'] : [];

        $idsNotExisting = array_diff($allids, $existingIds);
        if (!empty($idsNotExisting)) {
            $this->db->where_in('id', $idsNotExisting);
            $this->db->delete('given_row_material');
        }

        $rmData = [];
        foreach ($post['rowid'] as $key => $rowid) {

            if (!empty($post['row_material'][$key]) && !empty($post['rmTouch'][$key]) || !empty($post['rmWeight'][$key]) || !empty($post['rmQuantity'][$key])) {
                $rmData = [
                    'row_material_id' => isset($post['row_material'][$key]) ? $post['row_material'][$key] : null,
                    'touch'           => isset($post['rmTouch'][$key]) ? $post['rmTouch'][$key] : null,
                    'weight'          => isset($post['rmWeight'][$key]) ? $post['rmWeight'][$key] : null,
                    'quantity'        => isset($post['rmQuantity'][$key]) ? $post['rmQuantity'][$key] : null,
                ];
            }

            if ($rowid == 0) {
                if (!empty($post['row_material'][$key]) || !empty($post['rmTouch'][$key]) || !empty($post['rmWeight'][$key]) || !empty($post['rmQuantity'][$key])) {
                    $rmData['given_id']      = $post['given_id'];
                    $rmData['lot_wise_rm_id']      = $post['lot_wise_rm_id'][$key];
                    $rmData['user_id']       = session('id');
                    $rmData['creation_date'] = date('Y-m-d');
                    $rmData['garnu_id']      = isset($post['garnu_id']) ? $post['garnu_id'] : null;

                    if (!empty($post['lot_wise_rm_id'][$key])) {
                        $this->db->where('id', $post['lot_wise_rm_id'][$key])
                            ->set('given_weight', 'given_weight + ' . $rmData['weight'], false)
                            ->set('given_quantity', 'given_quantity + ' . $rmData['quantity'], false)
                            ->set('rem_weight', 'rem_weight - ' . $rmData['weight'], false)
                            ->set('rem_quantity', 'rem_quantity - ' . $rmData['quantity'], false);
                        $this->db->update('lot_wise_rm');
                        $weight = $this->db->select('rem_weight')->get_where('lot_wise_rm', array( 'id' => $post['lot_wise_rm_id'][$key] ))->row_array();
                        if ($weight['rem_weight'] == 0) {
                            // Update is_complated to YES
                            $this->db->where('id', $post['lot_wise_rm_id'][$key])->update('lot_wise_rm', [ 'is_complated' => 'YES' ]);
                        }
                    }
                    $insertBatch[] = $rmData;
                } else {
                    $insertBatch = [];
                }
            } else if (in_array($rowid, $existingIds)) {
                if (!empty($post['row_material'][$key]) || !empty($post['rmTouch'][$key]) || !empty($post['rmWeight'][$key]) || !empty($post['rmQuantity'][$key])) {
                    $rmData['id'] = $rowid;
                    $rmData['lot_wise_rm_id']      = $post['lot_wise_rm_id'][$key];

                    $givenRMData = $this->db->get_where('given_row_material', array( 'id' => $rowid ))->row_array();
                    if (!empty($post['lot_wise_rm_id'][$key])) {
                        $lwrmweight   = $rmData['weight'] - $givenRMData['weight'];
                        $lwrmquantity = $rmData['quantity'] - $givenRMData['quantity'];
                        if ($givenRMData['weight'] != $rmData['weight']) {
                            $this->db->where('id', $post['lot_wise_rm_id'][$key])
                                ->set('given_weight', 'given_weight + ' . $lwrmweight, false)
                                ->set('rem_weight', 'rem_weight - ' . $lwrmweight, false);
                            $this->db->update('lot_wise_rm');
                        }
                        if ($givenRMData['quantity'] != $rmData['quantity']) {
                            $this->db->where('id', $post['lot_wise_rm_id'][$key])
                                ->set('given_quantity', 'given_quantity + ' . $lwrmquantity, false)
                                ->set('rem_quantity', 'rem_quantity - ' . $lwrmquantity, false);
                            $this->db->update('lot_wise_rm');
                        }

                        $weight = $this->db->select('rem_weight')->get_where('lot_wise_rm', array( 'id' => $post['lot_wise_rm_id'][$key] ))->row_array();
                        if ($weight['rem_weight'] == 0) {
                            $this->db->where('id', $post['lot_wise_rm_id'][$key])->update('lot_wise_rm', ['is_complated' => 'YES' ]);
                        }
                    }
                }
                $updateBatch[] = $rmData;
            }
        }

        if (!empty($insertBatch)) {
            $this->db->insert_batch('given_row_material', $insertBatch);
        }
        if (!empty($updateBatch)) {
            $this->db->update_batch('given_row_material', $updateBatch, 'id');
        }
        flash()->withSuccess("Update Successfully.")->to("manufacturing/process/manage/" . $post['garnu_id']);
    }

    public function receiveGarnu()
    {
        $validation = $this->form_validation;
        $validation->set_rules('garnu_id', 'Garnu Id', 'required')
            ->set_rules('given_id', 'Given Id', 'required');

        if (!$validation->run()) {
            $response = [ 'success' => false, 'message' => validation_errors() ];
            echo json_encode($response);
            return;
        }

        $post     = $this->input->post();
        $garnu_id = $post['garnu_id'];
        $given_id = $post['given_id'];

        $page_data['garnuData'] = $this->dbh->getWhereRowArray('garnu', [ 'id' => $garnu_id ]);
        if (!empty($page_data['garnuData'])) {
            $page_data['givenData'] = $this->db->select('given.*,process.name AS process_name')
                ->from('given')
                ->join('process', 'given.process_id = process.id', 'left')
                ->where('given.id', $given_id)
                ->get()->row_array();
            if (!empty($page_data['givenData'])) {
                $page_data['metalData']    = $this->dbh->getWhereResultArray('process_metal_type', [ 'given_id' => $given_id ]);
                $page_data['receivedData'] = $this->dbh->getWhereResultArray('receive', [ 'given_id' => $given_id, 'garnu_id' => $garnu_id ]);
                if (!empty($page_data['receivedData']) || !empty($page_data['metalData']) || !empty($page_data['garnuData']) && !empty($page_data['givenData'])) {
                    $page_data['item']     = $this->db->select('*')->from('item')->get()->result_array();
                    $page_data['customer'] = $this->dbh->getWhereResultArray('customer', [ 'account_type_id' => 2, 'process_id' => $page_data['givenData']['process_id'] ]);
                    $view                  = $this->load->view(self::receiveGarnu, $page_data, true);
                    $response              = [ 'success' => true, 'message' => 'Data Feched Successfully', 'data' => $view ];
                } else {
                    $response = [ 'success' => false, 'message' => 'Data Not Found.' ];
                }
            } else {
                $response = [ 'success' => false, 'message' => 'Invalid Given Id' ];
            }
        } else {
            $response = [ 'success' => false, 'message' => 'Invalid Garnu Id' ];
        }

        echo json_encode($response);
        return;
    }

    public function receiveGarnuAdd()
    {
        $post           = $this->input->post();
        $existingIds    = isset($post['rcid']) ? $post['rcid'] : [];
        $allids         = isset($post['ids']) ? $post['ids'] : [];
        $idsNotExisting = array_diff($allids, $existingIds);
        $given_id       = $post['given_id'];
        $garnu_id       = $post['garnu_id'];
        $user_id        = session('id');

        $is_completed = isset($post['is_completed']) && $post['is_completed'] == 'on' ? "YES" : "NO";
        $is_kasar     = isset($post['is_kasar']) && $post['is_kasar'] == 'on' ? "YES" : "NO";

        $transfer_account = NULL;

        if ($is_kasar == "YES") {
            $transfer_account = ($post['transfer_account'] ?? NULL);
        }

        $this->db->where('id', $given_id)->update('given', [ 'vadharo_dhatado' => $post['jama_baki'], 'is_completed' => $is_completed, 'is_kasar' => $is_kasar, "transfer_account" => $transfer_account ]);


        if (!empty($idsNotExisting)) {
            $this->db->where_in('id', $idsNotExisting);
            $this->db->delete('receive');

            $this->db->where_in('received_id', $idsNotExisting);
            $this->db->delete('receive_row_material');
        }

        if ($post['pcs'][0] != 0 || $post['total_weight'][0] != 0 || $post['weight'][0] != 0 || $post['rm_weight'][0] != 0) {
            foreach ($post['rcid'] as $key => $rcid) {

                if ($post['pcs'][$key] != 0 || $post['rcid'][$key] != 0 || $post['raw-material-data'][$key] != "" || !empty($post['pcs'][$key]) || !empty($post['weight'][$key] || !empty($post['total_weight'][$key]) || !empty($post['remark'][$key]))) {
                    $lot_creation = isset($post['lot_creation_value'][$key]) && $post['lot_creation_value'][$key] == 'YES' ? "YES" : "NO";
                    $receivedData = [
                        'item_id'             => isset($post['item_id'][$key]) ? $post['item_id'][$key] : 0,
                        'pcs'                 => isset($post['pcs'][$key]) ? $post['pcs'][$key] : 0,
                        'weight'              => isset($post['weight'][$key]) ? $post['weight'][$key] : 0,
                        'labour_type'         => isset($post['labour_type'][$key]) ? $post['labour_type'][$key] : null,
                        'labour'              => isset($post['labour'][$key]) ? $post['labour'][$key] : 0,
                        'total_labour'        => isset($post['totalLabour'][$key]) ? $post['totalLabour'][$key] : 0,
                        'final_labour'        => isset($post['finalLabour'][$key]) ? $post['finalLabour'][$key] : 0,
                        'row_material_weight' => isset($post['rm_weight'][$key]) ? $post['rm_weight'][$key] : 0,
                        'total_weight'        => isset($post['total_weight'][$key]) ? $post['total_weight'][$key] : 0,
                        'touch'               => isset($post['touch'][$key]) ? $post['touch'][$key] : 0,
                        'fine'                => isset($post['fine'][$key]) ? $post['fine'][$key] : 0,
                        'remark'              => isset($post['remark'][$key]) ? $post['remark'][$key] : null,
                        'lot_creation'        => $lot_creation,
                    ];

                    if ($rcid == 0) {
                        $receivedData['given_id']      = $given_id;
                        $receivedData['garnu_id']      = $garnu_id;
                        $receivedData['user_id']       = $user_id;
                        $receivedData['creation_date'] = date('Y-m-d');
                        $this->db->insert('receive', $receivedData);
                        $receive_id = $this->db->insert_id();
                        $code       = date('M') . "_R$receive_id" . "_G$given_id";
                        $this->db->where('id', $receive_id)->update('receive', [ 'code' => $code ]);
                    } else if (in_array($rcid, $existingIds)) {
                        $receive_id = $rcid;
                        $this->dbh->updateRow('receive', $rcid, $receivedData);
                    }

                    $rawMaterialData             = $post['raw-material-data'][$key];
                    $updateArray['rcdid']        = [];
                    $updateData                  = [];
                    $updateArray['rm']['insert'] = [];
                    $updateArray['rm']['update'] = [];
                    $updateArray['rm']['delete'] = [];

                    if (isset($rawMaterialData) && !empty($rawMaterialData) && $rawMaterialData !== NULL) {
                        $rm_data  = explode('|', $rawMaterialData);
                        $rmDelete = $this->db->select('id')->where('received_id', $receive_id)->get('receive_row_material')->result();
                        foreach ($rm_data as $rcD) {
                            $rm                     = explode(',', $rcD);
                            $updateArray['rcdid'][] = $rm[8];
                            if (!empty($rm[0]) || !empty($rm[2]) || !empty($rm[3])) {
                                $lot_wise_rm_id = $rm[1];
                                if ($rm[8] == 0) {
                                    if (!empty($rm[1]) && is_numeric($rm[1])) {
                                        $this->db->where('id', $rm[1])
                                            ->set('receive_weight', 'receive_weight + ' . $rm[3], false)
                                            ->set('receive_quantity', 'receive_quantity + ' . $rm[4], false);
                                        $this->db->update('lot_wise_rm');
                                        $lot_wise_rm_id = $rm[1];
                                    } else if (!empty($rm[1]) && !is_numeric($rm[1])) {
                                        $this->db->insert('lot_wise_rm', array( 'user_id' => $user_id, 'code' => $rm[1], 'type' => 'RECEIVE', 'touch' => $rm[2], 'row_material_id' => $rm[0], 'weight' => $rm[3], 'quantity' => $rm[4], 'receive_weight' => $rm[3], 'receive_quantity' => $rm[4], 'rem_weight' => $rm[3], 'rem_quantity' => $rm[4], 'creation_date' => date('Y-m-d') ));
                                        $lot_wise_rm_id = $this->db->insert_id();
                                    }
                                } else {
                                    $rmData       = $this->db->get_where('receive_row_material', array( 'id' => $rm[8] ))->row_array();
                                    $lwrmweight   = $rm['3'] - $rmData['weight'];
                                    $lwrmquantity = $rm['4'] - $rmData['quantity'];
                                    if ($rmData['weight'] != $rm['3']) {
                                        $this->db->where('id', $rm[1])
                                            ->set('receive_weight', 'receive_weight + ' . $lwrmweight, false);
                                        $this->db->update('lot_wise_rm');
                                    }
                                    if ($rmData['quantity'] != $rm['4']) {
                                        $this->db->where('id', $rm[1])
                                            ->set('receive_quantity', 'receive_quantity + ' . $lwrmquantity, false);
                                        $this->db->update('lot_wise_rm');
                                    }
                                }
                                $updateData = [
                                    'received_id'     => $receive_id,
                                    'garnu_id'        => $garnu_id,
                                    'row_material_id' => $rm[0],
                                    'lot_wise_rm_id'  => $lot_wise_rm_id ?? 0,
                                    'touch'           => $rm[2] ?? 0,
                                    'weight'          => $rm[3] ?? 0,
                                    'quantity'        => $rm[4] ?? 0,
                                    'labour_type'     => $rm[5] ?? null,
                                    'labour'          => $rm[6] ?? 0,
                                    'total_labour'    => $rm[7] ?? 0,
                                ];
                            }
                            if ($rm[8] > 0) {
                                $updateData['id']              = $rm[8];
                                $updateArray['rm']['update'][] = $updateData;
                            } else {
                                $updateData['user_id']         = session('id');
                                $updateData['creation_date']   = date('Y-m-d');
                                $updateArray['rm']['insert'][] = $updateData;
                            }
                        }

                        if (!empty($updateArray['rm']['insert'])) {
                            $this->db->insert_batch('receive_row_material', $updateArray['rm']['insert']);
                            $response = [ 'success' => true, 'message' => 'Data Add Successfully.' ];
                        } else {
                            $response = [ 'success' => false, 'message' => 'Data Add Failed.' ];
                        }
                        if (!empty($updateArray['rm']['update'])) {
                            $this->db->update_batch('receive_row_material', $updateArray['rm']['update'], 'id');
                            $response = [ 'success' => true, 'message' => 'Data Update Successfully.' ];
                        }


                        if ($rmDelete) {
                            array_walk($rmDelete, function ($rmD) use (&$updateArray) {
                                if (!in_array($rmD->id, $updateArray['rcdid'])) {
                                    $updateArray['rm']['delete'][] = $rmD->id;
                                }
                            });
                            ($updateArray['rm']['delete'] && $this->db->where_in('id', $updateArray['rm']['delete'])->delete('receive_row_material'));
                        }
                    }
                }
            }

            $metalType = $post['metalType-data'];
            if (!empty($metalType) && $metalType !== NULL) {
                $rm_data                     = explode('|', $metalType);
                $rmDelete                    = $this->db->select('id')->where('given_id', $given_id)->get('process_metal_type')->result();
                $updateArray['mt']['insert'] = [];
                $updateArray['mt']['update'] = [];
                foreach ($rm_data as $rcD) {
                    $rm = explode(',', $rcD);

                    if (!empty($rm) && !empty($rm[0]) && !empty($rm[1]) || !empty($rm[2]) || !empty($rm[3])) {
                        $updateArray['pmtid'][] = $rm[4];
                        $updateData             = [
                            'given_id'      => $given_id,
                            'metal_type_id' => $rm[0],
                            'touch'         => $rm[1] ?? 0,
                            'weight'        => $rm[2] ?? 0,
                            'quantity'      => $rm[3] ?? 0,
                        ];
                        if ($rm[4] > 0) {
                            $updateData['id']              = $rm[4];
                            $updateArray['mt']['update'][] = $updateData;
                        } else {
                            $updateData['user_id']         = session('id');
                            $updateData['creation_date']   = date('Y-m-d');
                            $updateArray['mt']['insert'][] = $updateData;
                        }
                    }
                }

                if (!empty($updateArray['mt']['insert'])) {
                    $this->db->insert_batch('process_metal_type', $updateArray['mt']['insert']);
                    $response = [ 'success' => true, 'message' => 'Data Add Successfully.' ];
                } else {
                    $response = [ 'success' => false, 'message' => 'Data Add Failed.' ];
                }
                if (!empty($updateArray['mt']['update'])) {
                    $this->db->update_batch('process_metal_type', $updateArray['mt']['update'], 'id');
                    $response = [ 'success' => true, 'message' => 'Data Update Successfully.' ];
                }


                if ($rmDelete) {
                    array_walk($rmDelete, function ($rmD) use (&$updateArray) {
                        if (!in_array($rmD->id, $updateArray['pmtid'])) {
                            $updateArray['mt']['delete'][] = $rmD->id;
                        }
                    });
                    (isset($updateArray['mt']['delete']) && $this->db->where_in('id', $updateArray['mt']['delete'])->delete('process_metal_type'));
                }
            } else {
                $response = [ 'success' => true, 'message' => 'Data Add Successfully.' ];
            }
        } else {
            $response = [ 'success' => false, 'message' => 'Please Fill Complate form.' ];
        }

        echo json_encode($response);
        return;
    }

    public function givenRowMaterial()
    {
        try {
            $this->form_validation->set_rules('garnu_id', 'Garnu Id', 'trim|required|numeric');
            $this->form_validation->set_rules('given_id', 'Given Id', 'trim|required|numeric');
            if ($this->form_validation->run() == FALSE) {
                $response = [ 'success' => false, 'error' => validation_errors() ];
                echo json_encode($response);
                return;
            } else {
                $postData  = $this->input->post();
                $garnu_id  = $postData['garnu_id'];
                $given_id  = $postData['given_id'];
                $data      = $this->dbh->getWhereResultArray('given_row_material', [ 'garnu_id' => $garnu_id, 'given_id' => $given_id ]);
                $garnuData = $this->db->select('name,garnu_weight')->from('garnu')->where('id', $garnu_id)->get()->row_array();
                if (!empty($data) || !empty($garnuData)) {
                    $response = [ 'success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data, 'garnuData' => $garnuData ];
                } else {
                    $response = [ 'success' => false, 'message' => 'Data Not Found.', 'data' => [] ];
                }
                echo json_encode($response);
                return;
            }
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'error'   => $e->getMessage(),
                'data'    => []
            ];
            echo json_encode($response);
        }
    }

    public function receiveRowMaterial()
    {
        try {
            $this->form_validation->set_rules('garnu_id', 'Garnu Id', 'trim|required|numeric');
            $this->form_validation->set_rules('given_id', 'Given Id', 'trim|required|numeric');
            if ($this->form_validation->run() == FALSE) {
                $response = [ 'success' => false, 'error' => validation_errors() ];
                echo json_encode($response);
                return;
            } else {
                $postData     = $this->input->post();
                $garnu_id     = $postData['garnu_id'];
                $given_id     = $postData['given_id'];
                $receive      = $this->dbh->getWhereResultArray('receive', [ 'garnu_id' => $garnu_id, 'given_id' => $given_id ]);
                $receivedData = array_map(function ($row) {
                    return $this->dbh->getWhereResultArray('receive_row_material', [ 'received_id' => $row['id'] ]);
                }, $receive);

                $data = array_reduce($receivedData, function ($carry, $item) {
                    return array_merge($carry, $item);
                }, []);

                $garnuData = $this->db->select('name,garnu_weight')->from('garnu')->where('id', $garnu_id)->get()->row_array();
                if (!empty($data) || !empty($garnuData)) {
                    $response = [ 'success' => true, 'message' => 'Data Fetched successfully.', 'data' => $data, 'garnuData' => $garnuData ];
                } else {
                    $response = [ 'success' => false, 'message' => 'Data Not Found.', 'data' => [] ];
                }
                echo json_encode($response);
                return;
            }
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'error'   => $e->getMessage(),
                'data'    => []
            ];
            echo json_encode($response);
        }
    }

    public function given_print($garnu_id = null, $given_id = null)
    {
        $garnu_id                = $this->security->xss_clean($garnu_id);
        $given_id                = $this->security->xss_clean($given_id);
        $page_data['page_title'] = 'Print Given Filing Data';
        $page_data['data']       = $this->modal->printGivenItemData($garnu_id, $given_id);
        $page_data['garnu_id']   = $garnu_id;
        $page_data['given_id']   = $given_id;

        if (!empty($page_data['data']['givenData']) || !empty($page_data['data']['givenRowMaterial'])) {
            $this->load->view("admin/manufacturing/print/print_given_data.php", $page_data);
        } else {
            flash()->withError("Data Not Found.")->to("manufacturing/process/manage/" . $garnu_id);
        }
    }

    public function getRowMaterials()
    {
        $this->db->query(' SET SESSION sql_mode = "" ');
        $postData = $this->security->xss_clean($this->input->post());

        $draw            = $postData['draw'];
        $rowperpage      = (int) $postData['length']; // Rows display per page, cast to int for safety
        $start           = (int) $postData['start'];
        $columnIndex     = $postData['order'][0]['column']; // Column index
        $columnName      = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue     = '%' . $this->db->escape_like_str($postData['search']['value']) . '%';
        $garnu_id        = $postData['garnu_id'];
        $searchQuery     = "";
        $searchQuery2    = "";
        if ($searchValue != '') {
            $searchQuery  = " (row_material.name like '%" . $searchValue . "%') or (given_row_material.touch like '%" . $searchValue . "%') or (given_row_material.weight like '%" . $searchValue . "%') or (given_row_material.quantity like '%" . $searchValue . "%')";
            $searchQuery2 = " (row_material.name like '%" . $searchValue . "%') or (receive_row_material.touch like '%" . $searchValue . "%') or (receive_row_material.weight like '%" . $searchValue . "%') or (receive_row_material.quantity like '%" . $searchValue . "%')";
        }
        $totalRecords          = $this->getTotalRecordsWithFilter($searchQuery, $searchQuery2, $garnu_id);
        $totalRecordwithFilter = $this->getTotalRecordsWithFilter($searchQuery, $searchQuery2, $garnu_id);

        $sql      = "(SELECT 'Given' AS type, given_row_material.id, row_material.name as row_material, 
				given_row_material.touch, given_row_material.weight, given_row_material.quantity, 
				given_row_material.garnu_id,process.name as Process_name
				FROM given_row_material
				JOIN row_material ON given_row_material.row_material_id = row_material.id
				JOIN given ON given_row_material.given_id = given.id
				JOIN process ON given.process_id = process.id
				WHERE given_row_material.garnu_id = ? AND 
					(row_material.name LIKE ? OR 
						given_row_material.touch LIKE ? OR 
						given_row_material.weight LIKE ? OR 
						given_row_material.quantity LIKE ?))
				UNION ALL
				(SELECT 'Received' AS type, receive_row_material.id, row_material.name as row_material, 
						receive_row_material.touch, receive_row_material.weight, 
						receive_row_material.quantity, receive_row_material.garnu_id,process.name as Process_name
				FROM receive_row_material
				JOIN row_material ON receive_row_material.row_material_id = row_material.id
				JOIN receive ON receive_row_material.received_id = receive.id
				JOIN given ON receive.given_id = given.id
				JOIN process ON given.process_id = process.id
				WHERE receive_row_material.garnu_id = ? AND 
					(row_material.name LIKE ? OR 
						receive_row_material.touch LIKE ? OR 
						receive_row_material.weight LIKE ? OR 
						receive_row_material.quantity LIKE ?))
				ORDER BY id ASC
				LIMIT ? OFFSET ?";
        $bindings = array(
            $garnu_id,
            $searchValue,
            $searchValue,
            $searchValue,
            $searchValue,
            $garnu_id,
            $searchValue,
            $searchValue,
            $searchValue,
            $searchValue,
            $rowperpage,
            $start
        );
        $query    = $this->db->query($sql, $bindings);
        $records  = $query->result_array();

        $data = array();
        $i    = $start + 1;
        foreach ($records as $record) {

            $data[] = array(
                "id"           => $i,
                "row_material" => $record['row_material'],
                "process_name" => $record['Process_name'],
                "type"         => $record['type'],
                "touch"        => $record['touch'],
                "weight"       => $record['weight'],
                "quantity"     => $record['quantity'],
            );
            $i      = $i + 1;
        }

        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData"               => $data
        );
        echo json_encode($response);
        exit();
    }

    function getTotalRecordsWithFilter($searchQuery, $searchQuery2, $garnu_id)
    {
        $searchValue = '%' . $this->db->escape_like_str($this->input->post('search')['value']) . '%';
        $sql         = "SELECT SUM(total) AS total_records FROM (
					(SELECT COUNT(*) AS total
					 FROM given_row_material
					 JOIN row_material ON given_row_material.row_material_id = row_material.id
					 WHERE given_row_material.garnu_id = ? AND ($searchQuery))
					UNION ALL
					(SELECT COUNT(*) AS total
					 FROM receive_row_material
					 JOIN row_material ON receive_row_material.row_material_id = row_material.id
					 WHERE receive_row_material.garnu_id = ? AND ($searchQuery2))
				) AS counts";

        $query  = $this->db->query($sql, array( $garnu_id, $garnu_id ));
        $result = $query->row_array();
        return isset($result['total_records']) ? (int) $result['total_records'] : 0;
    }
}