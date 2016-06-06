<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Test_paper_Model extends CI_Model {
	const T_TEST_PAPER='test_paper';
	const T_DEGREE='degree_info';
	const T_MAJOR='major';
	const T_PAPER_GROUP='paper_group';
	const T_PAPER_ITEM='paper_item';
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
			
			return $this->db->get ( self::T_TEST_PAPER )->result ();
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
			
			return $this->db->from ( self::T_TEST_PAPER )->count_all_results ();
		}
		return 0;
	}
	
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete($where) {
		if (!empty($where)) {
			if ($this->db->delete ( self::T_TEST_PAPER, $where )) {
				return true;
			}
		}
		return false;
	}
	function change_state($arr){
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$data['state']=0;
				$this->db->update ( self::T_TEST_PAPER, $data, 'id = ' . $v );
			}
			return 1;
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
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_TEST_PAPER )->row ();
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
				if ($this->db->insert ( self::T_TEST_PAPER, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_TEST_PAPER, $data, 'id = ' . $id );
			}
		}
	}
	
	/**
	 * 批量修改
	 */
	function someupdate($where = null, $data = null) {
		if ($where != null && $data != null) {
			return $this->db->update ( self::T_TEST_PAPER, $data, $where );
		} else {
			return false;
		}
	}
	/**
	 * [get_degree_info 获取学历信息]
	 * @return [type] [description]
	 */
	function get_degree_info(){
		$this->db->where('state',1);
		return $this->db->get(self::T_DEGREE)->result_array();
	}
	/**
	 * [get_maojor_info 获取学位下的专业]
	 * @param  [type] $degreeid 学位id
	 * @return [type]           [array()]
	 */
	function get_maojor_info($degreeid){
		if(!empty($degreeid)){
			$this->db->where('degree',$degreeid);
			$this->db->where('state',1);
			return $this->db->get(self::T_MAJOR)->result_array();
		}	
		return array();
	}
	/**
	 * [get_degree_major 获取学位和专业]
	 * @return [type] [description]
	 */
	function get_degree_major($degreeid,$majorid){
		$str='';
		$dname='';
		$mname='';
		if(!empty($degreeid)){
			$dname=$this->get_degree_name($degreeid);
		}
		if(!empty($majorid)){
			$mname=$this->get_major_name($majorid);
		}
		$str.=$dname.'->'.$mname;
		return $str;
	}
	/**
	 * [get_degree_name 获取学位的名字]
	 * @param  [type] $degreeid [description]
	 * @return [type]           [description]
	 */
	function get_degree_name($degreeid){
		if(!empty($degreeid)){
			$this->db->select('title');
			$this->db->where('id',$degreeid);
			$data=$this->db->get(self::T_DEGREE)->row_array();
			if(!empty($data['title'])){
				return $data['title'];
			}
		}
		return '';
	}
	/**
	 * [get_degree_name 获取专业的名字]
	 * @param  [type] $degreeid [description]
	 * @return [type]           [description]
	 */
	function get_major_name($majorid){
		if(!empty($majorid)){
			$this->db->select('name');
			$this->db->where('id',$majorid);
			$data=$this->db->get(self::T_MAJOR)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_paper($field, $condition,$paperid) {
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
			$this->db->where('paperid',$paperid);
			return $this->db->get ( self::T_PAPER_GROUP )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_paper($condition,$paperid) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			$this->db->where('paperid',$paperid);
			return $this->db->from ( self::T_PAPER_GROUP )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_paper_group($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				if ($this->db->insert ( self::T_PAPER_GROUP, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_PAPER_GROUP, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one_paper_group($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_PAPER_GROUP )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete_paper_group($where) {
		if ($where != null) {
			if ($this->db->delete ( self::T_PAPER_GROUP, $where)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_paper_item($field, $condition) {
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
			return $this->db->get ( self::T_PAPER_ITEM )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_paper_item($condition) {
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			return $this->db->from ( self::T_PAPER_ITEM )->count_all_results ();
		}
		return 0;
	}
	/**
	 * 保存试题项基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_paper_item($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				if ($this->db->insert ( self::T_PAPER_ITEM, $data )) {
					
					return $this->db->insert_id ();
				}
			} else {
				$this->db->update ( self::T_PAPER_ITEM, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [get_group_info ]
	 * @return [type] [description]
	 */
	function get_group_info($paperid){
		if(!empty($paperid)){
			$this->db->where('paperid',$paperid);
			$this->db->order_by('orderby ASC,id ASC');
			return $this->db->get ( self::T_PAPER_GROUP )->result_array();
		}
		return array();
	}
	/**
	 * [get_item_info 获取]
	 * @return [type] [description]
	 */
	function get_item_info($arr){
		if(!empty($arr)&&is_array($arr)){
			$item_arr=array();
			foreach ($arr as $k => $v) {
				$item_arr[$v['id']]=$this->get_group_item_info($v['id']);
			}
			return $item_arr;
		}
		return array();
	}
	/**
	 * [get_group_item_info 获取大题里的小题]
	 * @return [type] [description]
	 */
	function get_group_item_info($groupid){
		if(!empty($groupid)){
			$this->db->where('groupid',$groupid);
			$this->db->order_by('orderby ASC,id ASC');
			return $this->db->get ( self::T_PAPER_ITEM )->result_array();
		}
		return array();
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one_paper_item($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_PAPER_ITEM )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 * 删除一条
	 *
	 * @param
	 *        	$id
	 */
	function delete_item($where) {
		if (!empty($where)) {
			if ($this->db->delete ( self::T_PAPER_ITEM, $where )) {
				return true;
			}
		}
		return false;
	}
	/**
	 * [get_group_allscore 获取大题总分数]
	 * @param  [type] $groupid [description]
	 * @return [type]          [description]
	 */
	function get_group_allscore($groupid){
		if(!empty($groupid)){
			$this->db->select('all_score');
			$this->db->where('id',$groupid);
			$data=$this->db->get ( self::T_PAPER_GROUP )->row_array();
			if(!empty($data['all_score'])){
				return $data['all_score'];
			}
		}
		return 0;
	}
	/**
	 * [get_group_allscore 获取实际总分数]
	 * @param  [type] $groupid [description]
	 * @return [type]          [description]
	 */
	function get_item_shiji_allscore($groupid){
		if(!empty($groupid)){
			$this->db->where('groupid',$groupid);
			$this->db->order_by('orderby ASC,id ASC');
			$data= $this->db->get ( self::T_PAPER_ITEM )->result_array();
			if(!empty($data)){
				$num=0;
				foreach ($data as $k => $v) {
					$num+=$v['score'];
				}
				return $num;
			}
		}
		return 0;
	}
	/**
	 * [get_item_one_score 获取这个提的分数]
	 * @return [type] [description]
	 */
	function get_item_one_score($id){
		if(!empty($id)){
			$this->db->select('score');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_PAPER_ITEM)->row_array();
			return $data['score'];
		}
		return 0;
	}
}