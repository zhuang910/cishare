<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 文章管理
 *
 * @author zyj
 *        
 */
class News extends Master_Basic {
	/**
	 * 文章管理
	 *
	 * @var array
	 */
	protected $programaids_news = array ();
	/**
	 *
	 * @var number
	 */
	public $programaid_parent = 0;
	
	/**
	 * 栏目字符串
	 *
	 * @var array
	 */
	public $programaids = null;
	
	/**
	 * 栏目查询字符串
	 *
	 * @var sting
	 */
	public $programaids_where = null;
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		
		$this->load->model ( $this->view . 'news_model' );
		$this->programaids_news = $this->_get_programaids ();
	}
	
	/**
	 * 主页
	 */
	function index() {
		
		// 获得语言的id
		$label_id =$_SESSION['language'];
		$column_info=$this->news_model->get_news_colum();
		// 如果是ajax请求则返回json数据列表
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
			$where=' site_language = "'.$label_id.'"';
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				title LIKE '%{$sSearch}%'
				OR
				orderby LIKE '%{$sSearch}%'
				OR
				state LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`createtime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
		
				)
				";
			}
			$sSearch_0 = mysql_real_escape_string ( $this->input->get ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND (
				id = '{$sSearch_0}'
				)
				";
			}
			$sSearch_1 = mysql_real_escape_string ( $this->input->get ( 'sSearch_1' ) );
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				title LIKE '%{$sSearch_1}%'
				)
				";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->get ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				orderby LIKE '%{$sSearch_2}%'
				)
				";
			}
			$sSearch_4 = mysql_real_escape_string ( $this->input->get ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				FROM_UNIXTIME(`lasttime`,'%Y-%m-%d') LIKE '%{$sSearch_4}%'
				)
				";
			}
			$sSearch_5 = mysql_real_escape_string ( $this->input->get ( 'sSearch_5' ) );
			if (! empty ( $sSearch_5 )) {
				$where .= "
				AND (
				columnid = '{$sSearch_5}'
				)
				";
			}
			$sSearch_3 = mysql_real_escape_string ( $this->input->get ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
				$where .= "
				AND (
				state = '{$sSearch_3}'
				)
				";
			}
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->news_model->count ($where );
			$output ['aaData'] = $this->news_model->get ( $where, $limit, $offset, 'orderby DESC' );
			foreach ( $output ['aaData'] as $item ) {
				// $item->columnid = ! empty ( $this->programaids_news [$item->columnid] ['title'] ) ? $this->programaids_news [$item->columnid] ['title'] : '';
				$item->columnid =$this->news_model->get_colum_name($item->columnid);
				$item->state = $this->_get_lists_state ( $item->state );
				$item->lasttime =!empty($item->lasttime)? date ( 'Y-m-d H:i:s', $item->lasttime ):'';
				$item->operation = '
					<a href="/master/cms/news/edit?id=' . $item->id . '&label_id=' . $item->site_language . '" class="green"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'news_index', array (
				'label_id' => $label_id ,
				'column_info'=>$column_info
		) );
	}
	
	/**
	 * 添加
	 */
	function add() {
		$label_id = $this->input->get ( 'label_id' );
		if (empty ( $label_id )) {
			$label_id = $this->open_language [0];
		}
		$this->_view ( 'news_edit', array (
				'programaids' => $this->programaids_news,
				'label_id' => $label_id 
		) );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		// 获得语言的id
		$label_id = $this->input->get ( 'label_id' );
		if (empty ( $label_id )) {
			$label_id = $this->open_language [0];
		}
		if ($id) {
			$where = "id = " . $id;
			$info = ( object ) $this->news_model->get_one ( $where );
			if (empty ( $info )) {
				$this->_alert ( '此文章不存在' );
			}
		}
		$column_info=$this->news_model->get_news_colum();
		// // 名师
		// $teacher_jpkc = $this->db->select ( '*' )->get_where ( 'teacher_jpkc', 'id > 0' )->result_array ();
		// if (! empty ( $teacher_jpkc )) {
			
		// 	foreach ( $teacher_jpkc as $pk => $pv ) {
				
		// 		$jpkc [$pv ['id']] = $pv ['title'];
		// 	}
		// }
		
		$this->_view ( 'news_edit', array (
				'programaids' => $this->programaids_news,
				'info' => ! empty ( $info ) ? $info : array (),
				'label_id' => $label_id ,
				'column_info'=>$column_info
				// 'jpkc' => !empty($jpkc)?$jpkc:array()
		) );
	}
	
	/**
	 * 保存数据
	 */
	function save() {
		set_time_limit(0);
		$data = $this->input->post ();
		$id = ! empty ( $data ['id'] ) ? $data ['id'] : '';
		unset ( $data ['id'] );
		if (! empty ( $data )) {
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			
			if (! empty ( $data ['createtime'] )) {
				$data ['createtime'] = strtotime ( $data ['createtime'] );
				if (! empty ( $data ['time'] )) {
					$data ['time'] = strtotime ( $data ['time'] );
				}
				if (! empty ( $id )) {
					$data ['lasttime'] = time ();
				
					$flag = $this->news_model->save ( $id, $data );
				} else {
					$flag = $this->news_model->save ( null, $data );
				}
				
				if ($flag) {
					ajaxReturn ( '', '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			}
		} else {
			
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/article/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/article/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'allowed_types' => 'jpeg|jpg|png',
				'file_name' => time () . rand ( 100000, 999999 ) 
		);
		
		if (! empty ( $config )) {
			$this->load->library ( 'upload', $config );
			// 创建目录
			mk_dir ( $config ['upload_path'] );
			
			if (! $this->upload->do_upload ( 'imagefile' )) {
				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
			} else {
				$imgdata = $this->upload->data ();
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
	}
	
	/**
	 * 更改文章状态
	 */
	function audit() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$action = $this->input->post ( 'action' );
		$type = intval ( $this->input->post ( 'type' ) );
		if (! empty ( $action ) && $action == 'true') {
			$result = $this->article_model->save_audit ( $id, $type );
			if ($result === true) {
				ajaxReturn ( '', '更改成功', 1 );
			}
		}
		$html = $this->_view ( 'article_audit', array (
				'id' => $id 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->_save_data ();
		
		if (! empty ( $data )) {
			// 上传缩略图
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			$id = $this->news_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 更新
	 */
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->_save_data ();
			// 上传缩略图
			if (! empty ( $_FILES ['imagefile'] ['name'] )) {
				$data ['image'] = $this->_upload ();
			}
			// 保存基本信息
			$this->news_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->news_model->get_one ( $where );
			$is = $this->news_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'title',
				'orderby',
				'createtime',
				'columnid',
				'state',
				'site_language' 
		);
	}
	
	/**
	 * 获取文章状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">启用</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 获取保存数据
	 */
	private function _save_data() {
		$time = time ();
		$return = array ();
		$data = $this->input->post ();
		if (! empty ( $data )) {
			unset ( $data ['imagefile'] );
			foreach ( $data as $key => $value ) {
				if ($key == 'id' && empty ( $value )) {
					unset ( $data [$key] );
				}
				
				$data [$key] = trim ( $value );
				if ($key == 'createtime') {
					$data ['createtime'] = strtotime ( $value );
				}
			}
		}
		
		$data ['lasttime'] = $time;
		$data ['adminid'] = $this->adminid;
		return $data;
	}
	
	/**
	 * 获取单页栏目ID
	 *
	 * @return multitype:Ambigous <>
	 */
	private function _get_programaids() {
		$programa = CF ( 'programa', '', 'application/cache/' );
		
		$programas = menu_tree ( $this->programaid_parent, $programa, 'programaid' );
		$this->programaids = find_child_str ( $programas, 'programaid' );
		$this->programaids_where = '\'' . str_replace ( " , ", " ", implode ( "','", explode ( ',', $this->programaids ) ) ) . '\'';
		
		$array = array ();
		if (! empty ( $this->programaids ) && ! empty ( $programa )) {
			$programa_fmba = explode ( ',', $this->programaids );
			if (! empty ( $programa_fmba ) && is_array ( $programa_fmba )) {
				foreach ( $programa_fmba as $programaid ) {
					if ($programa [$programaid] ['moduleid'] == 3) {
						$array [$programaid] = $programa [$programaid];
					}
				}
			}
		}
		// 干掉名师团队这个栏目
		foreach ( $array as $k => $v ) {
			if ($v ['title'] == 'NEWS&EVENTS') {
				unset ( $array [$k] );
			}
			// if ($v ['title'] == '国际交流') {
			// unset ( $array [$k] );
			// }
			// if ($v ['title'] == '友情链接') {
			// unset ( $array [$k] );
			// }
		}
		
		return $array;
	}
	
	/**
	 * 获得名师团队的精品课程
	 */
	function get_teacher() {
		$t = $this->_teacher ();
		if (! empty ( $t )) {
			ajaxReturn ( $t, '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	function _teacher() {
		$data = array ();
		$option = '';
		// 先查所有的名师
		$teacher_all = $this->db->select ( '*' )->get_where ( 'teacher_team', 'id >0' )->result_array ();
		if (! empty ( $teacher_all )) {
			foreach ( $teacher_all as $k => $v ) {
				$ids [] = $v ['id'];
				$jpkc_name [$v ['id']] = $v ['name'];
			}
			// 查选名师下的精品课程
			$where = 'teamid IN (' . implode ( ',', $ids ) . ')';
			$teacher_jpkc = $this->db->select ( '*' )->get_where ( 'teacher_jpkc', $where )->result_array ();
			if (! empty ( $teacher_jpkc )) {
				foreach ( $jpkc_name as $jk => $jv ) {
					foreach ( $teacher_jpkc as $pk => $pv ) {
						if ($pv ['teamid'] == $jk) {
							$datas [$jk] [$pv ['id']] = $pv ['title'];
						}
					}
				}
				$option = '<option value="">请选择名师--</option>';
				foreach ( $datas as $zk => $zv ) {
					$option .= '<option style="color:red;" disabled="disabled">' . $jpkc_name [$zk] . '</option>';
					if (! empty ( $datas [$zk] )) {
						foreach ( $datas [$zk] as $lk => $lv ) {
							$option .= '<option value=' . $lk . '>--' . $lv . '</option>';
						}
					}
				}
			}
		}
		return $option;
	}
}

