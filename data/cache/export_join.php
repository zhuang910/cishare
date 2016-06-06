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
		'insurance_info'=>array(
			array('table0'=>'student_info','where0'=>'student_info.id=insurance_info.userid'),
			// array('table1'=>'budget','where1'=>'budget.id=insurance_info.budgetid')
			),
		'course_elective' =>array(
			array('table0'=>'squad','where0'=>'squad.id=course_elective.squadid'),
			array('table1'=>'course','where1'=>'course.id=course_elective.courseid'),
			
			array('table2'=>'student_info','where2'=>'student_info.id=course_elective.userid'),
			),
		'books_fee'=>array(
			array('table0'=>'student','where0'=>'student.userid=books_fee.userid'),
			),
		'student'=>array(),
		'checking'=>array(),
		'squad'=>array(),
		'evaluat_student'=>array(),
		'score'=>array(),
		'teacher_score'=>array(),
		'budget'=>array(),
		'credentials'=>array(),
		'accommodation_info'=>array(),
		'room_electric_record'=>array(),
		'get_student_budget'=>array(),
	);