<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Teacher_knob_Model extends CI_Model {
	const T_TEACHER_COURSE = 'teacher_course';
	const T_COURSE = 'course';
	const T_MAJOR = 'major';
	const T_TEACHER = 'teacher';
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
	function count($condition, $teacherid) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->join(self::T_COURSE ,self::T_TEACHER_COURSE.'.courseid='.self::T_COURSE.'.id');
			$this->db->where('teacherid',$teacherid);
			$this->db->where('week',99);
			$this->db->where('knob',99);
			return $this->db->from ( self::T_TEACHER_COURSE )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition,$teacherid) {
		if (is_array ( $field ) && ! empty ( $field )) {
			//$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			$this->db->select('teacher_course.id,teacher_course.courseid,course.name as cname');
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$this->db->join(self::T_COURSE ,self::T_TEACHER_COURSE.'.courseid='.self::T_COURSE.'.id');
				$this->db->where('teacherid',$teacherid);
				$this->db->where('week',99);
				$this->db->where('knob',99);
			return $this->db->get ( self::T_TEACHER_COURSE )->result ();
		}
		return array ();
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_TEACHER_COURSE, $where);
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
				$base = $this->db->where ($where)->limit(1)->get(self::T_TEACHER_COURSE)->row ();
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
	function save( $data = array()) {
		
		if($data['id']!=null){
			if($this->db->delete ( self::T_TEACHER_COURSE, 'id='.$data['id'])){
						return 'del';
			}
		}
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_TEACHER_COURSE, $data );
				$id=$this->db->insert_id ();
				if($id){
						return $id;
				}
				return 0;
				
		} 
		return 0;
	}

	/**
	 * 获取课程信息        	
	 */
	function get_course_info($majorid){
		$this->db->where('majorid',$majorid);
		return $this->db->get ( self::T_COURSE)->result_array ();
	}
	/**
	 * 获取专业id
	 *
	 * @param number $courseid        	
	 */
	function get_majorid($courseid){
	$this->db->select('majorid');
	$this->db->where('id',$courseid);
	return $this->db->limit(1)->get(self::T_COURSE)->row_array();
		
	
	}
	/**
	 * 插入全部
	 *
	 * @param $data     	
	 */
	function insert_all($data){
	
		$where="teacherid=".$data['teacherid']." and courseid=".$data['courseid']." and week=".$data['week']." and knob=".$data['knob'];
		$result=$this->db->where ($where)->limit(1)->get(self::T_TEACHER_COURSE)->row ();
		if($result!=null){
			if($result->state==1){
				$data['state']=0;
				$this->db->update ( self::T_TEACHER_COURSE, $data, $where );
			}else{
				$data['state']=1;
				$this->db->update ( self::T_TEACHER_COURSE, $data, $where );
			}
		}else{
			$data['state']=1;
			$this->db->insert ( self::T_TEACHER_COURSE, $data );
		}
		return $result->state;
	}
	/**
	 * 获取老师时间信息
	 *
	 * @param $data     	
	 */
	function get_time_info($teacherid,$courseid){
		$this->db->where('teacherid',$teacherid);
		$this->db->where('courseid',$courseid);
		return $this->db->get(self::T_TEACHER_COURSE)->result_array();
	}
	/**
	 * 获取老师信息
	 *
	 * @param $teacherid    	
	 */
	function get_teacher_info($teacherid){
		$this->db->where('id',$teacherid);
		return $this->db->get(self::T_TEACHER)->row_array();;
	}
	/**
	 * 获取一条课程信息
	 *
	 * @param $courseid    	
	 */
	function get_course_one($courseid){
		$this->db->where('id',$courseid);
		return $this->db->get(self::T_COURSE)->row_array();;
	}
	/**
	 * 获取所有的课程
	 *       	
	 */
	function get_course(){
		return $this->db->get(self::T_COURSE)->result_array();
	}
	/**
	 *
	 *获取老师id
	 **/
	function get_teacherid($userid){
		$this->db->select('id');
		$this->db->where('userid',$userid);
		$data=$this->db->get(self::T_TEACHER)->row_array();
		return $data['id'];
	}
	/**
	 * 获取所有的课程
	 */
	function get_course_limit() {
		$this->db->limit(10);
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
	/**
	 * 获取老师挈带课程
	 *       	
	 */
	function get_t_c($tid){
		$this->db->where('teacherid',$tid);
		$this->db->where('week',99);
		$this->db->where('knob',99);
		return $this->db->get(self::T_TEACHER_COURSE)->result_array();
	}
		/**
	 * 获取所有的课程
	 */
	function get_search_courseinfo_limit($text) {
		$this->db->limit(10);
		$this->db->like('name',$text);
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
	/**
	 * 获取所有的课程
	 */
	function get_search_courseinfo($text) {
		$this->db->like('name',$text);
		return $this->db->get ( self::T_COURSE )->result_array ();
	}
}