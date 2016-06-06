<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Export_Model extends CI_Model {
	const T_MAJOR= 'major';
	const T_TEACHER='teacher';
	const T_COURSE='course';
	const T_SQUAD= 'squad';
	const T_MAJOR_COURSE='major_course';
	const T_TEACHER_COURSE='teacher_course';
	const T_CLASSROOM='classroom';
	const T_SCHEDULING='scheduling';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取专业信息
	 */
	function get_majorinfo(){
		return $this->db->get(self::T_MAJOR)->result();
	}
	/**
	 * 获取专业的所有学期
	 */
	function get_major_nowterm($id){
		$this->db->where('id=',$id);
		
		$nowterm=$this->db->get(self::T_MAJOR)->result_array();
		 $arr=array();
		 for($i=1;$i<=$nowterm[0]['termnum'];$i++){
		 	$arr[]=$i;
		 }
		return $arr;
		
	}
	/**
	 * 获取专业该学期的班级
	 */
	function get_squadinfo($mid,$term){
		$this->db->where('majorid',$mid);
		$this->db->where('nowterm',$term);
		return $this->db->get(self::T_SQUAD)->result_array();
	}
	/**
	 * 获取专业的课程
	 */
	function get_course($mid){
		$this->db->select('course.id,course.name as cname');
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_MAJOR_COURSE.'.majorid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_MAJOR_COURSE.'.courseid');
		$this->db->where('major_course.majorid',$mid);

		return $this->db->get(self::T_MAJOR_COURSE )->result_array();
	}
	function get_squad_num($majorid,$nowterm){
		$this->db->select ( 'count("*") as squadnum');
		$this->db->where('majorid',$majorid);
		$this->db->where('nowterm',$nowterm);
		 $data=$this->db->get(self::T_SQUAD)->row_array();
		 return $data['squadnum'];
	}
	
	/**
	 * 获取专业信息
	 */
	function get_teacher(){
		return $this->db->get(self::T_TEACHER)->result_array();
	}
	
	/**
	 * 获取已排课的信息1
	 *
	 *       	
	 */
	function get_scheduling_info($majorid,$nowterm,$squadid){
		
		
		$this->db->select('scheduling.id as id,teacher.name as tname,course.name as cname,classroom.name as rname,scheduling.week,scheduling.knob,scheduling.squadid,scheduling.nowterm,scheduling.courseid,scheduling.majorid');
		$this->db->where('scheduling.majorid',$majorid);
		$this->db->where('scheduling.nowterm',$nowterm);
		$this->db->where('scheduling.squadid',$squadid);
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
		return $this->db->get(self::T_SCHEDULING )->result_array();

	}
	


	/**
	 * 获取一条排课信息
	 *       	
	 */
	function get_scheduling_one($id){
		
		$this->db->select('scheduling.id as id,teacher.name as tname,course.name as cname,classroom.name as rname,scheduling.week,scheduling.knob,scheduling.squadid,scheduling.nowterm,scheduling.courseid,scheduling.majorid');
		$this->db->where('scheduling.id',$id);
		$this->db->where('scheduling.state',1);
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
		return $this->db->get(self::T_SCHEDULING )->row_array();

	}

	/**
	 * 获取一条排课信息
	 *       	
	 */
	function get_scheduling_t($tid,$term){
		$this->db->select('scheduling.id as id,major.name as mname,squad.name as sname,course.name as cname,classroom.name as rname,scheduling.week,scheduling.knob,scheduling.squadid,scheduling.nowterm,scheduling.courseid,scheduling.majorid');
		$this->db->where('scheduling.teacherid',$tid);
		if(!empty($term)){
			$this->db->where('scheduling.nowterm',$term);
		}
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_SCHEDULING.'.majorid');
		$this->db->join(self::T_SQUAD ,self::T_SQUAD.'.id='.self::T_SCHEDULING.'.squadid');
		return $this->db->get(self::T_SCHEDULING )->result_array();
	}




	function get_scheduling_term($majorid,$nowterm){
		$this->db->select('squadid');
		$this->db->where('majorid',$majorid);
		$this->db->where('nowterm',$nowterm);
		$this->db->group_by('squadid');
		$sid= $this->db->get(self::T_SCHEDULING )->result_array();

		$sdata=array();
		foreach ($sid as $k => $v) {
			$sdata[$v['squadid']]=$this->get_scheduling_info($majorid,$nowterm,$v['squadid']);
		}
		return $sdata;
	} 
	/**
	 * 获取班级信息
	 * */
	function get_sinfo($id){
		$this->db->where('id',$id);
		return $this->db->get(self::T_SQUAD)->row_array();
	}
	/**
	 * 获取老师排课的学期
	 * */
	function get_s_term($tid){
		$this->db->select('nowterm');
		$this->db->where('teacherid',$tid);
		$this->db->group_by('nowterm');
		return $this->db->get(self::T_SCHEDULING )->result_array();
	}
	//获取专业名称
	function get_major_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_MAJOR)->row_array();
			return $data['name'];
		}
		return false;
	}
	//获取班级名称
	function get_squad_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_SQUAD)->row_array();
			return $data['name'];
		}
		return false;
	}
}