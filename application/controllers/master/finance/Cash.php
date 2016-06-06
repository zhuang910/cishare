<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Cash extends Master_Basic{
	//收费类型
	public $config_type = array( 
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
    	14 => '电费押金',
    	15 => '申请减免费',
    );
    //金钱单位
	public $config_money = array(
		1 => '美元',
		2 => '人民币'
		);
	//状态
	public $config_state =array(
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
		$this->load->model($this->view.'cash_model');
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
			$where='zust_cash.id <> 0';
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_student_info.chname LIKE '%{$sSearch_0}%')";
			}
			$sSearch_1 =  $_GET['sSearch_1' ];
			if (! empty ( $sSearch_1 )) {
				$where .= " AND ( zust_student_info.enname LIKE '%{$sSearch_1}%')";
			}
			$sSearch_2 =  $_GET['sSearch_2' ];
			if (! empty ( $sSearch_2 )) {
				$where .= " AND ( zust_student_info.passport LIKE '%{$sSearch_2}%')";
			}
			$sSearch_3 =  $_GET['sSearch_3' ];
			if(isset($sSearch_3) && $sSearch_3 !='') {
				$where .= " AND ( zust_cash.type = {$sSearch_3})";
			}
			$sSearch_4 =  $_GET['sSearch_4' ];
			if (! empty ( $sSearch_4 )) {
				$where .= " AND ( zust_cash.number LIKE '%{$sSearch_4}%')";
			}
			$sSearch_5 =  $_GET['sSearch_5' ];
			if (! empty ( $sSearch_5 )) {
				$where .= " AND ( zust_cash.amount LIKE '%{$sSearch_5}%')";
			}
			$sSearch_6 =  $_GET['sSearch_6' ];
			if (! empty ( $sSearch_6 )) {
				$where .= " AND ( zust_cash.id LIKE '%{$sSearch_6}%')";
			}


			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->cash_model->count ( $where);
			$output ['aaData'] = $this->cash_model->get ( $where, $limit, $offset, $orderby );

			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->id;//ID
				$val->field_1 = $val->chname;//ID
				$val->field_2 = $val->enname;//ID
				$val->field_3 = $val->passport;//护照号
				$val->field_4 = isset($this->config_type[$val->type]) ? $this->config_type[$val->type] : '--';//收支类型
				$val->field_5 = $val->number;//收据号
				$val->field_6 = $val->amount;//实缴费用
				$val->field_7 = isset($this->config_money[$val->currency]) ? $this->config_money[$val->currency] : '--';//金钱单位
				
				$val->field_8 = isset($this->config_state[$val->state]) ? $this->config_state[$val->state] : '--';//状态
				$val->field_9 = date('Y-m-d',$val->createtime);//创建时间
				$val->field_10 = '
								<a class="green" title="编辑修改备注" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . '/cash/outline?id=' . $val->id . '&s=1\')"><i class="ace-icon fa fa-leaf bigger-130"></i></a><br />
								<span style="color:#F00;"><a href="javascript:pub_alert_html(\'cash/editproof?id=' . $val->id . '\');">查看凭据</a></span>
								';
				
			}
			exit ( json_encode ( $output ) );
		}

        $this->_view('cash_index');
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


    /**
	 * [outline 备注页面]
	 * @return [type] [description]
	 */
	function outline(){
		$id=intval($this->input->get('id'));
		$remark=$this->cash_model->get_student_remark($id);
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'cash_outline', array (
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
			$this->db->update('cash',$arr,'id = '.$data['id']);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',1);
	}

	/**
	 * 查看凭据
	 */
	function editproof() {
		$id = $this->input->get ( 'id' );
		$file = $this->db->get_where ( 'cash', 'id = ' . $id )->row_array ();
		$html = $this->_view ( 'cash_editport.php', array (
				'img' => ! empty ( $file['file'] ) ? $file['file'] : '' 
		), true );
		
		ajaxReturn ( $html, '', 1 );
	}
}