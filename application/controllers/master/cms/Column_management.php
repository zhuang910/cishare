<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 关于我们
 *
 * @author junjiezhang
 *        
 */
class Column_management extends Master_Basic {
	/**
	 * 关于我们
	 *
	 * @var array
	 */
	protected $type;
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		
		$this->load->model ( $this->view . 'column_model' );
	}
	
	/**
	 * 首页
	 */
	function index() {
		$array = $this->column_model->get_column_info ();
		if (! empty ( $array )) {
			$array = master_operate ( $array );
		}
		$id = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		$str = "<tr>
				<td>
					\$id
				</td>
				<td class='hidden-480'>\$spacer - \$title</td>
				<td>\$mtitle</td>
				<td>\$misjump</td>
				<td>\$mstate</td>
				<td>\$moperate</td>
			</tr>";
		$this->load->library ( 'Tree' );
		
		$Tree = new Tree ( $array );
		$Tree->icon = array (
				'&nbsp;│  ',
				'&nbsp;├─ ',
				'&nbsp;└─ ' 
		);
		$Tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$select_categorys = $Tree->get_tree ( $id, $str );
		// echo '<select>'.$select_categorys.'</select>';die;
		$name = $this->column_model->get_one ( $id );
		$this->_view ( 'column_management_index', array (
				'select_categorys' => ! empty ( $select_categorys ) ? $select_categorys : '',
				'name' => ! empty ( $name->title ) ? $name->title : '' 
		) );
	}
	
	/**
	 * 修改栏目的状态
	 */
	function edit_state_column() {
		$id = intval ( trim ( $this->input->get ( 'columnid' ) ) );
		$state = intval ( trim ( $this->input->get ( 'state' ) ) );
		if ($id) {
			$flag = $this->column_model->save ( $id, array (
					'state' => $state 
			) );
			if ($flag) {
				ajaxReturn ( '', '', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
}