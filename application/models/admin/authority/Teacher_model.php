<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zhuangqianlin
 *        
 */
class Teacher_Model extends CI_Model {
	const T_ARTICLE = 'teacher';
	const T_ADMIN = 'admin_info';
	
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
	 * 获取群组的名称
	 */
	function get_group($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->get ( self::T_G )->result_array ();
			
			if ($base) {
				foreach ( $base as $k => $v ) {
					$data [$v ['id']] = $v ['title'];
				}
				
				return $data;
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
		unset($data['createip']);
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_ARTICLE, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_ARTICLE, $data, 'id = ' . $id );
				//获取userid
				$userid=$this->get_teacher_userid($id);
				return $userid;
			}
		}
	}
	/**
	 * 保存用户基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_admin($id = null, $data = array()) {
		unset($data['sex']);
		$data['mobile']=$data['phone'];
		unset($data['phone']);
		$data['groupid']=4;
		$data['nikename']=$data['name'];
		unset($data['name']);
		if(!empty($data['post'])){
				unset($data['post']);
			}
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_ADMIN, $data );
				return $this->db->insert_id ();
			} else {

				return $this->db->update ( self::T_ADMIN, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 * [insert_admin 插入管理员表]
	 * @return [type] [description]
	 */
	function insert_admin($data){
		if(!empty($data)){
			$this->db->insert ( self::T_ADMIN, $data );
			return $this->db->insert_id ();
		}
		return '';
	}
	/**
	 *获取userid
	 **/
	function get_teacher_userid($id){
		$this->db->select('userid');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_ARTICLE)->row_array();
		return $data['userid'];
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
			), 'id = ' . $id );
		}
	}
	/**
	 * 删除admin
	 */
	function delete_admin($id) {
		if ($id != null) {
			$this->db->delete ( self::T_ADMIN, 'id='.$id );
			return true;
		}
		return false;
	}
	/**
	 * 删除
	 */
	function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_ARTICLE, $where );
			return true;
		}
		return false;
	}
	/**
	 * 导出模板的字段
	 */
	function get_teacher_fields(){
		return array(
			'name'=>'姓名',
			'englishname'=>'英文名字',
			'username'=>'用户名',
			'password'=>'密码',
			'sex'=>'性别',
			'tel'=>'电话',
			'email'=>'邮箱',
			'phone'=>'手机',
			'post'=>'职称',
			'content'=>'简介',
			'introduce'=>'介绍',
			'state'=>'状态'
			);
	}
	/**
	 * [checke_email 检查邮箱老师表是否存在数据库]
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	function checke_email($email){
		if(!empty($email)){
			$this->db->select('count(*) as num');
			$this->db->where('email',$email);
			$data=$this->db->get(self::T_ARTICLE)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * [checke_email 检查邮箱管理员表是否存在数据库]
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	function checke_admin_email($email){
		if(!empty($email)){
			$this->db->select('count(*) as num');
			$this->db->where('email',$email);
			$data=$this->db->get(self::T_ADMIN)->row_array();
			return $data['num'];
		}
		return 1;
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into zust_teacher ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}
	/**
	 * [checke_username 检查用户名是否存在]
	 * @return [type] [description]
	 */
	function checke_username($user){
		if(!empty($user)){
			$this->db->select('count(*) as num');
			$this->db->where('username',$user);
			$t=$this->db->get(self::T_ARTICLE)->row_array();
			$this->db->select('count(*) as num');
			$this->db->where('username',$user);
			$a=$this->db->get(self::T_ADMIN)->row_array();
			$num=$t['num']+$a['num'];
			return $num;
		}
		return 1;
	}	
}