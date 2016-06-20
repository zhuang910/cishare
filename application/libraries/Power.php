<?php
/**
 * @author: zyj
 */
class Power {
	public function __construct() {
		$CI = & get_instance ();
		$CI->load->model ( 'admin/power/power_model' );
	}
	
	/**
	 *
	 * @param string $groupid
	 *        	所属群组的id
	 * @param string $fun
	 *        	当时访问的方法
	 */
	public function checkpower($groupid = null, $fun = null) {
		$CI = & get_instance ();
		if ($groupid != null && $fun != null) {
			$result = $CI->power_model->get_power ( 'groupid = ' . $groupid );
			if (! empty ( $result )) {
				if (in_array ( $fun, $result )) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 返回无权限
	 */
	function _no_access() {
		$CI = & get_instance ();
		if ($CI->input->is_ajax_request () === true) {
			ajaxReturn ( '', '没有权限', 0 );
		} else {
			$this->_alert ( '没有权限', 0, 3 );
		}
	}
	
	/**
	 * 提示页面
	 *
	 * @param string $msg
	 *        	消息
	 * @param number $state
	 *        	状态 1 成功 2 错误
	 * @param number $sleep
	 *        	等待跳转时间 默认 3秒
	 */
	function _alert($msg, $state = 0, $sleep = 3) {
		$CI = & get_instance ();
		exit ( $CI->load->view ( 'admin/public/alert', array (
				'msg' => $msg,
				'jump' => '/admin/core/login',
				'state' => $state,
				'sleep' => $sleep 
		), true ) );
	}
}
?>
