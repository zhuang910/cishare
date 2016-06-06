<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 *
 * @name 申请管理-全部申请
 * @package apply
 * @author cucas Team [ZD]
 * @copyright Copyright (c) 2014-3-10, cucas
 *           
 */
class appdocument extends Master_Basic {
	/**
	 * 全部申请
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'process/';
		$this->load->model ( $this->view . 'appdocument_model' );
	}
	
	// 初始化
	function index() {
		$id = trim ( $this->input->get ( 'id' ) ); // 获取申请ID值
		$type = trim ( $this->input->get ( 'type' ) ); // 获取类型
		
		if (! empty ( $id )) {
			if ($type == 'online') { // 在线查看附件，返回JSON数组
				$data = $this->online_document ( $id );
			}
			if ($type == 'download') { // 下载附件，返回下载链接
				$data = $this->download_document ( $id );
			}
			echo json_encode ( $data );
		} else {
			echo json_encode ( array (
					'status' => 'N' 
			) );
		}
	}
	// 在线查看附件
	function online_document($id) {
		$OAADMIN = $this->load->database ( 'cucas', true );
		// $lists_app = $this->appdocument_model->get_app_one($id); //根据申请ID查找申请数据
		$str = '';
		$data = array ();
		$encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) );
		$str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
		
		if (! empty ( $str ['apply_attachment'] )) {
			foreach ( $str ['apply_attachment'] as $k => $v ) {
				$v['data'] = isset($v['data'][0]) ? $v['data'][0] : $v['data'];
				$dataF = $OAADMIN->where ( array (
						'aTopic_id' => $v ['attid'] 
				) )->get ( 'attachmentstopic' )->result_array ();
				
				$data [$k] = array (
						'link' => $v ['data'] ['url'],
						'src' => $v ['data']['thumbnailUrl'],
						'title' => $v ['data'] ['truename'] 
				);
			}
		}
		
		return $data;
	}
	
	/**
	 * 下载附件
	 */
	function check_upload() {
		$id = trim ( $this->input->get ( 'id' ) );
		$courseid = trim ( $this->input->get ( 'courseid' ) );
		$schoolid = trim ( $this->input->get ( 'schoolid' ) );
		$OAADMIN = $this->load->database ( 'cucas', true );
		// $lists_app = $this->appdocument_model->get_app_one($id); //根据申请ID查找申请数据
		$str = '';
		$data = array ();
		$encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) );
		$str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
		
		$appid = $OAADMIN->select ( 'attatemplate' )->where ( 'id = ' . $courseid )->get ( 'course' )->result_array ();
		$applyid = $appid [0] ['attatemplate'];
		$dataF = $OAADMIN->where ( array (
				'atta_id' => $applyid 
		) )->get ( 'attachmentstopic' )->result_array ();
		
		if (! empty ( $str ['apply_attachment'] )) {
			foreach ( $str ['apply_attachment'] as $k => $v ) {
				$v['data'] = isset($v['data'][0]) ? $v['data'][0] : $v['data'];
				$data [$v ['attid']] = array (
						'link' => ! empty ( $v ['data'] ['url'] ) ? $v ['data'] ['url'] : '',
						'src' => ! empty ( $v ['data'] ['thumbnailUrl'] ) ? $v ['data'] ['thumbnailUrl'] : '',
						'title' => ! empty ( $v ['data'] ['truename'] ) ? $v ['data'] ['truename'] : '',
						'name' => ! empty ( $dataF [0] ['TopicName'] ) ? $dataF [0] ['TopicName'] : '',
						'time' => ! empty ( $v ['time'] ) ? $v ['time'] : '' 
				);
			}
		}
		$this->load->vars ( 'dataF', $dataF );
		$this->load->vars ( 'data', $data );
		$html = $this->_view ( 'check_upload', '', true );
		ajaxReturn ( $html, '', 1 );
	}
	
	// 打包下载附件
	function download_document($id) {
		$this->online_document ( $id );
		$lists_teacher = $this->appdocument_model->get_teacher ( $id ); // 查找教师数据
		$data ['info'] = $lists_teacher;
		$data ['status'] = 'Y';
		$data ['type'] = "2";
	}
	
	/**
	 * 打包下载
	 */
	function download() {
		$id = trim ( $this->input->get ( 'id' ) ); // 获取申请ID值
		$this->load->library ( 'zip' );
		$str = '';
		$data = array ();
		$encrpt_str = cucas_base64_encode ( authcode ( $id, 'ENCODE', 'mongo_userinfo', 0 ) );
		$str = json_decode ( file_get_contents ( 'http://apply.' . DOHEAD . 'cucas.edu.cn/sync_userinfo/get_by_mongo/' . $encrpt_str ), true );
		
		if (! empty ( $str ['apply_attachment'] )) {
			foreach ( $str ['apply_attachment'] as $k => $v ) {
				$v['data'] = isset($v['data'][0]) ? $v['data'][0] : $v['data'];
				if (! empty ( $v['data'] )) {
					
					$data = file_get_contents ( '/home/www-root/newcucas' . $v ['data'] ['url'] );
					// $type = explode ( '.', $v ['data'] [0] ['url'] );
					$name = mb_convert_encoding ( $v ['data'] ['truename'], 'GBK', 'UTF-8' );
					$this->zip->add_data ( $name, $data );
				}
				
				// $name = mb_convert_encoding ( $v ['data'] [0] ['truename'] . ' - 简历编号 ' . $v . '.doc', 'GBK', 'UTF-8' );
				
				// $data [$k] = array (
				// 'link' => $v ['data'] [0] ['url'],
				// 'src' => $v ['data'] [0] ['thumbnailUrl'],
				// 'title' => $v ['data'] [0] ['truename']
				// );
			}
			
			$filezip = $this->zip->get_zip ( 'my_backup.zip' );
			$this->load->helper ( 'download' );
			
			force_download ( 'cucas.zip', $filezip );
		}
	}
}