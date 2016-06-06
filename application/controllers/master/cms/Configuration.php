<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 基础设置
 *
 * @author zyj
 *        
 */
class Configuration extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 站点语言设置
	 */
	function sitelang() {
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['site_language'];
		$open_l = CF ( 'site_language', '', CONFIG_PATH );
		$this->load->view ( 'master/cms/site_language', array (
				'site_language' => $l,
				'open_l' => $open_l 
		) );
	}
	
	/**
	 * 生成站点语言的缓存
	 */
	function update_site_language() {
		$data = $this->input->post ();
		if ($data['site_language']) {
			$arr=array();
			foreach ($data['site_language'] as $k => $v) {
				$ex=explode('_grf_', $v);
				$arr[$ex[0]]=$ex[1];
			}
			CF ( 'site_language', $arr, CONFIG_PATH );
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '至少选择一种语言', 0 );
		}
	}
	
	/**
	 * 站点语言设置
	 */
	function indexconfig() {
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['index_config'];
		
		$open_l = CF ( 'index_config', '', CONFIG_PATH );
		$this->load->view ( 'master/cms/index_config', array (
				'site_language' => $l,
				'open_l' => $open_l 
		) );
	}
	
	/**
	 * 生成站点语言的缓存
	 */
	function update_index_config() {
		$data = $this->input->post ();
		if ($data) {
			
			CF ( 'index_config', $data ['site_language'], CONFIG_PATH );
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
}

