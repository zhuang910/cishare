<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Pay_Model extends CI_Model {
	const T_MAJOR='major';
	const T_STUDENT='student';
	const T_MAJOR_TUITION='major_tuition';
	const T_ACC='accommodation_info';
	const T_TUITION_INFO='tuition_info';
	const T_ELECTRIC_INFO='electric_info';//电费表
	const T_QUA_INFO='quarterage_info';
	const T_ACC_PLE_INFO='acc_pledge_info';//住宿押金表
	const T_APP='apply_info';
	const T_SCH_INFO = 'scholarship_info';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_STUDENT_REBUILD='student_rebuild';//重修费用表
	const T_STU_BAR_CARD='student_barter_card';//换证费表
	const T_TUITION_DETAIL='tuition_info_detail';//学费详情表
	const T_CRE='credentials';//凭据表
	const T_MAJOR_COURSE='major_course';
	const T_COURSE_BOOKS='course_books';
	const T_BOOKS='books';
	const T_BOOKS_FEE='books_fee';//书费表
	const T_ORDERBY='apply_order_info';//所有订单表
	const T_INSURANCE_INFO='insurance_info';//保险费表
	const T_SQUAD='squad';
	const T_J_X_J_APP='applyscholarship_info';
	const T_STUDENT_INFO='student_info';
	const T_DEPOSIT_INFO='deposit_info';//申请押金表
	const T_BUDGET = 'budget';//收支表
	const T_APP_REM_TUITION='apply_remission_tuition';//申请减免学费表
	const T_PINGJU='credentials';//凭据表
	const T_CASH='cash';//现金表
	const T_SLOT_CARD='slot_card';//刷卡表
	const T_COURSE='course';//课程表
	const T_ELECTRIC_PLEDGE='electric_pledge';//电费押金表
	const T_BEDDIND_FEE='bedding_fee';//床品费表




	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}

	function get_before_tuition($userid,$majorid,$term){
		if(!empty($userid)&&!empty($majorid)&&!empty($term)){
             return $this->db
                ->select('a.*,b.proof_number,b.file_path')
                ->from(self::T_TUITION_INFO.' a')
                ->join(self::T_BUDGET.' b','a.budgetid = b.id','left')
                ->where('a.userid = '.$userid.' AND a.nowterm = '.$term.' AND a.paystate = 1')
                ->get()
                ->result_array();
		}
	}
	/**
	 *
	 *获取该条件的学生
	 **/
	function get_where_student($key,$value){
		$this->db->select('student_info.*');
		$this->db->like('student_info.'.$key,$value);
		return $this->db->get(self::T_STUDENT_INFO)->result_array();
	}
	/**
	 * [get_tuition 获取实收的学费  如果是新生默认收取第一学期的学费]
	 * @return [type] [description]
	 */
	function get_tuition($userid){
		if(!empty($userid)){
			//判断是否为新生
			$udata=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			if(!empty($udata)&&!empty($userid['squadid'])){
				//老学生
			}else{
				//获取第一学期的学费
				$this->db->where('majorid = '.$udata['major'].' AND term=1');
				$num=$this->db->get(self::T_MAJOR_TUITION)->row_array();
				if(!empty($num['tuition'])){
					return $num['tuition'];
				}
			}
		}
		return 0;
	}
	/**
	 * [get_book 获取书费
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_book($userid){

		// if(!empty($userid)){
		// 	//获取学生信息
		// 	$udata=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
		// 	if(!empty($udata)&&!empty($userid['major'])){
		// 		//获取当前专业的所有书籍
		// 		$this->db->where('majorid = '.$udata['major'].' AND term=1');
		// 		$num=$this->db->get(self::T_MAJOR_TUITION)->row_array();
		// 		if(!empty($num['tuition'])){
		// 			return $num['tuition'];
		// 		}
		// 	}
		// }
		return 0;
	}
	/**
	 * [get_book 获取住宿费 根据住宿订单表里开始入住时间
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_acc($userid){
		if(!empty($userid)){
			//判断是否为新生
			$this->db->where('userid = '.$userid);
			$udata=$this->db->get(self::T_ACC)->row_array();
			//获取当前房间的价格
			$now_prices=$this->get_now_room_prices($udata['roomid']);
			if(!empty($udata)){
				return $now_prices*$udata['accendtime'];	
			}
		}
		return 0;
	}
	//获取当前房间的价格
	function get_now_room_prices($rid){
		if(!empty($rid)){
			$this->db->select('prices');
			$this->db->where('id',$rid);
			$data=$this->db->get(self::T_PRICES)->row_array();
			if(!empty($data['prices'])){
				return $data['prices'];
			}
			return 0;
		}
	}
	/**
	 * [insert_tuition 插入学费表]
	 * @param  [type] $userid  [description]
	 * @param  [type] $tuition [description]
	 * @return [type]          [description]
	 */
	function insert_tuition($userid,$tuition,$term=1){
		if(!empty($userid)&&!empty($tuition)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['nowterm']=$term;
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['tuition']=$tuition;
			$data['danwei']='rmb';
			$data['adminid']=$_SESSION['master_user_info']->id;
			$data['name']=$user_info['name'];
			$data['email']=$user_info['email'];
			$data['nationality']=$user_info['nationality'];
			$data['mobile']=$user_info['mobile'];
			$data['tel']=$user_info['tel'];
			$data['passport']=$user_info['passport'];
			$data['paystate']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$this->db->insert(self::T_TUITION_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,6,$tuition);
			}
		}
	}
	/**
	 * [insert_scholarship_order 讲学支付插入订单表]
	 * @return [type] [description]
	 */
	function insert_scholarship_order($id,$userid,$ordertype,$money){
		if(!empty($userid)&&!empty($ordertype)){
			$arr['createtime']=time();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['ordertype']=$ordertype;
			$arr['ordermondey']=$money;
			$arr['userid']=$userid;
			$arr['applyid']=$id;
			$arr['lasttime']=time();
			$arr['paytime']=time();
			$arr['paystate']=1;
			$arr['is_scholarship']=1;
			$this->db->insert(self::T_ORDERBY,$arr);
			}
		
	}
	/**
	 * [insert_tuition 插入学费表]
	 * @param  [type] $userid  [description]
	 * @param  [type] $tuition [description]
	 * @return [type]          [description]p
	 */
	function type_insert_tuition($data){
		//插入学费表  返回id再插入订单表
		$tuition_id=$this->type_insert_tuition_one($data);
		if(!empty($tuition_id)){
			//插入订单表
			$orderid=$this->insert_order($data,$tuition_id,6);
			//如果是凭据用户  则插入凭据表
			if($data['isproof']==1){
				$creid=$this->insert_tuition_credentials($data,$orderid);
			}
			
			
			if(!empty($orderid)){
				return $orderid;
			}
		}
		return 0;
	}
	/**
	 * [insert_tuition_order 插入学费所有订单表]
	 * @return [type] [description]
	 */
	function insert_order($data,$id,$ordertype){
		if(!empty($data)){
			$arr['createtime']=time();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['ordertype']=$ordertype;
			$arr['userid']=$data['userid'];
			$arr['applyid']=$id;
			$arr['ordermondey']=$data['paid_in'];
			$arr['lasttime']=time();
			if($data['isproof']==1){
				$arr['paytype']=3;
			}
			$arr['paytime']=time();
			$arr['paystate']=1;
			$this->db->insert(self::T_ORDERBY,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [type_insert_tuition_one 插入学费表返回id]
	 * @return [type] [description]
	 */
	function type_insert_tuition_one($data){
		if(!empty($data)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$data['userid'])->row_array();
			//先查询有没有该生该学期的记录
			$is_youmeiyou=$this->check_is_tuition($data);
			
			$max_cucasid = build_order_no ();
			$arr['nowterm']=$data['term'];
			$arr['userid']=$data['userid'];
			$arr['tuition']=$data['paid_in'];
			$arr['danwei']='rmb';
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$arr['name']=$user_info['name'];
			$arr['email']=$user_info['email'];
			$arr['nationality']=$user_info['nationality'];
			$arr['mobile']=$user_info['mobile'];
			$arr['tel']=$user_info['tel'];
			$arr['passport']=$user_info['passport'];
			$arr['paystate']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['isproof']=$data['isproof'];
			$arr['remark']=$data['remark'];
			if(!empty($is_youmeiyou)){
				$this->db->update(self::T_TUITION_INFO,$arr,'id = '.$is_youmeiyou);
				$id= $is_youmeiyou;
			}else{
				$arr['number']= $max_cucasid;

				$this->db->insert(self::T_TUITION_INFO,$arr);
				$id= $this->db->insert_id();
			}
			
			if(!empty($data['rebuild_ids'])||!empty($data['barter_card_ids'])||!empty($data['abatement'])){
				//插入补缴重修费&&换证费&&续读生补缴额度
				$this->insert_tuition_detail($data,$id);
			}
			return $id;
		}
	}
	/**
	 * [check_is_tuition 检查有没有生成这条订单]
	 * @return [type] [description]
	 */
	function check_is_tuition($data){
		if(!empty($data)){
			$this->db->where('nowterm',$data['term']);
			$this->db->where('userid',$data['userid']);
			$this->db->where('paystate',0);
			$data=$this->db->get(self::T_TUITION_INFO)->row_array();
			return $data['id'];
		}
	}
	/**
	 * [insert_tuition_detail 插入学费详细]
	 * @return [type] [description]
	 */
	function insert_tuition_detail($data,$tuitionid){
		if(!empty($data)){
			if(!empty($data['rebuild_ids'])){
				$arr['rebuild_ids']=$data['rebuild_ids'];
				$arr['rebuild_money']=$data['rebuild'];
			}
			if(!empty($data['abatement'])){
					$arr['abatement']=$data['abatement'];
				}
			if(!empty($data['barter_card_ids'])){
				$arr['barter_card_ids']=$data['barter_card_ids'];
				$arr['barter_card_money']=$data['barter_card'];
			}
			if(!empty($data['apply_discount'])){
					$arr['apply_discount']=$data['apply_discount'];
				}
			if(!empty($data['scholarship_discount'])){
					$arr['scholarship_discount']=$data['scholarship_discount'];
				}
			if(!empty($data['entry_fee'])){
					$arr['entry_fee']=$data['entry_fee'];
				}
			if(!empty($data['pledge_fee'])){
					$arr['pledge_fee']=$data['pledge_fee'];
				}
			$arr['tuitionid']=$tuitionid;
			$this->db->insert(self::T_TUITION_DETAIL,$arr);
		}

	}
	/**
	 * [insert_electric 插入电费表]
	 * @return [type] [description]
	 */
	function insert_electric($userid,$electric){
		if(!empty($userid)&&!empty($electric)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['campusid']=$udata['campid'];
			$data['buildingid']=$udata['buildingid'];
			$data['floor']=$udata['floor'];
			$data['roomid']=$udata['roomid'];
			$data['pay']=$electric;
			$data['state']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ELECTRIC_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,7,$electric);
			}
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_electric=$this->db->select('electric_money')->where('userid',$userid)->get(self::T_ACC)->row_array();
			$new_electric['electric_money']=$electric+$old_electric['electric_money'];
			$this->db->where('userid',$userid)->update(self::T_ACC,$new_electric);
		}
	}
	/**
	 * [insert_electric 插入电费表]
	 * @return [type] [description]
	 */
	function type_insert_electric($data){
		//先插入电费缴费记录表
		$ele_id=$this->type_insert_electric_one($data);
		if(!empty($ele_id)){
			//所有订单表
			$orderid=$this->insert_order($data,$tuition_id,7);
			//插入凭据表
			if($data['isproof'==1]){
				$creid=$this->insert_electric_credentials($data,$orderid);
			}
			if(!empty($orderid)){
				return $orderid;
			}		
		}
		return 0;
	}
	/**
	 * [type_insert_electric_one 插入电费缴费记录表]
	 * @return [type] [description]
	 */
	function type_insert_electric_one($data){	
		if(!empty($data)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['userid']=$data['userid'];
			$arr['ordernumber']= $max_cucasid;
			$arr['campusid']=$udata['campid'];
			$arr['buildingid']=$udata['buildingid'];
			$arr['floor']=$udata['floor'];
			$arr['roomid']=$udata['roomid'];
			$arr['pay']=$data['electric'];
			$arr['state']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			if($data['isproof']==1){
				$arr['isproof']=$data['isproof'];
				$arr['proof_path']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ELECTRIC_INFO,$arr);
			$ele_id=$this->db->insert_id();
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_electric=$this->db->select('electric_money')->where('userid = '.$data['userid'].' AND acc_state = 6')->get(self::T_ACC)->row_array();
			$new_electric['electric_money']=$data['electric']+$old_electric['electric_money'];
			$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_electric);
			return $ele_id;
		}
	}
	/**
	 * [insert_electric_credentials 电费插入所有订单表]
	 * @return [type] [description]
	 */	
	function insert_electric_credentials($data,$ele_id){
		if(!empty($data)&&!empty($ele_id)){
			$arr['orderid']=$ele_id;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['electric'];
			$arr['item']=7;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=7;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_credentials 学费插入所有的订单表]
	 * @return [type] [description]
	 */
	function insert_tuition_credentials($data,$tuitionid){
		if(!empty($data)&&!empty($tuitionid)){
			$arr['orderid']=$tuitionid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=6;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=6;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_acc 插入住宿费表]
	 * @return [type] [description]
	 */
	function insert_acc($userid,$acc){
		if(!empty($userid)&&!empty($acc)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['campusid']=$udata['campid'];
			$data['buildingid']=$udata['buildingid'];
			$data['floor']=$udata['floor'];
			$data['roomid']=$udata['roomid'];
			$data['pay']=$acc;
			$data['state']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_QUA_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,4,$acc);
			}
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc=$this->db->select('acc_money')->where('userid',$userid)->get(self::T_ACC)->row_array();
			$new_acc['acc_money']=$acc+$old_acc['acc_money'];
			$this->db->where('userid',$userid)->update(self::T_ACC,$new_acc);
		}	
	}
	/**
	 * [insert_acc 插入住宿费表]
	 * @return [type] [description]
	 */
	function insert_book($userid,$book,$book_ids){
		if(!empty($userid)){
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$userid;
			$arr['book_ids']=$book_ids;
			// $arr['payable']=$data['book_money'];
			$arr['pay']=$book;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_BOOKS_FEE,$arr);
			$id= $this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,8,$book);
			}
		}
	}
	/**
	 * [insert_acc 插入住宿费表]
	 * @return [type] [description]
	 */
	function insert_insurance($userid,$insurance){
		if(!empty($userid)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$userid;
			// $arr['insurance_ids']=$data['insurance_ids'];
			// $arr['payable']=$data['insurance_money'];
			$arr['premium']=$insurance;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$arr['majorid']=$user_info['major'];
			$arr['squadid']=$user_info['squadid'];
			$arr['term']=1;
			$squad_info=$this->db->get(self::T_SQUAD,'id = '.$user_info['squadid'])->row_array();
			$arr['effect_time']=$squad_info['classtime'];
			$this->db->insert(self::T_INSURANCE_INFO,$arr);
			$id= $this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,9,$insurance);
			}
		}
	}
	/**
	 * [insert_acc 按类型插入住宿费表]
	 * @return [type] [description]
	 */
	function type_insert_acc($data){
		//插入住宿表
		$acc_id=$this->insert_acc_fee($data);
		if(!empty($acc_id)){
			//插入所有订单表
			$orderid=$this->insert_order($data,$acc_id,4);
			if($data['isproof']==1){
					$id=$this->insert_acc_credentials($data,$orderid);
			}
			return $orderid;
		}	
		return 0;
	}
	/**
	 * [insert_acc_fee 插入住宿费表  返回插入id]
	 * @return [type] [description]
	 */
	function insert_acc_fee($data){
		if(!empty($data)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['userid']=$data['userid'];
			$arr['ordernumber']= $max_cucasid;
			$arr['campusid']=$udata['campid'];
			$arr['buildingid']=$udata['buildingid'];
			$arr['floor']=$udata['floor'];
			$arr['roomid']=$udata['roomid'];
			$arr['pay']=$data['paid_in'];
			$arr['state']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['day_fee']=$data['day'];
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['proof_number']=$data['proof_number'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_QUA_INFO,$arr);
			$acc_id=$this->db->insert_id();
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc=$this->db->select('acc_money')->where('userid',$data['userid'])->get(self::T_ACC)->row_array();
			$new_acc['acc_money']=$data['paid_in']+$old_acc['acc_money'];
			$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_acc);
			return $acc_id;
		}
	}

	function insert_acc_credentials($data,$acc_id){
		if(!empty($data)&&!empty($acc_id)){
			$arr['orderid']=$acc_id;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['acc'];
			$arr['item']=4;
			$arr['currency']='rmb';
			$arr['way']=3;
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=4;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_acc 插入住宿费押金表]
	 * @return [type] [description]
	 */
	function insert_acc_pledge($userid,$acc_pledge){
		if(!empty($userid)&&!empty($acc_pledge)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$userid)->row_array();
			$max_cucasid = build_order_no ();
			$data['userid']=$userid;
			$data['ordernumber']= $max_cucasid;
			$data['campusid']=$udata['campid'];
			$data['buildingid']=$udata['buildingid'];
			$data['floor']=$udata['floor'];
			$data['roomid']=$udata['roomid'];
			$data['pay']=$acc_pledge;
			$data['state']=1;
			$data['paytime']=time();
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_ACC_PLE_INFO,$data);
			$id=$this->db->insert_id();
			if(!empty($id)){
				$this->insert_scholarship_order($id,$userid,10,$acc_pledge);
			}
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc_pledge=$this->db->select('acc_pledge_money')->where('userid',$userid)->get(self::T_ACC)->row_array();
			$new_acc_pledge['acc_pledge_money']=$acc_pledge+$old_acc_pledge['acc_pledge_money'];
			$this->db->where('userid',$userid)->update(self::T_ACC,$new_acc_pledge);
		}	
	}
	/**
	 * [get_scholarship 判断是不是奖学金用户]
	 * @return [type] [description]
	 */
	function get_scholarship_user($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('state > 7');
			$this->db->where('isscholar',1);
			$this->db->where('scholorshipid <> ""');
			$data=$this->db->get(self::T_APP)->row_array();
			if(!empty($data)){
				return $data;
			}
		}
		return array();
	}
	/**
	 * [get_scholarship_info 获取奖学金的信息]
	 * @return [type] [description]
	 */
	function get_scholarship_info($is_scholarship=array()){
		if(!empty($is_scholarship)){
			$this->db->where('id',$is_scholarship['scholorshipid']);
			return $this->db->get(self::T_SCH_INFO)->row_array();
		}
		return array();
	}
	/**
	 * [get_major_info 获取该学生所在的专业信息]
	 * @return [type] [description]
	 */
	function get_major_info($userid){
		if(!empty($userid)){
			//用户信息
			$user_info=$this->db->get_where(self::T_STUDENT,'userid = '.$userid)->row_array();
			//专业信息
			$this->db->where('id ',$user_info['majorid']);
			$major_info=$this->db->get(self::T_MAJOR)->row_array();
			if(!empty($major_info)){
				return $major_info;
			}
		}
		return array();
	}
	/**
	 * [get_term_tuition 获取当前学期的学费]
	 * @return [type] [description]
	 */
	function get_term_tuition($majorid,$term){
		if(!empty($majorid)&&!empty($term)){
			$this->db->where('majorid = '.$majorid.' AND term = '.$term);
			$info=$this->db->get(self::T_MAJOR_TUITION)->row_array();
			if(!empty($info)){
				return $info;
			}
		}
		return 0;
	}
	/**
	 * [is_tuition 判断该学期的学费交没交]
	 * @return boolean [description]
	 */
	function is_tuition($majorid,$term,$userid){
		if(!empty($majorid)&&!empty($term)){
			$this->db->select('count(*) as num');
			$this->db->where('nowterm = '.$term.' AND userid = '.$userid);
			$this->db->where('paystate',1);
			$info=$this->db->get(self::T_TUITION_INFO)->row_array();
			if(!empty($info['num'])){
				return $info['num'];
			}
		}
		return 0;
	}
	/**
	 * [get_typeacc_info 获取该学生的订房信息]
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_typeacc_info($userid){
		if(!empty($userid)){
			$this->db->select('accommodation_info.*,school_accommodation_campus.name as cname,school_accommodation_buliding.name as bname,school_accommodation_prices.name as rname,school_accommodation_prices.prices as dayprices');
			$this->db->join(self::T_PRICES ,self::T_PRICES.'.id='.self::T_ACC.'.roomid');
			$this->db->join(self::T_BULIDING ,self::T_BULIDING.'.id='.self::T_ACC.'.buildingid');
			$this->db->join(self::T_CAMPUS ,self::T_CAMPUS.'.id='.self::T_ACC.'.campid');
			$this->db->where('accommodation_info.userid',$userid);
			$this->db->where('accommodation_info.acc_state <>',4);
			$this->db->where('accommodation_info.acc_state <>',7);
			$udata=$this->db->get(self::T_ACC)->row_array();
			return $udata;
		}
		return array();
		
	}
	/**
	 * 查看在学的学生信息
	 */
	function get_student_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
	}
	//查找该学生在缴费学期下有没有重修费用
	function get_rebuild($term,$userid){
		if(!empty($term)&&!empty($userid)){
			$this->db->where('term',$term);
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT_REBUILD)->result_array();
		}
	}
	//查找该学生在缴费学期下有没有换证的费用
	
	function get_barter_card($term,$userid){
		if(!empty($term)&&!empty($userid)){
			$this->db->where('term',$term);
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STU_BAR_CARD)->result_array();
		}
	}
	/**
	 * [get_major_course 获取该专业的所有课程]
	 * @return [type] [description]
	 */
	function get_major_course($mid){
		if(!empty($mid)){
			$this->db->where('majorid',$mid);
			$this->db->join(self::T_COURSE ,self::T_MAJOR_COURSE.'.courseid='.self::T_COURSE.'.id');
			return $this->db->get(self::T_MAJOR_COURSE)->result_array();
		}
	}
	/**
	 * [get_course_book 获取该课程的书籍]
	 * @return [type] [description]
	 */
	function get_course_book($courseid){
		if(!empty($courseid)){
			$this->db->where('courseid',$courseid);
			return $this->db->get(self::T_COURSE_BOOKS)->result_array();
		}
	}
	/**
	 * [get_book_info 获取书籍信息]
	 * @return [type] [description]
	 */
	function get_book_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_BOOKS)->row_array();
		}
	}
	/**
	 * [type_insert_book_fee 按类型交书费]
	 * @return [type] [description]
	 */
	function type_insert_book_fee($data){
		if(!empty($data)){
			//先插入书费表
			$book_feeid=$this->insert_book_fee($data);
			if(!empty($book_feeid)){
				//插入所有订单表
				$orderid=$this->insert_order($data,$book_feeid,8);
				if($data['isproof']==1){
					$id=$this->insert_book_credentials($data,$orderid);
				}
				//插入凭据表
				return $orderid;
			}
		}
		return 0;
	}
	/**
	 * [insert_book_fee 插入书费表]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function insert_book_fee($data){
		if(!empty($data)){
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['book_ids']=$data['book_ids'];
			$arr['payable']=$data['book_money'];
			$arr['pay']=$data['paid_in'];
			$arr['paytime']=time();
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['proof_number']=$data['proof_number'];
				$arr['isproof']=$data['isproof'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_BOOKS_FEE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_book_credentials 书费插入所有订单表]
	 * @return [type] [description]
	 */
	function insert_book_credentials($data,$book_feeid){
		if(!empty($data)&&!empty($book_feeid)){
			$arr['orderid']=$book_feeid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=8;
			$arr['currency']='rmb';
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=8;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [type_insert_insurance_fee 插入保险费]
	 * @return [type] [description]
	 */
	function type_insert_insurance_fee($data){
		if(!empty($data)){
			//先插入书费表
			$insurance_feeid=$this->insert_insurance_fee($data);
			if(!empty($insurance_feeid)){
				//插入所有订单表
				$orderid=$this->insert_order($data,$insurance_feeid,9);
				if($data['isproof']==1){
					$id=$this->insert_insurance_credentials($data,$orderid);
				}
				//插入凭据表
				return $orderid;
			}
		}
		return 0;
	}
	/**
	 * [insert_insurance_fee 插入保险费表]
	 * @return [type] [description]
	 */
	function insert_insurance_fee($data){
		if(!empty($data)){
			$user_info=$this->db->get(self::T_STUDENT,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['studentid']=$user_info['id'];
			$arr['payable']=$data['payable'];
			$arr['premium']=$data['paid_in'];
			$arr['effect_time']=$data['effect_time'];
			$arr['majorid']=$user_info['major'];
			$arr['squadid']=$user_info['squadid'];
			$arr['paytime']=time();
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['proof_number']=$data['proof_number'];
				$arr['isproof']=$data['isproof'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_INSURANCE_INFO,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * 插入保险费凭据表
	 */
	function insert_insurance_credentials($data,$orderid){
		if(!empty($data)&&!empty($orderid)){
			$arr['orderid']=$orderid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=9;
			$arr['currency']='rmb';
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=9;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [type_insert_acc_pledge 按类别交住宿押金]
	 * @return [type] [description]
	 */
	function type_insert_acc_pledge($data){
		if(!empty($data)){
			//先插入押金表
			$acc_pledge_feeid=$this->insert_acc_pledge_fee($data);
			if(!empty($acc_pledge_feeid)){
				//插入所有订单表
				$orderid=$this->insert_order($data,$acc_pledge_feeid,10);
				if($data['isproof']==1){
					$id=$this->insert_acc_pledge_credentials($data,$orderid);
				}
				//插入凭据表
				return $orderid;
			}
		}
	}
	/**
	 * [insert_acc_pledge_fee 插入住宿押金表]
	 * @return [type] [description]
	 */
	function insert_acc_pledge_fee($data){
		if(!empty($data)){
			$udata=$this->db->get(self::T_ACC,'userid = '.$data['userid'])->row_array();
			$max_cucasid = build_order_no ();
			$arr['userid']=$data['userid'];
			$arr['ordernumber']= $max_cucasid;
			$arr['campusid']=$udata['campid'];
			$arr['buildingid']=$udata['buildingid'];
			$arr['floor']=$udata['floor'];
			$arr['roomid']=$udata['roomid'];
			$arr['payable']=$data['payable'];
			$arr['pay']=$data['paid_in'];
			$arr['state']=1;
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['adminid']=$_SESSION['master_user_info']->id;
			if($data['isproof']==1){
				$arr['isproof']=$data['isproof'];
				$arr['proof_number']=$data['proof_number'];
				$arr['proof_path']=$data['file'];
			}
			$this->db->insert(self::T_ACC_PLE_INFO,$arr);
			$acc_id=$this->db->insert_id();
			//更新到一直跑着的住宿订单表里的电费字段
			//获取原先的电费金钱
			$old_acc=$this->db->select('acc_pledge_money')->where('userid',$data['userid'])->get(self::T_ACC)->row_array();
			$new_acc['acc_pledge_money']=$data['paid_in']+$old_acc['acc_pledge_money'];
			$this->db->where('userid',$data['userid'])->update(self::T_ACC,$new_acc);
			return $acc_id;
		}
	}
	/**
	 * [insert_acc_pledge_credentials 插入住宿押金平局表]
	 * @return [type] [description]
	 */
	function insert_acc_pledge_credentials($data,$orderid){
		if(!empty($data)&&!empty($orderid)){
			$arr['orderid']=$orderid;
			$max_cucasid = build_order_no ();
			$arr['ordernumber']= $max_cucasid;
			$arr['userid']=$data['userid'];
			$arr['amount']=$data['paid_in'];
			$arr['item']=10;
			$arr['currency']='rmb';
			$arr['state']=1;
			$arr['remark']=$data['remark'];
			$arr['createtime']=time();
			$arr['ordertype']=10;
			if($data['isproof']==1){
				$arr['number']=$data['proof_number'];
				$arr['file']=$data['file'];
			}
			$arr['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [get_user_jiaoguo 获取交没交过奖学金]
	 * @return [type] [description]
	 */
	function get_user_jiaoguo($userid){
		if(!empty($userid)){
			$this->db->select('is_scholarship');
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			$data=$this->db->get(self::T_ACC)->row_array();
			if(!empty($data)){
				return $data['is_scholarship'];
			}
		}
		return 0;
	}
	/**
	 * [check_is_jiangxuejin 查看用户是否奖学金用户]
	 * @return [type] [description]
	 */
	function check_is_jiangxuejin($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('state >=',7);
			return $this->db->get(self::T_APP)->row_array();
		}
	}
	/**
	 * [check_is_jiangxuejin_tg 查看奖学金是否通过]
	 * @return [type] [description]
	 */
	function check_is_jiangxuejin_tg($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('state',5);
			return $this->db->get(self::T_J_X_J_APP)->row_array();
		}
	}
	/**
	 * [get_jiangxuejin_info查看奖学金的信息]
	 * @return [type] [description]
	 */
	function get_jiangxuejin_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_SCH_INFO)->row_array();
		}
	}
	/**
	 * [get_apply_tuition_discount 获取申请的时候有没有学费折扣]
	 * @return [type] [description]
	 */
	function get_apply_tuition_discount($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('is_tuition_discount',0);
			$this->db->where('state >=',7);
			return $this->db->get(self::T_APP)->row_array();
		}
	}
	/**
	 * [get_major_info_one 获取专业的一条信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_major_info_one($id){
		if(!empty($id)){
			//专业信息
			$this->db->where('id ',$id);
			$major_info=$this->db->get(self::T_MAJOR)->row_array();
			return $major_info;
		}
		return array();
	}
	/**
	 * [get_major_info_one 获取专业的一条信息]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_apply_info_one($userid){
		if(!empty($userid)){
			//专业信息
			$this->db->where('userid ',$userid);
			$this->db->where('state >=',7);
			$major_info=$this->db->get(self::T_APP)->row_array();
			return $major_info;
		}
		return array();
	}

	/**
	 * [get_deposit_info 查看申请的时候交没交押金]
	 * @return [type] [description]
	 */
	function get_deposit_info($aid,$userid){
		if(!empty($aid)&&!empty($userid)){
			$this->db->where('applyid',$aid);
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_DEPOSIT_INFO)->row_array();
		}
	}
	/**
	 * [ultimate_pay 终极缴费不改版]
	 * @return [type] [description]
	 */
	function ultimate_pay($data){

		if(!empty($data)){
			if($data['type']=='acc'){
				$this->acc_pledge($data);
			}
			if($data['type']=='acc_pledge'){
				$this->acc_pledge_yajin($data);
			}
			if($data['type']=='insurance'){
				$this->insurance($data);
			}
			if($data['type']=='book'){
				$this->book($data);
			}
			if($data['type']=='electric_pledge'){
				$this->electric_pledge($data);
			}
			if($data['type']=='bedding'){
				$this->bedding($data);
			}
			if($data['type']=='rebuild'){
				$this->rebuild($data);
			}
			if($data['type']=='barter_card'){
				$this->barter_card($data);
			}
			if($data['type']=='apply'){
				$this->apply($data);
			}
			if($data['type']=='pledge'){
				$this->pledge($data);
			}
			if($data['type']=='electric'){
				$this->electric($data);
			}
			// var_dump($data);exit;
			//交学费
			if($data['type']=='tuition'){
				$this->tuition($data);				
			}
		}
	}
	/**
	 * [delete_arr 有在线删除的就删]
	 * @param  [type] $type [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function delete_arr($type,$data){
		if(!empty($type)&&!empty($data)){

		}
	}
	/**
	 * [tuition 交学费]
	 * @return [type] [description]
	 */
	function tuition($data){
		if(!empty($data)){
			//查询有没有之前生成的
			$befor_info=$this->db->get_where('budget','userid = '.$data['userid'].' AND type = 6 AND term = '.$data['term'].' AND paystate = 0')->row_array();
			if(!empty($befor_info)){
				//删除收支表
				$this->db->delete('budget','id = '.$befor_info['id']);
				//删除订单表先查询在不在
				$order_info=$this->db->get_where('apply_order_info','budget_id = '.$befor_info['id'])->row_array();
				if(!empty($order_info)){
					$this->db->delete('apply_order_info','budget_id = '.$befor_info['id']);
				}
				//删除学费表
				$before_tui=$this->db->get_where('tuition_info','order_id = '.$order_info['id'])->row_array();
				if(!empty($before_tui)){
					$this->db->delete('tuition_info','order_id = '.$order_info['id']);
				}
			}
			//插入收支表
			$budgetid=$this->pub_save_budget(6,$data);
			//查询有没有有就删除
			// $this->delete_arr(6,$data);
			if(!empty($budgetid)){
				//插入收费类型表
				$typeid=$this->pub_save_typetable(6,$budgetid,$data);
				//插入学费表
				$tuition=array(
					'budgetid'=>$budgetid,
					'nowterm'=>$data['term'],
					'userid'=>$data['userid'],
					'tuition'=>$data['paid_in'],
					'danwei'=>'rmb',
					'paystate'=>1,
					'paytime'=>time(),
					'paytype'=>$data['paytype'],
					'createtime'=>time(),
					'lasttime'=>time(),
					'adminid'=>$_SESSION['master_user_info']->id
					);
				$this->db->insert('tuition_info',$tuition);
				$tuitionid=$this->db->insert_id();
				//插入换证费
				if(!empty($data['barter_card_ids'])){
					foreach ($data['barter_card_ids'] as $k => $v) {
						$info=$this->db->get_where('student_barter_card','id = '.$v)->row_array();
						//更新条记录
						$this->db->update('student_barter_card',array('tuitionid'=>$tuitionid,'state'=>1,'paytime'=>time()),'id = '.$v);
						//更新收支表
						$this->db->update('budget',array('paid_in'=>$info['money'],'paystate'=>1,'paytime'=>time(),'paytype'=>$data['paytype']),'id = '.$info['budgetid']);
						//保持平衡
						$tui_budget=array(
							'userid'=>$data['userid'],
							'term'=>$data['term'],
							'budget_type'=>2,
							'type'=>11,
							'should_returned'=>$info['money'],
							'true_returned'=>$info['money'],
							'returned_time'=>time(),
							'createtime'=>time(),
							'adminid'=>$_SESSION['master_user_info']->id
							);
						$this->db->insert('budget',$tui_budget);
					}
				}
				//插入重修费
				if(!empty($data['rebuild_ids'])){
					foreach ($data['rebuild_ids'] as $k => $v) {
						$info=$this->db->get_where('student_rebuild','id = '.$v)->row_array();
						//更新条记录
						$this->db->update('student_rebuild',array('tuitionid'=>$tuitionid,'state'=>1,'paytime'=>time()),'id = '.$v);
						//更新收支表
						$this->db->update('budget',array('paid_in'=>$info['money'],'paystate'=>1,'paytime'=>time(),'paytype'=>$data['paytype']),'id = '.$info['budgetid']);
						//保持平衡
						$tui_budget=array(
							'userid'=>$data['userid'],
							'term'=>$data['term'],
							'budget_type'=>2,
							'type'=>12,
							'should_returned'=>$info['money'],
							'true_returned'=>$info['money'],
							'returned_time'=>time(),
							'createtime'=>time(),
							'adminid'=>$_SESSION['master_user_info']->id
							);
						$this->db->insert('budget',$tui_budget);
					}
				}

			}
		}
	}
	/**
	 * [update_budget 更新收支表]
	 * @return [type] [description]
	 */
	function update_budget($id,$arr){
		if($id!=null){
			$this->db->update(self::T_BUDGET,$arr,'id = '.$id);
		}else{
			$this->db->insert(self::T_BUDGET,$arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [insert_credentials 插入凭据表]
	 * @return [type] [description]
	 */
	function save_credentials($id=null,$arr){
		if($id==null){
			$this->db->insert(self::T_CRE,$arr);
			return $this->db->insert_id();
		}else{
			//更新凭据表
		}
	}
	/**
	 * [save_cash description]
	 * @return [type] [description]
	 */
	function save_cash($id=null,$arr){
		if($id==null){
			$this->db->insert(self::T_CASH,$arr);
			return $this->db->insert_id();
		}else{
			//更新现金表
		}
	}
	/**
	 * [acc_pledge 交住宿费]
	 * @return [type] [description]
	 */
	function acc_pledge($data){
		if(!empty($data)){
			//插入收支表
			$budgetid=$this->pub_save_budget(4,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(4,$budgetid,$data);
			//查询住宿的信息
			$acc_info=$this->db->get_where('accommodation_info','id = '.$data['acc_id'])->row_array();
		   //插入住宿费交费详细表
            $acc_fee=array(
                'budgetid'=>$budgetid,
                'acc_id'=>$acc_info['id'],
                'day'=>$data['day'],
                'menoy'=>$data['paid_in'],
                'paystate'=>1,
                'paytype'=>$data['paytype'],
                'paytime'=>time(),
                'createtime'=>time(),
                'adminid'=>$_SESSION ['master_user_info']->id
            );
            $this->db->insert('acc_fee',$acc_fee);
			if($acc_info['paystate']!=1){
				$acc_arr=array(
					'accendtime'=>$data['day'],
					'paystate'=>1,
					'paytime'=>time(),
					'paytype'=>$data['paytype'],
					);
					$this->db->update('accommodation_info',$acc_arr,'id = '.$acc_info['id']);
			}else{
				//修改住宿表的费用和日期
				$acc_arr=array(
						'accendtime'=>$acc_info['accendtime']+$data['day'],

					);
				$this->db->update('accommodation_info',$acc_arr,'id = '.$acc_info['id']);
			}
			

		}
	}

	/**
	 * 公共插入收支表
	 */
	function pub_save_budget($type=0,$data=array()){
		if(empty($data['term'])){
			//获取学生的学期
			$student_info=$this->db->get_where('student','userid = '.$data['userid'])->row_array();
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				if(!empty($squad_info['nowterm'])){
					$data['term']=$squad_info['nowterm'];
				}
			}else{
				$data['term']=1;
			}
		}
		
		//没有收支表插收支表
		$budget_arr=array(
				'userid'=>$data['userid'],
				'budget_type'=>1,
				'type'=>$type,
				'term'=>$data['term'],
				'payable'=>$data['last_money'],
				'paid_in'=>$data['paid_in'],
				'paystate'=>1,
				'paytime'=>time(),
				'paytype'=>$data['paytype'],
				'createtime'=>time(),
				'adminid'=>$_SESSION['master_user_info']->id,
				'proof_number'=>$data['proof_number'],
				'file_path'=>$data['file_path'],
				'remark'=>$data['remark']
			);
		if(!empty($data['applyid'])){
			$budget_arr['applyid']=$data['applyid'];
		}
		$budgetid=$this->update_budget(null,$budget_arr);
		return $budgetid;
	}
	/**
	 * [pub_save_typetable 公共插入类型表]
	 * @return [type] [description]
	 */
	function pub_save_typetable($type,$budgetid,$data){
		if(!empty($budgetid)&&!empty($data)){
			if($data['paytype']==3){
				$cre_arr=array(
		 			'budgetid'=>$budgetid,
		 			'userid'=>$data['userid'],
		 			'amount'=>$data['paid_in'],
		 			'item'=>$type,
		 			'currency'=>1,
		 			'state'=>1,
		 			'createtime'=>time(),
		 			'ordertype'=>$type,
		 			'file'=>$data['file_path'],
	 				'number'=>$data['proof_number'],
	 				'state'=>1,
	 				'remark'=>$data['remark'],
	 				'updateuser'=>$_SESSION['master_user_info']->id,
	 				'updatetime'=>time(),
	 				'adminid'=>$_SESSION['master_user_info']->id
		 			);
		 		$this->save_credentials(null,$cre_arr);
			}
		}
		
	}
	/**
	 * [insurance 终极交保险费]
	 * @return [type] [description]
	 */
	function insurance($data){
		if(!empty($data)){
			//插入收支表
			$budgetid=$this->pub_save_budget(9,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(9,$budgetid,$data);
			//插入保险信息
			$insurance_arr=array(
				'budgetid'=>$budgetid,
				'payable'=>$data['last_money'],
				'paid_in'=>$data['paid_in'],
				//'deadline'=>$data['deadline'],
				'term'=>$data['term'],
				'student_type'=>$data['student_type'],
				'effect_time'=>strtotime($data['effect_time']),
				'userid'=>$data['userid'],
				'paystate'=>1,
				'paytime'=>time(),
				'remark'=>$data['remark'],
				'createtime'=>time(),
				'adminid'=>$_SESSION['master_user_info']->id
				);
			$this->db->insert('insurance_info',$insurance_arr);
		}
	}
	/**
	 * [book 交书费]
	 * @return [type] [description]
	 */
	function book($data){
		if(!empty($data)){
			//查看之前有没有预订书  有的话就删除
    		$before_info=$this->db->get_where('budget','term = '.$data['term'].' AND userid = '.$data['userid'].' AND type = 8 AND paystate = 0')->row_array();
    		if(!empty($before_info)){
    			$this->db->delete('apply_order_info','budget_id = '.$before_info['id']);
    			$this->db->delete('books_fee','budgetid = '.$before_info['id']);
    			$this->db->delete('budget','id = '.$before_info['id']);
    		}
			//插入收支表
			$budgetid=$this->pub_save_budget(8,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(8,$budgetid,$data);
			//插入书费表
			if(!empty($budgetid)){
				$book_fee_id=$this->insert_book_fee_zhongji($budgetid,$data);
				return $book_fee_id;
			}
		}
	}
	/**
	 * [insert_book_fee_zhongji 终极不改版插入书费表]
	 * @return [type] [description]
	 */
	function insert_book_fee_zhongji($budgetid=null,$data=array()){
		if(!empty($data)&&$budgetid!=null){
			$book_ids='';
			if(!empty($data['ids'])){
				foreach ($data['ids'] as $k => $v) {
					$book_ids.=$v.',';
				}
			}
			$insert_data['userid']=$data['userid'];
			$insert_data['budgetid']=$budgetid;
			$insert_data['term']=$data['term'];
			$insert_data['book_ids']=trim($book_ids,',');
			$insert_data['last_money']=$data['last_money'];
			$insert_data['paid_in']=$data['paid_in'];
			$insert_data['paystate']=1;
			$insert_data['paytime']=time();
			$insert_data['remark']=$data['remark'];
			$insert_data['createtime']=time();
			$insert_data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_BOOKS_FEE,$insert_data);
			return $this->db->insert_id();
		}
	}
	/**
	 * [book 电费押金]
	 * @return [type] [description]
	 */
	function electric_pledge($data){
		if(!empty($data)){
			$info=$this->db->get_where('budget','userid = '.$data['userid'].' AND type = 14 AND paystate = 0')->row_array();
			if(!empty($info)){
				//先查询再删除
				$df=$this->db->get_where('electric_pledge','budgetid = '.$info['id'])->row_array();
				if(!empty($df)){
					$this->db->delete('electric_pledge','budgetid = '.$info['id']);
				}
				//查询订单表
				$dd=$this->db->get_where('apply_order_info','budget_id = '.$info['id'])->row_array();
				if(!empty($dd)){
					$this->db->delete('apply_order_info','budget_id = '.$info['id']);
				}
				$this->db->delete('budget','id = '.$info['id']);

			}
			//插入收支表
			$budgetid=$this->pub_save_budget(14,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(14,$budgetid,$data);
			//插入书费表
			if(!empty($budgetid)){
				$electric_pledge_id=$this->insert_electric_pledge_zhongji($budgetid,$data);
				return $electric_pledge_id;
			}
		}
	}
	/**
	 * [insert_electric_pledge_zhongji 插入电费押金表]
	 * @return [type] [description]
	 */
	function insert_electric_pledge_zhongji($budgetid=null,$data=array()){
		if(!empty($data)&&$budgetid!=null){
			$insert_data=array(
					'budgetid'=>$budgetid,
					'userid'=>$data['userid'],
					'last_money'=>$data['last_money'],
					'paid_in'=>$data['paid_in'],
					'paystate'=>1,
					'paytime'=>time(),
					'createtime'=>time(),
					'adminid'=>$_SESSION['master_user_info']->id
				);
		}
		$this->db->insert(self::T_ELECTRIC_PLEDGE,$insert_data);
		return $this->db->insert_id();
	}
	/**
	 * [bedding 交床品费]
	 * @return [type] [description]
	 */
	function bedding($data){
		if(!empty($data)){
			//插入收支表
			$budgetid=$this->pub_save_budget(13,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(13,$budgetid,$data);
			//插入书费表
			if(!empty($budgetid)){
				$bedding_id=$this->insert_bedding_zhongji($budgetid,$data);
				return $bedding_id;
			}
		}
	}
	/**
	 * [insert_bedding_zhongji 插入床品费表]
	 * @return [type] [description]
	 */
	function insert_bedding_zhongji($budgetid=null,$data=array()){
		if(!empty($data)&&$budgetid!=null){
			$insert_data=array(
					'budgetid'=>$budgetid,
					'userid'=>$data['userid'],
					'last_money'=>$data['last_money'],
					'paid_in'=>$data['paid_in'],
					'paystate'=>1,
					'paytime'=>time(),
					'createtime'=>time(),
					'adminid'=>$_SESSION['master_user_info']->id
				);
		}
		$this->db->insert(self::T_BEDDIND_FEE,$insert_data);
		return $this->db->insert_id();
	}
	/**
	 * [rebuild 插入重修表]
	 * @return [type] [description]
	 */
	function rebuild($data){
		if(!empty($data)){
			//获取重修费id
			$ids=explode(',', $data['ids']);
			if(!empty($ids)){
				foreach ($ids as $k => $v) {
					//更新重修费表
					$this->update_rebuild($v);
					//获取该重修费的收支id
					$rebuild_info=$this->db->get_where(self::T_STUDENT_REBUILD,'id = '.$v)->row_array();
					$data['paid_in']=$rebuild_info['money'];
					//更新收支表
					$arr=array(
						'paid_in'=>$data['paid_in'],
						'paystate'=>1,
						'paytime'=>time(),
						'paytype'=>$data['paytype'],
						'remark'=>$data['remark']
						);
					$this->db->update(self::T_BUDGET,$arr,'id = '.$rebuild_info['budgetid']);
					$typeid=$this->pub_save_typetable(12,$rebuild_info['budgetid'],$data);
				}
				
			}
			
		}
	}
	/**
	 * [update_rebuild 更新重修表]
	 * @return [type] [description]
	 */
	function update_rebuild($id){
		if(!empty($id)){
			$arr=array(
				'state'=>1,
				'paytime'=>time(),
				);
			$this->db->update(self::T_STUDENT_REBUILD,$arr,'id = '.$id);
		}
	}
	/**
	 * [rebuild 插入换证表]
	 * @return [type] [description]
	 */
	function barter_card($data){
		if(!empty($data)){
			//获取重修费id
			$ids=explode(',', $data['ids']);
			if(!empty($ids)){
				foreach ($ids as $k => $v) {
					//更新重修费表
					$this->update_barter_card($v);
					//获取该重修费的收支id
					$rebuild_info=$this->db->get_where(self::T_STU_BAR_CARD,'id = '.$v)->row_array();
					$data['paid_in']=$rebuild_info['money'];
					//更新收支表
					$arr=array(
						'paid_in'=>$data['paid_in'],
						'paystate'=>1,
						'paytime'=>time(),
						'paytype'=>$data['paytype'],
						'remark'=>$data['remark']
						);
					$this->db->update(self::T_BUDGET,$arr,'id = '.$rebuild_info['budgetid']);
					$typeid=$this->pub_save_typetable(12,$rebuild_info['budgetid'],$data);
				}
				
			}
			
		}
	}
	/**
	 * [update_rebuild 更新换证表]
	 * @return [type] [description]
	 */
	function update_barter_card($id){
		if(!empty($id)){
			$arr=array(
				'state'=>1,
				'paytime'=>time(),
				);
			$this->db->update(self::T_STUDENT_REBUILD,$arr,'id = '.$id);
		}
	}
	/**
	 * [get_user_apply_info 获取该用户没有交申请费的记录]
	 * @return [type] [description]
	 */
	function get_user_apply_info($userid){
		if(!empty($userid)){
			$this->db->select('apply_info.*,major.name');
			$this->db->where('apply_info.userid',$userid);
			$this->db->where('apply_info.paystate <> 1');
			$this->db->where('apply_info.state <> 4');
			$this->db->where('apply_info.state <> 9');
			$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_APP.'.courseid');
			$data= $this->db->get(self::T_APP)->result_array();
			if(!empty($data)){
				foreach ($data as $k => $v) {
					$data[$k]['applytime']=date('Y-m-d',$v['applytime']);
				}
			}
			return $data;
		}
		return array();
	}
	/**
	 * [apply 插入申请费]
	 * @return [type] [description]
	 */
	function apply($data){
		//先判断收支表有没有记录
		 $is_budget=$this->db->get_where(self::T_BUDGET,'type =1 AND applyid = '.$data['applyid'])->row_array();
		 if(!empty($is_budget)){
		 	//更新收支表  这是大概有生成的订单表
		 	$budgetid=$is_budget['id'];
		 	$update_budget=array(
		 		'paid_in'=>$data['paid_in'],
		 		'paystate'=>1,
		 		'paytime'=>time(),
		 		'paytype'=>$data['paytype'],
		 		'lasttime'=>time(),
		 		'remark'=>$data['remark']
		 		);
		 	$this->db->update(self::T_BUDGET,$update_budget,'id = '.$budgetid);
		 	//查询所有订单  如有有的话就删除
		 	$order_info=$this->db->get_where(self::T_ORDERBY)->row_array();
		 	if(!empty($order_info)){	
		 		$this->db->delete(self::T_ORDERBY,'id = '.$order_info['id']);
		 	}
		 }else{
		 	//插入收支表
			$budgetid=$this->pub_save_budget(1,$data);
		 }
			
			//插入收费类型表
			$typeid=$this->pub_save_typetable(1,$budgetid,$data);
			//更新申请表
			if(!empty($budgetid)){
				$update_app=array(
					'danwei'=>1,
					'paystate'=>1,
					'paytime'=>time(),
					'paytype'=>$data['paytype'],
					);
				if($data['paytype']==3){
					$update_app['isproof']=1;
				}
				$this->db->update(self::T_APP,$update_app,'id = '.$data['applyid']);
				return $budgetid;
			}
	}
	/**
	 * [get_user_apply_pledge_info 获取交入学押金的记录]
	 * @return [type] [description]
	 */
	function get_user_apply_pledge_info($userid){
		if(!empty($userid)){
			$this->db->select('apply_info.*,major.name');
			$this->db->where('apply_info.userid',$userid);
			$this->db->where('apply_info.deposit_state <> 1');
			$this->db->where('apply_info.state <> 4');
			$this->db->where('apply_info.state <> 9');
			$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_APP.'.courseid');
			$data= $this->db->get(self::T_APP)->result_array();
			if(!empty($data)){
				foreach ($data as $k => $v) {
					$data[$k]['applytime']=date('Y-m-d',$v['applytime']);
				}
			}
			return $data;
		}
		return array();
	}
	/**
	 * [pledge 插入入学押金]
	 * @return [type] [description]
	 */
	function pledge($data){

                $tui_dep=CF('tuition','',CONFIG_PATH);
                if(!empty($tui_dep['pledgejiru'])&&$tui_dep['pledgejiru']=='yes'){
                    //开始计入学费1插入收支表2插入学费表
                    //没有收支表插收支表
                    $budget_arr=array(
                            'userid'=>$data['userid'],
                            'budget_type'=>1,
                            'type'=>6,
                            'term'=>1,
                            'payable'=>$data['paid_in'],
                            'paid_in'=>$data['paid_in'],
                            'paystate'=>1,
                            'paytime'=>time(),
                            'paytype'=>3,
                            'createtime'=>time(),
                            'adminid'=>$_SESSION['master_user_info']->id,
                            'proof_number'=>'',
                            'file_path'=>'',
                            'remark'=>'押金计入学费'
                        );
                    $this->db->insert('budget',$budget_arr);
                    $bgetid=$this->db->insert_id();
                    //插入学表
                    $tuition_arr=array(
                        'budgetid'=>$bgetid,
                        'nowterm'=>1,
                        'userid'=>$data['userid'],
                        'tuition'=>$data['paid_in'],
                        'danwei'=>'rmb',
                        'paystate'=>1,
                        'paytime'=>time(),
                        'paytype'=>8,
                        'createtime'=>time(),
                        'remark'=>'押金计入学费',
                        'lasttime'=>time(),
                        'adminid'=>$_SESSION['master_user_info']->id,
                        );  
                    $this->db->insert('tuition_info',$tuition_arr);
                    //插入一个退费表
                    //没有收支表插收支表
                    $budget_arr=array(
                            'userid'=>$data['userid'],
                            'budget_type'=>2,
                            'type'=>6,
                            'term'=>1,
                            'should_returned'=>$data['paid_in'],
                            'true_returned'=>$data['paid_in'],
                            'paystate'=>1,
                            'returned_time'=>time(),
                            'paytype'=>3,
                            'createtime'=>time(),
                            'adminid'=>$_SESSION['master_user_info']->id,
                            'proof_number'=>'',
                            'file_path'=>'',
                            'remark'=>'押金计入学费'
                        );
                    $this->db->insert('budget',$budget_arr);
                              
                }

		//先判断收支表有没有记录
		 $is_budget=$this->db->get_where(self::T_BUDGET,'type =5 AND applyid = '.$data['applyid'])->row_array();
		 if(!empty($is_budget)){
		 	//更新收支表  这是大概有生成的订单表
		 	$budgetid=$is_budget['id'];
		 	$update_budget=array(
		 		'paid_in'=>$data['paid_in'],
		 		'paystate'=>1,
		 		'paytime'=>time(),
		 		'paytype'=>$data['paytype'],
		 		'lasttime'=>time(),
		 		'remark'=>$data['remark']
		 		);
		 	$this->db->update(self::T_BUDGET,$update_budget,'id = '.$budgetid);
		 	//查询所有订单  如有有的话就删除
		 	$order_info=$this->db->get_where(self::T_ORDERBY)->row_array();
		 	if(!empty($order_info)){	
		 		$this->db->delete(self::T_ORDERBY,'id = '.$order_info['id']);
		 	}
		 }else{
		 	//插入收支表
			$budgetid=$this->pub_save_budget(5,$data);
		 }
			
			//插入收费类型表
			$typeid=$this->pub_save_typetable(5,$budgetid,$data);
			//更新申请表
			if(!empty($budgetid)){
				$update_app=array(
					'danwei'=>1,
					'deposit_state'=>1,
					'deposit_time'=>time(),
					'deposit_type'=>$data['paytype'],
					);
				if($data['paytype']==3){
					$update_app['isproof']=1;
				}
				$this->db->update(self::T_APP,$update_app,'id = '.$data['applyid']);
				return $budgetid;
			}
	}
	/**
	 * [electric 交电费]
	 * @return [type] [description]
	 */
	function electric($data){
		if(!empty($data)){
			//插入收支表
			$budgetid=$this->pub_save_budget(7,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(7,$budgetid,$data);
			//插入书费表
			if(!empty($budgetid)){
				$electric_id=$this->insert_electric_zhongji($budgetid,$data);
				return $electric_id;
			}
		}
	}
	/**
	 * [insert_electric_zhongji 插入电费表]
	 * @return [type] [description]
	 */
	function insert_electric_zhongji($budgetid,$data){
		if(!empty($budgetid)&&!empty($data)){
			$insert_arr=array(
				'budgetid'=>$budgetid,
				'userid'=>$data['userid'],
				'paid_in'=>$data['paid_in'],
				'paystate'=>1,
				'paytype'=>$data['paytype'],
				'paytime'=>time(),
				'createtime'=>time(),
				'adminid'=>$_SESSION['master_user_info']->id
				);
			$this->db->insert(self::T_ELECTRIC_INFO,$insert_arr);
			return $this->db->insert_id();
		}
	}
	/**
	 * [acc_pledge_yajin 交住宿押金]
	 * @return [type] [description]
	 */
	function acc_pledge_yajin($data){
		if(!empty($data)){
			$info=$this->db->get_where('budget','userid = '.$data['userid'].' AND type = 10 AND paystate = 0')->row_array();
			if(!empty($info)){
				$this->db->delete('apply_order_info','budget_id = '.$info['id']);
				$this->db->delete('acc_pledge_info','budgetid = '.$info['id']);
				$this->db->delete('acc_pledge_info','budgetid = '.$info['id']);
				$this->db->delete('budget','id = '.$info['id']);

			}
			//插入收支表
			$budgetid=$this->pub_save_budget(10,$data);
			//插入收费类型表
			$typeid=$this->pub_save_typetable(10,$budgetid,$data);
			//插入书费表
			if(!empty($budgetid)){
				$electric_pledge_id=$this->insert_pledge_yajin_zhongji($budgetid,$data);
				return $electric_pledge_id;
			}
		}
	}
	/**
	 * [insert_pledge_yajin_zhongji 插入住宿押金表]
	 * @return [type] [description]
	 */
	function insert_pledge_yajin_zhongji($budgetid,$data){
		//判断有没有该用户的住宿押金的记录有的话就杀出
		if(!empty($budgetid)&&!empty($data)){
				$insert_arr=array(
				'budgetid'=>$budgetid,
				'userid'=>$data['userid'],
				'payable'=>$data['last_money'],
				'pay'=>$data['paid_in'],
				'state'=>1,
				'paytime'=>time(),
				'createtime'=>time(),
				'adminid'=>$_SESSION['master_user_info']->id
				);
			$this->db->insert(self::T_ACC_PLE_INFO,$insert_arr);
			return $this->db->insert_id();
		}
	}

	/**
	 * [grf_insert_budget 插入收支表方法]
	 * @return [type] [description]
	 */
	function grf_insert_budget($data){
		if(!empty($data)){
			$this->db->insert(self::T_BUDGET,$data);
			return $this->db->insert_id();
			}
			return 0;
	}
	/**
	 * [get_user_apply_info 获取该用户没有交申请费的记录]
	 * @return [type] [description]
	 */
	function get_user_apply_infos($userid){
		if(!empty($userid)){
			$this->db->select('apply_info.*,major.name');
			$this->db->where('apply_info.userid',$userid);
			$this->db->where('apply_info.state >= 7');
			$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_APP.'.courseid');
			$data= $this->db->get(self::T_APP)->result_array();
			if(!empty($data)){
				foreach ($data as $k => $v) {
					$data[$k]['applytime']=date('Y-m-d',$v['applytime']);
				}
			}
			return $data;
		}
		return array();
	}
}	