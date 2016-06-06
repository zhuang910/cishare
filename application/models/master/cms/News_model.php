<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 学员风采管理
 *
 * @author zyj
 *        
 */
class News_model extends CI_Model {
	const T_ARTICLE = 'article_info';
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
		return $this->db->from ( self::T_ARTICLE )->count_all_results ();
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
		
		return $this->db->order_by ( $orderby )->get ( self::T_ARTICLE )->result ();
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_ARTICLE)->row ();
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
			), 'articleid = ' . $id );
		}
	}
	
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ARTICLE, $where);
			return true;
		}
		return false;
	}
	/**
	 * [get_news_colum 获取文章模型的栏目]
	 * @return [array]
	 */
	function get_news_colum(){
		$this->db->where('module_id',3);
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