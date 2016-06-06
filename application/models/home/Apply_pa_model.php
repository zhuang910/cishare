<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *         在线申请
 *        
 */
class Apply_pa_Model extends CI_Model {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取所有
	 */
	function get() {
		$this->db->order_by ( 'lasttime ASC' );
		$this->db->group_by ( 'applyid' );
		return $this->db->get ( self::T_APPLY )->result ();
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $catid        	
	 */
	function get_one($applyid = null) {
		if ($applyid != null) {
			return $this->db->get_where ( self::T_APPLY, 'applyid = ' . $applyid, 1, 0 )->row ();
		}
	}
	
	/**
	 * 获取一条 后太深情模板信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_template($where = null) {
		if ($where != null) {
			return $this->db->get_where ( self::T_APPLY_T, $where, 1, 0 )->row ();
		}
	}
	
	/**
	 * 获取模板 与快的关系
	 *
	 * @param number $catid        	
	 */
	function get_template_block_relation($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'orderby ASC' )->get ( self::T_APPLY_T_B )->result_array ();
		}
	}
	
	/**
	 * 获取块 与项之间的关系
	 *
	 * @param number $catid        	
	 */
	function get_template_block_form_relation($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'orderby ASC' )->get ( self::T_APPLY_B_F_R )->result_array ();
		}
	}
	
	/**
	 * 获取所有的附件信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_attachment($where = null) {
		if ($where != null) {
			$data = $this->db->where ( $where )->get ( self::T_APPLY_ATTACHMENT )->result_array ();
			if (! empty ( $data )) {
				return $data [0];
			}
		}
	}
	
	/**
	 * *
	 * 获取 附件模版信息
	 */
	function get_attachmentstopic($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'line DESC' )->get ( self::T_ATT )->result_array ();
		}
		return false;
	}
	
	/**
	 * 获取用户提交的附件的信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_attachment_info($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_APPLY_ATTACHMENT_INFO )->result_array ();
		}
	}
	
	/**
	 * 保存前台上穿的附件信息
	 */
	function save_upload_attachment($data = null) {
		if ($data != null) {
			$this->db->insert ( self::T_APPLY_ATTACHMENT_INFO, $data );
			return $this->db->insert_id ();
		}
		return false;
	}
	
	/**
	 * 删除前台用户上传的信息
	 */
	function del_upload_attachment($where = null) {
		if ($where !== null) {
			return $this->db->delete ( self::T_APPLY_ATTACHMENT_INFO, $where );
		}
		return false;
	}
	
	/**
	 * 获取用户填写的信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_template_info($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_APPLY_T_INFO )->result ();
		}
	}
	
	/**
	 * 删除
	 *
	 * @param number $slideid        	
	 */
	function del_apply_template_info($where = null) {
		if ($where !== null) {
			return $this->db->delete ( self::T_APPLY_T_INFO, $where );
		}
		return false;
	}
	
	/**
	 * 获取块 的信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_block($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_APPLY_BLOCK )->result_array ();
		}
	}
	
	/**
	 * 获取项 的信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_form($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->get ( self::T_APPLY_FORM )->result_array ();
		}
	}
	
	/**
	 * 获取项 的值的信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_form_item($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'orderby ASC' )->get ( self::T_APPLY_FORM_ITEM )->result_array ();
		}
	}
	
	/**
	 * 获取一条
	 * 接机 或是 住宿
	 */
	function get_apply_info($where = null, $table = null) {
		if ($where != null && $table != null) {
			$data = $this->db->where ( $where )->order_by ( 'id DESC' )->limit ( 1 )->get ( $table )->result_array ();
			
			if (! empty ( $data )) {
				return $data [0];
			}
			return false;
		}
		return false;
	}
	
	/**
	 * 获取申请表信息
	 */
	function get_apply_more_info($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'id DESC' )->get ( self::T_APPLY )->result_array ();
		}
	}
	
	/**
	 * 保存到申请表
	 */
	function save_apply_info($where = null, $data = array(), $table = null) {
		if ($where != null && $table != null) {
			return $this->db->update ( $table, $data, $where );
		} else {
			return $this->db->insert ( $table, $data );
		}
		return false;
	}
	
	/**
	 * 保存
	 *
	 * @param number $catid        	
	 * @param array $data        	
	 */
	function save($applyid = '', $data = array()) {
		if (! empty ( $applyid )) {
			return $this->db->update ( self::T_APPLY, $data, 'applyid = ' . $applyid );
		} else {
			return $this->db->insert ( self::T_APPLY, $data );
		}
		return false;
	}
	
	/**
	 * 保存
	 *
	 * 用户信息
	 */
	function save_apply_template_info($data = array()) {
		if (! empty ( $data )) {
			
			return $this->db->insert ( self::T_APPLY_T_INFO, $data );
		}
		return false;
	}
	
	/**
	 * 保存
	 *
	 * 历史
	 */
	function save_apply_history($data = array()) {
		if (! empty ( $data )) {
			
			return $this->db->insert ( self::T_APPLY_H, $data );
		}
		return false;
	}
	
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_base($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_APPLY, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_APPLY, $data, 'applyid = ' . $id );
			}
		}
	}
	
	/**
	 * 删除
	 *
	 * @param number $slideid        	
	 */
	function del_apply($where = null) {
		if ($where !== null) {
			return $this->db->delete ( self::T_APPLY, $where );
		}
		return false;
	}
	
	/**
	 * 删除
	 *
	 * @param number $slideid        	
	 */
	function delete($applyid = null) {
		if ($applyid !== null) {
			return $this->db->delete ( self::T_APPLY, 'applyid = ' . $applyid );
		}
		return false;
	}
}