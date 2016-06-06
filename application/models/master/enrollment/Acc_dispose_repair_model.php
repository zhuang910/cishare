<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Acc_dispose_repair_Model extends CI_Model {
	const T_REP_INFO='repairs_info';//保修表
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_STUDENT_INFO='student_info';
	const T_ADMIN_INFO='admin_info';
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
			
			return $this->db->get ( self::T_REP_INFO )->result ();
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
			
			return $this->db->from ( self::T_REP_INFO )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete($where) {
		if ($where != null) {
			if ($this->db->delete ( self::T_REP_INFO, $where)) {
				return true;
			}
		}
		return false;
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_REP_INFO )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				if ($this->db->insert ( self::T_REP_INFO, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_REP_INFO, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [get_campus_info 获取校区
	 * @param  [type] $site_language [站点语言]
	 * @return [type]                [array]
	 */
	function get_campus_info(){
		
			$data=$this->db->get(self::T_CAMPUS)->result_array();
			return $data;
		return array();
	}
	/**
	 * [get_room_info 获取房间]
	 * @return [type] [description]
	 */
	function get_room_info($bid,$floor){
		if(!empty($bid)&&!empty($floor)){
			$this->db->where('bulidingid',$bid);
			$this->db->where('floor',$floor);
			$data=$this->db->get(self::T_PRICES)->result_array();
			return $data;
		}
	}
	/**
	 * [get_user_name 获取学生名称]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_user_name($id){
		if(!empty($id)){
			$this->db->select('enname');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_INFO)->row_array();
			if(!empty($data)){
				return $data['enname'];
			}
		}
		return '';
	}
	/**
	 * [get_user_name 获取学生名称]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_admin_name($id){
		if(!empty($id)){
			$this->db->select('nikename');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_ADMIN_INFO)->row_array();
			if(!empty($data)){
				return $data['nikename'];
			}
		}
		return '';
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
}