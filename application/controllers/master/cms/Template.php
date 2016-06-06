<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * PPT管理
 *
 * @author junjiezhang
 *        
 */
class Template extends Master_Basic {
	/**
	 * 模版管理
	 *
	 * @var array
	 */
	protected $moudel = array ();
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		$this->load->model ( $this->view . 'template_model' );
		// 获取所有 模型
		$this->moudel = $this->_moudel ();
		$this->load->vars ( 'moudel', $this->moudel );
	}
	
	/**
	 * 获取所有的模型
	 */
	function _moudel() {
		$data = array ();
		$moudel_all = $this->db->select ( '*' )->get_where ( 'module_info', 'id > 0' )->result_array ();
		if (! empty ( $moudel_all )) {
			foreach ( $moudel_all as $k => $v ) {
				$data [$v ['id']] = $v ['title'];
			}
		}
		return $data;
	}
	
	/**
	 * 主题展示页
	 */
	function index() {
		// 获取所有的主题展示页
		$template = $this->template_model->get_templates ();
		$this->_view ( 'template_index', array (
				'template' => ! empty ( $template ) ? $template : array () 
		) );
	}
	
	/**
	 * 主题开关按钮
	 */
	function template_on_off() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
		
		if ($id) {
			$state_other = 1;
			if ($state == 0) {
				$state = 1;
				$state_other = 0;
			} else if ($state == 1) {
				$state = 0;
			}
			$flag = $this->template_model->update_template ( 'id = ' . $id, array (
					'state' => $state 
			) );
			if ($state_other == 0) {
				$this->template_model->update_template ( 'id != ' . $id, array (
						'state' => $state_other 
				) );
			}
			
			// 查询 哪一个打开 写入到 缓存
			$on = $this->template_model->get_template_one ( 'state = 1' );
			if ($on) {
				CF ( 'theme', array (
						'on' => $on->id 
				), CONFIG_PATH );
			}
			
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 模版展示页
	 */
	function template_list() {
		// 获取栏目的id
		$themeid = intval ( trim ( $this->input->get ( 'themeid' ) ) );
		
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}
			
			$where = 'theme_id = ' . $themeid;
			
			$like = array ();
			
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				name LIKE '%{$sSearch}%'
				OR
				orderby LIKE '%{$sSearch}%'
				OR
				state LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
		
				)
				";
			}
			
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_0}%'
				OR
				name LIKE '%{$sSearch_0}%'
				OR
				orderby LIKE '%{$sSearch_0}%'
				OR
				state LIKE '%{$sSearch_0}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_0}%'
		
				)
				";
			}
			
			$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_1}%'
				OR
				name LIKE '%{$sSearch_1}%'
				OR
				orderby LIKE '%{$sSearch_1}%'
				OR
				state LIKE '%{$sSearch_1}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_1}%'
		
				)
				";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				
				remark LIKE '%{$sSearch_2}%'
				
				)
				";
			}
			
			$sSearch_3 = mysql_real_escape_string ( $this->input->post ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				if ($sSearch_3 == - 1) {
					$sSearch_3 = 0;
				}
				$where .= "
				AND (
				
				state = {$sSearch_3}
				)
				";
			}
			
			$sSearch_4 = mysql_real_escape_string ( $this->input->post ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch_4}%'
				OR
				name LIKE '%{$sSearch_4}%'
				OR
				orderby LIKE '%{$sSearch_4}%'
				OR
				state LIKE '%{$sSearch_4}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch_4}%'
		
				)
				";
			}
			
			$sSearch_5 = mysql_real_escape_string ( $this->input->post ( 'sSearch_5' ) );
			if (! empty ( $sSearch_5 )) {
				
				$where .= "
				AND (
			
				module_id = {$sSearch_5}
				)
				";
			}
			// 输出
			$output ['sEcho'] = intval ( $_POST ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->template_model->count_ppt ( $where );
			$output ['aaData'] = $this->template_model->get_ppt ( $where, $limit, $offset, 'orderby DESC' );
			
			foreach ( $output ['aaData'] as $item ) {
				$item->createtime = ! empty ( $item->createtime ) ? date ( 'Y-m-d', $item->createtime ) : '';
				$state = $item->state;
				$item->state = $this->_set_state ( $state );
				$item->remark = !empty($item->remark)?$item->remark:'';
				$item->module_id = ! empty ( $item->module_id ) && ! empty ( $this->moudel [$item->module_id] ) ? $this->moudel [$item->module_id] : '';
				
				$item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="/master/cms/template/edittemplate?themeid=' . $themeid . '&_id=' . $item->id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';
				
				$item->operation.= '<li><a title="启用" href="javascript:;" onclick="edit_state(' . $themeid . ',' . $item->id . ',1)">启用
					</a></li><li><a title="停用" href="javascript:;" onclick="edit_state(' . $themeid . ',' . $item->id . ',0)">
					停用
					</a></li><li class="divider"></li>
					<li><a title="删除" href="javascript:;" onclick="del(' . $themeid . ',' . $item->id . ')">
					删除</a></li>';
				$item->operation.='</ul></div>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'template_list', array (
				'themeid' => $themeid 
		) );
	}
	
	/**
	 * 编辑内容
	 */
	function edit() {
		$columnid = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		if (! empty ( $columnid )) {
			$info = $this->pages_model->get_one ( $columnid );
			$this->_view ( 'ppt_edit', array (
					'columnid' => $columnid,
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 添加ppt
	 */
	function addtemplate() {
		// 获取栏目的id
		$themeid = intval ( trim ( $this->input->get ( 'themeid' ) ) );
		
		if ($themeid) {
			$this->_view ( 'template_addtemplate', array (
					'themeid' => $themeid 
			) );
		}
	}
	
	/**
	 * 验证命名
	 */
	function template_checkname() {
		$name = trim ( $this->input->get ( 'name' ) );
		$themeid = intval ( trim ( $this->input->get ( 'themeid' ) ) );
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		// 获取模版
		if (! empty ( $name ) && ! empty ( $themeid )) {
			
			if (! preg_match ( "/^[0-9a-zA-Z\_]*$/", $name )) {
				
				die ( json_encode ( '文件命名不符合规则' ) );
			}
			
			$m = $this->template_model->get_one ( "name = '{$name}'" . ' AND theme_id = ' . $themeid );
			
			if (! empty ( $m )) {
				if ($id) {
					if ($m->id == $id) {
						
						die ( json_encode ( true ) );
					} else {
						die ( json_encode ( '文件名已被占用' ) );
					}
				} else {
					die ( json_encode ( '文件名已被占用' ) );
				}
			} else {
				die ( json_encode ( true ) );
			}
		} else {
			die ( json_encode ( '文件名不能为空！' ) );
		}
	}
	
	/**
	 * 编辑ppt
	 */
	function edittemplate() {
		// 获取栏目的id
		$themeid = intval ( trim ( $this->input->get ( 'themeid' ) ) );
		// 获取栏目的id
		$id = intval ( trim ( $this->input->get ( '_id' ) ) );
		if ($themeid && $id) {
			$info = $this->template_model->get_one ( 'id = ' . $id );
			$this->_view ( 'template_addtemplate', array (
					'themeid' => $themeid,
					'info' => $info 
			) );
		}
	}
	
	/**
	 * 状态
	 */
	function _set_state($state = 0) {
		$state_array = array (
				'停用',
				'启用' 
		);
		return $state_array [$state];
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'orderby',
				'state',
				'createtime',
				'module_id',
				'remark' 
		);
	}
	
	/**
	 * 保存信息
	 */
	function save() {
		$data = $this->input->post ();
		if (! empty ( $data ['id'] )) {
			$id = $data ['id'];
		}
		unset ( $data ['id'] );
		$data ['lasttime'] = time ();
		$data ['adminid'] = $this->adminid;
		
		if (! empty ( $id )) {
			$flag = $this->template_model->save ( 'id = ' . $id, $data );
		} else {
			$data ['createtime'] = time ();
			$flag = $this->template_model->save ( null, $data );
		}
		// 保存的数据库的同时 写入文件中
		$this->save_file ( $data );
		
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 写如文件
	 */
	function save_file($data = array()) {
		if (! empty ( $data )) {
			// 文件目录 创建文件夹
			$dir = $_SERVER ['DOCUMENT_ROOT'] . '/application/views/home/themes/' . $data ['theme_id'];
			mk_dir ( $dir );
			// 生成文件 并写入文件
			
			file_put_contents ( $dir . '/' . $data ['name'] . '.php', $data ['content'] );
		}
	}
	
	/**
	 * 删除
	 */
	function deltemplate() {
		$themeid = intval ( $this->input->get ( 'themeid' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$is = $this->template_model->delete ( $id );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 修改状态
	 */
	function edit_template_state() {
		$themeid = intval ( $this->input->get ( 'themeid' ) );
		$id = intval ( $this->input->get ( 'id' ) );
		$state = intval ( $this->input->get ( 'state' ) );
		
		if ($id) {
			$is = $this->template_model->save ( 'id = ' . $id, array (
					'state' => $state 
			) );
			if ($is === true) {
				ajaxReturn ( '', '更新成功', 1 );
			}
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
}