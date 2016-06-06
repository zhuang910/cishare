<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class User_Model extends CI_Model {
	const T_ADMIN = 'admin_info';
	const T_MENU = 'system_menu';
	const T_GROUP_MENU = 'system_group_menu';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取所有管理员
	 */
	function get() {
		// return $this->db->get_where(self::T_ADMIN);
		return $this->db->get_where ( self::T_ADMIN )->result ();
	}
	/**
	 * 登录
	 */
	function dologin($username = null, $lastip = null) {
		if ($username != null) {
			$this->db->update ( self::T_ADMIN, array (
					'lasttime' => time (),
					'lastip' => $lastip 
			), "username ='" . $username . "'" );
		}
	}
	/**
	 * 获取用户基本信息
	 *
	 * @param string $username        	
	 */
	function get_user_info($username = null) {
		if ($username != null) {
			$result = $this->db->get_where ( self::T_ADMIN, 'username = \'' . $username . '\'', 1, 0 )->result ();
			if ($result) {
				return $result [0];
			}
		}
	}
	/**
	 * 获取用户所在组权限
	 *
	 * @param number $userid        	
	 */
	function get_user_authority($groupid = null) {
		if ($groupid != null) {
			$this->db->select ( 'a.*' );
			return $this->db->from ( self::T_MENU . ' a,' . self::T_GROUP_MENU . ' b' )->where ( 'b.groupid = ' . $groupid . ' AND a.pin = b.menupin' )->get ()->result ();
		}
	}
	
	/**
	 * 获取 权限信息 存入到session中
	 */
	function get_power($where = null) {
		if ($where != null) {
			$data = $power = array ();
			$data = $this->db->get_where (self::T_GROUP_MENU, $where )->result_array ();
			if (! empty ( $data )) {
				foreach ( $data as $k => $v ) {
					$power [] = $v ['power'];
				}
				return $power;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 递增密码错误次数
	 *
	 * @param number $userid        	
	 */
	function password_add_errcount($userid = null, $errcont = 0) {
		if ($userid != null) {
			$this->db->update ( self::T_ADMIN, array (
					'errcount' => $errcont + 1 
			), 'adminid = ' . $userid );
		}
	}
	/**
	 * 复位密码错误次数
	 *
	 * @param number $userid        	
	 */
	function password_reset_errcount($userid = null) {
		if ($userid != null) {
			return $this->db->update ( self::T_ADMIN, array (
					'errcount' => 0 
			), 'adminid = ' . $userid );
		}
	}
	/**
	 * 禁用用户
	 *
	 * @param number $userid        	
	 */
	function user_forbidden($userid = null) {
		if ($userid != null) {
			return $this->db->update ( self::T_ADMIN, array (
					'state' => 0 
			), 'adminid = ' . $userid );
		}
	}
	/**
	 * 启用用户
	 *
	 * @param number $userid        	
	 */
	function user_start($userid = null) {
		if ($userid != null) {
			return $this->db->update ( self::T_ADMIN, array (
					'state' => 1 
			), 'adminid = ' . $userid );
		}
	}
	
	/**
	 * 新增管理员
	 *
	 * @param array $data        	
	 */
	function insert($data) {
		if (! empty ( $data )) {
			return $this->db->insert ( self::T_ADMIN, $data );
		}
		return false;
	}
	
	/**
	 * 更新用户信息
	 */
	function update($adminid = null, $data = array()) {
		if ($adminid != null && ! empty ( $data ) && is_array ( $data )) {
			return $this->db->update ( self::T_ADMIN, $data, 'adminid = ' . $adminid );
		}
		return false;
	}
	
	/**
	 * 修改密码
	 *
	 * @param number $adminid        	
	 * @param string $password        	
	 */
	function password_edit($adminid, $rand, $password) {
		if ($adminid && $password) {
			return $this->db->update ( self::T_ADMIN, array (
					'password' => md5 ( $password ) . md5 ( $rand ),
					'salt' => $rand 
			), 'adminid = ' . $adminid );
		}
		return false;
	}
	
	/**
	 * 删除
	 *
	 * @param number $id        	
	 */
	function delete($id = null) {
		if ($id != null) {
			return $this->db->delete ( self::T_ADMIN, 'adminid = ' . $id );
		}
	}
	
	/**
	 * 获取字段
	 */
	function field_info() {
		return $this->db->list_fields ( self::T_ADMIN );
	}
}