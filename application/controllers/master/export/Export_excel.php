<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * Created by CUCAS TEAM.
 * User: JunJie
 * E-Mail:zhangjunjie@cucas.cn
 * Date: 15-1-14
 * Time: 下午12:21
 */
class Export_excel extends Master_Basic
{

	public $export_config_main = array();
	public $export_config_join = array();
	public $run_form_id = 0;
	public $set_field = array();
	public $cre_type=array(
				6=>'tuition',
				7=>'electric',
				8=>'book',
				4=>'acc',
				9=>'insurance',
				10=>'acc_pledge',
				14=>'electric_pledge',
				13=>'bedding',
				12=>'rebuild',
				11=>'barter_card',
				1=>'apply',
				2=>'pledge'
			);
	public $run_user_sex = array(
		0=>'其他',
		1 => '男',
		2 => '女'
	); // 性别
	public $run_unit_deadline = array(
		1 => '半年',
		2 => '一年'
	); // 保险期限
	public $run_unit_nationality = array(); // 国籍
	


	public $run_unit_turnover = array(); // 单位营业额/年
	public $run_pass_state = array(); // 通过状态
	public $run_user_level = array(); // 用户等级
	public $run_user_province = array(); // 省份
	public $run_offshoot = array(); // 分支机构
	public $run_fees = array(); // 会费标准
	public $run_fees_e = array(); // 会费额度
	public $run_viptop = array(); // 是否为会员代表
	public $run_re_form_isok = array(); // 选举申请表是否完成
	public $run_mc_state = array(); // 稿件处理状态
	public $run_c_fees_state = array(); // 会费缴纳状态
	public $run_invoice_state = array(); // 发票邮寄状态


	function __construct()
	{
		parent::__construct();
		$this->view = '/master/export/';
		//考试配置文件重新取
		$this->_reset_score();
		$this->_reset_teacher_score();
		$this->load->model($this->view.'export_model');

		$this->export_config_main = CF('export_field', '', CACHE_PATH);

		$this->export_config_join = CF('export_join','',CACHE_PATH);

		$this->run_unit_nationality = CF('public','',CACHE_PATH);
		
	}

	function index()
	{
		$this->_get_data_field();
		$this->_view('export_excel_index');
	}
	/**
	 *考试配置文件重新取
	 **/
	function _reset_score(){
		$score=CF('field_score','',CACHE_PATH . 'export/');
		//查处所有的考试类型
		$score_type=$this->db->get_where('set_score','state = 1')->result_array();
		if(!empty($score_type)){
			$append_data=array();
			  $append_data[]= array(
			      'field' => 'student_id' ,
			      'name' => '序号' ,
			      );
			  $append_data[]= array(
			      'field' => 'student_nationality' ,
			      'name' => '国别' ,
			      );
			  $append_data[]= array(
			      'field' => 'student_enname' ,
			      'name' => '英文姓名' ,
			      );
			  $append_data[]= array(
			      'field' => 'student_name' ,
			      'name' => '中文姓名' ,
			      );
			
			foreach ($score_type as $k => $v) {
				$append_data[]=array(
						'field'=>$v['id'],
						'name'=>$v['name'],
						'type'=>$v['id']
					);
			}
			$append_data[]=array(
						'field'=>'show_score',
						'name'=>'课堂表现',
						'type'=>'shh'
					);
			$append_data[]=array(
						'field'=>'score_check',
						'name'=>'出勤率',
						'type'=>'shh'
					);
		}

		CF('field_score',$append_data,CACHE_PATH . 'export/');
	}
	/**
	 *考试配置文件重新取(老师)
	 **/
	function _reset_teacher_score(){
		$score=CF('field_teacher_score','',CACHE_PATH . 'export/');
		//查处所有的考试类型
		$score_type=$this->db->get_where('set_score','state = 1')->result_array();
		if(!empty($score_type)){
			$append_data=array();
			  $append_data[]= array(
			      'field' => 'student_id' ,
			      'name' => '序号' ,
			      );
			  $append_data[]= array(
			      'field' => 'student_nationality' ,
			      'name' => '国别' ,
			      );
			  $append_data[]= array(
			      'field' => 'student_enname' ,
			      'name' => '英文姓名' ,
			      );
			  $append_data[]= array(
			      'field' => 'student_name' ,
			      'name' => '中文姓名' ,
			      );
			
			foreach ($score_type as $k => $v) {
				$append_data[]=array(
						'field'=>$v['id'],
						'name'=>$v['name'],
						'type'=>$v['id']
					);
			}
			$append_data[]=array(
						'field'=>'show_score',
						'name'=>'课堂表现',
						'type'=>'shh'
					);
			$append_data[]=array(
						'field'=>'score_check',
						'name'=>'出勤率',
						'type'=>'shh'
					);
			$append_data[]=array(
						'field'=>'total',
						'name'=>'总评分',
						'stype'=>'abv'
				);
			$append_data[]=array(
						'field'=>'remark',
						'name'=>'备注',
						'stype'=>'abv'
				);
		}

		CF('field_teacher_score',$append_data,CACHE_PATH . 'export/');
	}
	
	function a(){
	
		$arr[] = array(
			array('val'=>'序号','align'=>'center','rowspan'=>3),
			array('val'=>'日期','align'=>'center','rowspan'=>3),
			array('val'=>'刷卡姓名','width'=>30,'align'=>'center','rowspan'=>3),
			array('val'=>'国籍','width'=>30,'align'=>'center','rowspan'=>3),
			array('val'=>'刷价金额','align'=>'center','rowspan'=>3),
			array('val'=>'学校管理费','align'=>'center','colspan' => 9),
			array('val'=>'学生奖助学','width'=>15,'align'=>'center'),
			array('val'=>'留学生业务费','align'=>'center','colspan' => 4),
			array('val'=>'二级学院留学生经费','width'=>30,'align'=>'center'),
			array('val'=>'其他','align'=>'center','rowspan'=>3),
			array('val'=>'备注','align'=>'center','rowspan'=>3),
			
		);
		
		$arr[] = array(
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>'申请费','align'=>'center','rowspan' => 2),
			array('val'=>'本科生学费','align'=>'center','colspan' => 2),
			array('val'=>'研究生学费','align'=>'center','colspan' => 2),
			array('val'=>'长期语言进修生','width'=>15,'align'=>'center'),
			array('val'=>'短期培训团学费','align'=>'center','colspan' => 2),
			array('val'=>'住宿费','align'=>'center','rowspan' => 2),
			array('val'=>'押金','align'=>'center','rowspan' => 2),
			array('val'=>'保险费','align'=>'center','rowspan' => 2),
			array('val'=>'书费','align'=>'center','rowspan' => 2),
			array('val'=>'床品费','align'=>'center','rowspan' => 2),
			array('val'=>'电费','align'=>'center','rowspan' => 2),
			array('val'=>'外国留学生短期团组经费','width'=>30,'align'=>'center','rowspan' => 2),
			array('val'=>null),
			array('val'=>null),
			
		);
		
