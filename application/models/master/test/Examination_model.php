<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Examination_Model extends CI_Model {
	const T_TEST_PAPER='test_paper';
	const T_E_INFO='examination_info';
	const T_STUDENT='student';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition) {
		if (is_array ( $field ) && ! empty ( $field )) {
			
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			
			return $this->db->get ( self::T_TEST_PAPER )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			
			return $this->db->from ( self::T_TEST_PAPER )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [get_paper_num 获取考试的人数]
	 * @return [type] [description]
	 */
	function get_paper_num($paperid){
		if(!empty($paperid)){
			$this->db->select('count(*) as num');
			$this->db->where('paperid',$paperid);
			$data=$this->db->get(self::T_E_INFO)->row_array();
			if(!empty($data)){
				return $data['num'];
			}
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_ex($field, $condition,$paperid) {
		if (is_array ( $field ) && ! empty ( $field )) {
			
			$this->db->select ('zust_examination_info.id,zust_student.name,zust_student.id as sid,zust_student.userid,zust_student.passport,zust_examination_info.paperid,zust_examination_info.score,zust_examination_info.finish_state,zust_examination_info.used_time,zust_examination_info.time');
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$this->db->join ( self::T_STUDENT, self::T_STUDENT . '.userid=' . self::T_E_INFO . '.userid' );
			$this->db->where('examination_info.paperid',$paperid);
			return $this->db->get ( self::T_E_INFO )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_ex($condition,$paperid) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->join ( self::T_STUDENT, self::T_STUDENT . '.userid=' . self::T_E_INFO . '.userid' );
			$this->db->where('examination_info.paperid',$paperid);
			return $this->db->from ( self::T_E_INFO )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [get_paper_name 获取试卷名字]
	 * @param  [type] $paperid [description]
	 * @return [type]          [description]
	 */
	function get_paper_name($paperid){
		if(!empty($paperid)){
			$this->db->select('name');
			$this->db->where('id',$paperid);
			$data=$this->db->get(self::T_TEST_PAPER)->row_array();
			return $data['name'];
		}
		return '';
	}
}