<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Student_arrearage_Model extends CI_Model {
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
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * [get_qianfei_tuition 获取当前学费的欠费状态]
	 * @return [type] [description]
	 */
	function get_qianfei_tuition(){
		$return_arr=array();
		$studentAll = $this->_get_student ();
		$classxq = $this->_get_class_newterm ();
		 $major_term_tuition=$this->_get_major_term_tuition();
		 if(!empty($studentAll)){
		 	foreach ($studentAll as $k => $v) {
		 		// 查找班级 如果没有班级 怎认为 该学生是 第一学期 组合数据
					if (empty ( $v ['squadid'] )) {
						// 没有分班 学期为第一学期
						$nowterm = 1;
					} else {
						// 分班了 就能找到指定的学期
						$nowterm = ! empty ( $classxq [$v ['squadid']] ) ? $classxq [$v ['squadid']] : '';
					}
					// 先查所在专业 如果 所在专业为空 再查 报名专业
					if (empty ( $v ['majorid'] )) {
						$majorxuefei = $v ['major'];
					} else {
						$majorxuefei = $v ['majorid'];
					}
					//专业名
					$major_info = $this->db->get_where('major','id = '.$majorxuefei)->row_array();
					$now_tuition=! empty ( $major_term_tuition[$majorxuefei][$nowterm] ) ? $major_term_tuition[$majorxuefei][$nowterm] : '';
		 			$flag = $this->db->select ( '*' )->get_where ( 'tuition_info', 'userid = ' . $v ['userid'] . ' AND nowterm = ' . $nowterm .' AND paystate = 1')->result_array();
					$before_tuition=0;

					if(!empty($flag)){
						foreach ($flag as $kk => $vv) {
							$before_tuition+=$vv['tuition'];
						}
					}
					$surplus_tuition=$now_tuition-$before_tuition;
					
					if($surplus_tuition>0){
						$return_arr[]=$v['userid'];
					}
					
		 	}	
		 }
		 $return_arr[]=0;
		 return $return_arr;
	}
	/**
	 * 获取 学期 与班级的关系
	 */
	function _get_class_newterm() {
		$classAll = array (); // 所有的班级
		$classxq = array (); // 组合数据 班级的id => 学期
		$classAll = $this->db->select ( '*' )->get_where ( 'squad', 'id > 0' )->result_array ();
		if (! empty ( $classAll )) {
			foreach ( $classAll as $key => $val ) {
				$classxq [$val ['id']] = $val ['nowterm'];
			}
			return $classxq;
		}
		return array ();
	}
		/**
	 * [_get_major_term_tuition 获取专业每学期的学费]
	 * @return [type] [description]
	 */
	function _get_major_term_tuition(){
		$data=array();
		$majorAll = $this->db->select ( '*' )->get_where ( 'major', 'id > 0' )->result_array ();
		if(!empty($majorAll)){
			foreach ($majorAll as $k => $v) {
				$major_term=$this->db->select ( '*' )->get_where ( 'major_tuition', 'majorid = '.$v['id'] )->result_array ();
				if(!empty($major_term)){
					foreach ($major_term as $k => $v) {
						$data[$v['majorid']][$v['term']]=$v['tuition'];
					}
				}
			}
			if(!empty($data)){
				return $data;
			}
		}

		return $data;
	}
	/**
	 * 获取所有的学生
	 */
	function _get_student() {
		$studentAll = $this->db->select ( '*' )->get_where ( 'student', 'id > 0 AND major != 0 AND state = 1' )->result_array ();
		if (! empty ( $studentAll )) {
			return $studentAll;
		} else {
			return array ();
		}
	}
	/**
	 * [get_qianfei_acc ]
	 * @return [type] [description]
	 */
	function get_qianfei_acc(){
		$return_arr=array();
		$studentAll = $this->_get_student ();
		if(!empty($studentAll)){
			foreach ($studentAll as $k => $v) {
				$acc_info=$this->db->get_where('accommodation_info','userid = '.$v['userid'].' AND acc_state = 6')->row_array();
				if(!empty($acc_info)){
					$time=time()-$acc_info['accstarttime'];

					$shengday=$time/3600/24-$acc_info['accendtime'];
					if($shengday>0){
						$return_arr[]=$v['userid'];
					}
					
				}
			}
		}
		$return_arr[]=0;
		return $return_arr;
	}
	/**
	 * [get_qianfeijine 获取欠费金额]
	 * @return [type] [description]
	 */
	function get_qianfeijine($label_id,$userid){
		if(!empty($label_id)&&!empty($userid)){
			if($label_id==4){
				$acc_info=$this->db->get_where('accommodation_info','userid = '.$userid.' AND acc_state = 6')->row_array();
				if(!empty($acc_info)){
					$time=time()-$acc_info['accstarttime'];

					$shengday=$time/3600/24-$acc_info['accendtime'];
					return sprintf("%.2f",$shengday*$acc_info['registeration_fee']);
				}
			}
			if($label_id==7){
				//获取该生交电费的费用
				 $user_e=$this->db->get_where('electric_info','userid = '.$userid)->result_array();
				 $e_total=0;
					if(!empty($user_e)){
						foreach ($user_e as $kkk => $vvv) {
							$e_total+=$vvv['paid_in'];
						}
					}
				//查询已经用的电费
				$u_e_total=0;
				$user_y_e=$this->db->get_where('room_electric_user','userid = '.$userid)->result_array();
				if(!empty($user_y_e)){
					foreach ($user_y_e as $kkkk => $vvvv) {
						$u_e_total+=$vvvv['money'];
					}
				}
				$result=$e_total-$u_e_total;
				return abs($result);
			}
		}
		if($label_id==6){
			$return_arr=array();
			$stu=$this->db->get_where('student','userid = '.$userid)->row_array();
			$classxq = $this->_get_class_newterm ();
			 $major_term_tuition=$this->_get_major_term_tuition();
			 		// 查找班级 如果没有班级 怎认为 该学生是 第一学期 组合数据
						if (empty ( $stu ['squadid'] )) {
							// 没有分班 学期为第一学期
							$nowterm = 1;
						} else {
							// 分班了 就能找到指定的学期
							$nowterm = ! empty ( $classxq [$stu ['squadid']] ) ? $classxq [$stu ['squadid']] : '';
						}
						// 先查所在专业 如果 所在专业为空 再查 报名专业
						if (empty ( $stu ['majorid'] )) {
							$majorxuefei = $stu ['major'];
						} else {
							$majorxuefei = $stu ['majorid'];
						}
						//专业名
						$major_info = $this->db->get_where('major','id = '.$majorxuefei)->row_array();
						$now_tuition=! empty ( $major_term_tuition[$majorxuefei][$nowterm] ) ? $major_term_tuition[$majorxuefei][$nowterm] : '';
			 			$flag = $this->db->select ( '*' )->get_where ( 'tuition_info', 'userid = ' . $stu ['userid'] . ' AND nowterm = ' . $nowterm .' AND paystate = 1')->result_array();
						$before_tuition=0;

						if(!empty($flag)){
							foreach ($flag as $kk => $vv) {
								$before_tuition+=$vv['tuition'];
							}
						}
						$surplus_tuition=$now_tuition-$before_tuition;
						return $surplus_tuition;
		}
		return 0;
	}
	/**
	 * [get_qianfei_ele description]
	 * @return [type] [description]
	 */
	function get_qianfei_ele(){
		$return_arr=array();
		$studentAll = $this->_get_student ();
		if(!empty($studentAll)){
			foreach ($studentAll as $k => $v) {
				//获取该生交电费的费用
				 $user_e=$this->db->get_where('electric_info','userid = '.$v['userid'])->result_array();
				 $e_total=0;
					if(!empty($user_e)){
						foreach ($user_e as $kkk => $vvv) {
							$e_total+=$vvv['paid_in'];
						}
					}
				//查询已经用的电费
				$u_e_total=0;
				$user_y_e=$this->db->get_where('room_electric_user','userid = '.$v['userid'])->result_array();
				if(!empty($user_y_e)){
					foreach ($user_y_e as $kkkk => $vvvv) {
						$u_e_total+=$vvvv['money'];
					}
				}
				$result=$e_total-$u_e_total;
				
				if($result<0){
					$return_arr[]=$v['userid'];
				}
			}
		}
		$return_arr[]=0;
		return $return_arr;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition,$label_id) {
		if($label_id==6){
				$where=$this->get_qianfei_tuition();
			}
		if($label_id==4){
			$where=$this->get_qianfei_acc();
		}
		if($label_id==7){
			$where=$this->get_qianfei_ele();
		}
		if (is_array ( $field ) && ! empty ( $field )) {
			
			$this->db->select ('student.id,student.userid,student.nationality,student.name as name,student.enname as enname,student.passport as passport,squad.nowterm as nowterm,student.majorid as majorid');
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->where_in('student.userid',$where);
				
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
		if($label_id==6){
					$where=$this->get_qianfei_tuition();
					
				}
			if($label_id==4){
			$where=$this->get_qianfei_acc();
		}
		if($label_id==7){
			$where=$this->get_qianfei_ele();
		}
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->where_in('student.userid',$where);
			
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
}