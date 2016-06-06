<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 单页管理
 *
 * @author zyj
 *        
 */
class Page extends Master_Basic {
	/**
	 * 单页栏目ids
	 *
	 * @var array
	 */
	protected $programaids_pages = array ();
	public $programaids = null;
	public $programaid_parent = 0;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'master/cms/page_model' );
		// 单页栏目ID
		$this->programaids_pages = $this->_get_programaids ();
	}

	/**
	 * 列表
	 */
	function index() {
		// 获取语言的id
		$label_id = $_SESSION['language'];
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}
			$where=' module_id=2';
			$label_id =$this->input->get('label_id');
			$sSearch = mysql_real_escape_string ( $this->input->get ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				title LIKE '%{$sSearch}%'
				)
				";
			}
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->page_model->count ( $where);
			$output ['aaData'] = $this->page_model->get ( $where,$limit, $offset, 'id DESC');
			foreach ( $output ['aaData'] as $item ) {
				$page_info=$this->page_model->get_page_info($item->id,$label_id);
				$item->lastuser=$this->page_model->get_admin_name($page_info['lastuser']);
				$item->lasttime=!empty($page_info['lasttime'])?date('Y-m-d H:i:s',$page_info['lasttime']):'';
				//操作
				$item->operation='<a class="green" href="/master/cms/page/edit?id='.$item->id.'&label_id='.$label_id.'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>';
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'master/cms/pages_index', array (
				'label_id' => $label_id 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		$site_language_page =  $this->input->get ( 'label_id' );
		if ($id) {
			$where = "programaid = {$id} AND site_language = '{$site_language_page}'";
			$info = $this->page_model->get_one ( $where );

			$this->_view ( 'master/cms/pages_edit', array (
					'info' => $info,
					'pid' => $id,
					'site_language_page' => $site_language_page 
			) );
		}
	}
	
	/**
	 * 更新
	 */
	function update() {
		$id = intval ( $this->input->post ( 'programaid' ) );
		if ($id) {
			$data = $this->_save_data ();
			$result = $this->page_model->save ( $id, $data, $this->adminid );
			if ($result === true) {
				ajaxReturn ( '', '修改成功', 1 );
			} else {
				ajaxReturn ( '', '修改失败', 0 );
			}
		}
	}
	
	/**
	 * 返回保存数据
	 *
	 * @return multitype:array |boolean
	 */
	private function _save_data() {
		$post = $this->input->post ();
		$fields = $this->page_model->field ();
		$save = array ();
		if ($fields && $post) {
			foreach ( $post as $f => $val ) {
				if (in_array ( $f, $fields )) {
					$save [$f] = $val;
				}
			}
			return $save;
		}
		return false;
	}
	
	/**
	 * 获取单页栏目ID
	 *
	 * @return multitype:Ambigous <>
	 */
	private function _get_programaids() {
		$programa = CF ( 'programa', '', 'page_modellication/cache/' );
		
		$programas = menu_tree ( $this->programaid_parent, $programa, 'programaid' );
		$this->programaids = find_child_str ( $programas, 'programaid' );
		$this->programaids_where = '\'' . str_replace ( " , ", " ", implode ( "','", explode ( ',', $this->programaids ) ) ) . '\'';
		
		$array = array ();
		if (! empty ( $this->programaids ) && ! empty ( $programa )) {
			$programa_fmba = explode ( ',', $this->programaids );
			if (! empty ( $programa_fmba ) && is_array ( $programa_fmba )) {
				foreach ( $programa_fmba as $programaid ) {
					if ($programa [$programaid] ['moduleid'] == 2) {
						$array [$programaid] = $programa [$programaid];
					}
				}
			}
		}	
		return $array;
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
		);
	}
}