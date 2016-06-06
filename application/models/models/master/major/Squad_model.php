<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Squad_Model extends CI_Model {
	const T_MAJOR= 'major';
	const T_SQUAD= 'squad';
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
	function count($condition,$majorid) {

		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
 			$this->db->where('majorid',$majorid);
			return $this->db->from ( self::T_SQUAD)->count_all_results ();
		}
		return 0;
	}

	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition,$majorid) {
		if (is_array ( $field ) && ! empty ( $field )) {
			$this->db->select ( str_replace ( " , ", " ", implode ( "`, `", $field ) ) );
			// $this->db->select('squad.nowterm,squad.id,squad.name,squad.majorid,major.name as mname,squad.englishname,squad.classtime,squad.spacing,squad.maxuser,squad.state');
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			// $this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_SQUAD.'.majorid');
			$this->db->where('majorid',$majorid);
			$this->db->group_by('nowterm');
			return $this->db->get ( self::T_SQUAD )->result ();
		}
		return array ();
	}
	function get_nowterm_squad($nowterm,$majorid){
		$this->db->select('squad.nowterm,squad.id,squad.name,squad.majorid,major.name as mname,squad.englishname,squad.classtime,squad.spacing,squad.maxuser,squad.state');
		$this->db->where('nowterm',$nowterm);
		$this->db->where('majorid',$majorid);
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_SQUAD.'.majorid');
		return $this->db->get(self::T_SQUAD)->result_array();

	}
	function get_majorinfo($majorid){
		$this->db->where('id',$majorid);
		return $this->db->get(self::T_MAJOR)->row();
	}

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
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save($id = null, $data = array()) {
		$data['classtime']=strtotime($data['classtime']);
		if (! empty ( $data )) {
			if ($id == null) {
				if($this->db->insert ( self::T_SQUAD, $data )){
					
					$this->major_num($data['majorid']);
					return 1;
					}
			} else {
				$this->db->update ( self::T_SQUAD, $data, 'id = ' . $id );
			}
		}
	}
	function major_num($id){
		if($id!=null){
			$this->db->select('count(*) as squadnum');
			$this->db->where('majorid',$id);
			$num=$this->db->get ( self::T_SQUAD )->row_array ();
			
			return $this->db->update ( self::T_MAJOR, $num, 'id = ' . $id );
		}
		
	}
	/**
	 * 删除一条
	 *
	 * @param $where       	
	 */
		function delete($id) {
		if ($id!= null) {
			$where='id='.$id;
			$data=$this->get_one($where);
			
			if($this->db->delete ( self::T_SQUAD, 'id='.$id)){
				$this->major_num($data->majorid);
				return true;
				}
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
				$base = $this->db->where ($where)->limit(1)->get(self::T_SQUAD)->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 *
	 *获取专业名字
	 **/
	function get_majorname($id){
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_MAJOR)->row_array();
		return $data['name'];
	}
	/**
	 *
	 * 删除关联的课程关系表
	 */
	function delete_guanlian($id){
		if ($id != null) {
			$this->db->delete ( self::T_PAIKE, 'squadid = ' . $id );
		}
		return false;
	}
}