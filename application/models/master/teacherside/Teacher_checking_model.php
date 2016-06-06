<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Teacher_checking_Model extends CI_Model {
	
	const T_MAJOR= 'major';
	const T_TEACHER='teacher';
	const T_COURSE='course';
	const T_SQUAD= 'squad';
	const T_MAJOR_COURSE='major_course';
	const T_TEACHER_COURSE='teacher_course';
	const T_SCHEDULING='scheduling';
	const T_STUDENT='student';
	const T_SCORE='score';
	const T_CHECKING = 'checking';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 *
	 *获取老师id
	 **/
	function get_teacherids($userid){
		$this->db->select('id');
		$this->db->where('userid',$userid);
		$data=$this->db->get(self::T_TEACHER)->row_array();
		return $data['id'];
	}
	
	/**
	 * 获取专业信息
	 */
	function get_majorinfo($tid){
		$this->db->select('major.id,major.name');
		$this->db->where('teacherid',$tid);
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_SCHEDULING.'.majorid');
		$this->db->group_by('scheduling.majorid');
		return $this->db->get(self::T_SCHEDULING)->result();
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
	function get_squadinfo($mid,$term,$tid){
		$this->db->select('squad.id,squad.name');
		$this->db->where('scheduling.majorid',$mid);
		$this->db->where('scheduling.nowterm',$term);
		$this->db->where('teacherid',$tid);
		$this->db->join(self::T_SQUAD ,self::T_SQUAD.'.id='.self::T_SCHEDULING.'.squadid');
		$this->db->group_by('scheduling.squadid');
		return $this->db->get(self::T_SCHEDULING)->result_array();;
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
	 * 
	 * 获取学生
	 * */
	function get_studentinfo($sid){
		$this->db->select('id,studentid,name,email,enname');
		$this->db->where('squadid',$sid);
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	 *获取该学生的所有考勤情况
	 **/
	function get_student_checkinginfo($studentid){
		$this->db->select('checking.*,course.name as cname,teacher.name as tname,major.name as mname,squad.name as sname');
		$this->db->where('checking.studentid',$studentid);
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_CHECKING.'.courseid');
		$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_CHECKING.'.teacherid');
		$this->db->join(self::T_SQUAD ,self::T_SQUAD.'.id='.self::T_CHECKING.'.squadid');
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_CHECKING.'.majorid');
		$data= $this->db->get(self::T_CHECKING)->result_array();
		$attendance=array();
			$i=0;
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					if($k=='type'){
						switch ($v) {
							case 0:
								$v='正点';
								break;
							case 1:
								$v='缺勤';
								break;
							case 2:
								$v='早退';
								break;
							case 3:
								$v='迟到';
								break;
							case 4:
								$v='请假';
								break;
						
						}
					}
					if($k=='knob'){
						$v=($v*2-1).','.($v*2).'节课';
					}
				$attendance[$i][$k]=$v;
				}
				$i++;
			}
			return $attendance;
	}
	/**
	 * 获取上课情况
	 */
	function get_out($majorid,$squadid,$teacherid){
		$this->db->select('scheduling.id,scheduling.majorid,scheduling.teacherid,scheduling.courseid,scheduling.squadid,scheduling.classroomid,scheduling.week,scheduling.knob,scheduling.nowterm,scheduling.state,course.name as cname');
		$this->db->where('scheduling.majorid',$majorid);
		$this->db->where('scheduling.squadid',$squadid);
		$this->db->where('scheduling.teacherid',$teacherid);
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		return $this->db->get(self::T_SCHEDULING )->result_array();
	}
	/**
	 * 获取获取开课时间
	 */
	function get_classtime($majorid,$squadid){
		$this->db->select('classtime');
		$this->db->where('majorid',$majorid);
		$this->db->where('id',$squadid);
		return $this->db->get(self::T_SQUAD )->row_array();
	}


	function save($id = null, $data = array()) {
		if($data['type']==0){
			$this->db->where('studentid',$data['studentid']);
			$this->db->where('week',$data['week']);
			$this->db->where('knob',$data['knob']);
			$this->db->where('date',$data['date']);
			$this->db->delete ( self::T_CHECKING);
			return 1;
		}
		$ck=$this->insert_update($data['week'],$data['knob'],$data['date'],$data['studentid']);
		
			if ($ck) {

				$this->db->update ( self::T_CHECKING, $data, 'id = ' . $ck['id'] );
				return 1;
			} else {
				$this->db->insert ( self::T_CHECKING, $data );
				return $this->db->insert_id ();
			}
		
	}
	/**
	 * 获取该学生当前周的考勤情况
	 * 
	 */
	function get_ck($studentid,$time){
		$k=date('w',$time);
		
		$timeymd=date('Y-m-d',$time);
		$time=strtotime($timeymd);
		if($k==0){
			$k=6;
		}else{
			$k=$k-1;
		}
		$stime=$time-$k*3600*24;
		$etime=$stime+7*24*3600;
		$this->db->where('studentid',$studentid);
		$this->db->where('date >=',$stime);
		$this->db->where('date <=',$etime);
		return $this->db->get(self::T_CHECKING )->result_array();
	}
	function get_next_ck($studentid,$otime){
		$this->db->where('studentid',$studentid);
		$this->db->where('date >=',$otime);
		$this->db->where('date <=',$otime+7*24*3600);
		return $this->db->get(self::T_CHECKING )->result_array();
	}
	function get_up_ck($studentid,$stime){
		$this->db->where('studentid',$studentid);
		$this->db->where('date <=',$stime);
		$this->db->where('date >=',$stime-7*24*3600);
		return $this->db->get(self::T_CHECKING )->result_array();
	}
	/**
	 * 判断是否更新
	 * 
	 */
	function insert_update($w,$k,$d,$s){
		$this->db->select('id');
		$this->db->where('studentid',$s);
		$this->db->where('week',$w);
		$this->db->where('knob',$k);
		$this->db->where('date',$d);
		return $this->db->get(self::T_CHECKING )->row_array();
	}
	/**
	 * 计算出勤率
	 * 
	 */
	function chuqin($sid,$m,$h){
		$this->db->select('count(*) as num');
		$this->db->where('studentid',$sid);
		$num =$this->db->get(self::T_CHECKING )->row_array();
		if($num['num']==0){
			return '100%';
		}

		$mnum=$this->get_m_num($m);
		$s=$num['num']/$mnum['mnum'];
		$bai=(1-$s)*100;

		$bai=sprintf('%.2f', $bai).'%';
		return $bai;
		
	}
	/**
	 * 获取专业的总天数
	 * 
	 */
	function get_m_num($m){
		$this->db->select('termdays as mnum');
		$this->db->where('id',$m);
		return $this->db->get(self::T_MAJOR )->row_array();
	}
	/**
	 * 计算考勤率
	 * 
	 */
	function kaoqin($s){
		$this->db->select('date,knob');
		$this->db->where('studentid',$s);
		$this->db->order_by("id","desc"); 
		$kaoqin =$this->db->get(self::T_CHECKING )->row_array();
		if($kaoqin==null){
			return '全勤';
		}
		$knob=(($kaoqin['knob']*2)-1).','.($kaoqin['knob']*2).'节课';
		return date('Y-m-d',$kaoqin['date']).','.$knob;
		
	}
	/**
	 * 获取专业名称
	 *       	
	 */
	function get_mname($majorid){
		$this->db->select('id,name');
		$this->db->where('id',$majorid);
		return $this->db->get(self::T_MAJOR)->row_array();
	}
	/**
	 * 获取专业学期
	 *       	
	 */
	function get_m_term($majorid){
		$this->db->select('termnum');
		$this->db->where('id',$majorid);
		return $this->db->get(self::T_MAJOR)->row_array();
	}
	/**
	 * 获取班级信息
	 *       	
	 */
	function get_squad_info($squadid){
		$this->db->select('id,name,nowterm');
		$this->db->where('id',$squadid);
		return $this->db->get(self::T_SQUAD)->row_array();
	}
	/**
	 * 获取学生信息
	 *       	
	 */
	function get_sname($studentid){
		$this->db->select('id,name');
		$this->db->where('id',$studentid);
		return $this->db->get(self::T_STUDENT)->row_array();
	}
	/**
	 *
	 *获取专业字段
	 **/
	function get_checking_fields(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_checking"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		return $data;
	}
	/**
	 *
	 *获取该条件的学生
	 **/
	function get_where_student($key,$value,$arr){
		$this->db->select('student.id,student.name,student.studentid,student.email,student.majorid,student.squadid,student.passport,major.name as mname,squad.name as sname,student.isclass');
		$this->db->like('student.'.$key,$value);
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_STUDENT.'.majorid');
		$this->db->join(self::T_SQUAD ,self::T_SQUAD.'.id='.self::T_STUDENT.'.squadid');
		$this->db->where_in('squadid',$arr);
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	 *
	 *获取老师所带所有的班级
	 **/
	function get_squadid_all($tid){
		$this->db->select('squadid');
		$this->db->where('teacherid',$tid);
		$this->db->group_by('scheduling.squadid');
		$data= $this->db->get(self::T_SCHEDULING)->result_array();
		$arr=array();
		foreach ($data as $key => $value) {
			$arr[]=$value['squadid'];
		}
		return $arr;
	}
	/**
	 *
	 *获取当前天的安排的课程
	 **/
	function get_nowday_course($squadid,$week){
		$this->db->select('scheduling.*,course.name');
		$this->db->where('scheduling.squadid',$squadid);
		$this->db->where('scheduling.week',$week);
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_SCHEDULING.'.courseid');
		return $this->db->get(self::T_SCHEDULING)->result_array();
	}
	/**
	 *获取改班级的当前学期
	 **/
	function get_squad_term($id){
		$this->db->where('id',$id);
		$data= $this->db->get(self::T_SQUAD)->row_array();
		return $data['nowterm'];
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into sdyinc_checking ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 * 获取专业id
	 */
	function get_majorid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_MAJOR)->row_array();
		return $data['id'];
	}
	/**
	 * 
	 * 获取考勤字段
	 * */
	function get_check_fields(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_checking"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		unset($data['courseid']);
		unset($data['teacherid']);
		return $data;
	}
	/**
	 * 获取班级id
	 */
	function get_squadid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_SQUAD)->row_array();
		return $data['id'];
	}
	/**
	 * 获取学生ID
	 */
	function get_studentid($name,$email){
		$this->db->select('id');
		$this->db->where('name',$name);
		$this->db->where('email',$email);
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['id'];
	}
	/**
	 * 获取课程id
	 */
	function get_courseid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_COURSE)->row_array();
		return $data['id'];
	}
		/**
	 * 
	 * 获取老师姓名
	 * */
	function get_teacherid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_TEACHER)->row_array();
		return $data['id'];
	}
	/**
	 * 
	 * 获取考勤类别编号
	 * */
	function get_checktype($str){
		$str=trim($str);
		switch ($str)
			{
			case '正点':
			  return 0;
			  break;  
			case '缺勤':
			  return 1;
			  break;
			case '早退':
			  return 2;
			  break;
			case '迟到':
			  return 3;
			  break;
			case '请假':
			  return 4;
			  break;
		
			}
	}
	/**
	 * 
	 * 获取week
	 * */
	function get_week($str){
			switch ($str)
			{
			case '星期一':
			  return 1;
			  break;  
			case '星期二':
			  return 2;
			  break;
			case '星期三':
			  return 3;
			  break;
			case '星期四':
			  return 4;
			  break; 
			case '星期五':
			  return 5;
			  break;
			case '星期六':
			  return 6;
			case '星期日':
		      return 7;
			  break;
		
			}
	}
	/**
	 * 
	 * 获取配置hour
	 * */
	function get_hour($hour){
		$str='';
		foreach($hour['hour'] as $k =>$v){
			$s=$v*2-1;
			$e=$v*2;
			$str.= $s.'-'.$e.'节课,';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 * 
	 * 获取knob
	 * */
	function get_knob($str){
			switch ($str)
			{
			case '1-2节课':
			  return 1;
			  break;  
			case '3-4节课':
			  return 2;
			  break;
			case '5-6节课':
			  return 3;
			  break;
			case '7-8节课':
			  return 4;
			  break;
			case '9-10节课':
			  return 5;
		
		
			}
	}
		/**
	 * 插入考勤字段
	 */
	function insert_checking_fields($insert,$value){
		$sql='insert into sdyinc_checking ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 *
	 *获取考勤的老师id课程id
	 *@week
	 *@knob
	 **/
	function get_teacherid_courseid($squadid,$week,$knob){
		// $week=$this->get_week($week);
		// $knob=$this->get_knob($knob);
		$this->db->where('squadid',$squadid);
		$this->db->where('week',$week);
		$this->db->where('knob',$knob);
		
		$data=$this->db->get(self::T_SCHEDULING)->row_array();
		return $data['teacherid'].','.$data['courseid'];
	}
	/**
	 *
	 *检查是否有重复记录
	 *@$insert:字段
	 *@$value:字段值
	 **/
	function check_checking($insert,$value){
		$insert=explode(',',$insert);
		$value=explode(',',$value);
		
		$this->db->select('count(*) as count');
		$this->db->where($insert[2],trim($value[2],'""'));
		$this->db->where($insert[0],trim($value[0],'""'));
		$this->db->where($insert[3],trim($value[3],'""'));
		$this->db->where($insert[5],trim($value[5],'""'));
		$this->db->where($insert[9],trim($value[9],'""'));
		$data=$this->db->get(self::T_CHECKING)->row_array();
		return $data['count'];
	}
}