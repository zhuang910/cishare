<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 学员风采管理
 *
 * @author zyj
 *        
 */
class Student_check_model extends CI_Model {
	const T_ARTICLE = 'checking';
	const T_STUDENT='student';
	const T_MAJOR='major';
	const T_SQUAD='squad';
	const T_COURSE='course';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
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
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition, $programaids = null) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if ($programaids !== null) {
					$this->db->where ( 'columnid in(' . $programaids . ')' );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_ARTICLE )->result ();
		}
		return array ();
	}
		/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_checking($condition,$label_id) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			 $id=$this->get_student_id();
			
				if($label_id==0){
					if(!empty($id['jg'])){
						$this->db->where_in('id',$id['jg']);
						return $this->db->from ( self::T_STUDENT )->count_all_results ();
					}else{
						return 0;
					}
					
				}else{
					if(!empty($id['kc'])){
						$this->db->where_in('id',$id['kc']);
						return $this->db->from ( self::T_STUDENT )->count_all_results ();
					}else{
						return 0;
					}
				}

			
		}
		return 0;
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_checking($field, $condition,$label_id) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$id=$this->get_student_id();
			
				if($label_id==0){
					if(!empty($id['jg'])){
						$this->db->where_in('id',$id['jg']);
						return $this->db->get ( self::T_STUDENT )->result();
					}else{
						return array();
					}
					
				}else{
					if(!empty($id['kc'])){
						$this->db->where_in('id',$id['kc']);
						return $this->db->get ( self::T_STUDENT )->result();
					}else{
						return array();
					}
				}

			
		}
		return array ();
	}

	/**
	 * 签证统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_visaend($condition,$label_id) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			 $id=$this->get_visaend_id();
				if($label_id==2){
					if(!empty($id['fast'])){
						$this->db->where_in('id',$id['fast']);
						return $this->db->from ( self::T_STUDENT )->count_all_results ();
					}else{
						return 0;
					}
					
				}else{
					if(!empty($id['due'])){
						$this->db->where_in('id',$id['due']);
						return $this->db->from ( self::T_STUDENT )->count_all_results ();
					}else{
						return 0;
					}
				}

			
		}
		return 0;
	}
	
	/**
	 * 签证统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_visaend_s($condition,$label_id) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			$where = 'id > 0 AND visatime > 0';
			$time_n = time();
			$time_n_1 = time() + 24*3600*7;
			if($label_id == 3){
				$where.= ' AND visatime < '.$time_n;
			}else{
				$where .=' AND visatime > '.$time_n.' AND visatime < '.$time_n_1;
			}
			$this->db->where ($where);
			return $this->db->from ( 'student_visa' )->count_all_results ();
			
			
		}
		return 0;
	}
	/**
	 * 签证获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_visaend($field, $condition,$label_id) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$id=$this->get_visaend_id();
				if($label_id==2){
					if(!empty($id['fast'])){
						$this->db->where_in('id',$id['fast']);
						return $this->db->get ( self::T_STUDENT )->result();
					}else{
						return array();
					}
					
				}else{
					if(!empty($id['due'])){
						$this->db->where_in('id',$id['due']);
						return $this->db->get ( self::T_STUDENT )->result();
					}else{
						return array();
					}
				}

			
		}
		return array ();
	}
	/**
	 * 签证获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_visaend_s($field, $condition,$label_id) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ('*');
			if (is_array ( $condition ) && ! empty ( $condition )) {
				
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			$where = 'id > 0 AND visatime > 0';
			$time_n = time();
			$time_n_1 = time() + 24*3600*7;
			if($label_id == 3){
				$where.= ' AND visatime < '.$time_n;
				$this->db->where($where);
						return $this->db->get ('student_visa')->result();
			}else{
				$where .=' AND visatime > '.$time_n.' AND visatime < '.$time_n_1;
				$this->db->where($where);
						return $this->db->get ('student_visa')->result();
			}
			/*
				if($label_id==2){
					if(!empty($id['fast'])){
						$this->db->where_in('id',$id['fast']);
						return $this->db->get ( self::T_STUDENT )->result();
					}else{
						return array();
					}
					
				}else{
					if(!empty($id['due'])){
						$this->db->where_in('id',$id['due']);
						return $this->db->get ( self::T_STUDENT )->result();
					}else{
						return array();
					}
				}
				*/
			
		}
		return array ();
	}

	function get_student_id(){
		//获取所有的分班学生
		$count_jg = 0;
		$count_kc = 0;
		$attendance_notice = CF ( 'attendance_notice', '', CONFIG_PATH );
		$stuarr=$this->get_stuid();
		$count_kc_arr=array();
		$count_jg_arr=array();
		$data=array();
		foreach ($stuarr as $k => $v) {
			$num=$this->get_stu_num($v['id']);

			//开除线
			if($num>$attendance_notice['dismiss']){
				$count_kc_arr[]=$v['id'];
				$count_kc=$count_kc+1;
				continue;
			}
			//通知线
			if($num>$attendance_notice['warning']){
			
				$count_jg_arr[]=$v['id'];
				$count_jg=$count_jg+1;

				continue;
			}
		}
		$data['jg']=$count_jg_arr;
		$data['kc']=$count_kc_arr;
			return $data;
	}
	function get_stuid(){
		$this->db->select('id');
		$this->db->where('squadid <>','');
		return $this->db->get('student')->result_array();
	}
	function get_stu_num($id){
		$this->db->select('count(*) as num');
		$this->db->where('studentid',$id);
		$data=$this->db->get('checking')->row_array();
		return $data['num'];
	}
	function get_majorname($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_MAJOR)->row_array();
		return $data['name'];
	}
	function get_squadname($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_SQUAD)->row_array();
		return $data['name'];
	}
	/////////签证
	function get_visaend_id(){
		$fast_due=0;
		$due=0;
		$fast_due_stu=array();
		$due_stu=array();
		//获取所有有签证的时间的学生
		$stu_qian=$this->qian_student_id();
		foreach ($stu_qian as $k => $v) {
			$due_time=$this->get_stu_due_time($v['id']);
			//签证到期的时间
			if($due_time<time()){
				$due_stu[]=$v['id'];
				$due=$due+1;
				continue;
			}
			//签证一周内到期的学生
			$time=time()+24*3600*7;
			if($due_time<$time){
				$fast_due_stu[]=$v['id'];
				$fast_due=$fast_due+1;
				continue;
			}
		}
		$data['fast']=$fast_due_stu;
		$data['due']=$due_stu;
		return $data;
	}
	//获取所有有签证到期时间的学生
	function qian_student_id(){
		$this->db->select('id');
		$this->db->where('visaendtime <>','');
		return $this->db->get('student')->result_array();
	}
	//获取学生的签证到期时间
	function get_stu_due_time($id){
		$this->db->select('visaendtime');
		$this->db->where('id',$id);
		$data=$this->db->get('student')->row_array();
		return $data['visaendtime'];
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_ARTICLE)->row ();
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
				return $this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_content($id = null, $data = array()) {
		if (! empty ( $data )) {
			// 验证内容表是否存在$id
			$is = $this->db->get_where ( self::T_ARTICLE_CONTENT, 'articleid = ' . $id, 1, 0 )->row ();
			if (! empty ( $is )) {
				return $this->db->update ( self::T_ARTICLE_CONTENT, $data, 'articleid = ' . $id );
			} else {
				$data ['articleid'] = $id;
				return $this->db->insert ( self::T_ARTICLE_CONTENT, $data );
			}
		}
	}
	
	/**
	 * 审核文章
	 *
	 * @param number $id        	
	 * @param number $state        	
	 */
	function save_audit($id = null, $state = 1) {
		if ($id !== null) {
			return $this->db->update ( self::T_ARTICLE, array (
					'state' => $state 
			), 'articleid = ' . $id );
		}
	}
	
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ARTICLE, $where);
			return true;
		}
		return false;
	}
	/**
	 * [get_student_checking 查看学生详细的考勤]
	 * @return [type] [description]
	 */
	function get_student_checking($sid){
		$this->db->select('checking.*,course.name as cname');
		$this->db->where('checking.studentid',$sid);
		$this->db->join(self::T_COURSE,self::T_ARTICLE . '.courseid=' . self::T_COURSE . '.id');
		return $this->db->get(self::T_ARTICLE)->result_array();
	}
}