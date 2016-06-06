<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Img_Model extends CI_Model {
	const T_IMG = 'image_info';
	const T_COLUMN='column_info';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 统计申请条数
	 *
	 * @param string $where        	
	 */
	function count($where = null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_IMG )->count_all_results ();
	}
	
	/**
	 * 获取申请信息
	 *
	 * @param string $where
	 *        	条件
	 * @param number $limit
	 *        	偏移量
	 * @param number $offset        	
	 * @param string $orderby
	 *        	排序
	 * @author z.junjie 2014-6-28
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'orderby desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
		
		return $this->db->order_by ( $orderby )->get ( self::T_IMG )->result ();
	}
	
	// /**
	//  * 统计条数
	//  *
	//  * @param array $field        	
	//  * @param array $condition        	
	//  */
	// function count($condition) {
	// 	if (is_array ( $condition ) && ! empty ( $condition )) {
	// 		if (! empty ( $condition ['where'] )) {
	// 			$this->db->where ( $condition ['where'] );
	// 		}
			
	// 		return $this->db->from ( self::T_IMG )->count_all_results ();
	// 	}
	// 	return 0;
	// }
	// /**
	//  * 获取列表数据
	//  *
	//  * @param array $field        	
	//  * @param array $condition        	
	//  */
	// function get($field, $condition) {
	// 	if (is_array ( $field ) && ! empty ( $field )) {
	// 		$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
	// 		if (is_array ( $condition ) && ! empty ( $condition )) {
	// 			if (! empty ( $condition ['where'] )) {
	// 				$this->db->where ( $condition ['where'] );
	// 			}
				
	// 			if (! empty ( $condition ['orderby'] )) {
	// 				$this->db->order_by ( $condition ['orderby'] );
	// 			}
	// 			$this->db->limit ( $condition ['limit'], $condition ["offset"] );
	// 		}
	// 		return $this->db->get ( self::T_IMG )->result ();
	// 	}
	// 	return array ();
	// }
	
	/**
	 * 保存
	 */
	function save($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::T_IMG, $data, 'id = ' . $id );
		} else {
			$this->db->insert ( self::T_IMG, $data );
			return $this->db->insert_id ();
		}
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T_IMG, 'id = ' . $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 删除
	 *
	 * @param number $menuid        	
	 */
	function delete($id = 0) {
		if ($id) {
			return $this->db->delete ( self::T_IMG, 'id = ' . $id );
		}
	}
	/**
	 * [get_news_colum 获取文章模型的栏目]
	 * @return [array]
	 */
	function get_news_colum(){
		$this->db->where('module_id',5);
		$result=$this->db->get(self::T_COLUMN)->result_array();
		if(!empty($result)){
			return $result;
		}else{
			return array();
		}
	}
	/**
	 * [get_colum_name 获取栏目的名字]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_colum_name($id){
		if(!empty($id)){
			$this->db->select('title');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_COLUMN)->row_array();
			if($data['title']){
				return $data['title'];
			}
		}
		return '';
	}

}