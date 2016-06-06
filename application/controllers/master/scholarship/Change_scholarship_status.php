<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理-全部申请
 * @package 	apply
 * @author 		Laravel
 * @copyright   
 **/
class Change_scholarship_status extends Master_Basic {
	
	/**
	 * 全部申请
	 **/
	function __construct() {
		parent::__construct ();
		$this->load->library ( 'sdyinc_email' );
		$this->load->model (  'master/charge/pay_model' );
		$this->load->model (  'master/scholarship/change_scholarship_status_model' );
		
		
	}
	
	//初始化
	function index() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		
		if ($id) {
			//根据唯一ID查询对应记录
			$result = $this->change_scholarship_status_model->get_ones ( $id );
			
			if (! empty ( $result )) {
				//0 未完成 1 审核中 2 打回 3 打回提交 4 拒绝 5 通过 6 结束
				//定义操作数组
				$opeate = array(
						0 => array(
								1 => '处理中',
								6 => '结束'
						),
						1 => array(
								2 => '打回',
								4 => '拒绝',
								5 => '通过',
								6 => '结束',
						),
						2 => array(
								4 => '拒绝',
								5 => '通过',
								6 => '结束',
						),
						3 => array(
								2 => '打回',
								4 => '拒绝',
								5 => '通过',
								6 => '结束',
						),
						4 => array(
								5 => '通过',
								6 => '结束',
						),
						5 => array(
								4 => '拒绝',
								6 => '结束',
						),
						
				);
				
				
				$html = $this->load->view ( 'master/scholarship/change_app_status', array('result' => !empty($result)?$result:array(),'opeate' => $opeate,'label_id' => $label_id), true );
				ajaxReturn ( $html, '', 1 );
			}
			ajaxReturn ( '', '所查数据不存在', 0 );
		}
		ajaxReturn ( '', '缺少必要参数', 0 );
	}



	
	/*修改申请数据表状态*/
	function submit_app_status(){
		
		//获取申请ID
		$id = intval ( $this->input->post ( 'id' ) );
		$status = intval ( $this->input->post ( 'state' ) );		//准备前端更新数据 - 状态
	    $info=$this->change_scholarship_status_model->get_relation_one($id);
		//如果是通过学生的状态   查看奖学金的类型  分别插入相应的收费表
        if($status==5){
        	if($info->apply_state==2&&$info->ischinascholorship==0){
                //有一种情况是学生还没申请住宿
                $this->check_acc($id,$info);
        		//新生奖学金
          	  $this->First_Blood($id,$info);
        	}elseif($info->apply_state==1&&$info->ischinascholorship==0){
        		//在学奖学金//后来改成在学奖学金不计入任何费用
        	  // $this->Penta_Kill($id,$info);
              $sssss=1; 
        	}
        }
		if($status && $id){
			
			$insertetips = trim ( $this->input->post ( 'insertetips' ) );		//准备前端更新数据 - 是否将文本域内容一起发送给前端用户
			$tips = !empty($insertetips)?trim ( $this->input->post ( 'tips' ) ):"";		//准备前端更新数据 - 提醒
		
			//得到数据 修改状态 发邮件
			//修改状态
			if($status == 2){
				$data_status = array(
						'state' => $status,
						'isinformation' => 0,
						'isatt' => 0,
						'issubmit' => 0
				);
			}else{
				$data_status = array(
						'state' => $status,
				);
			}
			
			
			$flag = $this->db->update('applyscholarship_info',$data_status,'id = '.$id);
			if($flag){
				//发邮件
				//根据唯一ID查询对应申请记录
				$email_array = array(
						1 => 42,
						2 => 38,
						4 => 39,
						5 => 40,
						6 => 41
						
				);
				$app_info = $this->change_scholarship_status_model->get_relation_one ( $id );
			
				$MAIL = new sdyinc_email ();
				
				$val_arr  = array(
						'email' =>$app_info->email,
						'name' =>$app_info->title,
						'school_name' => 'Zhejiang University of Science and Technology',
						'tip_content' => $tips,
						'student_name' => $app_info->enname,
				);
				$MAIL->dot_send_mail ( $email_array[$status],$app_info->email,$val_arr);
				
			
				ajaxReturn ( '', '邮件发送失败', 1 );
				
				
				}else{
					ajaxReturn ( '', '后端数据更新失败，请重试', 0 );
				}
			}else{
				ajaxReturn ( '', '前端数据更新失败，请重试', 0 );
			}
		}
        /**
         * [check_acc 检查有没有住宿]
         * @return [type] [description]
         */
        function check_acc($id,$info){
            if(!empty($id)){
                $app_sc_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
                if(!empty($app_sc_info['scholarshipid'])){
                    $sc_info=$this->db->get_where('scholarship_info','id = '.$app_sc_info['scholarshipid'])->row_array();
                    if($sc_info['cost_state']==1){
                        $cost_cover=json_decode($sc_info['cost_cover'],true);
                        if(in_array(3, $cost_cover)){
                            $acc_info=$this->db->get_where('accommodation_info','userid = '.$info->userid.' AND acc_state <> 4 AND acc_state <> 7')->row_array();
                            if(empty($acc_info)){
                                ajaxReturn('','该奖学金包括住宿，该生没有预定住宿',0);
                            }
                        }
                    }
                }
               
            }
          return true;
        }
	/**
	 * [Penta_Kill 查询有没有讲学金]
	 */
	function Penta_Kill($id,$info){
		if(!empty($id)&&!empty($info)){
			//查看本学期有没有其他的奖学金
			$before_info=$this->db->get_where('applyscholarship_info','term = '.$info->term.' AND userid = '.$info->userid.' AND state = 5')->row_array();
			if(!empty($before_info)){
				//删除之前奖学金所插入的费用表
				$this->delete_all($before_info);
				$this->db->update('applyscholarship_info',array('state'=>6),'id = '.$before_info['id']);
			}
			//获取申请奖学金的信息
	        if(!empty($id)){
	            if($info->cost_state==1){
	                //奖学金类型等于覆盖类型的奖学金处理
	                $this->cost_state_one($id,$info);
	            }elseif($info->cost_state==2){
	                $this->cost_state_two($id,$info);
	                //指定比例交学费
	            }elseif($info->cost_state==3){

	                //指定金额交学费
	                $this->cost_state_three($id,$info);
	            }
	        }
		}
	}

	/**
	* [First_Blood 新生奖学金通过]
	* @param [type] $id   [description]
	* @param [type] $info [description]
	*/
    private function First_Blood($id,$info){
    	//查看本学期有没有其他的奖学金
		$before_info=$this->db->get_where('applyscholarship_info','term = '.$info->term.' AND userid = '.$info->userid.' AND state = 5')->row_array();
        if(!empty($before_info)){
			//删除之前奖学金所插入的费用表
			$this->delete_all($before_info);
			$this->db->update('applyscholarship_info',array('state'=>6),'id = '.$before_info['id']);
		}
        //获取申请奖学金的信息
        if(!empty($id)){
            if($info->cost_state==1){
                //奖学金类型等于覆盖类型的奖学金处理
                $this->cost_state_one($id,$info);
            }elseif($info->cost_state==2){
                $this->cost_state_two($id,$info);
                //指定比例交学费
            }elseif($info->cost_state==3){
                //指定金额交学费
                $this->cost_state_three($id,$info);
            }
        }
    }
    /**
     * [cost_state_two 指定学费比例奖学金]
     * @param  [type] $id   [description]
     * @param  [type] $info [description]
     * @return [type]       [description]
     */
    function cost_state_three($id,$info){
        if(!empty($id)&&!empty($info)){
            //给当前学费打折了
             //获取该学生申请的专业
            if($info->apply_state==2){
           		 //获取该学生申请的专业
            	$apply_info=$this->db->get_where('apply_info','id = '.$info->applyid)->row_array();
            	$major_id=$apply_info['courseid'];
        	}elseif ($info->apply_state==1) {
        		$student_info=$this->db->get_where('student','userid = '.$info->userid)->row_array();
        		if(!empty($student_info['majorid'])){
        			$major_id=$student_info['majorid'];
        		}elseif(!empty($student_info['major'])){
        			$major_id=$student_info['major'];
        		}
        	}
            //h获取该专业的每学期的学费
            $major_tuition=$this->db->get_where('major_tuition','majorid = '.$major_id)->result_array();
            //这条申请奖学金的信息
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();

            if(!empty($major_tuition)){
                //开始交本学期的学费
                if($info->trem_year==1){
                    //交第一学期的
                    foreach($major_tuition as $k =>$v){
                        if($v['term']==$info->term){
                        	if($info->cost_money>$v['tuition']){
                        		$money=$v['tuition'];
                        		//开始插曲收支表
                        		$shengyu_money=$info->cost_money-$v['tuition'];
                        		 //开始插入收支个各种表
                            $budget_shengarr=$this->return_budget_arr($info->userid,1,16,$v['term'],$shengyu_money,$shengyu_money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        	  $budget_id=$this->pay_model->grf_insert_budget($budget_shengarr);
                        	  $tuibudget_shengarr=$this->return_budget_arr($info->userid,2,16,$v['term'],$shengyu_money,$shengyu_money,$scholarship_info['id'],$scholarship_info['scholarshipid'],2);
                        	 $budget_id=$this->pay_model->grf_insert_budget($tuibudget_shengarr);
                        	}else{
                        		$money=$info->cost_money;
                        	}
                            //开始插入收支个各种表
                            $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term'],$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                            $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                            //插入学费信息表
                            $tuition_arr=array(
                                'budgetid'=>$budget_id,
                                'nowterm'=>$v['term'],
                                'userid'=>$info->userid,
                                'tuition'=>$money,
                                'danwei'=>'rmb',
                                'paystate'=>1,
                                'paytime'=>time(),
                                'paytype'=>6,
                                'createtime'=>time(),
                                'lasttime'=>time(),
                                'adminid'=>$_SESSION ['master_user_info']->id,
                            );
                            $this->db->insert('tuition_info',$tuition_arr);
                            //开始插入收支个各种表
                            $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term'],$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                            $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
                        }
                    }

                }else{
//                //开始循环专业学费进行缴纳
                    foreach($major_tuition as $k =>$v){
                    	if($info->cost_money>$v['tuition']){
                    		$money=$v['tuition'];
                    		//开始插曲收支表
                    		$shengyu_money=$info->cost_money-$v['tuition'];
                    		 //开始插入收支个各种表
                     	   $budget_shengarr=$this->return_budget_arr($info->userid,1,16,$v['term'],$shengyu_money,$shengyu_money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                    	   $budget_id=$this->pay_model->grf_insert_budget($budget_shengarr);
                    	   $tuibudget_shengarr=$this->return_budget_arr($info->userid,2,16,$v['term'],$shengyu_money,$shengyu_money,$scholarship_info['id'],$scholarship_info['scholarshipid'],2);
                    		$budget_id=$this->pay_model->grf_insert_budget($tuibudget_shengarr);
                    	}else{
                    		$money=$info->cost_money;
                    	}
                        //开始插入收支个各种表
                        $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term'],$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                        //插入学费信息表
                        $tuition_arr=array(
                            'budgetid'=>$budget_id,
                            'nowterm'=>$v['term'],
                            'userid'=>$info->userid,
                            'tuition'=>$money,
                            'danwei'=>'rmb',
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>6,
                            'createtime'=>time(),
                            'lasttime'=>time(),
                            'adminid'=>$_SESSION ['master_user_info']->id,
                        );
                        $this->db->insert('tuition_info',$tuition_arr);
                        //开始插入收支个各种表
                        $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term'],$v['tuition'],$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
                    }
                }
            }
        }
    }
    /**
     * [cost_state_two 指定学费比例奖学金]
     * @param  [type] $id   [description]
     * @param  [type] $info [description]
     * @return [type]       [description]
     */
    function cost_state_two($id,$info){
        if(!empty($id)&&!empty($info)){
            //给当前学费打折了
             //获取该学生申请的专业
            if($info->apply_state==2){
           		 //获取该学生申请的专业
            	$apply_info=$this->db->get_where('apply_info','id = '.$info->applyid)->row_array();
            	$major_id=$apply_info['courseid'];
        	}elseif ($info->apply_state==1) {
        		$student_info=$this->db->get_where('student','userid = '.$info->userid)->row_array();
        		if(!empty($student_info['majorid'])){
        			$major_id=$student_info['majorid'];
        		}elseif(!empty($student_info['major'])){
        			$major_id=$student_info['major'];
        		}
        	}
            //h获取该专业的每学期的学费
            $major_tuition=$this->db->get_where('major_tuition','majorid = '.$major_id)->result_array();
            //这条申请奖学金的信息
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();

            if(!empty($major_tuition)){
                //开始交本学期的学费
                if($info->trem_year==1){
                    //交第一学期的
                    foreach($major_tuition as $k =>$v){
                        if($v['term']==$info->term){
                            //开始插入收支个各种表
                            $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term'],$v['tuition']*$info->cost_ratio/100,$v['tuition']*$info->cost_ratio/100,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                            $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                            //插入学费信息表
                            $tuition_arr=array(
                                'budgetid'=>$budget_id,
                                'nowterm'=>$v['term'],
                                'userid'=>$info->userid,
                                'tuition'=>$v['tuition']*$info->cost_ratio/100,
                                'danwei'=>'rmb',
                                'paystate'=>1,
                                'paytime'=>time(),
                                'paytype'=>6,
                                'createtime'=>time(),
                                'lasttime'=>time(),
                                'adminid'=>$_SESSION ['master_user_info']->id,
                            );
                            $this->db->insert('tuition_info',$tuition_arr);
                            //开始插入收支个各种表
                            $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term'],$v['tuition']*$info->cost_ratio/100,$v['tuition']*$info->cost_ratio/100,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                            $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
                        }
                    }

                }else{
//                //开始循环专业学费进行缴纳
                    foreach($major_tuition as $k =>$v){
                        //开始插入收支个各种表
                        $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term'],$v['tuition']*$info->cost_ratio/100,$v['tuition']*$info->cost_ratio/100,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                        //插入学费信息表
                        $tuition_arr=array(
                            'budgetid'=>$budget_id,
                            'nowterm'=>$v['term'],
                            'userid'=>$info->userid,
                            'tuition'=>$v['tuition']*$info->cost_ratio/100,
                            'danwei'=>'rmb',
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>6,
                            'createtime'=>time(),
                            'lasttime'=>time(),
                            'adminid'=>$_SESSION ['master_user_info']->id,
                        );
                        $this->db->insert('tuition_info',$tuition_arr);
                        //开始插入收支个各种表
                        $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term'],$v['tuition']*$info->cost_ratio/100,$v['tuition']*$info->cost_ratio/100,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
                    }
                }
            }
        }
    }
    /**
     * 奖学金类型是覆盖类型的处理方法
     */
    private function cost_state_one($id,$info){
        if(!empty($id)&&!empty($info)){
            //查看指定覆盖的类型json数据1学费2住宿费3住宿押金4书费5床品费6保险费
            $cost_cover=json_decode($info->cost_cover,true);
            if(in_array('1',$cost_cover)){
                //交学费了
                //判断奖学金缴费时间段
                $this->cost_cover_tuition($id,$info);
            }
            if(in_array('2',$cost_cover)){
                //住宿费
                $this->cost_cover_acc($id,$info);
            }
            if(in_array('3',$cost_cover)){
                //住宿押金
                $this->cost_cover_acc_pledge($id,$info);
            }
            if(in_array('4',$cost_cover)){
                //书费
                $this->cost_cover_book($id,$info);
            }
//            if(in_array('5',$cost_cover)){
//                //床品费
////                $this->cost_cover_bedding($id,$info);
//            }
            if(in_array('6',$cost_cover)){
                //保险费
                $this->cost_cover_insurance($id,$info);
            }
        }
    }

    /**
     * 交保险费
     */
    function cost_cover_insurance($id,$info){
        if(!empty($id)&&!empty($info)){
            //查看保险费的开关及费用
            $insurance= CF('insurance','',CONFIG_PATH);
             //获取该学生申请的专业
            if($info->apply_state==2){
           		 //获取该学生申请的专业
            	$apply_info=$this->db->get_where('apply_info','id = '.$info->applyid)->row_array();
            	$major_id=$apply_info['courseid'];
        	}elseif ($info->apply_state==1) {
        		$student_info=$this->db->get_where('student','userid = '.$info->userid)->row_array();
        		if(!empty($student_info['majorid'])){
        			$major_id=$student_info['majorid'];
        		}elseif(!empty($student_info['major'])){
        			$major_id=$student_info['major'];
        		}
        	}
            $major_info=$this->db->get_where('major','id = '.$major_id)->row_array();
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
            if($insurance['insurance']=='yes'&&$major_info['termnum']){
                //保险费的金额  新生老生
                if($info->term==1){
                    $money=$insurance['insurancemoney_one'];
                    $student_type=1;
                }else{
                    $money=$insurance['insurancemoney_two'];
                    $student_type=2;
                }
                if($info->trem_year==1){
                    //只交半年的保险费
                    $budget_arr=$this->return_budget_arr($info->userid,1,9,$info->term,$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                    $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                    //插入保险费表
                    $insurance_arr=array(
                        'budgetid'=>$budget_id,
                        'payable'=>$money,
                        'paid_in'=>$money,
                        'deadline'=>1,
                        'student_type'=>$student_type,
                        'effect_time'=>$major_info['opentime'],
                        'userid'=>$info->userid,
                        'paystate'=>1,
                        'paytime'=>time(),
                        'createtime'=>time(),
                        'adminid'=>$_SESSION ['master_user_info']->id
                    );
                    $this->db->insert('insurance_info',$insurance_arr);
                    //只交半年的保险费
                    $budget_arr=$this->return_budget_arr($info->userid,2,9,$info->term,$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                    $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                }elseif($info->trem_year==2){
                    //插几年的
                    for($i=1;$i<=floor($major_info['termnum']/2);$i++){
                        $budget_arr=$this->return_budget_arr($info->userid,1,9,$info->term,$money*2,$money*2,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                        //插入保险费表
                        $insurance_arr=array(
                            'budgetid'=>$budget_id,
                            'payable'=>$money*2,
                            'paid_in'=>$money*2,
                            'deadline'=>2,
                            'student_type'=>$student_type,
                            'effect_time'=>strtotime("+".($i-1)." year",$major_info['opentime']),
                            'userid'=>$info->userid,
                            'paystate'=>1,
                            'paytime'=>time(),
                            'createtime'=>time(),
                            'adminid'=>$_SESSION ['master_user_info']->id
                        );
                        $this->db->insert('insurance_info',$insurance_arr);
                        //只交半年的保险费
                        $budget_arr=$this->return_budget_arr($info->userid,2,9,$info->term,$money*2,$money*2,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);

                    }
                    if($major_info['termnum']%2!=0){
                        //再插入半年的
                        $budget_arr=$this->return_budget_arr($info->userid,1,9,$info->term,$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                        //插入保险费表
                        $insurance_arr=array(
                            'budgetid'=>$budget_id,
                            'payable'=>$money,
                            'paid_in'=>$money,
                            'deadline'=>1,
                            'student_type'=>$student_type,
                            'effect_time'=>strtotime("+".floor($major_info['termnum']/2)." year",$major_info['opentime']),
                            'userid'=>$info->userid,
                            'paystate'=>1,
                            'paytime'=>time(),
                            'createtime'=>time(),
                            'adminid'=>$_SESSION ['master_user_info']->id
                        );
                        $this->db->insert('insurance_info',$insurance_arr);
                        //只交半年的保险费
                        $budget_arr=$this->return_budget_arr($info->userid,2,9,$info->term,$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                    }
                }
            }
        }
    }
    /**
     * 交书费
     *
     */
    function cost_cover_book($id,$info){
        if(!empty($id)&&!empty($info)){
            //获取该学生申请的专业
            if($info->apply_state==2){
           		 //获取该学生申请的专业
            	$apply_info=$this->db->get_where('apply_info','id = '.$info->applyid)->row_array();
            	$major_id=$apply_info['courseid'];
        	}elseif ($info->apply_state==1) {
        		$student_info=$this->db->get_where('student','userid = '.$info->userid)->row_array();
        		if(!empty($student_info['majorid'])){
        			$major_id=$student_info['majorid'];
        		}elseif(!empty($student_info['major'])){
        			$major_id=$student_info['major'];
        		}
        	}
            $major_info=$this->db->get_where('major','id = '.$major_id)->row_array();
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
            if($info->trem_year==1){
                //只交第一学期的书费
                $book_info=$this->_get_major_term_book($major_id,$info->term);
                //插入收支表
                $budget_arr=$this->return_budget_arr($info->userid,1,8,$info->term,$book_info['last_money'],$book_info['last_money'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                //插入书费表
                $book_fee=array(
                    'userid'=>$info->userid,
                    'budgetid'=>$budget_id,
                    'term'=>$info->term,
                    'book_ids'=>$book_info['bookids'],
                    'last_money'=>$book_info['last_money'],
                    'paid_in'=>$book_info['last_money'],
                    'paystate'=>1,
                    'paytime'=>time(),
                    'createtime'=>time(),
                    'adminid'=>$_SESSION ['master_user_info']->id
                );
                $this->db->insert('books_fee',$book_fee);
                //插入收支表
                $budget_arr=$this->return_budget_arr($info->userid,2,8,$info->term,$book_info['last_money'],$book_info['last_money'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
            }elseif($info->trem_year==2){
                //交每学期的书费
                for ($i=1; $i <= $major_info['termnum'] ; $i++) {
                    //只交第一学期的书费
                    $book_info=$this->_get_major_term_book($apply_info['courseid'],$i);
                    //插入收支表
                    $budget_arr=$this->return_budget_arr($info->userid,1,8,$i,$book_info['last_money'],$book_info['last_money'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                    $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                    //插入书费表
                    $book_fee=array(
                        'userid'=>$info->userid,
                        'budgetid'=>$budget_id,
                        'term'=>$i,
                        'book_ids'=>$book_info['bookids'],
                        'last_money'=>$book_info['last_money'],
                        'paid_in'=>$book_info['last_money'],
                        'paystate'=>1,
                        'paytime'=>time(),
                        'createtime'=>time(),
                        'adminid'=>$_SESSION ['master_user_info']->id
                    );
                    $this->db->insert('books_fee',$book_fee);
                    //插入收支表
                    $budget_arr=$this->return_budget_arr($info->userid,2,8,$i,$book_info['last_money'],$book_info['last_money'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                    $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                }
            }
        }
    }
    /**
     * 缴纳住宿费押金
     */
    function cost_cover_acc_pledge($id,$info){
        if(!empty($id)&&!empty($info)){
            //查询有没有交住宿押金
            $is=$this->db->get_where('acc_pledge_info','userid = '.$info->userid.' AND is_retreat = 0')->row_array();
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
            //查看开关
            $acc_pledge= CF('acc_pledge','',CONFIG_PATH);
            if(empty($is)){
                //开始插入收支表
                $budget_arr=$this->return_budget_arr($info->userid,1,10,$info->term,$acc_pledge['acc_pledgemoney'],$acc_pledge['acc_pledgemoney'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                //插入住宿押金表
                $acc_pledeg_arr=array(
                    'budgetid'=>$budget_id,
                    'userid'=>$info->userid,
                    'payable'=>$acc_pledge['acc_pledgemoney'],
                    'pay'=>$acc_pledge['acc_pledgemoney'],
                    'state'=>1,
                    'paytime'=>time(),
                    'createtime'=>time(),
                    'adminid'=>$_SESSION ['master_user_info']->id
                );
                $this->db->insert('acc_pledge_info',$acc_pledeg_arr);
                //插入收支表持平刚才奖学金插入
                $budget_arr=$this->return_budget_arr($info->userid,2,10,$info->term,$acc_pledge['acc_pledgemoney'],$acc_pledge['acc_pledgemoney'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
            }
        }
    }
    /**
     * 指定覆盖类型交住宿费
     */
    function cost_cover_acc($id,$info){
        if(!empty($id)&&!empty($info)){
            //获取该学生申请的专业
            if($info->apply_state==2){
           		 //获取该学生申请的专业
            	$apply_info=$this->db->get_where('apply_info','id = '.$info->applyid)->row_array();
            	$major_id=$apply_info['courseid'];
        	}elseif ($info->apply_state==1) {
        		$student_info=$this->db->get_where('student','userid = '.$info->userid)->row_array();
        		if(!empty($student_info['majorid'])){
        			$major_id=$student_info['majorid'];
        		}elseif(!empty($student_info['major'])){
        			$major_id=$student_info['major'];
        		}
        	}
            $major_info=$this->db->get_where('major','id = '.$major_id)->row_array();
            //这条申请奖学金的信息
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
            //查询住宿的预定信息
            $acc_info=$this->db->get_where('accommodation_info','userid = '.$info->userid.' AND acc_state <> 4 AND acc_state <> 7')->row_array();
            if(!empty($acc_info)){
                //计算出天数
                if($info->trem_year==1){
                    //交一学期的住宿费
                    $day=$major_info['termdays'];
                }elseif($info->trem_year==2){
                    //计算出每学期的天数
                    $day=$major_info['termdays']*$major_info['termnum'];
                }
                //计算总共的金钱
                $room_info=$this->db->get_where('school_accommodation_prices','id = '.$acc_info['roomid'])->row_array();
                $money=$day*$room_info['prices'];
                //开始插入
                //开始插入收支个各种表
                $budget_arr=$this->return_budget_arr($info->userid,1,4,$info->term,$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                //插入住宿费交费详细表
                $acc_fee=array(
                    'budgetid'=>$budget_id,
                    'acc_id'=>$acc_info['id'],
                    'day'=>$day,
                    'menoy'=>$money,
                    'paystate'=>1,
                    'paytype'=>6,
                    'paytime'=>time(),
                    'createtime'=>time(),
                    'adminid'=>$_SESSION ['master_user_info']->id
                );
                $this->db->insert('acc_fee',$acc_fee);
                //开始插入收支个各种表
                $tuibudget_arr=$this->return_budget_arr($info->userid,2,4,$info->term,$money,$money,$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
                if($acc_info['paystate']==1){
                    $update['accendtime']=$day+$acc_info['accendtime'];
                }else{
                    $update['accendtime']=$day;
                }
                $update['paystate']=1;
                $update['paytime']=time();
                $update['paytype']=6;
                $this->db->update('accommodation_info',$update,'id = '.$acc_info['id']);

            }
        }
    }
    /**
     * 指定覆盖类型交学费
     */
    function cost_cover_tuition($id,$info){
        if(!empty($id)&&!empty($info)){
        	if($info->apply_state==2){
           		 //获取该学生申请的专业
            	$apply_info=$this->db->get_where('apply_info','id = '.$info->applyid)->row_array();

            	$major_id=$apply_info['courseid'];
        	}elseif ($info->apply_state==1) {
        		$student_info=$this->db->get_where('student','userid = '.$info->userid)->row_array();
        		if(!empty($student_info['majorid'])){
        			$major_id=$student_info['majorid'];
        		}elseif(!empty($student_info['major'])){
        			$major_id=$student_info['major'];
        		}
        	}
            //h获取该专业的每学期的学费
            $major_tuition=$this->db->get_where('major_tuition','majorid = '.$major_id)->result_array();
            //这条申请奖学金的信息
            $scholarship_info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();

            if($info->trem_year==1){
                //交第一学期的
                foreach($major_tuition as $k =>$v){
                    if($v['term']==$info->term){
                        //开始插入收支个各种表
                        $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term'],$v['tuition'],$v['tuition'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                        //插入学费信息表
                        $tuition_arr=array(
                            'budgetid'=>$budget_id,
                            'nowterm'=>$v['term'],
                            'userid'=>$info->userid,
                            'tuition'=>$v['tuition'],
                            'danwei'=>'rmb',
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>6,
                            'createtime'=>time(),
                            'lasttime'=>time(),
                            'adminid'=>$_SESSION ['master_user_info']->id,
                        );
                        $this->db->insert('tuition_info',$tuition_arr);
                        //开始插入收支个各种表
                        $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term'],$v['tuition'],$v['tuition'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);


                        //开始插入收支个各种表
                        $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term']+1,$v['tuition'],$v['tuition'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
                        //插入学费信息表
                        $tuition_arr=array(
                            'budgetid'=>$budget_id,
                            'nowterm'=>$v['term']+1,
                            'userid'=>$info->userid,
                            'tuition'=>$v['tuition'],
                            'danwei'=>'rmb',
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>6,
                            'createtime'=>time(),
                            'lasttime'=>time(),
                            'adminid'=>$_SESSION ['master_user_info']->id,
                        );
                        $this->db->insert('tuition_info',$tuition_arr);
                        //开始插入收支个各种表
                        $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term']+1,$v['tuition'],$v['tuition'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
                        $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
                    }
                }

            }else{
//                //开始循环专业学费进行缴纳
                foreach($major_tuition as $k =>$v){
	                	if($v['term']>=$info->term){
	                		 //开始插入收支个各种表
	                        $budget_arr=$this->return_budget_arr($info->userid,1,6,$v['term'],$v['tuition'],$v['tuition'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
	                        $budget_id=$this->pay_model->grf_insert_budget($budget_arr);
	                        //插入学费信息表
	                        $tuition_arr=array(
	                            'budgetid'=>$budget_id,
	                            'nowterm'=>$v['term'],
	                            'userid'=>$info->userid,
	                            'tuition'=>$v['tuition'],
	                            'danwei'=>'rmb',
	                            'paystate'=>1,
	                            'paytime'=>time(),
	                            'paytype'=>6,
	                            'createtime'=>time(),
	                            'lasttime'=>time(),
	                            'adminid'=>$_SESSION ['master_user_info']->id,
	                        );
	                        $this->db->insert('tuition_info',$tuition_arr);
	                        //开始插入收支个各种表
	                        $tuibudget_arr=$this->return_budget_arr($info->userid,2,6,$v['term'],$v['tuition'],$v['tuition'],$scholarship_info['id'],$scholarship_info['scholarshipid'],3);
	                        $budget_id=$this->pay_model->grf_insert_budget($tuibudget_arr);
	                	}
                       
                    }
            }

        }
    }

    /**
     * 组合收支表字段
     */
    function return_budget_arr($userid,$budget_type,$type,$term,$payable,$paid_in,$applyscholarshipid,$scholarshipid,$is_get_scholarship_money){
        $data=array(
            'userid'=>$userid,
            'budget_type'=>$budget_type,
            'type'=>$type,
            'term'=>$term,
           
            'paytype'=>6,
            'is_scholarship'=>1,
            'createtime'=>time(),
            'adminid'=>$_SESSION ['master_user_info']->id,
            'lasttime'=>time(),
            'remark'=>'奖学金支付',
            'applyscholarshipid'=>$applyscholarshipid,
            'scholarshipid'=>$scholarshipid
        );
        if($budget_type==1){
        	$data['payable']=$payable;
            $data['paid_in']=$paid_in;
            $data['paystate']=1;
            $data['paytime']=time();
        }elseif($budget_type==2){
        	$data['should_returned']=$payable;
            $data['true_returned']=$paid_in;
            $data['returned_time']=time();	
        }
        if($is_get_scholarship_money!=null){
        	$data['is_get_scholarship_money']=$is_get_scholarship_money;
        }
        return $data;
    }
	/**
	 * 返回保存数据
	 *
	 * @return multitype:array |boolean
	 */
	private function _save_data() {
		$post = $this->input->post ();
		$fields = $this->change_scholarship_status_model->field_info ();
		$save = array ();
		if ($fields && $post) {
			foreach ( $post as $f => $val ) {
				if (in_array ( $f, $fields )) {
					$save [$f] = $val;
				}
			}
			return $save;
		}
		return false;
	}


	/*上传文件调用*
	 * 
	 * 
	 */
	function upload_eoffer(){
		
		$upload_path = UPLOADS .'/oa/eoffer/';
		// 创建文件夹
		$data = array();
		$this->mkdirs ( $upload_path );
		if (sizeof ( $_FILES )) {
			foreach ( $_FILES as $key => $value ) {
				$fileupload = $this->_do_upload ( $key, $upload_path );
				if ($fileupload)
					$data [$key] = '/oa/eoffer/' . $fileupload ['upload_data'] ['file_name'];
			}
		}
		return $data;
	}
	
	/**
	 * 上传方法
	 */
	function _do_upload($key, $path) {
		$config ['upload_path'] = $path;
		$config ['allowed_types'] = 'gif|jpeg|jpg|png';
		$config ['max_size'] = '10000';
		$config ['encrypt_name'] = TRUE;
		
		$this->load->library ( 'upload', $config );
		if (! $this->upload->do_upload ( $key )) {
			ajaxReturn('',$this->upload->display_errors ('',''),0);
			// return false;
			return $config;
		} else {
			$data = array (
					'upload_data' => $this->upload->data () 
			);
			return $data;
		}
	}
	// 创建文件夹
	function mkdirs($dir) {
		if (! is_dir ( $dir )) {
			if (! $this->mkdirs ( dirname ( $dir ) )) {
				return false;
			}
			if (! mkdir ( $dir, 0777 )) {
				return false;
			}
		}
		return true;
	}
	

	//初始化
	function add_remark() {
		$id = intval ( $this->input->get ( 'id' ) );
		$result = $this->db->select('id,remark')->get_where('applyscholarship_info','id = '.$id)->row();
		$html = $this->load->view ( 'master/scholarship/add_remark', array(
				'result' => $result
		), true );
		ajaxReturn ( $html, '', 1 );

	}
	
	//添加学号
	function add_number() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		
		$html = $this->load->view ( 'master/enrollment/appmanager/add_number', array('id' => $id), true );
		ajaxReturn ( $html, '', 1 );
	
	}
	

	//设置中国政府奖学金
	function scholorship_set() {
		$id = intval ( $this->input->get ( 'id' ) );
		$label_id = intval ( $this->input->get ( 'label_id' ) );
		//中国政府奖学金
		$scholarship = $this->db->select('*')->get_where('scholarship_info','ischinascholorship = 1 AND state = 1')->result_array();
		//申请信息
		$applyInfo = $this->db->select('scholorstate,scholorshipid')->get_where('apply_info','id = '.$id)->row();
		$html = $this->load->view ( 'master/enrollment/appmanager/scholorship_set', array('id' => $id,'applyinfo' => !empty($applyInfo)?$applyInfo:array(),'scholarship'=>!empty($scholarship)?$scholarship:array()), true );
		ajaxReturn ( $html, '', 1 );
	
	}
	
	/**
	 * 执行 中国政府奖学金 设置
	 */
	function do_scholorship_set(){
		$scholorstate = intval(trim($this->input->post('scholorstate')));
		$scholorshipid = intval(trim($this->input->post('scholorshipid')));
		$remark = trim($this->input->post('remark'));
		$id = intval(trim($this->input->post('id')));
		if(!empty($scholorstate) && !empty($id) && !empty($scholorshipid)){
			if($scholorstate == -1){
				$scholorstate = 0;
			}
			
			//需要两部操作 1 更新申请表的 第二步 更新 奖学金申请表的 如果奖学金申请表中没有数据 则 先插入 数据
			//先获得 申请表的数据
			$applyInfo = $this->db->select('*')->get_where('apply_info','id = '.$id)->row();
			//判断申请信息 中 是否申请了 中国政府奖学金  没有的话 更新一下
			$this->db->update('apply_info',array('scholorshipid' => $scholorshipid,'scholorstate' => $scholorstate,'isscholar' => 1));
			//查询  奖学金申请表里是否有数据 
			$result = $this->db->select('*')->get_where('applyscholarship_info','userid = '.$applyInfo->userid.' AND scholarshipid = '.$scholorshipid.' AND type = 3')->row();
			if(!empty($result)){
				//更新
				$flag = $this->db->update('applyscholarship_info',array('state' => $scholorstate,'remark' => $remark),'id = '.$result->id);
			}else{
				//根据userid 查询用户信息
				$info = $this->db->select('enname,passport,email,nationality')->get_where('student_info','id = '.$applyInfo->userid)->row();
				//插入
				$max_number = build_order_no ();
				$dataA = array(
						'number' => $max_number,
						'userid' => $applyInfo->userid,
						'scholarshipid' => $scholorshipid,
						'type' => 3,
						'name' => ! empty ( $info->enname ) ? $info->enname : '',
						'passport' => ! empty ( $info->passport ) ? $info->passport : '',
						'email' => ! empty ( $info->email ) ? $info->email : '',
						'nationality' => ! empty ( $info->nationality ) ?$info->nationality : '',
						'applytime' => time (),
						'isstart' => 1,
						'isinformation' => 1,
						'isatt' => 1,
						'issubmit' => 1,
						'state' => $scholorstate,
						'lasttime' => time () 
				);
				$flag = $this->db->insert('applyscholarship_info',$dataA);
			}
			if($flag){
				ajaxReturn('','',1);
			}else{
				ajaxReturn('','',0);
			}
		}else{
			ajaxReturn('','',0);
		}
	}
	
	/**
	 * 添加学号
	 */
	function do_add_number(){
		$this->load->model('master/enrollment/edit_app_info_model');
		$id = intval(trim($this->input->post('id')));
		$studentid = intval(trim($this->input->post('studentid')));
		if($id && $studentid){
			$flag = $this->edit_app_info_model->update_app_info($id,array('studentid'=>$studentid));
			//写入日志
			//组织信息 首先 查用户的id
			$userid = $this->db->select('userid')->get_where('apply_info','id = '.$id)->row();
			//查询邮箱
			$email = $this->db->select('email')->get_where('student_info','id = '.$userid->userid)->row();
				
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户为' .$email->email.'分配学号为:'.$studentid,
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'appid' => $id
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			if($flag){
				ajaxReturn('','',1);
			}else{
				ajaxReturn('','',0);
			}
		}else{
			ajaxReturn('','',0);
		}
	}
	
	//初始化
	function ckaddress() {
		$id = intval ( $this->input->get ( 'id' ) );
		$userid = intval ( $this->input->get ( 'userid' ) );
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		$result = $this->db->select('*')->order_by('id DESC')->limit(1)->get_where('app_getoffer','appid = '.$id.' AND userid = '.$userid)->row();
		
		$html = $this->load->view ( 'master/enrollment/appmanager/ckaddress', array(
				'result' => $result,
				'nationality' => $nationality
		), true );
		ajaxReturn ( $html, '', 1 );
	
	}
	
	/**
	 * 获取 奖学金的 全部
	 */
	function get_scholorshipapply(){
		$data = array();
		// 奖学金开关
		$scholarship_on = CF ( 'scholarship', '', CONFIG_PATH );
		if (! empty ( $scholarship_on ) && $scholarship_on ['scholarship'] == 'yes') {
			$scholarship = $this->db->select('*')->get_where('scholarship_info','id > 0')->result_array();
			if(!empty($scholarship)){
				foreach ($scholarship as $k => $v){
					$data[$v['id']] = $v['title'];
				}
			}
				
		}
		return $data;
	}
		/**
	 * [get_major_book 获取该学期的书费]
	 * @return [type] [description]
	 */
	function _get_major_term_book($majorid,$term){
		//获取该专业的所有书籍
		$course_info=$this->pay_model->get_major_course($majorid);
		//筛选本学期课程
		if(!empty($course_info)){
			foreach ($course_info as $k => $v) {
				$shanchu=0;
				$term_start=json_decode($v['term_start'],true);
				if(!empty($term_start)){
					foreach ($term_start as $key => $value) {
					
						if($value==$term){
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
						$book[$vv['booksid']]=$this->pay_model->get_book_info($vv['booksid']);
						$last_money+=$book[$vv['booksid']]['price'];
						$bookids.=$vv['booksid'].',';
					}
				}
			}
		}
		$returndata['book']=$book;
		$returndata['bookids']=trim($bookids,',');
		$returndata['last_money']=$last_money;
		return $returndata;
	}
	/**
	 * [delete_all 删除之前插入的费用表]
	 * @return [type] [description]
	 */
	function delete_all($applyinfo){
		if(!empty($applyinfo)){
			//查询该申请的奖学金类型
		   $scholarship_info=$this->db->get_where('scholarship_info','id = '.$applyinfo['scholarshipid'])->row_array();
			$budget_info=$this->db->get_where('budget','term >= '.$applyinfo['term'].' AND scholarshipid = '.$applyinfo['scholarshipid'].' AND applyscholarshipid = '.$applyinfo['id'])->result_array();
		   if($scholarship_info['cost_state']==1){
		   		//删除指定覆盖那些表
		   		$this->delete_one($applyinfo,$scholarship_info,$budget_info);
		   }elseif($scholarship_info['cost_state']==2||$scholarship_info['cost_state']==3){
		   		//删除指定比例那些表
		   		$this->delete_two($applyinfo,$scholarship_info,$budget_info);
		   }

		}
	}
	/**
	 * [delete_two 删除奖学指定比例的那些表]
	 * @return [type] [description]
	 */
	function delete_two($applyinfo,$scholarship_info,$budget_info){
		if(!empty($applyinfo)&&!empty($scholarship_info)&&!empty($budget_info)){
			foreach ($budget_info as $k => $v) {
					if($v['budget_type']==1){
						switch ($v['type']) {
							case 6:
								//删除学费表
								$this->db->delete('tuition_info','budgetid = '.$v['id']);
								break;
							default:
								# code...
								break;
						}
					}
					
					$this->db->delete('budget','id = '.$v['id']);

				}
		}
	}
	/**
	 * [delete_one 删除指定覆盖那些表]
	 * @return [type] [description]
	 */
	function delete_one($applyinfo,$scholarship_info,$budget_info){
		if(!empty($applyinfo)&&!empty($scholarship_info)&&!empty($budget_info)){
			if(!empty($budget_info)){
				foreach ($budget_info as $k => $v) {
					if($v['budget_type']==1){
						switch ($v['type']) {
							case 6:
								//删除学费表
								$this->db->delete('tuition_info','budgetid = '.$v['id']);
								break;
							case 4:
								//z住宿费信息
								$acc_fee=$this->db->get_where('acc_fee','budgetid = '.$v['id'])->row_array();
								$acc_info=$this->db->get_where('accommodation_info','id = '.$acc_fee['acc_id'])->row_array();
								//先更新住宿申请表
								$up=array(
									'accendtime'=>$acc_info['accendtime']-$acc_fee['day'],
									);
								$this->db->update('accommodation_info',$up,'id = '.$acc_fee['acc_id']);
								//删除
								$this->db->delete('acc_fee','budgetid = '.$v['id']);
								break;
							case 10:
								//删除学费表
								$this->db->delete('acc_pledge_info','budgetid = '.$v['id']);
								break;
							case 8:
								//删除学费表
								$this->db->delete('books_fee','budgetid = '.$v['id']);
								break;
							case 9:
								//删除学费表
								$this->db->delete('insurance_info','budgetid = '.$v['id']);
								break;
							default:
								# code...
								break;
						}
					}
					
					$this->db->delete('budget','id = '.$v['id']);

				}
			}
			// $this->db->delete('budget','term >= '.$applyinfo['term'].' AND ');
		}
	}
	/**
	 * [update_jiangxuejin_page 更换学奖学金界面]
	 * @return [type] [description]
	 */
	function update_jiangxuejin_page(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
			$applyinfo=$this->db->get_where('apply_info','id = '.$info['applyid'])->row_array();
			$major_info=$this->db->get_where('major','id = '.$applyinfo['courseid'])->row_array();
			if(!empty($major_info['scholarship'])){
				$where=explode(',',$major_info['scholarship']);
				$scholarship_info=$this->db->where_in('id',$where)->where('apply_state',2)->get('scholarship_info')->result_array();
				$html = $this->_view ( 'master/scholarship/update_otherscholarship_box', array (
					'scholarship_info'=>$scholarship_info,
					'id'=>$id
					), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		
	}
	/**
	 * [update_jiangxuejin  更新该跳记录的奖学金]
	 * @return [type] [description]
	 */
	function update_jiangxuejin(){
		$data=$this->input->post();

		if(!empty($data)){
			$info=$this->db->get_where('applyscholarship_info','id = '.$data['id'])->row_array();
			//删除之前奖学金所插入的费用表
			$this->delete_all($info);
			$arr=array(
				'state'=>1,
				'scholarshipid'=>$data['scholarshipid'],
				);
			$this->db->update('applyscholarship_info',$arr,'id = '.$data['id']);
			ajaxReturn($data['id'],'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [update_zaixuejiangxuejin_page 更换在学奖学金]
	 * @return [type] [description]
	 */
	function update_zaixuejiangxuejin_page(){
		$id=$this->input->get('id');
		if(!empty($id)){
			$info=$this->db->get_where('applyscholarship_info','id = '.$id)->row_array();
			$student_info=$this->db->get_where('student','userid = '.$info['userid'])->row_array();
			$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			if(!empty($major_info['scholarship'])){
				$where=explode(',',$major_info['scholarship']);
				$scholarship_info=$this->db->where_in('id',$where)->where('apply_state',1)->get('scholarship_info')->result_array();
				$html = $this->_view ( 'master/scholarship/update_zaiotherscholarship_box', array (
					'scholarship_info'=>$scholarship_info,
					'id'=>$id
					), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
	}
}