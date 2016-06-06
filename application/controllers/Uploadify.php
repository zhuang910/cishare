<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 上传类
 *
 * @author JJ
 *        
 */
class Uploadify extends CI_Controller {
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 开始上传
	 */
	function do_upload() {
		$verifyToken = md5 ( 'unique_salt' . $_POST ['timestamp'] );
		if (! empty ( $_FILES ) && $_POST ['token'] == $verifyToken) {
			set_time_limit(0);
			$config ['upload_path'] = UPLOADS . 'apply/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
			mk_dir ( $config ['upload_path'] );
			$config ['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|rar|zip';
			$config ['max_size'] = '8192';
			$config ['file_name'] = build_order_no ();
			$save_path = '/uploads/apply/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
			
			$this->load->library ( 'upload', $config );
			
			if (! $this->upload->do_upload ( 'files' )) {
				exit ( $this->upload->display_errors ( '', '' ) );
			} else {
				$r = $this->upload->data ();
				$data = array (
						'name' => $r ['file_name'],
						'size' => $r ['file_size'],
						'type' => $r ['file_type'],
						'ext' => $r ['file_ext'],
						'url' => $save_path . $r ['orig_name'],
						'truename' => $r ['client_name'] 
				);
				exit ( json_encode ( $data ) );
			}
		}
	}
}