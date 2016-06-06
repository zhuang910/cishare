<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Electric_info extends Master_Basic{

    //金钱单位
	public $config_state = array(
		0 => '未缴纳',
		1 =>  '已缴纳' , 
		2 => '失败', 
		3 =>  '待缴纳'
		);
	//状态
	public $config_type =array(
		0 => '待支付',
		1 =>  '成功' , 
		2 => '失败', 
		3 =>  '待审核'
		);

	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model($this->view.'electric_info_model');
	}

		/**
	 * 后台主页
	 */
	function index() {
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
			$where='zust_electric_info.id <> 0';
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_electric_info.id LIKE '%{$sSearch_0}%')";
			}
			$sSearch_1 =  $_GET['sSearch_1' ];
			if (! empty ( $sSearch_1 )) {
				$where .= " AND ( zust_student_info.chname LIKE '%{$sSearch_1}%')";
			}
			$sSearch_2 =  $_GET['sSearch_2' ];
			if (! empty ( $sSearch_2 )) {
				$where .= " AND ( zust_student_info.enname LIKE '%{$sSearch_2}%')";
			}
			$sSearch_3 =  $_GET['sSearch_3' ];
			if(! empty ( $sSearch_3 )) {
				$where .= " AND ( zust_student_info.passport LIKE '%{$sSearch_3}%')";
			}
			$sSearch_4 =  $_GET['sSearch_4' ];
			if (! empty ( $sSearch_4 )) {
				$where .= " AND ( zust_electric_info.paid_in LIKE '%{$sSearch_4}%')";
			}

			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->electric_info_model->count ( $where);
			$output ['aaData'] = $this->electric_info_model->get ( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->id;//ID
				$val->field_1 = $val->chname;//中文名字
				$val->field_2 = $val->enname;//英文名字
				$val->field_3 = $val->passport;//护照号
				$val->field_4 = $val->paid_in;//实缴费用
				$val->field_5 = isset($this->config_state[$val->paystate]) ? $this->config_state[$val->paystate] : '--';//收支类型;//
				$val->field_6 = isset($this->config_type[$val->paytype]) ? $this->config_type[$val->paytype] : '--';//收支类型
				$val->field_7 = date('Y-m-d',$val->paytime);//收费时间
				
			}
			exit ( json_encode ( $output ) );
		}

        $this->_view('electric_info_index');
    }



	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'paid_in',
				'paystate',
				'paytype',
				'paytime'
		);
	}



}