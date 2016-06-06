<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 文章分类
 *
 * @author zhuangqianlin
 * @desc 2016-05-28
 */
class Category extends Master_Basic {
    protected $_size = 3;
    protected $_count = 0;
    protected $_countpage = 0;
    protected $data_student = array ();
    /**
     * 基础类构造函数
     */
    function __construct() {
        parent::__construct ();
        $this->view = 'master/category/';
        $this->load->model ( $this->view . 'category_model' );
    }

    /**
     * 后台主页
     */
    function index() {
		
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

            // 状态筛选
            $where = 'cat_id>0 ';

            $sSearch = mysql_real_escape_string ( $this->input->get ( 'sSearch' ) );
            if(!empty($sSearch)){
                $where .= "
				AND (
				cat_id LIKE '%{$sSearch}%'
				OR
				category_name LIKE '%{$sSearch}%'
				)
				";
            }

            $sSearch_0 = $this->input->get ( 'sSearch_0' );
            if (! empty ( $sSearch_0 )) {
                $where .= "
				AND 
				cat_id LIKE '%{$sSearch_0}%'
				
				";
            }

            $sSearch_1 = mysql_real_escape_string ( $this->input->get ( 'sSearch_1' ) );
            if (! empty ( $sSearch_1 )) {
                $where .= " AND  category_name LIKE '%{$sSearch_1}%' ";
            }

            // 排序
            $orderby = 'cat_id DESC';
            if (isset ( $_GET ['iSortCol_0'] )) {
                for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
                    if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
                        $orderby = $fields [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
                    }
                }
            }

            $output ['sEcho'] = intval ( $_GET ['sEcho'] );
            $output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->category_model->count ( $where );
			
            $output ['aaData'] = $this->category_model->get ( $where, $limit, $offset, $orderby );
			//echo $this->db->last_query();
            foreach ( $output ['aaData'] as $item ) {

                $item->operation = '<div class="btn-group"><a class="btn btn-xs btn-info" href="' . $this->zjjp . 'category/category' . '/edit?id=' . $item->cat_id . '">编辑</a><button data-toggle="dropdown" class="btn btn-xs btn-info btn-white dropdown-toggle">
								更多
								<span class="ace-icon fa fa-caret-down icon-only"></span>
							</button>
							<ul class="dropdown-menu dropdown-info dropdown-menu-right">';

                $item->operation .= '
					<li><a href="javascript:;" title="删除" onclick="del(' . $item->cat_id . ')">删除</a></li>
				</ul></div>';
            }
            exit ( json_encode ( $output ) );
        }

        $this->_view ( 'category_index');
    }

    /**
     * 添加ppt
     */
    function add() {
        $list = $this->category_model->get();
        $list1 = array();
        foreach ($list as $key=>$va) {
            $list1[$key]['id'] = $va->cat_id;
            $list1[$key]['pId'] = $va->pid;
            $list1[$key]['name'] = $va->category_name;
        }
        $this->_view ( 'category_edit',array (
            'list' => json_encode($list1)
        )  );
    }

    /**
     * [del 删除]
     *
     * @return [type] [description]
     */
    function del() {
        $id = intval ( $this->input->get ( 'id' ) );
        if (! empty ( $id )) {
            $where = 'cat_id = ' . $id;
            $this->category_model->delete ( $where );
            ajaxReturn ( '', '', 1 );
        }
        ajaxReturn ( '', '', 0 );
    }
    /**
     * 编辑
     */
    function edit() {
        $id = intval ( $this->input->get ( 'id' ) );

        if ($id) {
            $where = "cat_id={$id}";
            $info = $this->category_model->get_one ( $where );

            $p_info = $this->category_model->get_one("cat_id={$info->pid}");
            $info->pid_name = $p_info->category_name;

            if (empty ( $info )) {
                ajaxReturn ( '', '该分类不存在', 0 );
            }
        }

        $list = $this->category_model->get();
        $list1 = array();
        foreach ($list as $key=>$va) {
            $list1[$key]['id'] = $va->cat_id;
            $list1[$key]['pId'] = $va->pid;
            $list1[$key]['name'] = $va->category_name;
        }

        $this->_view ( 'category_edit', array (
            'list' => json_encode($list1),
            'info' => $info,
            'id' => $id,
        ) );
    }

    // 更新
    function update() {
        $id = intval ( $this->input->post ( 'id' ) );
        $data = $this->input->post ();
        unset($data['id']);
        if ($id) {
            // 保存基本信息
           $flag = $this->category_model->save ( $id, $data );

        }else{
           $flag = $this->category_model->save ( null, $data );
        }

        if($flag) {
            ajaxReturn ( '', '保存成功', 1 );
        }
        ajaxReturn ( '', '保存失败', 0 );
    }

    /**
     * 设置列表字段
     */
    private function _set_lists_field() {
        return array (
            '',
            'cat_id',
            'pid',
            'user_id',
            'category_name'
        );
    }

    /**
     * 上传
     *
     * @return string
     */
    private function _upload() {
        $config = array (
            'save_path' => '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
            'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
            'allowed_types' => 'xls|xlsx',
            'file_name' => time () . rand ( 100000, 999999 )
        );

        if (! empty ( $config )) {
            $this->load->library ( 'upload', $config );
            // 创建目录
            mk_dir ( $config ['upload_path'] );

            if (! $this->upload->do_upload ( 'file' )) {
                ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
            } else {
                $imgdata = $this->upload->data ();
                return $config ['save_path'] . '/' . $imgdata ['file_name'];
            }
        }
    }

}