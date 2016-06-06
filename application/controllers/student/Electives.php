<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Electives extends Student_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
        is_studentlogin ();
		$this->load->model ( 'student/electives_model' );
	}
	
	/**
	 */
	function index() {
		//获取学生所在小儿专业里面的查出里面的课程根据班级所在的学期来显示可选修的课程
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//获取所在的专业和班级
		$userinfo=$this->electives_model->get_user_info($userid);
		//判断是否分班
		$is_squad=0;
		//筛选后的课程
		$course=array();
		if(!empty($userinfo['squadid'])){
			$is_squad=1;
			//获取学生的当前学期
			$now_term=$this->electives_model->get_now_term($userinfo['squadid']);
			//获取这个专业选修课的课程
			$major_coursse=$this->electives_model->get_major_course($userinfo['majorid']);
			//判断时间是不是当时的选课的时间
			if(!empty($major_coursse)){
				$xuankecourse=$this->electives_model->check_course_time($major_coursse);
				if(!empty($xuankecourse)){
					//判断是不是当时的学期可以选课
					$course=$this->electives_model->check_course_term($xuankecourse,$now_term);
				}	
			}
			//判断学期是不是当时的学期可以选择选修课
		}
		$course_info=array();
		if(!empty($course)){
			$course_info=$this->electives_model->get_s_course_info($course);
		}
		//筛选出已经报名的选修课  
		if(!empty($course_info)){
			$course_info=$this->electives_model->screen_course_info($course_info,$userid);
		}
		$this->load->view ( '/student/electives_index',array(
				'is_squad'=>$is_squad,
				'course_info'=>$course_info
			));
	}
	/**
	 * [apply_course 学生提交报名的课程]
	 * @return [type] [description]
	 */
	function apply_course(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$userinfo=$this->electives_model->get_user_info($userid);
		//获取排课id
		$sid=intval(trim($this->input->get('sid')));
		//判断sid是不是学生可以报名的课程  防止firebug攻击
		$is=$this->check_student_course($sid);
		if($is===1){
			//有权限报名这个课程
			//查询这个课程排课的信息
			$paike_info=$this->electives_model->get_paike_info_one($sid);
			//获取学生的当前学期
			$now_term=$this->electives_model->get_now_term($userinfo['squadid']);
			//查询这个学生是否报过该时间的短的课程
			$is_bao=$this->electives_model->check_student_bao($userid,$userinfo['squadid'],$now_term,$paike_info);
			if($is_bao>0){
				ajaxreturn('','',3);
			}
			//查询该学生在这个时间段必修课是否有课
			$is_youke=$this->electives_model->check_student_youke($userid,$userinfo['squadid'],$now_term,$paike_info);
			if($is_youke>0){
					ajaxreturn('','',4);
			}
			//查询该学生在这个时间段是否有课
			$is_xuanxiuyouke=$this->electives_model->check_student_xuanxiuyouke($userid,$userinfo['squadid'],$now_term,$paike_info);
			if($is_xuanxiuyouke>0){
					ajaxreturn('','',5);
			}
			//插入学生报名的选修课课程
			$insert_id=$this->electives_model->insert_course_e($userid,$userinfo['squadid'],$now_term,$paike_info);
			if(!empty($insert_id)){
				ajaxreturn('','',1);
			}
		}else{
			ajaxreturn('','',2);
		}
		ajaxreturn('','',0);
	}
	/**
	 * [check_student_course 判断学生是不是可以报名这个课程  防止firebug攻击]
	 * @return [type] [description]
	 */
	function check_student_course($sid){
		//获取学生所在小儿专业里面的查出里面的课程根据班级所在的学期来显示可选修的课程
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//获取所在的专业和班级
		$userinfo=$this->electives_model->get_user_info($userid);
		//筛选后的课程
		$course=array();
		if(!empty($userinfo['squadid'])){
			//获取学生的当前学期
			$now_term=$this->electives_model->get_now_term($userinfo['squadid']);
			//获取这个专业选修课的课程
			$major_coursse=$this->electives_model->get_major_course($userinfo['majorid']);
			//判断时间是不是当时的选课的时间
			if(!empty($major_coursse)){
				$xuankecourse=$this->electives_model->check_course_time($major_coursse);
				if(!empty($xuankecourse)){
					//判断是不是当时的学期可以选课
					$course=$this->electives_model->check_course_term($xuankecourse,$now_term);
				}	
			}
			//判断学期是不是当时的学期可以选择选修课
		}
		$course_info=array();
		if(!empty($course)){
			$course_info=$this->electives_model->get_s_course_info($course);
		}
		//验证排课的id是不是在这个数组里面
		if(!empty($course_info)){
			foreach ($course_info as $k => $v) {
				if($sid==$v['id']){
					return 1;
				}
			}
		}
		return 0;
	}
	/**
	 * [upelectives 已经报名的选修课]
	 * @return [type] [description]
	 */
	function upelectives(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$course_info=$this->electives_model->get_user_course_de_info($userid,0);
		$this->load->view ( '/student/student_electives_index',array(
				'course_info'=>$course_info
			));
	}
	/**
	 * [upelectives 已经报名的选修课]
	 * @return [type] [description]
	 */
	function failelectives(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$course_info=$this->electives_model->get_user_course_de_info($userid,2);
		$this->load->view ( '/student/student_electives_indexs',array(
				'course_info'=>$course_info
			));
	}
	/**
	 * [delete_student_course ]
	 * @return [type] [description]
	 */
	function delete_student_course(){
		$id=intval(trim($this->input->get('id')));
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//检查是否有权限删除这个id
		$is=$this->check_student_course_delete($id,$userid);
		if($is==1){
			$where='id ='.$id;
			$is_d=$this->electives_model->delete_user_course($where);
			if($is_d==true){
				ajaxreturn('','',1);
			}
		}else{
			ajaxreturn('','',2);
		}
		ajaxreturn('','',0);
	}
	/**
	 * [check_student_course_delete 检查是否能删除所报的课程]
	 * @return [type] [description]
	 */
	function check_student_course_delete($id,$userid){
		if(!empty($id)&&!empty($userid)){
			$num=$this->electives_model->get_user_course_num($id,$userid);
			if($num>0){
				return 1; 
			}
		}
		return 0;
	}
	/**
	 * [myelectives 我的选修课]
	 * @return [type] [description]
	 */
	function myelectives(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$course_info=$this->electives_model->get_user_course_de_info($userid,1);
		$this->load->view ( '/student/student_qr_electives_index',array(
				'course_info'=>$course_info
			));
	}
	/**
	 * [look_remark ]
	 * @return [type] [description]
	 */
	function look_remark(){
		$id=$this->input->get('id');
		$info=$this->db->get_where('course_elective','id = '.$id)->row_array();
		$this->load->view ( '/student/ele_look_remark',array(
			'info'=>$info
			));
	}
	/**
	 * [book_room 查看教学大纲]
	 * @return [type] [description]
	 */
	function look_course(){
		$id=intval(trim($this->input->get('id')));
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$course_info=$this->electives_model->get_user_course_info_one($id);
		//获取该课程该老师的教学大纲
		$info='';
		if(!empty($course_info['teacherid'])&&!empty($course_info['courseid'])){
			$info=$this->electives_model->get_tea_course($course_info['teacherid'],$course_info['courseid']);
		}
		$html = $this->load->view ( '/student/look_course', array(
				'info'=>$info
				), true );
		ajaxReturn ( $html, '', 1 );
	}
}