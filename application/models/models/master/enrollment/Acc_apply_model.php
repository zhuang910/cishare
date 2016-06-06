<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zyj
 *        
 */
class Acc_apply_Model extends CI_Model {
	const T_ARTICLE = 'accommodation_info';
	const T_C = 'credentials';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_USER_ROOM='user_room';
	const T_STUDENT_INFO='student_info';

	
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
	function count($condition, $where = null) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			if ($where !== null) {
					$this->db->where ( $where );
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
	function get($field, $condition, $where = null) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if ($where !== null) {
					$this->db->where ( $where );
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
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	
	/**
	 * 获取群组的名称
	 */
	function get_group($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->get ( self::T_G )->result_array ();
			
			if ($base) {
				foreach ( $base as $k => $v ) {
					$data [$v ['id']] = $v ['title'];
				}
				
				return $data;
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
				$this->db->insert ( self::T_ARTICLE, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_content($id = null, $data = array()) {
		if (! empty ( $data )) {
			// 验证内容表是否存在$id
			$is = $this->db->get_where ( self::T_ARTICLE_CONTENT, 'articleid = ' . $id, 1, 0 )->row ();
			if (! empty ( $is )) {
				return $this->db->update ( self::T_ARTICLE_CONTENT, $data, 'articleid = ' . $id );
			} else {
				$data ['articleid'] = $id;
				return $this->db->insert ( self::T_ARTICLE_CONTENT, $data );
			}
		}
	}
	
	/**
	 * 审核文章
	 *
	 * @param number $id        	
	 * @param number $state        	
	 */
	function save_audit($id = null, $state = 1) {
		if ($id !== null) {
			return $this->db->update ( self::T_ARTICLE, array (
					'state' => $state 
			), 'id = ' . $id );
		}
	}
	/**
	 * 审核文章
	 *
	 * @param number $id        	
	 * @param number $state        	
	 */
	function save_acc_state($id = null, $acc_state = 1) {
		if ($id !== null) {
			return $this->db->update ( self::T_ARTICLE, array (
					'acc_state' => $acc_state 
			), 'id = ' . $id );
		}
	}
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ARTICLE, $where );
			return true;
		}
		return false;
	}
	/**
	 * [pay_change_state 现场缴费修改状态]
	 * @param  [array] $data [修改数据]
	 * @return [type]       [Boolean]
	 */
	function pay_change_state($data){
		$arr=array();
		if(!empty($data)){
			$arr['paytime']=time();
			$arr['danwei']=$data['currency'];
			$arr['registeration_fee']=$data['amount'];
			$arr['paystate']=1;
			$this->db->update ( self::T_ARTICLE, $arr, 'id = ' . $data['id']);
			return true;
		}
		return flase;
	}
	/**
	 * [insert_pay_record 插入缴费记录]
	 * @param  [array] $data [插入数据]
	 * @return [type]       [Boolean]
	 */
	function insert_pay_record($data){
		if(!empty($data)){
			unset($data['id']);
			$data['state']=1;
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert ( self::T_C, $data);
			return true;
		}
		return flase;
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
	 * [insert_user_room 插入学生房间表]
	 * @param  [type] $userid [description]
	 * @param  [type] $roomid [description]
	 * @return [type]         [description]
	 */
	function insert_user_room($userid,$roomid){
		
		if(!empty($userid)&&!empty($roomid)){
			$data['userid']=$userid;
			$data['roomid']=$roomid;
			$data['adminid']=$_SESSION['master_user_info']->id;
			$data['createtime']=time();
			$this->db->insert ( self::T_USER_ROOM, $data );
			return $this->db->insert_id ();
		}
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
	 * [get_buliding_info 获取该校区下的楼房]
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	function get_buliding_info($cid){
		if(!empty($cid)){
			$this->db->where('columnid',$cid);
			$data=$this->db->get(self::T_BULIDING)->result_array();
			return $data;
		}
		return array();
	}
	/**
	 * [get_buliding_info 获取该校区下的楼房]
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	function get_buliding_floor($bid){
		if(!empty($bid)){
			$this->db->select('floor_num');
			$this->db->where('id',$bid);
			$data=$this->db->get(self::T_BULIDING)->row_array();
			if(!empty($data['floor_num'])){
				return $data['floor_num'];
			}
		}
		return 0;
	}
	/**
	 * [get_where_room 获取该校区该楼层的房间]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	function get_where_room($data=array()){
		if(!empty($data)){
			$this->db->where('bulidingid',$data['bulidingid']);
			$this->db->where('floor',$data['floor']);
			$data=$this->db->get(self::T_PRICES)->result_array();
			return $data;
		}
	}
	/**
	 * [update_room_shate 更新房间的状态入预订多少人和已满的状态]
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
			if($in_user_num>=$maxuser_num){
				$arr['is_reserve']=2;
				$this->db->update(self::T_PRICES,$arr,'id='.$roomid);
			}
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
	//获取现实预订的人数
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
	 * [get_tj_before 获取调剂之前的所申请的住宿信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_tj_before($id){
		if(!empty($id)){
			$this->db->select('campid,buildingid,floor,roomid');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_ARTICLE)->row_array();
			$json_data=json_encode($data);
			if(!empty($json_data)){
				return $json_data;
			}
		}
		return '';
	}
	/**
	 * [get_room_manmeiman 判断房间满没满]
	 * @return [type] [description]
	 */
	function get_room_manmeiman($roomid){
		if(!empty($roomid)){
			$this->db->where('id',$roomid);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if($data['in_user_num']>=$data['maxuser']){
				//1是满
				return 1;
			}
		}
		//0是不满
		return 0;
	}
}