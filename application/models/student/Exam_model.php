<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Exam_Model extends CI_Model {
	const T_P = 'test_paper';
	const T_P_G = 'paper_group';
	const T_P_I = 'paper_item';
	const T_E_I = 'examination_info';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取试卷信息
	 */
	function get_paper_info($where = null) {
		$data = array ();
		if ($where != null) {
			$data = $this->db->select ( '*' )->order_by ( 'id ASC' )->get_where ( self::T_P, $where )->result_array ();
		}
		return $data;
	}
	
	/**
	 * 获取大题
	 */
	function get_question_big($where = null) {
		$data = array ();
		if ($where != null) {
			$data = $this->db->select ( '*' )->order_by ( 'orderby ASC' )->get_where ( self::T_P_G, $where )->result_array ();
		}
		return $data;
	}
	
	/**
	 * 获取 小题
	 */
	/**
	 * 获取大题
	 */
	function get_question_small($where = null) {
		$data = array ();
		if ($where != null) {
			$data = $this->db->select ( '*' )->order_by ( 'orderby ASC' )->get_where ( self::T_P_I, $where )->result_array ();
		}
		return $data;
	}
	
	/**
	 * 保存答案 信息
	 */
	function save_answer($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_E_I, $data );
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
	
	/**
	 * 修改基本信息
	 */
	function update_answer($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_E_I, $data, $where );
		}
	}
	
	/**
	 * 获取答题信息
	 */
	function get_one_answer($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->select ( '*' )->order_by ( 'id DESC' )->get_where ( self::T_E_I, $where )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	
	// ///////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * 注册添加
	 * 数据
	 */
	function save_base($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_A, $data );
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
	
	/**
	 * 报名参见活动
	 */
	function save_activity_user($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_A_U, $data );
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
	
	/**
	 * 修改基本信息
	 */
	function update_base($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_A, $data, $where );
		}
	}
	/**
	 *
	 * @param string $where        	
	 * @param string $data        	
	 * @return boolean
	 */
	function update_activity_user($where = null, $data = null) {
		if ($where !== null && $data != null) {
			return $this->db->update ( self::T_A_U, $data, $where );
		} else {
			return false;
		}
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_one_base($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->where ( $where )->get ( self::T_A )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_one_collect($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->where ( $where )->get ( self::T_A_C )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_one_content($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->where ( $where )->get ( self::T_A_E )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	
	/**
	 * 获取 一条 用户的 参与信息
	 */
	function get_activity_user_mine($where = null) {
		$data = array ();
		if ($where != null) {
			$datas = $this->db->where ( $where )->get ( self::T_A_U )->result_array ();
			if (! empty ( $datas )) {
				$data = $datas [0];
			}
		}
		return $data;
	}
	
	/**
	 * 统计数量(总页数、总条数)
	 * @table 表名
	 * @where 条件
	 * @size 查询多少条
	 */
	function counts($where, $size = 10) {
		$count = $this->db->get_where ( self::T_A, $where )->num_rows ();
		$data = array (
				'allcount' => $count,
				'pagecount' => ceil ( $count / $size ) 
		);
		return $data;
	}
	
	/**
	 * 得到多条信息 默认降序
	 * @table 表名
	 * @where 条件
	 * @select 查询字段
	 * @offset 从第几条开始查询
	 * @size 查询多少条
	 * @orderby 排序
	 */
	function getall($where = 'id > 0', $select = '*', $offset = '0', $size = '10', $orderby = 'createtime DESC') {
		$res = array ();
		$query = $this->db->select ( $select )->order_by ( $orderby )->get_where ( self::T_A, $where, $size, $offset );
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
	}
	
	/**
	 * 统计数量(总页数、总条数)
	 * @table 表名
	 * @where 条件
	 * @size 查询多少条
	 */
	function countsuser($where, $size = 10) {
		$count = $this->db->get_where ( self::T_A_U, $where )->num_rows ();
		$data = array (
				'allcount' => $count,
				'pagecount' => ceil ( $count / $size ) 
		);
		return $data;
	}
	
	/**
	 * 得到多条信息 默认降序
	 * @table 表名
	 * @where 条件
	 * @select 查询字段
	 * @offset 从第几条开始查询
	 * @size 查询多少条
	 * @orderby 排序
	 */
	function getalluser($where = 'id > 0', $select = '*', $offset = '0', $size = '10', $orderby = 'createtime DESC') {
		$res = array ();
		$query = $this->db->select ( $select )->order_by ( $orderby )->get_where ( self::T_A_U, $where, $size, $offset );
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
	}
	
	/**
	 * 获取活动
	 *
	 * @param string $where        	
	 * @return multitype:
	 */
	function get_all_act($where = null) {
		$data = array ();
		if ($where != null) {
			$data = $this->db->where ( $where )->get ( self::T_A )->result_array ();
		}
		return $data;
	}
	
	/**
	 * 查询数据是否存在
	 */
	function get_info_one($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		}
	}
	
	/**
	 * 删除
	 */
	function del_content($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_A_E, $where );
			return true;
		}
		return false;
	}
	
	/**
	 * 删除收藏
	 */
	function del_one_collect($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_A_C, $where );
			return true;
		}
		return false;
	}
	
	/**
	 * 删除活动
	 */
	function del_activity_base_content($id = null) {
		if ($id != null) {
			$this->db->delete ( self::T_A, 'id = ' . $id );
			$data = $this->get_one_content ( 'activityid = ' . $id );
			if (! empty ( $data )) {
				$this->del_content ( 'activityid = ' . $id );
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 取消报名
	 */
	function del_activity_user($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_A_U, $where );
			return true;
		}
		return false;
	}
	/**
	 * 注册添加
	 * 数据
	 */
	function save_content($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_A_E, $data );
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
	
	/**
	 * 注册添加
	 * 数据
	 */
	function add($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_ARTICLE, $data );
			$userid = $this->db->insert_id ();
			$where = array (
					'id' => $userid 
			);
			return $this->db->where ( $where )->get ( self::T_ARTICLE )->result_array ();
		} else {
			return false;
		}
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function countuser($condition = null) {
		if ($condition != null) {
			$this->db->where ( $condition );
			return $this->db->from ( self::T_A_U )->count_all_results ();
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	
	/**
	 * 添加收藏
	 */
	function save_collect($data = array()) {
		if (! empty ( $data )) {
			$this->db->insert ( self::T_A_C, $data );
			return $this->db->insert_id ();
		} else {
			return false;
		}
	}
}