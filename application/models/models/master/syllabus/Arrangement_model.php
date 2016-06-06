	<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Arrangement_Model extends CI_Model {
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
		$this->db->where('course.variable',1);
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
	
	/*
	 *获取关联课程的老师
	 *
	 */
	function get_teacher_info($courseid,$week,$knob,$nowterm){
		$this->db->select('teacher.id as id,teacher.name as name');
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_TEACHER_COURSE.'.teacherid');
		$this->db->where('teacher_course.courseid',$courseid);
		$this->db->where('teacher_course.week',$week);
		$this->db->where('teacher_course.knob',$knob);
		$this->db->where('teacher_course.state',1);
		$tdata= $this->db->get(self::T_TEACHER_COURSE )->result_array();
		foreach ($tdata as $k => $v) {
			$num=$this->get_t_s($courseid,$v['id'],$week,$knob,$nowterm);
			if($num['num']>0){
				unset($tdata[$k]);
			}
		}
		//var_dump($tdata);exit;
		return $tdata;
	}
	/*
	 *获取教室信息
	 *
	 */
	function get_classroom_info($week,$knob,$num_day,$term){
		if(!empty($num_day)){
			$this->db->where('size >=',$num_day);
		}
		$roomdata= $this->db->get(self::T_CLASSROOM )->result_array();
		foreach ($roomdata as $k => $v) {
			$num=$this->get_r_s($week,$knob,$v['id'],$term);
			if($num['num']>0){
				unset($roomdata[$k]);
			}
		}
		return $roomdata;
	}
	/**
	 * 获取一个课程已经占的教室
	 *       	
	 */
	function get_r_s($week,$knob,$rid,$term){
		$this->db->select('count(*) as num');
		$this->db->where('classroomid',$rid);
		$this->db->where('week',$week);
		$this->db->where('knob',$knob);
		$this->db->where('nowterm',$term);
		return $this->db->get(self::T_SCHEDULING )->row_array();
	}
	/**
	 * 保存排课信息
	 *
	 *       	
	 * @param array $data        	
	 */
	function save($data = array(),$knob_arr=array()) {
		if(empty($knob_arr)){
			if($data['id']!=0){
	
				$this->db->update ( self::T_SCHEDULING, $data, 'id = ' . $data['id']);
				return $data;
			
			}else {
					
				$this->db->insert ( self::T_SCHEDULING, $data );
				return $this->db->insert_id ();
			}
		}else{
			$data['merge']=count($knob_arr)+1;
			$this->db->insert ( self::T_SCHEDULING, $data );
			unset($data['merge']);
			foreach ($knob_arr as $k => $v) {
				$data['knob']=$v;
				$this->db->insert ( self::T_SCHEDULING, $data );
				
			}
			return $data;
		}
		
			
			
		
	}
	/**
	 * 获取已排课的信息
	 *
	 *       	
	 */
	function get_scheduling_info($majorid,$nowterm,$squadid){
		
		
		$this->db->select('scheduling.id as id,teacher.name as tname,course.name as cname,classroom.name as rname,scheduling.week,scheduling.knob,scheduling.squadid,scheduling.nowterm,scheduling.courseid,scheduling.majorid,scheduling.merge');
		$this->db->where('scheduling.majorid',$majorid);
		$this->db->where('scheduling.nowterm',$nowterm);
		
		$this->db->where('scheduling.squadid',$squadid);
		
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
		return $this->db->get(self::T_SCHEDULING )->result_array();

	}
	/**
	 * 判断教室是否占用
	 *
	 *         	
	 * @param array $data        	
	 */
	function judge_classroom($data){
		if (! empty ( $data )) {
			//var_dump($data);die;
			$this->db->select('id,squadid,teacherid,courseid');
			$this->db->where('week',$data['week']);
			$this->db->where('knob',$data['knob']);

			$this->db->where('classroomid',$data['classroomid']);
			$this->db->where('state',1);
			return $this->db->get(self::T_SCHEDULING)->row_array();
		}
	}
	/**
	 * 判断老师已经上课
	 *
	 *         	
	 * @param array $data        	
	 */
	function judge_teacher($data){
		if (! empty ( $data )) {
			$this->db->select('id,squadid,teacherid,courseid');
			$this->db->where('week',$data['week']);
			$this->db->where('knob',$data['knob']);
			$this->db->where('teacherid',$data['teacherid']);
			$this->db->where('state',1);
			return $this->db->get(self::T_SCHEDULING)->row_array();
		}
	}

	/**
	 * 获取一条排课信息
	 *       	
	 */
	function get_scheduling_one($id){
		
		$this->db->select('scheduling.id as id,teacher.name as tname,course.name as cname,classroom.name as rname,scheduling.week,scheduling.knob,scheduling.squadid,scheduling.nowterm,scheduling.courseid,scheduling.majorid');
		$this->db->where('scheduling.id',$id);
		
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
		return $this->db->get(self::T_SCHEDULING )->row_array();

	}
	/**
	 * 计算每周最少上几节课
	 *       	
	 */
	function countcourse($mid,$cid){
		$mdays=$this->db->select('termdays as days')->where('id',$mid)->get(self::T_MAJOR)->row_array();
		$chour=$this->db->select('hour')->where('id',$cid)->get(self::T_COURSE)->row_array();
		return ceil($chour['hour']/($mdays['days']/7));
	}
	/**
	 * 计算老师课程时间差
	 *       	
	 */
	function count_t_c($mid,$cid){
		$this->db->select('count(*) as thour');
		$this->db->where('courseid',$cid);
		$this->db->where('state',1);
		$thour=$this->db->get(self::T_TEACHER_COURSE)->row_array();
		$this->db->select('squadnum');
		$this->db->where('id',$mid);
		$snum=$this->db->get(self::T_MAJOR)->row_array();
		return array('thour'=>$thour['thour'],'snum'=>$snum['squadnum']);
	}
	/**
	 * 获取该课程每周的每周的可用时间
	 *       	
	 */
	function get_c_h($cid,$term){
		$this->db->select('teacherid,week,knob');
		$this->db->where('courseid',$cid);
		$this->db->where('state',1);
		$cdata= $this->db->get(self::T_TEACHER_COURSE)->result_array();
		foreach ($cdata as $k => $v) {
			$num=$this->get_t_s($cid,$v['teacherid'],$v['week'],$v['knob'],$term);
			if($num['num']>0){
				unset($cdata[$k]);
			}
		}
		return $cdata;
	}
	/**
	 * 获取已排课的课程时间段
	 *       	
	 */
	function get_c_s($cid,$week,$knob){
		$this->db->select('count(*) as num');
		$this->db->where('courseid',$cid);
		$this->db->where('week',$week);
		$this->db->where('knob',$knob);
		
		return $this->db->get(self::T_SCHEDULING )->row_array();
	}
	/**
	 * 获取一个课程的可用老师
	 *       	
	 */
	function get_t_h($cid,$term){
		$this->db->select('teacher.id,teacher.name,teacher_course.week,teacher_course.knob');
		$this->db->where('teacher_course.courseid',$cid);
		$this->db->where('teacher_course.state',1);
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_TEACHER_COURSE.'.teacherid');
		$tdata= $this->db->get(self::T_TEACHER_COURSE)->result_array();
		
		foreach ($tdata as $k => $v) {
			$num=$this->get_t_s($cid,$v['id'],$v['week'],$v['knob'],$term);
			if($num['num']>0){
				unset($tdata[$k]);
			}
		}
		//var_dump($tdata);exit;
		return $tdata;
	}
	/**
	 * 获取一个课程已排课的老师
	 *       	
	 */
	function get_t_s($cid,$tid,$week,$knob,$term){
		$this->db->select('count(*) as num');
		$this->db->where('courseid',$cid);
		$this->db->where('teacherid',$tid);
		$this->db->where('week',$week);
		$this->db->where('knob',$knob);
		$this->db->where('nowterm',$term);
		return $this->db->get(self::T_SCHEDULING )->row_array();
	}
		/**
	 * 删除
	 *       	
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_SCHEDULING, $where);
			return true;
		}
		return false;
	}
	//获取班级容纳人数
	function get_squad_day($id){
		if(!empty($id)){
			$this->db->select('maxuser');
			$this->db->where('id',$id);
			$num=$this->db->get(self::T_SQUAD)->row_array();
			if(!empty($num)){
				return $num['maxuser'];
			}
		}
		return 0;
	}
	/**
	 * [get_day_hour_info 获取当天老师可用的节课]
	 * @return [type] [description]
	 */
	function get_day_hour_info($arr){
		if(!empty($arr)){
			//先获取老师的有没关联这个课程
			$teacher_info=$this->check_teacher_course($arr['teacherid'],$arr['courseid'],$arr['week'],$arr['knob']);
			if(!empty($teacher_info)){
				$info=array();
				foreach ($teacher_info as $k => $v) {
					if($v['knob']==$arr['knob']){
						unset($teacher_info[$k]);
						continue;
					}
					//查询有没有占用教室
					$is_room=$this->check_room($v['week'],$v['knob'],$arr['nowterm'],$arr['classroomid']);
					$is_squad=$this->check_squad($v['week'],$v['knob'],$arr['nowterm'],$arr['classroomid'],$arr['squadid']);
					if(($is_squad+$is_room)>0){
						unset($teacher_info[$k]);
						continue;
					}

					$info[]=$v;
					if(!empty($teacher_info[$k+1]['knob'])&&$teacher_info[$k+1]['knob']!=$v['knob']+1){
						break;
					}
				}
			}
			return $info;
		}
		return array();
	}
	/**
	 * [check_teacher_course 判断老师是否有这门课程]
	 * @param  [type] $teacherid [description]
	 * @param  [type] $courseid  [description]
	 * @return [type]            [description]
	 */
	function check_teacher_course($teacherid,$courseid,$week,$knob){
		if(!empty($teacherid)&&!empty($courseid)){
			$this->db->where('teacherid',$teacherid);
			$this->db->where('courseid',$courseid);
			$this->db->where('week',$week);
			$this->db->where('knob >=',$knob);
			$this->db->order_by('knob asc');
			return $this->db->get(self::T_TEACHER_COURSE)->result_array();
		}
		return array();
	}
	/**
	 * [check_room 判断当天的教室是否占用]
	 * @return [type] [description]
	 */
	function check_room($week,$knob,$nowterm,$classroomid){
		if(!empty($week)&&!empty($knob)&&!empty($nowterm)&&!empty($classroomid)){
			$this->db->select('count(*) as num');
			$this->db->where('week',$week);
			$this->db->where('knob',$knob);
			$this->db->where('nowterm',$nowterm);
			$this->db->where('classroomid',$classroomid);
			$data=$this->db->get(self::T_SCHEDULING)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [check_room 判断当天的班级是否排课]
	 * @return [type] [description]
	 */
	function check_squad($week,$knob,$nowterm,$classroomid,$squadid){
		if(!empty($week)&&!empty($knob)&&!empty($nowterm)&&!empty($classroomid)&&!empty($squadid)){
			$this->db->select('count(*) as num');
			$this->db->where('week',$week);
			$this->db->where('knob',$knob);
			$this->db->where('nowterm',$nowterm);
			$this->db->where('squadid',$squadid);
			$data=$this->db->get(self::T_SCHEDULING)->row_array();
			return $data['num'];
		}
		return 1;
	}
	//获取一条排课
	function get_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_SCHEDULING)->row_array();
			return $data;
		}
	}
	/**
	 * [delete_sub 删除已经合并的]
	 * @return [type] [description]
	 */
	function delete_sub($arr){
		if(!empty($arr)){
			$this->db->where('week',$arr['week']);
			$this->db->where('knob',$arr['knobs']);
			$this->db->where('nowterm',$arr['nowterm']);
			$this->db->where('squadid',$arr['squadid']);
			$this->db->where('majorid',$arr['majorid']);
			$this->db->where('teacherid',$arr['teacherid']);
			$this->db->where('courseid',$arr['courseid']);
			$this->db->delete ( self::T_SCHEDULING);
		}
	}
}