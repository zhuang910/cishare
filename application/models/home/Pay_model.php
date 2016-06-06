<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *         在线申请
 *        
 */
class Pay_Model extends CI_Model {
	const T_APPLY = 'apply_order_info'; // 订单表
	const T_APPLY_C = 'credentials'; // 凭据表
	const T_APPLY_PAYPAL = 'paypal';//paypal表
	const T_APPLY_HIS = 'apply_history';//申请历史
    const T_ACC='accommodation_info';
    const T_COURSE='course';
    const T_MAJOR_COURSE='major_course';
    const T_COURSE_BOOKS='course_books';
    const T_BOOKS='books';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * [get_major_course 获取该专业的所有课程]
	 * @return [type] [description]
	 */
	function get_major_course($mid){
		if(!empty($mid)){
			$this->db->where('majorid',$mid);
			$this->db->join(self::T_COURSE ,self::T_MAJOR_COURSE.'.courseid='.self::T_COURSE.'.id');
			return $this->db->get(self::T_MAJOR_COURSE)->result_array();
		}
	}
	/**
	 * [get_course_book 获取该课程的书籍]
	 * @return [type] [description]
	 */
	function get_course_book($courseid){
		if(!empty($courseid)){
			$this->db->where('courseid',$courseid);
			return $this->db->get(self::T_COURSE_BOOKS)->result_array();
		}
	}
	/**
	 * [get_book_info 获取书籍信息]
	 * @return [type] [description]
	 */
	function get_book_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_BOOKS)->row_array();
		}
	}



	/**
	 * 保存凭据信息
	 */
	function save_credentials($where = null, $data = null) {
		if ($where != null) {
			return $this->db->update ( self::T_APPLY_C, $data, $where );
		} else {
			return $this->db->insert ( self::T_APPLY_C, $data );
		}
		return false;
	}
	
	/**
	 * 获取凭据xu
	 *
	 * @param number $catid        	
	 */
	function get_credentials($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'id DESC' )->limit ( 1 )->get ( self::T_APPLY_C )->result_array ();
		}
	}
	
	
	/**
	 * 保存paypal表
	 */
	function save_paypal($where = null, $data = null) {
		if ($where != null) {
			return $this->db->update ( self::T_APPLY_PAYPAL, $data, $where );
		} else {
			return $this->db->insert ( self::T_APPLY_PAYPAL, $data );
		}
		return false;
	}
	
	/**
	 * 获取paypal
	 *
	 * @param number $catid
	 */
	function get_paypal($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'id DESC' )->limit ( 1 )->get ( self::T_APPLY_PAYPAL )->result_array ();
		}
	}
	
	/**
	 * 保存申请历史
	 */
	function save_apply_history($where = null, $data = null) {
		if ($where != null) {
			return $this->db->update ( self::T_APPLY_HIS, $data, $where );
		} else {
			return $this->db->insert ( self::T_APPLY_HIS, $data );
		}
		return false;
	}
	
	/**
	 * 获取订单信息
	 *
	 * @param number $catid        	
	 */
	function get_apply_order_info($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by('id DESC')->limit ( 1 )->get ( self::T_APPLY )->result_array ();
		}
	}
	
	/**
	 * 保存订单信息
	 */
	function save_apply_order_info($where = null, $data = array()) {
		if ($where != null) {
			return $this->db->update ( self::T_APPLY, $data, $where );
		} else {
			return $this->db->insert ( self::T_APPLY, $data );
		}
		return false;
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
			return $this->db->where ( $where )->order_by ( 'orderby ASC' )->get ( self::T_APPLY_ATTACHMENT )->result_array ();
		}
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
			return $this->db->insert ( self::T_APPLY_ATTACHMENT_INFO, $data );
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
			return $this->db->where ( $where )->get ( self::T_APPLY_T_INFO )->result_array ();
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
	 * 获取所有
	 */
	function get_apply_info($where = null) {
		if ($where != null) {
			return $this->db->where ( $where )->order_by ( 'id DESC' )->limit ( 1 )->get ( self::T_APPLY )->row ();
		}
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
	function delete($applyid = null) {
		if ($applyid !== null) {
			return $this->db->delete ( self::T_APPLY, 'applyid = ' . $applyid );
		}
		return false;
	}

    /**
     * 获取预定房间的信息
     */
    function get_acc_info($where){
        if(!empty($where)){
            $this->db->where($where);
            return $this->db->get(self::T_ACC)->row_array();
        }
    }
}