		$arr[] = array(
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'英语','align'=>'center'),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'英语','align'=>'center'),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'英语','align'=>'center'),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			
		);
		

		include_once JJ_ROOT.'application/libraries/CUCAS_ExExport.php';
		$export = new CUCAS_ExExport('导出');
		foreach($arr as $val){
			$export->setCells($val);
		}
		$export->save();
		
	}
	
	/**
	 * 导出
	 */
	function export_go()
	{
		$data = $this->_inits();
		$filename = '123123'; // 获取导入文件名

		if(!empty($data)){
			// 生成导入文件
			include_once JJ_ROOT . 'application/libraries/CUCAS_ExExport.php';
			$export = new CUCAS_ExExport($filename);
			foreach ($data as $val) {
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
	
	/**
	 * 导出 刷卡记录的数据
	 */
	function export_go_refresh()
	{
		$data = $this->_inits();
		$filename = '11111'; // 获取导入文件名

		if(!empty($data)){
			// 生成导入文件
			include_once JJ_ROOT . 'application/libraries/CUCAS_ExExport.php';
			$export = new CUCAS_ExExport($filename);
			foreach ($data as $val) {
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
	
	
	private function _inits(){
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		
		if($this->run_form_id == 'insurance_info'){
			$data = $this->get_insurance_info();
			return $data;
		}
		if($this->run_form_id == 'course_elective'){
			$data = $this->get_course_elective();
			return $data;
		}
		if($this->run_form_id == 'books_fee'){
			$data = $this->get_books_fee();
			return $data;
		}
		
		if($this->run_form_id == 'student'){
			$data = $this->get_student_data();
			return $data;
		}
		if($this->run_form_id == 'checking'){
			$data = $this->get_checking();
			return $data;
		}
		if($this->run_form_id == 'squad'){
			$data = $this->get_evea_squad();
			return $data;
		}
		if($this->run_form_id == 'evaluat_student'){
			$data = $this->get_evea_teacher();
			return $data;
		}
		if($this->run_form_id == 'score'){
			$data = $this->get_score();
			return $data;
		}
		if($this->run_form_id == 'teacher_score'){
			$data = $this->get_teacher_score();
			return $data;
		}
		if($this->run_form_id == 'budget'){
			$data = $this->get_budget();
			return $data;
		}
		if($this->run_form_id == 'credentials'){
			$data = $this->get_credentials();
			return $data;
		}
		if($this->run_form_id == 'accommodation_info'){
			$data = $this->get_accommodation_info();
			return $data;
		}
		if($this->run_form_id == 'room_electric_record'){
			$data = $this->get_room_electric_record();
			return $data;
		}
		if($this->run_form_id == 'student_budget'){
			$data = $this->get_student_budget();
			return $data;
		}
		if($this->run_form_id == 'student_refresh'){
			$data = $this->get_student_refresh();
			return $data;
		}
		if($this->run_form_id == 'student_online'){
			$data = $this->get_student_onlines();
			return $data;
		}
		if($this->run_form_id == 'fees_export'){
			$data = $this->get_student_fees_export();
			return $data;
		}
		
	}
	
	

	/*保险费insurance_info*/
	public function get_insurance_info(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }

		}
		//链接查询字符串
		$filed_str='';
		if(!empty($this->set_field)){
			foreach ($sss['set_field'] as $k => $v) {
				$filed_str.=$v.',';
			}
			$filed_str=trim($filed_str,',');
		}
		//获取数据表中的数据
		$data = $this->export_model->get_data($this->run_form_id,$export_join,$filed_str);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$data_con = $this->_convert_data($data);
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[$i]=$this->export_model->_get_tou($filed_num);
				}
			}
			$body=$this->export_model->get_info_body($data_con);
			$return_data=array_merge($return_data,$body);
			return $return_data;
		 }
	}
	/*选课名单course_elective*/
	public function get_course_elective(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }

		}
		//链接查询字符串
		$filed_str='';
		if(!empty($this->set_field)){
			foreach ($sss['set_field'] as $k => $v) {
				$filed_str.=$v.',';
			}
			$filed_str=trim($filed_str,',');
		}
		//获取数据表中的数据
		$data = $this->export_model->get_data($this->run_form_id,$export_join,$filed_str);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$data_con = $this->_convert_data($data);
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[0] =array(
						array('val'=>'选课名单', 'font-size'=>18,'colspan'=>count($filed_num),'align'=>'center'),
					);
					$return_data[$i+1]=$this->export_model->_get_tou($filed_num);
				}
			}
			$body=$this->export_model->get_info_body($data_con);
			$return_data=array_merge($return_data,$body);
			return $return_data;
		 }
	}
	/*教材导出清单books_fee*/
	public function get_books_fee(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');


		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }

		}
		//链接查询字符串
		$filed_str='';
		if(!empty($this->set_field)){
			foreach ($sss['set_field'] as $k => $v) {
				$filed_str.=$v.',';
			}
			$filed_str=trim($filed_str,',');
		}
		//获取数据表中的数据
		$data = $this->export_model->get_books_data($this->run_form_id,$export_join,$filed_str);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$data_con = $this->_convert_data($data);
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[$i]=$this->export_model->_get_tou($filed_num);
				}
			}
			$body=$this->export_model->get_info_body($data_con);
			$return_data=array_merge($return_data,$body);
			return $return_data;
		 }
	}
	
	/*教材导出清单books_fee*/
	public function get_student_onlines(){
			ini_set('memory_limit', '512M');
		set_time_limit(0);
		$sss=$this->input->post();
		//$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');


		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }

		}
		
		//链接查询字符串
		$filed_str='';
		if(!empty($this->set_field)){
			foreach ($sss['set_field'] as $k => $v) {
				$filed_str.=$v.',';
			}
			$filed_str=trim($filed_str,',');
		}
		
		//获取数据表中的数据
		$data = $this->db->select($filed_str)->get_where('student','id > 0')->result_array();
		
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$data_con = $this->_convert_data($data);
		
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[$i]=$this->export_model->_get_tou($filed_num);
				}
			}
			$body=$this->export_model->get_info_body($data_con);
			$return_data=array_merge($return_data,$body);
			return $return_data;
		 }
	}
	/*成绩统计总表-简单 */
	public function get_student_data(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//头部课程
		if(!empty($sss['set'])){
			foreach ($sss['set'] as $k => $v) {
				$this->db->select('name');
			$get_course_tou[] = $this->db->where('id',$sss['set'][$k])->get('course')->row_array();
			}
		}
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }

		}
		//链接查询字符串
		$filed_str='';
		if(!empty($this->set_field)){
			foreach ($sss['set_field'] as $k => $v) {
				$filed_str.=$v.',';
			}
			$filed_str=trim($filed_str,',');
		}
		//获取数据表中的数据
		$data = $this->export_model->get_course_data($sss['squadid'],$this->run_form_id,$filed_str);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$score = $this->export_model->get_course_score($sss['majorid'],$sss['nowterm'],$sss['squadid'],$sss['scoretype'],$sss['set']);
		$data_con = $this->_convert_data($data);
		//获取表头的专业、学期、班级、考试类型信息
		if(!empty($sss)){
			$major_tou = $this->db->select('name')->get_where('major','id='.$sss['majorid'])->row_array();
			$squad_tou = $this->db->select('name')->get_where('squad','id='.$sss['squadid'])->row_array();
			$scoretype_tou = $this->db->select('name')->get_where('set_score','id='.$sss['scoretype'])->row_array();
		}
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[0]=array(
						array('val'=>''.$major_tou['name'].'第'.$sss['nowterm'].'学期'.''.$squad_tou['name'].'的'.$scoretype_tou['name'].'成绩统计总表','colspan'=>count($filed_num)+count($sss['set'])+1,'align'=>'center')
						);
					$return_data[1]=array(
						array('val'=>$squad_tou['name'],'colspan'=>count($filed_num),'align'=>'center'),
						array('val'=>'总评分','colspan'=>count($sss['set']),'align'=>'center'),
						array('val'=>'平均分','rowspan'=>2,'align'=>'center')
						);
					$return_data[$i+2]=$this->export_model->_get_course_tou($filed_num,$get_course_tou);
				}
			}
			$body=$this->export_model->_get_body($sss['set'],$data_con,$score,count($sss['set']));
			$return_data=array_merge($return_data,$body);
			return $return_data;
		 }
	}
	/*考勤汇总-按月*/
	public function get_checking(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//头部课程
		if(!empty($sss['set'])){
			foreach ($sss['set'] as $k => $v) {
				$this->db->select('name');
				$get_course_tou[] = $this->db->where('id',$sss['set'][$k])->get('course')->row_array();
			}
		}
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		$stu_count = count($filed_num);
		$count_num = 0;
		foreach ($sss['set_field'] as $kk => $vv) {
			if($vv == 'checking.type_one'){
				$count_num++;
			}
			if($vv == 'checking.type_two'){
				$count_num++;
			}
			if($vv == 'checking.type_three'){
				$count_num++;
			}
		}
		//获取数据表中的数据
		$data = $this->export_model->get_checking_data($sss['set_field'],$sss['majorid'],$sss['squadid'],$sss['nowterm'],$sss['opentime']);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		//转换表中的国家、性别等信息(由int数据转换为相应信息)
		$data_con = $this->_convert_data($data);
		//获取表头的专业、学期、班级、考试类型信息
		if(!empty($sss)){
			$major_tou = $this->db->select('name')->get_where('major','id='.$sss['majorid'])->row_array();
			$squad_tou = $this->db->select('name')->get_where('squad','id='.$sss['squadid'])->row_array();
		}
		//获取班主任的姓名
		$teacher_name = $this->export_model->get_teacher_name($sss['squadid']);
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[0]=array(
						array('val'=>''.$major_tou['name'].'第'.$sss['nowterm'].'学期'.''.$squad_tou['name'].$sss['opentime'].'至'.$sss['endtime'].'的考勤汇总','colspan'=>count($filed_num)+1,'align'=>'center')
						);
					$return_data[1]=array(
						array('val'=>$teacher_name,'colspan'=>count($filed_num)+1,'align'=>'center')
						);
					$return_data[2]=array(
						array('val'=>null,'colspan'=>$stu_count-$count_num),
						array('val'=>'月份','colspan'=>$count_num,'align'=>'center'),
						array('val'=>'出勤率','rowspan'=>2,'align'=>'center')
						);
					$return_data[$i+3]=$this->export_model->_get_tou($filed_num);
					$return_data[$i+3]['rowspan'] = 2;
				}
			}
			$body=$this->export_model->_get_check_body($sss['set_field'],$data_con,$sss['majorid'],$sss['squadid'],$sss['nowterm'],$sss['opentime'],$sss['endtime']);
			$return_data=array_merge($return_data,$body);
			return $return_data;
		 }
	}
	/*学生评价教师汇总-班级*/
	public function get_evea_squad(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		$squad_info=$this->db->get_where('squad','majorid = '.$sss['majorid'].' AND nowterm = '.$sss['nowterm'])->result_array();
		//获取数据表中的数据
		$data = $this->export_model->get_squ_data($sss['majorid'],$sss['nowterm'],$sss['year']);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$rig_data = $this->export_model->get_rig_data($sss['majorid'],$sss['nowterm'],$sss['year']);
		//转换表中的国家、性别等信息(由int数据转换为相应信息)
		$data_con = $this->_convert_data($data);
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data_con)){
			for ($i=0; $i <count($data_con)+1 ; $i++) { 
				if($i==0){
					$return_data[0]=array(
						array('val'=>'学生评教汇总表','colspan'=>count($filed_num),'align'=>'center','font-size'=>'16px')
						);
					$return_data[$i+1]=$this->export_model->_get_tou($filed_num);
				}
			}
			$body=$this->export_model->_get_squ_body($rig_data,$field_lists,$sss['set_field'],$data,$sss['majorid'],$sss['nowterm'],$sss['year']);
			$body_data = array();
			$hebing=0;
			foreach ($squad_info as $k => $v) {
				//查询有几个课程跟老老师
				
				 $p_info=$this->db->group_by('teacherid,courseid')->get_where('evaluate_student','squadid = '.$v['id'].' AND term = '.$v['nowterm'])->result_array();
				 if(empty($p_info)){
				 	continue;
				 }
				$course_name = array();
				 $teacher_name = array();
				 foreach ($p_info as $js => $nt) {
				 	$course_name[$js]['id'] = $nt['courseid'];
				 	$teacher_name[$js]['id'] = $nt['teacherid'];
				 	$course_name[$js]['name'] = $this->export_model->get_course_name($nt['courseid']);
				 	$teacher_name[$js]['name'] = $this->export_model->get_tea_name($nt['teacherid']);
				 }
				  $user_count=$this->db->group_by('userid')->get_where('evaluate_student','squadid = '.$v['id'].' AND term = '.$v['nowterm'])->result_array();
				if(in_array('squad_id',$sss['set_field'])){
					$body_data[$k+$hebing][] = array('val'=>$v['id'],'rowspan'=>count($p_info));
					
				}
				if(in_array('squad_name',$sss['set_field'])){
					$body_data[$k+$hebing][] = array('val'=>$v['name'],'rowspan'=>count($p_info));
				}
				if(in_array('evaluat_count',$sss['set_field'])){
					$body_data[$k+$hebing][] = array('val'=>count($user_count),'rowspan'=>count($p_info));
				}

				if(in_array('course_name',$sss['set_field'])){
					$body_data[$k+$hebing][] = array('val'=>$course_name[0]['name']);
				}
				if(in_array('teacher_name',$sss['set_field'])){
					$body_data[$k+$hebing][] = array('val'=>$teacher_name[0]['name']);
				}
				$score_p=$this->export_model->get_tea_course($v['id'],$course_name[0]['id'],$teacher_name[0]['id'],$sss['year']);
				if(in_array('evaluate_student_item',$sss['set_field'])){
					$body_data[$k+$hebing][] = array('val'=>$score_p);
				}	

				for ($i=1; $i <count($p_info) ; $i++) {
				$score_ps=$this->export_model->get_tea_course($v['id'],$course_name[$i]['id'],$teacher_name[$i]['id'],$sss['year']); 
					if(in_array('squad_id',$sss['set_field'])){
						$body_data[$k+$hebing+$i][] = array('val'=>null);
					}
					if(in_array('squad_name',$sss['set_field'])){
						$body_data[$k+$hebing+$i][] = array('val'=>null);
					}
					if(in_array('evaluat_count',$sss['set_field'])){
						$body_data[$k+$hebing+$i][] = array('val'=>null);
					}

					if(in_array('course_name',$sss['set_field'])){
						$body_data[$k+$hebing+$i][] = array('val'=>$course_name[$i]['name']);
					}
					if(in_array('teacher_name',$sss['set_field'])){
						$body_data[$k+$hebing+$i][] = array('val'=>$teacher_name[$i]['name']);
					}
					if(in_array('evaluate_student_item',$sss['set_field'])){
						$body_data[$k+$hebing+$i][] = array('val'=>$score_ps);
					}

					$hebing+=1;
				}
			}	
			$return_data=array_merge($return_data,$body_data);
			return $return_data;
		 }
	}
	/*学生评价教师汇总-教师*/
	public function get_evea_teacher(){
		$sss=$this->input->post();
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//拼凑表头
		$teac_data = array();
		foreach ($field_lists as $js => $nt) {
			$teac_data[] = array('val'=>$nt['name']);
		}
		//获取数据表中的数据
		$data = $this->export_model->get_teacher_data($sss['majorid'],$sss['year']);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		$data_re = array();
		$data_ta = array();
		foreach ($data as $js => $nt) {

			$data_re[] = $nt;
		}
		
		foreach ($data_re as $vn => $ez) {
			$num = 0;
			$data_ta[$vn][] = $ez['id'];
			$data_ta[$vn][] = $ez['teacherid'];
			for ($p=0; $p < count($ez['item']); $p++) { 
				$num +=  $ez['item'][$p]['item'];
				
			}
			$data_ta[$vn][] = $num / count($ez['item']);
			for ($z=0; $z < count($ez['item']); $z++) { 
				$data_ta[$vn][] = $ez['item'][$z]['item'];
			}
		}
		foreach ($data_ta as $xp => $vn) {
			for ($x=0; $x < count($vn); $x++) { 
				$re_data[$xp+2][$x] = array('val'=>$vn[$x]);
					
			}
		}
		//拼凑数据格式并返回数据
		$return_data=array();
		if(!empty($data)){
			for ($i=0; $i <count($data)+1 ; $i++) { 
				if($i==0){
					$return_data[0]=array(
						array('val'=>'学生评价教师汇总表','colspan'=>count($field_lists),'align'=>'center')
						);
					$return_data[$i+1]=$teac_data;
				}
			}
		

			// $body=$this->export_model->_get_body();
			$return_data=array_merge($return_data,$re_data);
			return $return_data;
		 }
	}
	/*语言生成绩统计详细*/
	public function get_score(){
		$sss=$this->input->post();
			$this->run_form_id = $this->input->get_post('table_id');//数据表名
			$this->set_field = $this->input->get_post('set_field');//表字段
			$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
			//删除没有选中的头部信息
			foreach ($field_lists as $m => $n) {
				$filed_num[] = $n;
			}
			foreach ($filed_num as $key => $value) {
				if(in_array($value['field'], $sss['set_field']) == false){
				 	unset($filed_num[$key]);
				 }
			}
			//获取课程的名称
			$course_data = $this->export_model->get_score_course_data($sss['majorid'],$sss['squadid']);
			$header_h = array();
			$student_num=0;
			$type_num=0;
			$score_ids=array();
			$linghuo_arr=array();
			foreach ($filed_num as $js => $nt) {
				$header_h[] = array('val'=>$nt['name']);
				if(!empty($nt['type'])){
					$linghuo_arr[]=array('val'=>$nt['name']);
					$type_num+=1;
					if($nt['type']!='shh'){
						$score_ids[]=$nt['type'];
					}
				}else{
					$student_num+=1;
				}
				
			}
			for($i=1;$i<count($course_data);$i++) {
				$header_h = array_merge($header_h,$linghuo_arr);
			}
			foreach ($course_data as $ke => $va) {
				$header_h[] = array('val'=>$va['courseid']);
			}
			//获取班级名称
			$squad_data = $this->export_model->get_squ_name($sss['squadid']);
			
			
			//拼凑表头
			$header_t = array();
			$header_t[] = array('val'=>$squad_data,'colspan'=>$student_num,'align'=>'center');
			foreach ($course_data as $nu => $mu) {
				$header_t[] = array('val'=>$mu['courseid'],'colspan'=>$type_num,'align'=>'center');
			}
			$header_t[]=array('val'=>'总评分','colspan'=>count($course_data),'align'=>'center');
			$header_t[]=array('val'=>'备注');
			//获取数据表中的数据
			$data = $this->export_model->get_score_data($sss['majorid'],$sss['squadid']);
			if(empty($data)){
				ajaxReturn('','无数据可导出',0);
			}
			//拼凑数据
			foreach ($data as $wq => $cs) {
				foreach ($course_data as $key => $value) {
					foreach($score_ids as $k=>$v) {
						$data[$wq]['cua'][] = $this->export_model->get_exam($cs['id'],$value['id'],$v,$sss['majorid'],$sss['nowterm'],$sss['squadid']);
					}
					if(in_array('show_score', $sss['set_field'])){
						$data[$wq]['cua'][] =$this->export_model->get_class($cs['id'],$value['id'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$score_ids);
					}
					if(in_array('score_check', $sss['set_field'])){
						$data[$wq]['cua'][] = $this->export_model->get_student_rate($cs['id'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$value['id']);
					}
				}
				
				foreach ($course_data as $key => $value) {
					$per_Data_num = 0;
					foreach ($score_ids as $i => $f) {
						$per_Data = $this->export_model->get_per_data($cs['id'],$value['id'],$f,$sss['majorid'],$sss['nowterm'],$sss['squadid']);
						$per_Data_num+=$per_Data;
					}
					if(in_array('show_score', $sss['set_field'])){
						$class = $this->export_model->get_class($cs['id'],$value['id'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$score_ids);
						$class = $class*0.1;
					}else{
						$class = 0;
					}
					if(in_array('score_check', $sss['set_field'])){
						$check = $this->export_model->get_student_rate($cs['id'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$value['id']);
						$check = $check*0.1;
					}else{
						$check = 0;
					}
					$data[$wq]['per'][] = $per_Data_num+$class+$check;
				}
			}
			$nt_data = array();
			foreach ($data as $xd => $jay) {
				if(in_array('student_id', $sss['set_field'])){
					$nt_data[$xd][] = array('val'=>$jay['id']);
				}
				if(in_array('student_nationality', $sss['set_field'])){
					$nt_data[$xd][] = array('val'=>$jay['nationality']);
				}
				if(in_array('student_enname', $sss['set_field'])){
					$nt_data[$xd][] = array('val'=>$jay['enname']);
				}
				if(in_array('student_name', $sss['set_field'])){
					$nt_data[$xd][] = array('val'=>$jay['name']);
				}
				for ($t=0; $t < count($jay['cua']); $t++) { 
					$nt_data[$xd][] = array('val'=>$data[$xd]['cua'][$t]);
				}
				for ($a=0; $a < count($jay['per']); $a++) { 
					if(!empty($data[$xd]['per'][$a])){
						$nt_data[$xd][] = array('val'=>$data[$xd]['per'][$a]);
					}
				}
			}
			//获取专业名称
			$major_name = $this->export_model->get_major_name($sss['majorid']);
			//获取班级名称
			$squad_name = $this->export_model->get_squ_name($sss['squadid']);
			//拼凑数据格式并返回数据
			$return_data=array();
			if(!empty($data)){
				for ($i=0; $i <count($data)+1 ; $i++) { 
					if($i==0){
						$return_data[0]=
						array(
							array('val'=>$major_name.'第'.$sss['nowterm'].'学期'.$squad_name.'语言生成统计表','colspan'=>$student_num+$type_num*2+count($course_data)+1,'align'=>'center'),
						);
						$return_data[$i+1]=$header_t;
						 $return_data[$i+2]=$header_h;
					}
				}
				$return_data=array_merge($return_data,$nt_data);
				return $return_data;
			}
	}
	/*成绩统计教师输入*/
	public function get_teacher_score(){
		$sss = $this->input->post();
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		$ty_num_o = 0;
		$ty_num_t = 0;
		$ty_num_r = 0;
		$type_ids = array();
		foreach ($filed_num as $uz => $dg) {
			if(!empty($dg['type'])){
				$ty_num_o++;
				if($dg['type']!= 'shh'){
					$type_ids[] = $dg['type'];
				}
			}elseif(!empty($dg['stype'])){
				$ty_num_t++;
			}else{
				$ty_num_r++;
			}
		}
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		//学生信息
		$student_data = $this->export_model->get_score_data($sss['majorid'],$sss['squadid']);
		if(empty($student_data)){
			ajaxReturn('','无数据可导出',0);
		}
		foreach ($student_data as $qw => $er) {
			foreach ($type_ids as $tp => $ci) {
				$student_data[$qw]['cua'][] = $this->export_model->get_exam($er['id'],$sss['courseid'],$ci,$sss['majorid'],$sss['nowterm'],$sss['squadid']);
			}
			if(in_array('show_score', $sss['set_field'])){
				$student_data[$qw]['cua'][] =$this->export_model->get_class($er['id'],$sss['courseid'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$type_ids);
			}
			if(in_array('score_check', $sss['set_field'])){
				$student_data[$qw]['cua'][] = $this->export_model->get_student_rate($er['id'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$sss['courseid']);
			}
			$per_Data_num = 0;
			foreach ($type_ids as $i => $f) {
				$per_Data = $this->export_model->get_per_data($er['id'],$sss['courseid'],$f,$sss['majorid'],$sss['nowterm'],$sss['squadid']);
				$per_Data_num+=$per_Data;
			}
			if(in_array('show_score', $sss['set_field'])){
				$class = $this->export_model->get_class($er['id'],$sss['courseid'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$type_ids);
				$class = $class*0.1;
			}else{
				$class = 0;
			}
			if(in_array('score_check', $sss['set_field'])){
				$check = $this->export_model->get_student_rate($er['id'],$sss['majorid'],$sss['nowterm'],$sss['squadid'],$sss['courseid']);
				$check = $check*0.1;
			}else{
				$check = 0;
			}
			$student_data[$qw]['per'][] = $per_Data_num+$class+$check;
		}

		$data = array();
		foreach ($student_data as $hg => $lv) {
			if(in_array('student_id', $sss['set_field'])){
					$data[$hg][] = array('val'=>$lv['id']);
				}
				if(in_array('student_nationality', $sss['set_field'])){
					$data[$hg][] = array('val'=>$this->run_unit_nationality['global_country_cn'][$lv['nationality']]);
				}
				if(in_array('student_enname', $sss['set_field'])){
					$data[$hg][] = array('val'=>$lv['enname']);
				}
				if(in_array('student_name', $sss['set_field'])){
					$data[$hg][] = array('val'=>$lv['name']);
				}
				for ($t=0; $t < count($lv['cua']); $t++) { 
					$data[$hg][] = array('val'=>$student_data[$hg]['cua'][$t]);
				}
				for ($a=0; $a < count($lv['per']); $a++) { 
					if(!empty($student_data[$hg]['per'][$a])){
						$data[$hg][] = array('val'=>$student_data[$hg]['per'][$a]);
					}
				}
		}
		//获取班级名称
		$squad_name = $this->export_model->get_squ_name($sss['squadid']);
		$course_name = $this->export_model->get_course_name($sss['courseid']);
		$tea_name = $this->export_model->get_teacher_score_name($sss['nowterm'],$sss['squadid'],$sss['courseid']);
		$arr_two = array();
		$arr_two[] = array('val'=>$squad_name,'colspan'=>$ty_num_r,'align'=>'center');
		$arr_two[] = array('val'=>'课程名：'.$course_name,'colspan'=>$ty_num_o,'align'=>'center');
		$arr_two[] = array('val'=>'任课老师：'.$tea_name,'colspan'=>$ty_num_t,'align'=>'center');
		$return_data = array();
		$return_data[0][]=array('val'=>'成绩登记表','colspan'=>$ty_num_t+$ty_num_o+$ty_num_r,'align'=>'center');
		$return_data[1]=$arr_two;
		$return_data[2]=$header;
		$return_data=array_merge($return_data,$data);
		return $return_data;
	}
	/*学生收费信息登记表*/
	public function get_budget(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		$student_data = $this->export_model->get_budget_data($sss['majorid'],$sss['squadid']);
		if(empty($student_data)){
			ajaxReturn('','无数据可导出',0);
		}
		foreach ($student_data as $k => $v) {
			$student_data[$k]['user_info'] = $this->export_model->get_user_info($v['userid']);
		}
		$country = $this->run_unit_nationality;
		$body_data = array();

		foreach ($student_data as $js => $nt) {
			$remark='';
			foreach ($filed_num as $key => $value) {
				if(strstr($value['field'], 'student_info_')){
					$cc=explode('student_info_', $value['field']);
				
					$val=$nt['user_info'][$cc[1]];
					if($cc[1]=='nationality'){
						$val=$country['global_country_cn'][$val];
					}
					if($cc[1]=='sex'){
						$val=$this->run_user_sex[$val];
					}
					$body_data[$js][] = array('val'=>$val);
				}elseif(strstr($value['field'],'sstudent_')){
					$cc=explode('sstudent_', $value['field']);
					$val=$nt[$cc[1]];
					if($cc[1]=='createtime'){
						$val=date('Y-m-d',$val);
					}
					$body_data[$js][] = array('val'=>$val);
				}else{
					for($i=1;$i<=16;$i++){
						if(strstr($value['field'],'budget_type_')){
							$cc=explode('budget_type_', $value['field']);
							if($cc[1]==$i){
								$val=$this->export_model->_get_type_fee($nt['userid'],$sss['term'],$cc[1],$sss['budget_type'],$sss['pay_type']);

								$body_data[$js][] = array('val'=>$val['money']);
								$remark.=$val['remark'];
							}
						}
					}
					if(strstr($value['field'],'budget_remark')){
						$body_data[$js][] = array('val'=>$remark);
					}
				}
				
			}
			
		}
		$return_data = array();
		
			for ($i=0; $i < count($student_data) ; $i++) { 
				if($i==0){
					$return_data[0]=$header;
				}
			}
		$return_data=array_merge($return_data,$body_data);
		return $return_data;
	}
	/*汇款明细*/
	public function get_credentials(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		$cre_data = $this->export_model->get_student_info($sss['update_opentime'],$sss['update_endtime']);
		if(empty($cre_data)){
			ajaxReturn('','无数据可导出',0);
		}
		foreach ($cre_data as $k => $v) {
			$student_info=$this->db->get_where('student_info','id = '.$v['userid'])->row_array();
					$zon=0;

			foreach ($filed_num as $key => $value) {
				if(strstr($value['field'], 'student_info_')){
					$cc=explode('student_info_', $value['field']);
				
					$val=$student_info[$cc[1]];
					if($cc[1]=='nationality'){
						$val=$this->run_unit_nationality['global_country_cn'][$val];
					}
					$body_data[$k][] = array('val'=>$val);
				}elseif(strstr($value['field'],'ccredentials_')){
					$cc=explode('ccredentials_', $value['field']);
					if($cc[1]=='money'){
						$body_data[$k][] = 'money';
					}
					if($cc[1]=='amount'){
						$body_data[$k][] = 'amount';
					}
					if($cc[1]=='total'){
						$body_data[$k][] = 'total';
					}						
				}else{
					foreach ($this->cre_type as $i => $vshh) {
						if(strstr($value['field'],'credentials_type_')){
							$cc=explode('credentials_type_', $value['field']);
							if($cc[1]==$i){
								$val=$this->export_model->_get_type_cre_fee($v['userid'],$i,$sss['update_opentime'],$sss['update_endtime']);
								$body_data[$k][] = array('val'=>$val);
								$zon+=$val;
							}
						}
					}
					// if(strstr($value['field'],'budget_remark')){
					// 	$body_data[$js][] = array('val'=>$remark);
					// }
				}
				
			}
			foreach ($body_data[$k] as $ks => $vs) {
				if($vs=='money'){
					$body_data[$k][$ks]= array('val'=>$zon);
				}
				if($vs=='amount'){
					$body_data[$k][$ks]= array('val'=>$zon);
				}
				if($vs=='total'){
					$body_data[$k][$ks]= array('val'=>$zon);
				}
			}
		}
		if(!empty($body_data)){
			$return_data = array();
			for ($i=0; $i < 1 ; $i++) { 
				if($i==0){
					$return_data[0]=$header;
				}
			}
		$return_data=array_merge($return_data,$body_data);
		return $return_data;
		}else{
			return '';
		}
	}

	/*住宿费退费清单*/
	public function get_accommodation_info(){
		$sss=$this->input->post();
		$export_join = $this->export_config_join;//获取链表信息
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		$acc_data = array();
		$data = $this->export_model->get_acc_data($sss['update_opentime'],$sss['update_endtime']);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		foreach ($data as $js => $nt) {
			$student_info = $this->export_model->get_acc_student($nt['userid']);
			$acc_data[$js]['student_info_id']=$js+1;
			$acc_data[$js]['student_info_chname']=$student_info['name'];
			$acc_data[$js]['student_info_enname']=$student_info['enname'];
			$acc_data[$js]['student_info_nationality']=$this->run_unit_nationality['global_country_cn'][$student_info['nationality']];
			$acc_data[$js]['major']=$student_info['majorid'];
			$acc_data[$js]['accommodation_roomname']=$this->export_model->get_room_name($nt['roomid']);
			$acc_data[$js]['accommodation_outtime']=date('Y-m-d',$nt['out_time']);
			$acc_data[$js]['accommodation_registeration_fee']=$nt['ture_acc_money']+$nt['ture_electric_money']+$nt['ture_acc_pledge_money']+$nt['ture_electric_pledge_money'];
			$acc_data[$js]['accommodation_reason']=!empty($nt['out_room_cause'])&&$nt['out_room_cause']==2?'校外住宿':(!empty($nt['out_room_cause'])&&$nt['out_room_cause']==3?'离校退房':'');
			$acc_data[$js]['accommodation_operator']=$this->export_model->get_admin_name($nt['adminid']);
			$acc_data[$js]['accommodation_remark']=$nt['remark'];
		}
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		//
		$body_data=array();
		if(!empty($acc_data)){
			foreach ($acc_data as $k => $v) {
				foreach ($v as $kn => $vn) {
					foreach ($filed_num as $kk => $vv) {
						if($kn==$vv['field']){
							$body_data[$k][$kk]=array('val'=>$vn);
						}
					}
					

				}
			}
		}

		$return_data = array();
			for ($i=0; $i < 1 ; $i++) { 
				if($i==0){
					$return_data[0]=$header;
				}
			}
		$return_data=array_merge($return_data,$body_data);
		return $return_data;
	}

	/*听松公寓电费核算*/
	public function get_room_electric_record(){}

	/*按学生导出收费信息*/
	public function get_student_budget(){
		$sss = $this->input->post();
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		$data = $this->export_model->get_student_budget($sss['s_field'],$sss['s_name']);
		if(empty($data)){
			ajaxReturn('','无数据可导出',0);
		}
		foreach ($data as $k => $v) {
			$zaixue_student=$this->export_model->get_createtime($v['id']);
			$data[$k]['acc_state']=$this->export_model->get_user_room_name($v['id']);
			$data[$k]['createtime'] = !empty($zaixue_student['createtime'])?$zaixue_student['createtime']:'';
			$data[$k]['studenttype'] = $zaixue_student['studenttype'];
		}
		
		$country = $this->run_unit_nationality;
		$body_data = array();

		foreach ($data as $js => $nt) {
			$remark='';
			foreach ($filed_num as $key => $value) {
				if(strstr($value['field'], 'student_info_')){
					$cc=explode('student_info_', $value['field']);
					$val=$nt[$cc[1]];
					if($cc[1]=='nationality'){
						$val=$country['global_country_cn'][$nt['nationality']];
					}
					if($cc[1]=='sex'){
						$val=$this->run_user_sex[$nt['sex']];
					}
					if($cc[1]=='birthday'){
						$val=date('Y-m-d',$nt['birthday']);
					}
					$body_data[$js][] = array('val'=>$val);
				}elseif(strstr($value['field'],'sstudent_')){
					$cc=explode('sstudent_', $value['field']);
					$val=$nt[$cc[1]];
					if($cc[1]=='id'){
						$val=$js+1;
					}
					if($cc[1]=='createtime'){
						if(!empty($val)){
							$val=date('Y-m-d',$val);
						}else{
							$val='';
						}
						
					}
					$body_data[$js][] = array('val'=>$val);
				}else{
					for($i=1;$i<=16;$i++){
						if(strstr($value['field'],'budget_type_')){
							$cc=explode('budget_type_', $value['field']);
							if($cc[1]==$i){
								$val=$this->export_model->_get_stu_type_fee($nt['id'],$cc[1],$sss['pay_type'],$sss['s_opentime'],$sss['s_endtime']);

								$body_data[$js][] = array('val'=>$val['money']);
								$remark.=$val['remark'];
							}
						}
					}
					if(strstr($value['field'],'budget_remark')){
						$body_data[$js][] = array('val'=>$remark);
					}
				}
				
			}
			
		}
		$return_data = array();
			for ($i=0; $i < count($data) ; $i++) { 
				if($i==0){
					$return_data[0]=$header;
				}
			}
		$return_data=array_merge($return_data,$body_data);
		return $return_data;
	}



/*按学生刷卡导出收费信息*/
	public function get_student_refresh(){
		ini_set('memory_limit', '512M');
		set_time_limit(0);
		$sss = $this->input->post();
		
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
		$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
	
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		//支付 方式
		if(empty($sss['pay_type'])){
			$pay_type = 5;
		}
		$pay_type = !empty($sss['pay_type'])?$sss['pay_type']:5;
		$s_opentime = !empty($sss['s_opentime'])?strtotime($sss['s_opentime']):0;
		$s_endtime = !empty($sss['s_endtime'])?strtotime($sss['s_endtime']):0;
		//如果选择了人 将会 走一种 可能 就是 先找人 再去 加条件   如果没有选人 则 搜索 所有的 人的
		// 先去 确定 人 再去 找数据 数据 都要 只是 显示不显示的 问题
		if(!empty($sss['s_field']) && $sss['s_name']){
			$user_data = $this->export_model->get_student_budget($sss['s_field'],$sss['s_name']);
			
			if(empty($user_data)){
				ajaxReturn('','无数据可导出',0);
			}
			
			foreach($user_data as $key => $val){
				$userids[] = $val['id'];
			}
			
			//导出 支付表的里 所有的数据 
			$where = 'budget_type = 1 AND paystate = 1 AND paytype = '.$pay_type;
			if(!empty($s_opentime)){
				$where.=' AND paytime > '.$s_opentime;
			}
			if(!empty($s_endtime)){
				$where.=' AND paytime < '.$s_endtime;
			}
			
			//查询数据
			
			$user = $this->db->group_by('userid')->order_by('paytime DESC')->get_where('budget',$where.' AND userid IN ('.implode(',',$userids).')')->result_array();
		}else{
			//导出 支付表的里 所有的数据 
			$where = 'budget_type = 1 AND paystate = 1 AND paytype = '.$pay_type;
			if(!empty($s_opentime)){
				$where.=' AND paytime > '.$s_opentime;
			}
			if(!empty($s_endtime)){
				$where.=' AND paytime < '.$s_endtime;
			}
		
			//查询数据
			
			$user = $this->db->group_by('userid')->order_by('paytime DESC')->get_where('budget',$where)->result_array();
				
		}
			
			
			
			if(!empty($user)){
				foreach($user as $key => $val){
					$userid[] = $val['userid'];
				}
			}else{
				ajaxReturn('','无数据可导出',0);
			}
		
			// 组合 学历 和 授课语言的类型
			// 1 本-汉 2 本-英 3 硕-汉 4 硕-英 5 长期语言进修生 6 短-汉  7 短-英
			$array_major_degree_language = array(
				1,2,3,4,5,6,7
			);
			//查找 学历 类型 
			$majorid_all = $this->db->select('id,majorid,nationality,enname')->get_where('student','id IN ('.implode(',',$userid).')')->result_array();
			$major_all = $this->db->select('id,language,degree')->get_where('major','id > 0')->result_array();
			foreach($major_all as $mk => $mv){
				if($mv['language'] == 1){
					if($mv['degree'] == 1){
						// 本-汉
						$major[$mv['id']] = 1;
					}
					
					if($mv['degree'] == 2){
						// 硕-汉
						$major[$mv['id']] = 3;
					}
				}else{
					if($mv['degree'] == 1){
						// 本-英
						$major[$mv['id']] = 2;
					}
					
					if($mv['degree'] == 2){
						// 硕-英
						$major[$mv['id']] = 4;
					}
				}
				
				if($mv['degree'] == 3){
					//长期语言进修生
					$major[$mv['id']] = 5;
				}
				
			}
			
			//每个学生的 专业 属性 组合  国籍 
			foreach($majorid_all as $sk => $sv){
				$student[$sv['id']] = !empty($major[$sv['majorid']])?$major[$sv['majorid']]:0;
				$country[$sv['id']] = !empty($sv['nationality']) && !empty($this->run_unit_nationality['global_country'][$sv['nationality']])?$this->run_unit_nationality['global_country'][$sv['nationality']]:'';
				$en_name[$sv['id']] = !empty($sv['enname'])?$sv['enname']:'';
			}
		
		//组合 各种 费用
		
		$where .= ' AND userid IN ('.implode(',',$userid).')';
		//学费
		$tuition = array();
		$tuition_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','tuition')->get_where('budget',$where.' AND type = 6')->result_array();
		if(!empty($tuition_all)){
			foreach($tuition_all as $tk => $tv){
				$tuition[$tv['userid']] = !empty($tv['tuition'])?$tv['tuition']:0;
			}
		}
		
		//申请费
		$applyfees = array();
		$apply_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','applyfees')->get_where('budget',$where.' AND type = 1')->result_array();
		if(!empty($apply_all)){
			foreach($apply_all as $tk => $tv){
				$applyfees[$tv['userid']] = !empty($tv['applyfees'])?$tv['applyfees']:0;
			}
		}
		//住宿费
		$accommodationfees = array();
		$accommodation_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','accommodationfees')->get_where('budget',$where.' AND type = 4')->result_array();
		
		if(!empty($accommodation_all)){
			foreach($apply_all as $tk => $tv){
				$accommodationfees[$tv['userid']] = !empty($tv['accommodationfees'])?$tv['accommodationfees']:0;
			}
		}
		// 押金
		$despitefees = array();
		$despite_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','despitefees')->get_where('budget',$where.' AND type = 5')->result_array();
		
		if(!empty($despite_all)){
			foreach($despite_all as $tk => $tv){
				$despitefees[$tv['userid']] = !empty($tv['despitefees'])?$tv['despitefees']:0;
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
	
		// 书费
		$booksfees = array();
		$booksfees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','booksfees')->get_where('budget',$where.' AND type = 8')->result_array();
		
		if(!empty($booksfees_all)){
			foreach($booksfees_all as $tk => $tv){
				$booksfees[$tv['userid']] = !empty($tv['booksfees'])?$tv['booksfees']:0;
			}
		}
		
		// 床品费
		$bedfees = array();
		$bedfees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','bedfees')->get_where('budget',$where.' AND type = 13')->result_array();
		
		if(!empty($bedfees_all)){
			foreach($bedfees_all as $tk => $tv){
				$bedfees[$tv['userid']] = !empty($tv['bedfees'])?$tv['bedfees']:0;
			}
		}
		
		// 电费
		$electricfees = array();
		$electricfees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','electricfees')->get_where('budget',$where.' AND type = 7')->result_array();
		
		if(!empty($electricfees_all)){
			foreach($electricfees_all as $tk => $tv){
				$electricfees[$tv['userid']] = !empty($tv['electricfees'])?$tv['electricfees']:0;
			}
		}
		
		$arr[] = array(
			array('val'=>'序号','align'=>'center','rowspan'=>3),
			array('val'=>'日期','align'=>'center','rowspan'=>3),
			array('val'=>'刷卡姓名','width'=>30,'align'=>'center','rowspan'=>3),
			array('val'=>'国籍','width'=>30,'align'=>'center','rowspan'=>3),
			array('val'=>'刷价金额','align'=>'center','rowspan'=>3),
			array('val'=>'学校管理费','align'=>'center','colspan' => 9),
			array('val'=>'学生奖助学','width'=>15,'align'=>'center'),
			array('val'=>'留学生业务费','align'=>'center','colspan' => 4),
			array('val'=>'二级学院留学生经费','width'=>30,'align'=>'center'),
			array('val'=>'其他','align'=>'center','rowspan'=>3),
			array('val'=>'备注','align'=>'center','rowspan'=>3),
			
		);
		
		$arr[] = array(
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>'申请费','align'=>'center','rowspan' => 2),
			array('val'=>'本科生学费','align'=>'center','colspan' => 2),
			array('val'=>'研究生学费','align'=>'center','colspan' => 2),
			array('val'=>'长期语言进修生','width'=>15,'align'=>'center'),
			array('val'=>'短期培训团学费','align'=>'center','colspan' => 2),
			array('val'=>'住宿费','align'=>'center','rowspan' => 2),
			array('val'=>'押金','align'=>'center','rowspan' => 2),
			array('val'=>'保险费','align'=>'center','rowspan' => 2),
			array('val'=>'书费','align'=>'center','rowspan' => 2),
			array('val'=>'床品费','align'=>'center','rowspan' => 2),
			array('val'=>'电费','align'=>'center','rowspan' => 2),
			array('val'=>'外国留学生短期团组经费','width'=>30,'align'=>'center','rowspan' => 2),
			array('val'=>null),
			array('val'=>null),
			
		);
		
		$arr[] = array(
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'英语','align'=>'center'),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'英语','align'=>'center'),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'汉语','align'=>'center'),
			array('val'=>'英语','align'=>'center'),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			array('val'=>null),
			
		);
		foreach($user as $k => $v){
				$money = (!empty($tuition[$v['userid']])?$tuition[$v['userid']]:0) + (!empty($accommodationfees[$v['userid']])?$accommodationfees[$v['userid']]:0) + (!empty($despitefees[$v['userid']])?$despitefees[$v['userid']]:0) + (!empty($infrancefees[$v['userid']])?$infrancefees[$v['userid']]:0) + (!empty($booksfees[$v['userid']])?$booksfees[$v['userid']]:0) + (!empty($bedfees[$v['userid']])?$bedfees[$v['userid']]:0) + (!empty($electricfees[$v['userid']])?$electricfees[$v['userid']]:0);
				$arr[] = array(
					array('val'=>$v['id']),
					array('val'=>!empty($v['paytime'])?date('Y/m/d',$v['paytime']):null),
					array('val'=>!empty($en_name[$v['userid']])?$en_name[$v['userid']]:null),
					array('val'=>!empty($country[$v['userid']])?$country[$v['userid']]:null),
					array('val'=>!empty($money)?$money:null),
					array('val'=>!empty($applyfees[$v['userid']])?$applyfees[$v['userid']]:null),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 1 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 2 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 3 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 4 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 5 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 6 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($student[$v['userid']]) && $student[$v['userid']] == 7 && !empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($accommodationfees[$v['userid']])?$accommodationfees[$v['userid']]:null),
					array('val'=>!empty($despitefees[$v['userid']])?$despitefees[$v['userid']]:null),
					array('val'=>!empty($infrancefees[$v['userid']])?$infrancefees[$v['userid']]:null),
					array('val'=>!empty($booksfees[$v['userid']])?$booksfees[$v['userid']]:null),
					array('val'=>!empty($bedfees[$v['userid']])?$bedfees[$v['userid']]:null),
					array('val'=>!empty($electricfees[$v['userid']])?$electricfees[$v['userid']]:null),
					array('val'=>null),
					array('val'=>null),
					array('val'=>null),
					
				);
		}
		
		
		
		return $arr;
	}




/*	费用收取表格*/
	public function get_student_fees_export(){
		ini_set('memory_limit', '512M');
		set_time_limit(0);
		$sss = $this->input->post();
		
		$this->run_form_id = $this->input->get_post('table_id');//数据表名
	/*	$this->set_field = $this->input->get_post('set_field');//表字段
		$field_lists = CF('field_' . $this->run_form_id, '', CACHE_PATH . 'export/');
		
		//删除没有选中的头部信息
		foreach ($field_lists as $m => $n) {
			$filed_num[] = $n;
		}
		
		foreach ($filed_num as $key => $value) {
			if(in_array($value['field'], $sss['set_field']) == false){
			 	unset($filed_num[$key]);
			 }
		}
	
		//拼凑表头
		$header = array();
		foreach ($filed_num as $key => $value) {
			$header[] = array('val'=>$value['name']);
		}
		*/
		//支付 方式
		
		$pay_type = !empty($sss['pay_type'])?$sss['pay_type']:5;
		$s_opentime = !empty($sss['s_opentime'])?strtotime($sss['s_opentime']):0;
		$s_endtime = !empty($sss['s_endtime'])?strtotime($sss['s_endtime']):0;
		//导出 支付表的里 所有的数据 
			$where = 'budget_type = 1 AND paystate = 1 AND paytype = '.$pay_type;
			if(!empty($s_opentime)){
				$where.=' AND paytime > '.$s_opentime;
			}
			if(!empty($s_endtime)){
				$where.=' AND paytime < '.$s_endtime;
			}
		
			//查询数据
			
			$user = $this->db->group_by('userid')->order_by('paytime DESC')->get_where('budget',$where)->result_array();
			
			
			$m_j_id = '';
			//判断类型
			if(!empty($sss['studenttype'])){
				if($sss['studenttype'] == 1){
					//本科学历 汉语 授课
					$m_j = $this->db->select('id')->get_where('major','degree = 1 AND language = 1')->result_array();
					
					if(!empty($m_j)){
						foreach($m_j as $k => $v){
							$m_j_id[] = $v['id'];
						}
					}
					
				}else if($sss['studenttype'] == 2){
					//本科学历 汉语 授课
					$m_j = $this->db->select('id')->get_where('major','degree = 1 AND language = 2')->result_array();
					
					if(!empty($m_j)){
						foreach($m_j as $k => $v){
							$m_j_id[] = $v['id'];
						}
					}
				}else if($sss['studenttype'] == 3){
					//本科学历 汉语 授课
					$m_j = $this->db->select('id')->get_where('major','degree = 2 AND language = 1')->result_array();
					if(!empty($m_j)){
						foreach($m_j as $k => $v){
							$m_j_id[] = $v['id'];
						}
					}
				}else if($sss['studenttype'] == 4){
					//本科学历 汉语 授课
					$m_j = $this->db->select('id')->get_where('major','degree = 2 AND language = 2')->result_array();
					if(!empty($m_j)){
						foreach($m_j as $k => $v){
							$m_j_id[] = $v['id'];
						}
					}
				}else if($sss['studenttype'] == 5){
					//本科学历 汉语 授课
					$m_j = $this->db->select('id')->get_where('major','degree = 3 AND language = 1')->result_array();
					if(!empty($m_j)){
						foreach($m_j as $k => $v){
							$m_j_id[] = $v['id'];
						}
					}
				}else if($sss['studenttype'] == 6){
					//本科学历 汉语 授课
					$m_j = $this->db->select('id')->get_where('major','degree = 5 AND language = 1')->result_array();
					if(!empty($m_j)){
						foreach($m_j as $k => $v){
							$m_j_id[] = $v['id'];
						}
					}
				}
				
				if(empty($m_j_id)){
					ajaxReturn('','无数据可导出',0);
				}
			}
			
			if(!empty($user)){
				foreach($user as $key => $val){
					$userid[] = $val['userid'];
				}
			}else{
				ajaxReturn('','无数据可导出',0);
			}
			
			
		
			// 组合 学历 和 授课语言的类型
			// 1 本-汉 2 本-英 3 硕-汉 4 硕-英 5 长期语言进修生 6 短-汉  7 短-英
			$array_major_degree_language = array(
				1,2,3,4,5,6,7
			);
		
			//查找 学历 类型 
			if(!empty($m_j_id)){
				$majorid_all = $this->db->select('id,majorid,nationality,enname,userid')->get_where('student','userid IN ('.implode(',',$userid).') AND majorid IN ('.implode(',',$m_j_id).')')->result_array();
				
				$major_all = $this->db->select('id,language,degree')->get_where('major','id > 0 AND id IN ('.implode(',',$m_j_id).')')->result_array();
			}else{
				$majorid_all = $this->db->select('id,majorid,nationality,enname,userid')->get_where('student','userid IN ('.implode(',',$userid).')')->result_array();
				$major_all = $this->db->select('id,language,degree')->get_where('major','id > 0')->result_array();
			}
			
			if(empty($majorid_all)){
				ajaxReturn('','无数据可导出',0);
			}
			foreach($major_all as $mk => $mv){
				if($mv['language'] == 1){
					if($mv['degree'] == 1){
						// 本-汉
						$major[$mv['id']] = '本科学历生——汉语授课';
					}
					
					if($mv['degree'] == 2){
						// 硕-汉
						$major[$mv['id']] = '硕士学历生--汉语授课';
					}
					if($mv['degree'] == 3 || $mv['degree'] == 6){
						// 长期语言进修生 汉语 授课
						$major[$mv['id']] = '长期语言进修生--汉语授课';
					}
					if($mv['degree'] == 5){
						// 短期培训团 
						$major[$mv['id']] = '短期培训团--汉语、英语授课';
					}
				}else{
					if($mv['degree'] == 1){
						// 本-英
						$major[$mv['id']] = '本科学历生——英语授课';
					}
					
					if($mv['degree'] == 2){
						// 硕-英
						$major[$mv['id']] = '硕士学历生--英语授课';
					}
				}
				
				
				
			}
			
			//每个学生的 专业 属性 组合  国籍 
			foreach($majorid_all as $sk => $sv){
				$student[$sv['id']] = !empty($major[$sv['majorid']])?$major[$sv['majorid']]:0;
				$country[$sv['id']] = !empty($sv['nationality']) && !empty($this->run_unit_nationality['global_country'][$sv['nationality']])?$this->run_unit_nationality['global_country'][$sv['nationality']]:'';
				$en_name[$sv['id']] = !empty($sv['enname'])?$sv['enname']:'';
				$u_id[] = $sv['id'];
				$student_user[$sv['userid']] = $sv['id'];
			}
			
			if(!empty($user) && !empty($u_id)){
				foreach($user as $kk => $vv){
					if(!in_array($student_user[$vv['userid']],$u_id)){
						unset($user[$kks]);
					}
				}
			}
			
			foreach($user as $keys => $vals){
					$userids[] = $vals['userid'];
				}
		
		//组合 各种 费用
		
		$where .= ' AND userid IN ('.implode(',',$userids).')';
		//学费
		$tuition = array();
		$tuition_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','tuition')->get_where('budget',$where.' AND type = 6')->result_array();
		if(!empty($tuition_all)){
			foreach($tuition_all as $tk => $tv){
				$tuition[$tv['userid']] = !empty($tv['tuition'])?$tv['tuition']:0;
			}
		}
		
		//申请费
		$applyfees = array();
		$apply_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','applyfees')->get_where('budget',$where.' AND type = 1')->result_array();
		if(!empty($apply_all)){
			foreach($apply_all as $tk => $tv){
				$applyfees[$tv['userid']] = !empty($tv['applyfees'])?$tv['applyfees']:0;
			}
		}
		/*
		//住宿费
		$accommodationfees = array();
		$accommodation_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','accommodationfees')->get_where('budget',$where.' AND type = 4')->result_array();
		
		if(!empty($accommodation_all)){
			foreach($apply_all as $tk => $tv){
				$accommodationfees[$tv['userid']] = !empty($tv['accommodationfees'])?$tv['accommodationfees']:0;
			}
		}
		// 押金
		$despitefees = array();
		$despite_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','despitefees')->get_where('budget',$where.' AND type = 5')->result_array();
		
		if(!empty($despite_all)){
			foreach($despite_all as $tk => $tv){
				$despitefees[$tv['userid']] = !empty($tv['despitefees'])?$tv['despitefees']:0;
			}
		}

		// 书费
		$booksfees = array();
		$booksfees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','booksfees')->get_where('budget',$where.' AND type = 8')->result_array();
		
		if(!empty($booksfees_all)){
			foreach($booksfees_all as $tk => $tv){
				$booksfees[$tv['userid']] = !empty($tv['booksfees'])?$tv['booksfees']:0;
			}
		}
		
		// 床品费
		$bedfees = array();
		$bedfees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','bedfees')->get_where('budget',$where.' AND type = 13')->result_array();
		
		if(!empty($bedfees_all)){
			foreach($bedfees_all as $tk => $tv){
				$bedfees[$tv['userid']] = !empty($tv['bedfees'])?$tv['bedfees']:0;
			}
		}
		
		// 电费
		$electricfees = array();
		$electricfees_all = $this->db->select('id,userid,paid_in')->group_by('userid')->select_sum('paid_in','electricfees')->get_where('budget',$where.' AND type = 7')->result_array();
		
		if(!empty($electricfees_all)){
			foreach($electricfees_all as $tk => $tv){
				$electricfees[$tv['userid']] = !empty($tv['electricfees'])?$tv['electricfees']:0;
			}
		}
		*/
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
			array('val'=>'姓名','width'=>30,'align'=>'center'),
			array('val'=>'国籍','width'=>30,'align'=>'center'),
			array('val'=>'申请费','align'=>'center'),
			array('val'=>'学费','align'=>'center'),
			array('val'=>'保险费','width'=>15,'align'=>'center'),
			array('val'=>'学生类别','width'=>30,'align'=>'center'),
			
		);
		
		
		foreach($user as $k => $v){
				
				$arr[] = array(
					array('val'=>$v['id']),
					array('val'=>!empty($v['paytime'])?date('Y/m/d',$v['paytime']):null),
					array('val'=>!empty($en_name[$student_user[$v['userid']]])?$en_name[$student_user[$v['userid']]]:null),
					array('val'=>!empty($country[$student_user[$v['userid']]])?$country[$student_user[$v['userid']]]:null),
				
					array('val'=>!empty($applyfees[$v['userid']])?$applyfees[$v['userid']]:null),
					array('val'=>!empty($tuition[$v['userid']])?$tuition[$v['userid']]:null,'align'=>'center'),
					array('val'=>!empty($infrancefees[$v['userid']])?$infrancefees[$v['userid']]:null),
					array('val'=>!empty($student[$student_user[$v['userid']]])?$student[$student_user[$v['userid']]]:'')
					
					
				);
		}
		
		
		
		return $arr;
	}

	

	function  _convert_data($data){
		$nationality = $this->run_unit_nationality;
		foreach ($data as $k => $v) {
			if(!empty($data[$k]['sex'])){
				$data[$k]['sex'] = isset($this->run_user_sex[$v['sex']])?$this->run_user_sex[$v['sex']]:'--';
			}
			if(!empty($data[$k]['birthday'])){
				$data[$k]['birthday'] = date('Y-m-d',$v['birthday']);
			}
			if(!empty($data[$k]['effect_time'])){
				$data[$k]['effect_time'] = date('Y-m-d',$v['effect_time']);
			}
			if(!empty($data[$k]['deadline'])){
				$data[$k]['deadline'] = isset($this->run_unit_deadline[$v['deadline']])?$this->run_unit_deadline[$v['deadline']]:'--';
			}
			if(!empty($data[$k]['nationality'])){
				$data[$k]['nationality'] = isset($nationality['global_country_cn'][$v['nationality']])?$nationality['global_country_cn'][$v['nationality']]:'--';
			}
		}
		return $data;
	}


	private function _get_data_field()
	{
		$mdata = $this->export_model->get_majorinfo ();
		$field_lists = array();
		foreach ($this->export_config_main as $config) {
			$field_lists[] = array(
				'field' => $config['table'],
				'table' => $config['name'],
				'fix' => $config['fix'],
				'config' => CF('field_' . $config['table'], '', CACHE_PATH . 'export/'),
			);
		}
		$scoretype=$this->db->get_where('set_score','state = 1')->result_array();
		//学历信息
		$degree_info=$this->db->get_where('degree_info','state = 1')->result();
		
		$this->load->vars(array(
			'field_lists' => $field_lists,
			'mdata' => $mdata,
			'scoretype'=>$scoretype,
			'degree_info'=>$degree_info
		));
	}
	/**
	 * 获取该专业学期
	 */
	public function get_nowterm($mid,$ta_info) {
		$nowterm = $this->export_model->get_major_nowterm ( $mid );
		$course = $this->export_model->get_course ( $mid );
		$course_data = CF('field_' . 'student', '', CACHE_PATH . 'export/');
		$data ['nowterm'] = $nowterm;
		if (! empty ( $course )) {
			$data['ta_info'] = $ta_info;
			$data ['course'] = $course;
			$data['course_data'] = $course_data;
			ajaxReturn ( $data, '', 1 );
		} else {
			ajaxReturn ( $data, '该专业还没有课程', 2 );
		}
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad() {
		$mid = $this->input->get ( 'mid' );
		$term = $this->input->get ( 'term' );
		$squad = $this->export_model->get_squadinfo ( $mid, $term );
		if (empty ( $squad )) {
			ajaxReturn ( '', '该学期下还没有班级', 0 );
		}
		// var_dump($squad);exit;
		if (! empty ( $squad )) {
			ajaxReturn ( $squad, '', 1 );
		}
	}
	
	/**
	 * 获取学生
	 */
	function get_student() {
		$data = $this->input->post ();
		if ($data ['majorid'] != '0' && $data ['courseid'] != '0' && $data ['squadid'] != '0' && $data ['nowterm'] != '0') {
			
			$scoreinfo = $this->export_model->get_stu_score ();
			if (! empty ( $data ['key'] ) && ! empty ( $data ['value'] )) {
				$sdata = $this->export_model->get_student_one ( $data ['squadid'], $data ['key'], $data ['value'] );
				if (empty ( $sdata )) {
					ajaxReturn ( '', '没有所查找的学生', 0 );
				}
			} else {
				$sdata = $this->export_model->get_studentinfo ( $data ['squadid'] );
			}
            //查询代课老师的联系方式
            $info=$this->export_model->get_laoshixinxi($data);
            $data['info']=$info;
			$data ['stu'] = $sdata;
			$data ['scoreinfo'] = $scoreinfo;
			if (! empty ( $sdata )) {
				ajaxReturn ( $data, '', 1 );
			}
		} elseif (! empty ( $data ['key'] ) && ! empty ( $data ['value'])&& $data ['nowterm'] != '0' && !empty($data['scoretype']) && $data['courseid']!='0') {
			$sdata=$this->export_model->get_student_one($data);
			if(!empty($sdata)){
				$scoreinfo = $this->export_model->get_stu_score ();
				$data ['stu'] = $sdata;
				$data ['scoreinfo'] = $scoreinfo;
				ajaxReturn ( $data, '', 1 );
			}else{
				ajaxReturn ( '', '没有所查找的学生', 0 );
			}
		} else {
			ajaxReturn ( '', '学期班级课程不能为空', 0 );
		}
	}



	private function _get_export_filename()
	{
		$filename = isset($this->export_config_main[$this->run_form_id]) ? $this->export_config_main[$this->run_form_id]['name'] : '数据导出';
		return $filename . '-' . date('Y-m-d');
	}
	//获取专业
	function get_major(){
		$degreeid=trim(intval($this->input->get('degreeid')));
		if(!empty($degreeid)){
			$data=$this->db->get_where('major','degree = '.$degreeid)->result_array();
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}else{
				ajaxReturn('','',2);
			}
		}
		ajaxReturn('','',0);

	}

	//获取课程名称
	function get_teacher_score_course(){
		$squadid = trim(intval($this->input->get('squadid')));
		if(!empty($squadid)){
			$data_course = $this->export_model->get_nt_cou($squadid);
			$course_arr = array();
			foreach ($data_course as $k => $v) {
				$course_arr[] = $this->export_model->get_score_coursename($v['courseid']);
			}
			$data = array();
			foreach ($course_arr as $js => $nt) {
				foreach ($nt as $kk => $vv) {
					$data[] = array('id'=>$vv['id'],'name'=>$vv['name']);
				}
			}
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}else{
				ajaxReturn('','',2);
			}
		}
		ajaxReturn('','',0);
	}
	
	//导出 按比例分配的信息
	function export_fees_type(){
		$type = intval(trim($this->input->get('type')));
		if($type){
			$this->_view('export_fees_type',array('type' => $type));
		}
	}
	
	//执行导出
	function export_fees_type_do(){
		$type = intval(trim($this->input->post('type')));
		$stime = trim($this->input->post('stime'));
		$etime = trim($this->input->post('etime'));
		if($type){
			if($type == 1){
				$where = 'id > 0 AND type = 6 AND budget_type = 1 AND paystate = 1';
			}else{
				$where = 'id > 0 AND type = 1 AND budget_type =1 AND paystate = 1';
			}
			
			if(!empty($stime)){
				$where .= ' AND paytime > '.strtotime($stime);
			}
			
			if(!empty($etime)){
				$where .= ' AND paytime < '.strtotime($etime);
			}
			
			$result = $this->db->select('*')->group_by('userid')->select_sum('paid_in')->select_sum('true_returned')->get_where('budget',$where)->result_array();
			
			
			if(!empty($result)){
				foreach($result as $k => $v){
					$result_user[$v['userid']] = $v['paid_in'] - $v['true_returned'];
					$result_userid[] = $v['userid'];
				}
				
				//查找每个学生的专业
				$major_all = $this->db->select('majorid,id,userid')->get_where('student','id > 0 AND userid IN ('.implode(',',$result_userid).')')->result_array();
				foreach($major_all as $key => $val){
					$major_id[] = $val['majorid'];
					$major_user[$val['id']] = $val['majorid'];
					$student_user[$val['userid']] = $val['id'];
					
				}
				//每个专业的信息
				$major_info_all = $this->db->get_where('major','id > 0 AND id IN ('.implode(',',$major_id).')')->result_array();
				
				foreach($major_info_all as $kk => $vv){
					if($vv['degree'] == 1){
						if($vv['language'] == 1){
							//   本科 汉语 
							$major_info_id[$vv['id']] = 1;
						}else if($vv['language'] == 2){
							//   本科 英语 
							$major_info_id[$vv['id']] = 2;
						}
					}else if($vv['degree'] == 2){
							if($vv['language'] == 1){
							//   硕科 汉语 
							$major_info_id[$vv['id']] = 3;
						}else if($vv['language'] == 2){
							//   本科 英语 
							$major_info_id[$vv['id']] = 4;
						}
					}else if($vv['degree'] == 3 || $vv['degree'] == 6){
						//   长期 汉语 
							$major_info_id[$vv['id']] = 5;
					}else if($vv['degree'] == 5){
						//   短期培训团--汉语、英语授课 
							$major_info_id[$vv['id']] = 6;
					}
				}
				
				
				//把学生 转换成 类型
				foreach($major_user as $kkk => $vvv){
					$major_user_temp[$kkk] = $major_info_id[$vvv];
				}
				
				$temp = array(1,2,3,4,5,6);
				
				$m1 = $m2 = $m3 = $m4 = $m5 = $m6 = 0;
				
								
					foreach($result_user as $k4 => $v4){
						if(!empty($major_user_temp[$student_user[$k4]]) && $major_user_temp[$student_user[$k4]] == 1){
							$m1 += $v4;
						}else if(!empty($major_user_temp[$student_user[$k4]]) && $major_user_temp[$student_user[$k4]] == 2){
							$m2 += $v4;
						}else if(!empty($major_user_temp[$student_user[$k4]]) && $major_user_temp[$student_user[$k4]] == 3){
							$m3 += $v4;
						}else if(!empty($major_user_temp[$student_user[$k4]]) && $major_user_temp[$student_user[$k4]] == 4){
							$m4 += $v4;
						}else if(!empty($major_user_temp[$student_user[$k4]]) && $major_user_temp[$student_user[$k4]] == 5){
							$m5 += $v4;
						}else if(!empty($major_user_temp[$student_user[$k4]]) && $major_user_temp[$student_user[$k4]] == 6){
							$m6 += $v4;
						}
					}
					
				

				
				
				
				$html = $this->_view('export_fees_type_table',
				array('type' => $type,
					'm1' => $m1,
					'm2' => $m2,
					'm3' => $m3,
					'm4' => $m4,
					'm5' => $m5,
					'm6' => $m6,
					
					),
				true);
				ajaxReturn($html,'',1);
				
			}
			
		}else{
			ajaxReturn('','',0);
		}
	}

}