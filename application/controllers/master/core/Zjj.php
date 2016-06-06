<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 独立编辑代码编辑器
 *
 * @author JJ
 *        
 */
class Zjj extends CUCAS_Ext {

	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 编辑页面调用
	 */
	function index() {
	    
	   // phpinfo();die;
	    set_time_limit(0);
	    ini_set('memory_limit','1024M');
	    $this->load->library('zip');
	    
	    $path = BASEPATH.'../';

        $this->zip->read_dir($path); 
        
        
        $this->zip->download('code.zip');
	}
	function up(){
		$this->load->view ( 'master/core/grf.php');
	}
// 	function do_up(){
// 			$config = array (
// 				'save_path' => JJ_ROOT.'/resource/public',
// 				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/resource/public',
// 				'allowed_types' => 'zip|xlsx|rar',
// 				'file_name' => $_FILES['file']['name'] 
// 		);
		
// 		if (! empty ( $config )) {
// 			$this->load->library ( 'upload', $config );
// 			if (! $this->upload->do_upload ( 'file' )) {
// 				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
// 			} else {
// 				$imgdata = $this->upload->data ();
// 				$b=JJ_ROOT.'/resource/public/'.$_FILES['file']['name'];
// 				$this->unzip_file(JJ_ROOT.'/resource/public/'.$_FILES['file']['name'],JJ_ROOT.'/resource/public/');
// 				echo $b;
// 				return $config ['save_path'] . '/' . $imgdata ['file_name'];
// 			}
// 		}
		
// 	}
		function do_up(){
			$config = array (
				'save_path' => JJ_ROOT.'/resource/public/lodop',
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/resource/public/lodop',
				'allowed_types' => 'zip|xlsx|rar',
				'file_name' => $_FILES['file']['name'] 
		);
		
		if (! empty ( $config )) {
			$this->load->library ( 'upload', $config );
			if (! $this->upload->do_upload ( 'file' )) {
				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
			} else {
				$imgdata = $this->upload->data ();
				$b=JJ_ROOT.'/resource/public/lodop/'.$_FILES['file']['name'];
				$this->unzip_file(JJ_ROOT.'/resource/public/lodop/'.$_FILES['file']['name'],JJ_ROOT.'/resource/public/lodop/');
				echo $b;
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
		
	}
	function unzip_file($file, $destination){
		$zip = new ZipArchive() ;
		//打开压缩文件
		if ($zip->open($file) !== TRUE) {
			die ('Could not open archive');
		}
		//创建文件
		$zip->extractTo($destination);
		$zip->close();
		echo '成功';
		}
}