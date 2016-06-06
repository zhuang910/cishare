function pub_alert_success(msg){
	var msg = msg ? msg : '操作成功';
	$.gritter.add({
		title: '提示!',
		text: msg,
		class_name: 'gritter-success'
	});
}

function pub_alert_error(msg){
	var msg = msg ? msg : '操作失败';
	$.gritter.add({
		title: '提示!',
		text: msg,
		class_name: 'gritter-error'
	});
}

function pub_alert_confirm(url,data,msg){
  if(!url) return false;
    msg = msg ? msg : '确定要执行本次操作吗？';
    bootbox.confirm({
            message: msg,
            buttons: {
              confirm: {
               label: "确定",
               className: "btn-primary btn-sm"
              },
              cancel: {
               label: "取消",
               className: "btn-sm"
              }
            },
            callback: function(result) {
              if(result){
                  $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    dataType:'json',
                    success:function(r){
                      if(r.state == 1){
                        pub_alert_success(r.info);
                        setTimeout('location.reload()',1000);
                      }else{
                        pub_alert_error(r.info);
                      }
                    }
                   });
              }
            }
            }
          );
}
function pub_alert_confirms(url,data,msg){
  if(!url) return false;
    msg = msg ? msg : '确定要执行本次操作吗？';
    bootbox.confirm({
            message: msg,
            buttons: {
              confirm: {
               label: "确定",
               className: "btn-primary btn-sm"
              },
              cancel: {
               label: "取消",
               className: "btn-sm"
              }
            },
            callback: function(result) {
              if(result){
                  $.ajax({
                    type:'POST',
                    url:url,
                    data:data,
                    dataType:'json',
                    success:function(r){
                      if(r.state == 1){
                        pub_alert_success(r.info);
                         
                         paike();

                      }
                      if(r.state==2){
                        pub_alert_success(r.info);
                           $('#td'+r.data.start).removeAttr('rowspan');
                        var str='';
                         $.each(r.data.merge, function(k, v) {
                            str='<td id="td'+v+'"><a class="blue" data-toggle="modal" role="button">---</a></td>';
                           $('#td'+k).after(str);
                         });
                      
                         paike();
                      }
                    }
                   });
              }
            }
            }
          );
}
function pub_alert_html(url,isjump,addvar){
  addvar = addvar ? '&' : '?';
  isjump ? location.href=url+addvar+UVAR : '';
  $.ajax({
    type:'GET',
    url:url,
    dataType:'json',
    success:function(r){
      if(r.state == 1){
        $('body').prepend(r.data);
        _pub_alert_bootbox();
      }else{
        pub_alert_error(r.info);
      }
    }
  })
}

function _pub_alert_bootbox(){
    $("#pub_edit_bootbox .close").on('click',function(){
      $("#pub_edit_bootbox").remove();$("div.modal-backdrop").remove();$("body").removeClass('modal-open');
    });
    $("#pub_edit_bootbox .uptrue").on('click',function(){
          $("#pub_edit_bootbox").remove();$("div.modal-backdrop").remove();$("body").removeClass('modal-open');
           if($('#upload_file').length>0){
              var text='<div class="modal-backdrop fade in"></div>';
              $('body').append(text);
            }

    });
    $("#pub_edit_bootbox").modal({
      "backdrop": "static",
      "keyboard": true,
      "show": true
    });
}
function Tabs(id,title,content,box,on,action){
  if(action){
      $(id+' '+title).click(function(){
        $(this).addClass(on).siblings().removeClass(on);
        $(content+" > "+box).eq($(id+' '+title).index(this)).show().siblings().hide();
      });
    }else{
      $(id+' '+title).mouseover(function(){
        $(this).addClass(on).siblings().removeClass(on);
        $(content+" > "+box).eq($(id+' '+title).index(this)).show().siblings().hide();
      });
    }
}


function pub_alert_upload_html(url,isjump,addvar){
  addvar = addvar ? '&' : '?';
  isjump ? location.href=url+addvar+UVAR : '';
  $.ajax({
    type:'GET',
    url:url,
    dataType:'json',
    success:function(r){
      if(r.state == 1){
        $('body').prepend(r.data);

        _pub_alert_bootbox_upload();
      }else{
        pub_alert_error(r.info);
      }
    }
  })
}

function _pub_alert_bootbox_upload(){
    $("#closes").on('click',function(){
      $("#upload_file").remove();
      $("div.modal-backdrop").remove();
      $("body").removeClass('modal-open');
    });
    $("#upload_file .uptrue").on('click',function(){
      $("#upload_file").remove();$("div.modal-backdrop").remove();$("body").removeClass('modal-open');
    });
    $("#upload_file").modal({
      "backdrop": "static",
      "keyboard": true,
      "show": true
    });
}
function alert_loading(){
  var html = '\
    <div id="pub_edit_bootbox" class="modal fade" tabindex="-1">\
    <div class="modal-dialog">\
    <div class="modal-content">\
      <h3 class="lighter block green" style="text-align:center;margin:0 auto; padding:30px;">处理中...</h3>\
    </div>\
    </div>\
    </div>\
  ';
  $('body').append(html);
  _pub_alert_bootbox();
}