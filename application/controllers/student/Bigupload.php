<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 填写表单
 *
 * @author junjiezhang
 *        
 */
class Bigupload extends Home_Basic {
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取上传进度
	 */
	function getprogress(){
		if ($this->input->is_ajax_request () === true) {
			$key = $this->input->get ( 'progress_key' );
			if ($key) {
				$status = apc_fetch ( 'upload_' . $key );
				if ($status) {
					echo ceil($status ['current'] / $status ['total'] * 100);
				}else{
					echo 0;
				}
			}
		}
	}
}