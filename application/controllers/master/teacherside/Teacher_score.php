<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Teacher_score extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/teacherside/';
		$this->load->model($this->view.'teacher_score_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
	
		if(!empty($_SESSION ['master_user_info']->id)){
			$userid=$_SESSION ['master_user_info']->id;
		}
		
		$teacherid=$this->teacher_score_model->get_teacherids($userid);
		$mdata=$this->teacher_score_model->get_majorinfo($teacherid);
		$t_course=$this->teacher_score_model->get_t_course($teacherid);
		$scoretype=$this->db->where('state = 1')->get('set_score')->result_array();
		$this->_view ('teacher_score_index',array(
			'mdata'=>$mdata,
			'scoretype'=>$scoretype,
			't_course'=>$t_course
			));
	}
	/**
	 * 获取该专业学期
	 */
	public function get_nowterm($mid){
		$nowterm=$this->teacher_score_model->get_major_nowterm($mid);
		$course=$this->teacher_score_model->get_course($mid);
			$data['nowterm']=$nowterm;
		if(!empty($course)){
			$data['course']=$course;
			ajaxReturn ( $data, '', 1 );
		}else{
			ajaxReturn($data,'该专业还没有课程',2);
		}
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		if(!empty($_SESSION ['master_user_info']->id)){
			$userid=$_SESSION ['master_user_info']->id;
		}
		$teacherid=$this->teacher_score_model->get_teacherids($userid);
		$squad=$this->teacher_score_model->get_squadinfo($mid,$term,$teacherid);
		if(empty($squad)){
			ajaxReturn('','该学期下还没有您所带班级',0);		}
		//var_dump($squad);exit;
		if(!empty($squad)){
			ajaxReturn ( $squad, '', 1 );
		}
	}

	/**
	 *
	 *获取学生
	 **/
	function get_student(){
	 $data=$this->input->post();
	 if($data['majorid']!='0'&&$data['courseid']!='0'&&$data['squadid']!='0'&&$data['nowterm']!='0'){
	 
			
			$scoreinfo=$this->teacher_score_model->get_stu_score();
			 if(!empty($data['key'])&&!empty($data['value'])){
				$sdata=$this->teacher_score_model->get_student_one($data['squadid'],$data['key'],$data['value']);
		   	 	if(empty($sdata)){
		   	 		ajaxReturn('','没有所查找的学生',0);
		   	 	}
		   	 }else{
				$sdata=$this->teacher_score_model->get_studentinfo($data['squadid']);
		   	 }
			$data['stu']=$sdata;
			$data['scoreinfo']=$scoreinfo;
			if(!empty($sdata)){
				ajaxReturn($data,'',1);
			}else {
				ajaxReturn('','没有所查找的学生',0);
			}
	 }elseif(!empty($data['key'])&&!empty($data['value'])){
	 	var_dump($data);exit;
	 }else{
	 	ajaxReturn('','学期班级课程不能为空',0);
	 }
	}
	/**
	 * 保存成绩
	 * 
	 * */
	function save_score(){
		$data['majorid']=$this->input->get('majorid');
		$data['squadid']=$this->input->get('squadid');
		$data['term']=$this->input->get('nowterm');
		$data['scoretype']=$this->input->get('scoretype');
		$data['courseid']=$this->input->get('courseid');
		$dataone=$this->input->get();
		$datatwo=$this->input->post();
		if($this->teacher_score_model->insert_score($data,$datatwo)){
				$scoreinfo=$this->teacher_score_model->get_stu_score();
				if(!empty($dataone['key'])&&!empty($dataone['value'])){
				$sdata=$this->teacher_score_model->get_student_one($dataone['squadid'],$dataone['key'],$dataone['value']);
			   	 	if(empty($sdata)){
			   	 		ajaxReturn('','没有所查找的学生',0);
				   	 	}
			   	 }else{
					$sdata=$this->teacher_score_model->get_studentinfo($data['squadid']);
			   	 }
				$data['stu']=$sdata;
				$data['scoreinfo']=$scoreinfo;
			ajaxReturn($data,'',1);
		}
	}
	/**
	 * 删除
	 * 
	 * */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		$sid= intval ( $this->input->get ( 'sid' ) );
		$key=  $this->input->get ( 'key' ) ;
		$value=$this->input->get ( 'value') ;
		if ($id) {
			$where = "id = {$id}";
			$is = $this->teacher_score_model->delete ( $where );
			if ($is === true) {
				$scoreinfo=$this->teacher_score_model->get_stu_score();
				if(!empty($key)&&!empty($value)){
					$sdata=$this->teacher_score_model->get_student_one($sid,$key,$value);
				}else{
					$sdata=$this->teacher_score_model->get_studentinfo($sid);
				}
				$data['stu']=$sdata;
				$data['scoreinfo']=$scoreinfo;
				ajaxReturn ( $data, '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 *导出
	 *
	 **/
	function export(){
		$where=$this->input->post();
		foreach ($where as $key => $value) {
			if($value==0){
				unset($where[$key]);
			}
		}
		$this->load->library('sdyinc_export');
		
			$d=$this->sdyinc_export->do_export_score($where);
		if(!empty($d)){
			$this->load->helper('download');
			force_download('chengji'. time().'.xlsx', $d);
			return 1;
		}
	} 
	/**
	 *
	 *导入页面
	 **/
	function tochanel(){
		$mdata=$this->teacher_score_model->get_majorinfo();
		$scoretype=CF('scoretype','',CONFIG_PATH);
		$s=intval($this->input->get('s'));
			if(!empty($s)){
			$html = $this->_view ( 'tochanel', array(
				'mdata'=>$mdata,
				'scoretype'=>$scoretype,
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	}

	/**
	 *
	 *导出模板
	 **/
	function tochaneltenplate(){
		$ddata=$this->input->post();
		$sdata=$this->teacher_score_model->get_studentinfo($ddata['squadid']);
		// var_dump($sdata);
		// var_dump($data);exit;
		$data=$this->teacher_score_model->get_stuscore_fields();
		$this->load->library('sdyinc_export');
		$d=$this->sdyinc_export->stuscore_tochaneltenplate($data,$ddata,$sdata);
		if(!empty($d)){
			$this->load->helper('download');
			force_download('chengji'. time().'.xlsx', $d);
			return 1;
		}
	}

		/**
	 *
	 *上传major
	 **/
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

        $inputFileName ='./uploads/tempexcel/'.$_FILES["file"]["name"];
        if (file_exists($inputFileName))
        {
                //echo $_FILES["file"]["name"] . " already exists. <br />";
                unlink($inputFileName);    //如果服务器上存在同名文件，则删除
        }
        else
        {
        }
        move_uploaded_file($_FILES["file"]["tmp_name"],$inputFileName);
        echo "Stored in: " . $inputFileName;
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
        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[1];
   	     $num=count($sheetData[1]);
        $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C', 'D', 'E', 'F', 'G','H');
        $mfields=$this->teacher_score_model->get_stuscore_fields();
         if($num!=count($mfields)){
        	echo '字段个数不匹配';
        	exit();
        }
        $keysInFile = array ( );
        foreach ($mfields as $key => $value) {
        	$keysInFile[]=$value;
        }
        foreach( $columns as $keyIndex => $columnIndex ){
                if ( $keywords[$columnIndex] != $keysInFile[$keyIndex] ){
                        echo $warning . $columnIndex . '列应为' . $keysInFile[$keyIndex] . '，而非' . $keywords[$columnIndex];
                        exit();
                }
        }
        $insert='';
        foreach ($mfields as $k => $v) {

        	$insert.=$k.',';
        }
        $insert=trim($insert,',');
        unset($sheetData[1]);
			$i=65;
			$m=2;
			$str='';
			foreach ($sheetData as $k => $v) {

				$value='';
				$mid='';
				foreach ($v as $kk => $vv) {
					if(empty($vv)){
						echo $warning.'excel中的'.$kk.$m.'数据不能为空';
						exit();
					}
					if($kk=='A'){
						$value.='"'.$this->teacher_score_model->get_studentid($vv,$v['I']).'",';
					}elseif($kk=='B'){
						$value.='"'.$this->teacher_score_model->get_majorid($vv).'",';
						$mid=$this->teacher_score_model->get_majorid($vv);
					}
					elseif($kk=='C'){
						$value.='"'.$this->teacher_score_model->get_courseid(trim($vv,'“”')).'",';
					}elseif($kk=='D'){	

						$value.='"'.$this->teacher_score_model->get_squadid($vv,$mid).'",';
					
					}elseif($kk=='F'){
						$vv=mb_convert_encoding($vv,'GB2312','UTF-8');
						$vv=substr($vv,2,1);
						$value.='"'.$vv.'",';
					}elseif($kk=='H'){
						$value.='"'.$this->teacher_score_model->get_scoretypeid($vv).'",';
					}
					else{
						$value.='"'.$vv.'",';

					}
				

				}
				
				$value=trim($value,',');
				$count=$this->teacher_score_model->check_score($insert,$value);
				if($count>0){
					$str.='<br />excel中的'.$m."行与数据库重复";
						$m++;
					continue;
				}
				$m++;
				$this->teacher_score_model->insert_fields($insert,$value);
			$i++;
			}
			if($str!=''){
				echo $str;
			}
	}

}