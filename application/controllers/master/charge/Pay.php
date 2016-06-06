	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Pay extends Master_Basic {
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/charge/';
			$this->load->model ( $this->view . 'pay_model' );
			$this->load->model ( 'master/enrollment/acc_dispose_electric_model' );
		
		}
		
		/**
		 * 后台主页
		 */
		function index() {

			$this->_view ( 'pay_index' );
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
			//查询学生状态
			$state = $this->db->select('state')->get_where('student','userid = '.$userid)->row();
			
			if(!empty($major_info)){
				$major_info['termnum'] = 16;
				if(!empty($state->state) && $state->state == 9){
					$major_info['isliuji'] = 1;
				}else{
					$major_info['isliuji'] = 0;
				}
				
				ajaxreturn($major_info,'',1);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_major_term_tuition 获取当前学期学费是否支付的状态]
	 * @return [type] [description]
	 */
	function get_major_term_tuition(){	
		$majorid=intval($this->input->get('majorid'));
		$term=intval($this->input->get('term'));
		$userid=intval($this->input->get('userid'));
		$isliuji=intval($this->input->get('isliuji'));

		if(!empty($majorid)&&!empty($term)&&!empty($userid)){
			//获取当前学期的学费
			$now_tuition=$this->pay_model->get_term_tuition($majorid,$term);
			$before_tuition=0;
			//查看当前学期交了多少学费了
			$before_tuition_arr=$this->pay_model->get_before_tuition($userid,$majorid,$term);
			if(!empty($before_tuition_arr)){
				foreach ($before_tuition_arr as $k => $v) {
					if(!empty($v['paytime'])){
						$before_tuition_arr[$k]['paytime']=date('Y-m-d',$v['paytime']);
					}
					if(empty($v['budgetid'])){
						$order_info=$this->db->get_where('apply_order_info','id = '.$v['order_id'])->row_array();
						$before_tuition_arr[$k]['budgetid']=!empty($order_info['budgetid'])?$order_info['budgetid']:0;
					}
					$before_tuition+=$v['tuition'];
				}
			}
			$surplus_tuition=$now_tuition['tuition']-$before_tuition;
			 //var_dump($surplus_tuition);exit;
			if($surplus_tuition<=0){
				if($isliuji == 0){
					ajaxReturn('','该学期的学费已经交够',2);
				}
			}
			

			//先查看换证费开关
			$tuition=CF('tuition','',CONFIG_PATH);
			//查询当前学期有没有换证费
			if($tuition['replacement']=='yes'){
				$barter_card_ids='';
				$money_barter_card=0;
				//查找该学生缴费学期下有没有重修费
				$barter_card=$this->pay_model->get_barter_card($term,$userid);
				if(!empty($barter_card)){
					foreach ($barter_card as $k => $v) {
						$barter_card_ids.=$v['id'].'-grf-';
						$money_barter_card+=$v['money'];
					}
					$barter_card_ids=trim($barter_card_ids,'-grf-');
				}
			}
			//查询当前有没有重修费
			if($tuition['repair_fee']=='yes'){
				$rebuild_ids='';
				$money_rebuild=0;
				//查找该学生缴费学期下有没有重修费
				$rebuild=$this->pay_model->get_rebuild($term,$userid);
				if(!empty($rebuild)){
					foreach ($rebuild as $k => $v) {
						if($v['createtime']!=0){
							$rebuild[$k]['createtime']=date('Y-m-d',$v['createtime']);
						}
						$rebuild_ids.=$v['id'].'-grf-';
						$money_rebuild+=$v['money'];
					}
					$rebuild_ids=trim($rebuild_ids,'-grf-');
				}
			}
			$data['rebuild_info']=!empty($rebuild)?$rebuild:0;
			$data['barter_card_info']=!empty($barter_card)?$barter_card:0;
			//换证费
			$data['money_barter_card']=!empty($money_barter_card)?$money_barter_card:0;
			$data['barter_card_ids']=!empty($barter_card_ids)?$barter_card_ids:'';
			//重修费
			$data['money_rebuild']=!empty($money_rebuild)?$money_rebuild:0;
			$data['rebuild_ids']=!empty($rebuild_ids)?$rebuild_ids:'';
			$data['surplus_tuition']=!empty($surplus_tuition)?$surplus_tuition:0;
			$data['before_tuition_arr']=!empty($before_tuition_arr)?$before_tuition_arr:0;
			$data['now_tuition']=!empty($now_tuition['tuition'])?$now_tuition['tuition']:0;
			$tuition_his=0;
			//获取以往记录的
			$tuition_his=$this->db
                ->select('a.*,b.proof_number,b.file_path')
                ->from('tuition_info'.' a')
                ->join('budget'.' b','a.budgetid = b.id','left')
                ->where('a.userid = '.$userid.' AND a.nowterm <>'.$term.' AND a.paystate = 1')
                ->get()
                ->result_array();

			//$this->db->get_where('tuition_info','userid = '.$userid.' AND paystate = 1 AND nowterm <>'.$term)->result_array();
			// var_dump($tuition_his);exit;
			if(!empty($tuition_his)){
				foreach ($tuition_his as $key => $value) {
					if(empty($value['budgetid'])){
						$order_info=$this->db->get_where('apply_order_info','id = '.$value['order_id'])->row_array();
						$tuition_his[$key]['budgetid']=!empty($order_info['budgetid'])?$order_info['budgetid']:0;
					}
					$tuition_his[$key]['paytime']=date('Y-m-d',$value['paytime']);
				}
			}
			$data['tuition_his']=$tuition_his;
			ajaxreturn($data,'',1);
		}
		ajaxreturn('','',0);
	}

	//按类别缴费住宿费
	function type_acc(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			$info=$this->pay_model->get_typeacc_info($userid);
			// //判断该生是不是奖学金用户
			// //是不是奖学金
			// $is_jx=0;
			// //奖学金类型
			// $jx_state=0;
			// //如果奖学金是指定覆盖的  
			// $term_year=0;//1一次2每次
			// $cost_cover=array();
			// //查看是不是奖学金生  //查看状态是不是已经被录取
			// $is_jiangxuejin=$this->pay_model->check_is_jiangxuejin($userid);
			// if(!empty($is_jiangxuejin['scholorshipid'])){

			// 	//如果是查看是否通过
			// 	$is_tongguo=$this->pay_model->check_is_jiangxuejin_tg($userid,$is_jiangxuejin['scholorshipid']);
			// 	// var_dump($is_tongguo);exit;
			// 	if($is_tongguo['state']==5){
			// 		$is_jx=1;
			// 		//查看该奖学金的信息  
			// 		$jiang_info=$this->pay_model->get_jiangxuejin_info($is_jiangxuejin['scholorshipid']);
			// 		$jx_state=$jiang_info['cost_state'];
			// 		if(!empty($jiang_info)){
			// 			//查看奖学金的类型  是不是覆盖类型的   是不是指定比例的  是不是指定金额的
			// 			if($jiang_info['cost_state']==1){
			// 				//指定覆盖
			// 				$cost_cover=json_decode($jiang_info['cost_cover'],true);
			// 				//覆盖住宿
			// 				$term_year=$jiang_info['trem_year'];
			// 				if(in_array('2', $cost_cover)){
			// 					//覆盖第一个学期的住宿费
			// 					if($jx_state==1){
			// 						if($term_year==1){
			// 							if($info['is_sch_pay']==0){
			// 								//查询该生的入住时间 加上这个学期的跨度是不用交的范围
			// 								//查询这个专业的跨度
			// 								$app_info=$this->pay_model->get_apply_info_one($userid);
			// 								//查询这个专业的信息
			// 								$major_info=$this->pay_model->get_major_info_one($app_info['courseid']);
			// 								$acc_money=$major_info['termdays']*$info['registeration_fee'];
			// 								$update['acc_money']=$acc_money;
			// 								$update['paystate']=1;
			// 								$update['paytime']=time();
			// 								$update['is_sch_pay']=1;
			// 								//更新住宿信息的住宿钱数
			// 								$this->db->update('accommodation_info',$update,'id = '.$info['id']);
			// 							}
										
			// 						}else{
			// 							//查询该生的入住时间 加上这个学期的跨度是不用交的范围
			// 							//查询这个专业的跨度
			// 							$app_info=$this->pay_model->get_apply_info_one($userid);
			// 							//查询这个专业的信息
			// 							$major_info=$this->pay_model->get_major_info_one($app_info['courseid']);
			// 							$acc_money=$major_info['termnum']*$major_info['termdays']*$info['registeration_fee'];
			// 							$update['acc_money']=$acc_money;
			// 							$update['paystate']=1;
			// 							$update['paytime']=time();
			// 							$this->db->update('accommodation_info',$update,'id = '.$info['id']);
			// 						}
									
			// 					}

			// 				}
							
			// 			}
			// 		}
			// 	} 
				
			// }
			// $data['is_jx']=$is_jx;
			// $data['term_year']=$term_year;
			// $data['cost_cover']=$cost_cover;
			// $data['jx_state']=$jx_state;
			$data['info']=$info;
			//到期时间
			$data['day']=date('Y-m-d',$info['accstarttime']+$info['accendtime']*24*3600);
			$data['acc_id']=$info['id'];
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
		if($data['type']=='acc_pledge'){
			$id=$this->pay_model->type_insert_acc_pledge($data);
			if(!empty($id)){
				ajaxreturn('','',1);
			}
			ajaxreturn('','',0);
		}
		//按类型交书费
		if($data['type']=='book'){
			$id=$this->pay_model->type_insert_book_fee($data);
			if(!empty($id)){
				ajaxreturn('','',1);
			}
			ajaxreturn('','',0);
		}
		//插入保险费
		if($data['type']=='insurance'){
			$id=$this->pay_model->type_insert_insurance_fee($data);
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
            //查看是否为奖学金用户
            $info=$this->check_sch_stu($userid);
            $is_jiao=0;
            if(!empty($info)){
                //是奖学金用户
                if($info['cost_state']==1){
                    //指定覆盖
                    $cost_cover=json_decode($info['cost_cover'],true);
                    if(in_array('4', $cost_cover)){
                        //不用交书费
                        $is_jiao=1;

                    }
                }
            }
            $data['is_jiao']=$is_jiao;
			$data['book_money']=$book_money;
			$data['book_ids']=$book_ids;
			$data['major_info']=$major_info;
			ajaxreturn($data,'',1);
		}
		ajaxreturn('','',0);
	}
	/**
	 * [type_insurance 获取相关的保险费信息]
	 * @return [type] [description]
	 */
	function type_insurance(){
		$userid=intval($this->input->get('userid'));
		$app_info=$this->pay_model->get_apply_info_one($userid);
		//useiid   用来查询该学生在今年有没有交保险费
		//获取配置的保险费用
		$insurance= CF('insurance','',CONFIG_PATH);
		//查看是否为奖学金用户
		$info=$this->check_sch_stu($userid);
		$is_jiao=0;
		$year_jiao=0;
		if(!empty($info)){
			//是奖学金用户
			if($info['cost_state']==1){
				//指定覆盖
				$cost_cover=json_decode($info['cost_cover'],true);
				if(in_array('6', $cost_cover)){
					if($info['trem_year']==1){
						//一年不用交保险费
						$is_jiao=1;
						if(!empty($app_info['opening'])&&time()>($app_info['opening']+365*24*3600)){
							$year_jiao=1;
						}
					}else{
						//每年不用交学费
						$is_jiao=2;
					}
				}
			}
		}
		//查询保险费历史记录
		$info_his=$this->db->get_where('insurance_info','paystate = 1 AND userid = '.$userid)->result_array();
		$a = array(
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
		if(!empty($info_his)){
			foreach ($info_his as $key => $value) {
				foreach ($value as $k => $v) {
					if($k=='paytime'){
						$info_his[$key]['paytime']=date('Y-m-d',$v);
					}
					if($k=='effect_time'){
						$info_his[$key]['effect_time']=date('Y-m-d',$v);
					}
					if($k=='deadline'){
						$info_his[$key]['deadline']=$v==1?'半年':'一年';
					}
					
					if($k == 'term'){
						$v = !empty($v)?$v:1;
						$info_his[$key]['deadline']=$a[$v];
					}
					
				}
				$info_budget=$this->db->get_where('budget','id = '.$value['budgetid'])->row_array();
				$info_his[$key]['paytype']=$info_budget['paytype'];
				
			}
		}
		$data['info_his']=!empty($info_his)?$info_his:0;
		$data['is_jiao']=$is_jiao;
		$data['year_jiao']=$year_jiao;
		$data['info']=$info;
		$data['insurance']=$insurance;
		ajaxreturn($data,'',1);

	}
	/**
	 * [type_acc_pledge 获取住宿押金]
	 * @return [type] [description]
	 */
	function type_acc_pledge(){
		//查询该学生今年有没有交保险费
		$userid=intval($this->input->get('userid'));
        $acc_info=$this->pay_model->get_typeacc_info($userid);
        $acc_pledge_info=$this->db->get_where('acc_pledge_info','userid = '.$userid.' AND state <> 2')->row_array();
        if(!empty($acc_pledge_info)){
        	if($acc_pledge_info['state']==1){
        		ajaxReturn('','押金已经支付成功',2);
        	}elseif($acc_pledge_info['state']==3){
        		ajaxReturn('','押金已经支付,待确认',2);
        	}
        }
		//useiid   用来查询该学生在今年有没有交押金
		//获取配置的保险费用
		$acc_pledge= CF('acc_pledge','',CONFIG_PATH);
		//查看是不是奖学金用户
        //查看是否为奖学金用户
        $info=$this->check_sch_stu($userid);
        $is_jiao=0;
        if(!empty($info)){
            //是奖学金用户
            if($info['cost_state']==1){
                //指定覆盖
                $cost_cover=json_decode($info['cost_cover'],true);
                if(in_array('3', $cost_cover)){

                    //不用交住宿押金
                    $is_jiao=1;
                    //把押金插入表中
                    $arr['acc_pledge_money']=$acc_pledge['acc_pledgemoney'];
                    $this->db->update('accommodation_info',$arr,'id = '.$acc_info['id']);

                }
            }
        }
        $data['acc_pledge']=$acc_pledge;
        $data['is_jiao']=$is_jiao;
		ajaxreturn($data,'',1);
	}

/**
 * [check_sch_stu 查看是不是奖学金生有诶有通过]
 * @param  [type] $userid [description]
 * @return [type]         [description]
 */
	function check_sch_stu($userid){
		//查看是不是奖学金生  //查看状态是不是已经被录取
		$is_jiangxuejin=$this->pay_model->check_is_jiangxuejin($userid);
		if($is_jiangxuejin['isscholar']==1&&!empty($is_jiangxuejin['scholorshipid'])){

			//如果是查看是否通过
			$is_tongguo=$this->pay_model->check_is_jiangxuejin_tg($userid,$is_jiangxuejin['scholorshipid']);
			// var_dump($is_tongguo);exit;
			if($is_tongguo['state']==5){
				//查看该奖学金的信息  
				$jiang_info=$this->pay_model->get_jiangxuejin_info($is_jiangxuejin['scholorshipid']);
				return $jiang_info;
			} 
		}
		return array();
	}

	/**
	 * [chaange_type_select 按类别选择缴费]
	 * @return [type] [description]
	 */
	function unify_chaange_type_select_submit(){
		$data=$this->input->post();
		if(!empty($data['type'])){
			$this->pay_model->ultimate_pay($data);

            ajaxReturn('','',1);
		}
		ajaxReturn('','请选择缴费类型',0);
	}
	/**
	 * [change_paid_in 获取保险费的应交费用]
	 * @return [type] [description]
	 */
	function change_paid_in(){
		//$time=intval($this->input->get('time'));
		$student_type=intval($this->input->get('student_type'));
		$insurance= CF('insurance','',CONFIG_PATH);
		if($student_type==1){
			//$data=$insurance['insurancemoney_one']*$time;
			$data=$insurance['insurancemoney_one'];
			ajaxReturn($data,'',1);
		}elseif($student_type==2){
			//$data=$insurance['insurancemoney_two']*$time;
			$data=$insurance['insurancemoney_two'];
			ajaxReturn($data,'',1);
		}
		ajaxReturn(0,'',0);
	}
	/**
	 * [get_major_book 获取该学期的书费]
	 * @return [type] [description]
	 */
	function get_major_book(){
		$data=$this->input->post();
		//获取该专业的所有书籍
		$course_info=$this->pay_model->get_major_course($data['majorid']);
		//筛选本学期课程
		if(!empty($course_info)){
			foreach ($course_info as $k => $v) {
				$shanchu=0;
				$term_start=json_decode($v['term_start'],true);
				if(!empty($term_start)){
					foreach ($term_start as $key => $value) {
					
						if($value==$data['term']){
							$shanchu=1;
						}
					}
				}
				if($shanchu==0){
					unset($course_info[$k]);
				}
			}
		}
		$book=array();
		$bookids='';
		$last_money=0;
		if(!empty($course_info)){
			foreach ($course_info as $k => $v) {
				//获取该课程的书籍
				$book_info=$this->pay_model->get_course_book($v['courseid']);
				if(!empty($book_info)){
					foreach ($book_info as $kk => $vv) {
						//获取书籍信息
						$geng=$this->pay_model->get_book_info($vv['booksid']);
						if(empty($geng)){
							continue;
						}
						$book[$vv['booksid']]=$geng;
						$last_money+=$book[$vv['booksid']]['price'];
						$bookids.=$vv['booksid'].',';
					}
				}
			}
		}
		$money=0;
		//查看已经选过的
    	$before_info=$this->db->get_where('books_fee','userid = '.$data['userid'].' AND term = '.$data['term'])->result_array();
    	$ids_str='';
    	if(!empty($before_info)){
    		foreach ($before_info as $k => $v) {
    			$ids_str.=$v['book_ids'].',';
    			if($v['paystate']==1){
    				continue;
    			}
    			$money+=$v['last_money'];
    		}

    	}
    	if(!empty($ids_str)){
    		$select_id=explode(',', trim($ids_str,','));
    		
    	}
    	//已经交过的书籍
    	$yi_jiao=$this->db->get_where('books_fee','userid = '.$data['userid'].' AND term = '.$data['term'].' AND paystate = 1')->result_array();
		if(!empty($yi_jiao)){
			$yi_jiao_ids='';
			foreach ($yi_jiao as $k => $v) {
				$yi_jiao_ids.=$v['book_ids'].',';
			}
		}
		if(!empty($yi_jiao_ids)){
    		$jiao_select_id=explode(',', trim($yi_jiao_ids,','));
    		
    	}

		$returndata['paystate']=!empty($before_info['paystate'])?$before_info['paystate']:0;
    	$returndata['select_id']=!empty($select_id)?$select_id:array();
    	$returndata['jiao_select_id']=!empty($jiao_select_id)?$jiao_select_id:array();
    	$returndata['money']=!empty($money)?$money:0;
		$returndata['book']=$book;
		$returndata['bookids']=trim($bookids,',');
		$returndata['last_money']=$last_money;
		ajaxReturn($returndata,'',1);
	}
	/**
	 * [type_electric_pledge 电费押金类型]
	 * @return [type] [description]
	 */
	function type_electric_pledge(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			//查看学生有没有交过电费押金
			$electric_pledge_info=$this->db->get_where('electric_pledge','userid = '.$userid.' AND paystate = 1 AND is_retreat = 0')->row_array();
			if(!empty($electric_pledge_info)){
				ajaxReturn('','',2);
			}
		}
		//获取电费押金的金钱
		$electric=CF('electric','',CONFIG_PATH);
		if($electric['electric']=='yes'&&!empty($electric['electricmoney'])){
			ajaxReturn($electric['electricmoney'],'',1);
		}
	}
	/**
	 * [type_bedding 床品费]
	 * @return [type] [description]
	 */
	function type_bedding(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			//查看学生有没有交过电费押金
			$bedding_info=$this->db->get_where('bedding_fee','userid = '.$userid.' AND paystate = 1')->row_array();
			if(!empty($bedding_info)){
				ajaxReturn('','',2);
			}
		}
		//获取电费押金的金钱
		$bed=CF('bed','',CONFIG_PATH);
		if($bed['bed']=='yes'&&!empty($bed['bedmoney'])){
			ajaxReturn($bed['bedmoney'],'',1);
		}
	}
	/**
	 * [type_rebuild 类别重修费]
	 * @return [type] [description]
	 */
	function type_rebuild(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			//获取该学生的专业信息
			$major_info=$this->pay_model->get_major_info($userid);
			$data['major_info']=$major_info;
			ajaxreturn($data,'',1);
		}
		ajaxreturn('','',0);
	}
	/**
	 * [get_major_rebuild 获取该学期的重修费用]
	 * @return [type] [description]
	 */
	function get_major_rebuild(){
		$data=$this->input->post();
		if(!empty($data)){
			//获取该学期有没有没有交的重修费
			$returndata=$this->db->get_where('student_rebuild','userid = '.$data['userid'].' AND term = '.$data['term'].' AND state <>1')->result_array();
			if(empty($returndata)){
				ajaxReturn('','',0);
			}
			$money=0;
			$ids='';
			foreach ($returndata as $k => $v) {
				//应交的钱
				$money+=$v['money'];
				$ids.=$v['id'].',';
			}
			$arr['last_money']=$money;
			$arr['ids']=trim($ids,',');
			ajaxReturn($arr,'',1);
		}
	}
	/**
	 * [type_barter_card 类别换证费]
	 * @return [type] [description]
	 */
	function type_barter_card(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			//获取该学生的专业信息
			$major_info=$this->pay_model->get_major_info($userid);
			$data['major_info']=$major_info;
			ajaxreturn($data,'',1);
		}
		ajaxreturn('','',0);
	}
	/**
	 * [get_major_rebuild 获取该学期的换证费用]
	 * @return [type] [description]
	 */
	function get_major_barter_card(){
		$data=$this->input->post();
		if(!empty($data)){
			//获取该学期有没有没有交的重修费
			$returndata=$this->db->get_where('student_barter_card','userid = '.$data['userid'].' AND term = '.$data['term'].' AND state <>1')->result_array();
			if(empty($returndata)){
				ajaxReturn('','',0);
			}
			$money=0;
			$ids='';
			foreach ($returndata as $k => $v) {
				//应交的钱
				$money+=$v['money'];
				$ids.=$v['id'].',';
			}
			$arr['last_money']=$money;
			$arr['ids']=trim($ids,',');
			ajaxReturn($arr,'',1);
		}
	}
	/**
	 * [type_apply 获取用户的申请还没有交申请费的选手]
	 * @return [type] [description]
	 */
	function type_apply(){
		$userid=$this->input->get('userid');
		//获取该用户和的申请
		$apply_info=$this->pay_model->get_user_apply_info($userid);
		if(!empty($apply_info)){
			ajaxReturn($apply_info,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_apply_registration_fee 获取申请的申请费]
	 * @return [type] [description]
	 */
	function get_apply_registration_fee(){
		$applyid=$this->input->get('id');
		if(!empty($applyid)){
			$data=$this->db->get_where('apply_info','id = '.$applyid)->row_array();
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [type_pledge 类型入学押金]
	 * @return [type] [description]
	 */
	function type_pledge(){
		$userid=$this->input->get('userid');
		//获取该用户和的申请
		$apply_info=$this->pay_model->get_user_apply_pledge_info($userid);
		if(!empty($apply_info)){
			ajaxReturn($apply_info,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_pledge_registration_fee 获取入学押金]
	 * @return [type] [description]
	 */
	function get_pledge_registration_fee(){
		$applyid=$this->input->get('id');
		if(!empty($applyid)){
			$data=$this->db->get_where('apply_info','id = '.$applyid)->row_array();
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [type_electric查询电费欠费通知]
	 * @return [type] [description]
	 */
	function type_electric(){
		$userid=$this->input->get('userid');
		$fee=0;
		//查看学生所在的房间
		$roomid=$this->db->get_where('accommodation_info','userid = '.$userid.' AND acc_state = 6')->row_array();
		if(!empty($roomid)){
			$data=$this->acc_dispose_electric_model->get_rooms_user($roomid['roomid']);
			if(!empty($data)){
				foreach ($data as $key => $value) {
					if($value['userid']==$userid){
						$fee=abs($value['fee']);
					}
				}
			}
		}ajaxReturn($fee,'',1);
		
	}
	/**
	 * [budgetid 查看备注]
	 * @return [type] [description]
	 */
	function look_remark(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$info=$this->db->get_where('budget','id = '.$id)->row_array();
			$info=$info['remark'];
			$html = $this->load->view('master/charge/look_remark',array(
				'info'=>$info
            ),true);
       		 ajaxReturn($html,'',1);
		}
	}
	/**
	 * [print_shouju 打印收据]
	 * @return [type] [description]
	 */
	function print_shouju(){
		$userid=$this->input->get('userid');
		$paid_in=$this->input->get('paid_in');
		$proof_number=$this->input->get('proof_number');
		$paytype=$this->input->get('paytype');
		$type=$this->input->get('type');
		$info=$this->db->get_where('print_template','parentid = 10')->result_array();
		$html = $this->load->view('master/charge/print_shouju',array(
			'userid'=>$userid,
			'paid_in'=>$paid_in,
			'proof_number'=>$proof_number,
			'paytype'=>$paytype,
			'type'=>$type,
			'info'=>$info
            ),true);
       		 ajaxReturn($html,'',1);
	}
}