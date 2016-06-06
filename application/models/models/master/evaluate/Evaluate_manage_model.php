<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Evaluate_manage_Model extends CI_Model {
	const T_STUDENT = 'student';
	const T_MAJOR = 'major';
	const T_SQUAD ='squad';
	const T_COURSE='course';
	const T_FACULTY='faculty';
	const T_MESSAGE_LOG='message_log';
	const T_P_M_C='push_mail_config';
	const T_M_R='mail_record';
	const T_MAJOR_COURSE='major_course';
	const T_BOOKS='books';
	const T_COURSE_BOOKS='course_books';
	const T_STUDENT_BOOKS='student_book';
	const T_PAIKE='scheduling';
	const T_TEACHER='teacher';
	const T_E_STUDENT = 'evaluate_student';
	const T_E_STU_FINISH='evaluate_student_finish';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}

	function get_evaluate_student_finish_id(){
		$str='';
		$this->db->select('userid');
		$data=$this->db->get(self::T_E_STU_FINISH)->result_array();
		foreach ($data as $k => $v) {
			$str.=$v['userid'].',';	
		}
		return trim($str,',');
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition,$label_id) {
		if($label_id==1){
			$this->db->join ( self::T_E_STU_FINISH, self::T_E_STU_FINISH . '.userid=' . self::T_STUDENT . '.userid' );
		}
		if($label_id==2){
			$ids=$this->get_evaluate_student_finish_id();
			if(!empty($ids)){
				$this->db->where('student.userid NOT IN ('.$ids.')');
			}
		}
		if (is_array ( $field ) && ! empty ( $field )) {
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$this->db->where('student.state',1);
			$this->db->join ( self::T_SQUAD, self::T_SQUAD . '.id=' . self::T_STUDENT . '.squadid' );
			return $this->db->get ( self::T_STUDENT )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition,$label_id) {
		if($label_id==1){
			$this->db->join ( self::T_E_STU_FINISH, self::T_E_STU_FINISH . '.userid=' . self::T_STUDENT . '.userid' );
		}
		if($label_id==2){
			$ids=$this->get_evaluate_student_finish_id();
			if(!empty($ids)){
				$this->db->where('student.userid NOT IN ('.$ids.')');
			}
		}
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->where('student.state',1);
			$this->db->join ( self::T_SQUAD, self::T_SQUAD . '.id=' . self::T_STUDENT . '.squadid' );
			
			return $this->db->from ( self::T_STUDENT )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [get_major_squad 获取专业和班级]
	 * @return [type] [description]
	 */
	function get_major_squad($id){
		if(!empty($id)){
			$this->db->select ('major.name as mname,squad.name as sname');
			$this->db->join ( self::T_MAJOR, self::T_STUDENT . '.majorid=' . self::T_MAJOR . '.id' );
			$this->db->join ( self::T_SQUAD, self::T_STUDENT . '.squadid=' . self::T_SQUAD . '.id' );
			$this->db->where('student.id',$id);
			$data=$this->db->get ( self::T_STUDENT)->row_array();
			if(!empty($data)){
				$str=$data['mname'].'->'.$data['sname'];
				return $str;
			}else{
				return '还没有分班';
			}
			
		}
		return '';
	}
	
	
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_STUDENT, $where);
			return true;
		}
		return false;
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_STUDENT)->row ();
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
	function save_message( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_MESSAGE_LOG, $data );
				return $this->db->insert_id ();
		
		}
	}
	//获取userid数组
	function get_userid_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_userid_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条userid
	function get_userid_one($id){
		if(!empty($id)){
			$this->db->select('userid');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['userid'];
		}
		return 0;
	}
	//获取发邮件配置
	function get_addresserset(){
		return $this->db->get(self::T_P_M_C)->result_array();
	}
	/**
	 * 保存邮件发送记录
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_email( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_M_R, $data );
				return $this->db->insert_id ();
		
		}
	}
	function get_email_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_email_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条email
	function get_email_one($id){
		if(!empty($id)){
			$this->db->select('email');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['email'];
		}
		return 0;
	}
	/**
	 * [get_send_book_state 获取发书状态]
	 * @return [type] [description]
	 */
	function get_send_book_state($sid,$mid){
		if(!empty($sid)&&!empty($mid)){
			//获取所有的书籍
			$all_book=$this->get_major_books($mid);
			$str='';
			if(!empty($all_book)){
				foreach ($all_book as $k => $v) {
					$str.=$v['id'].'-grf-';
				}
				$str=trim($str,'-grf-');
			}
			//已发书的信息
			$data=$this->get_student_book_info($sid);
			if(!empty($data['bookid'])){
				if(trim($data['bookid'])==trim($str)){
					return '已领取';
				}else{
					return '未领取';
				}
			}else{
				return '未领取';
			}
			
		}
		return '';
	}
	/**
	 * [get_send_book_price 获取该专业下所有书籍的总价格]
	 * @return [type] [description]
	 */
	function get_send_book_price($sid,$mid){
		if(!empty($sid)&&!empty($mid)){
			//获取所有的书籍
			$all_book=$this->get_major_books($mid);
			$num=0;
			if(!empty($all_book)){
				foreach ($all_book as $k => $v) {
					$num+=$v['price'];
				}
				return $num;
			}
			
			
		}
		return '';
	}
	/**
	 * [get_major_books 获取该专业下的书籍]
	 * @param  [type] $mid [专业id]
	 * @return [type]      [description]
	 */
	function get_major_books($mid){
		if(!empty($mid)){
			//获取该专业下的所有课程
			$m_course=$this->get_major_course($mid);

			if(!empty($m_course)){
				//书籍
				$book=array();
				foreach ($m_course as $k => $v) {
					//获取书籍id
					$book_id=$this->get_books_id($v['courseid']);
					if(!empty($book_id)){
						foreach ($book_id as $kk => $vv) {
							//获取该书籍详细信息
							$book_one=$this->get_books($vv['booksid']);
							if(!empty($book_one)){
								$is=1;
								//去除重复的书籍
								if(!empty($book)){
									foreach ($book as $key => $value) {
										if($value['id']==$book_one['id']){
											$is=0;
										}
									}
								}
								if($is!=0){
									$book[]=$book_one;
								}
								
							}
						}
					}
					
				}

				return $book;
			}
		}
		return array();
	}
	/**
	 * [get_major_course 获取该专业的所有课程]
	 * @param  [type] $mid [专业id]
	 * @return [type]      [description]
	 */
	function get_major_course($mid){
		if(!empty($mid)){
			$this->db->where('majorid',$mid);
			return $this->db->get(self::T_MAJOR_COURSE)->result_array();
		}
	}
	/**
	 * [get_books_id 获取书籍id]
	 * @param  [type] $cid [课程id]
	 * @return [type]      [description]
	 */
	function get_books_id($cid){
		if(!empty($cid)){
			$this->db->where('courseid',$cid);
			return $this->db->get(self::T_COURSE_BOOKS)->result_array();
		}

	}
	/**
	 * [get_course_books 获取该课程的所有书籍]
	 * @param  [type] $cid [课程id]
	 * @return [type]      [description]
	 */
	function get_books($bid){
		if(!empty($bid)){
			$this->db->where('id',$bid);
			$this->db->where('state',1);
			return $this->db->get(self::T_BOOKS)->row_array();
		}
	}
	/**
	 * [do_save_student_book 保存以发书的学生]
	 * @return [type] [description]
	 */
	function do_save_student_book($data){
		if(!empty($data)){
			$str='';
			if(!empty($data['bookid'])){
				foreach ($data['bookid'] as $k=> $v) {
					$str.=$v.'-grf-';
				}
				$str=trim($str,'-grf-');
				$data['bookid']=$str;
			}
			$this->db->delete ( self::T_STUDENT_BOOKS, 'studentid='.$data['studentid'].' AND nowterm='.$data['nowterm']);
			$this->db->insert ( self::T_STUDENT_BOOKS, $data );
			return $this->db->insert_id ();
		}
	}
	/**
	 * [get_student_book_info 获取该学生的发书信息]
	 * @return [type] [description]
	 */
	function get_student_book_info($sid){
		if(!empty($sid)){
			$this->db->where('studentid',$sid);
			return $this->db->get(self::T_STUDENT_BOOKS)->row_array();
		}
	}

	/**
	 * 获取该学生下所有的课程和老师
	 */
	function get_course_teacher($userid){
		if(!empty($userid)){
			//获取该学生所在的班级
			$user_info=$this->get_user_info($userid);
			if(!empty($user_info['squadid'])){
				//获取班级信息
				$squad_info=$this->get_squad_info($user_info['squadid']);
				//获取该班级所有的课程所带的老师
				$course_teacher=$this->get_course_teacher_info($squad_info['id'],$squad_info['nowterm']);
				return $course_teacher;
			}
			
		}
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
	 * [get_squad_info 获取班级信息]
	 * @return [type] [description]
	 */
	function get_squad_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_SQUAD)->row_array();
		}
	}
	/**
	 * [get_course_teacher_info 获取班级下的课程和老师]
	 * @return [type] [description]
	 */
	function get_course_teacher_info($sid,$term){
		if(!empty($sid)&&!empty($term)){
			$this->db->select('scheduling.*,major.name as mname,squad.name as sname,course.name as cname,teacher.name as tname');
			$this->db->where('scheduling.squadid',$sid);
			$this->db->where('scheduling.nowterm',$term);
			$this->db->group_by('scheduling.teacherid');
			$this->db->join ( self::T_MAJOR, self::T_PAIKE . '.majorid=' . self::T_MAJOR . '.id' );
			$this->db->join ( self::T_SQUAD, self::T_PAIKE . '.squadid=' . self::T_SQUAD . '.id' );
			$this->db->join ( self::T_TEACHER, self::T_PAIKE . '.teacherid=' . self::T_TEACHER . '.id' );
			$this->db->join ( self::T_COURSE, self::T_PAIKE . '.courseid=' . self::T_COURSE . '.id' );
			return $this->db->get(self::T_PAIKE)->result_array();
		}
	}
	/**
	 * 获取课程名字
	 */
	function get_course_name($cid){
		if(!empty($cid)){
			$this->db->select('name');
			$this->db->where('id',$cid);
			$data=$this->db->get(self::T_COURSE)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * 获取课程名字
	 */
	function get_teacher_name($cid){
		if(!empty($cid)){
			$this->db->select('name');
			$this->db->where('id',$cid);
			$data=$this->db->get(self::T_TEACHER)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * [check_student_evaluate 查看课程老师评教的完成情况]
	 * @return [type] [description]
	 */
	function check_student_evaluate($userid,$arr){
		// var_dump($arr);exit;
		if(!empty($userid)&&!empty($arr)){
			$this->db->select('count(*) as num');
			$this->db->where('userid',$userid);
			$this->db->where('majorid',$arr['majorid']);
			$this->db->where('squadid',$arr['squadid']);
			$this->db->where('term',$arr['nowterm']);
			$this->db->where('courseid',$arr['courseid']);
			$this->db->where('teacherid',$arr['teacherid']);
			$data=$this->db->get(self::T_E_STUDENT)->row_array();
			if(!empty($data)){
				return $data['num'];
			}
		}
		return 0;
	}
}