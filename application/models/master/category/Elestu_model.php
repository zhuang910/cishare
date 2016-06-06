<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Elestu_Model extends CI_Model {
	const T_STUDENT = 'student';
	const T_C_E='course_elective';
	const T_MAJOR='major';
	const T_SQUAD='squad';
	const T_E_S='electives_scheduling';
	const T_COURSE = 'course';
	const T_TEACHER='teacher';
	const T_CLASSROOM='classroom';
	const T_S='scheduling';
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
	function get($field, $condition,$cid,$label_id) {
		if (is_array ( $field ) && ! empty ( $field )) {
			
			$this->db->select ('course_elective.state,course_elective.week,course_elective.knob,classroom.name as rname,course.name as cname,teacher.name as tname,course_elective.id as id,student.userid as userid,student.id as sid,student.name as name,student.enname as enname,student.passport as passport,squad.nowterm as nowterm');
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$this->db->join ( self::T_STUDENT, self::T_STUDENT . '.userid=' . self::T_C_E . '.userid' );
			$this->db->join ( self::T_SQUAD, self::T_SQUAD . '.id=' . self::T_STUDENT . '.squadid' );
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_C_E . '.teacherid' );
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_C_E . '.courseid' );
			$this->db->join ( self::T_CLASSROOM, self::T_CLASSROOM . '.id=' . self::T_C_E . '.classroomid' );
			$this->db->where('course_elective.courseid',$cid);
			$this->db->where('course_elective.state',$label_id);
			return $this->db->get ( self::T_C_E )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition,$cid,$label_id) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->join ( self::T_STUDENT, self::T_STUDENT . '.userid=' . self::T_C_E . '.userid' );
			$this->db->join ( self::T_SQUAD, self::T_SQUAD . '.id=' . self::T_STUDENT . '.squadid' );
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_C_E . '.teacherid' );
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_C_E . '.courseid' );
			$this->db->where('course_elective.courseid',$cid);
			$this->db->where('course_elective.state',$label_id);
			return $this->db->from ( self::T_C_E )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [get_major_squad 获取专业和班级]
	 * @return [type] [description]
	 */
	function get_major_squad($id){
		if(!empty($id)){
			$this->db->select ('major.name as mname,squad.name as sname');
			$this->db->join ( self::T_MAJOR, self::T_STUDENT . '.majorid=' . self::T_MAJOR . '.id' );
			$this->db->join ( self::T_SQUAD, self::T_STUDENT . '.squadid=' . self::T_SQUAD . '.id' );
			$this->db->where('student.id',$id);
			$data=$this->db->get ( self::T_STUDENT)->row_array();
			if(!empty($data)){
				$str=$data['mname'].'->'.$data['sname'];
				return $str;
			}else{
				return '还没有分班';
			}
			
		}
		return '';
	}
	/**
	 * [get_electives_info 获取选课的排课信息]
	 * @param  [type] $cid [课程id]
	 * @return [type]      [description]
	 */
	function get_electives_info($cid){
		if(!empty($cid)){
			$this->db->select('electives_scheduling.teacherid as teacherid,electives_scheduling.classroomid as classroomid,electives_scheduling.id as id,teacher.name as tname,course.name as cname,classroom.name as rname,electives_scheduling.week,electives_scheduling.knob,electives_scheduling.courseid');
			$this->db->where('electives_scheduling.courseid',$cid);
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_E_S . '.teacherid' );
			$this->db->join ( self::T_CLASSROOM, self::T_CLASSROOM . '.id=' . self::T_E_S . '.classroomid' );
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_E_S . '.courseid' );
			return $this->db->get(self::T_E_S)->result_array();
		}
		return array();
	}
	/**
	 * [save_scheduling 存入排课]
	 * @return [type] [description]
	 */
	function save_scheduling($data){
		if(!empty($data)){
			//userid和当前学期
			$arr=json_decode($data['ids']);
			foreach ($arr as $k => $v) {
				$userid_term=explode('-grf-', $v);
				$data['userid']=$userid_term[0];
				$data['nowterm']=$userid_term[1];
				$data['majorid']=$data['courseid'];
				$data['squadid']=$data['courseid'];
				unset($data['ids']);
				$is=$this->chcked_scheduling($data);
				if($is>0){
					continue;
				}
				$this->db->insert ( self::T_S, $data );
			}
			return 1;
		}
	}
	/**
	 * [chcked_scheduling 检查排课信息是否插入]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function chcked_scheduling($data){
		if(!empty($data)){
			$this->db->select('count(*) as num');
			$this->db->where('courseid',$data['courseid']);
			$this->db->where('nowterm',$data['nowterm']);
			$this->db->where('userid',$data['userid']);
			$data=$this->db->get(self::T_S)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [save_edit_scheduling 存入单挑数据]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function save_edit_scheduling($data){
		if(!empty($data)){
			unset($data['id']);
			$data['majorid']=$data['courseid'];
			$data['squadid']=$data['courseid'];
			$this->db->insert ( self::T_S, $data );
			return $this->db->insert_id ();
		}
	}
	/**
	 * [get_scheduling_info 获取学生对该课程的排课情况]
	 * @param  [type] $cid     [description]
	 * @param  [type] $userid  [description]
	 * @param  [type] $nowterm [description]
	 * @return [type]          [description]
	 */
	function get_scheduling_info($cid,$userid,$nowterm){
		$this->db->where('courseid',$cid);
		$this->db->where('nowterm',$nowterm);
		$this->db->where('userid',$userid);
		$data=$this->db->get(self::T_S)->result_array();
		return $data;
	}
	/**
	 * [del_scheduling 删除]
	 * @return [type] [description]
	 */
	function del_scheduling($data){
		if(!empty($data)){
			$this->db->delete ( self::T_S, 'id='.$data['id']);
		}
	}
}