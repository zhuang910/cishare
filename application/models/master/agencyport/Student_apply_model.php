<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Student_apply_Model extends CI_Model {
	const T_APP='apply_info';
	const T_STUDENT_INFO='student_info';
	const T_MAJOR='major';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition) {
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
			$this->db->where('agencyid',$_SESSION['master_user_info']->id);
			return $this->db->get ( self::T_STUDENT_INFO )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->where('agencyid',$_SESSION['master_user_info']->id);
			return $this->db->from ( self::T_STUDENT_INFO )->count_all_results ();
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_STUDENT_INFO )->row ();
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
				$this->db->insert ( self::T_STUDENT_INFO, $data );
				$userid = $this->db->insert_id ();
				$where = array (
						'id' => $userid 
				);
				return $this->db->where ( $where )->get ( self::T_STUDENT_INFO )->row_array ();
			} else {
				return $this->db->update ( self::T_STUDENT_INFO, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * 获取数据
	 */
	function get_course_base($where = null) {
		$data = array ();
		if ($where != null) {
			$data = $this->db->select ( '*' )->get_where ( self::T_MAJOR, $where )->result_array ();
		}
		return $data;
	}
	/**
	 * 获取一条
	 */
	function get_apply_info($where = null) {
		if ($where != null) {
			$data = $this->db->where ( $where )->order_by ( 'id DESC' )->limit ( 1 )->get ( self::T_APP )->result_array ();
			if (! empty ( $data )) {
				return $data [0];
			}
			return false;
		}
		return false;
	}
	/**
	 * [get_major_one 获取专业数据一条]
	 * @return [type] [description]
	 */
	function get_major_one($where = null){
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_MAJOR )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * [insert_apply_info_one 插入申请表1]
	 * @return [type] [description]
	 */
	function insert_apply_info_one($userid,$courseid){
		if(!empty($userid)&&!empty($courseid)){
			$data['number']= build_order_no ();
			$data['userid']=$userid;
			$data['courseid']=$courseid;
			$data['applytime']=time();
			$data['isstart']=1;
			$this->db->insert(self::T_APP,$data);
			return $this->db->insert_id();
		}
	}

    /**
     * 获取
     */
    function get_user_major($userid){
        if(!empty($userid)){
            $this->db->where('userid',$userid);
            $this->db->where('issubmit',1);
            $data=$this->db->get(self::T_APP)->row_array();
            if(!empty($data['courseid'])){
                //获取专业的名字
                $mname=$this->get_major_name($data['courseid']);
                return $mname;
            }
        }
        return '';
    }

    /**
     * 获取专业的名字
     */
    function get_major_name($id){
        if(!empty($id)){
            $this->db->select('name');
            $this->db->where('id',$id);
            $data=$this->db->get(self::T_MAJOR)->row_array();
            return $data['name'];
        }
        return '';
    }

    /**
     * 获取该学的的申请信息
     */
    function get_apply_student_info($userid){
        if(!empty($userid)){
            $this->db->where('userid',$userid);
            $this->db->where('issubmit',1);
            $data=$this->db->get(self::T_APP)->row_array();
            return $data;
        }
        return array();
    }
}