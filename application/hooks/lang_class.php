<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * ʶ������
 *
 * @author JJ
 *        
 */
class lang_class extends CI_Controller{
	
	function set_lang() {
		
		// ��Uri�зֽ����ǰ�����ԣ��� '', 'en' �� 'ch'
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