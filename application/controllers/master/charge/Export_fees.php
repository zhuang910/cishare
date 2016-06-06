	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Export_fees extends Master_Basic {
		public $run_unit_nationality = array(); // 国籍
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/charge/';
			$this->load->model ( '/master/student/student_model' );
			
			$this->run_unit_nationality = CF('public','',CACHE_PATH);
		}
		
		/**
		 * 后台主页
		 */
		function index() {
			//获取 学历
			$major_info = $this->student_model->get_major_info ('id>0',0,0,'language desc');
			$degree = $this->student_model->get_degree_name('id > 0',0,0,'orderby ASC');
		
			// 获取学历
			$major_info = $this->_get_major_by_degree($major_info);
			
			$this->_view ( 'export_fees_index', array(
					'major_info' => $major_info,
					'degree' => $degree
				));
		}
		
		private function _get_major_by_degree($major_lists = array()){
			$temp = array();
			if(!empty($major_lists)){
				$degree = $this->student_model->get_degree_name('id > 0',0,0,'orderby ASC');
				
				foreach($degree as $key => $item){
					foreach($major_lists as $info){
						if($info->degree == $item['id']){
							$temp[$key]['degree_title'] = $item['title'];
							$temp[$key]['degree_major'][] = $info;
						}
					}
				}
			}
			return $temp;
    }
	
	function export_fees_do(){
		
		ini_set('memory_limit', '512M');
		set_time_limit(0);
		$data = $this->input->post();
	
		if(empty($data['degree'])){
			ajaxReturn('','无数据可导出',0);
		}
		
		if(empty($data['type'])){
			ajaxReturn('','无数据可导出',0);
		}
		$time = time();
		
		// 取数据
		$where = 'id > 0 AND paystate = 1 AND budget_type = 1';
	
		//时间段
		if(!empty($data['stime'])){
			$s = explode('-',$data['stime']);
			
			$st = mktime(0,0,0,$s[1],$s[2],$s[0]);
			$where .= ' AND paytime > '.$st;
		}
		if(!empty($data['etime'])){
			$e = explode('-',$data['etime']);
			$et = mktime(0,0,0,$e[1],$e[2],$e[0]);
			$where .= ' AND paytime < '.$et;
		}
		
		
			//费用类型
		if($data['type'] == 6){
				$user = $this->db->group_by('userid')->order_by('paytime DESC')->get_where('budget',$where.' AND (type=6 OR type=9)')->result_array();
				
				if(empty($user)){
					ajaxReturn('','无数据可导出',0);
				}
				//查询 满足的 用户
				foreach($user as $k => $v){
					$userid [] = $v['userid'];
				}
				
				//学历
				$degree = $data['degree'];
				$major_id = $this->db->get_where('major','degree = '.$degree)->result_array();
				foreach($major_id as $k => $v){
					$major_id_xy[] = $v['id'];
				}
				
				if(!empty($data['courseid'])){
					// 通过 申请表 查询 每个学生的 专业
					$majorid_all = $this->db->select('userid,courseid')->get_where('apply_info','id > 0 AND userid IN ('.implode(',',$userid).') AND courseid = '.$data['courseid'])->result_array();
				}else{
					// 通过 申请表 查询 每个学生的 专业
					$majorid_all = $this->db->select('userid,courseid')->get_where('apply_info','id > 0 AND userid IN ('.implode(',',$userid).') AND courseid IN ('.implode(',',$major_id_xy).')')->result_array();
				}
				
				if(empty($majorid_all)){
					ajaxReturn('','无数据可导出',0);
				}
				
				foreach($majorid_all as $kk => $vv){
					$userid_ex[] = $vv['userid'];
				}
				
				foreach($user as $kkk => $vvv){
					if(!in_array($vvv['userid'],$userid_ex)){
						unset($user[$kkk]);
					}else{
						$uid[] = $vvv['userid'];
						
					}
					
				}
				if(empty($user)){
					ajaxReturn('','无数据可导出',0);
				}
				
				$majorid_all = $this->db->select('id,nationality,enname,userid,passport')->get_where('student','userid IN ('.implode(',',$uid).')')->result_array();
				
				if(empty($majorid_all)){
					ajaxReturn('','无数据可导出',0);
				}
					//每个学生的 专业 属性 组合  国籍 
				foreach($majorid_all as $sk => $sv){
					//$student[$sv['id']] = !empty($major[$sv['majorid']])?$major[$sv['majorid']]:0;
					$country[$sv['id']] = !empty($sv['nationality']) && !empty($this->run_unit_nationality['global_country'][$sv['nationality']])?$this->run_unit_nationality['global_country'][$sv['nationality']]:'';
					$en_name[$sv['id']] = !empty($sv['enname'])?$sv['enname']:'';
					$passport[$sv['id']] = !empty($sv['passport'])?$sv['passport']:'';
					//$u_id[] = $sv['id'];
					$student_user[$sv['userid']] = $sv['id'];
				}
				
				//学费
				$tuition = array();
				$tuition_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','tuition')->get_where('budget',$where.' AND type = 6')->result_array();
				if(!empty($tuition_all)){
					foreach($tuition_all as $tk => $tv){
						$tuition[$tv['userid']] = !empty($tv['tuition'])?$tv['tuition']:0;
					}
				}
				
				// 保险费
				$infrancefees = array();
				$infrancefees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','infrancefees')->get_where('budget',$where.' AND type = 9')->result_array();
				
				if(!empty($infrancefees_all)){
					foreach($infrancefees_all as $tk => $tv){
						$infrancefees[$tv['userid']] = !empty($tv['infrancefees'])?$tv['infrancefees']:0;
					}
				}
				
				$arr[] = array(
					array('val'=>'序号','align'=>'center'),
					array('val'=>'日期','align'=>'center'),
					array('val'=>'国籍','width'=>30,'align'=>'center'),
					array('val'=>'姓名','width'=>30,'align'=>'center'),
					array('val'=>'护照','width'=>30,'align'=>'center'),
					array('val'=>'学费','align'=>'center'),
					array('val'=>'保险费','width'=>15,'align'=>'center'),
					array('val'=>'备注','width'=>30,'align'=>'center'),
					
				);
				
				foreach($user as $k => $v){
					
					$arr[] = array(
						array('val'=>$v['id']),
						array('val'=>!empty($v['paytime'])?date('Y/m/d',$v['paytime']):null),
						
						array('val'=>!empty($country[$student_user[$v['userid']]])?$country[$student_user[$v['userid']]]:null),
					
						array('val'=>!empty($en_name[$student_user[$v['userid']]])?$en_name[$student_user[$v['userid']]]:null),
						array('val'=>!empty($passport[$student_user[$v['userid']]])?$passport[$student_user[$v['userid']]]:null),
						array('val'=>!empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
						array('val'=>!empty($infrancefees[$v['userid']])?$infrancefees[$v['userid']]:null),
						array('val'=>!empty($v['remark'])?$v['remark']:null),
						
						
					);
				}
				
					$filename = '11111'; // 获取导入文件名
			
			if(!empty($arr)){
				// 生成导入文件
				include_once JJ_ROOT . 'application/libraries/CUCAS_ExExport.php';
				$export = new CUCAS_ExExport($filename);
				foreach ($arr as $val) {
					$export->setCells($val);
				}
				ob_start();
				$export->save();
				$data = ob_get_contents();
				ob_end_clean();

				$file = build_order_no().'.xls';
				$download_path = UPLOADS_WEB.'export/'.date('Y').'/'.date('md').'/'.$file;
				$save_path = UPLOADS.'export/'.date('Y').'/'.date('md').'/';
				mk_dir($save_path);

				file_put_contents($save_path.$file,$data);
				
				ajaxReturn($download_path,$filename.'.xls',1);
			}else{
				ajaxReturn('','无数据可导出',0);
			}
			
		}else{
			$where .= ' AND type = '.$data['type'];
			$user = $this->db->group_by('userid')->order_by('paytime DESC')->get_where('budget',$where)->result_array();
			
			if(empty($user)){
				ajaxReturn('','无数据可导出',0);
			}
			//查询 满足的 用户
			foreach($user as $k => $v){
				$userid [] = $v['userid'];
			}
			
			//学历
			$degree = $data['degree'];
			$major_id = $this->db->get_where('major','degree = '.$degree)->result_array();
			foreach($major_id as $k => $v){
				$major_id_xy[] = $v['id'];
			}
			
			if(!empty($data['courseid'])){
				// 通过 申请表 查询 每个学生的 专业
				$majorid_all = $this->db->select('userid,courseid')->get_where('apply_info','id > 0 AND userid IN ('.implode(',',$userid).') AND courseid = '.$data['courseid'])->result_array();
			}else{
				// 通过 申请表 查询 每个学生的 专业
				$majorid_all = $this->db->select('userid,courseid')->get_where('apply_info','id > 0 AND userid IN ('.implode(',',$userid).') AND courseid IN ('.implode(',',$major_id_xy).')')->result_array();
			}
			
			if(empty($majorid_all)){
				ajaxReturn('','无数据可导出',0);
			}
			
			foreach($majorid_all as $kk => $vv){
				$userid_ex[] = $vv['userid'];
			}
			
			foreach($user as $kkk => $vvv){
				if(!in_array($vvv['userid'],$userid_ex)){
					unset($user[$kkk]);
				}else{
					$uid[] = $vvv['userid'];
					
				}
				
			}
			if(empty($user)){
				ajaxReturn('','无数据可导出',0);
			}
			
			$majorid_all = $this->db->select('id,nationality,enname,userid,passport')->get_where('student','userid IN ('.implode(',',$uid).')')->result_array();
			
			if(empty($majorid_all)){
				ajaxReturn('','无数据可导出',0);
			}
				//每个学生的 专业 属性 组合  国籍 
			foreach($majorid_all as $sk => $sv){
				//$student[$sv['id']] = !empty($major[$sv['majorid']])?$major[$sv['majorid']]:0;
				$country[$sv['id']] = !empty($sv['nationality']) && !empty($this->run_unit_nationality['global_country'][$sv['nationality']])?$this->run_unit_nationality['global_country'][$sv['nationality']]:'';
				$en_name[$sv['id']] = !empty($sv['enname'])?$sv['enname']:'';
				$passport[$sv['id']] = !empty($sv['passport'])?$sv['passport']:'';
				//$u_id[] = $sv['id'];
				$student_user[$sv['userid']] = $sv['id'];
			}
			
			//各种费
			$tuition = array();
			$tuition_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','tuition')->get_where('budget',$where)->result_array();
			if(!empty($tuition_all)){
				foreach($tuition_all as $tk => $tv){
					$tuition[$tv['userid']] = !empty($tv['tuition'])?$tv['tuition']:0;
				}
			}
			
			
			if($data['type'] == 1){
				$arr[] = array(
				array('val'=>'序号','align'=>'center'),
				array('val'=>'日期','align'=>'center'),
				array('val'=>'国籍','width'=>30,'align'=>'center'),
				array('val'=>'姓名','width'=>30,'align'=>'center'),
				array('val'=>'护照','width'=>30,'align'=>'center'),
				array('val'=>'申请费','align'=>'center'),
				array('val'=>'备注','width'=>30,'align'=>'center'),
				
				);
			}else if($data['type'] == 8){
				$arr[] = array(
				array('val'=>'序号','align'=>'center'),
				array('val'=>'日期','align'=>'center'),
				array('val'=>'国籍','width'=>30,'align'=>'center'),
				array('val'=>'姓名','width'=>30,'align'=>'center'),
				array('val'=>'护照','width'=>30,'align'=>'center'),
				array('val'=>'书费','align'=>'center'),
				array('val'=>'备注','width'=>30,'align'=>'center'),
				
			);
			}
			
			
			foreach($user as $k => $v){
				
				$arr[] = array(
					array('val'=>$v['id']),
					array('val'=>!empty($v['paytime'])?date('Y/m/d',$v['paytime']):null),
					
					array('val'=>!empty($country[$student_user[$v['userid']]])?$country[$student_user[$v['userid']]]:null),
				
					array('val'=>!empty($en_name[$student_user[$v['userid']]])?$en_name[$student_user[$v['userid']]]:null),
					array('val'=>!empty($passport[$student_user[$v['userid']]])?$passport[$student_user[$v['userid']]]:null),
					array('val'=>!empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
				
					array('val'=>!empty($v['remark'])?$v['remark']:null),
					
					
				);
			}
			
				$filename = '11111'; // 获取导入文件名
		
		if(!empty($arr)){
			// 生成导入文件
			include_once JJ_ROOT . 'application/libraries/CUCAS_ExExport.php';
			$export = new CUCAS_ExExport($filename);
			foreach ($arr as $val) {
				$export->setCells($val);
			}
			ob_start();
			$export->save();
			$data = ob_get_contents();
			ob_end_clean();

			$file = build_order_no().'.xls';
			$download_path = UPLOADS_WEB.'export/'.date('Y').'/'.date('md').'/'.$file;
			$save_path = UPLOADS.'export/'.date('Y').'/'.date('md').'/';
			mk_dir($save_path);

			file_put_contents($save_path.$file,$data);
			
			ajaxReturn($download_path,$filename.'.xls',1);
		}else{
			ajaxReturn('','无数据可导出',0);
		}
			
			
		}
	
	}
}