<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 申请表
 *
 * @author zyj
 *        
 */
class Upload_attachment extends Master_Basic {
	protected $html_main = null;
	protected $html_left = null;
	protected $html_form = null;
	protected $form_state = array ();
	protected $user_form_data = array ();
	protected $issubmit = 0;
	protected $isstart = 0;
	protected $isapplypay = 1; // 需支付
	protected $applyfee = 0; // 设置的费用
	protected $applydanwei = ''; // 设置的单位
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'home/user_model' );
		$this->load->model ( 'home/apply_model' );
		$this->load->model ( 'home/course_model' );
		$this->load->model ( 'home/fillingoutforms_model' );
		$this->load->model ( 'home/validate_model' );
	}
	
	/**
	 * 提交资料
	 */
	function upload_materials() {
		$applyid = trim ( $this->input->get ( 'applyid' ) );
		$a_id = $applyid;
		if ($applyid) {
			$applyid = intval ( cucas_base64_decode ( $applyid ) );
			$where_apply_info = "id = {$applyid}";
			// 查询数据
			$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
			$where_c = "id = {$apply_info['courseid']} AND state = 1";
			$course = $this->course_model->get_one ( $where_c );
			$coursenames = $this->course_model->get_one ( 'id = ' . $apply_info ['courseid'] );
			// 首先看一下该课程有没有添加附件信息 如果是 空的 就是所有的附件信息
			if (! empty ( $course ['attatemplate'] )) {
				$where_attachment = 'atta_id = ' . $course ['attatemplate'];
			} else {
				$where_attachment = 'atta_id > 1 AND aKind = "Y" AND admin_id = 0';
			}
			$attachment = $this->apply_model->get_apply_attachment ( $where_attachment );
			
			// 填写的附件的信息
			$where_apply_attachment_info = "applyid = {$applyid} AND userid = {$apply_info['userid']}";
			$attachment_info = $this->apply_model->get_apply_attachment_info ( $where_apply_attachment_info );
			$attachment_content = $this->apply_model->get_attachmentstopic ( 'atta_id = ' . $attachment ['atta_id'] );
		}
		$this->load->view ( 'master/enrollment/studentedit/apply_upload_materials', array (
				'courseid' => ! empty ( $apply_info ['courseid'] ) ? $apply_info ['courseid'] : '',
				'attachment' => ! empty ( $attachment ) ? $attachment : array (),
				'attachment_info' => ! empty ( $attachment_info ) ? $attachment_info : array (),
				'apply_info' => ! empty ( $apply_info ) ? $apply_info : array (),
				'attachment_content' => ! empty ( $attachment_content ) ? $attachment_content : '',
				'coursenames' => $coursenames,
				'a_id' => $a_id 
		) );
	}
	
	/**
	 * 保存提交资料
	 */
	function save_upload_atta() {
		$courseid = intval ( trim ( $this->input->post ( 'courseid' ) ) );
		$attachmentid = intval ( trim ( ($this->input->post ( 'attachmentid' )) ) );
		$applyid = trim ( ($this->input->post ( 'applyid' )) );
		$applyid = cucas_base64_decode ( $applyid );
		$where_apply_info = "id = {$applyid}";
		$apply_info = $this->apply_model->get_apply_info ( $where_apply_info );
		$data = $this->input->post ( 'datas' );
		$url = $data ['url'];
		// $value = json_encode ( $data );
		$userid = $apply_info ['userid'];
		$truename = $data ['truename'];
		$thumbnailUrl = $data ['thumbnailUrl'];
		$addData = array (
				'courseid' => $courseid,
				'attachmentid' => $attachmentid,
				'userid' => $userid,
				// 'value' => $value,
				'url' => $url,
				'truename' => $truename,
				'thumbnailUrl' => $thumbnailUrl,
				'time' => time (),
				'applyid' => $applyid 
		);
		$result = $this->apply_model->save_upload_attachment ( $addData );
		
		echo $result;
		exit ();
	}
	
	/**
	 * 上传附件
	 */
	public function upload_files() {
		$config ['upload_path'] = UPLOADS . 'my/apply/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		
		$config ['allowed_types'] = 'gif|jpg|jpeg|png|pdf|doc|docx|rar|zip';
		$config ['max_size'] = '8192';
		$config ['file_name'] = build_order_no ();
		$save_path = '/uploads/my/apply/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
		
		$this->load->library ( 'upload', $config );
		mk_dir ( $config ['upload_path'] );
		if (! $this->upload->do_upload ( 'files' )) {
			$info = $this->upload->display_errors ( '', '' );
			ajaxReturn ( '', $info, 0 );
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
			ajaxReturn ( $data, '', 1 );
		}
	}
	
	/**
	 * 删除图片
	 */
	public function delFiles() {
		$id = $this->input->post ( 'id' );
		$where = "id = {$id}";
		// 先要去查询一下 这条数据 属不属这个人
		$result = $this->apply_model->get_apply_attachment_info ( $where );
		
		if (empty ( $result )) {
			echo 0;
			exit ();
		}
		$flag = $this->apply_model->del_upload_attachment ( $where );
		
		if ($flag) {
			echo 1;
		} else {
			echo 0;
		}
		exit ();
	}
}