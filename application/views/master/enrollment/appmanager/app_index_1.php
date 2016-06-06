<?php $this->load->view(MASTER.'public/header');?>
<?php $this->load->view(MASTER.'public/main_line');?>
<?php $this->load->view(MASTER.'public/js_table')?>
<?php $this->load->view(MASTER.'public/js_css_chosen')?>
<?php $this->load->view(MASTER.'public/js_css_notify')?>
<?php $this->load->view(MASTER.'public/oa_jquery')?>

<div class="row-fluid">
	<div class="span12">
		<div class="box  box-color box-bordered">
			<div class="box-title">
				<h3>
					<i class="icon-table"></i>
					材料审核中
				</h3>
			</div>
			<div class="box-content nopadding">
				<ul class="nav nav-tabs" style="padding-top:3px;padding-left:5px;">
				    <li <?php if($label_id ==0):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=0"><h5>未提交资料</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='1'):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=1"><h5>材料审核中</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='2'):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=2"><h5>打回</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='3'):?> class="active" <?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=3"><h5>打回提交</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='4'):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=4"><h5>拒绝</h5></a>
                    </li>
					<li <?php if(!empty($label_id) && $label_id =='5'):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=5"><h5>调剂</h5></a>
                    </li>
                    <!--<li <?php if(!empty($label_id) && $label_id =='6'):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=6"><h5>预录取</h5></a>
                    </li>-->
                    <li <?php if(!empty($label_id) && $label_id =='7'):?> class="active"<?php endif;?>>
                    <a href="/epmaster/appmanager/index?cnav=347&cleft=349&cmenuid=351&label_id=7"><h5>录取</h5></a>
                    </li>
                   
				</ul>
				<table class="table table-hover table-nomargin table-bordered dataTable-columnfilter dataTable">
					<thead>		
						<tr class="thefilter">
						    <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<th>状态/时间</th>
							<th>审核</th>
							<th>操作</th>
						</tr>
						<tr>
						    <th>ID</th>
							<th>申请对象信息</th>
							<th>申请人信息</th>
							<th>状态/时间</th>
							<th>审核</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php  if(!empty($lists)):?>
							<?php foreach($lists as $val):?>
                            <tr id="<?=$val->appid; ?>">
                                <td><?=$val->appid;?></td>
                                <td>
                                    <a href="/epmaster/appmanager/app_detail?id=<?=$val->appid?>&cnav=347&cleft=349&cmenuid=351" class="" rel="tooltip" data-original-title="详情">
									申请编号：<?=$val->number;?></a><br/>
                                    专业名：<?=$val->name;?>
									<?php if(!empty($val->scholorshipid) && !empty($scholorshipapply[$val->scholorshipid])){?>
										(<?=$scholorshipapply[$val->scholorshipid]?>)
										
									<?php }?>
									<br/>
                                    
                                    学制：
                                    <a id="program_length" class="editables editable-click" data-placement="top" data-original-title="修改学制" data-pk="<?=$val->appid; ?>^text" data-type="text" href="#">
                                    <?=$val->program_length;?>   (<?=$programa_unit[$val->program_unit]?>)</a> <br/>
             
                                    开学/截止：
									<a id="opening" data-viewformat="yyyy-mm-dd" data-type="date" class="editables editable-click" data-placement="top" data-original-title="修改开学" data-pk="<?=$val->appid; ?>^date" href="#">
									<? if(empty($val->opening)) echo date('Y-m-d',$val->opening); else echo date('Y-m-d',$val->opening);?>
									</a>
                                     / 
                                    <a id="deadline" data-viewformat="yyyy-mm-dd" data-type="date" class="editables editable-click" data-placement="top" data-original-title="修改截止" data-pk="<?=$val->appid; ?>^date" href="#">
									<?=date('Y-m-d',$val->deadline);?>
                                    </a>
                                </td>
                                <td>
                                    姓名：
									<a id="enname" data-type="text" class="editable editable-click" data-placement="top" data-original-title="修改姓名" data-pk="<?=$val->appid; ?>^text" href="#">
									<?=$val->enname;?>
                                    </a><br/>
                                    性别：
                                    <a id="sex" data-type="text" class="editable editable-click" data-placement="top" data-original-title="修改性别" data-pk="<?=$val->appid; ?>^text" href="#">
									<?=!empty($val->sex)?$val->sex==1?'Male':'Female':'';?>
                                    </a><br/>
                                    邮箱：
									<a id="email" data-type="text" class="editable editable-click" data-placement="top" data-original-title="修改邮箱" data-pk="<?=$val->appid; ?>^text" href="#">
									<?=$val->email;?>
                                    </a><br/>
                                    国籍：
									<a id="nationality" data-type="select" class="editable editable-click" data-placement="top" data-original-title="修改国籍" data-pk="<?=$val->appid; ?>^text" href="#">
									<?=$country[$val->nationality];?>
                                    </a><br/>
                                    护照：
									<a id="passport" data-type="text" class="editable editable-click" data-placement="top" data-original-title="修改护照" data-pk="<?=$val->appid; ?>^text" href="#">
									<?=$val->passport;?>
                                    </a>
                                </td>
                                <td>
                               
                                    <span style="color:#F00;"><?=$app_state[$val->status];?></span><br/>
                                    	提交时间：<?=date('Y-m-d',$val->applytime);?><br/><br/>
                                    	<a rel="<?=$val->appid; ?>" class="tip"  id='alert' href='javascript:viod();'>用户流程跟踪</a>
                                </td>
                                <td>
						              	<!--申请表： 
						              	<a href="/process/appform/index?id=<?=$val->appid;?>&type=online">
                                                                                                           审核</a>
						              	<a href="/process/appform/index?id=<?=$val->appid;?>&type=download">
						              	下载</a><br/>-->
						                 附件：
						               <!-- <a rel="<?=$val->appid; ?>" class="tip"  id='online' href='javascript:viod();'>                           
						                                           审核</a>-->
						                <a href="/epmaster/appmanager/attach_download?id=<?=$val->appid;?>">                           
						                                           下载</a><br/><br/>
						                 <!--<?php if ($val->status==6) :?>
						                  
						                <?php if ($val->deposit_state==1) :?>
						                	押金：<a id="deposit_state" data-type="text" >已交</a><br/>
						                <?php  else:?> 
						                	押金：<a id="deposit_state" data-type="text" >未交</a><br/>
						                <?php endif;?>
						                <?php endif;?>
                                        <?php if ($val->status==6):?>
                                        	 押金审核：
						                 <a id="paperdocumentyes" class="editable editable-click editable-open" rel="tooltip" href="#" data-pk="32^text" data-original-title="修改押金接收状态" data-placement="left" data-type="select" data-source="[{value: 2, text: '已收到'},{value: 1, text: '未收到'}]">押金审核</a><br/>
                                        <?php endif;?>-->
                                        
						              
                                </td>
                                <td>
                                <?php if ($val->status==6&&$val->deposit_state==1 ):?>
                                	 <a href="javascript:pub_alert_html('/epmaster/change_app_status/index?id=<?=$val->appid?>&label_id=<?=$label_id?>');" class="btn btn-small btn-success" rel="tooltip" data-original-title="修改">修改状态</a><br/>
                                
                                <?php endif;?>

                                  
                                   <a id="remark" data-type="textarea" class="editabless" data-placement="left" data-original-title="修改备注"  data-value='<?=$val->remark; ?>' data-pk="<?=$val->appid; ?>^text" href="#" rel="tooltip">
								      <?php if(!empty($val->remark)):?>查看备注<?php else:?>添加备注<?php endif;?></a>  <br /><br />
								     <a class="btn btn-danger  btn-small " href="javascript:end_apply(<?=$val->appid?>);" data-original-title="结束" rel="tooltip">结束</a> 
                                </td>
                            </tr>
                            <?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<?php $this->load->view(MASTER.'public/footer');?>