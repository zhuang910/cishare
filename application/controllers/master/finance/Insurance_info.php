<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Insurance_info extends Master_Basic{
	//收费类型
	public $config_type = array( 
    	1 => '新生',
    	2 => '老生'  	
    );
    //金钱单位
	public $config_deadline = array(
			1 => '第一学期',
			2 => '第二学期',
			3 => '第三学期',
			4 => '第四学期',
			5 => '第五学期',
			6 => '第六学期',
			7 => '第七学期',
			8 => '第八学期',
			9 => '第九学期',
			10 => '第十学期',
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
		$this->load->model($this->view.'insurance_info_model');
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
			$where='zust_insurance_info.id <> 0';
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_insurance_info.id LIKE '%{$sSearch_0}%')";
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
				$where .= " AND ( zust_insurance_info.payable LIKE '%{$sSearch_4}%')";
			}
			$sSearch_5 =  $_GET['sSearch_5' ];
			if (! empty ( $sSearch_5 )) {
				$where .= " AND ( zust_insurance_info.paid_in LIKE '%{$sSearch_5}%')";
			}

			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->insurance_info_model->count ( $where);
			$output ['aaData'] = $this->insurance_info_model->get ( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->id;//ID
				$val->field_1 = $val->chname;//中文名
				$val->field_2 = $val->enname;//英文名
				$val->field_3 = $val->passport;//护照号
				$val->field_4 = $val->payable;//应缴费用
				$val->field_5 = $val->paid_in;//实缴费用
				$val->field_6 = isset($this->config_type[$val->student_type]) ? $this->config_type[$val->student_type] : '--';//是否是新生
				$val->field_7 = date('Y-m-d',$val->effect_time);//保险生效日期
				$val->field_8 = isset($this->config_deadline[$val->term]) ? $this->config_deadline[$val->term] : '--';//状态
				$val->field_9 = date('Y-m-d',$val->createtime);//创建时间
				$val->field_10 = '
								<a class="btn btn-xs btn-info"  href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . '/insurance_info/outline?id=' . $val->id . '&s=1\')">编辑修改备注</a><br />
								';
				
			}
			exit ( json_encode ( $output ) );
		}

        $this->_view('insurance_info_index');
    }



	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'payable',
				'paid_in',
				'deadline',//保险期限1半年2一年
				'student_type',//1新生2老生
				'effect_time',//保险生效日期
				'paystate',//0未支付1支付成功2支付失败3待确认
				'remark',
				'paytime'
		);
	}


    /**
	 * [outline 备注页面]
	 * @return [type] [description]
	 */
	function outline(){
		$id=intval($this->input->get('id'));
		$remark=$this->insurance_info_model->get_student_remark($id);
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'insurance_info_outline', array (
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
			$this->db->update('insurance_info',$arr,'id = '.$data['id']);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',1);
	}
}