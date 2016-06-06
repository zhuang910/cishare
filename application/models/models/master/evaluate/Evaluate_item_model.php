<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Evaluate_item_Model extends CI_Model {
	const T_E_CLASS = 'evaluate_class';
	const T_E_ITEM = 'evaluate_item';
	const T_E_ITEM_INFO = 'evaluate_item_info';


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
	function count($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			
			return $this->db->from ( self::T_E_ITEM )->count_all_results ();
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
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_E_ITEM )->result ();
		}
		return array ();
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_E_ITEM, $where);
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
				$base = $this->db->where ($where)->limit(1)->get(self::T_E_ITEM)->row ();
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
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_E_ITEM_INFO)->row ();
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
				$this->db->insert ( self::T_E_ITEM, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_E_ITEM, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [set_evaluate_time 设置时间]
	 */
	function set_evaluate_time($data){
		if(!empty($data)){
			$arr['starttime']=strtotime($data['starttime']);
			$arr['endtime']=strtotime($data['endtime']);
			$json_ids=$data['ids'];
			unset($data['ids']);
			$ids= json_decode($json_ids);
			if(!empty($ids)){
				foreach ($ids as $k => $v) {
					$this->db->update(self::T_E_ITEM,$arr,'id='.$v);
				}
			}
		}
	}
	/**
	 * [update_info 更新中英文项信息]
	 * @return [type] [description]
	 */
	function update_info($data){
		if(!empty($data)){
			$num=$this->check_item_info($data['itemid'],$data['site_language']);
			if($num>0){
				$this->db->update(self::T_E_ITEM_INFO,$data,'itemid = '.$data['itemid'].' AND site_language = "'.$data['site_language'].'"');
				return $data['itemid'];
			}else{
				$this->db->insert(self::T_E_ITEM_INFO,$data);
				return $this->db->last_query();
			}
		}
	}
	/**
	 * [check_item_info 查询项信息]
	 * @return [type] [description]
	 */
	function check_item_info($itemid,$site_language){
		if(!empty($itemid)){
			$this->db->select('count(*) as num');
			$this->db->where('itemid',$itemid);
			$this->db->where('site_language',$site_language);
			$data=$this->db->get(self::T_E_ITEM_INFO)->row_array();
			if(!empty($data)){
				return $data['num'];
			}
		}
		return 0;
	}
}