<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-10-16
 * Time: 下午1:26
 */

defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 登陆
 *
 * @author zhuangqianlin
 *
 */
class Uploads extends CUCAS_Ext
{
	function __construct()
	{
		parent::__construct();
		$this->view = 'admin/uploads/';
	}

	/**
	 * 测试页面
	 */
	function test()
	{
		$this->load->view('admin/uploads/test');
	}

	/**
	 * 统一上传入口
	 */
	function index()
	{
		$data = $this->input->get();
		$img_id=!empty($data['img_id'])?$data['img_id']:'';
		$input_name=!empty($data['input_name'])?$data['input_name']:'';
		$file_limit = $data['file_limit'];
		$isthumb = $data['isthumb'];
		$moduleid = $data['moduleid'];
		$sessid = time();
		$isadmin = 0;
		$userid = 1;
		$swf_auth_key = $this->sysmd5($sessid . $userid);
		$file_size = $data['file_size'];
		$file_types = '*.' . str_replace(",", ";*.", $data['file_types']);
		$s = intval($this->input->get('s'));
		// 是否为编辑器调取
		$is_edit = (int)$this->input->get('is_edit');
		$this->load->vars(array(
			'file_limit' => $file_limit,
			'isthumb' => $isthumb,
			'sessid' => $sessid,
			'isadmin' => $isadmin,
			'userid' => $userid,
			'swf_auth_key' => $swf_auth_key,
			'file_size' => $file_size,
			'file_types' => $file_types,
			'small_upfile_limit' => 1,
			'moduleid' => $moduleid,
			'is_edit' => $is_edit,
			'img_id'=>!empty($img_id)?$img_id:'',
			'input_name'=>!empty($input_name)?$input_name:''
		));
		if ($is_edit === 1) {
			$this->_view('index');
		} else {
			$html = $this->_view('index', array(), true);
			ajaxReturn($html, '', 1);
		}
	}

	/**
	 * 开始上传
	 */
	function upload()
	{
		$path = UPLOADS . 'cms/' . date('Ym') . '/' . date('d') . '/';
		$path_web = UPLOADS_WEB . 'cms/' . date('Ym') . '/' . date('d') . '/';
		if (!is_dir($path)) {
			mk_dir($path);
		}
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|jpeg|png|doc|docx|pdf|xls|xlsx|mpg|wmv|avi|wma|mp3|mid|asf|rm|rmvb|wav|wma|mp4|swf|zip|rar';
		$config['max_size'] = '8192';
		$config['file_name'] = build_order_no();
		$this->load->library('upload', $config);
			
		if (!$this->upload->do_upload('filedata')) {
			ajaxReturn(0, $this->upload->display_errors(), 0);
		} else {

			$data = array('upload_data' => $this->upload->data());
			$aid = 11;
			$returndata['aid'] = $aid;
			$returndata['filepath'] = $path_web . $data['upload_data']['orig_name'];
			$returndata['fileext'] = $data['upload_data']['file_ext'];
			$returndata['isimage'] = in_array($data['upload_data']['file_ext'], array('.gif', '.jpg', '.png')) ? 1 : 0;
			$returndata['filename'] = $data['upload_data']['orig_name'];
			$returndata['filesize'] = $data['upload_data']['file_size'];
			ajaxReturn($returndata, '上传成功', '1');
		}
	}

	function sysmd5($str, $key = '', $type = 'sha1')
	{
		$key = $key ? $key : 'f591bddde4eb2575723728112c49f6d3';
		return hash($type, $str . $key);
	}

	function kindeditor()
	{
		$this->load->view('admin/uploads/kindeditor');
	}

	/**
	 * 统一上传入口
	 */
	function indexs()
	{
		$data = $this->input->get();
		$file_limit = $data['file_limit'];
		$isthumb = $data['isthumb'];
		$moduleid = $data['moduleid'];
		$sessid = time();
		$isadmin = 0;
		$userid = 1;
		$swf_auth_key = $this->sysmd5($sessid . $userid);
		$file_size = $data['file_size'];
		$file_types = '*.' . str_replace(",", ";*.", $data['file_types']);;
		$s = intval($this->input->get('s'));
		$this->_view('indexs', array(
			'file_limit' => $file_limit,
			'isthumb' => $isthumb,
			'sessid' => $sessid,
			'isadmin' => $isadmin,
			'userid' => $userid,
			'swf_auth_key' => $swf_auth_key,
			'file_size' => $file_size,
			'file_types' => $file_types,
			'small_upfile_limit' => 1,
			'moduleid' => $moduleid
		));

	}

}