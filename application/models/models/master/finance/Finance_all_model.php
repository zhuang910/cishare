<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Finance_all_Model extends CI_Model {
	const T_A_O_I = 'apply_order_info';
	const T_STU_INFO='student_info';

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
			
			return $this->db->from ( self::T_A_O_I )->count_all_results ();
		}
		return 0;
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
			return $this->db->get ( self::T_A_O_I )->result ();
		}
		return array ();
	}
	/**
	 *
	 *获取用户名称
	 **/
	function get_username($userid){
		$this->db->select('chname');
		$this->db->where('id',$userid);
		$data= $this->db->get(self::T_STU_INFO)->row_array();
		return $data['chname'];
	}
	/**
	 *
	 *获取订单的类型
	 **/
	function get_ordertype($typeid){
		switch ($typeid) {
			case 1:
				return '申请费';
				break;
			case 2:
				return '代付费';
				break;
			case 3:
				return '接机费';
				break;
			case 4:
				return '住宿费';
				break;
			case 5:
				return '押金';
				break;
			
		}
	}
	//获取支付方式
	function get_paytype($typeid	){
		switch ($typeid) {
				case 1:
					return 'paypal';
					break;
				case 2:
					return 'payease';
					break;
				case 3:
					return '凭据';
					break;
			}
	}
	//获取支付状态
	function get_paystate($stateid){
		switch ($stateid) {
			case 0:
				return '未支付';
				break;
			case 1:
				return '成功';
				break;
			case 2:
				return '失败';
				break;
			case 3:
				return '待审核';
				break;
		}
	}
}