<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * @name 		申请管理-全部申请
 * @package 	apply
 * @author 		cucas Team [ZD]
 * @copyright   Copyright (c) 2014-3-10, cucas
 **/
class process extends Master_Basic {
	/**
	 * 全部申请
	 **/
	function __construct() {
		parent::__construct ();
	
		$this->load->model ( 'master/enrollment/edit_app_info_model' );
		
	}
	
	//初始化
	function edit_app_info() {
		$id = trim ( $this->input->post ( 'pk' ) );         //获取记录ID值
		$key = trim ( $this->input->post ( 'name' ) );		//获取记录字段值
		$value = trim ( $this->input->post ( 'value' ) ); 	//获取记录更新值
		$value_format = explode('^',$id);					//分割字符串，获得^号后值，用来判断是否是日期格式
		
		if($value_format[1]=='date'){
			$value=strtotime($value);
		}
		$id=$value_format[0];
		//如果为申请完成状态修改时，同时修改WWW端对应状态
		if($key=='appfinish'){
			$update_array=array($key => $value); 				//准备更新数据
			$result = $this->edit_app_info_model->update_app_info_www ( $id, $update_array );
			if(!$result)
				ajaxReturn ( '', '更新WWW端状态失败，请重试', 0 );
		}
		
		if($key=='onlineappyes'){
			$update_array=array($key => $value,'onlineapptime'=>time()); 				//准备更新数据
		}else if($key=='paperdocumentyes'){
			$update_array=array($key => $value,'paperdocumenttime'=>time()); 				//准备更新数据
		}else if($key=='cucasofferreceived'){
			$update_array=array($key => $value,'cucasofferreceivedtime'=>time()); 				//准备更新数据
		}else if($key=='enrollmentyes'){
			$update_array=array($key => $value,'enrollmentconfirmtime'=>time()); 				//准备更新数据
		}else if($key=='appfinish'){
			$update_array=array($key => $value,'appfinishtime'=>time()); 				//准备更新数据
		}else if($key=='commissionyes'){
			$update_array=array($key => $value,'commissionyestime'=>time()); 				//准备更新数据
		}else{
			$update_array=array($key => $value); 				//准备更新数据
		}
		
		//执行更新
		$result = $this->edit_app_info_model->update_app_info ( $id, $update_array );
		
		if ($result) {
			ajaxReturn ( '', '更新成功', 1 );
		}else{
			ajaxReturn ( '', '更新失败，请重试', 0 );
		}
	}	
	
