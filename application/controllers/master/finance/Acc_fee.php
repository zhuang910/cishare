<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Acc_fee extends Master_Basic{
	//收费类型
	public $config_type = array( 
    	1 => 'paypal',
    	2 => 'payease',
    	3 => '汇款',
    	4 => '现金',
    	5 => '刷卡',
    	6 => '奖学金支付'
    );
	//状态
	public $config_state =array(
		0 => '未缴纳',
		1 =>  '已缴纳' , 
		2 => '失败', 
		3 =>  '处理中'
		);

	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model($this->view.'acc_fee_model');
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
			$where='zust_acc_fee.id <> 0';
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_acc_fee.id LIKE '%{$sSearch_0}%')";
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
			if (isset($sSearch_4) && $sSearch_4) {
				$where .= " AND ( zust_acc_fee.day LIKE '%{$sSearch_4}%')";
			}
			$sSearch_5 =  $_GET['sSearch_5' ];
			if (! empty ( $sSearch_5 )) {
				$where .= " AND ( zust_acc_fee.menoy LIKE '%{$sSearch_5}%')";
			}
			$sSearch_6 =  $_GET['sSearch_6' ];
			$sSearch_6 =strtotime($sSearch_6);
			if (! empty ( $sSearch_6 )) {
				$where .= " AND ( zust_acc_fee.paytime LIKE '%{$sSearch_6}%')";
			}


			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_fee_model->count ( $where);
			$output ['aaData'] = $this->acc_fee_model->get ( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->id;
				$val->field_1 = $val->chname;
				$val->field_2 = $val->enname;
				$val->field_3 = $val->passport;
				$val->field_4 = $val->day;
				$val->field_5 = $val->menoy;
				$val->field_6 = isset($this->config_state[$val->paystate]) ? $this->config_state[$val->paystate] : '--';
				$val->field_7 = isset($this->config_type[$val->paytype]) ? $this->config_type[$val->paytype] : '--';
				$val->field_8 = date('Y-m-d',$val->paytime);
				
				
			}
			exit ( json_encode ( $output ) );
		}

        $this->_view('acc_fee_index');
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
				'createtime'
		);
	}


    
}