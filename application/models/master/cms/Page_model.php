<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 学员风采管理
 *
 * @author zyj
 *        
 */
class Page_model extends CI_Model {
	const T_ARTICLE = 'column_info';
	const T_PAGE='pages_info';
	const T_ADMIN_INFO='admin_info';
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
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_ARTICLE )->count_all_results ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		$data= $this->db->order_by ( $orderby )->get ( self::T_ARTICLE )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
	}
	
	//获取管理员
	function get_admin_name($id){
		if(!empty($id)){
			$this->db->select('username');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_ADMIN_INFO)->row_array();
			return $data['username'];
		}
		return '';
	}
	/**
	 * 获取所有
	 */
	function get_($where = null) {
		if($where != null){
			return $this->db->where($where)->get( self::T_PAGE )->result ();
		}
		
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $programaid
	 *        	栏目ID
	 */
	function get_one($where = null) {
		if($where != null){
			return $this->db->where ($where)->get(self::T_PAGE)->row ();
		}else{
			return false;
		}
		
	}
	
	/**
	 * 保存
	 *
	 * @param number $programaid
	 *        	栏目ID
	 * @param array $data
	 *        	保存数据
	 * @param number $adminid
	 *        	修改人
	 * @return boolean
	 */
	function save($programaid = null, $data = array(), $adminid = null) {
		if ($programaid !== null && ! empty ( $data ) && is_array ( $data )) {
			$is = $this->get_one ( array('programaid' => $programaid,'site_language' => $data['site_language']));
			$time = time ();
			$data ['filename'] = '';
			$data ['lasttime'] = $time;
			$data ['lastuser'] = $adminid;
			if ($is) {
				return $this->db->update ( self::T_PAGE, $data, 'programaid = ' . $programaid .' AND site_language = "'.$data['site_language'].'"');
			} else {
				$data ['createtime'] = $time;
				return $this->db->insert ( self::T_PAGE, $data );
			}
		}
		return false;
	}
	
	/**
	 * 获取字段
	 */
	function field() {
		return $this->db->list_fields ( self::T_PAGE );
	}
	/**
	 * [get_last_time 获取最后修改时间]
	 * @param  [type] $id [description]
	 * @param  [type] $l  [description]
	 * @return [type]     [description]
	 */
	function get_page_info($id,$l){
		if(!empty($id)&&!empty($l)){
			$this->db->where('programaid',$id);
			$this->db->where('site_language',$l);
			return $this->db->get(self::T_PAGE)->row_array();
		}
		return array();
	}
}