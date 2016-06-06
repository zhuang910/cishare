<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Fee_Model extends CI_Model {
	const T_BUDGET = 'budget';//收支表
	const T_ORDER = 'apply_order_info';//所有订单表
	const T_ACC = 'accommodation_info';//住宿主表
	const T_ACC_HISTORY = 'accommodation_history';//住宿历史表
	const T_PLEDGE='acc_pledge_info';//住宿押金表
	const T_APP_REM_TUITION='apply_remission_tuition';//申请减免学费表


	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * [insert_budget 插入收支表]
	 * @return [type] [返回插入id]
	 */
	function insert_budget($arr){
		if(!empty($arr)){
			$this->db->insert(self::T_BUDGET,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_order 插入所有订单表]
	 * @return [type] [返回插入id]
	 */
	function insert_order($arr){
		if(!empty($arr)){
			$this->db->insert(self::T_ORDER,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_acc 插入住宿表]
	 * @return [type] [返回住宿id]
	 */
	function insert_acc($arr){
		if(!empty($arr)){
			$arr['accstarttime']=strtotime($arr['accstarttime']);
			$this->db->insert(self::T_ACC,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_history 插入历史表]
	 * @return [type] [description]
	 */
	function insert_history($arr){
		if(!empty($arr)){
			$this->db->insert(self::T_ACC_HISTORY,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_pledge 插入住宿押金表]
	 * @return [type] [description]
	 */
	function insert_pledge($arr){
		if(!empty($arr)){
			$this->db->insert(self::T_PLEDGE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_apply_remission_tuition 插入申请减免学费表]
	 * @return [type] [description]
	 */
	function insert_apply_remission_tuition($arr){
		if(!empty($arr)){
			$this->db->insert(self::T_APP_REM_TUITION,$arr);
			return $this->db->insert_id();
		}	
	}
}