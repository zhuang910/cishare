<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Payconf extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		//$this->load->model($this->view.'sysconf_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$apply=CF('apply','',CONFIG_PATH);
		$pledge=CF('pledge','',CONFIG_PATH);
		$stay=CF('stay','',CONFIG_PATH);
		$pickup=CF('pickup','',CONFIG_PATH);
		if($pickup == 1){
			$pickup = '';
		}
		//阶级收费设置
		
		$city = CF('city','',CONFIG_PATH);
		
		$scholarship= CF('scholarship','',CONFIG_PATH);
		$bed= CF('bed','',CONFIG_PATH);
		$insurance= CF('insurance','',CONFIG_PATH);
		$electric= CF('electric','',CONFIG_PATH);
		$books= CF('books','',CONFIG_PATH);
		$acc_pledge= CF('acc_pledge','',CONFIG_PATH);
		$tuition=CF('tuition','',CONFIG_PATH);
		$abatement=CF('abatement','',CONFIG_PATH);
		$this->_view ('payconf_index',array(
			'apply'=>$apply,
			'pledge'=>$pledge,
			'stay'=>$stay,
			'pickup'=>$pickup,
			'scholarship'=>$scholarship,
			'bed'=>$bed,
			'insurance'=>$insurance,
			'electric'=>$electric,
			'books'=>$books,
			'acc_pledge'=>$acc_pledge,
			'tuition'=>$tuition,
			'abatement'=>$abatement,
			'city' => $city
			));
	}
	
	function applysave(){
		if($this->input->post("apply")=='no'){
			$apply=trim($this->input->post("apply"));
			CF('apply',array('apply'=>$apply),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$apply=trim($this->input->post("apply"));
			$applymoney=trim($this->input->post("applymoney"));
			$applyway=trim($this->input->post("applyway"));
			CF('apply',array('apply'=>$apply,'applymoney'=>$applymoney,'applyway'=>$applyway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	function pledgesave(){
		if($this->input->post("pledge") == 'no'){
			$pledge=trim($this->input->post("pledge"));
			CF('pledge',array('pledge'=>$pledge),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$pledge=trim($this->input->post("pledge"));
			$pledgemoney=trim($this->input->post("pledgemoney"));
			$pledgeway=trim($this->input->post("pledgeway"));
			CF('pledge',array('pledge'=>$pledge,'pledgemoney'=>$pledgemoney,'pledgeway'=>$pledgeway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	
	}		
	function staysave(){
		if($this->input->post("stay") == 'no'){
			$stay=trim($this->input->post("stay"));
			CF('stay',array('stay'=>$stay),CONFIG_PATH);
			ajaxReturn('','',1);
		}elseif($this->input->post("stay") == 'yes'){
			$stay=trim($this->input->post("stay"));
			CF('stay',array('stay'=>$stay),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$stay=trim($this->input->post("stay"));
			$staymoney=trim($this->input->post("staymoney"));
			$stayway=trim($this->input->post("stayway"));
			CF('stay',array('stay'=>$stay,'staymoney'=>$staymoney,'stayway'=>$stayway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	
	/*添加接机设置*/
	function add_pickup(){
		$city = CF('city','',CONFIG_PATH);
		
		$html = $this->_view ('add_pickup',array('city' => $city),true);
		ajaxReturn($html,'',1);
	}
	
	function pickupsave(){
	/*	if($this->input->post("pickup") == 'no'){
			$pickup=trim($this->input->post("pickup"));
			CF('pickup',array('pickup'=>$pickup),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$pickup=trim($this->input->post("pickup"));
			$pickupmoney=trim($this->input->post("pickupmoney"));
			$pickupway=trim($this->input->post("pickupway"));
			CF('pickup',array('pickup'=>$pickup,'pickupmoney'=>$pickupmoney,'pickupway'=>$pickupway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	*/
		
		$data = $this->input->post();
	
		if(!empty($data)){
			
			foreach ( $data ['cityid'] as $k => $v ) {
					if(!empty($v)){
						$dataForm [$v] ['cityid'] = $v;
						$dataForm [$v] ['cityfees'] = !empty($data['cityfees'][$k])?$data['cityfees'][$k]:0;
						$dataForm [$v] ['timefees'] = !empty($data['timefees'][$k])?$data['timefees'][$k]:0;
						$dataForm [$v] ['busfees'] = !empty($data['busfees'][$k])?$data['busfees'][$k]:0;
						$dataForm [$v] ['carfees'] = !empty($data['carfees'][$k])?$data['carfees'][$k]:0;
						$dataForm [$v] ['stime'] = !empty($data['stime'][$k])?strtotime($data['stime'][$k]):0;
						$dataForm [$v] ['etime'] = !empty($data['etime'][$k])?strtotime($data['etime'][$k]):0;
					}
					
					
			}
			
			
			if(!empty($dataForm)){
				CF('pickup',$dataForm,CONFIG_PATH);
					ajaxReturn('','',1);
			}
				
			
		}
		
		CF('pickup',array(),CONFIG_PATH);
		ajaxReturn('','',1);
	}	
	function scholarshipsave(){
	
			$scholarship=trim($this->input->post("scholarship"));

			if(!empty($scholarship)){
				CF('scholarship',array('scholarship'=>$scholarship),CONFIG_PATH);
				ajaxReturn('','',1);
			}
			
		
		ajaxReturn('','',0);	
	}
	//设置床上用品费用
	function bedsave(){
		if($this->input->post("bed") == 'no'){
			$bed=trim($this->input->post("bed"));
			CF('bed',array('bed'=>$bed),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$bed=trim($this->input->post("bed"));
			$bedmoney=trim($this->input->post("bedmoney"));
			$bedway=trim($this->input->post("bedway"));
			CF('bed',array('bed'=>$bed,'bedmoney'=>$bedmoney,'bedway'=>$bedway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	
	}
	//保险费
	function insurancesave(){
		if($this->input->post("insurance") == 'no'){
			$insurance=trim($this->input->post("insurance"));
			CF('insurance',array('insurance'=>$insurance),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$insurance=trim($this->input->post("insurance"));
			$insurancemoney_one=trim($this->input->post("insurancemoney_one"));
			$insurancemoney_two=trim($this->input->post("insurancemoney_two"));
			$insuranceway=trim($this->input->post("insuranceway"));
			CF('insurance',array('insurance'=>$insurance,'insurancemoney_one'=>$insurancemoney_one,'insurancemoney_two'=>$insurancemoney_two,'insuranceway'=>$insuranceway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	
	}
	//电费
	function electricsave(){
		if($this->input->post("electric") == 'no'){
			$electric=trim($this->input->post("electric"));
			CF('electric',array('electric'=>$electric),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$electric=trim($this->input->post("electric"));
			$electricmoney=trim($this->input->post("electricmoney"));
			$electricway=trim($this->input->post("electricway"));
			CF('electric',array('electric'=>$electric,'electricmoney'=>$electricmoney,'electricway'=>$electricway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	
	}	
	//书费
	function bookssave(){
		if($this->input->post("books") == 'no'){
			$books=trim($this->input->post("books"));
			CF('books',array('books'=>$books),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$books=trim($this->input->post("books"));
			$booksmoney=trim($this->input->post("booksmoney"));
			$booksway=trim($this->input->post("booksway"));
			CF('books',array('books'=>$books,'booksmoney'=>$booksmoney,'booksway'=>$booksway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	
	}
	//书费
	function acc_pledgesave(){
		if($this->input->post("acc_pledge") == 'no'){
			$acc_pledge=trim($this->input->post("acc_pledge"));
			CF('acc_pledge',array('acc_pledge'=>$acc_pledge),CONFIG_PATH);
			ajaxReturn('','',1);
		}else{
			$acc_pledge=trim($this->input->post("acc_pledge"));
			$acc_pledgemoney=trim($this->input->post("acc_pledgemoney"));
			$acc_pledgeway=trim($this->input->post("acc_pledgeway"));
			CF('acc_pledge',array('acc_pledge'=>$acc_pledge,'acc_pledgemoney'=>$acc_pledgemoney,'acc_pledgeway'=>$acc_pledgeway),CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);	
	}	
	//换证费计入学费开关
	function replacementsave(){
		$data=$this->input->post();
		if(!empty($data)){
			$tuition=CF('tuition','',CONFIG_PATH);
			$tuition['replacement']=$data['replacement'];
			CF('tuition',$tuition,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}	
	//重修费计入学费开关
	function repair_feesave(){
		$data=$this->input->post();
		if(!empty($data)){
			$tuition=CF('tuition','',CONFIG_PATH);
			$tuition['repair_fee']=$data['repair_fee'];
			CF('tuition',$tuition,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
		
	//重修费计入学费开关
	function pledgejirusave(){
		$data=$this->input->post();
		if(!empty($data)){
			$tuition=CF('tuition','',CONFIG_PATH);
			$tuition['pledgejiru']=$data['pledgejiru'];
			CF('tuition',$tuition,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	
	//重修费计入学费开关
	function entry_feesave(){
		$data=$this->input->post();
		if(!empty($data)){
			$tuition=CF('tuition','',CONFIG_PATH);
			$tuition['entry_fee']=$data['entry_fee'];
			CF('tuition',$tuition,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	//重修费计入学费开关
	function abatementsave(){
		$data=$this->input->post();
		if(!empty($data)){
			CF('abatement',$data,CONFIG_PATH);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
}