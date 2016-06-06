	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * ºóÌ¨Ê×Ò³
	 *
	 * @author JJ
	 *        
	 */
	class DO_apply extends Master_Basic {
        protected $agencyid=0;
		/**
		 * »ù´¡Àà¹¹Ôìº¯Êý
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/agencyport/';
			$this->load->model ( $this->view . 'do_apply_model' );
            $this->agencyid=$this->get_agencyid($_SESSION['master_user_info']->id);
		}
		
		/**
		 * ºóÌ¨Ö÷Ò³
		 */
		function index() {
            $label_id=intval(trim($this->input->get('label_id')));
            if(empty($label_id)){
               $label_id=0;

            }
			// var_dump($_SESSION);exit;
			if ($this->input->is_ajax_request () === true) {
				// ÉèÖÃ²éÑ¯×Ö¶Î
				
				$fields = $this->_set_lists_field ();
				// ²éÑ¯Ìõ¼þ×éºÏ
				$condition = dateTable_where_order_limit ( $fields );
                $label_id=$this->input->get('label_id');
                if(empty($label_id)){
                    $label_id=0;

                }
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->do_apply_model->count ($this->agencyid, $condition,$label_id);
				
				$output ['aaData'] = $this->do_apply_model->get ($this->agencyid, $fields, $condition,$label_id);
                    
               // echo $this->db->last_query();exit;
				foreach ( $output ['aaData'] as $item ) {
                    $down_e='';
                    //判断是否能下载E-offer
                    if($item->e_offer_status==1){
                        $down_e='<a style="color:red" href="/master/agencyport/do_apply/down_eoffer?aid='.$item->id.'">Check e-offer</a>';
                    }
                    if(!empty($item->issubmittime)){
                        $item->issubmittime=date('Y-m-d H:i:s',$item->issubmittime);
                    }
                    $item->paystate=$this->do_apply_model->get_apply_paystate_atr($item->paystate);
                    $item->operation= '';
                    if($item->state==2||$item->state==4||$item->state==6){
                        $item->operation.= '<a class="green" title="Application Management" href="/master/agencyport/index?userid=' . $item->userid . '" ><i class="ace-icon fa fa-leaf bigger-130"></i></a>';
                    }
                    $item->state=$this->do_apply_model->get_apply_state_atr($item->state).$down_e;

                }
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'do_apply_index' ,array(
                'label_id'=>$label_id
				));
		}
		/**
		 * ÉèÖÃÁÐ±í×Ö¶Î
		 */
		private function _set_lists_field() {
			return array (
				"cucas_apply_info.id",
				"cucas_student_info.chname",
				"cucas_student_info.enname",
				"cucas_student_info.email",
				"cucas_student_info.passport",
                "cucas_major.englishname",
                "cucas_apply_info.state",
                "cucas_apply_info.issubmittime",
                "cucas_apply_info.commission",
                "cucas_apply_info.paystate",
                "cucas_apply_info.courseid",
                "cucas_apply_info.e_offer_status",
                "cucas_apply_info.userid",
			);
		}
        /**
         * 获取中介公司的账号id
         */
        function get_agencyid($userid){
            $data=$this->db->select ( 'id' )->get_where ( 'agency_info', 'userid = ' . $userid )->row_array ();
            return $data['id'];
        }

        /**
         * 下载e-offer
         */
        function down_eoffer(){
            $aid=intval(trim($this->input->get('aid')));
            if(!empty($aid)){
                //获取本申请的e-offer地址
                $e_path=$this->do_apply_model->get_apply_e_path($aid);
                if(!empty($e_path)){
                    $this->load->helper('download');
                    $data = @file_get_contents($e_path);
                    force_download($aid.'e_offer.pdf', $data);
                }
            }
        }
}