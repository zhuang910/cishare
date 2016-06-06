<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Itemsetting extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/score/';
		$this->load->model($this->view.'itemsetting_model');
	}
	
	/**
		 * 后台主页
		 */
		function index() {
			if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field ();
				
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->itemsetting_model->count ( $condition );
				
				$output ['aaData'] = $this->itemsetting_model->get ( $fields, $condition );
				
				foreach ( $output ['aaData'] as $item ) {
					$item->state = $this->_get_lists_state ( $item->state );
					$state = $item->state;
					$item->operation = '
					<a class="btn btn-xs btn-info" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'itemsetting/edit?id=' . $item->id . '&s=1\')">编辑</a>
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
			
			$this->_view ( 'itemsetting_index' );
		}
		/**
		 * 获取课程状态
		 *
		 * @param string $statecode        	
		 * @param string $stateindexcode        	
		 * @return string
		 */
		private function _get_lists_state($statecode = null) {
			if ($statecode != null) {
				$statemsg = array (
						'<span class="label label-important">禁用</span>',
						'<span class="label label-success">正常</span>' 
				);
				return $statemsg [$statecode];
			}
			return;
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'id',
					'code',
					'name',
					'enname',
					'scores_of',
					'state' 
			);
		}
		/**
		 * [add 添加编辑页面]
		 */
		function add(){
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'addtype', array (), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$id = $this->itemsetting_model->save ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	//编辑
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->itemsetting_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该考试不存在', 0 );
			}
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'addtype', array (
					'info'=>$info
					), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		
	}
	//更新
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		$data=$this->input->post();
		if ($id) {
			// 保存基本信息
			$this->itemsetting_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	//删除
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->itemsetting_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	 /**
	 * 导入页面
	 */
	function tochanel() {
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$html = $this->_view ( 'tochanel_set', array (), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * 导出模板
	 */
	function tochaneltenplate() {
		$data = $this->itemsetting_model->get_item_fields ();
		$this->load->library ( 'sdyinc_export' );
		$d = $this->sdyinc_export->itemsetting_tochaneltenplate ( $data );
		if (! empty ( $d )) {
			$this->load->helper ( 'download' );
			force_download ( 'item' . time () . '.xlsx', $d );
			return 1;
		}
	}
		/**
		 *
		 *导出
		 **/
		function export(){
			$this->load->library ( 'sdyinc_export' );
			$d = $this->sdyinc_export->do_export_itemsetting ();
			if (! empty ( $d )) {
				$this->load->helper ( 'download' );
				force_download ( 'item' . time () . '.xlsx', $d );
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
		$str = $_SERVER ['DOCUMENT_ROOT'] . '/uploads/work/' . date ( 'Y' ) . '/' . date ( 'md' ) . '/';
			if(!is_dir($str)){
				mk_dir($str);
			}
        $inputFileName =$str.'/'.$_FILES["file"]["name"];
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
        
         //设置只读，可取消类似"3.08E-05"之类自动转换的数据格式，避免写库失败
        $objReader->setReadDataOnly(true);
      
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetData = $objPHPExcel->getSheet(0)->toArray(null,true,true,true);
        //excel2003文件，可使用'$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);'
        //excel2007文件使用"getActiveSheet()"方法时会提示出错：对non-object使用了"toArray"方法。    
         $keywords = $sheetData[1];
   	     $num=count($sheetData[1]);
         $warning = '上传文件字段格式不正确，请修改后重新上传！<br />';
        $columns = array ( 'A', 'B', 'C' );
        $mfields=$this->itemsetting_model->get_item_fields();
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
                         unlink($inputFileName);   
                        exit();
                }
        }
        $insert='';
        foreach ($mfields as $k => $v) {
        	$insert.=$k.',';
        }
        $insert.='createtime,adminid';
        $insert=trim($insert,',');
        unset($sheetData[1]);
			$i=65;
			$ss=2;
			$str='';
			foreach ($sheetData as $k => $v) {
				$value='';
				foreach ($v as $kk => $vv) {
					if($kk=='E'){
						$vv=$vv=='是'?1:0;
						$value.='"'.$vv.'",';
					}else{
						$value.='"'.$vv.'",';
					}
				}
				$value.='"'.time().'",';
				$value.='"'.$_SESSION['master_user_info']->id.'",';
				$value=trim($value,',');
				$count=$this->itemsetting_model->check_course($insert,$value);
				if($count>0){
					$str.='<br />excel中的'.$ss."行专业名与数据库重复";
					$ss++;
					continue;
				}
				$this->itemsetting_model->insert_fields($insert,$value);
			$i++;
			$ss++;
			}
			if($str!=''){
				echo $str;
			}
	}
}