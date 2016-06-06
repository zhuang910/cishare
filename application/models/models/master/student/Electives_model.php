<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Electives_Model extends CI_Model {
	const T_COURSE = 'course';
	const T_TEACHER_COURSE='teacher_course';
	const T_KEIBIAO='scheduling';
	const T_SQUAD='squad';
	const T_TEACHER='teacher';
	const T_CLASSROOM='classroom';
	const T_E_S='electives_scheduling';
	const T_C_E='course_elective';
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
			$this->db->where('variable',0);
			$this->db->where('state',1);
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
			$this->db->where('variable',0);
			$this->db->where('state',1);
			return $this->db->from ( self::T_COURSE )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [set_course_time 设置时间]
	 */
	function set_course_time($data){
		if(!empty($data)){
			$arr['starttime']=strtotime($data['starttime']);
			$arr['endtime']=strtotime($data['endtime']);
			$json_ids=$data['ids'];
			unset($data['ids']);
			$ids= json_decode($json_ids);
			if(!empty($ids)){
				foreach ($ids as $k => $v) {
					$this->db->update(self::T_COURSE,$arr,'id='.$v);
				}
			}
		}
	}
	/**
	 * [get_teacher_time 获取该课程的老师可用时间]
	 * @param  [type] $cid [课程id]
	 * @return [type]      [可用时间数组]
	 */
	function get_teacher_time($cid){
		if(!empty($cid)){
			$this->db->where('courseid',$cid);
			$data=$this->db->get(self::T_TEACHER_COURSE)->result_array();
			if(!empty($data)){
				foreach ($data as $k => $v) {
					$is=$this->checke_teacher_time($v['teacherid'],$v['week'],$v['knob']);
					if($is==1){
						unset($data[$k]);
					}
				}
			}
			return $data;
		}
		return array();
	}
	/**
	 * [checke_teacher_time 检查老师的时间是否占用]
	 * @return [type] [description]
	 */
	function checke_teacher_time($t_id,$week,$knob){
		if(!empty($t_id)&&!empty($week)&&!empty($knob)){
			$this->db->where('teacherid',$t_id);
			$this->db->where('week',$week);
			$this->db->where('knob',$knob);
			$data=$this->db->get(self::T_KEIBIAO)->row_array();
			//班级的当前学期
			if(!empty($data)){
				$squad_now_term=$this->get_squad_now_term($data['squadid']);
				if($squad_now_term==$data['nowterm']){
					return 1;
				}else{
					return 2;
				}
			}else{
				return 2;
			}
			
		}
		return 1;
	}
	/**
	 * [squad_now_term 获取班级的当前学期]
	 * @param  [type] $sid [description]
	 * @return [type]      [description]
	 */
	function get_squad_now_term($sid){
		if(!empty($sid)){
			$this->db->select('nowterm');
			$this->db->where('id',$sid);
			$data=$this->db->get(self::T_SQUAD)->row_array();
			return $data['nowterm'];
		}
	}
	/**
	 * [get_teacher_info 获取老师信息数组]
	 * @param  [type] $tid [description]
	 * @return [type]      [description]
	 */
	function get_teacher_info_arr($tid){
		if(!empty($tid)){
			$tid_arr=explode('-', $tid);
			$teacher_arr=array();
			foreach ($tid_arr as $k => $v) {
				$teacher_arr[]=$this->get_teacher_info($v);
			}
			return $teacher_arr;
		}
		return array();
	}
	/**
	 * 获取一条老师信息
	 */
	function get_teacher_info($tid){
		if(!empty($tid)){
			$this->db->where('id',$tid);
			return $this->db->get(self::T_TEACHER)->row_array();
		}
		return array();
	}
	/**
	 * [get_classroom 获取可用的教室]
	 * @return [type] [description]
	 */
	function get_classroom($cid,$week,$knob){
		//获取该课程的容纳人数
		$c_size=$this->get_course_size($cid);
		$this->db->where('size >=',$c_size);
		$data=$this->db->get(self::T_CLASSROOM)->result_array();
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$is=$this->checke_class_room($v['id'],$week,$knob);
					if($is==1){
						unset($data[$k]);
					}
			}
			return $data;
		}
		return array();
	}
	//获取该课程的容纳人数
	function get_course_size($cid){
		if(!empty($cid)){
			$this->db->select('size');
			$this->db->where('id',$cid);
			$data=$this->db->get(self::T_COURSE)->row_array();
			return $data['size'];
		}
		return 0;
	}
	/**
	 * [checke_teacher_time 检查教室在当学期是否占用]
	 * @return [type] [description]
	 */
	function checke_class_room($rid,$week,$knob){
		if(!empty($rid)&&!empty($week)&&!empty($knob)){
			$this->db->where('classroomid',$rid);
			$this->db->where('week',$week);
			$this->db->where('knob',$knob);
			$data=$this->db->get(self::T_KEIBIAO)->row_array();
			//班级的当前学期
			if(!empty($data)){
				$squad_now_term=$this->get_squad_now_term($data['squadid']);
				if($squad_now_term==$data['nowterm']){
					return 1;
				}else{
					return 2;
				}
			}else{
				return 2;
			}
			
		}
		return 1;
	}
	/**
	 * [save 保存选修课的排课信息]
	 * @return [type] [description]
	 */
	function save($data){
		if(!empty($data)){
			$data['createtime']=time();
			$this->db->insert ( self::T_E_S, $data );
			return $this->db->insert_id ();
		}
	}
	/**
	 * [get_teacher_name 获取老师的名字]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_teacher_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_TEACHER)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * [get_teacher_name 获取教室的名字]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_classroom_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_CLASSROOM)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
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
			$this->db->select('electives_scheduling.id as id,teacher.name as tname,course.name as cname,classroom.name as rname,electives_scheduling.week,electives_scheduling.knob,electives_scheduling.courseid');
			$this->db->where('electives_scheduling.courseid',$cid);
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_E_S . '.teacherid' );
			$this->db->join ( self::T_CLASSROOM, self::T_CLASSROOM . '.id=' . self::T_E_S . '.classroomid' );
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_E_S . '.courseid' );
			return $this->db->get(self::T_E_S)->result_array();
		}
		return array();
	}
	/**
	 * [delete 删除排课信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function delete($id){
		if(!empty($id)){
			$this->db->delete ( self::T_E_S, 'id='.$id);
			return true;
		}
	}
	/**
	 * [get_electives_number 获取已经报这个课程的学生人数]
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	function get_electives_number($cid){
		if(!empty($cid)){
			$this->db->select('count(*) as num');
			$this->db->where('courseid',$cid);
			$data=$this->db->get(self::T_C_E)->row_array();
			return $data['num'];
		}
		return 0;
	}
	
}