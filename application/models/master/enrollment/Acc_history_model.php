<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zyj
 *        
 */
class Acc_history_Model extends CI_Model {
	const T_ARTICLE = 'accommodation_history';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_STUDENT_INTO='student_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
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
			return $this->db->from ( self::T_ARTICLE )->count_all_results ();
		}
		return 0;
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
			return $this->db->get ( self::T_ARTICLE )->result ();
		}
		return array ();
	}
	
	/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_campus_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_CAMPUS)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
		/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_buliding_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_BULIDING)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_room_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_user_name($id){
		if(!empty($id)){
			$this->db->select('enname');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_INTO)->row_array();
			if(!empty($data['enname'])){
				return $data['enname'];
			}
		}
		return '';
	}
}