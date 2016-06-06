<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Created by CUCAS TEAM.
 * User: JunJie
 * E-Mail:zhangjunjie@cucas.cn
 * Date: 15-1-14
 * Time: 下午2:30
 */

return array(

	array('field'=>'books_fee.id','name'=>'序号'),
	array('field'=>'student.enname','name'=>'姓名（护照名）'),
	array('field'=>'student.sex','name'=>'性别'),
	array('field'=>'student.nationality','name'=>'国籍'),
	array('field'=>'student.passport','name'=>'护照号码'),
	array('field'=>'student.birthday','name'=>'出生日期'),
	array('field'=>'student.squadid','name'=>'班级'),
	array('field'=>'books_fee.book_ids','name'=>'所购教材明细'),
	array('field'=>'books_fee.paid_in','name'=>'支付金额'),
	);