<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Itemsetting_Model extends CI_Model {
	const T_SET_SCORE='set_score';
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
			
			return $this->db->get ( self::T_SET_SCORE )->result ();
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
			
			return $this->db->from ( self::T_SET_SCORE )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete($where) {
		if ($where != null) {
			if ($this->db->delete ( self::T_SET_SCORE,$where )) {
				return true;
			}
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
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_SET_SCORE )->row ();
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
				if ($this->db->insert ( self::T_SET_SCORE, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_SET_SCORE, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [get_course_fields 导出的字段]
	 * @return [type] [description]
	 */
	function get_item_fields(){
		return array(
			'code'=>'代号',
			'name'=>'中文名字',
			'enname'=>'英文名字',
			'scores_of'=>'考试占比',
			'state'=>'状态'
			);
	}
	/**
	 *
	 *检查是否有重复记录
	 *@$insert:字段
	 *@$value:字段值
	 **/
	function check_course($insert,$value){
		$insert=explode(',',$insert);
		$value=explode(',',$value);
		$this->db->select('count(*) as count');
		$this->db->where($insert[0],trim($value[0],'""'));
		$data=$this->db->get(self::T_SET_SCORE)->row_array();
		return $data['count'];
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into zust_set_score ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
}