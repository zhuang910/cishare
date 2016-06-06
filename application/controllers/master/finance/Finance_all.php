<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Finance_all extends Master_Basic {
	//订单类型
	public $config_ordertype = array(
		1 => '申请报名费',
		2 => '押金',
		3 => '接机',
		4 => '住宿',
		5 => '入学押金',
		6 => '学费',
		7 => '电费',
		8 => '书费',
		9 => '保险费',
		10 => '住宿押金费',
		11 => '换证费',
		12 => '重修费',
		13 => '床品费',
		14 => '电费押金'
		);

	//支付方式
	public $config_paytype = array(
		1 => 'paypal',
		2 => 'payease',
		3 => '汇款'
		);
	//支付状态
	public $config_paystate = array(
		0 => '未支付',
		1 => '成功',
		2 => '失败',
		3 => '待审核'
		);

	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model($this->view.'finance_all_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$field = $this->_set_lists_field ();
			
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}

			// 排序
			$orderby = null;
			if (isset ( $_POST ['iSortCol_0'] )) {
				for($i = 0; $i < intval ( $_POST ['iSortingCols'] ); $i ++) {
					// bSortable_x:false
					if ($_POST ['bSortable_' . intval ( $_POST ['iSortCol_' . $i] )] == "true") {
						$orderby = $field [intval ( $_POST ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_POST ['sSortDir_' . $i] );
					}
				}
			}

			$where = 'zust_apply_order_info.id <> 0';
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_apply_order_info.id LIKE '%{$sSearch_0}%')";
			}
			$sSearch_1 =  $_GET['sSearch_1' ];
			if (! empty ( $sSearch_1 )) {
				$where .= " AND ( zust_student_info.chname LIKE '%{$sSearch_1}%')";
			}
			$sSearch_2 =  $_GET['sSearch_2' ];
			if (! empty ( $sSearch_2 )) {
				$where .= " AND ( zust_student_info.passport LIKE '%{$sSearch_2}%')";
			}
			$sSearch_3 =  $_GET['sSearch_3' ];
			if(! empty ( $sSearch_3 )) {
				$where .= " AND ( zust_apply_order_info.ordernumber LIKE '%{$sSearch_3}%')";
			}
			$sSearch_4 =  $_GET['sSearch_4' ];
			if (isset($sSearch_4) && $sSearch_4 != '') {
				$where .= " AND ( zust_apply_order_info.ordertype = {$sSearch_4})";
			}
			
			$sSearch_5 =  $_GET['sSearch_5' ];
			if (! empty ( $sSearch_5 )) {
				$where .= " AND ( zust_apply_order_info.ordermondey LIKE '%{$sSearch_5}%')";
			}

			// 查询条件组合
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->finance_all_model->count ( $where);
			$output ['aaData'] = $this->finance_all_model->get ( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $val ) {
				$val->id = $val->id;//ID
				$val->chname = $val->chname;//ID
				$val->passport = $val->passport;//护照号
				$val->ordernumber = $val->ordernumber;//订单号
				$val->ordertype = isset($this->config_ordertype[$val->ordertype]) ? $this->config_ordertype[$val->ordertype] : '--';//订单类型
				$val->ordermondey = $val->ordermondey;//总钱数
				$val->paytype = isset($this->config_paytype[$val->paytype]) ? $this->config_paytype[$val->paytype] : '--';//支付方式
				$val->paystate = isset($this->config_paystate[$val->paystate]) ? $this->config_paystate[$val->paystate] : '--';//支付状态
				$val->paytime = date('Y-m-d',$val->paytime);//支付时间
			}
			exit ( json_encode ( $output ) );
		}
		$this->_view ('finance_all_index');
	}
	
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'ordernumber',
				'ordertype',
				'ordermondey',
				'paytype',
				'paytime',
				'paystate',
		);
	}


}