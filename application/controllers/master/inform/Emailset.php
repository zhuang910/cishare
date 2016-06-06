<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 通知邮件配置
 *
 * @author JJ
 *
 */
class Emailset extends Master_Basic
{
	/**
	 * 基础类构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->view = 'master/inform/';
		$this->load->model($this->view . 'push_mail_model');
	}

	/**
	 * 配置主页
	 */
	function index()
	{
		if ($this->input->get('nd')) {
			$page = $_GET['page']; // 当前页
			$limit = $_GET['rows']; // 条数
			$sidx = $_GET['sidx']; // 排序字段
			$sord = $_GET['sord']; // 排序方式  asc 升序

			if (!$sidx) $sidx = 1;

			$keywords = mysql_real_escape_string(trim($this->input->get('keywords')));


			$where = null;
			if(!empty($keywords)){
				$where = "(smtp_host LIKE '%{keywords}%' OR smtp_user LIKE '%{$keywords}%' OR smtp_port = '{$keywords}' OR mailtype = '{$keywords}')";
			}

			$count = $this->push_mail_model->count_config($where);
			if ($count > 0) {
				$total_pages = ceil($count / $limit);
			} else {
				$total_pages = 0;
			}
			if ($page > $total_pages) $page = $total_pages;

			$offset = $limit * $page - $limit;
			$result = $this->push_mail_model->get_config($where, $limit, $offset, "{$sidx} {$sord}");
			foreach ($result as $info) {
				$info->createtime = date('Y-m-d', $info->createtime);
			}

			exit(json_encode($result));
		}

		$defult = CF('emailset', '', CONFIG_PATH);

		$this->_view('emailset_index', array(
			'defult' => $defult['defult'],
		));
	}

	/**
	 * 筛选页面
	 */
	function search()
	{
		$html = $this->_view('emailset_search', '', true);
		ajaxReturn($html, '', 1);
	}

	/**
	 * 添加
	 */
	function add()
	{
		$html = $this->_view('emailset_edit', array(
			'action' => 'add'
		), true);
		ajaxReturn($html, '', 1);
	}

	/**
	 * 修改
	 */
	function edit()
	{
		$id = (int)$this->input->get('id');
		if (!empty($id)) {
			$info = $this->push_mail_model->get_config_one($id);

			$html = $this->_view('emailset_edit', array(
				'action' => 'edit',
				'info' => $info
			), true);
			ajaxReturn($html, '', 1);
		}
	}

	/**
	 * 插入
	 */
	function insert()
	{
		if (IS_AJAX === true) {

			$data = array(
				'protocol' => 1,
				'smtp_host' => mysql_real_escape_string(trim($this->input->post('smtp_host'))),
				'smtp_user' => mysql_real_escape_string(trim($this->input->post('smtp_user'))),
				'smtp_pass' => md5(mysql_real_escape_string(trim($this->input->post('smtp_pass')))),
				'smtp_port' => (int)$this->input->post('smtp_port'),
				'smtp_timeout' => 5,
				'wordwrap' => true,
				'mailtype' => $this->input->post('mailtype'),
				'createtime' => time(),
				'updatetime' => time(),
				'createuser' => $this->adminid,
				'updateuser' => $this->adminid
			);

			$this->_config_check($data);

			$is = $this->push_mail_model->save_config($data);
			if ($is === true) {
				ajaxReturn('', '添加成功', 1);
			} else {
				ajaxReturn('', '添加失败', 0);
			}
		}
	}

	/**
	 * 更新
	 */
	function update()
	{
		$id = (int)$this->input->post('id');
		if (IS_AJAX === true && !empty($id)) {

			$data = array(
				'protocol' => 1,
				'smtp_host' => mysql_real_escape_string(trim($this->input->post('smtp_host'))),
				'smtp_user' => mysql_real_escape_string(trim($this->input->post('smtp_user'))),
				'smtp_pass' => md5(mysql_real_escape_string(trim($this->input->post('smtp_pass')))),
				'smtp_port' => (int)$this->input->post('smtp_port'),
				'smtp_timeout' => 5,
				'wordwrap' => true,
				'mailtype' => $this->input->post('mailtype'),
				'updatetime' => time(),
				'updateuser' => $this->adminid
			);

			$this->_config_check($data, 'edit');
			if (empty($data['smtp_pass'])) {
				$data['smtp_pass'] = $this->input->post('old_smtp_pass');
			}

			$is = $this->push_mail_model->save_config($data, $id);
			if ($is === true) {
				ajaxReturn('', '添加成功', 1);
			} else {
				ajaxReturn('', '添加失败', 0);
			}
		}
	}

	/**
	 * 删除
	 */
	function delete()
	{
		$ids = $this->input->post('ids');
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$this->push_mail_model->delete_config($id);
			}
			ajaxReturn('', '删除成功', 1);
		}
	}

	/**
	 * 快捷操作
	 */
	function dummy()
	{
		$oper = $this->input->post('oper');
		$id = (int)$this->input->post('id');
		if (!empty($oper) && !empty($id)) {
			switch ($oper) {
				case 'edit':
					$data = array(
						'protocol' => 1,
						'smtp_user' => mysql_real_escape_string(trim($this->input->post('smtp_user'))),
						'smtp_port' => (int)$this->input->post('smtp_port'),
						'mailtype' => $this->input->post('mailtype'),
						'updatetime' => time(),
						'updateuser' => $this->adminid
					);

					if (empty($data['smtp_user'])) {
						exit('用户帐号不能为空');
					}

					$this->load->helper('email');
					if (!valid_email($data['smtp_user'])) {
						exit('用户帐号格式不正确');
					}

					if (empty($data['smtp_port'])) {
						exit('端口不能为空');
					}

					$this->push_mail_model->save_config($data, $id);
					break;
				case 'del':
					$this->push_mail_model->delete_config($id);
					break;
			}
			exit('1');
		}
		exit('修改失败');
	}

	/**
	 * 验证数据合理性
	 *
	 * @param array  $data
	 * @param string $type
	 */
	private function _config_check($data = array(), $type = 'add')
	{
		if (!empty($data) && is_array($data)) {
			if (empty($data['smtp_host'])) {
				ajaxReturn('', '服务器地址不能为空', 0);
			}

			if (empty($data['smtp_user'])) {
				ajaxReturn('', '用户帐号不能为空', 0);
			}

			$this->load->helper('email');
			if (!valid_email($data['smtp_user'])) {
				ajaxReturn('', '用户帐号格式不正确', 0);
			}

			if ($type == 'add') {
				if (empty($data['smtp_pass'])) {
					ajaxReturn('', '密码不能为空', 0);
				}
			}


			if (empty($data['smtp_port'])) {
				ajaxReturn('', '端口不能为空', 0);
			}
		}
	}
	/**
	 *保存默认发件人
	 **/
	function save(){
		$data['defult']=$this->input->post();
		CF('emailset',$data,CONFIG_PATH);
		ajaxReturn('','',1);
	}
}