<?php $this->load->view('society/headermy.php')?>
<div class="width_925">
		  <h2 class="mt50 mb20"><span><?=lang('editphoto')?></span></h2>
		  <div class="center_geren">
			<div class="xiugai">
			  <div class="top_pic">
				<div class="top_box"><img id="preview" src="<?=$pic?>"  <?php if(empty($pic)){echo 'style="diplay:none" width=-1 height=-1';}else{echo 'width=143 height=145';}?> /></div>
				<div class="top_font"><div id="localImag"><?=lang('motx')?></div>
			  </div>
			  <div class="gray_btn">
				<div class="neiqianbtn1"><a href="javascript:;" onclick="document.getElementById('doc').click()" class="btn_mouseout"><?=lang('djsctp')?></a></div>
			  </div>
			  <div class="red_btn">
			  <form enctype="multipart/form-data" method="post" id="pic" action="/society/society/stu_pic">
			  <input type="file" name="doc" id="doc" onchange="javascript:setImagePreview(this.value);" style="display:none;">  

				<div class="neiqianbtn2"><a  href="javascript:void(0)" name="submit" onclick="document.getElementById('pic').submit();" ><?=lang('submit')?></a></div>
				</form>
			  </div>
			</div>
		  </div>
	</div>
<script>

$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
				dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 5000);
				window.location.reload();
			}else if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 5000);
			}
		},
		dataType:'json'
	
	});
	
	

});
</script>
<script>  
function setImagePreview(filePath) {  
    var docObj=document.getElementById("doc");  
    var imgObjPreview=document.getElementById("preview");  
    if(docObj.files && docObj.files[0]){  
        //火狐下，直接设img属性  
            
        imgObjPreview.style.display = 'block';  
        imgObjPreview.style.width = '143px';  
        imgObjPreview.style.height = '145px';                      
        //imgObjPreview.src = docObj.files[0].getAsDataURL();  
          
        //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式    
        imgObjPreview.src = window.URL.createObjectURL(docObj.files[0]);  
    }else{  
        //IE下，使用滤镜  
        docObj.select();  
        var imgSrc = document.selection.createRange().text;  
        var localImagId = document.getElementById("localImag");  
        //必须设置初始大小  
        localImagId.style.width = "143px";  
        localImagId.style.height = "145px";  
        //图片异常的捕捉，防止用户修改后缀来伪造图片  
        try{  
            localImagId.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";  
            localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;  
        }catch(e){  
            alert("<?=lang('error_gs')?>");  
            return false;  
        }  
        imgObjPreview.style.display = 'none';  
        document.selection.empty();  
    }  
    return true;  
}  
function jj(){
	 var d = dialog({
                    title: '<?=lang('welcome')?>',
                    content: '<?=lang('2m')?>',
                  });
	 d.show();
}
</script>  
<?php $this->load->view('society/footer.php')?>