<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Checking_Model extends CI_Model {
	
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
	const T_CLASSROOM='classroom';
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
	 * 获取专业信息
	 */
	function get_major_name($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data= $this->db->get(self::T_MAJOR)->row_array();
		return $data['name'];
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
	//获取专业一共多少周
	function get_hebdomad_num($id){
		$this->db->where('id=',$id);
		
		$data=$this->db->get(self::T_MAJOR)->row_array();

		$num=floor($data['termdays']/7);
		 $arr=array();
		 for($i=1;$i<=$num;$i++){
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
	 * 获取专业该学期的教室
	 */
	function get_class_room_info($mid,$term){
		$this->db->select('scheduling.classroomid,classroom.name');
		$this->db->where('scheduling.majorid',$mid);
		$this->db->where('scheduling.nowterm',$term);
		$this->db->join(self::T_CLASSROOM ,self::T_SCHEDULING.'.classroomid='.self::T_CLASSROOM.'.id');
		$this->db->group_by('classroomid');
		return $this->db->get(self::T_SCHEDULING)->result_array();
	}
	/**
	 * 获取专业该学期的教室
	 */
	function get_class_room_teacher_info($mid,$term,$croomid){
		$this->db->select('scheduling.teacherid,teacher.name');
		$this->db->where('scheduling.majorid',$mid);
		$this->db->where('scheduling.nowterm',$term);
		$this->db->where('scheduling.classroomid',$croomid);
		$this->db->join(self::T_TEACHER ,self::T_SCHEDULING.'.teacherid='.self::T_TEACHER.'.id');
		$this->db->group_by('teacherid');
		return $this->db->get(self::T_SCHEDULING)->result_array();
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
								$v='旷课';
								break;
							case 2:
								$v='请假';
								break;
							case 3:
								$v='迟到';
								break;
						}
					}
					if($k=='knob'){
						$v=$v.'节课';
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
	function get_out($majorid,$squadid){
		$this->db->select('scheduling.id,scheduling.majorid,scheduling.teacherid,scheduling.courseid,scheduling.squadid,scheduling.classroomid,scheduling.week,scheduling.knob,scheduling.nowterm,scheduling.state,course.name as cname');
		$this->db->where('scheduling.majorid',$majorid);
		$this->db->where('scheduling.squadid',$squadid);
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
		$knob=$kaoqin['knob'].'节课';
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
		return array(
				 'studentid' =>  '学生名',
				  'majorid' =>  '专业名',
				  'teacherid' =>  '老师',
				  'courseid' =>  '课程',
				  'squadid' =>  '班级',
				  'adminid' =>  '管理员' ,
				  'nowterm' =>  '当前学期' ,
				  'date' =>  '日期',
				  'type' =>  '类别0 正点 1缺勤 2早退 3迟到4请假' ,
				  'week' =>  '当前星期' ,
				  'knob' =>  '当前节课' ,
				  'email' =>  '学生邮箱' ,
				  'remark' =>  '备注',
			);
	}
	/**
	 *
	 *获取该条件的学生
	 **/
	function get_where_student($key,$value){
		$this->db->select('student.id,student.name,student.studentid,student.email,student.majorid,student.squadid,student.passport,major.name as mname,squad.name as sname,student.isclass');
		$this->db->like('student.'.$key,$value);
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_STUDENT.'.majorid');
		$this->db->join(self::T_SQUAD ,self::T_SQUAD.'.id='.self::T_STUDENT.'.squadid');
		return $this->db->get(self::T_STUDENT)->result_array();
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
		$sql='insert into zust_checking ('.$insert.') values('.$value.')';
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
		return array(
			   'studentid' =>  '学生名',
				  'majorid' =>  '专业名',
				  'squadid' =>  '班级',
				  'adminid' =>  '管理员' ,
				  'nowterm' =>  '当前学期' ,
				  'date' =>  '日期',
				  'type' =>  '类别0 正点 1缺勤 2早退 3迟到4请假' ,
				  'knob' =>  '当前节课' ,
				  'email' =>  '学生邮箱' ,
				  'remark' =>  '备注',
			);
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
			case '旷课':
			  return 1;
			  break;
			case '请假':
			  return 2;
			  break;
			case '迟到':
			  return 3;
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
			case '1节课':
			  return 1;
			  break;  
			case '2节课':
			  return 2;
			  break;
			case '3节课':
			  return 3;
			  break;
			case '4节课':
			  return 4;
			  break;
			case '5节课':
			  return 5;
			  break;
			case '6节课':
			  return 6;
			  break;
			 case '7节课':
			  return 7;
			  break;
			 case '8节课':
			  return 8;
			  break;
			 case '9节课':
			  return 9;
			  break;
			 case '10节课':
			  return 10;
			  break;
			}
	}
		/**
	 * 插入考勤字段
	 */
	function insert_checking_fields($insert,$value){
		$sql='insert into zust_checking ('.$insert.') values('.$value.')';
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
	/**
	 *
	 *获取排课情况
	 */
	function get_scheduling($data){
		if(!empty($data)){
			$this->db->where('majorid',$data['majorid']);
			$this->db->where('squadid',$data['squadid']);
			$this->db->where('nowterm',$data['nowterm']);
			$this->db->where('courseid',$data['courseid']);
			return $this->db->get(self::T_SCHEDULING)->result_array();
		}
		return flase;
	}
	/**
	 *
	 *精确搜索学生
	 **/
	function get_student_one($data){
		$this->db->select('id,studentid,name,email');
		if(!empty($data['squadid'])){
			$this->db->where('squadid',$data['squadid']);
		}
		$this->db->like($data['key'],$data['value']);
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	　*
	  *获取该老师
	  **/
	function get_teacher_info ($squadid)	{
		if(!empty($squadid)){
			$this->db->select('scheduling.teacherid,teacher.name');
			$this->db->where('scheduling.squadid',$squadid);
			$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
			$this->db->group_by('scheduling.teacherid');
			return $this->db->get(self::T_SCHEDULING)->result_array();
		}
		return false;
	}
	/**
	　*
	  *获取该老师
	  **/
	function get_classroom_info ($teacherid){
		if(!empty($teacherid)){
			$this->db->select('scheduling.classroomid,classroom.name');
			$this->db->where('scheduling.teacherid',$teacherid);
			$this->db->join(self::T_CLASSROOM ,self::T_CLASSROOM.'.id='.self::T_SCHEDULING.'.classroomid');
			$this->db->group_by('scheduling.classroomid');
			return $this->db->get(self::T_SCHEDULING)->result_array();
		}
		return false;
	}
	/**
	 *
	 *获取所有的学生考勤情况
	 **/
	function get_checking($time){
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
		$this->db->where('date >=',$stime);
		$this->db->where('date <=',$etime);
		$data= $this->db->get(self::T_CHECKING)->result_array();
		$arr=array();
		foreach ($data as $k => $v) {
			foreach ($v as $kk => $vv) {
				if($kk=='type'){
					switch ($vv) {
							case 0:
								$vv='正常';
								break;
							case 1:
								$vv='旷课';
								break;
							case 2:
								$vv='请假';
								break;
							case 3:
								$vv='迟到';
								break;
							}
					

						}
						$arr[$k][$kk]=$vv;
				}
			}
		return $arr;
	}
	/**
	 *
	 *保存考勤第二版
	 **/
	function save_checking($id,$data){

		if(empty($id)){
			$this->db->insert ( self::T_CHECKING, $data );
				return $this->db->insert_id ();
		}else{
			$this->db->update ( self::T_CHECKING, $data, 'id = ' . $id );
			return 1;
		}
		return false;
	}
	//打印报表获取班级
	function get_report_squadinfo($data){
		foreach ($data as $key => $value) {
			if($key=='num'){
				continue;
			}
			$this->db->where($key,$value);
		}
		$this->db->group_by('knob');
		$this->db->order_by('knob','asc');
		return $this->db->get(self::T_SCHEDULING)->result_array();
	}
	//获取所有的学生
	function get_student_all($squadid,$num){
		$student_arr=array();
		foreach ($squadid as $k => $v) {
			$str='';
			$str.=$this->get_knob_str($v['knob']);
			$str.='grf'.$this->get_course_str($v['courseid']);
			$str.='grf'.$this->get_squad_str($v['squadid']);
			$student_arr[$str]=$this->get_student($v['squadid'],$v['knob'],$num);
		}
		return $student_arr;
	}
	//获取该班级的学生
	function get_student($squadid,$knob,$num){
		$this->db->select('id,enname,name,passport,nationality,mobile');
		$this->db->where('squadid',$squadid);
		$data= $this->db->get(self::T_STUDENT)->result_array();
		foreach ($data as $k => $v) {
			if(!empty($v['nationality'])){
				$data[$k]['nationality']=$this->get_nationality_name($v['nationality']);

			}
				$remark='';
				for ($i=1; $i < 6; $i++) { 
					$info=$this->get_student_checking($v['id'],$squadid,$knob,$i,$num);
					if(!empty($info)){
						$data[$k]['week'.$i]=$info['type'];
						$remark.=','.$info['remark'];
					}else{
						$data[$k]['week'.$i]=$info;
					}
					
				}
				$data[$k]['remark']=trim($remark,',');
		}
		return $data;
	}
	//获取总行数
	function get_count_all($student_all){
		$num=0;
		foreach ($student_all as $key => $value) {
			$num+=count($value);
		}
		return $num;
	}
	//获取打印的节课
	function get_knob_str($knob){
		switch ($knob)
			{
			case 1:
			  return '1-2节课<br />8:00-9:50';
			  break;  
			case 2:
			  return '3-4节课<br />10:00-11:50';
			  break;
			case 3:
			  return '5-6节课<br />13:00-14:50';
			  break;
			case 4:
			  return '7-8节课<br />15:00-16:50';
			  break;
			case 5:
			  return '9-10节课<br />17:00-18:50';
		
		
			}
	}
	//获取打印课程
	function get_course_str($courseid){
		$this->db->select('name');
		$this->db->where('id',$courseid);
		$data=$this->db->get(self::T_COURSE)->row_array();
		return $data['name'];
	}
	//获取打印课程
	function get_squad_str($squadid){
		$this->db->select('name');
		$this->db->where('id',$squadid);
		$data=$this->db->get(self::T_SQUAD)->row_array();
		return $data['name'];
	}
	//获取打印教室名字
	function get_classroom_name($data){
		$this->db->select('name');
		$this->db->where('id',$data['classroomid']);
		$data=$this->db->get(self::T_CLASSROOM)->row_array();
		return $data['name'];
	}
	//获取打印老师名字
	function get_teacher_name($data){
		$this->db->select('name');
		$this->db->where('id',$data['teacherid']);
		$data=$this->db->get(self::T_TEACHER)->row_array();
		return $data['name'];
	}
	//获取打印周一到周五的考勤信息
	function get_student_checking($studentid,$squadid,$knob,$week,$num){
		//获取班级的开班日期
		$squadtime=$this->get_squad_time($squadid);
		$date=$squadtime+($num-1)*7*24*3600+($week-1)*24*3600;
	
		$this->db->select('type,remark');
		$this->db->where('studentid',$studentid);
		$this->db->where('squadid',$squadid);
		$this->db->where('knob',$knob);
		$this->db->where('week',$week);
		$this->db->where('date',$date);
		$data=$this->db->get(self::T_CHECKING)->row_array();
		if(!empty($data)){
			$data['type']=$this->get_checking_type_name($data['type']);
			return $data;
		}
		return '';

	}
	//获取开班时间
	function get_squad_time($id){
		$this->db->select('classtime');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_SQUAD)->row_array();
		return $data['classtime'];
	}
	//获取年份
	function get_date($data,$num){
		$arr=array();
		$classtime=$this->get_squad_time($data[0]['squadid']);
		$arr['sdate']=$classtime+($num-1)*24*3600;
		$arr['edate']=$arr['sdate']+6*24*3600;
		return $arr;
	}
	//获取打印考勤类别
	function get_checking_type_name($id){
		switch ($id) {
			case 0:
				return '正点';
				break;
			case 1:
				return '缺勤';
				break;
			case 2:
				return '早退';
				break;
			case 3:
				return '迟到';
				break;
			case 4:
				return '请假';
				break;
			}
	}
	//获取打印考勤国籍
	function get_nationality_name($id){
		$nationality=CF('public','',CACHE_PATH);
		return $nationality['global_country_cn'][$id];
	}
}