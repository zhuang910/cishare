<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理
 * @package 	Change_offer_status_model
 * @author 		Laravel
 * @copyright   
 **/
class Change_offer_status_model extends CI_Model {
	const T_APP	= 'apply_info';		//后端申请管理主表
	const T_APPLY_HISTORY	= 'apply_history';				//前端操作日志表
	const T_LOG				= 'app_log';				//后端操作日志表
	const T_USER			= 'student_info';				//前端用户表
	const T_MESSAGE			= 'message_info';			//前端消息表
	const T_OFFER           = 'app_getoffer';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 从OA端OFFER收发表中获取一条
	 *
	 * @param number $id        	
	 */
	function get_one_offer($app_id = null) {
		if ($app_id != null) {
			return $this->db->get_where ( self::T_OFFER, 'appid = ' . $app_id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 获取一条信息
	 * 
	 */
	
	function  get_one($id = null){
		if ($id !=null) {
			$this->db->select('*,apply_info.state as status,apply_info.id as appid');
			$this->db->from('apply_info');
			$this->db->join('major','apply_info.courseid=major.id');
			$this->db->join('student_info','apply_info.userid=student_info.id');
			return  $lists = $this->db->where('apply_info.id',$id)->get()->row();
		}
		return false;
	}
	
	/**
	 * 获取永久通信地址
	 */
	function  get_address($id = null){
		if ($id !=null) {
			
			$this->db->select('student_info.address');
			$this->db->from('apply_info');
			$this->db->join('student_info','apply_info.userid=student_info.id');
			return  $lists = $this->db->where('apply_info.id',$id)->get()->row();
		}
		return false;
	}
	/**
	 * 发送纸质offer后更新申请表状态
	 */
	function  update_app_sendoffer_status($id = null, $data = array()){
		if ($id !=null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_APP, $data, 'id = ' . $id );
		}
		return  false;
	}
	
	/**
	 * 更新一条数据
	 * 
	 */
	
	function  update_offer($data = array()){
		if (! empty ( $data ) && is_array ( $data )) {
			return $this->db->insert ( self::T_OFFER, $data ); 
		}
		return false;
	}
	
	/**
	 * 从OA端申请管理表中获取一条
	 *
	 * @param number $id        	
	 */
	function get_one_app($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * //执行更新前端申请表状态
	 * @param int $id 更新的申请id
	 */
	function update_www_app($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_APPLY, $data, 'id = ' . $id );
		}
		return false;
	}
	
	/**
	 * //执行更新前端地址确认表状态
	 * @param int $id 更新的申请id
	 */
	function update_www_offer($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_OFFER, $data, 'appid = ' . $id );
		}
		return false;
	}
	
	/**
	 * //执行OA端申请管理主表状态
	 * @param int $id 更新的申请id
	 */
	function update_oa_app($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_APP, $data, 'id = ' . $id );
		}
		return false;
	}
	
	/**
	 * 插入信息
	 * @param int $id 更新的申请id
	 */
	function send_message_info($title = '', $content  = '', $send_name = '') {
	
		if (! empty ( $title ) && ! empty ( $content ) && ! empty ( $send_name )) {
			$userinfo = $this->db->select('id')->get_where ( self::T_USER, 'email = "' . $send_name.'"', 1, 0 )->row ();
			$data=array(
					'title'=>$title,
					'content'=>$content,
					'sendtime'=>time(),
					'userid'=> $userinfo->id,
					'adminid'=> 1
			);
			return $this->db->insert ( self::T_MESSAGE, $data ); //发送消息
		}
		return false;
	}
	
	/**
	 * //执行OA端OFFER收发表状态
	 * @param int $id 更新的申请id
	 */
	function update_oa_offer($id = null, $data = array()) {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_OFFER, $data, 'appid = ' . $id );
		}
		return false;
	}
	
	/**
	 * 执行前端日志表记录插入
	 */
	function insert_www_log( $data = array() ) {
		return $this->db->insert ( self::T_APPLY_HISTORY, $data );
	}
	
	/**
	 * 执行后端日志表记录插入
	 */
	function insert_oa_log( $data = array() ) {
		return $this->db->insert ( self::T_LOG, $data );
	}
	
}