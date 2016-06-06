<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zyj
 *        
 */
class Buildingprice_Model extends CI_Model {
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_FLOOR_ROOM='buliding_floor_room';//楼层房间数
	const T_USER_ROOM='user_room';
	const T_PRICES_INFO = 'school_accommodation_prices_info'; // 院校校区宿舍价格表
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
			return $this->db->from ( self::T_PRICES )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition, $programaids = null) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if ($programaids !== null) {
					$this->db->where ( 'columnid in(' . $programaids . ')' );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_PRICES )->result ();
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_PRICES )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_info_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_PRICES_INFO )->row ();
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
	function save_info($id = null, $data = array()) {
		if (! empty ( $data )) {
			$where = 'roomid='.$data['roomid'].' AND site_language="'.$data['site_language'].'"';
			$num=$this->get_info_one($where);
			if(!empty($num)){
				$this->db->delete ( self::T_PRICES_INFO, $where );

			}
			if ($id == null) {
				$data['createtime']=time();
				$data['adminid']=$_SESSION['master_user_info']->id;
				$this->db->insert ( self::T_PRICES_INFO, $data );
				return $this->db->insert_id ();
			}
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
				$this->db->insert ( self::T_PRICES, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_PRICES, $data, 'id = ' . $id );
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
			return $this->db->update ( self::T_BULIDING, array (
					'state' => $state 
			), 'id = ' . $id );
		}
	}
	
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_PRICES, $where );
			return true;
		}
		return false;
	}
	/**
	 * [get_building_floor_num 获取楼层数]
	 * @param  [type] $buildingid [楼层id]
	 * @return [type]             [楼层数]
	 */
	function get_building_floor_num($buildingid){
		if(!empty($buildingid)){
			$this->db->select('floor_num');
			$this->db->where('id',$buildingid);
			$data=$this->db->get(self::T_BULIDING)->row_array();
			if(!empty($data)){
				return $data['floor_num'];
			}
		}
		return 0;
	}
	/**
	 * [get_room_num 获取该楼该楼层的房间数]
	 * @param  [type] $bulidingid [description]
	 * @param  [type] $floor      [description]
	 * @return [type]             [description]
	 */
	function get_room_num($bulidingid,$floor){
		if(!empty($bulidingid)&&!empty($floor)){
			$this->db->select('count(*) as num');
			$this->db->where('bulidingid',$bulidingid);
			$this->db->where('floor',$floor);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if($data['num']){
				return $data['num'];
			}
		}
		return 0;
	}
	/**
	 * [get_bulidingid_floor_room_num 获取该楼还楼层设置的房间数]
	 * @param  [type] $bulidingid [description]
	 * @param  [type] $floor      [description]
	 * @return [type]             [description]
	 */
	function get_bulidingid_floor_room_num($bulidingid,$floor){
		if(!empty($bulidingid)&&!empty($floor)){
			$this->db->select('room_num');
			$this->db->where('bulidingid',$bulidingid);
			$this->db->where('floor',$floor);
			$data=$this->db->get(self::T_FLOOR_ROOM)->row_array();
			if($data['room_num']){
				return $data['room_num'];
			}
		}
		return 0;
	}
	/**
	 * [update_room_shate 更新房间的状态入住多少人和已满的状态]
	 * @return [type] [description]
	 */
	function update_room_shate($roomid,$num){
		if(!empty($roomid)){
			//获取该房间的容纳多少人
			$maxuser_num=$this->get_room_maxuser($roomid);
			$in_user_num=$this->get_in_user_num($roomid);
			if($in_user_num>=$num){
				return $maxuser_num;
			}
			return 0;
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
			$data=$this->db->get(self::T_USER_ROOM)->row_array();
			if(!empty($data['num'])){
				return $data['num'];
			}
		}
		return 0;
	}
}