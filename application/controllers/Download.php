<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * Created by CUCAS TEAM.
 * User: Junjie Zhang
 * E-Mail: zhangjunjie@cucas.cn
 * Date: 14/12/22
 * Time: 下午2:55
 */
class Download extends CUCAS_Ext
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper('download');

		$path = trim($this->input->get_post('path'));
		$file = trim($this->input->get_post('file'));

		if (!empty($path)) {
			$file_path = BASEPATH . '..' . $path;

			if (file_exists($file_path)) {
				$data = file_get_contents($file_path);
				if(empty($file)){
					$file = basename($file_path);
				}
				force_download($file, $data);
			}
		}

	}
}