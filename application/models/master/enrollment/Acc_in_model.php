<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Acc_in_Model extends CI_Model {
	const T_ACC = 'accommodation_info';
	const T_STUDENT = 'student';
	const T_MAJOR = 'major';
	const T_SQUAD ='squad';
	const T_STUDENT_INFO='student_info';
	const T_INSUR='insurance_info';
	const T_ARTICLE='tuition_info';
	const T_C = 'credentials';
	const T_MESSAGE_LOG='message_log';
	const T_P_M_C='push_mail_config';
	const T_M_R='mail_record';

	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_USER_ROOM='user_room';
	const T_ACC_RECORD='accommodation_history';//住宿历表
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
	function count($where=null) {
		$this->db->select('accommodation_info.*,student_info.chname,student_info.nationality,student_info.sex,student_info.enname,student_info.passport,student_info.email');
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
    	$this->db->join('student_info','student_info.id=accommodation_info.userid');
		return $this->db->from ( self::T_ACC )->count_all_results ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		$this->db->select('accommodation_info.*,student_info.chname,student_info.nationality,student_info.sex,student_info.enname,student_info.passport,student_info.email');
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
    	$this->db->join('student_info','student_info.id=accommodation_info.userid');
		$data= $this->db->order_by ( $orderby )->get ( self::T_ACC )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
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
	function get_email_user_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_email_user_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条email
	function get_email_user_one($id){
		if(!empty($id)){
			$this->db->select('email');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_INFO)->row_array();
			return $data['email'];
		}
		return 0;
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
	//获取专业id
	function get_major_ids($str){
		if(!empty($str)){
			$this->db->like('name',$str);
			$data=$this->db->get(self::T_MAJOR)->result_array();
		}
		$ids='';
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$ids.=$v['id'].',';
			}
			$ids=trim($ids,',');
			return $ids;
		}else{
			return '';
		}
	}
	//获取国家的名字
	function get_nationality($id){
		$data=CF('public','',CACHE_PATH);
		if(!empty($id)){
			$info=$this->db->get_where('student_info','id = '.$id)->row_array();
			return $data['global_country_cn'][$info['nationality']];
		}
		return false;
	}
	/**
	 * 获得专业名字       	
	 */
	function get_majorname($id){
		$data= $this->db->where('id',$id)->get ( self::T_MAJOR)->row_array ();
		return $data['name'];
	}
	/**
	 * 获得班级名字       	
	 */
	function get_squadname($id){
		if($id==0){
			return '还没有分班';
		}else{
			$data= $this->db->where('id',$id)->get ( self::T_SQUAD)->row_array ();
		return $data['name'];
		}
		
	}
	/**
	 * [get_user_one 获取用户表one]
	 * @return [type] [description]
	 */
	function get_user_one($where=null){
		if($where!=null){
			return $this->db->get( self::T_STUDENT_INFO , $where )->row();
		}
		return array();
	}
	/**
	 * 获取一条申请信息
	 *
	 * @param number $id        	
	 */
	function get_acc_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ACC )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
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
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if(!empty($data)){
				return $data;
			}
		}
		return array();
	}
		/**
	 * [get_campus_name 获取已经入住的学生]
	 * @return [type] [description]
	 */
	function get_in_user($roomid){
		if(!empty($roomid)){
			$this->db->where('roomid',$roomid);
			$this->db->where('is_in',1);
			$this->db->join('student_info','user_room.userid=student_info.id');
			$data=$this->db->get(self::T_USER_ROOM)->result_array();
			if(!empty($data)){
				return $data;
			}
		}
		return array();
	}
	/**
	 * [insert_user_room 插入确认学生房间表]
	 * @param  [type] $userid [description]
	 * @param  [type] $roomid [description]
	 * @return [type]         [description]
	 */
	function insert_user_room($userid,$roomid,$campusid,$buildingid,$floor){
		if(!empty($userid)&&!empty($roomid)){
			$id=$this->db->select('id')->get(self::T_USER_ROOM, 'userid = '.$userid .' AND roomid='.$roomid )->row_array();
			if(!empty($id)){
				$data['in_time']=time();
				$data['is_in']=1;
				$this->db->update ( self::T_USER_ROOM, $data , 'id ='.$id['id']);
				$data['userid']=$userid;
				$data['roomid']=$roomid;
				$data['adminid']=$_SESSION['master_user_info']->id;
				$data['createtime']=time();
				$data['campusid']=$campusid;
				$data['buildingid']=$buildingid;
				$data['floor']=$floor;
				unset($data['is_in']);
				$data['state']=1;
				$this->db->insert(self::T_ACC_RECORD,$data);
				return $id;
			}else{
				$data['in_time']=time();
				$data['userid']=$userid;
				$data['roomid']=$roomid;
				$data['adminid']=$_SESSION['master_user_info']->id;
				$data['createtime']=time();
				$data['is_in']=1;
				$data['campusid']=$campusid;
				$data['buildingid']=$buildingid;
				$data['floor']=$floor;
				$this->db->insert ( self::T_USER_ROOM, $data );
				unset($data['is_in']);
				$data['state']=1;
				$this->db->insert(self::T_ACC_RECORD,$data);
				return $this->db->insert_id ();
			}
		}
		return 0;
	}
	/**
	 * [get_campus_name 获取用户的邮箱]
	 * @return [type] [description]
	 */
	function get_user_email($id){
		if(!empty($id)){
			$this->db->select('email');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_INFO)->row_array();
			if(!empty($data['email'])){
				return $data['email'];
			}
		}
		return '';
	}
	/**
	 * [update_room_shate 更新房间的状态入住多少人和已满的状态]
	 * @return [type] [description]
	 */
	function update_room_shate($roomid){
		if(!empty($roomid)){
			//获取该房间的容纳多少人
			$maxuser_num=$this->get_room_maxuser($roomid);
			$in_user_num=$this->get_in_user_num($roomid);
			if(!empty($in_user_num)){
				//更新房间入住的人数
				$arr['in_user_num']=$in_user_num;
				$this->db->update(self::T_PRICES,$arr,'id='.$roomid);
			}
			// if($in_user_num>=$maxuser_num){
			// 	$arr['is_reserve']=2;
			// 	$this->db->update(self::T_PRICES,$arr,'id='.$roomid);
			// }
		}
	}
	//获取房间容纳多少人
	function get_room_maxuser($roomid){
		if(!empty($roomid)){
			$this->db->select('maxuser');
			$this->db->where('id',$roomid);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if(!empty($data['maxuser'])){
				return $data['maxuser'];
			}
		}
		return 0;
	}
	//获取现实入住的人数
	function get_in_user_num($roomid){
		if(!empty($roomid)){
			$this->db->select('count(*) as num');
			$this->db->where('roomid',$roomid);
			$this->db->where('is_in',0);
			$data=$this->db->get(self::T_USER_ROOM)->row_array();
			if(!empty($data['num'])){
				return $data['num'];
			}
		}
		return 0;
	}

	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ACC )->row ();
			if ($base) {
				return $base;
			}
			return array ();
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
	 * [update_acc_is_in 更新订单状态已经入住]
	 * @return [type] [description]
	 */
	function update_acc_is_in($acc_id){
		if(!empty($acc_id)){
			$arr['acc_state']=6;
			$this->db->update(self::T_ACC,$arr,'id = '.$acc_id);
		}
	}
	
}