<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理
 * @author 		Laravel
 * @copyright  
 **/
class Change_scholarship_status_Model extends CI_Model {
	const T_APP	= 'applyscholarship_info';		//后端申请管理主表
				
	const T_USER = 'student_info';				//前端用户表
	const T_BUDGET = 'budget';//收支表
	const T_TUITION_INFO='tuition_info';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	* 获取申请信息
	* $where=array()  搜索条件
	*/
	function get_app_array($where=array()){
		if(!empty($where)){
			$this->db->where($where);
		}
		$list = $this->db->get(self::T_APP )
						 ->result();
		return $list;
	}
	/*
	/**
	* 获取申请信息
	* $where=''  搜索条件
	*/
	function get_app($where = ''){
		if(!empty($where)){
			$this->db->where($where);
		}
		$list = $this->db->get(self::T_APP )
						 ->result();
		//echo $this->db->last_query();
		return $list;
	}

	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 申请表和用户表相互关联
	 * 获取一条数据
	 */
	
	function get_ones($id = null){
		if ($id !=null) {
			$this->db->select('*,applyscholarship_info.state as status,applyscholarship_info.id as appid');
			$this->db->from('applyscholarship_info');
			$this->db->join('scholarship_info','applyscholarship_info.scholarshipid=scholarship_info.id');
			return  $lists = $this->db->where('applyscholarship_info.id',$id)->get()->row();
		}
		return false;
	}
	
	
	/**
	 * 获得管理数据表中的一条记录
	 * 
	 */
	function  get_relation_one($id = null){
		if ($id !=null) {
			$this->db->select('*');
			$this->db->from('applyscholarship_info');
			$this->db->join('scholarship_info','applyscholarship_info.scholarshipid=scholarship_info.id');
			$this->db->join('student_info','applyscholarship_info.userid=student_info.id');
			return $lists = $this->db->where('applyscholarship_info.id',$id)->get()->row();
		}
		return false;
	}
	/* 更新前端申请表状态\提醒,日志记录表，*/
	function update_www_app_info($id = null, $data = array(), $action='') {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			$result = $this->db->update ( self::T_APP, $data, 'id = ' . $id );
			
			 if($result){
				$appresult = $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
				if(count($appresult)>0){
					$data_history= array(
									'userid'=>$appresult->userid,
									'appid'=>$appresult->id,
									'action'=>$action,
									'actiontime'=>time(),
									'actionip'=> get_client_ip()
					);
					return $this->db->insert ( self::T_APPLY_HISTORY, $data_history); //往前端插入操作日志
				}else{
					return false;	
				}
			}else{
				return false;	
			} 
		}
		return false;
	}
	
	/* 更新后端申请表状态\标记符*/
	function update_oa_app_info($id = null, $data = array(),$action='',$tips='') {
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			$data['lasttime']=time();  //将最后更新时间加入待更新数组
			$result = $this->db->update ( self::T_APP, $data, 'id = ' . $id );
			if($result){
				$appresult = $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
				
				if(count($appresult)>0){
					$data_history= array(
									'appid'=>$appresult->id,
									'events'=>$action,
									'lasttime'=>time(),
									'operater'=> 1,
									'remark'=> $tips
					);
					return $this->db->insert ( self::T_LOG, $data_history); //往前端插入操作日志
				}else{
					return false;
				}
			}else{
				return false;	
			}
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
	 * 获取字段
	 */
	function field_info() {
		return $this->db->list_fields ( self::T_APP );
	}
	/**
	 * 插入收支表
	 */
	 function insert_budget($data){
	 	if(!empty($data)){
	 		$this->db->insert(self::T_BUDGET,$data);
	 		return $this->db->insert_id();
	 	}
	 	return 0;
	 }
}