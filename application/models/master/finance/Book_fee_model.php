<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/*
*现金收费管理
*
*/
class Book_fee_Model extends CI_Model{
	const T_SQUAD ='squad';
	const T_MAJOR = 'major';
	const CASH = 'books_fee';
	const T_STUDENT = 'student';
	const T_STUDENT_BOOKS = 'books';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	//查询数据总数
	function count($where = null){
    	$this->db->select('books_fee.*,zust_student.*');
		if($where !== null){
			$this->db->where($where);
    	}
    	$this->db->join('zust_student','zust_student.userid=zust_books_fee.userid');
    	return $this->db->count_all_results(self::CASH);
	}


//获得数据
    function get($where = null,$limit = 25,$offset = 0,$orderby = null,$major_id,$term,$squad_id){
    	$this->db->select('zust_books_fee.*,zust_books_fee.id as bookid,zust_student.*');
    	if($where != null){
			$this->db->where($where);
    	}
    	if($limit){
			$this->db->limit($limit,$offset);
    	}

    	if($orderby !== null){
    		$this->db->order_by($orderby);
    	}
    	if($major_id!=0){
    		$this->db->where('zust_student.majorid',$major_id);
    	}
    	if($term!=0){
    		$this->db->where('zust_books_fee.term',$term);
    	}
    	if($squad_id!=0){
    		$this->db->where('zust_student.squadid',$squad_id);
    	}
    	$this->db->join('zust_student','zust_student.userid=zust_books_fee.userid');
    	return $this->db->get(self::CASH)->result();
    }
	/**
	 * [get_student_remark 获取学生备注]
	 * @return [type] [description]
	 */
	function get_student_remark($id){
		if(!empty($id)){
			$this->db->select('remark');
			$this->db->where('id',$id);
			$data=$this->db->get(self::CASH)->row_array();
			return $data['remark'];
		}
		return '';
	}
	/**
	 * [get_student_book_info 获取该学生的信息]
	 * @return [type] [description]
	 */
	function get_student_book_info($sid){
		$str=explode(',', $sid)	;
		if(!empty($sid)){
			$this->db->where_in('id',$str);
			return $this->db->get(self::T_STUDENT_BOOKS)->result_array();
		}
		
	}

}