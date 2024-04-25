<?php

class Sequence_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	public function getNextSequence($model=''){
		$model = $this->security->xss_clean($model);
		$seq = '';
		if(!empty($model)){
			$data = $this->db->query("SELECT * FROM sequence 
										WHERE model = ".$this->db->escape($model)."
										ORDER BY id DESC 
										LIMIT 1")->row_array();
			$seq = $data['sequence'];
			$data['increment'] = $data['increment'] + 1;
			$padding = '';
			$number_length = strlen($data['increment'].'');
			if($number_length >= 1 && $data['padding'] >= $number_length){
				for ($i=0; $i < $data['padding'] - $number_length; $i++){
					$padding .= '0';
				}
			}
			//echo 'number_length: '.$number_length. ' padding: '.$padding. ' increment: '.$data['increment'];die;
			$data['sequence']  = $data['prefix'].$padding.$data['increment'].$data['suffix'];

			$this->db->where('id',$data['id']);
			$this->db->update('sequence',array('sequence'=>$data['sequence'],'increment'=>$data['increment']));
		}
		return $seq;
	}

	public function sequence($id = 0){
		if(!empty($id) && $id != 0){
			$this->db->where('id',$id);
			return $this->db->get('sequence')->row_array();
		}else{
			$this->db->order_by('id','desc');
			return $this->db->get('sequence')->result_array();
		}
	}

}

?>
