<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Books_Model extends CI_Model {
	const T_BOOKS='books';//书籍
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
			
			return $this->db->get ( self::T_BOOKS )->result ();
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
			
			return $this->db->from ( self::T_BOOKS )->count_all_results ();
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
			if ($this->db->delete ( self::T_BOOKS, $where)) {
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_BOOKS )->row ();
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
				if ($this->db->insert ( self::T_BOOKS, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_BOOKS, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 批量修改
	 */
	function someupdate($where = null, $data = null) {
		if ($where != null && $data != null) {
			return $this->db->update ( self::T_BOOKS, $data, $where );
		} else {
			return false;
		}
	}
	
	/**
	 * 获得奖学金信息
	 */
	function get_scholarshi() {
		$data = array ();
		$data = $this->db->where ( 'state = 1' )->get ( self::T_S )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['id']] = $v ['title'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 获得专业
	 */
	function get_major() {
		$data = array ();
		$data = $this->db->where ( 'state = 1' )->get ( self::T_M )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['id']] = $v ['name'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 模版信息
	 */
	function get_templates() {
		$data = array ();
		$where = "classType = 1";
		$where .= ' AND admin_id = 0';
		$data = $this->db->where ( $where )->get ( self::T_T )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['tClass_id']] = $v ['ClassName'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 附件信息
	 */
	function get_attachments() {
		$data = array ();
		$data = $this->db->where ( 'atta_id > 1' )->get ( self::T_A )->result_array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				$basic [$v ['atta_id']] = $v ['AttaName'];
			}
			return $basic;
		}
		return array ();
	}
	
	/**
	 * 审核文章
	 *
	 * @param number $id        	
	 * @param number $state        	
	 */
	function save_audit($id = null, $state = 1) {
		if ($id !== null) {
			return $this->db->update ( self::T_BOOKS, array (
					'state' => $state 
			), 'id = ' . $id );
		}
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_course_content($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ATLAS_CONTENT )->row ();
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
	function save_course($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_ATLAS_CONTENT, $data );
				return $this->db->insert_id ();
			} else {
				return $this->db->update ( self::T_ATLAS_CONTENT, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 *
	 *获取专业字段
	 **/
	function get_course_fields(){
		return array(
			  'name' =>  '课程名',
			  'englishname' =>  '英文名字' ,
			  'hour' =>  '课时',
			  'absenteeism' =>  '缺勤通知线' ,
			  'expel' =>  '开除通知线' ,
			  'variable' =>  '是否选修' ,
			  'state' =>  '状态(是否启用)' ,
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
		$data=$this->db->get(self::T_BOOKS)->row_array();
		return $data['count'];
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into zust_books ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 * [get_course_fields 导出的字段]
	 * @return [type] [description]
	 */
	function get_item_fields(){
		return array(
			'name'=>'中文名字',
			'enname'=>'英文名字',
			'price'=>'单价',
			'state'=>'状态'
			);
	}

}