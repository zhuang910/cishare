<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
header ( 'Content-Type: text/html; charset=utf8' );
/**
 *
 * @name 申请表管理
 * @package Citys
 * @author cucas Team [zyj]
 * @copyright Copyright (c) 2014-1-06, cucas
 */
class Apply_form extends Master_Basic {
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->load->model ( $this->view . 'apply_form_model' );
		// 页的类型
		$pagetype = array (
				'0' => '普通',
				'1' => '家庭',
				'2' => '教育',
				'3' => '工作',
				'4' => '签名' 
		);
		
		$this->load->vars ( 'pagetype', $pagetype );
		// 群组是否显示
		$classkind = array (
				'Y' => '隐藏',
				'N' => '显示' 
		);
		$this->load->vars ( 'classkind', $classkind );
		// 表单类型
		$formType = array (
				'1' => '文本框',
				'2' => '日期控件-年月日',
				'8' => '日期控件-年月',
				'3' => '文本域',
				'4' => '单选',
				'5' => '复选',
				'6' => '下拉列表',
				'7' => '标签' 
		);
		$this->load->vars ( 'formType', $formType );
		// 是否必填
		$isInput = array (
				'Y' => '必填',
				'N' => '不必填' 
		);
		$this->load->vars ( 'isInput', $isInput );
		// 是否隐藏
		$isHidden = array (
				'Y' => '隐藏',
				'N' => '显示' 
		);
		$this->load->vars ( 'isHidden', $isHidden );
	}
	/**
	 * 模版管理
	 */
	function index() {
		$where_t = 'parent_id = 0 AND classType = 1';
		
		$lists = $this->apply_form_model->get_template_info ( $where_t );
		if (! empty ( $lists )) {
			foreach ( $lists as $k => $v ) {
				// 查页的数量
				$where_page = 'classType = 2 AND rootID = ' . $v ['tClass_id'];
				$count = $this->apply_form_model->get_page_count ( $where_page );
				$lists [$k] ['count'] = ! empty ( $count ) ? $count : 0;
			}
		}
		$this->_view ( 'apply_form_index', array (
				'lists' => $lists 
		) );
	}
	
	/**
	 * 查找某个模版下的
	 * 所有页
	 */
	function page() {
		$tClass_id = $this->input->get ( 'tClass_id' );
		if (! empty ( $tClass_id )) {
			$where_page = 'classType = 2 AND rootID = ' . $tClass_id;
			$lists = $this->apply_form_model->get_template_info ( $where_page );
			if (! empty ( $lists )) {
				foreach ( $lists as $k => $v ) {
					// 查群组的数量
					$where_group = 'classType = 3 AND parent_id  = ' . $v ['tClass_id'];
					$count = $this->apply_form_model->get_page_count ( $where_group );
					$lists [$k] ['count'] = ! empty ( $count ) ? $count : 0;
				}
			}
		}
		$this->_view ( 'apply_form_page', array (
				'lists' => $lists,
				'tClass_id' => $tClass_id 
		) );
	}
	
	/**
	 * 查找某个页下的
	 * 所群组
	 */
	function group() {
		// 页的id
		$tClass_id = $this->input->get ( 'groupid' );
		// 模版的id
		$mobanid = $this->input->get ( 'tClass_id' );
		if (! empty ( $tClass_id )) {
			$where_group = 'classType = 3 AND parent_id  = ' . $tClass_id;
			$lists = $this->apply_form_model->get_template_info ( $where_group );
			if (! empty ( $lists )) {
				foreach ( $lists as $k => $v ) {
					// 查群组下的项的数量
					$where_items = 'Class_id = ' . $v ['tClass_id'];
					$count = $this->apply_form_model->get_items_count ( $where_items );
					$lists [$k] ['count'] = ! empty ( $count ) ? $count : 0;
				}
			}
		}
		$this->_view ( 'apply_form_group', array (
				'lists' => $lists,
				'pageid' => $tClass_id,
				'tClass_id' => $mobanid 
		) );
	}
	
	/**
	 * 查找某个群组下的
	 * 所有项
	 */
	function items() {
		$tClass_id = $this->input->get ( 'itemsid' );
		$mobanid = $this->input->get ( 'tClass_id' );
		if (! empty ( $tClass_id )) {
			$where_items = 'Class_id  = ' . $tClass_id;
			$lists = $this->apply_form_model->get_formtopic_info ( $where_items );
			if (! empty ( $lists )) {
				foreach ( $lists as $k => $v ) {
					if (in_array ( $v ['formType'], array (
							4,
							5,
							6 
					) )) {
						$where_formitem = 'topic_id = ' . $v ['topic_id'];
						$isonclick = $this->apply_form_model->get_formitem_info ( $where_formitem );
						
						if (! empty ( $isonclick [0] ['ControlID'] )) {
							$lists [$k] ['isonclick'] = '是';
						} else {
							$lists [$k] ['isonclick'] = '否';
						}
					} else {
						$lists [$k] ['isonclick'] = '否';
					}
				}
			}
		}
		$this->_view ( 'apply_form_items', array (
				'lists' => $lists,
				'groupid' => $tClass_id,
				'tClass_id' => $mobanid 
		) );
	}
	
	/**
	 * 新建模版
	 */
	function new_templates() {
		$tClass_id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($tClass_id) {
			$where_t = 'tClass_id = ' . $tClass_id;
			$templates = $this->apply_form_model->get_template_info ( $where_t );
		}
		
		$this->_view ( 'apply_form_new_templates', array (
				'result' => ! empty ( $templates [0] ) ? $templates [0] : array () 
		) );
	}
	
	/**
	 * 保存申请表模版
	 */
	function save_templates() {
		$data = $this->input->post ();
		$tClass_id = ! empty ( $data ['tClass_id'] ) ? $data ['tClass_id'] : '';
		unset ( $data ['tClass_id'] );
		if (empty ( $data ['classKind'] )) {
			$data ['classKind'] = 'N';
		}
		if (! empty ( $tClass_id )) {
			// 更新数据
			$flag = $this->apply_form_model->save_templates ( 'tClass_id = ' . $tClass_id, $data );
		} else {
			// 保存数据
			$data ['classType'] = 1;
			$flag = $this->apply_form_model->save_templates ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 添加页
	 */
	function new_page() {
		// 申请表模版id
		$tClass_id = intval ( trim ( $this->input->get ( 'tClass_id' ) ) );
		// 页的id
		$pageid = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($pageid) {
			// 获取页的内容
			$where_p = 'classType = 2 AND tClass_id = ' . $pageid;
			$result = $this->apply_form_model->get_template_info ( $where_p );
		}
		$this->_view ( 'apply_form_new_page', array (
				'result' => ! empty ( $result [0] ) ? $result [0] : array (),
				'tClass_id' => ! empty ( $tClass_id ) ? $tClass_id : '' 
		) );
	}
	
	/**
	 * 保存页
	 */
	function save_page() {
		$data = $this->input->post ();
		// 页的父级分类
		$tClass_id = ! empty ( $data ['tClass_id'] ) ? $data ['tClass_id'] : '';
		unset ( $data ['tClass_id'] );
		// 获取该模版的信息目的是要 获取学校的id
		$where_t = 'tClass_id = ' . $tClass_id;
		$templates = $this->apply_form_model->get_template_info ( $where_t );
		$pageid = ! empty ( $data ['pageid'] ) ? $data ['pageid'] : '';
		unset ( $data ['pageid'] );
		if (! empty ( $pageid )) {
			$flag = $this->apply_form_model->save_templates ( 'tClass_id = ' . $pageid, $data );
		} else {
			// 保存数据
			$data ['parent_id'] = $tClass_id;
			$data ['rootID'] = $tClass_id;
			$data ['classType'] = 2;
			$flag = $this->apply_form_model->save_templates ( null, $data );
		}
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 添加 群组
	 */
	function new_group() {
		// 申请表模版id
		$tClass_id = intval ( trim ( $this->input->get ( 'tClass_id' ) ) );
		// 页的id
		$pageid = intval ( trim ( $this->input->get ( 'groupid' ) ) );
		
		// 群组的id
		$groupid = intval ( trim ( $this->input->get ( 'id' ) ) );
		// 编辑和新建群组
		
		if (! empty ( $groupid )) {
			$where_g = 'classType = 3 AND tClass_id = ' . $groupid;
			$result = $this->apply_form_model->get_template_info ( $where_g );
			// 添加群组
		}
		$this->_view ( 'apply_form_new_group', array (
				'result' => ! empty ( $result [0] ) ? $result [0] : array (),
				'tClass_id' => ! empty ( $tClass_id ) ? $tClass_id : '',
				'pageid' => ! empty ( $pageid ) ? $pageid : '' 
		) );
	}
	
	/**
	 * 修改全局群组
	 */
	function new_global_group() {
		// 群组的id
		$groupid = intval ( trim ( $this->input->get ( 'id' ) ) );
		// 编辑和新建群组
		
		if (! empty ( $groupid )) {
			$where_g = 'classType = 3 AND tClass_id = ' . $groupid;
			$result = $this->apply_form_model->get_template_info ( $where_g );
			// 添加群组
		}
		$this->_view ( 'apply_form_new_global_group', array (
				'result' => ! empty ( $result [0] ) ? $result [0] : array () 
		) );
	}
	
	/**
	 * 添加已有群组
	 */
	function new_group_page() {
		// 申请表模版id
		$tClass_id = intval ( trim ( $this->input->get ( 'tClass_id' ) ) );
		// 页的id
		$pageid = intval ( trim ( $this->input->get ( 'groupid' ) ) );
		
		$groups = $this->apply_form_model->get_template_info ( 'parent_id = 2' );
		if (! empty ( $groups )) {
			foreach ( $groups as $k => $v ) {
				$group [$v ['tClass_id']] = $v ['ClassName'];
			}
		}
		
		$this->_view ( 'apply_form_new_group_global', array (
				'tClass_id' => ! empty ( $tClass_id ) ? $tClass_id : '',
				'pageid' => ! empty ( $pageid ) ? $pageid : '',
				'group' => ! empty ( $group ) ? $group : array () 
		) );
	}
	
	/**
	 * 保存群组 新建或是编辑的
	 */
	function save_group() {
		$data = $this->input->post ();
		// 模版id
		$tClass_id = ! empty ( $data ['tClass_id'] ) ? $data ['tClass_id'] : '';
		// 获取该模版的信息目的是要 获取学校的id
		$where_t = 'tClass_id = ' . $tClass_id;
		$templates = $this->apply_form_model->get_template_info ( $where_t );
		unset ( $data ['tClass_id'] );
		
		// 页的id
		$pageid = ! empty ( $data ['pageid'] ) ? $data ['pageid'] : '';
		unset ( $data ['pageid'] );
		
		// 群组id
		$groupid = ! empty ( $data ['groupid'] ) ? $data ['groupid'] : '';
		unset ( $data ['groupid'] );
		if (empty ( $data ['classKind'] )) {
			$data ['classKind'] = 'N';
		}
		if (! empty ( $groupid )) {
			// 编辑群组
			$flag = $this->apply_form_model->save_templates ( 'tClass_id = ' . $groupid, $data );
		} else {
			// 添加群组
			$data ['parent_id'] = $pageid; // 页的id
			$data ['rootID'] = $tClass_id; // 模版的id
			$data ['classType'] = 3;
			$data ['admin_id'] = $templates [0] ['admin_id'];
			$flag = $this->apply_form_model->save_templates ( null, $data );
		}
		
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 保存全局群组
	 */
	function save_global_group() {
		$data = $this->input->post ();
		// 群组id
		$groupid = ! empty ( $data ['groupid'] ) ? $data ['groupid'] : '';
		unset ( $data ['groupid'] );
		if (empty ( $data ['classKind'] )) {
			$data ['classKind'] = 'N';
		}
		if (! empty ( $groupid )) {
			// 编辑群组
			$flag = $this->apply_form_model->save_templates ( 'tClass_id = ' . $groupid, $data );
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
	 * 保存已有群组
	 */
	function save_page_group() {
		$data = $this->input->post ();
		$tClass_id = ! empty ( $data ['tClass_id'] ) ? $data ['tClass_id'] : '';
		// 获取该模版的信息目的是要 获取学校的id
		$where_t = 'tClass_id = ' . $tClass_id;
		$templates = $this->apply_form_model->get_template_info ( $where_t );
		unset ( $data ['tClass_id'] );
		// 查找原来的群组 以及项
		$group = ! empty ( $data ['group'] ) ? $data ['group'] : '';
		unset ( $data ['group'] );
		if (! empty ( $group )) {
			$group_info = $this->apply_form_model->get_template_info ( 'tClass_id = ' . $group );
			$items = $this->apply_form_model->get_formtopic_info ( 'Class_id = ' . $group );
		}
		
		// 页的id
		$pageid = ! empty ( $data ['pageid'] ) ? $data ['pageid'] : '';
		unset ( $data ['pageid'] );
		if (empty ( $data ['classKind'] )) {
			$data ['classKind'] = 'N';
		}
		
		// 添加群组
		$data ['parent_id'] = $pageid; // 页的id
		$data ['rootID'] = $tClass_id; // 模版的id
		$data ['classType'] = 3;
		$data ['admin_id'] = $templates [0] ['admin_id'];
		$data ['ClassName'] = $group_info [0] ['ClassName'];
		$flag = $this->apply_form_model->save_templates ( null, $data );
		
		if ($flag) {
			foreach ( $items as $key => $val ) {
				$dataitem ['Class_id'] = $flag;
				$dataitem ['formTitle'] = $val ['formTitle'];
				$dataitem ['formType'] = $val ['formType'];
				$dataitem ['phpArrar'] = $val ['phpArrar'];
				$dataitem ['formID'] = $val ['formID'];
				$dataitem ['cols'] = $val ['cols'];
				$dataitem ['rows'] = $val ['rows'];
				$dataitem ['line'] = $val ['line'];
				$dataitem ['des'] = $val ['des'];
				$dataitem ['formHelp'] = $val ['formHelp'];
				$dataitem ['isHidden'] = $val ['isHidden'];
				$dataitem ['isInput'] = $val ['isInput'];
				$f_l = $this->apply_form_model->save_formtopic ( null, $dataitem );
				
				if (! empty ( $val ['formType'] ) && in_array ( $val ['formType'], array (
						4,
						5,
						6 
				) )) {
					$formitems = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $val ['topic_id'] );
					
					if (! empty ( $formitems )) {
						foreach ( $formitems as $k => $v ) {
							$dataformites ['topic_id'] = $f_l;
							$dataformites ['itemTitle'] = $v ['itemTitle'];
							$dataformites ['formValue'] = $v ['formValue'];
							$dataformites ['line'] = $v ['line'];
							$dataformites ['HiddenType'] = $v ['HiddenType'];
							$dataformites ['ControlID'] = $v ['ControlID'];
							$this->apply_form_model->save_formitem ( null, $dataformites );
						}
					}
				}
			}
			ajaxReturn ( 'back', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除某个群组下的项
	 */
	function del_items() {
		$id = $this->input->get ( 'id' );
		if (! empty ( $id )) {
			$flag = $this->apply_form_model->del_items ( 'topic_id = ' . $id );
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
	 * 获得群组信息
	 */
	function get_group() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$templates = $this->apply_form_model->get_template_info ( 'tClass_id = ' . $id );
			$html = $this->_view ( 'get_group', array (
					'result' => ! empty ( $templates ) ? $templates [0] : array () 
			), true );
			ajaxReturn ( $html, '', 1 );
		} else {
			ajaxReturn ( $html, '', 1 );
		}
	}
	
	/**
	 * 编辑项
	 */
	function new_items() {
		// 项的id
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		// 群组的id
		$itemsid = intval ( trim ( $this->input->get ( 'itemsid' ) ) );
		// 项的类型
		$formType = intval ( trim ( $this->input->get ( 'formType' ) ) );
		
		// 申请表模版id
		$tClass_id = intval ( trim ( $this->input->get ( 'tClass_id' ) ) );
		
		if ($id) {
			$result = $this->apply_form_model->get_formtopic_info ( 'topic_id = ' . $id );
			if (! empty ( $formType ) && in_array ( $formType, array (
					4,
					5,
					6 
			) )) {
				// 项的下面是否有值 下拉 单选 复选
				$formitem = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $id );
				if (! empty ( $formitem )) {
					// 表单项值的点击事件
					
					// 查询隐藏群组
					$where_g = 'classType = 3 AND classKind = "Y" AND rootID = ' . $tClass_id;
					$g = $this->apply_form_model->get_template_info ( $where_g );
					if (! empty ( $g )) {
						foreach ( $g as $k => $v ) {
							$group [$v ['tClass_id']] = $v ['ClassName'];
						}
					}
					
					// 查询所有的隐藏项
					$where_x = 'isHidden = "Y" AND Class_id = ' . $itemsid;
					$f = $this->apply_form_model->get_formtopic_info ( $where_x );
					if (! empty ( $f )) {
						foreach ( $f as $key => $val ) {
							$form [$val ['topic_id']] = $val ['formTitle'];
						}
					}
				}
			}
		}
		
		$this->_view ( 'apply_form_new_items', array (
				'result' => ! empty ( $result [0] ) ? $result [0] : array (),
				'itemsid' => ! empty ( $itemsid ) ? $itemsid : '',
				'formitem' => ! empty ( $formitem ) ? $formitem : array (),
				'group' => ! empty ( $group ) ? $group : array (),
				'form' => ! empty ( $form ) ? $form : array (),
				'tClass_id' => ! empty ( $tClass_id ) ? $tClass_id : '' 
		) );
	}
	
	/**
	 * 保存项的信息
	 */
	function save_items() {
		$data = $this->input->post ();
		
		if (! empty ( $data ['topic_id'] )) {
			if (empty ( $data ['isHidden'] )) {
				$data ['isHidden'] = 'N';
			}
			if (empty ( $data ['isInput'] )) {
				$data ['isInput'] = 'N';
			}
			$topic_id = $data ['topic_id'];
			unset ( $data ['topic_id'] );
			// 查询一下是否有值
			$ff = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $topic_id );
			if (! empty ( $ff )) {
				$this->apply_form_model->del_get_formitem_info ( 'topic_id = ' . $topic_id );
			}
			// 组织项的的内容的值
			if (! empty ( $data ['itemTitle'] )) {
				foreach ( $data ['itemTitle'] as $k => $v ) {
					$dataForm [$k] ['topic_id'] = $topic_id;
					$dataForm [$k] ['itemTitle'] = $v;
					$dataForm [$k] ['formValue'] = $data ['formValue'] [$k];
					$dataForm [$k] ['line'] = $data ['lines'] [$k];
					if (! empty ( $data ['ControlIDF'] [$k] )) {
						$dataForm [$k] ['HiddenType'] = 2;
						$dataForm [$k] ['ControlID'] = $data ['ControlIDF'] [$k];
					}
					
					if (! empty ( $data ['ControlIDG'] [$k] )) {
						$dataForm [$k] ['HiddenType'] = 1;
						$dataForm [$k] ['ControlID'] = $data ['ControlIDG'] [$k];
					}
				}
			}
			unset ( $data ['itemTitle'] );
			unset ( $data ['formValue'] );
			unset ( $data ['lines'] );
			unset ( $data ['ControlIDF'] );
			unset ( $data ['ControlIDG'] );
			
			// 插入数据
			if (! empty ( $dataForm )) {
				foreach ( $dataForm as $fk => $fv ) {
					$this->apply_form_model->save_formitem ( null, $fv );
				}
			}
			
			$flag = $this->apply_form_model->save_formtopic ( 'topic_id = ' . $topic_id, $data );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		}
	}
	
	/**
	 * 添加项的值
	 */
	function add_item() {
		$gid = intval ( trim ( $this->input->get ( 'gid' ) ) );
		$tClass_id = intval ( trim ( $this->input->get ( 'tClass_id' ) ) );
		if (! empty ( $gid ) && ! empty ( $tClass_id )) {
			
			// 查询隐藏群组
			$where_g = 'classType = 3 AND classKind = "Y" AND rootID = ' . $tClass_id;
			$g = $this->apply_form_model->get_template_info ( $where_g );
			if (! empty ( $g )) {
				foreach ( $g as $k => $v ) {
					$group [$v ['tClass_id']] = $v ['ClassName'];
				}
			}
			
			// 查询所有的隐藏项
			$where_x = 'isHidden = "Y" AND Class_id = ' . $gid;
			$f = $this->apply_form_model->get_formtopic_info ( $where_x );
			if (! empty ( $f )) {
				foreach ( $f as $key => $val ) {
					$form [$val ['topic_id']] = $val ['formTitle'];
				}
			}
		}
		$html = $this->_view ( 'apply_form_add_item', array (
				'group' => ! empty ( $group ) ? $group : array (),
				'form' => ! empty ( $form ) ? $form : array () 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 管理全局群组
	 */
	function global_group() {
		// 全局群组 parent_id = 2
		$where_group = 'classType = 3 AND parent_id  = 2';
		$lists = $this->apply_form_model->get_template_info ( $where_group );
		if (! empty ( $lists )) {
			foreach ( $lists as $k => $v ) {
				// 查群组下的项的数量
				$where_items = 'Class_id = ' . $v ['tClass_id'];
				$count = $this->apply_form_model->get_items_count ( $where_items );
				$lists [$k] ['count'] = ! empty ( $count ) ? $count : 0;
			}
		}
		
		$this->_view ( 'apply_form_global_group', array (
				'lists' => $lists 
		) );
	}
	
	/**
	 * 添加 全局群组
	 */
	function new_group_global() {
		$this->_view ( 'apply_form_new_group_globals' );
	}
	
	/**
	 * 保存全局群组
	 */
	function save_new_group_global() {
		$data = $this->input->post ();
		$data ['parent_id'] = 2;
		$data ['rootID'] = 1;
		$data ['classType'] = 3;
		
		$flag = $this->apply_form_model->save_templates ( null, $data );
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除全局群组
	 */
	function del_group_global() {
		$tClass_id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$flag = $this->apply_form_model->del_templateclass ( 'tClass_id = ' . $tClass_id );
		if ($flag) {
			ajaxReturn ( '', '删除成功！', 1 );
		} else {
			ajaxReturn ( '', '删除失败', 0 );
		}
	}
	
	/**
	 * 全局项
	 */
	function global_items() {
		$where_items = 'Class_id = 4';
		$lists = $this->apply_form_model->get_formtopic_info ( $where_items );
		if (! empty ( $lists )) {
			foreach ( $lists as $k => $v ) {
				if (in_array ( $v ['formType'], array (
						4,
						5,
						6 
				) )) {
					$where_formitem = 'topic_id = ' . $v ['topic_id'];
					$isonclick = $this->apply_form_model->get_formitem_info ( $where_formitem );
					
					if (! empty ( $isonclick [0] ['ControlID'] )) {
						$lists [$k] ['isonclick'] = '是';
					} else {
						$lists [$k] ['isonclick'] = '否';
					}
				} else {
					$lists [$k] ['isonclick'] = '否';
				}
			}
		}
		
		$this->_view ( 'apply_form_global_items', array (
				'lists' => $lists 
		) );
	}
	
	/**
	 * 编辑全局项
	 */
	function new_global_item() {
		// 项的id
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		
		$formType = intval ( trim ( $this->input->get ( 'formType' ) ) );
		
		if ($id) {
			$result = $this->apply_form_model->get_formtopic_info ( 'topic_id = ' . $id );
			if (! empty ( $formType ) && in_array ( $formType, array (
					4,
					5,
					6 
			) )) {
				// 项的下面是否有值 下拉 单选 复选
				$formitem = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $id );
			}
		}
		
		$this->_view ( 'apply_form_new_global_item', array (
				'result' => ! empty ( $result [0] ) ? $result [0] : array (),
				'formitem' => ! empty ( $formitem ) ? $formitem : array () 
		) );
	}
	
	/**
	 * 添加全局项
	 */
	function add_new_global_item() {
		$this->_view ( 'apply_form_add_new_global_item' );
	}
	
	/**
	 * 保存 添加全局项
	 */
	function save_add_global_item() {
		$data = $this->input->post ();
		
		$data ['Class_id'] = 4;
		
		// 组织项的的内容的值
		if (! empty ( $data ['itemTitle'] )) {
			foreach ( $data ['itemTitle'] as $k => $v ) {
				
				$dataForm [$k] ['itemTitle'] = $v;
				$dataForm [$k] ['formValue'] = $data ['formValue'] [$k];
				$dataForm [$k] ['line'] = $data ['lines'] [$k];
			}
		}
		unset ( $data ['itemTitle'] );
		unset ( $data ['formValue'] );
		unset ( $data ['lines'] );
		$flag = $this->apply_form_model->save_formtopic ( null, $data );
		
		// 插入数据
		if (! empty ( $dataForm )) {
			foreach ( $dataForm as $fk => $fv ) {
				$fv ['topic_id'] = $flag;
				$this->apply_form_model->save_formitem ( null, $fv );
			}
		}
		
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 获取条件的
	 * 全局表单项
	 */
	function get_global_item() {
		$formType = intval ( trim ( $this->input->get ( 'formType' ) ) );
		if ($formType) {
			$where_items = 'Class_id = 4 AND formType = ' . $formType;
			$lists = $this->apply_form_model->get_formtopic_info ( $where_items );
			$option = '';
			if (! empty ( $lists )) {
				$option .= '<option value="">--请选择--</option>';
				foreach ( $lists as $k => $v ) {
					$option .= '<option value=' . $v ['topic_id'] . '>' . $v ['formTitle'] . '</option>';
				}
			}
			ajaxReturn ( $option, '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 获取全局项
	 */
	function get_global_item_title() {
		$title = intval ( trim ( $this->input->get ( 'title' ) ) );
		if ($title) {
			$where_items = 'topic_id = ' . $title;
			$lists = $this->apply_form_model->get_one_formtopic ( $where_items );
			if (! empty ( $lists )) {
				// 看是否是 那三种 特殊的
				if (in_array ( $lists ['formType'], array (
						4,
						5,
						6 
				) )) {
					// 查询 内容项的表 formitem
					$formitem = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $title );
				}
				$html = $this->_view ( 'apply_form_eidt_formitem', array (
						'result' => ! empty ( $lists ) ? $lists : array (),
						'formitem' => ! empty ( $formitem ) ? $formitem : array () 
				), true );
				ajaxReturn ( $html, '', 1 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 添加 全局已有表单项
	 */
	function add_global_group_item() {
		$itemsid = intval ( trim ( $this->input->get ( 'itemsid' ) ) );
		$this->_view ( 'apply_form_add_global_group_item', array (
				'Class_id' => ! empty ( $itemsid ) ? $itemsid : '' 
		) );
	}
	
	/**
	 * 群组中 新加项
	 */
	function add_group_item() {
		$itemsid = intval ( trim ( $this->input->get ( 'itemsid' ) ) );
		$this->_view ( 'apply_form_add_group_item', array (
				'Class_id' => ! empty ( $itemsid ) ? $itemsid : '' 
		) );
	}
	
	/**
	 * 保存 群组中的新加项
	 */
	function save_add_group_item() {
		$data = $this->input->post ();
		
		// 组织项的的内容的值
		if (! empty ( $data ['itemTitle'] )) {
			foreach ( $data ['itemTitle'] as $k => $v ) {
				
				$dataForm [$k] ['itemTitle'] = $v;
				$dataForm [$k] ['formValue'] = $data ['formValue'] [$k];
				$dataForm [$k] ['line'] = $data ['lines'] [$k];
			}
		}
		unset ( $data ['itemTitle'] );
		unset ( $data ['formValue'] );
		unset ( $data ['lines'] );
		$flag = $this->apply_form_model->save_formtopic ( null, $data );
		
		// 插入数据
		if (! empty ( $dataForm )) {
			foreach ( $dataForm as $fk => $fv ) {
				$fv ['topic_id'] = $flag;
				$this->apply_form_model->save_formitem ( null, $fv );
			}
		}
		
		if ($flag) {
			ajaxReturn ( '', '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 保存要添加的全局项
	 */
	function save_add_group_item_zyj() {
		// 表单项的id
		$zyjid = intval ( trim ( $this->input->post ( 'zyjid' ) ) );
		$Class_id = intval ( trim ( $this->input->post ( 'Class_id' ) ) );
		if (! empty ( $zyjid ) && $Class_id) {
			$where_items = 'topic_id = ' . $zyjid;
			$lists = $this->apply_form_model->get_one_formtopic ( $where_items );
			if (! empty ( $lists )) {
				// 看是否是 那三种 特殊的
				if (in_array ( $lists ['formType'], array (
						4,
						5,
						6 
				) )) {
					// 查询 内容项的表 formitem
					$formitem = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $zyjid );
				}
				
				// 获取 表单项的内容 $lists 和 表单项内容的内容
				// 组合新的数据 插入到 表单项的表中
				unset ( $lists ['topic_id'] );
				$lists ['Class_id'] = $Class_id;
				$id = $this->apply_form_model->save_formtopic ( null, $lists );
				
				// 插入表单项的数据
				if (! empty ( $id ) && ! empty ( $formitem )) {
					foreach ( $formitem as $key => $val ) {
						unset ( $val ['item_id'] );
						$val ['topic_id'] = $id;
						$this->apply_form_model->save_formitem ( null, $val );
					}
				}
				if ($id) {
					ajaxReturn ( '', '', 1 );
				}
			} else {
				ajaxReturn ( '', '', 0 );
			}
		}
	}
	
	/**
	 * 保存全局项
	 */
	function save_global_item() {
		$data = $this->input->post ();
		
		if (! empty ( $data ['topic_id'] )) {
			if (empty ( $data ['isHidden'] )) {
				$data ['isHidden'] = 'N';
			}
			if (empty ( $data ['isInput'] )) {
				$data ['isInput'] = 'N';
			}
			$topic_id = $data ['topic_id'];
			unset ( $data ['topic_id'] );
			$data ['Class_id'] = 4;
			// 查询一下是否有值
			$ff = $this->apply_form_model->get_formitem_info ( 'topic_id = ' . $topic_id );
			if (! empty ( $ff )) {
				$this->apply_form_model->del_get_formitem_info ( 'topic_id = ' . $topic_id );
			}
			// 组织项的的内容的值
			if (! empty ( $data ['itemTitle'] )) {
				foreach ( $data ['itemTitle'] as $k => $v ) {
					$dataForm [$k] ['topic_id'] = $topic_id;
					$dataForm [$k] ['itemTitle'] = $v;
					$dataForm [$k] ['formValue'] = $data ['formValue'] [$k];
					$dataForm [$k] ['line'] = $data ['lines'] [$k];
				}
			}
			unset ( $data ['itemTitle'] );
			unset ( $data ['formValue'] );
			unset ( $data ['lines'] );
			
			// 插入数据
			if (! empty ( $dataForm )) {
				foreach ( $dataForm as $fk => $fv ) {
					$this->apply_form_model->save_formitem ( null, $fv );
				}
			}
			
			$flag = $this->apply_form_model->save_formtopic ( 'topic_id = ' . $topic_id, $data );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		}
	}
}