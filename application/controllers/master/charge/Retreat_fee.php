<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Retreat_fee extends Master_Basic {
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/charge/';
            $this->load->model ( $this->view . 'retreat_fee_model' );
            $this->load->model ( $this->view . 'pay_model' );

		}
		
		/**
		 * 后台主页
		 */
		function index() {
			$this->_view ( 'retreat_index' );
		}
		/**
		 * [get_student_quick 快捷查找学生]
		 * @return [type] [description]
		 */
		function get_student_quick(){
			$key=$this->input->get('key');
			$value=$this->input->get('value');
			if(!empty($key)&&!empty($value)){
				$sdata=$this->pay_model->get_where_student($key,$value);
			}
			if(empty($sdata)){
				ajaxreturn('','没有所查询的学生',2);
			}
			$data['stu']=$sdata;
			if(!empty($sdata)){
				ajaxReturn($data,'',1);
			}
		}
		/**
		 * [quick_pay 快速缴费]
		 * @return [type] [description]
		 */
		function quick_pay(){
			$s=intval($this->input->get('s'));
			$userid=intval($this->input->get('userid'));
			//实收学费 新生默认是第一学期的
			$tuition=$this->pay_model->get_tuition($userid);
			//实收电费的值是在配置文件里提出
			$electric_= CF('electric','',CONFIG_PATH);
			if(!empty($electric_)&&$electric_['electric']=='yes'&&!empty($electric_['electricmoney'])){
				$electric=$electric_['electricmoney'];
			}else{
				$electric=0;
			}
			//获取书费
			$book=$this->pay_model->get_book($userid);
			//实收住宿费是在该学生英住了多少天的算出的费用
			$acc=$this->pay_model->get_acc($userid);
			//保险费
			$insurance=0;
			//住宿押金
			$acc_pledge_= CF('acc_pledge','',CONFIG_PATH);
			if(!empty($acc_pledge_)&&$acc_pledge_['acc_pledge']=='yes'&&!empty($acc_pledge_['acc_pledgemoney'])){
				$acc_pledge=$acc_pledge_['acc_pledgemoney'];
			}else{
				$acc_pledge=0;
			}
			$total=$tuition+$electric+$book+$acc+$insurance+$acc_pledge;
			//判断是不是奖学金用户
			$scholarship_info=array();
			$is_scholarship=array();
			$is_scholarship=$this->pay_model->get_scholarship_user($userid);
			if(!empty($is_scholarship['scholorshipid'])){
				$scholarship_info=$this->pay_model->get_scholarship_info($is_scholarship);
			}
			if(!empty($s)){
				$html = $this->_view ( 'quick_pay', array (
				'tuition_p'=>$tuition,
				'electric_p'=>$electric,
				'book_p'=>$book,
				'acc_p'=>$acc,
				'insurance_p'=>$insurance,
				'acc_pledge_p'=>$acc_pledge,
				'total'=>$total,
				'userid'=>$userid,
				'is_scholarship'=>$is_scholarship,
				'scholarship_info'=>$scholarship_info
				), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		/**
		 * [sub_pay 插入各个费用]
		 * @return [type] [description]
		 */
		function sub_pay(){
			$data=$this->input->post();
			if(!empty($data['scholarship'])&&!empty($data['scholarship_p'])){
				if(!empty($data['arrearage_p'])){
					$data['scholarship_p']+=$data['arrearage_p'];
				}
				//总钱数
				$total_money=0;
					//插入学费表
				if(!empty($data['tuition'])&&!empty($data['tuition_p'])&&!empty($data['userid'])){
					$total_money+=$data['tuition_p'];
				}
				//插入电费表
				if(!empty($data['electric'])&&!empty($data['electric_p'])&&!empty($data['userid'])){
					$total_money+=$data['electric_p'];
				}
				//插入住宿费
				if(!empty($data['acc'])&&!empty($data['acc_p'])&&!empty($data['userid'])){
					$total_money+=$data['acc_p'];
				}
				//插入押金表
				if(!empty($data['acc_pledge'])&&!empty($data['acc_pledge_p'])&&!empty($data['userid'])){
					$total_money+=$data['acc_pledge_p'];
				}
				//奖学金余额
				$scholarship_balance=$data['scholarship_p']-$total_money;
				if($scholarship_balance>=0){
					$this->pay_model->insert_tuition($data['userid'],$data['tuition_p'],1);
					$this->pay_model->insert_electric($data['userid'],$data['tuition_p']);
					$this->pay_model->insert_acc($data['userid'],$data['acc_p']);
					$this->pay_model->insert_acc_pledge($data['userid'],$data['acc_pledge_p']);
					ajaxreturn('','缴费成功',1);

				}else{
					$arrearage=$total_money-$data['scholarship_p'];
					ajaxReturn($arrearage,'',3);
				}
			}
			//插入学费表
			if(!empty($data['tuition'])&&!empty($data['tuition_p'])&&!empty($data['userid'])){
				$this->pay_model->insert_tuition($data['userid'],$data['tuition_p'],1);
			}
			//插入电费表
			if(!empty($data['electric'])&&!empty($data['electric_p'])&&!empty($data['userid'])){
				$this->pay_model->insert_electric($data['userid'],$data['tuition_p']);
			}
			//插入住宿费
			if(!empty($data['acc'])&&!empty($data['acc_p'])&&!empty($data['userid'])){
				$this->pay_model->insert_acc($data['userid'],$data['acc_p']);
			}
			//插入押金表
			if(!empty($data['acc_pledge'])&&!empty($data['acc_pledge_p'])&&!empty($data['userid'])){
				$this->pay_model->insert_acc_pledge($data['userid'],$data['acc_pledge_p']);
			}
			//为开发完
			ajaxreturn('','缴费成功',1);

		}
		/**
		 * [sub_pay 插入各个费用]
		 * @return [type] [description]
		 */
		function count_scholarship(){
			$data=$this->input->post();
			if(!empty($data['scholarship'])&&$data['scholarship_p']){
				//总钱数
				$total_money=0;
					//插入学费表
				if(!empty($data['tuition'])&&!empty($data['tuition_p'])&&!empty($data['userid'])){
					$total_money+=$data['tuition_p'];
				}
				//插入电费表
				if(!empty($data['electric'])&&!empty($data['electric_p'])&&!empty($data['userid'])){
					$total_money+=$data['electric_p'];
				}
				//插入住宿费
				if(!empty($data['acc'])&&!empty($data['acc_p'])&&!empty($data['userid'])){
					$total_money+=$data['acc_p'];
				}
				//插入押金表
				if(!empty($data['acc_pledge'])&&!empty($data['acc_pledge_p'])&&!empty($data['userid'])){
					$total_money+=$data['acc_pledge_p'];
				}
				//奖学金余额
				$scholarship_balance=$data['scholarship_p']-$total_money;
				if($scholarship_balance>=0){
					ajaxreturn('','',1);
				}else{
					$arrearage=$total_money-$data['scholarship_p'];
					ajaxReturn($arrearage,'',0);
				}
			}

		}
		/**
		 * [type_pay 按类别缴费]
		 * @return [type] [description]
		 */
		function type_pay(){
			$s=intval($this->input->get('s'));
			$userid=intval($this->input->get('userid'));
			if(!empty($s)){
				$html = $this->_view ( 'type_pay', array (
				'userid'=>$userid
				), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
	/**
		 * [type_pay 按类别缴费也没页面]
		 * @return [type] [description]
		 */
		function type_pay_page(){
			$userid=intval($this->input->get('userid'));
			$this->_view ( 'type_pay_page' , array (
				'userid'=>$userid
				));
		}
	/**
	 * [type_tuition 按类别交学费]
	 * @return [type] [description]
	 */
	function type_tuition(){
		$userid=$this->input->get('userid');
		if(!empty($userid)){
			
			//获取该学生的专业信息
			$major_info=$this->pay_model->get_major_info($userid);
			ajaxreturn($major_info,'',1);
		}
	}
	/**
	 * [get_major_term_tuition 获取当前学期学费是否支付的状态]
	 * @return [type] [description]
	 */
	function get_major_term_tuition(){	
		$majorid=intval($this->input->get('majorid'));
		$term=intval($this->input->get('term'));
		$userid=intval($this->input->get('userid'));
		if(!empty($majorid)&&!empty($term)&&!empty($userid)){
				
			//获取当前学期的学费
			$term_tuition=$this->pay_model->get_term_tuition($majorid,$term);
			//判断这个学期的学费交没交
			$is=$this->pay_model->is_tuition($majorid,$term,$userid);
			//判断是不是续读生
			$is_repe=0;
			$abatementmoney=0;
			//查看学生是否为续读生
			$is_repetition=$this->pay_model->get_student_info($userid);
			if(!empty($is_repetition['is_repetition'])&&$is_repetition['is_repetition']==1){
				//是续读生   查看续读生开关和减免比例
				$abatement=CF('abatement','',CONFIG_PATH);
				if(!empty($abatement['abatement'])&&$abatement['abatement']=='yes'&&!empty($abatement['abatementmoney'])){
					$is_repe=1;
					//取出面减免额度
					$abatementmoney=$abatement['abatementmoney'];
					$discount_money=($term_tuition['tuition']*$abatementmoney)/100;
				}
			}

			//查看该学期有没有补缴学的重修费
			//先查看重修费开关
			$tuition=CF('tuition','',CONFIG_PATH);
			//总重修费
			$total_rebuild=0;
			//重修id们
			$rebuild_ids='';
			if($tuition['repair_fee']=='yes'){
				//查找该学生缴费学期下有没有重修费
				$rebuild=$this->pay_model->get_rebuild($term,$userid);
				if(!empty($rebuild)){
					foreach ($rebuild as $k => $v) {
						$rebuild_ids.=$v['id'].'-grf-';
						$total_rebuild+=$v['money'];
					}
					$rebuild_ids=trim($rebuild_ids,'-grf-');
				}
			}

			//查看该学期有没有补缴的换证费
			//先查看换证费开关
			$tuition=CF('tuition','',CONFIG_PATH);
			//总重修费
			$total_barter_card=0;
			$barter_card_ids='';
			if($tuition['replacement']=='yes'){
				//查找该学生缴费学期下有没有重修费
				$barter_card=$this->pay_model->get_barter_card($term,$userid);
				if(!empty($barter_card)){
					foreach ($barter_card as $k => $v) {
						$barter_card_ids.=$v['id'].'-grf-';
						$total_barter_card+=$v['money'];
					}
					$barter_card_ids=trim($barter_card_ids,'-grf-');
				}
			}
			
			if(!empty($discount_money)){
				$data['discount_money']=$discount_money;
			}

			//最终缴费金额
			$last_money=$discount_money+$total_rebuild+$total_barter_card;
			$data['term_tuition']=$term_tuition;
			$data['is']=$is;
			$data['is_repe']=$is_repe;
			$data['abatementmoney']=$abatementmoney;
			$data['total_rebuild']=$total_rebuild;
			$data['rebuild_ids']=$rebuild_ids;
			$data['total_barter_card']=$total_barter_card;
			$data['barter_card_ids']=$barter_card_ids;
			$data['last_money']=$last_money;
			ajaxreturn($data,'',1);
		}
		ajaxreturn('','',0);
	}
	//按类别缴费住宿费
	function type_acc(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			$info=$this->pay_model->get_typeacc_info($userid);
			//判断是不是已经欠费
			if($info['residue_days']<0){
				$data['residue_days']=Abs($info['residue_days']);
				$data['is_qianfei']=1;
				$data['qianfei_money']=$info['dayprices']*Abs($info['residue_days']);
			}

			$data['info']=$info;
			ajaxreturn($data,'',1);
		}
	}
	/**
	 * [chaange_type_select 按类别选择缴费]
	 * @return [type] [description]
	 */
	function chaange_type_select_submit(){
		$data=$this->input->post();
		//按类型交学费
		if($data['type']=='tuition'){
			$id=$this->pay_model->type_insert_tuition($data);
			if(!empty($id)){
				ajaxreturn('','交学费成功',1);
			}
			ajaxreturn('','交学费失败',0);
		}
		//按类型交电费
		if($data['type']=='electric'&&!empty($data['electric'])){
			$id=$this->pay_model->type_insert_electric($data);
			if(!empty($id)){
				ajaxreturn('','交电费成功',1);
			}
			ajaxreturn('','交电费失败',0);
		}
		//住宿费
		if($data['type']=='acc'&&!empty($data['paid_in'])){
			$id=$this->pay_model->type_insert_acc($data);
			if(!empty($id)){
				ajaxreturn('','',1);
			}
			ajaxreturn('','',0);
		}
		if($data['type']=='acc_pledge'&&!empty($data['acc_pledge'])){
			$this->pay_model->insert_acc_pledge($data['userid'],$data['acc_pledge']);
		}
		//按类型交书费
		if($data['type']=='book'){
			$id=$this->pay_model->type_insert_book_fee($data);
			if(!empty($id)){
				ajaxreturn('','',1);
			}
			ajaxreturn('','',0);
		}
	}
	/**
	 * [type_book 交书费]
	 * @return [type] [description]
	 */
	function type_book(){
		$userid=$this->input->get('userid');
		if(!empty($userid)){
			//获取该学生的专业信息
			$major_info=$this->pay_model->get_major_info($userid);
			//获取该专业的所有书籍
			$course_info=$this->pay_model->get_major_course($major_info['id']);
			$book=array();
			$book_ids='';
			if(!empty($course_info)){
				foreach ($course_info as $k => $v) {
					//获取该课程的书籍
					$book_info=$this->pay_model->get_course_book($v['courseid']);
					if(!empty($book_info)){
						foreach ($book_info as $kk => $vv) {
							//获取书籍信息
							$book[$vv['booksid']]=$this->pay_model->get_book_info($vv['booksid']);
						}
					}
				}
			}
			$book_money=0;
			if(!empty($book)){
				foreach ($book as $k => $v) {
					$book_ids.=$v['id'].'-grf-';
					$book_money+=$v['price'];
				}
				$book_ids=trim($book_ids,'-grf-');
			}
			$data['book_money']=$book_money;
			$data['book_ids']=$book_ids;
			ajaxreturn($data,'',1);
		}
		ajaxreturn('','',0);
	}

	/**
	 * [retreat_fee_page 退费弹框]
	 * @return [type] [description]
	 */
	function retreat_fee_page(){
		$userid=intval($this->input->get('userid'));
		//获取学生的学期
		$student_info=$this->db->get_where('student','userid = '.$userid)->row_array();
		if(!empty($student_info['squadid'])){
			$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
			if(!empty($squad_info['nowterm'])){
				$term=$squad_info['nowterm'];
			}
		}else{
			$term=1;
		}
		$tuition_info=$this->db->where('userid = '.$userid.' AND paystate = 1')->order_by('nowterm ASC')->get('tuition_info')->result_array();
		$total=0;
		foreach ($tuition_info as $k => $v) {
			if($v['nowterm']==$term){
				//获取专业的
		        $major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
		        $yue=0;
		        $age_tuition=0;
		        if(!empty($major_info['termdays'])){
		            $yue=ceil($major_info['termdays']/30);
		            $age_tuition=floor($v['tuition']/$yue);
		        }

		        //上了几个月了
		        $shangyue=ceil((time()-$student_info['createtime'])/30/24/3600);
		        $now_tuition=$v['tuition']-$age_tuition*$shangyue;
		        if($v['paytype']!=6&&$v['nowterm']>=$term){
					$tuition_info[$k]['tuifei']=sprintf("%.2f",$now_tuition-$now_tuition*3.36/100);
				}else{
					$tuition_info[$k]['tuifei']=0;
				}
			
			}else{
				 if($v['paytype']!=6&&$v['nowterm']>=$term){
					$tuition_info[$k]['tuifei']=sprintf("%.2f",$v['tuition']-$v['tuition']*3.36/100);
				 }else{
				 	$tuition_info[$k]['tuifei']=0;
				 }
			}

			if($v['paytype']!=6&&$v['nowterm']>=$term){
				$total+=(sprintf("%.2f",$tuition_info[$k]['tuifei'])-sprintf("%.2f",$tuition_info[$k]['refund_amounts']));
			}
		}
		$html = $this->_view ( 'student_retreat_fee', array (
			'userid'=>$userid,
			'total'=>sprintf("%.2f",$total),
			'tuition_info'=>$tuition_info
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	/**
	 * [retreat 退费]
	 * @return [type] [description]
	 */
	function retreat(){
		$data=$this->input->post();
		
        $userid=$data['userid'];
		$flag = intval($this->input->post('flag'));
		if($flag == 1){
			if(!empty($data)){
            //获取该学生班级学期
            $student_info=$this->db->get_where('student','userid = '.$userid)->row_array();
            if(!empty($student_info['squadid'])){
                //查看班级的属性
                $squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
                $term=$squad_info['nowterm'];
            }else{
                $term=1;
            }
            //先插入收支表
				$budget_arr=array(
					'userid'=>$data['userid'],
					'budget_type'=>2,
					'type'=>6,
					'term'=>$term,
					'should_returned'=>$data['should_returned'],
					'true_returned'=>$data['true_returned'],
					'returned_time'=>time(),
					'createtime'=>time(),
					'adminid'=>$_SESSION ['master_user_info']->id,
					'lasttime'=>time()
				);
				$this->db->insert('budget',$budget_arr);
				$id=$this->db->insert_id();
				$is_re = false;
				if(!empty($data['tui_info'])){
					foreach ($data['tui_info'] as $k => $v) {
						if(sprintf("%.2f",$v['tuifei']) == sprintf("%.2f",$v['refund_amounts']))
							continue;

						$y_e = (sprintf("%.2f",$v['tuifei']) - sprintf("%.2f",$v['refund_amounts']));
						if( $y_e >= sprintf("%.2f",$budget_arr['true_returned']) && $is_re === false){
							$t_arr = array(
								'refund_amounts' => (sprintf("%.2f",$budget_arr['true_returned']) + sprintf("%.2f",$v['refund_amounts']))
							);

	//                        if ($y_e == sprintf("%.2f",$budget_arr['true_returned'])) {
	//                            $t_arr['paystate'] = 2;
	//                        }

							$this->db->update('tuition_info', $t_arr, 'id = ' . $v['t_id']);

							$is_re = true;
						}else if(sprintf("%.2f",$v['refund_amounts']) > 0 && $is_re === false){

							$this->db->update('tuition_info',array(
								'refund_amounts' => $v['tuifei']//,
								// 'paystate' => 2
							),'id = '.$v['t_id']);

							$budget_arr['true_returned'] = abs(sprintf("%.2f",$budget_arr['true_returned']) - (sprintf("%.2f",$v['tuifei'])-sprintf("%.2f",$v['refund_amounts'])));
						} else if(sprintf("%.2f",$budget_arr['true_returned']) > 0 && $is_re === false){
							$this->db->update('tuition_info',array(
								'refund_amounts' => $v['tuifei']//,
							   // 'paystate' => 2
							),'id = '.$v['t_id']);

							$budget_arr['true_returned'] = abs(sprintf("%.2f",$budget_arr['true_returned']) - sprintf("%.2f",$v['tuifei']));
						}
						//$this->db->update('tuition_info',array('paystate'=>2),'id = '.$v);
					}
				}
	//			$id=$this->retreat_fee_model->retreat_student($data);
				if(!empty($id)){
					ajaxreturn('','',1);
				}
			}
		}else if($flag == 2){
			if(!empty($data['total'])){
				foreach($data['total'] as $key => $val){
					
					if(!empty($val['total'])){
						//先去获取数据
						$o_data = $this->db->get_where('tuition_info','id = '.$key)->row();
						//先插入收支表
						$budget_arr=array(
							
							'budget_type'=>2,
							'type'=>6,
							'should_returned'=>!empty($data['tui_info'][$key]['tuifei'])?$data['tui_info'][$key]['tuifei']:0,
							'true_returned'=>$val['total'],
							'returned_time'=>time(),
							'adminid'=>$_SESSION ['master_user_info']->id,
							'lasttime'=>time()
						);
						$flag_b = $this->db->update('budget',$budget_arr,'id = '.$o_data->budgetid);
						
						//更新 学费表
						$total_amounts = $val['total'] + (!empty($data['tui_info'][$key]['refund_amounts'])?$data['tui_info'][$key]['refund_amounts']:0);
						$flag_t = $this->db->update('tuition_info',array('refund_amounts' => $total_amounts),'id = '.$key);
						
						if($flag_t && $flag_b){
							ajaxReturn(''.'',1);
							
						}
					}
				}
				
			}
			
			
			
		}
		
		ajaxreturn('','',0);
	}
}