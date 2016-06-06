<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Student_Model extends CI_Model {
	const T_ARTICLE = 'student_info';
	const T_CHECKING='checking';
	const T_STUDENT='student';//在学学生表
	const T_COURSE='course';
	const T_MAJOR='major';
	const T_SCORE='score';
	const T_SCHEDULING='scheduling';
	const T_TEACHER='teacher';
	const T_CLASSROOM='classroom';
	const T_USER_MESSAGE='user_message';
	const T_SQUAD='squad';
	const T_COURSE_E='course_elective';	
	const T_SOCIETY = 'society_info';		//社团表
    const T_APP='apply_info';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 修改基本信息
	 */
	function basic_update($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_ARTICLE, $data, $where );
		}
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_info_one($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		}
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_info_society_one($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_SOCIETY )->result_array ();
		}
	}
	
	/**
	 * 注册添加
	 * 数据
	 */
	function add($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_ARTICLE, $data );
			$userid = $this->db->insert_id ();
			$where = array (
					'id' => $userid 
			);
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		} else {
			return false;
		}
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition, $programaids = null) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			if ($programaids !== null) {
				$this->db->where ( 'columnid in(' . $programaids . ')' );
			}
			return $this->db->from ( self::T_ARTICLE )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->row ();
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
				$this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ARTICLE, $where );
			return true;
		}
		return false;
	}
		/**
	 *
	 *获取该学生的考勤
	 **/
	function get_attendance($userid,$nowterm,$identifying){
		$id=$this->get_student_id($userid);
		if(!empty($id)){
			$this->db->select('checking.date,checking.type,checking.knob,course.name,course.englishname,major.name as mname,major.englishname as menglishname');
			$this->db->where('studentid',$id);
			$this->db->where('nowterm',$nowterm);
			$this->db->join(self::T_COURSE,self::T_COURSE . '.id=' . self::T_CHECKING . '.courseid');
			$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_CHECKING . '.majorid');
			if($identifying=='part'){
				$this->db->limit(6);
			}
			$this->db->order_by('date desc, knob asc'); 
			$data= $this->db->get(self::T_CHECKING)->result_array();
			$attendance=array();
			$i=0;
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					if($k=='knob'){
						$v=$v;
					}
					if($k=='date'){
						$v=date('Y-m-d',$v);
					}	
				$attendance[$i][$k]=$v;
				}
				$i++;
			}
			return $attendance;
		}
	}

	/**
	 *	
	 *获取在学学生id
	 **/
	function get_student_id($userid){
		$this->db->where('userid',$userid);
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['id'];
	}
	/**
	 *	
	 *获取在学学生班级id
	 **/
	function get_student_squadid($userid){
		$this->db->where('userid',$userid);
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['squadid'];
	}
	/**
	 *
	 *获取该学生所在专业的学期数
	 **/
	function get_term($userid){
		$this->db->select('major.termnum');
		$this->db->where('userid',$userid);
		$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_STUDENT . '.majorid');
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['termnum'];
	}

	/**
	 *
	 *获取该学生的成绩
	 **/
	function get_achievement($userid,$nowterm,$scoretype,$identifying){
		$id=$this->get_student_id($userid);
		if(!empty($id)){
			$this->db->select('score.score,score.show_score,course.name,course.englishname,major.name as mname,major.englishname as menname,squad.name as sname,squad.englishname as senname');
			$this->db->where('studentid',$id);
			$this->db->where('term',$nowterm);
			$this->db->where('scoretype',$scoretype);
			$this->db->join(self::T_COURSE,self::T_COURSE . '.id=' . self::T_SCORE . '.courseid');
			$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_SCORE . '.majorid');
			$this->db->join(self::T_SQUAD,self::T_SQUAD . '.id=' . self::T_SCORE . '.squadid');
			if($identifying=='part'){
				$this->db->limit(6);
			}
			
			$data= $this->db->get(self::T_SCORE)->result_array();
			for ($i=0; $i <count($data) ; $i++) { 
				if($data[$i]['score']>=90){
					$data[$i]['type']='1';
				}elseif($data[$i]['score']>=80){
					$data[$i]['type']='2';
				}elseif($data[$i]['score']>=70){
					$data[$i]['type']='3';
				}elseif($data[$i]['score']>=60){
					$data[$i]['type']='4';
				}elseif($data[$i]['score']<60){
					$data[$i]['type']='5';
				}
				
			}
			return $data;
		}
	}

	/**
	 *
	 *计算学生所有科目的平均分
	 **/
	function avg_score($data){
		if(!empty($data)){
			$sum=0;
			foreach ($data as $k => $v) {
				$sum+=$v['score'];
			}
			$num=count($data);
			$avg=$sum/$num;
			return number_format($avg,1);
		}
		return 0;
	}
	/**
	 *
	 *获取该学生课表
	 **/
	function get_schedules($userid,$nowterm){
		$squadid=$this->get_student_squadid($userid);
		if(!empty($squadid)){
			$this->db->select('scheduling.id as id,teacher.name as tname,teacher.englishname as tenglishname,course.name as cname,course.englishname as cenglishname,classroom.name as rname,classroom.englishname as renglishname,scheduling.week,scheduling.knob,scheduling.squadid,scheduling.nowterm,scheduling.courseid,scheduling.majorid');
			$this->db->where('scheduling.nowterm',$nowterm);
			$this->db->where('scheduling.squadid',$squadid);
			$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
			$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
			$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
			return $this->db->get(self::T_SCHEDULING )->result_array();
		}
	}
	/**
	 *获取用户未读消息
	 *
	 *
	 **/
	function get_messagenum($id){
		if(!empty($id)){
			$this->db->select('count(*) as num');
			$this->db->where('studentid',$id);
			$this->db->where('readstate',2);
			$this->db->where('delete',2);
			$data=$this->db->get(self::T_USER_MESSAGE)->row_array();
			return $data['num'];
		}
		return 0;	
	}
	/**
	 *
	 *插入图片
	 **/
	function insert_pic($img,$id){
		$img=trim($img,'.');
		$data['photo']=$img;
		return $this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
	}
	function get_pic($id){
		$this->db->select('photo');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_ARTICLE)->row_array();
		return $data['photo'];
	}
	//获取专业名称
	function get_major_name($userid){
		if(!empty($userid)){
			$this->db->select('major.name as name,major.englishname as enname');
			$this->db->where('student.userid',$userid);
			$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_STUDENT . '.majorid');
			$data=$this->db->get(self::T_STUDENT)->row_array();
			if(!empty($data)){
				return $data;
			}else{
				return '';
			}
		}
		return '';
	}
		//获取班级名称
	function get_squad_name($userid){
		if(!empty($userid)){
			$this->db->select('squad.name as name,squad.englishname as enname');
			$this->db->where('student.userid',$userid);
			$this->db->join(self::T_SQUAD,self::T_SQUAD . '.id=' . self::T_STUDENT . '.squadid');
			$data=$this->db->get(self::T_STUDENT)->row_array();
			if(!empty($data)){
				return $data;
			}else{
				return '';
			}
		}
		return '';
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
	 * [get_ele_info 获取选修课的信息]
	 * @return [type] [description]
	 */
	function get_ele_info($userid,$term){
		if(!empty($userid)&&!empty($term)){
			$this->db->select('course_elective.*,course.name as cname,course.englishname as cenname,teacher.name as tname,teacher.englishname as tenname,classroom.name as rname,classroom.englishname as renname');
			$this->db->where('course_elective.userid',$userid);
			$this->db->where('course_elective.term',$term);
			$this->db->where('course_elective.state',1);
			$this->db->join ( self::T_COURSE, self::T_COURSE . '.id=' . self::T_COURSE_E . '.courseid' );
			$this->db->join ( self::T_TEACHER, self::T_TEACHER . '.id=' . self::T_COURSE_E . '.teacherid' );
			$this->db->join ( self::T_CLASSROOM, self::T_CLASSROOM . '.id=' . self::T_COURSE_E . '.classroomid' );
			return $this->db->get(self::T_COURSE_E)->result_array();
		}
	}

    /**
     *检查该学生的申请有内有通过
     */
    function check_student_apply($userid){
        if(!empty($userid)){
            $this->db->where('userid',$userid);
            $this->db->where('state >= 7');
            $data=$this->db->get(self::T_APP)->row_array();
            if(!empty($data)){
                return 1;
            }

        }
        return 0;
    }
    /**
     *检查该学生是否在学
     */
    function check_student_student($userid){
        if(!empty($userid)){
            $this->db->where('userid',$userid);
            $data=$this->db->get(self::T_STUDENT)->row_array();
            if(!empty($data)){
                return 1;
            }

        }
        return 0;
    }
}