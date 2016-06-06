<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 栏目管理
 *
 * @author zyj
 *        
 */
class Column_Model extends CI_Model {
	const T_COLUMN='ci_column_info';
	const T_MODULE='ci_module_info';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 *获取栏目列表
	 **/
	function get_column_info(){
		$this->db->select('column_info.*,module_info.title as mtitle');
		$this->db->join ( self::T_MODULE, self::T_MODULE . '.id=' . self::T_COLUMN . '.module_id' );
		$this->db->order_by('column_info.orderby ASC ,column_info.id ASC');
		return $this->db->get(self::T_COLUMN)->result_array();
	}

	/**
	 *获取模型信息
	 **/
	function get_module_info(){
		return $this->db->get(self::T_MODULE)->result_array();
	}
	/**
	 *检查是否含有子栏目
	 **/
	function is_sub_column_num($parent_id){
		if(!empty($parent_id)){
			$this->db->select('count(*) as num');
			$this->db->where('parent_id',$parent_id);
			$data=$this->db->get(self::T_COLUMN)->row_array();
			if($data['num']>0){
				return 1;
			}else{
				return 0;
			}
		}
		return false;
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T_COLUMN, 'id = ' . $id, 1, 0 )->row ();
		}
	}
	/**
	 * 删除
	 *
	 * @param number $menuid        	
	 */
	function delete($id = 0) {
		if ($id) {
			return $this->db->delete ( self::T_COLUMN, 'id = ' . $id );
		}
	}
	
	/**
	 * 保存
	 */
	function save($id = null, $data) {
		if (! empty ( $id )) {
			return $this->db->update ( self::T_COLUMN, $data, 'id = ' . $id );
		} else {
			$this->db->insert ( self::T_COLUMN, $data );
			return $this->db->insert_id();
		}
	}
	/**
	 *排序
	 **/
	function update_orderby($data){
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$arr=explode('_', $k);
				$update['orderby']=$v;
				$this->db->update ( self::T_COLUMN, $update, 'id = ' . $arr[1] );
			}
		}
		return 1;
	}
	/**
	 *获取菜单栏目数组
	 **/
	function get_column_info_nav(){
		$arr_list=$this->get_column_list();
		// var_dump($arr_list);exit;
		//验证是否有权限
		$arr=$this->get_authority_arr($arr_list);
		$array=menu_tree(0,$arr,'id','parent_id');
		return $array;
	}
	//获取uri3  判断选中状态
	function get_uri_three(){
		$arr_list=$this->get_column_list();
		$arr=$this->get_authority_arr($arr_list);
		$uri=array();
		foreach ($arr as $k => $v) {
			if(!empty($v['admin_menu'])){
				$address=explode('/', $v['admin_menu']);
				$uri[$v['id']]=$v['id'];
			}
		}
		
		return $uri;
	}	
	//获取菜单列表	
	function get_column_list(){
		$this->db->where('isshow',1);
		$this->db->where('state',1);
		return $this->db->get(self::T_COLUMN)->result_array();
	}
	//获取有权限的栏目
	function get_authority_arr($arr){
		$array=array();
		foreach ($arr as $k => $v) {
			if(!empty($_SESSION['power']) && in_array(md5($v['id']+10000), $_SESSION['power'])){
				$array[]=$v;
			}
		}
		return $array;
	}
	//获取所有的栏目
	function get_column_infos(){
		return $this->db
			->select('a.*,b.unique')
			->from(self::T_COLUMN.' a')
			->join(self::T_MODULE .' b','a.module_id = b.id','left')
			->where('a.isshow = 1 AND a.state = 1')
			->order_by('a.orderby ASC,a.id ASC')
			->get()
			->result_array();
	}
}