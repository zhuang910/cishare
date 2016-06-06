<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Checking extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/checking/';
		$this->load->model($this->view.'checking_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
	
	
		$mdata=$this->db->order_by('language DESC')->get_where('major','id > 0')->result();
		
        // 获取学历
        $mdata = $this->_get_major_by_degree($mdata);
		
		$scoretype=CF('scoretype','',CONFIG_PATH);
		$this->_view ('checking_index',array(
			'mdata'=>$mdata,
			'scoretype'=>$scoretype,
			));
	}
	private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
           
			$degree = $this->db->order_by('orderby DESC')->get('degree_info','id > 0')->result_array();
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
	/**
	 * 获取该专业学期
	 */
	public function get_nowterm($mid){
		$nowterm=$this->checking_model->get_major_nowterm($mid);
		$course=$this->checking_model->get_course($mid);
		$num=$this->checking_model->get_hebdomad_num($mid);
		$data['nowterm']=$nowterm;
		$data['course']=$course;
		$data['num']=$num;
		if(!empty($data['nowterm'])&&!empty($data['course'])){
			ajaxReturn ( $data, '', 1 );
		}else{
			ajaxReturn ( '', '', 0 );
		}
		
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		$squad=$this->checking_model->get_squadinfo($mid,$term);
		if(!empty($squad)){
			ajaxReturn ( $squad, '', 1 );
		}else{
			ajaxreturn('','该学期下还没有班级',0);
		}
	}

	/**
	 * 获取该学期的教室
	 */
	function get_class_room(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		$classroom=$this->checking_model->get_class_room_info($mid,$term);
		if(!empty($classroom)){
			ajaxReturn ( $classroom, '', 1 );
		}else{
			ajaxreturn('','该学期下还没有老师负责的教室',0);
		}
	}
	
	/**
	 * 获取该学期的在该教室所上课的老师
	 */
	function get_class_room_teacher(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		$croomid=$this->input->get('classroomid');
		$teacher=$this->checking_model->get_class_room_teacher_info($mid,$term,$croomid);
		if(!empty($teacher)){
			ajaxReturn ( $teacher, '', 1 );
		}else{
			ajaxreturn('','该学期下还没有该教室负责的老师',0);
		}
	}
	function get_student(){
		$data=$this->input->post();
		
		$hour=CF('hour','',CONFIG_PATH);
		
		
		$sdata=$this->checking_model->get_studentinfo($data['squadid']);
		if(empty($sdata)){
			ajaxreturn('','该班级还没有学生',2);
		}
		foreach ($sdata as $k => $v) {
			$sdata[$k]['kaoqin']=$this->checking_model->kaoqin($v['id']);
			$sdata[$k]['chuqin']=$this->checking_model->chuqin($v['id'],$data['majorid'],$hour);
		}
		$data['stu']=$sdata;
		if(!empty($sdata)){
			ajaxReturn($data,'',1);
		}
	}
	/**
	 *
	 *获取更多学生的详细考勤信息
	 **/
	function student_more_checking(){
		$s=intval($this->input->get('s'));
		$studentid=intval($this->input->get('studentid'));
		if(!empty($s)){
			$sname=$this->checking_model->get_sname($studentid);
			$checking_info=$this->checking_model->get_student_checkinginfo($studentid);
			$html = $this->_view ( 'student_more_checking', array(
				'sname'=>$sname,
				'checking_info'=>$checking_info
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}
	//设置考勤
	function set_checking(){
		$majorid=intval($this->input->get('majorid'));
		$studentid=intval($this->input->get('studentid'));
		$squadid=intval($this->input->get('squadid'));
		$s=intval($this->input->get('s'));
			if(!empty($s)){
				$out=$this->checking_model->get_out($majorid,$squadid);
				$classtime=$this->checking_model->get_classtime($majorid,$squadid);
				$hour=CF('hour','',CONFIG_PATH);
				$ckinfo=$this->checking_model->get_ck($studentid,time());
				$sname=$this->checking_model->get_sname($studentid);
				$s=date('N',time());
				$stime=time()-24*3600*($s-1);

				$etime=$stime+24*3600*6;
			$html = $this->_view ( 'set_checking', array(
				'hour'=>$hour,
				'out'=>$out,
				'classtime'=>$classtime,
				'ckinfo'=>$ckinfo,
				'sname'=>$sname,
				'majorid'=>$majorid,
				'studentid'=>$studentid,
				'squadid'=>$squadid,
				'stime'=>$stime,
				'etime'=>$etime,
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}
	function nexttime(){
		$stime=$this->input->get('starttime');
		$otime=$this->input->get('overtime');
		$classtime=$this->input->get('classtime');
		$studentid=$this->input->get('studentid');

		$ckdata=$this->checking_model->get_next_ck($studentid,strtotime($otime));
		//var_dump(strtotime($otime)+24*3600*7);exit;
		$starttime=strtotime($stime)+24*3600*7;
		$overtime=strtotime($stime)+24*3600*13;
		$date['start']=date('Y-m-d',$starttime);
		$date['over']=date('Y-m-d',$overtime);
		$date['ckdata']=$ckdata;
		$date['mo']=date('m-d',$starttime);
		$date['tu']=date('m-d',$starttime+24*3600);
		$date['we']=date('m-d',$starttime+24*3600*2);
		$date['th']=date('m-d',$starttime+24*3600*3);
		$date['fr']=date('m-d',$starttime+24*3600*4);
		$date['sa']=date('m-d',$starttime+24*3600*5);
		$date['su']=date('m-d',$starttime+24*3600*6);
		ajaxReturn($date,'',1);
		
	}
		function uptime(){
		$stime=$this->input->get('starttime');

		$classtime=$this->input->get('classtime');
		if(strtotime($stime)<=$classtime){
			ajaxReturn('','已经是开班的时间了',0);
		}
		$otime=$this->input->get('overtime');
		$studentid=$this->input->get('studentid');
		$ckdata=$this->checking_model->get_up_ck($studentid,strtotime($stime));

		$starttime=strtotime($stime)-24*3600*7;
		$overtime=strtotime($stime)-24*3600;
		$date['start']=date('Y-m-d',$starttime);
		$date['over']=date('Y-m-d',$overtime);
		$date['ckdata']=$ckdata;
		$date['mo']=date('m-d',$starttime);
		$date['tu']=date('m-d',$starttime+24*3600);
		$date['we']=date('m-d',$starttime+24*3600*2);
		$date['th']=date('m-d',$starttime+24*3600*3);
		$date['fr']=date('m-d',$starttime+24*3600*4);
		$date['sa']=date('m-d',$starttime+24*3600*5);
		$date['su']=date('m-d',$starttime+24*3600*6);
		ajaxReturn($date,'',1);
		
	}
	function changedate(){
		$stime=$this->input->get('starttime');
		$studentid=$this->input->get('studentid');
		$classtime=$this->input->get('classtime');
		if(strtotime($stime)<=$classtime){
			$ckdata=$this->checking_model->get_up_ck($studentid,$classtime);
			

			$date['start']=date('Y-m-d',$classtime);
			$date['over']=date('Y-m-d',$classtime*7*24*3600);
			$date['ckdata']=$ckdata;
			ajaxReturn($date,'已经是开班的时间了',0);
		}
		$ckdata=$this->checking_model->get_up_ck($studentid,strtotime($stime));
		$k=date('w',strtotime($stime));
		if($k==0){
			$k=6;
		}else{
			$k=$k-1;
		}
		$stime=strtotime($stime)-$k*3600*24;
		$etime=$stime+6*24*3600;
		$date['start']=date('Y-m-d',$stime);
		$date['over']=date('Y-m-d',$etime);
		$date['ckdata']=$ckdata;
		$date['mo']=date('m-d',$stime);
		$date['tu']=date('m-d',$stime+24*3600);
		$date['we']=date('m-d',$stime+24*3600*2);
		$date['th']=date('m-d',$stime+24*3600*3);
		$date['fr']=date('m-d',$stime+24*3600*4);
		$date['sa']=date('m-d',$stime+24*3600*5);
		$date['su']=date('m-d',$stime+24*3600*6);
		ajaxReturn($date,'',1);

	}
	function insert(){
		$time=$this->input->get('time');
		$data=$this->input->post();
		//var_dump($data);exit;
		$data['date']=strtotime($time)+24*3600*($data['week']-1);
		if (! empty ( $data )) {
			
			$id = $this->checking_model->save ( null, $data );
			if ($id) {
				//缺勤发邮件
				if(!empty($data['type']) && $data['type'] == 1){

					$this->qin_email($data);
				}
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
		//var_dump($data['date']);
	}
	
	/**
	 * 缺勤 发邮件
	 */
	function qin_email($data = null){
		if($data != null){
			if(!empty($data['type']) && $data['type'] == 1){
				//设置的 缺勤通知线
				$attendance_notice = CF('attendance_notice','',CONFIG_PATH);
				//当前学期
				$nowterm = $data['nowterm'];
				//userid
				$studentid = $data['studentid'];
				//查看当前学期的 缺勤次数
				$data_count = $this->db->select('*')->get_where('checking','studentid = '.$studentid .' AND type = 1')->result_array();
				
				if(count($data_count) > $attendance_notice['warning']){
					//发邮件
					$email = $this->db->select('email')->get_where('student','id = '.$studentid)->row();
					$val_arr = array(
							'email' => $email->email,
							'notice_line' => $attendance_notice['warning']
					);
					$this->load->library ( 'sdyinc_email' );
					$MAIL = new sdyinc_email ();
					$MAIL->dot_send_mail ( 21,$email->email,$val_arr);
					
				}
			}
			
			
			
			
		}
	}
	
	function qin(){
		$mid=$this->input->get('mid');
		$sid=$this->input->get('sid');
		$hour=CF('hour','',CONFIG_PATH);
		$data['kaoqin']=$this->checking_model->kaoqin($sid);
		$data['chuqin']=$this->checking_model->chuqin($sid,$mid,$hour);
		ajaxReturn($data,'',1);
	}
	/**
	 *
	 *导出
	 **/
	function export(){
		$where=$this->input->post();
		foreach ($where as $key => $value) {
			if($value==0){
				unset($where[$key]);
			}
		}
		$this->load->library('sdyinc_export');
	
			$d=$this->sdyinc_export->do_export_checking($where);
		
		if(!empty($d)){
			$this->load->helper('download');
			force_download('checking'. time().'.xlsx', $d);
			return 1;
		}
	
	}
	/**
	 *
	 *按条件获取学生
	 **/
	function get_student_quick(){
		$key=$this->input->get('key');
		$value=$this->input->get('value');
		$hour=CF('hour','',CONFIG_PATH);
		if(!empty($key)&&!empty($value)){
			$sdata=$this->checking_model->get_where_student($key,$value);
		}
		if(empty($sdata)){
			ajaxreturn('','没有所查询的学生',2);
		}
		foreach ($sdata as $k => $v) {
			$sdata[$k]['kaoqin']=$this->checking_model->kaoqin($v['id']);
			$sdata[$k]['chuqin']=$this->checking_model->chuqin($v['id'],$v['majorid'],$hour);
		}
		$data['stu']=$sdata;
		if(!empty($sdata)){
			ajaxReturn($data,'',1);
		}
	}
	/**
	 *快速设置考勤页面
	 *
	 **/
	function quick_checking_page(){
		$majorid=intval($this->input->get('majorid'));
		$studentid=intval($this->input->get('studentid'));
		$squadid=intval($this->input->get('squadid'));
		$s=intval($this->input->get('s'));
		if(!empty($s)){
				$classtime=$this->checking_model->get_classtime($majorid,$squadid);
				$sname=$this->checking_model->get_sname($studentid);
				$s=date('N',time());
				$stime=time()-24*3600*($s-1);
				$termnum=$this->checking_model->get_m_term($majorid);

				$etime=$stime+24*3600*6;
			$html = $this->_view ( 'quick_set_checking', array(
				'classtime'=>$classtime,
				'sname'=>$sname,
				'majorid'=>$majorid,
				'studentid'=>$studentid,
				'squadid'=>$squadid,
				'stime'=>$stime,
				'etime'=>$etime,
				'termnum'=>$termnum,
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}
	function get_checkeing_course(){
		$date=$this->input->get('date');
		$squadid=$this->input->get('squadid');
		$week=date('w',strtotime($date));
		$course=$this->checking_model->get_nowday_course($squadid,$week);
		ajaxreturn($course,'',1);
	}

	function insert_checking(){
		$data=$this->input->post();
		$arr=explode('-', $data['course']);
		$data['teacherid']=$arr[0];
		$data['courseid']=$arr[1];
		$data['knob']=$arr[2];
		$data['date']=strtotime($data['date']);
		$data['week']=date('w',$data['date']);
		$data['nowterm']=$this->checking_model->get_squad_term($data['squadid']);
		unset($data['course']);
		if (! empty ( $data )) {
			
			$id = $this->checking_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}else{
				ajaxReturn ( 'back', '添加失败', 0 );
			}
		}else{
			ajaxReturn ( 'back', '请选择', 0 );
		}

		
	}
	/**
	 *
	 *导入页面
	 **/
	function tochanel(){
			$mdata=$this->checking_model->get_majorinfo();
		$s=intval($this->input->get('s'));
			if(!empty($s)){
			$html = $this->_view ( 'tochanel', array(
				'mdata'=>$mdata,
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}

	// /**
	//  *
	//  *导出模板
	//  **/
	// function tochaneltenplate(){	
	// 	$data=$this->checking_model->get_checking_fields();
	// 	$this->load->library('sdyinc_export');
	// 	$d=$this->sdyinc_export->checking_tochaneltenplate($data);
	// 	if(!empty($d)){
	// 		$this->load->helper('download');
	// 		force_download('checking'. time().'.xlsx', $d);
	// 		return 1;
	// 	}
	// }
		/**
	 *
	 *导出模板
	 **/
	function tochaneltenplate(){
		$datas=$this->input->post();
		$data=$this->checking_model->get_checking_fields();	
		$sdata=$this->checking_model->get_studentinfo($datas['squadid']);
		$this->load->library('sdyinc_export');
		$d=$this->sdyinc_export->checking_tochaneltenplate($data,$datas,$sdata);
		if(!empty($d)){
			$this->load->helper('download');
			force_download('checking'. time().'.xlsx', $d);
			return 1;
		}
	}


		function upload_excel(){
		    //判断文件类型，如果不是"xls"或者"xlsx"，则退出
        if ( $_FILES["file"]["type"] == "application/vnd.ms-excel" ){
                $inputFileType = 'Excel5';
        }
        elseif ( $_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ){
                $inputFileType = 'Excel2007';
        }
        else {
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "您选择的文件格式不正确";
                exit();
        }
        
        if ($_FILES["file"]["error"] > 0)
        {
                echo "Error: " . $_FILES["file"]["error"] . "<br />";
                exit();
        }

       $inputFileName = $_SERVER ['DOCUMENT_ROOT'] . $this->_upload();
        if (file_exists($inputFileName))
        {
                //echo $_FILES["file"]["name"] . " already exists. <br />";
                unlink($inputFileName);    //如果服务器上存在同名文件，则删除
        }
        else
        {
        }
        move_uploaded_file($_FILES["file"]["tmp_name"],$inputFileName);
           $this->load->library('PHPExcel');
		$this->load->library('PHPExcel/IOFactory');
		$this->load->library('PHPExcel/Writer/Excel2007');
        $objReader = IOFactory::createReader($inputFileType);
        $WorksheetInfo = $objReader->listWorksheetInfo($inputFileName);

        //读取文件最大行数、列数，偶尔会用到。
        $maxRows = $WorksheetInfo[0]['totalRows'];
        $maxColumn = $WorksheetInfo[0]['totalColumns']; 
       
         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
       
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);
        $zjj = $objPHPExcel->getActiveSheet();
        $zjj->getStyle('F1:F'.$maxRows)->getNumberFormat()->setFormatCode('YYYY-mm-dd');
        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[2];
   	     $num=count($sheetData[2]);
   	    	$str=$sheetData[1]['A'];
   	    	$arr=explode('--',$str);
   	    	$term=substr($arr[1], 3,1);
   	     $majorid=$this->checking_model->get_majorid($arr[0]);
        $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E','F','G','H');
 		//     

        $cfields=$this->checking_model->get_check_fields();
        if($num!=count($cfields)){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($cfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                        exit();
                }
        }
        $insert='';
        foreach ($cfields as $k => $v) {
        	if($k=='type'){
        		$insert.=$k.',';
        		$insert.='week,';
        	}else{
        		$insert.=$k.',';

        	}
        }
        $insert.='teacherid,courseid';
        $insert=trim($insert,',');
        unset($sheetData[1]);
        unset($sheetData[2]);
			$i=65;
			$m=3;
			$k=65;
			$str='';
			foreach ($sheetData as $k => $v) {
				$value='';
				foreach ($v as $kk => $vv) {
					if($kk=='A'){
						$value.='"'.$this->checking_model->get_studentid($vv,$v['I']).'",';
					}elseif($kk=='B'){
						//获取专业id
						$arr=explode('-', $vv);	
						$vv=$arr[0];
						$value.='"'.$vv.'",';
					}elseif($kk=='C'){
						//获取班级id
						$arr=explode('-', $vv);	
						$vv=$arr[0];
						$value.='"'.$vv.'",';
					}elseif($kk=='E'){
						$vv=$term;
						$value.='"'.$vv.'",';
					}elseif($kk=='F'){
						$app_time=strtotime($zjj->getCell('F'.$k)->getFormattedValue());
						$value.='"'.$app_time.'",';
					}elseif($kk=='G'){

						$vv=$this->checking_model->get_checktype($vv);
						$week='';
						$value.='"'.$vv.'",';
						$date=strtotime($zjj->getCell('F'.$k)->getFormattedValue());
						$week=date('w',$date);
						if($week==0){
							$week=7;
						}
						$value.='"'.$week.'",';
					}elseif($kk=='H'){
						$knob=$this->checking_model->get_knob($vv);
						$value.='"'.$knob.'",';
					}
					else{
						$value.='"'.$vv.'",';

					}

				}
				$value=trim($value,',');

				//判断是否有重复的记录
				// $count=$this->checking_model->checkchecking($insert,$value);

				// 				if($count>0){
				// 					continue;
				// 				}
				// $insert.=',majorid,nowterm';
				
				$squadstr=explode('-', $v['C']);	
				$excelsquadid=$squadstr[0];
				
				$teacherid_courseid=$this->checking_model->get_teacherid_courseid($excelsquadid,$week,$knob);
				if(strlen($teacherid_courseid)==1){
					$str.='<br />excel中的'.$m."行数据有误,该时间段没有课程";
					$m++;
					continue;
				}
				$value.=','.$teacherid_courseid;
				$count=$this->checking_model->check_checking($insert,$value);
				if($count>0){
					$str.='<br />excel中的'.$m."行与数据库重复";
						$m++;
					continue;
				}
				$m++;
				$this->checking_model->insert_checking_fields($insert,$value);
				
				
				//检查是否有重复记录
			
				//插入数据库
				//
				
			$i++;
			}
			if($str!=''){
				echo $str;
			}else{
				echo $inputFileName.'上传成功';
			}


	}
	/** 
	 *
	 *考勤录入下一时间
	 **/
	function next_time(){
		$stime=$this->input->get('starttime');
		$etime=$this->input->get('overtime');
		$starttime=strtotime($stime)+24*3600*7;
		$overtime=strtotime($stime)+24*3600*13;
		$date['start']=date('Y-m-d',$starttime);
		$date['over']=date('Y-m-d',$overtime);
		$date['mo']=date('m-d',$starttime);
		$date['tu']=date('m-d',$starttime+24*3600);
		$date['we']=date('m-d',$starttime+24*3600*2);
		$date['th']=date('m-d',$starttime+24*3600*3);
		$date['fr']=date('m-d',$starttime+24*3600*4);
		$date['sa']=date('m-d',$starttime+24*3600*5);
		$date['su']=date('m-d',$starttime+24*3600*6);
		ajaxReturn($date,'',1);
	}
	/** 
	 *
	 *考勤录入上一周时间
	 **/
	function up_time(){
		$stime=$this->input->get('starttime');
		$starttime=strtotime($stime)-24*3600*7;
		$overtime=strtotime($stime)-24*3600;
		$date['start']=date('Y-m-d',$starttime);
		$date['over']=date('Y-m-d',$overtime);
		$date['mo']=date('m-d',$starttime);
		$date['tu']=date('m-d',$starttime+24*3600);
		$date['we']=date('m-d',$starttime+24*3600*2);
		$date['th']=date('m-d',$starttime+24*3600*3);
		$date['fr']=date('m-d',$starttime+24*3600*4);
		$date['sa']=date('m-d',$starttime+24*3600*5);
		$date['su']=date('m-d',$starttime+24*3600*6);
		ajaxReturn($date,'',1);
	}
	/** 
	 *时间变化
	 **/
	function change_date(){
		$stime=$this->input->get('starttime');
		$studentid=$this->input->get('studentid');
		$classtime=$this->input->get('classtime');
		if(strtotime($stime)<=$classtime){
			$ckdata=$this->checking_model->get_up_ck($studentid,$classtime);
			

			$date['start']=date('Y-m-d',$classtime);
			$date['over']=date('Y-m-d',$classtime*7*24*3600);
			$date['ckdata']=$ckdata;
			ajaxReturn($date,'已经是开班的时间了',0);
		}
		$ckdata=$this->checking_model->get_up_ck($studentid,strtotime($stime));
		$k=date('w',strtotime($stime));
		if($k==0){
			$k=6;
		}else{
			$k=$k-1;
		}
		$stime=strtotime($stime)-$k*3600*24;
		$etime=$stime+6*24*3600;
		$date['start']=date('Y-m-d',$stime);
		$date['over']=date('Y-m-d',$etime);
		$date['ckdata']=$ckdata;
		$date['mo']=date('m-d',$stime);
		$date['tu']=date('m-d',$stime+24*3600);
		$date['we']=date('m-d',$stime+24*3600*2);
		$date['th']=date('m-d',$stime+24*3600*3);
		$date['fr']=date('m-d',$stime+24*3600*4);
		$date['sa']=date('m-d',$stime+24*3600*5);
		$date['su']=date('m-d',$stime+24*3600*6);
		ajaxReturn($date,'',1);

	}
	/**
	 *
	 *考勤录取获取学生
	 **/
	function get_students(){
		$data=$this->input->post();
		$hour=CF('hour','',CONFIG_PATH);
		$time=$this->input->get('time');
		if(!empty($data['key'])&&!empty($data['value'])){
			$sdata=$this->checking_model->get_student_one($data);
		}else{
			$sdata=$this->checking_model->get_studentinfo($data['squadid']);
		}
		if(empty($sdata)){
			ajaxreturn('','该班级还没有学生',2);
		}
		//获取排课情况
		$pdata=$this->checking_model->get_scheduling($data);
		
		foreach ($sdata as $k => $v) {
			$sdata[$k]['kaoqin']=$this->checking_model->kaoqin($v['id']);
			$sdata[$k]['chuqin']=$this->checking_model->chuqin($v['id'],$data['majorid'],$hour);
		}
		$data['stu']=$sdata;

		$data['pdata']=$pdata;
		//学生考勤情况
		$data['checking']=$this->checking_model->get_checking(strtotime($time));
		
		if(!empty($sdata)){
			ajaxReturn($data,'',1);
		}
	}


	/*********************打印报表*************************/
	/**
	 *
	 *打印报表
	 **/
	function get_student_report(){
		$data=$this->input->post();
		$hour=CF('hour','',CONFIG_PATH);
		
		
		$sdata=$this->checking_model->get_studentinfo($data['squadid']);
		if(empty($sdata)){
			ajaxreturn('','该班级还没有学生',2);
		}
		foreach ($sdata as $k => $v) {
			$sdata[$k]['kaoqin']=$this->checking_model->kaoqin($v['id']);
			$sdata[$k]['chuqin']=$this->checking_model->chuqin($v['id'],$data['majorid'],$hour);
		}
		$data['stu']=$sdata;
		if(!empty($sdata)){
			ajaxReturn($data,'',1);
		}
	}
	//获取老师
	function get_teacher(){
		$squadid=$this->input->get('squadid');
		//获取该班级的代课老师
		$tea_data=$this->checking_model->get_teacher_info($squadid);
		if(!empty($tea_data)){
			ajaxreturn($tea_data,'',1);
		}else{
			ajaxreturn('','没有查询到该班的代课老师',0);
		}
	}
	//获取教室
	function get_classroom(){
		$teacherid=$this->input->get('teacher');
		//获取该班级的代课老师
		$room_data=$this->checking_model->get_classroom_info($teacherid);
		if(!empty($room_data)){
			ajaxreturn($room_data,'',1);
		}else{
			ajaxreturn('','没有查询到所代课的教室',0);
		}
	}
	/**
	 *
	 *打印报表
	 **/
	function checking_report(){
			$data=$this->input->post();
			$squadid=$this->checking_model->get_report_squadinfo($data);
			//获取所有的学生
			$student_all=$this->checking_model->get_student_all($squadid,$data['num']);
			//总行数
			$count_all=$this->checking_model->get_count_all($student_all);
			//
			$classroom=$this->checking_model->get_classroom_name($data);
			$teacher=$this->checking_model->get_teacher_name($data);
			$majorname=$this->checking_model->get_major_name($data['majorid']);
			$date=$this->checking_model->get_date($squadid,$data['num']);
			$this->_view ('checking_print_report',array(
				'student_all'=>$student_all,
				'count_all'=>$count_all,
				'classroom'=>$classroom,
				'teacher'=>$teacher,
				'majorname'=>$majorname,
				'trem'=>$data['nowterm'],
				'num'=>$data['num'],
				'date'=>$date
			));
	}

	function zjj_test(){
		$date=$this->input->get('date');
		$type=$this->input->post('value[c_type]');
		$remark=$this->input->post('value[c_remark]');
		$dates=strtotime($date);
		$pk=$this->input->post('pk');
		$pk_arr=explode('-', $pk);
		$data['studentid']=$pk_arr[0];
		$data['email']=$pk_arr[1];
		$data['majorid']=$pk_arr[2];
		$data['teacherid']=$pk_arr[3];	
		$data['courseid']=$pk_arr[4];
		$data['squadid']=$pk_arr[5];
		$data['knob']=$pk_arr[6];
		$data['nowterm']=$pk_arr[7];
		$data['week']=$pk_arr[8];
		$datess=$dates+($data['week']-1)*24*3600;
		$data['date']=$datess;
		$data['adminid']=$_SESSION ['master_user_info']->id;
		$data['type']=$type;
		$data['remark']=$remark;
		$id=null;
		if(!empty($pk_arr[9])){
			$id=$pk_arr[9];
		}
		$id=$this->checking_model->save_checking($id,$data);
		if(!empty($id)){
			ajaxReturn('','插入成功',1);
		}
		
	}
	/**
	 * 上传
	 *
	 * @return string
	 */
	private function _upload() {
		$config = array (
				'save_path' => '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'upload_path' => $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Ym' ) . '/' . date ( 'd' ),
				'allowed_types' => 'xls|xlsx',
				'file_name' => time () . rand ( 100000, 999999 ) 
		);
		
		if (! empty ( $config )) {
			$this->load->library ( 'upload', $config );
			// 创建目录
			mk_dir ( $config ['upload_path'] );
			
			if (! $this->upload->do_upload ( 'file' )) {
				ajaxReturn ( '', $this->upload->display_errors ( '', '' ), 0 );
			} else {
				$imgdata = $this->upload->data ();
				return $config ['save_path'] . '/' . $imgdata ['file_name'];
			}
		}
	}
}
