<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Accommodation_Model extends CI_Model {
	const T_ARTICLE = 'pages_info';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_STUDENT_INFO='student_info';
	const T_ACC='accommodation_info';
	const T_BAOXIU='repairs_info';
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
	function count($condition, $programaids = null) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			if ($programaids !== null) {
				$this->db->where ( 'columnid in(' . $programaids . ')' );
			}
			return $this->db->from ( self::T_ARTICLE )->count_all_results ();
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->result_array ();
			if ($base) {
				return $base [0];
			}
			return array ();
		}
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_buliding_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_BULIDING )->result_array ();
			if ($base) {
				return $base [0];
			}
			return array ();
		}
	}
	/**
	 * 获取多条数据
	 *
	 * @param number $id        	
	 */
	function get_news($where = null, $limit = null) {
		if ($where != null && $limit != null) {
			$base = array ();
			$base = $this->db->where ( $where )->order_by ( 'orderby DESC' )->limit ( $limit )->get ( self::T_ARTICLE )->result_array ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	
	/**
	 * 得到多条信息 默认降序
	 * @table 表名
	 * @where 条件
	 * @select 查询字段
	 * @offset 从第几条开始查询
	 * @size 查询多少条
	 * @orderby 排序
	 */
	function getall($where = 'id > 0', $select = '*', $offset = '0', $size = '10', $orderby = 'id DESC') {
		$res = array ();
		$query = $this->db->select ( $select )->order_by ( $orderby )->get_where ( self::T_ARTICLE, $where, $size, $offset );
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
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
				$this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
			}
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
	 * [get_camp_bulid_room 获取该校区下的该楼层下的房间]
	 * @return [type] [description]
	 */
	function get_camp_bulid_room($cid,$bid){
		if(!empty($cid)&&!empty($bid)){
			// $this->db->where('campusid',$cid);
			$this->db->where('bulidingid',$bid);
			$this->db->where('is_reserve <>0');
			return $this->db->get(self::T_PRICES)->result_array();
		}
	}
	/**
	 * [get_user_info 获取用户信息]
	 * @return [type] [description]
	 */
	function get_user_info($userid){
		if(!empty($userid)){
			$this->db->where('id',$userid);
			return $this->db->get(self::T_STUDENT_INFO)->row_array();
		}
	}
	/**
	 * 获取房间的信息
	 */
	function get_room_info_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_PRICES)->row_array();
		}
	}
	/**
	 * [save_accommodation_info ]
	 * @return [type] [description]
	 */
	function save_accommodation_info($data){
		if(!empty($data)){
			$max_cucasid = build_order_no ();
			$data['ordernumber']= $max_cucasid;
			$data['subtime']=time();
			$data['danwei']='rmb';
			$data['applytime']=time();
			$data['paystate']=0;
			$is_shoufei=$this->check_isshoufei();
			if($is_shoufei==1){
				$data['acc_state']=1;
			}else{
				$data['acc_state']=2;
			}
			
			$this->db->insert(self::T_ACC,$data);
			return $this->db->insert_id();
		}
	}
	/**
	 *检车是否收费 
	 */
	function check_isshoufei(){
		$flag_isshoufei=0;
		$stay = CF ( 'stay', '', CONFIG_PATH );
			if (! empty ( $stay ) && in_array ( $stay ['stay'], array (
					'yes',
			) )) {
				// 住宿费和押金
				$flag_isshoufei = 1;
			}
			$stay_yajin = CF ( 'acc_pledge', '', CONFIG_PATH );
			if (! empty ( $stay_yajin ) && in_array ( $stay_yajin ['acc_pledge'], array (
					'yes',
			) )) {
				// 住宿费和押金
				$flag_isshoufei = 1;
			}
			return $flag_isshoufei;
	}
	/**
	 * [get_acc_info 获取该学生的房间信息]
	 * @return [type] [description]
	 */
	function get_acc_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			return $this->db->get(self::T_ACC)->row_array();
		}
		return array();
	}
	/**
	 * [save_baoxiu d插入保修表]
	 * @return [type] [description]
	 */
	function save_baoxiu($data){
		if(!empty($data)){
			$data['state']=0;
			$data['createtime']=time();
			$this->db->insert(self::T_BAOXIU,$data);
			return $this->db->insert_id();
		}
	}
	/**
	 * [get_user_info 获取该用户的保修的信息]
	 * @return [type] [description]
	 */
	function get_user_repairs_info($userid,$state){
		if(!empty($userid)){
			$this->db->select('repairs_info.*,school_accommodation_campus.name as cname,school_accommodation_campus.enname as cenname,school_accommodation_buliding.name as bname,school_accommodation_buliding.enname as benname,school_accommodation_prices.name as rname,school_accommodation_prices.enname as renname');
			$this->db->where('repairs_info.userid',$userid);
			$this->db->where('repairs_info.state',$state);
			$this->db->join ( self::T_CAMPUS, self::T_CAMPUS . '.id=' . self::T_BAOXIU . '.campusid' );
			$this->db->join ( self::T_BULIDING, self::T_BULIDING . '.id=' . self::T_BAOXIU . '.buildingid' );
			$this->db->join ( self::T_PRICES, self::T_PRICES . '.id=' . self::T_BAOXIU . '.roomid' );
			return $this->db->get(self::T_BAOXIU)->result_array();
		}	
	}
	/**
	 * [check_quanxian 查找有没有权限]
	 * @return [type] [description]
	 */
	function check_quanxian($userid,$id){
		if(!empty($userid)&&!empty($id)){
			$this->db->select('count(*) as num');
			$this->db->where('userid',$userid);
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_BAOXIU)->row_array();
			return $data['num'];
		}
		return 0;
	}
	/**
	 * [get_baoxiu_remark 获取学生的保修备注]
	 * @return [type] [description]
	 */
	function get_baoxiu_remark($id){
		if(!empty($id)){
			$this->db->select('remark');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_BAOXIU)->row_array();
			return $data['remark'];
		}
		return '';
	}
	/**
	 * 删除报修服务
	 * @return [type] [description]
	 */
	function delete_repairs($id){
		if ($id != null) {
			$this->db->delete ( self::T_BAOXIU, 'id = '.$id );
			return true;
		}
		return false;
	}
}