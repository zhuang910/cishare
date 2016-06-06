<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Acc_dispose_student_Model extends CI_Model {
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
	const T_ACC_RECORD='accommodation_record';//住宿历表
	const T_OUT_ROOM='out_room';//退房表
	const T_ACC_HISTORY='accommodation_history';//历史记录表
	const T_LAN_INFO='landlord_info';
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
	function count($where=null,$label_id) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		if($label_id==1){
			$this->db->join('accommodation_info','accommodation_info.userid=student.userid');
			$this->db->where('accommodation_info.acc_state=6');
		}
		return $this->db->from ( self::T_STUDENT )->count_all_results ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'student.id desc',$label_id) {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
		if($label_id==1){
			$this->db->join('accommodation_info','accommodation_info.userid=student.userid');
			$this->db->where('accommodation_info.acc_state=6');
		}
		$data= $this->db->get ( self::T_STUDENT )->result ();
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
			return $data['global_country_cn'][$id];
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
			$arr['is_in']=1;
			$this->db->update(self::T_ACC,$arr,'id = '.$acc_id);
		}
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_user_room_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_USER_ROOM )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * [get_surplus_acc_money 计算剩余的住宿费]
	 * @return [type] [description]
	 */
	function get_surplus_acc_money($acc_info){
		if(!empty($acc_info)){
			//获取该房间的每天的价格
			$day_prices=$this->get_room_prices($acc_info->roomid);

			$now_time=time();
			if($acc_info->accstarttime<$now_time){
				$shengyu=$now_time-$acc_info->accstarttime;
				$day_num=ceil($shengyu/3600/24);
				return ($acc_info->accendtime-$day_num)*$day_prices;
			}else{
				//退还全部的住宿费  一天没住
				return $acc_info->accendtime*$day_prices;
			}

		
		}
	}
	/**
	 * [get_room_prices 获取房间的每天的价格]
	 * @return [type] [description]
	 */
	function get_room_prices($id){
		if(!empty($id)){
			$base = $this->db->select('prices')->where ( 'id = '.$id )->limit ( 1 )->get ( self::T_PRICES )->row ();
				if(!empty($base->prices)){
					return $base->prices;
				}
			;
		}
		return 0;
	}
	/**
	 * [save_out_room 保存到学生退房的表里]
	 * @return [type] [description]
	 */
	function save_out_room($data){
		if(!empty($data)){
			$data['out_time']=strtotime($data['out_time']);
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_OUT_ROOM,$data);
			return $this->db->insert_id ();
		}
	}
	/**
	 * \
	 * @return [type] [退房插入历史记录表]
	 */
	function save_acc_history($data){
		if(!empty($data)){
			$data['leave_time']=strtotime($data['out_time']);
			unset($data['out_time']);
			$data['state']=2;
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ACC_HISTORY,$data);
			return $this->db->insert_id ();
		}
	}
	/**
	 * [save_landlord_info 保存房东的信息]
	 * @return [type] [description]
	 */
	function save_landlord_info($data){
		if(!empty($data)){
			$where='userid = '.$data['userid'];
			$is=$this->get_one_lan_info($where);
			if($is){
				$this->db->update(self::T_LAN_INFO,$data,'id = '.$is->id);
				return $is->id;
			}else{
				$data['createtime']=time();
				$data['adminid']=$_SESSION['master_user_info']->id;
				$this->db->insert(self::T_LAN_INFO,$data);
				return $this->db->insert_id ();
			}
			
		}
	}
	/**
	 * [get_one_lan_info 获取一条的信息]
	 * @return [type] [description]
	 */
	function get_one_lan_info($where=null){
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_LAN_INFO )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * [get_building_info 获取该校区下的楼宇]
	 * @return [type] [description]
	 */
	function get_building_info($cid,$id){
		if(!empty($cid)){
			$this->db->where('columnid',$cid);
			if($id!=0){
				$this->db->where('id',$id);
			}
			return $this->db->get(self::T_BULIDING)->result_array();
		}
		return array();
	}
	/**
	 * [get_room_info 获取该楼宇该楼层下的房间]
	 * @return [type] [description]
	 */
	function get_room_info($bid,$floor){
		if(!empty($bid)&&!empty($floor)){
			$this->db->where('bulidingid',$bid);
			$this->db->where('floor',$floor);
			return $this->db->get(self::T_PRICES)->result_array();
		}
		return array();
	}
}