<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Stuscore_Model extends CI_Model {
	const T_MAJOR= 'major';
	const T_TEACHER='teacher';
	const T_COURSE='course';
	const T_SQUAD= 'squad';
	const T_MAJOR_COURSE='major_course';
	const T_TEACHER_COURSE='teacher_course';
	const T_CLASSROOM='classroom';
	const T_SCHEDULING='scheduling';
	const T_STUDENT='student';
	const T_SCORE='score';
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
	 * 
	 * 获取学生
	 * */
	function get_studentinfo($sid){
		$this->db->select('id,studentid,name,email,enname');
		$this->db->where('squadid',$sid);
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	 * 
	 * 获取详细搜索出来的学生
	 * */
	function get_student_one($squadid,$k,$v){
		$this->db->select('id,studentid,name,email,enname');
		$this->db->where('squadid',$squadid);	
		$this->db->where($k,$v);	
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	 * 
	 * 获取详细搜索出来的学生
	 * */
	function get_student_ones($data){
		$this->db->select('id,studentid,name,email,enname');
		$this->db->where($data['key'],$data['value']);	
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	 * 
	 * 获取学生
	 * */
	function insert_score($do,$dt){
		foreach ($dt as $k => $v) {
			if(empty($v)){
				continue;
			}
			$do['studentid']=$k;
            $do['score']=$v[0];
            $do['show_score']=$v[1];
            $do['createtime']=time();
			$this->db->insert ( self::T_SCORE, $do );
		}
		return 1;
	}
	function get_stu_score(){
		return $this->db->get(self::T_SCORE)->result_array();
	}
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_SCORE, $where);
			return true;
		}
		return false;
	}
		/**
	 *
	 *获取专业字段
	 **/
	function get_stuscore_fields(){
		return array(
			  'studentid' =>  '学生名',
			  'majorid' =>  '专业',
			  'courseid' =>  '课程',
			  'squadid' =>  '班级',
			  'adminid' =>  '管理员' ,
			  'term' =>  '学期',
			  'score' =>  '分数',
			  'show_score'=>'课堂表现分',
			  'scoretype' =>  '考试类型' ,
			  'email' =>  '学生邮箱' ,
			);
	}

	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into zust_score ('.$insert.') values('.$value.')';
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
		if(!empty($str)){
			$arr=explode('-', $str);
			return $arr[0];
		}
		return 0;
	}
	/**
	 * 获取班级id
	 */
	function get_squadid($name,$mid){
		$this->db->select('id');
		$this->db->where('majorid',$mid);
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
	 *检查是否有重复记录
	 *@$insert:字段
	 *@$value:字段值
	 **/
	function check_score($insert,$value){
		$insert=explode(',',$insert);
		$value=explode(',',$value);
		$this->db->select('count(*) as count');
		$this->db->where($insert[2],trim($value[2],'""'));
		$this->db->where($insert[0],trim($value[0],'""'));
		$this->db->where($insert[3],trim($value[3],'""'));
		$this->db->where($insert[7],trim($value[7],'""'));
		$data=$this->db->get(self::T_SCORE)->row_array();
		return $data['count'];
	}

    /**
     * 获取一条老师的半颗信息
     */
    function get_laoshixinxi($data){
        if(!empty($data)){
            $this->db->select('scheduling.*,teacher.*');
            $this->db->where('scheduling.majorid',$data['majorid']);
            $this->db->where('scheduling.courseid',$data['courseid']);
            $this->db->where('scheduling.nowterm',$data['nowterm']);
            $this->db->where('scheduling.squadid',$data['squadid']);
            $this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
            $this->db->group_by('scheduling.teacherid');
            return $this->db->get(self::T_SCHEDULING)->result_array();
        }
    }
}