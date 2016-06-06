<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Course_Model extends CI_Model {
	const T_COURSE = 'course';
	const T_MAJOR = 'major';
	const T_S = 'scholarship_info';
	const T_M = 'major';
	const T_T = 'templateclass'; // 模版
	const T_A = 'attachments'; // 附件
	const T_ATLAS_IMAGES = 'course_images';
	const T_ATLAS_CONTENT = 'course_content';
	const T_BOOKS='books';//书籍
	const T_COURSE_BOOKS='course_books';//课程关联书籍表
	const T_PAIKE='scheduling';
	const T_CHECKING='checking';
	const T_MAJOR_COURSE='major_course';
	const T_TEACHER_COURSE='teacher_course';
	
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
			
			return $this->db->get ( self::T_COURSE )->result ();
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
			
			return $this->db->from ( self::T_COURSE )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete($id) {
		if ($id != null) {
			$where = 'id=' . $id;
			$data = $this->get_one ( $where );
			
			if ($this->db->delete ( self::T_COURSE, 'id=' . $id )) {
				$this->db->delete ( self::T_ATLAS_CONTENT, 'courseid = ' . $id );
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_COURSE )->row ();
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
				if ($this->db->insert ( self::T_COURSE, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_COURSE, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 批量修改
	 */
	function someupdate($where = null, $data = null) {
		if ($where != null && $data != null) {
			return $this->db->update ( self::T_COURSE, $data, $where );
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
			return $this->db->update ( self::T_COURSE, array (
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
	 * 删除内容
	 */
	function del_course($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ATLAS_CONTENT, $where );
			return true;
		}
		return false;
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
			  'credit' =>  '学分' ,
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
		$data=$this->db->get(self::T_COURSE)->row_array();
		return $data['count'];
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into zust_course ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 * 获取前十个书籍
	 */
	function get_books_limit() {
		$this->db->limit(10);
		$this->db->where('state',1);
		return $this->db->get ( self::T_BOOKS)->result_array ();
	}
	/**
	 * 获取该课程的书籍
	 */
	function get_c_b($courseid) {
		if(!empty($courseid)){
			$this->db->where ( 'courseid', $courseid );
			return $this->db->get ( self::T_COURSE_BOOKS )->result_array ();
		}
		return array();
	}
	/**
	 * 保存课程-书籍
	 */
	function save_books($data = array()) {
		// var_dump($data);exit;
		if ($data ['id'] != null) {
			if ($this->db->delete ( self::T_COURSE_BOOKS, 'id=' . $data ['id'] )) {
				return 'del';
			}
		}
		if (! empty ( $data )) {
			$this->db->insert ( self::T_COURSE_BOOKS, $data );
			$id = $this->db->insert_id ();
			return $id;
		}
		return 0;
	}
	/**
	 * 获取所有的书籍
	 */
	function get_search_booksinfo_limit($text) {
		$this->db->limit(10);
		$this->db->like('name',$text);
		$this->db->where('state',1);
		return $this->db->get ( self::T_BOOKS )->result_array ();
	}
	/**
	 * 获取所有的书籍
	 */
	function get_books() {
		$this->db->where('state',1);
		return $this->db->get ( self::T_BOOKS )->result_array ();
	}
	/**
	 * 获取所有的课程
	 */
	function get_search_booksinfo($text) {
		$this->db->like('name',$text);
		$this->db->where('state',1);
		return $this->db->get ( self::T_BOOKS )->result_array ();
	}
		/**
	 *
	 * 删除关联的课程关系表
	 */
	function delete_guanlian($id){
		if ($id != null) {
			$this->db->delete ( self::T_MAJOR_COURSE, 'courseid = ' . $id );
			$this->db->delete ( self::T_TEACHER_COURSE, 'courseid = ' . $id );
			$this->db->delete ( self::T_PAIKE, 'courseid = ' . $id );
		}
		return false;
	}
}