	//修改课程信息
	function edit_app_course_info() {
		$id = trim ( $this->input->post ( 'pk' ) );         //获取记录ID值
		$key = trim ( $this->input->post ( 'name' ) );		//获取记录字段值
		$value = trim ( $this->input->post ( 'value' ) ); 	//获取记录更新值
		$value_format = explode('^',$id);					//分割字符串，获得^号后值，用来判断是否是日期格式
	
		if($value_format[1]=='date'){
			$value=strtotime($value);
		}
		$id=$value_format[0];
		//如果为申请完成状态修改时，同时修改WWW端对应状态
		if($key=='appfinish'){
			$update_array=array($key => $value); 				//准备更新数据
			$result = $this->edit_app_info_model->update_app_info_www ( $id, $update_array );
			if(!$result)
				ajaxReturn ( '', '更新WWW端状态失败，请重试', 0 );
		}
	
		if($key=='onlineappyes'){
			$update_array=array($key => $value,'onlineapptime'=>time()); 				//准备更新数据
		}else if($key=='paperdocumentyes'){
			$update_array=array($key => $value,'paperdocumenttime'=>time()); 				//准备更新数据
		}else if($key=='cucasofferreceived'){
			$update_array=array($key => $value,'cucasofferreceivedtime'=>time()); 				//准备更新数据
		}else if($key=='enrollmentyes'){
			$update_array=array($key => $value,'enrollmentconfirmtime'=>time()); 				//准备更新数据
		}else if($key=='appfinish'){
			$update_array=array($key => $value,'appfinishtime'=>time()); 				//准备更新数据
		}else if($key=='commissionyes'){
			$update_array=array($key => $value,'commissionyestime'=>time()); 				//准备更新数据
		}else{
			$update_array=array($key => $value); 				//准备更新数据
		}
	
		//执行更新
		$result = $this->edit_app_info_model->update_app_course_info ( $id, $update_array );
	
		if ($result) {
			ajaxReturn ( '', '更新成功', 1 );
		}else{
			ajaxReturn ( '', '更新失败，请重试', 0 );
		}
	}
	
	
	//编辑备注信息
	function edit_app_remark_info() {
		
		$id = trim ( $this->input->post ( 'pk' ) );         //获取记录ID值
		$key = trim ( $this->input->post ( 'name' ) );		//获取记录字段值
		$value = trim ( $this->input->post ( 'value' ) ); 	//获取记录更新值
		$value_format = explode('^',$id);					//分割字符串，获得^号后值，用来判断是否是日期格式
	
		if($value_format[1]=='date'){
			$value=strtotime($value);
		}
		$id=$value_format[0];
		//如果为申请完成状态修改时，同时修改WWW端对应状态
		if($key=='appfinish'){
			$update_array=array($key => $value); 				//准备更新数据
			$result = $this->edit_app_info_model->update_app_info_www ( $id, $update_array );
			if(!$result)
				ajaxReturn ( '', '更新WWW端状态失败，请重试', 0 );
		}
	
		if($key=='onlineappyes'){
			$update_array=array($key => $value,'onlineapptime'=>time()); 				//准备更新数据
		}else if($key=='paperdocumentyes'){
			$update_array=array($key => $value,'paperdocumenttime'=>time()); 				//准备更新数据
		}else if($key=='cucasofferreceived'){
			$update_array=array($key => $value,'cucasofferreceivedtime'=>time()); 				//准备更新数据
		}else if($key=='enrollmentyes'){
			$update_array=array($key => $value,'enrollmentconfirmtime'=>time()); 				//准备更新数据
		}else if($key=='appfinish'){
			$update_array=array($key => $value,'appfinishtime'=>time()); 				//准备更新数据
		}else if($key=='commissionyes'){
			$update_array=array($key => $value,'commissionyestime'=>time()); 				//准备更新数据
		}else{
			$update_array=array($key => $value); 				//准备更新数据
		}
		
		//执行更新
		$result = $this->edit_app_info_model->update_app_remark_info ( $id, $update_array );
	
		if ($result) {
			// 写入日志
			//组织信息 首先 查用户的id
			$userid = $this->db->select('userid,apply_user')->get_where('apply_info','id = '.$id)->row();
            if(empty($userid->apply_user)){
                $this->edit_app_info_model->update_app_remark_info ( $id, array(
                    'apply_user' => $this->adminid
                ) );
            }

			//查询邮箱
			$email = $this->db->select('email')->get_where('student_info','id = '.$userid->userid)->row();
			
			$datalog = array (
					'adminid' => $_SESSION ['master_user_info']->id,
					'adminname' => $_SESSION ['master_user_info']->username,
					'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '给申请用户' .$email->email . '添加了备注',
					'ip' => get_client_ip (),
					'lasttime' => time (),
					'type' => 2,
					'remark' => !empty($update_array['remark'])?$update_array['remark']:'',
					'appid' => $id
			);
			if (! empty ( $datalog )) {
				$this->adminlog->savelog ( $datalog );
			}
			ajaxReturn ( '', '更新成功', 1 );
		}else{
			ajaxReturn ( '', '更新失败，请重试', 0 );
		}
	}
	

	
	//结束申请
	function over_app() {
		$id = intval ( $this->input->get ( 'id' ) );
		if (! empty ( $id )) {
			$data = array(
				'state' => '9'	
			);
			$result = $this->edit_app_info_model->app_over_update($id, $data);
			
			if ($result === true) {
				// 写入日志
				//组织信息 首先 查用户的id
				$userid = $this->db->select('userid')->get_where('apply_info','id = '.$id)->row();
				//查询邮箱
				$email = $this->db->select('email')->get_where('student_info','id = '.$userid->userid)->row();
				
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了申请用户' .$email->email . '的状态为结束',
						'ip' => get_client_ip (),
						'lasttime' => time (),
						'type' => 2,
						'appid' => $id
						
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				
				
				ajaxReturn ( '', '更新成功', 1 );
			}
			ajaxReturn ( '', '更新失败', 0 );
		}
		ajaxReturn ( '', '缺少必要参数', 0 );
	}
	
	/**
	 * 查看用户操作记录
	 */
	
	function  cat_user_operate(){
		$id = trim ( $this->input->get( 'id' ) );         //获取申请ID值
		$type = trim ( $this->input->get( 'type' ) );     //获取类型
        
		if(!empty($id)){
			if($type=='alert'){
				$result = $this->edit_app_info_model->cat_user_operate($id);
				foreach ($result as $key =>$info){
					$data['paperdocumentyes'][] = date('Y-m-d H:i',$info->actiontime)."|".$info->action.'<br/>';
				} 
				$data['status']='Y';
				$data['type'] = "1";
			}
			echo json_encode($data);
		}else{
			echo json_encode(array('status'=>'N'));
		}
	}
	
	/**
	 * 查看用户确认的地址信息
	 */
	
	function  cat_user_address_info(){
		$id = trim ( $this->input->get( 'id' ) );         //获取申请ID值
		$type = trim ( $this->input->get( 'type' ) );     //获取类型
		if(!empty($id)){
			if($type=='alerts'){
				$result = $this->edit_app_info_model->cat_user_address_info($id);
				$data['name']=$result->name;
				$data['tel']=$result->tel;
				$data['address']=!empty($result->address)?$result->address:'';
				$data['mail_address']=$result->building.'|'.$result->street.'|'.$result->city.'|'.$result->country;
				$data['postcode']=$result->postcode;
				$data['status']='Y';
				$data['type'] = "1";
			}
			echo json_encode($data);
		}else{
			echo json_encode(array('status'=>'N'));
		}
	}
	

	
}