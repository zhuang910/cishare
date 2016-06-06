<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Budget_Model extends CI_Model {
	const BUDGET = 'budget';
	const T_STU_INFO='student_info';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	//查询数据总数
	function count($where = null){
    	$this->db->select('zust_budget.*,zust_student_info.chname,zust_student_info.passport');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('zust_student_info','zust_student_info.id=zust_budget.userid');
    	return $this->db->count_all_results(self::BUDGET);
	}


//获得数据
    function get($where = null,$limit = 25,$offset = 0,$orderby = null){
    	$this->db->select('zust_budget.*,zust_student_info.chname,zust_student_info.passport');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    	$this->db->join('zust_student_info','zust_student_info.id=zust_budget.userid');
    	return $this->db->get(self::BUDGET)->result();
    }
	

//获取学期
    function get_term(){
    	$this->db->select('MAX(zust_budget.term)');

    	return $this->db->get(self::BUDGET)->row_array();
    }

}