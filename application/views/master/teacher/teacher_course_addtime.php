<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>
	<li>
		<a href="javascript:;">基础设置</a>
	</li>
	<li>
		<a href="javascript:;">在学设置</a>
	</li>
	<li>
		<a href="/master/teacher/teacher">教师管设置</a>
	</li>
	<li><a href="javascript:history.back();">关联课程</a></li>
	
</ul>
EOD;
?>
<?php $this->load->view('master/public/header',array(
    'breadcrumb'=>$breadcrumb,
));?>
    <!-- bootstrap & fontawesome -->

    <script src="<?=RES?>master/js/jquery.validate.min.js"></script>

    <!-- /section:settings.box -->
    <div class="page-header">
        <h1>
            关联课程
        </h1>
    </div><!-- /.page-header -->
    <div class="step-pane active" id="step1">
        <h3 class="lighter block green"><b><?=$teacher_info['name']?></b>老师<b><?=$course_info['name']?></b>课程的安排时间
            <a href="javascript:history.back();" title='返回上一级' class="pull-right ">
                <i class="ace-icon fa fa-reply light-green bigger-130"></i>
            </a>
        </h3>
    </div>
    <div class="step-pane active" id="step1">
        <h3 class="lighter block">
            <label class="inline">
                <input id="xiangbuxiang" class="ace ace-switch ace-switch-5" type="checkbox" onchange="quanxuans(this,1)">
                <span class="lbl middle"></span>
                全选
            </label>
            <label class="inline">
                <input id="xiangbuxiang" class="ace ace-switch ace-switch-5" type="checkbox" onchange="quanxuans(this,2)">
                <span class="lbl middle"></span>
                取消全选
            </label>
        </h3>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            <div>
                <form class="form-horizontal" id="validation-form" method="post" action="<?=$zjjp?>teacher_course/update">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>节</th>
                            <th>星期一</th>
                            <th>星期二</th>
                            <th>星期三</th>
                            <th>星期四</th>
                            <th>星期五</th>
                            <th>星期六</th>
                            <th>星期日</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($hour['hour'] as $k=>$v):?>
                            <tr>
                                <td><?php
                                    echo $v."节课";
                                    ?></td>
                                <?php for($i=1;$i<=7;$i++):?>
                                    <?php $num=0;?>
                                    <td>
                                        <label class="inline">
                                            <?php
                                            foreach($timeinfo as $kk=>$vv){
                                                if($i==$vv['week'] && $v==$vv['knob'] && $vv['state']==1){
                                                    $num=1;
                                                }
                                            }

                                            if($num!=0){
                                                echo '<input id="gritter-light" class="ace ace-switch ace-switch-5 gengruifeng" type="checkbox" checked="" onchange="insert(',$v,',',$i,')">';

                                            }else{
                                                echo '<input id="gritter-light" class="ace ace-switch ace-switch-5 gengruifeng" type="checkbox"  onchange="insert(',$v,',',$i,')">';
                                            }

                                            ?>


                                            <span class="lbl middle"></span>
                                        </label>
                                    </td>
                                <?php endfor;?>

                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>

                </form>
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
    <script src="<?=RES?>master/js/markdown/markdown.min.js"></script>
    <script src="<?=RES?>master/js/markdown/bootstrap-markdown.min.js"></script>


    <script type="text/javascript">
//        $(function(){
//            var gege=0;
//            $('.gengruifeng').each(function(){
//                if(this.checked){
//                    gege=1;
//                }
//
//            });
//            if(gege == 1){
//                $("#xiangbuxiang").attr("checked", true);
//            }
//
//        })
function quanxuans(th,s){
    if(s==1){
        //取消全选
        $('.gengruifeng').each(function(){
            this.checked = true;
        });
        update_base(1);
    }else{
        //全选
        $('.gengruifeng').each(function(){
            this.checked = false;
        });
        update_base(2);
    }
}
        function update_base(s){
            var teacherid=<?=$teacherid?>;
            var courseid=<?=$courseid?>;
            $.ajax({
                url: '<?=$zjjp?>teacher_course/inser_timegeng?teacherid='+teacherid+'&courseid='+courseid+'&s='+s,
                type: 'POST',
                dataType: 'json',
                async: true,
                data: {}
            })
                .done(function(r) {

                })
                .fail(function() {
                    console.log("error");
                })
        }
        function quanxuan(th){
            $('.gengruifeng').each(function(){
                if(this.checked){
                    this.checked = false;
                }else{
                    this.checked = true;
                }
                var gaga = $(this).attr('onchange');
                setTimeout(eval(gaga),9000);
//                eval(gaga);
            });
        }
        function insert(k,w){

            var teacherid=<?=$teacherid?>;
            var courseid=<?=$courseid?>;
            $.ajax({
                url: '<?=$zjjp?>teacher_course/insert_time?teacherid='+teacherid+'&courseid='+courseid+'&week='+w+'&knob='+k,
                type: 'POST',
                dataType: 'json',
                async: true,
                data: {}
            })
                .done(function(r) {

                })
                .fail(function() {
                    console.log("error");
                })


        }








    </script>





    <!-- end script -->
<?php $this->load->view('master/public/footer');?>