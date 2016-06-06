<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Visa_Model extends CI_Model {
	const T_STUDENT = 'student';
	const T_MAJOR = 'major';
	const T_SQUAD ='squad';
	const T_COURSE='course';
	const T_FACULTY='faculty';
	const T_STUDENT_VISA='student_visa';
	const T_STUDENT_INFO='student_info';
	const T_DEGREE_INFO='degree_info';
	const T_INSUR='insurance_info';
	const T_ARTICLE='tuition_info';
	const T_C = 'credentials';
	const T_MESSAGE_LOG='message_log';
	const T_P_M_C='push_mail_config';
	const T_M_R='mail_record';
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
			
			$this->db->select ('student_visa.id as id, student_visa.visatime as visatime,student_visa.manage_state as manage_state,student.id as sid,student.name as name ,student.enname as enname,student.passport as passport');
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$this->db->join(self::T_STUDENT,self::T_STUDENT_VISA . '.studentid=' . self::T_STUDENT . '.id');
			$this->db->where('student.state',1);
			return $this->db->get ( self::T_STUDENT_VISA )->result ();
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
			$this->db->join(self::T_STUDENT,self::T_STUDENT_VISA . '.studentid=' . self::T_STUDENT . '.id');
			$this->db->where('student.state',1);
			return $this->db->from ( self::T_STUDENT_VISA )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [get_visastate 获取签证状态]
	 * @return [type] [description]
	 */
	function get_visastate($id){
		if(!empty($id)){
			$this->db->select('visatime');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_VISA)->row_array();
			if(!empty($data)){
				$time=strtotime($data['visatime']);
				$time_guoqi=strtotime("+1 month");
				if($time>$time_guoqi){
					return '正常';
				}elseif($time>time()&&$time<$time_guoqi){
					return '<span style="color:orange">即将过期</span>';
				}elseif($time<time()){
					return '<span style="color:red">已过期</span>';
				}
			}
		}
		return '';
	}
	function get_student_on_info($id){
		// var_dump($id);exit;
		if(!empty($id)){
			$this->db->select('student.*,student.id as s_id,student_visa.*');
			$this->db->join(self::T_STUDENT_VISA,self::T_STUDENT_VISA . '.studentid=' . self::T_STUDENT . '.id');
			$this->db->where('student.id',$id);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
		return array();
	}
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_part_class($where=null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_STUDENT )->count_all_results ();
	
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_part_class($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		$data= $this->db->order_by ( $orderby )->get ( self::T_STUDENT )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_STUDENT, $where);
			return true;
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
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_STUDENT)->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 *
	 *获取student_visa一条数据
	 **/
	function get_visa_one($id){
		if(!empty($id)){
			$base = $this->db->where ('studentid',$id)->limit(1)->get(self::T_STUDENT_VISA)->row ();
			if(!empty($base)){
				return $base;
			}
		}
		return false;
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
				$this->db->insert ( self::T_STUDENT, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_STUDENT, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 *更新字段
	 **/
	function update_fields($data=array()){
		if(!empty($data)){
			$arr[$data['name']]=$data['value'];
			if($data['name']=='degreeid'){
				$is=$this->get_student_type($data['value']);
				if($is=='1'){
					$arrs['studenttype']='学历生';
					$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
				}else{
					$arrs['studenttype']='非学历生';
					$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
				}
			}elseif($data['name']=='firstname'){
				$lastname=$this->get_student_lastname($data['pk']);
				$enname=$data['value'].' '.$lastname;
				$arrs['enname']=$enname;
				$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
			}elseif($data['name']=='lastname'){
				$firstname=$this->get_student_firstname($data['pk']);
				$enname=$firstname.' '.$data['value'];
				$arrs['enname']=$enname;
				$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
			}
			$this->db->update ( self::T_STUDENT, $arr, 'id = ' . $data['pk'] );
		}
	}
	/**
	 *更新student_visa字段
	 **/
	function update_visa_fields($data,$name){
		//查询id是否存在
		$count=$this->check_student_visa($data['pk']);
		if($count>0){
			if(!empty($data)&&!empty($name)){
				if($name=='visatime'){
					$date=strtotime($data['value']);
					$visaendtime['visaendtime']=$date;
					$this->db->update ( self::T_STUDENT, $visaendtime, 'id = ' . $data['pk'] );
				}
				$arr[$name]=$data['value'];
				$this->db->update(self::T_STUDENT_VISA,$arr,'studentid='.$data['pk']);
			}
		}else{
			//创建student_visa
			$arr[$name]=$data['value'];
			$arr['studentid']=$data['pk'];
			$this->db->insert ( self::T_STUDENT_VISA, $arr );
		}
		
	}
	//检查是否存在
	function check_student_visa($studentid){
		if(!empty($studentid)){
			$this->db->select('count("*") as count');
			$this->db->where('studentid',$studentid);
			$data=$this->db->get(self::T_STUDENT_VISA)->row_array();
			return $data['count']; 
		}
		return false;
	}
	/**
	 * [get_squad_name 获取班级名字]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_squad_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_SQUAD)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_message( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_MESSAGE_LOG, $data );
				return $this->db->insert_id ();
		
		}
	}
	//获取userid数组
	function get_userid_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_userid_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条userid
	function get_userid_one($id){
		if(!empty($id)){
			$this->db->select('userid');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['userid'];
		}
		return 0;
	}
	//获取发邮件配置
	function get_addresserset(){
		return $this->db->get(self::T_P_M_C)->result_array();
	}
	/**
	 * 保存邮件发送记录
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_email( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_M_R, $data );
				return $this->db->insert_id ();
		
		}
	}
	function get_email_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_email_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条email
	function get_email_one($id){
		if(!empty($id)){
			$this->db->select('email');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['email'];
		}
		return 0;
	}
}