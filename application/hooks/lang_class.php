<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 识别语言
 *
 * @author JJ
 *        
 */
class lang_class extends CI_Controller{
	
	function set_lang() {
		
		// 从Uri中分解出当前的语言，如 '', 'en' 或 'ch'
		$my_lang = $this->uri->segment ( 1 );
		
		$get_lang = 'english';
		switch ($my_lang) {
			case 'cn' :
				$get_lang = 'chinese';
				break;
			default :
				break;
		}
		
		$this->config->set_item ( 'language', $get_lang );
		$this->config->set_item ( 'post_lang', '_' . $get_lang );
		
		$this->load->helper ( 'language' );
	}
}