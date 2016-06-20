<?php
/**
 * 日志类
 * 
 * @author: zyj
 */
class Adminlog {
	public function __construct() {
		$CI = & get_instance ();
		$CI->load->model ( 'admin/authority/log_model' );
	}
	
	/**
	 *
	 * @param string $groupid
	 *        	所属群组的id
	 * @param string $fun
	 *        	当时访问的方法
	 */
	public function savelog($data = null) {
		$CI = & get_instance ();
		if ($data != null) {
			$result = $CI->log_model->save ( $data );
			if (! empty ( $result )) {
				return true;
			} else {
				return false;
			}
		}
	}
}
?>
