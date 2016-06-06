<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Budget extends Master_Basic {
	//收费类型
	public $config_type = array( 
    	1 => '申请费',
    	2 => '押金',
    	3 => '接机费',
    	4 => '住宿费',
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
    	15 => '申请减免学费',
    	16=>'奖学金费用'
    );


    //支付状态
	public $config_state =array(
		0 => '未支付',
		1 =>  '成功' , 
		2 => '失败', 
		3 =>  '待审核'
		);


	//支付方式
	public $config_paytype = array(
		1 => 'paypal',
		2 => 'payease',
		3 => '汇款',
		4 => '现金',
		5 => '刷卡',
		6=>'奖学金支付',
		7=>'申请减免'
		);

	//

	public $config_school = array(
		0 => '<span class="label label-important">否</span>',
		1 => '<span class="label label-success">是</span>'
		);


	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model($this->view.'budget_model');
		$this->update_info();

	}
	function update_info(){
		$info=$this->db->get_where('budget','paytype = 6')->result_array();
		if(!empty($info)){
			foreach ($info as $key => $value) {
				$is_scholarship=array(
					'is_scholarship'=>1
					);
				$this->db->update('budget',$is_scholarship,'id = '.$value['id']);
			}
		}
	}
	/**
	 * 后台主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
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
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '1';
			$where='zust_budget.budget_type = '.$label_id ;
			$sSearch_0 =  $_GET['sSearch_0' ];
			if (! empty ( $sSearch_0 )) {
				$where .= " AND ( zust_budget.id LIKE '%{$sSearch_0}%')";
			}
			$sSearch_1 =  $_GET['sSearch_1' ];
			if (! empty ( $sSearch_1 )) {
				$where .= " AND ( zust_student_info.chname LIKE '%{$sSearch_1}%')";
			}
			$sSearch_2 =  $_GET['sSearch_2' ];
			if (! empty ( $sSearch_2 )) {
				$where .= " AND ( zust_student_info.passport LIKE '%{$sSearch_2}%')";
			}
			$sSearch_3 =  $_GET['sSearch_3' ];//收支类型
			if(isset($sSearch_3) && $sSearch_3 !='') {
				$where .= " AND ( zust_budget.type = {$sSearch_3})";
			}
			$sSearch_4 =  $_GET['sSearch_4' ];
			if (! empty ( $sSearch_4 )) {
				$where .= " AND ( zust_budget.payable LIKE '%{$sSearch_4}%')";
			}
			$sSearch_5 =  $_GET['sSearch_5' ];
			if (! empty ( $sSearch_5 )) {
				$where .= " AND ( zust_budget.paid_in LIKE '%{$sSearch_5}%')";
			}
			$sSearch_6 =  $_GET['sSearch_6' ];//支付状态
			if (isset($sSearch_6) && $sSearch_6 !='') {
				$where .= " AND ( zust_budget.paystate = {$sSearch_6})";
			}
			$sSearch_7 =  $_GET['sSearch_7' ];//支付方式
			if (isset($sSearch_7) && $sSearch_7 !='') {
				$where .= " AND ( zust_budget.paytype = {$sSearch_7})";
			}
			$sSearch_8 =  $_GET['sSearch_8' ];
			if (isset($sSearch_8) && $sSearch_8 !='') {
				$where .= " AND ( zust_budget.term = {$sSearch_8})";
			}

			// 输出
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->budget_model->count ( $where);
			$output ['aaData'] = $this->budget_model->get ( $where, $limit, $offset, $orderby );
			foreach ( $output ['aaData'] as $val ) {
				$val->field_0 = $val->id;//ID
				$val->field_1 = $val->chname;//用户名
				$val->field_2 = $val->passport;//护照号
				$val->field_3 = isset($this->config_type[$val->type]) ? $this->config_type[$val->type] : '--';//收支类型
				if($val->type==16){
					//是否有余额可退
					if($val->is_get_scholarship_money==2){
						//未退
						$val->field_3.='+<a href="javascript:;"onclick="tuifei(' . $val->id . ')" >未退费</a>';
					}elseif($val->is_get_scholarship_money==1){
						//已经退
						$val->field_3.='+已退费';
					}
				}
				if($label_id==1){
					$val->field_4 = $val->payable;//应缴费用
					$val->field_5 = $val->paid_in;//实缴费用
					$val->field_7 = date('Y-m-d',$val->paytime);//支付时间
					$val->field_8 = isset($this->config_paytype[$val->paytype])? $this->config_paytype[$val->paytype]:'--';//支付方式
					if(!empty($val->proof_number)&&!empty($val->file_path)){
						$val->field_8.='<a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'budget/look_file_path?id=' . $val->id . '&s=1\')"  title="查看收据" id="upstate">查看</a>';
					}
				$val->field_6 = isset($this->config_state[$val->paystate])?$this->config_state[$val->paystate]:'--';//支付状态
					
				}elseif($label_id==2){
					$val->field_4 = $val->should_returned;//应退费用
					$val->field_5 = $val->true_returned;//实退费用
					$val->field_7 = date('Y-m-d',$val->returned_time);//退费时间

				}
				
				$val->field_9 = !empty($val->term) ? '第'.$val->term.'学期' : '--';//学期
				$val->field_10 = isset($this->config_school[$val->is_scholarship])? $this->config_school[$val->is_scholarship]:'--';//奖学金用户
				
			}
			exit ( json_encode ( $output ) );
		}

		$num = $this->budget_model->get_term();
		foreach ($num as $key => $value) {
			$maxnum = $value;
		}

        $this->_view('budget_index',array(
        	'maxnum' => $maxnum,
        	'label_id' => $label_id
        	));
    }
	
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'budget_type',
				'type',
				'payable',
				'paid_in',
				'should_returned',
				'true_returned',
				'returned_time',
				'paystate',
				'paytime',
				'paytype',
				'term',
				'is_scholarship',
				'createtime',
				'adminid',
				'lasttime',
				'remark'
		);
	}
	/**
	 * [look_file_path 查看收据信息]
	 * @return [type] [description]
	 */
	function look_file_path(){
		$id=$this->input->get('id');
		$info=$this->db->get_where('budget','id = '.$id)->row_array();
		if(!empty($id)){
			$html = $this->_view('look_file_path',array(
               'info'=>!empty($info)?$info:array(),
            ),true);
   		    ajaxReturn($html,'',1);
		}
	}
	/**
	 * [tuifei 退费]
	 * @return [type] [description]
	 */
	function tuifei(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$this->db->update('budget',array('is_get_scholarship_money'=>1),'id = '.$id);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [look_file_path 查看收据信息]
	 * @return [type] [description]
	 */
	function excel_where(){
		
			$html = $this->_view('excel_where',array(
            ),true);
   		    ajaxReturn($html,'',1);
		}
	/**
	 * [export 导出已经交书费的学生]
	 * @return [type] [description]
	 */
	function export_type(){
		$this->load->library ( 'sdyinc_export' );
		$data=$this->input->post();
		if(empty($data['starttime'])){
			$data['starttime']='1970-01-01';
		}
		if(empty($data['endtime'])){	
			$data['endtime']='2037-01-01';
		}
			$d = $this->sdyinc_export->do_budget_type ($data);
			if (! empty ( $d )) {
				$this->load->helper ( 'download' );
				force_download ( 'book' . time () . '.xlsx', $d );
				return 1;
			}
	}
}