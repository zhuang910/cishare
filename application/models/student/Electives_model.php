<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Electives_Model extends CI_Model {
	const T_STUDENT="student";
	const T_MAJOR_COURSE='major_course';
	const T_COURSE='course';
	const T_SQUAD='squad';
	const T_E_S='electives_scheduling';
	const T_TEACHER='teacher';
	const T_CLASS_ROOM='classroom';
	const T_COURSE_E='course_elective';
	const T_SCHEDULING='scheduling';
	const T_TEACHER_COURSE='teacher_course';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * [get_user_info 获取学生的信息]
	 * @return [type] [description]
	 */
	function get_user_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
	}
	/**
	 * [get_major_course 获取这个专业的选修课]
	 * @return [type] [description]
	 */
	function get_major_course($majorid){
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
			$data=$this->db->get(self::T_MAJOR_COURSE)->result_array();
			if(!empty($data)){
				$xuanxiuke=array();
				//判断是不是选修课
				foreach ($data as $k => $v) {
					$is=$this->check_course($v['courseid']);
					if($is===0){
						unset($data[$k]);
					}else{
						$xuanxiuke[]=$is;
					}
				}
				return $xuanxiuke;
			}
		}
		return array();
	}
	/**
	 * [get_now_term 获取当学生所在的学期]
	 * @return [type] [description]
	 */
	function get_now_term($sid){
		if(!empty($sid)){
			$this->db->select('nowterm');
			$this->db->where('id',$sid);
			$data=$this->db->get(self::T_SQUAD)->row_array();
			if(!empty($data['nowterm'])){
				return $data['nowterm'];
			}
		}
		return 0;
	}
	/**
	 * [check_course 判断是不是选修课]
	 * @return [type] [description]
	 */
	function check_course($cid){
		if(!empty($cid)){
			$this->db->where('id',$cid);
			$data=$this->db->get(self::T_COURSE)->row_array();
			if(!empty($data)){
				if($data['variable']==0){
					return $data;
				}
			}
		}
		return 0;
	}
	/**
	 * [check_course_time  检车选修课是否当时的时间段]
	 * @return [type] [description]
	 */
	function check_course_time($major_course){
		if(!empty($major_course)){	
			$course=array();
			foreach ($major_course as $k => $v) {
				if($v['starttime']<=time()&&$v['endtime']>=time()){
					$course[]=$v;
				}
			}
			return $course;
		}
		return array();
	}
	/**
	 * [check_course_term 判断选课是不是当前的学期]
	 * @return [type] [description]
	 */
	function check_course_term($course,$nowterm){
		$new_course=array();
		if(!empty($course)&&!empty($nowterm)){
			foreach ($course as $k => $v) {
				$is=0;
				$term_arr=json_decode($v['term_start'],true);
				if(!empty($term_arr)){
					foreach ($term_arr as $kk => $vv) {
						if($nowterm==$vv){
							$is=1;
						}
					}
				}
				if($is==1){
					$new_course[]=$v;
				}
			}
			return $new_course;
		}
		return array();
	}
	/**
	 * [get_s_course_info 获取选修课的排课情况]
	 * @return [type] [description]
	 */
	function get_s_course_info($course){
		if(!empty($course)){
			//打包成课程字符串做where条件用
			$cid_str=$this->coursearr_coursestr($course);
			$this->db->select('electives_scheduling.*,course.name as cname,course.englishname as cenname,teacher.name as tname,teacher.englishname as tenname,classroom.name as rname,classroom.englishname as renname');
			$this->db->where('electives_scheduling.courseid IN ('.$cid_str.')');
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_E_S . '.courseid' );
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_E_S . '.teacherid' );
			$this->db->join ( self::T_CLASS_ROOM, self::T_CLASS_ROOM . '.id=' . self::T_E_S . '.classroomid' );
			$this->db->order_by('electives_scheduling.courseid desc');
			$data=$this->db->get(self::T_E_S)->result_array();
			return $data;
		}
		return array();
	}
	/**
	 * [coursearr_coursestr 转换str]
	 * @return [type] [description]
	 */
	function coursearr_coursestr($course){
		$str='';
		if(!empty($course)){
			foreach ($course as $k => $v) {
				$str.=$v['id'].',';
			}
		}
		return trim($str,',');
	}
	/**
	 * [get_paike_info_one 获取一条排课的信息
	 * @return [type] [description]
	 */
	function get_paike_info_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_E_S)->row_array();
		}
		return array();
	}
	/**
	 * [insert_course_e 插入学生报名的选修课的表]
	 * @return [type] [description]
	 */
	function insert_course_e($userid,$squadid,$now_term,$arr){
		if(!empty($userid)&&!empty($arr)){
			$insert_arr['courseid']=$arr['courseid'];
			$insert_arr['teacherid']=$arr['teacherid'];
			$insert_arr['classroomid']=$arr['classroomid'];
			$insert_arr['week']=$arr['week'];
			$insert_arr['knob']=$arr['knob'];
			$insert_arr['userid']=$userid;
			$insert_arr['squadid']=$squadid;
			$insert_arr['term']=$now_term;
			$insert_arr['createtime']=time();
			$this->db->insert(self::T_COURSE_E,$insert_arr);
			return $this->db->insert_id();
		}
		return 0;
	}
	/**
	 * [check_student_bao 查询有没有报过该时间段的该老师的课程]
	 * @return [type] [description]
	 */
	function check_student_bao($userid,$squadid,$now_term,$arr){
		if(!empty($userid)&&!empty($arr)){
			$this->db->select('count(*) as num');
			$this->db->where('courseid',$arr['courseid']);
			$this->db->where('teacherid',$arr['teacherid']);
			$this->db->where('classroomid',$arr['classroomid']);
			$this->db->where('week',$arr['week']);
			$this->db->where('knob',$arr['knob']);
			$this->db->where('term',$now_term);
			$this->db->where('squadid',$squadid);
			$data=$this->db->get(self::T_COURSE_E)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [screen_course_info 筛选已经选过的课程]
	 * @return [type] [description]
	 */
	function screen_course_info($info,$userid){
		if(!empty($info)&&!empty($userid)){
			// var_dump($info);
			//查找该学生的报名的课程
			$user_course=$this->get_user_course_info($userid);
			// var_dump($user_course);
			if(!empty($user_course)){
				foreach ($info as $k => $v) {
					$is=0;
					foreach ($user_course as $kk => $vv) {
						if($v['courseid']==$vv['courseid']&&$v['teacherid']==$vv['teacherid']&&$v['classroomid']==$vv['classroomid']&&$v['week']==$v['week']&&$v['knob']==$vv['knob']){
							$is=1;
							
						}
					}
					// var_dump($is);
					if($is==1){
						unset($info[$k]);
					}
				}
			}
			// var_dump($info);exit;

			return $info;
		}
		return array();
	}
	/**
	 * [get_user_course_info 查找该学生的报名的课程]
	 * @return [type] [description]
	 */
	function get_user_course_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_COURSE_E)->result_array();
		}
	}
	/**
	 * [get_user_course_info 查找该学生的报名的课程]
	 * @return [type] [description]
	 */
	function get_user_course_de_info($userid,$state){
		if(!empty($userid)){
			$this->db->select('course_elective.*,course.name as cname,course.englishname as cenname,teacher.name as tname,teacher.englishname as tenname,classroom.name as rname,classroom.englishname as renname');
			$this->db->where('course_elective.userid',$userid);
			$this->db->where('course_elective.state',$state);
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_COURSE_E . '.courseid' );
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_COURSE_E . '.teacherid' );
			$this->db->join ( self::T_CLASS_ROOM, self::T_CLASS_ROOM . '.id=' . self::T_COURSE_E . '.classroomid' );
			$this->db->order_by('course_elective.courseid desc');
			return $this->db->get(self::T_COURSE_E)->result_array();
		}
	}
	/**
	 * [get_user_course_num 获取该学生有没有报这个课程]
	 * @return [type] [description]
	 */
	function get_user_course_num($id,$userid){
		if(!empty($id)&&!empty($userid)){
			$this->db->select('count(*) as num');
			$this->db->where('id',$id);
			$this->db->where('userid',$userid);
			$data=$this->db->get(self::T_COURSE_E)->row_array();
			return $data['num'];
		}
		return 0;
	}
	/**
	 *
	 *删除
	 **/
	function delete_user_course($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_COURSE_E, $where );
			return true;
		}
		return false;
	}
	/**
	 * [check_student_bao 查询有没有报过该时间段的该老师的课程]
	 * @return [type] [description]
	 */
	function check_student_youke($userid,$squadid,$now_term,$arr){

		if(!empty($userid)&&!empty($arr)){
			$this->db->select('count(*) as num');
			$this->db->where('week',$arr['week']);
			$this->db->where('knob',$arr['knob']);
			$this->db->where('nowterm',$now_term);
			$this->db->where('squadid',$squadid);
			$data=$this->db->get(self::T_SCHEDULING)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [check_student_bao 查询有没有报过该时间段的该老师的课程]
	 * @return [type] [description]
	 */
	function check_student_xuanxiuyouke($userid,$squadid,$now_term,$arr){
		if(!empty($userid)&&!empty($arr)){
			$this->db->select('count(*) as num');
			$this->db->where('week',$arr['week']);
			$this->db->where('knob',$arr['knob']);
			$this->db->where('term',$now_term);
			$this->db->where('squadid',$squadid);
			$data=$this->db->get(self::T_COURSE_E)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [get_user_course_info_one 获取一条学生的选择信息]
	 * @return [type] [description]
	 */
	function get_user_course_info_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_COURSE_E)->row_array();
		}
	}
	/**
	 * [get_tea_course 获取教学大纲]
	 * @return [type] [description]
	 */
	function get_tea_course($tid,$cid){
		if(!empty($tid)&&!empty($cid)){
			$this->db->select('outline');
			$this->db->where('teacherid',$tid);
			$this->db->where('courseid',$cid);
			$this->db->where('knob',99);
			$this->db->where('week',99);
			$data=$this->db->get(self::T_TEACHER_COURSE)->row_array();
			return $data['outline'];
		}
		return '';
	}
}