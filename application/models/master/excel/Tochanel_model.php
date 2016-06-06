<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Tochanel_Model extends CI_Model {
	const T_MAJOR='major';
	const T_FACULTY='faculty';
	const T_DEGREE_INFO='degree_info';
	const T_COURSE='course';
	const T_SCORE='score';
	const T_MAJOR_COURSE='major_course';
	const T_SQUAD='squad';
	const T_STUDENT='student';
	const T_CHECKING='checking';
	const T_TEACHER_COURSE='teacher_course';
	const T_TEACHER='teacher';
	const T_COURSE_CONTENT='course_content';
	const T_COURSE_IMAGES='course_images';
	const T_ATTA='attachments';
	const T_TEMPLATECLASS='templateclass';
	const T_SHIP='scholarship_info';
	const T_ATTACHMENTS='attachments';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}

	
	/**
	 * 获取专业字段1
	 */
	function get_fields(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_major"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		unset($data['coursenum']);
		unset($data['squadnum']);
		unset($data['state']);
		return $data;
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into sdyinc_major ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 * 获取学院ID
	 */
	function get_faculty($name){
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_FACULTY)->row_array();
		return $data['id'];
	}
	/**
	 * 获取学历id
	 */
	function get_degree($name){
		$this->db->where('title',$name);
		$data=$this->db->get(self::T_DEGREE_INFO)->row_array();
		return $data['id'];
	}
	/**
	 * 获取学历
	 */
	function get_degreename(){
		$data=$this->db->get(self::T_DEGREE_INFO)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['title'].',';
		}
		return trim($str,',');
	}
	/**
	 *检查是否插入maojor
	 */
	function checkmajor($insert,$value){
		
		
		$insert=explode(',',$insert);
		$value=explode(',',$value);
		$this->db->select('count(*) as num');
		for($i=0;$i<count($insert);$i++){
			$this->db->where($insert[$i],trim($value[$i],'""'));
		}
		$num=$this->db->get(self::T_MAJOR)->row_array();
		return $num['num'];
	}



	/**
	 * 获取课程字段
	 */
	function get_course_fields(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_course"';
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
	 * 插入课程字段
	 */
	function insert_course_fields($insert,$value){
		$sql='insert into sdyinc_course ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	


	/**
	 * 获取专业信息
	 */
	function get_major_info(){
		$this->db->select('id,name');
		return $this->db->get(self::T_MAJOR)->result_array();
	}
	/**
	 * 获取该专业的课程
	 **/
	
	function get_major_course($majorid){
		$this->db->select('sdyinc_course.name');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_MAJOR_COURSE.'.courseid');
		$this->db->where('majorid',$majorid);
		$data=$this->db->get(self::T_MAJOR_COURSE)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['name'].',';
		}
		if(empty($str)){
			return '还没有设置课程';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 * 获取专业名称
	 */
	function get_major_name($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data= $this->db->get(self::T_MAJOR)->row_array();
		return $data['name'];
	}
	/**
	 * 获取该专业的班级
	 **/
	
	function get_major_squad($majorid,$term){
		$this->db->select('name');
		$this->db->where('majorid',$majorid);
		$this->db->where('nowterm',$term);
		$data=$this->db->get(self::T_SQUAD)->result_array();
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value['name'].',';
		}
		if(empty($str)){
			return '还没有班级';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 * 获取专业的学期
	 * 
	 * */
	function get_major_term($majorid){
		$this->db->select('termnum');
		$this->db->where('id',$majorid);
		$data=$this->db->get(self::T_MAJOR)->row_array();
		$str='';
		for($i=1;$i<=$data['termnum'];$i++){
			$str.='第'.$i.'学期,';
		}
		if(empty($str)){
			return '还没有设置学期';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 *获取考试类型 
	 * */
	function get_scoretype(){
		$scoretype=CF('scoretype','',CONFIG_PATH);
		$str='';
		unset($scoretype[0]);
		foreach ($scoretype as $key => $value) {
			$str.=$value.',';
		}
		if(empty($str)){
			return '还没有设置考试类型';
		}
		$str=trim($str,',');
		return $str;
	}
	/**
	 * 获取成绩字段
	 */
	function get_score_fields(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_score"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		unset($data['majorid']);
		unset($data['adminid']);
		unset($data['term']);
		return $data;
	}
	/**
	 * 插入成绩字段
	 */
	function insert_score_fields($insert,$value){
		$sql='insert into sdyinc_score ('.$insert.') values('.$value.')';
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
	 * 获取考试类型代号
	 */
	function get_scoretypeid($str){
		$scoretype=CF('scoretype','',CONFIG_PATH);
		
		unset($scoretype[0]);
		foreach ($scoretype as $key => $value) {
			if($value==$str){
				$str=$key;
			}
		}
		
		return $str;
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
	function get_studentid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
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
	 *检查是否插入course
	 */
	function checkscore($insert,$value){
		
		
		$insert=explode(',',$insert);
		$value=explode(',',$value);
		$this->db->select('count(*) as num');
		for($i=0;$i<count($insert);$i++){
			$this->db->where($insert[$i],trim($value[$i],'""'));
		}
		$num=$this->db->get(self::T_SCORE)->row_array();
		return $num['num'];
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
		unset($data['majorid']);
		unset($data['adminid']);
		unset($data['nowterm']);
		return $data;
	}

	/**
	 * 获取该专业的老师
	 * 
	 * */
	function get_major_teacher($majorid){
		$this->db->select('sdyinc_course.id');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_MAJOR_COURSE.'.courseid');
		$this->db->where('majorid',$majorid);
		$data=$this->db->get(self::T_MAJOR_COURSE)->result_array();
		$str='';
		
		foreach ($data as $key => $value) {
			$this->db->select('sdyinc_teacher.name');
			$this->db->where('courseid',$value['id']);	
			$this->db->where('week',99);
			$this->db->where('knob',99);
			$this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_TEACHER_COURSE.'.teacherid');	
			$datat=$this->db->get(self::T_TEACHER_COURSE)->result_array();
			foreach ($datat as $k => $v) {
				$str.=$v['name'].',';
			}
		}
		if(empty($str)){
			return '还没有设置老师';
		}
		$str=trim($str,',');
		
		return $str;
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
			  return 1;
			  break;  
			case '早退':
			  return 2;
			  break;
			case '迟到':
			  return 3;
			  break;
			case '缺勤':
			  return 4;
			  break;
			case '请假':
			  return 5;
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
	 *检查是否插入checking
	 */
	function checkchecking($insert,$value){
		
		
		$insert=explode(',',$insert);
		$value=explode(',',$value);
		$this->db->select('count(*) as num');
		for($i=0;$i<count($insert);$i++){
			$this->db->where($insert[$i],trim($value[$i],'""'));
		}
		$num=$this->db->get(self::T_CHECKING)->row_array();
		return $num['num'];
	}
	/**
	 * 插入考勤字段
	 */
	function insert_checking_fields($insert,$value){
		$sql='insert into sdyinc_checking ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 * 获取课程附表字段content
	 */
	function get_course_content(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_course_content"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		unset($data['courseid']);
		return $data;
	}
	/**
	 * 获取课程附表字段images
	 */
	function get_course_images(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_course_images"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		unset($data['courseid']);
		return $data;
	}
	/**
	 * 
	 * 整理栏目字段
	 **/
	function get_programs($data){

		$str="";
		foreach ($data as $key => $value) {
			$str.=$value['title'].',';
		}
		return trim($str,',');
	}
	/**
	 * 
	 * 获取附件模板
	 * */
	function get_attat(){
		$data=$this->db->get(self::T_ATTA)->result_array();
		foreach ($data as $key => $value) {
			$str.=$value['AttaName'].',';
		}
		return trim($str,',');
	}
	/**
	 * 
	 * 获取申请表
	 * */
	function get_class(){
		$this->db->where('parent_id',0);
		$this->db->where('classType',1);
		$data=$this->db->get(self::T_TEMPLATECLASS)->result_array();
		unset($data[0]);
		foreach ($data as $key => $value) {
			$str.=$value['AttaName'].',';
		}
		return trim($str,',');
	}
	/**
	 * 
	 * 获取奖学金
	 * */
	function get_ship(){
		$data=$this->db->get(self::T_SHIP)->result_array();
		foreach ($data as $key => $value) {
			$str.=$value['title'].',';
		}
		return trim($str,',');
	}
	/**
	 * 
	 * 插入课程主表
	 * */
	function insert_course($v){
		$sql='insert into sdyinc_course  values('."'null'".','.$v.')';
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	/**
	 * 
	 * 插入课程content表
	 * */
	function insert_course_content($v,$id){
		$sql='insert into sdyinc_course_content values('."'null'".','.$id.','.$v.')';
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	/**
	 * 
	 * 插入课程content表
	 * */
	function insert_course_images($v,$id){
		$sql='insert into sdyinc_course_images values('."'null'".','.$id.','.$v.')';
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	/**
	 *检查是否插入course
	 */
	function checkcourse($insert,$value){
		
		$insert=explode(',',$insert);
		$value=explode(',',$value);

		$this->db->select('count(*) as num');
		for($i=0;$i<count($insert);$i++){
			if(empty($value[$i])){
				continue;
			}
			$this->db->where($insert[$i],trim($value[$i],'""'));
		}
		$num=$this->db->get(self::T_COURSE)->row_array();
		return $num['num'];
	}

	function get_programsid($data,$name){
		foreach ($data as $key => $value) {

			if($value['title']==$name){

				return $value['programaid'];
			}else{
				return 'null';
			}
		}
	}
	function get_program_unit($data,$name){
		foreach ($data as $key => $value) {
			if($value==$name){
				return $key;
			}else{
				return 'null';
			}
		}
	}
	function get_language($data,$name){
		foreach ($data as $key => $value) {
			if($value==$name){
				return $key;
			}else{
				return 'null';
			}
		}
	}
	function get_hsk($data,$name){
		foreach ($data as $key => $value) {
			if($value==$name){
				return $key;
			}else{
				return 'null';
			}
		}
	}
	function get_degree_type($data,$name){
		foreach ($data as $key => $value) {
			if($value==$name){
				return $key;
			}else{
				return 'null';
			}
		}
	}
	/**
	 * 
	 * 获取申请附件模板id
	 * */
	function get_attatemplatename($name){
		$this->db->select('atta_id');
		$this->db->where('AttaName',$name);
		$data=$this->db->get(self::T_ATTACHMENTS)->row_array();
		return $data['atta_id'];
	}
	
	/**
	 * 
	 * 获取申请附件模板id
	 * */
	function get_applytemplatename($name){
		$this->db->select('tClass_id');
		$this->db->where('ClassName',$name);
		$data=$this->db->get(self::T_TEMPLATECLASS)->row_array();
		return $data['tClass_id'];
	}
}
