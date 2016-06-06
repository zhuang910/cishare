<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Classroom_Model extends CI_Model {
	const T_CLASSROOM = 'classroom';
	const T_PAIKE='scheduling';
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
	function count($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			
			return $this->db->from ( self::T_CLASSROOM )->count_all_results ();
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
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			return $this->db->get ( self::T_CLASSROOM )->result ();
		}
		return array ();
	}
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_CLASSROOM, $where);
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
				$base = $this->db->where ($where)->limit(1)->get(self::T_CLASSROOM)->row ();
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
				$this->db->insert ( self::T_CLASSROOM, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_CLASSROOM, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 *
	 * 删除关联的课程关系表
	 */
	function delete_guanlian($id){
		if ($id != null) {
			$this->db->delete ( self::T_PAIKE, 'classroomid = ' . $id );
		}
		return false;
	}

    /**
     * 检查该时间段有没有安排课程
     */
    function check_time($data){
        if(!empty($data)){
            $this->db->select('count(*) as num');
            $this->db->where('classroomid',$data['classroomid']);
            $this->db->where('week',$data['week']);
            $this->db->where('knob',$data['knob']);
            $arr=$this->db->get(self::T_PAIKE)->row_array();
            return $arr['num'];
        }
        return 1;
    }
}