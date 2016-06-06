<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * Created by CUCAS TEAM.
 * User: junjie Zhang
 * E-Mail: zhangjunjie@cucas.cn
 * Date: 15-6-29
 * Time: 上午8:42
 */

class Test extends CUCAS_Ext {
    function __construct()
    {
        parent::__construct();
    }
    
    function del_budget(){
        $this->db->delete('budget','id = 5558');
         $this->db->delete('budget','id = 348');
          $this->db->delete('budget','id = 2400');
           $this->db->delete('budget','id = 5462');
			    $this->db->delete('tuition_info','id = 2833');
			     $this->db->delete('tuition_info','id = 343');
			    $this->db->delete('insurance_info','id = 327');
			    $this->db->delete('insurance_info','id = 2654');
    }
    
    function visa_v(){
      $data = $this->db->get_where('student_visa','id > 0')->result_array();
	  foreach($data as $k => $v){
		 if(!empty($v['visatime'])){
			 $visatime = strtotime($v['visatime']);
			 $this->db->update('student_visa',array('visatime' => $visatime),'id = '.$v['id']);
		 }
		 
		  if(!empty($v['oldvisatime'])){
			 $oldvisatime = strtotime($v['oldvisatime']);
			 $this->db->update('student_visa',array('oldvisatime' => $oldvisatime),'id = '.$v['id']);
		 }
		 
		  if(!empty($v['issuetime'])){
			 $issuetime = strtotime($v['issuetime']);
			 $this->db->update('student_visa',array('issuetime' => $issuetime),'id = '.$v['id']);
		 }
	  }
    }
}