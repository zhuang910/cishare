<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zyj
 *        
 */
class Buildings_Model extends CI_Model {
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_FLOOR_ROOM='buliding_floor_room';//楼层房间数
	const T_BULIDING_INFO = 'school_accommodation_buliding_info'; // 院校校区内容表
	
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
			return $this->db->from ( self::T_BULIDING )->count_all_results ();
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
			return $this->db->get ( self::T_BULIDING )->result ();
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_BULIDING )->row ();
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_BULIDING_INFO )->row ();
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
				$this->db->insert ( self::T_BULIDING, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_BULIDING, $data, 'id = ' . $id );
			}
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
			$where='bulidingid='.$data['bulidingid'].' AND "'.$data['site_language'].'"';
			$num=$this->get_info_one($where);
			if(!empty($num)){
				$this->db->delete ( self::T_BULIDING_INFO, $where );
			}
			if ($id == null) {
				$data['createtime']=time();
				$data['adminid']=$_SESSION['master_user_info']->id;
				$this->db->insert ( self::T_BULIDING_INFO, $data );
				return $this->db->insert_id ();
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
			$this->db->delete ( self::T_BULIDING, $where );
			return true;
		}
		return false;
	}
	/**
	 * [insert_floor_room_num 添加楼层房间数]
	 * @return [type] [description]
	 */
	function insert_floor_room_num($id = null, $data = array()){
		if($id!==null&&!empty($data)){
			$insert_arr['bulidingid']=$id;
			foreach ($data as $k => $v) {
				$insert_arr['floor']=$k;
				if(!empty($v)){
					$insert_arr['room_num']=$v;
					$insert_arr['adminid']=$_SESSION['master_user_info']->id;
					$insert_arr['createtime']=time();
				}else{
					continue;
				}
				$this->db->insert ( self::T_FLOOR_ROOM, $insert_arr );
			}
		}
	}
	/**
	 * [update_major_tuition 更新专业学期学费]
	 * @param  [type] $id   [description]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	function update_floor_room_num($id = null, $data = array()){
		if($id!==null&&!empty($data)){
			$this->db->delete ( self::T_FLOOR_ROOM, 'bulidingid=' . $id );
			$this->insert_floor_room_num($id,$data);
		}
	}
	/**
	 * 获取总楼层的总房间数
	 *
	 * @param number $majorid       	
	 */
	function get_floor_room($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->get ( self::T_FLOOR_ROOM )->result_array ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
}