<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Order_Model extends CI_Model {
	const T_A_O_I = 'apply_order_info';
	const T_STU_INFO = 'student_info';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 统计申请条数
	 *
	 * @param string $where
	 */
	function count_apply($where = null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_A_O_I )->count_all_results ();
	}
	
	/**
	 * 获取申请信息
	 *
	 * @param string $where
	 *        	条件
	 * @param number $limit
	 *        	偏移量
	 * @param number $offset
	 * @param string $orderby
	 *        	排序
	 * @author z.junjie 2014-6-28
	 */
	function get_apply($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		return $this->db->order_by ( $orderby )->get ( self::T_A_O_I )->result ();
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
	 * 获取用户名称
	 */
	function get_username($userid = null, $ordertype = null) {
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$str = '';
		if ($userid != null && $ordertype != null) {
			if ($ordertype == 6) {
				$this->db->select ( '*' );
				$this->db->where ( 'id', $userid );
				$data = $this->db->get ( 'student' )->row_array ();
				$name = ! empty ( $data ['name'] ) ? $data ['name'] : '--';
				$email = ! empty ( $data ['email'] ) ? $data ['email'] : '--';
				$nationality = ! empty ( $data ['nationality'] ) ? $nationality [$data ['nationality']] : '--';
				$mobile = ! empty ( $data ['mobile'] ) ? $data ['mobile'] : '--';
				$passport = ! empty ( $data ['passport'] ) ? $data ['passport'] : '--';
			} else {
				$this->db->select ( '*' );
				$this->db->where ( 'id', $userid );
				$data = $this->db->get ( self::T_STU_INFO )->row_array ();
				$name = ! empty ( $data ['chname'] ) ? $data ['chname'] : '--';
				$email = ! empty ( $data ['email'] ) ? $data ['email'] : '--';
				$nationality = ! empty ( $data ['nationality'] ) ? $nationality [$data ['nationality']] : '--';
				$mobile = ! empty ( $data ['mobile'] ) ? $data ['mobile'] : '--';
				$passport = ! empty ( $data ['passport'] ) ? $data ['passport'] : '--';
			}
			
			$str .= '姓名：' . $name . '<br />';
			$str .= '邮箱：' . $email . '<br />';
			$str .= '国籍：' . $nationality . '<br />';
			$str .= '手机号：' . $mobile . '<br />';
			$str .= '护照：' . $passport . '<br />';
		}
		return $str;
	}
	/**
	 * 获取订单的类型
	 */
	function get_ordertype($typeid) {
		switch ($typeid) {
			case 1 :
				return '申请费';
				break;
			case 2 :
				return '代付费';
				break;
			case 3 :
				return '接机费';
				break;
			case 4 :
				return '住宿费';
				break;
			case 5 :
				return '押金';
				break;
			case 6 :
				return '学费';
				break;
			case 7 :
				return '电费';
				break;
			case 8 :
				return '书费';
				break;
			case 9 :
				return '保险费';
				break;
			case 10 :
				return '住宿押金';
				break;
		}
	}
	// 获取支付方式
	function get_paytype($typeid) {
		switch ($typeid) {
			case 1 :
				return 'paypal';
				break;
			case 2 :
				return 'payease';
				break;
			case 3 :
				return '凭据';
				break;
		}
	}
	// 获取支付状态
	function get_paystate($stateid) {
		switch ($stateid) {
			case 0 :
				return '未支付';
				break;
			case 1 :
				return '成功';
				break;
			case 2 :
				return '失败';
				break;
			case 3 :
				return '待审核';
				break;
		}
	}
	/**
	 * 获取 金额 和单位
	 *
	 * @param string $applyid        	
	 * @param string $ordertype        	
	 */
	function get_money($applyid = null, $ordertype = null) {
		$table = array (
				'1' => 'apply_info',
				'3' => 'pickup_info',
				// '4' => 'accommodation_info',
				'4' => 'quarterage_info',

				'5' => 'deposit_info',
				'6' => 'tuition_info' ,
				'7'=>'electric_info',
				'8'=>'books_fee',
				'9'=>'insurance_info',
				'10'=>'acc_pledge_info'
		);
		$str = '';
		if ($applyid != null && $ordertype != null) {
			if ($ordertype == 1) {
				$this->db->select ( 'registration_fee,danwei' );
				$this->db->where ( 'id', $applyid );
				$data = $this->db->get ( $table [$ordertype] )->row_array ();
				
				if ($data ['danwei'] == 1) {
					$danwei = 'USD';
				} else {
					$danwei = 'RMB';
				}
				$str .= $data ['registration_fee'] . ' ' . $danwei;
			}elseif($ordertype == 6){
				$this->db->select ( 'tuition' );
				$this->db->where ( 'id', $applyid );
				$data = $this->db->get ( $table [$ordertype] )->row_array ();
				$danwei = 'RMB';
				$str .= $data ['tuition'] . ' ' . $danwei;	
			}elseif($ordertype == 4||$ordertype==7||$ordertype==8||$ordertype==10){
				$this->db->select ( 'pay' );
				$this->db->where ( 'id', $applyid );
				$data = $this->db->get ( $table [$ordertype] )->row_array ();
				$danwei = 'RMB';
				$str .= $data ['pay'] . ' ' . $danwei;
			}elseif($ordertype == 9){
				$this->db->select ( 'premium' );
				$this->db->where ( 'id', $applyid );
				$data = $this->db->get ( $table [$ordertype] )->row_array ();
				$danwei = 'RMB';
				$str .= $data ['premium'] . ' ' . $danwei;
			} else {
				$this->db->select ( 'registeration_fee,danwei' );
				$this->db->where ( 'id', $applyid );
				$data = $this->db->get ( $table [$ordertype] )->row_array ();
				
				if ($data ['danwei'] == 1) {
					$danwei = 'USD';
				} else {
					$danwei = 'RMB';
				}
				$str .= $data ['registeration_fee'] . ' ' . $danwei;
			}
		}
		return $str;
	}
}