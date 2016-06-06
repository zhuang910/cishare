<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 填写表单
 *
 * @author junjiezhang
 *        
 */
class Fillingoutforms_Model extends CI_Model {
	const T_COURSE = 'scholarship_info';
	const T_APPLY = 'applyscholarship_info';
	const T_OLD_FORM_MAIN = 'templateclass';
	const T_OLD_FORM_GROUP = 'formtopic';
	const T_OLD_FORM_ITEM = 'formitem';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取课程信息
	 *
	 * @param number $cid        	
	 */
	function get_course_info($cid = 0) {
		if ($cid) {
			return $this->db->select ( '*' )->where ( 'id = ' . $cid )->limit ( 1 )->get ( self::T_COURSE )->row ();
		}
		return false;
	}
	
	/**
	 * 获取申请表ID
	 */
	function get_form_id($cid = 0) {
		if ($cid) {
			$info = $this->get_course_info ( $cid );
			if ($info) {
				return $info->applytemplate;
			}
		}
		return false;
	}
	
	/**
	 * 获取默认申请表
	 *
	 * @param number $sid        	
	 */
	function get_default_form_id() {
	
			$info = $this->db->select ( 'tClass_id' )->where ( 'classType = 1 AND classKind = \'Y\'' )->limit ( 1 )->get ( 'templateclass' )->row ();
			if (! empty ( $info )) {
				return $info->tClass_id;
			}
	
		return false;
	}
	
	/**
	 * 获取附件表ID
	 */
	function get_attachments_id($cid = 0) {
		if ($cid) {
			$info = $this->get_course_info ( $cid );
			if ($info) {
				return $info->attatemplate;
			}
		}
		return false;
	}
	
	/**
	 * 获取表单
	 */
	function get_form_data($form_id = 0, $type = 2) {
		if ($form_id) {
			return $this->db->select ( 'tClass_id as id,ClassName as name,PageType as type' )->where ( 'parent_id = ' . $form_id . ' AND classType = ' . $type . ' AND classKind = \'N\'' )->order_by ( 'line desc' )->get ( 'templateclass' )->result ();
		}
		return false;
	}
	
	/**
	 * 获取表单项
	 */
	function get_form_item($pid = 0) {
		if ($pid) {
			$item = $this->db->where ( 'Class_id', $pid )->order_by ( 'line desc' )->get ( self::T_OLD_FORM_GROUP )->result ();
			if (! $item)
				return NULL;
			
			foreach ( $item as $info ) {
				$is_child = array (
						4, // 单选
						6  // 下拉
								);
				if (in_array ( $info->formType, $is_child )) {
					$o = $this->db->where ( 'topic_id', $info->topic_id )->get ( self::T_OLD_FORM_ITEM )->result ();
					$info->options = $o;
				}
			}
			return $item;
		}
	}
	
	/**
	 * 获取用户数据库申请信息
	 */
	function get_apply_info($cid = 0, $uid = 0) {
		if ($cid && $uid) {
			
			return $this->db->select ( '*' )->where ( 'userid = ' . $uid . ' AND scholarshipid = ' . $cid )->limit ( 1 )->get ( self::T_APPLY )->row ();
		}
		return false;
	}
}