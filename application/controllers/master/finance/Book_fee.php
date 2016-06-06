<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Book_fee extends Master_Basic{


	//状态
	public $config_state =array(
		0 => '未支付',
		1 =>  '成功' , 
		2 => '失败', 
		3 =>  '待确认'
		);

	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model($this->view.'book_fee_model');
	}

		/**
	 * 后台主页
	 */
	function index() {
		$major_id = $this->input->get('major_id');
		$term = $this->input->get('term');
		$squad_id = $this->input->get('squad_id');

		$major_id = !empty($major_id) ? $major_id : 0;
		$squad_id = !empty($squad_id) ? $squad_id : 0;
		$term = !empty($term) ? $term : 0;
		if ($this->input->is_ajax_request () === true) {
			$field = $this->_set_lists_field ();
			
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}

			// 排序
			$orderby = null;
			if (isset ( $_GET ['iSortCol_0'] )) {
				for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
					// bSortable_x:false
					if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
						$orderby = $field [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
					}
				}
			}
			$major_id = $this->input->get('major_id');
			$major_id = !empty($major_id) ? $major_id : 0;
			$squad_id = $this->input->get('squad_id');
			$squad_id = !empty($squad_id) ? $squad_id : 0;
			$term = $this->input->get('term');
			$term = !empty($term) ? $term : 0;

			$where='zust_books_fee.id <> 0';
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_books_fee.id LIKE '%{$sSearch_0}%')";
			}
			$sSearch_1 =  $_GET['sSearch_1' ];
			if (! empty ( $sSearch_1 )) {
				$where .= " AND ( zust_student.name LIKE '%{$sSearch_1}%')";
			}
			$sSearch_2 =  $_GET['sSearch_2' ];
			if (! empty ( $sSearch_2 )) {
				$where .= " AND ( zust_student.enname LIKE '%{$sSearch_2}%')";
			}
			$sSearch_3 =  $_GET['sSearch_3' ];
			if(! empty ( $sSearch_3 )) {
				$where .= " AND ( zust_student.passport LIKE '%{$sSearch_3}%')";
			}
			$sSearch_4 =  $_GET['sSearch_4' ];
			if (! empty ( $sSearch_4 )) {
				$where .= " AND ( zust_books_fee.last_money LIKE '%{$sSearch_4}%')";
			}
			$sSearch_5 =  $_GET['sSearch_5' ];
			if (! empty ( $sSearch_5 )) {
				$where .= " AND ( zust_books_fee.paid_in LIKE '%{$sSearch_5}%')";
			}

			//专业名
			$majorname = $this->db->get('zust_major')->result_array();			
			foreach ($majorname as $m => $n) {
				$config_major[$n['id']] = $n['name'];
			}
			//班级名
			$squadname = $this->db->get('zust_squad')->result_array();			
			foreach ($squadname as $w => $d) {
				$config_squad[$d['id']] = $d['name'];
			}
			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->book_fee_model->count ( $where);
			$output ['aaData'] = $this->book_fee_model->get ( $where, $limit, $offset, $orderby ,$major_id,$term,$squad_id);
			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->bookid;//ID
				$val->field_1 = $val->name;//中文名字
				$val->field_2 = $val->enname;//英文名字
				$val->field_3 = $val->passport;//护照号
				// $val->field_4 = $val->majorid;//专业
				$val->field_4 = isset($config_major[$val->majorid]) ? $config_major[$val->majorid] : '--';//专业
				$val->field_5 = '第'.$val->term.'学期';//学期
				// $val->field_6 = $val->squadid;//班级
				$val->field_6 = isset($config_squad[$val->squadid]) ? $config_squad[$val->squadid] : '--';//班级
				$val->field_7 = $val->last_money;//应缴金额
				// $val->field_7 = isset($this->config_money[$val->currency]) ? $this->config_money[$val->currency] : '--';//金钱单位
				$val->field_8 = $val->paid_in;//实缴金额
				// $val->field_9 = $val->paystate;//状态
				$val->field_9 = isset($this->config_state[$val->paystate]) ? $this->config_state[$val->paystate] : '--';//状态
				$val->field_10 = date('Y-m-d',$val->createtime);//创建时间
				$val->field_11 = '
								<a class="green" title="编辑修改备注" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . '/book_fee/outline?id=' . $val->bookid . '&s=1\')"><i class="ace-icon fa fa-leaf bigger-130"></i></a>	
								<a class="green" title="发书管理" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'book_fee/edit_book/?&id='.$val->bookid.'&s=1\')"><i class="normal-icon ace-icon fa fa-book pink bigger-130"></i></a>
								';
				
			}
			exit ( json_encode ( $output ) );
		}

		$major_info=$this->db->get_where('major','state = 1')->result_array();
		$major_info_one=$this->db->get_where('major','id = '.$major_id.' AND state = 1')->row_array();
		$squad_info=$this->db->get_where('squad','majorid='.$major_id.' AND nowterm = '.$term)->result_array();
        $this->_view('book_fee_index',array(
        	'major_id' => $major_id,
        	'squad_id' => $squad_id,
        	'major_info'=>$major_info,
        	'squad_info' => $squad_info,
        	'major_info_one'=>!empty($major_info_one)?$major_info_one:array(),
        	'term'=>$term
        	));
    }



	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'type',
				'number',
				'amount',
				'file',
				'currency',
				'state',
				'remark',
				'createtime',
				'book_ids'
		);
	}


    /**
	 * [outline 备注页面]
	 * @return [type] [description]
	 */
	function outline(){
		$id=intval($this->input->get('id'));
		$remark=$this->book_fee_model->get_student_remark($id);
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'book_fee_outline', array (
				'remark'=>$remark,
				'id'=>$id
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}

	/**
	 * [edit_remark 编辑学生备注]
	 * @return [type] [description]
	 */
	function edit_remark(){
		$data=$this->input->post();
		if(!empty($data['id'])){
			$arr['remark']=$data['remark'];
			$this->db->update('books_fee',$arr,'id = '.$data['id']);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',1);
	}

/**
	 * [edit_book 弹框]
	 * @return [type] [description]
	 */
	function edit_book(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval($this->input->get ( 'id' ));
		$str = $this->db->get('zust_books_fee')->row_array();
		//获取
		$s_b_info=$this->book_fee_model->get_student_book_info($str['book_ids']);
		if (! empty ( $s )) {
			$html = $this->_view ( 'edit_books', array (
				's_b_info' => $s_b_info
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}


}