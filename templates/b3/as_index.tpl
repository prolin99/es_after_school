
<{$toolbar}>

<div class="row">
<h3>現在期別：<{$data.month_data.monthdoc}></h3>
 <{if  (($data.month_data.cando>0) and ( $data.month_data.month_id ) ) }>
 <span class="label label-success">尚在填報期間！報名截止：<{$data.month_data.deadline}></span>
  <a class="btn btn-primary" href="export.php?mid=<{$data.month_data.month_id}>" >點名冊</a>
 <{else}>
 <span class="label  label-default">填報時間結束！報名截止：<{$data.month_data.deadline}></span>
 <a class="btn btn-primary" href="export.php?mid=<{$data.month_data.month_id}>" >點名冊</a>
 <{/if}>
<{if ($data.admin)}>
<form method="post" class="form-inline" action="" name="main">

  <div class="form-group">
      <label for="admin_class_id">班級</label>

      <{html_options name='admin_class_id' class='form-control'  options=$data.class_list_c  selected=$data.sel_class  onchange="submit();"}>
      <span  class="label label-danger">管理者權限</span>
</div>

</form>
<{else}>
	<h2><{$data.class_list_c[$data.sel_class]  }></h2>
<{/if}>


<{if ($data.month_data.cando and $data.month_data.month_id )}>
<form method="post" class="form-inline"  action="index.php" name="form2">
  <div class="form-group">
  <label for="sel_stud">學生:</label>
  <select name="sel_stud"  class='form-control'   >
	<{foreach  key=key item=list   from= $data.class_stud }>
		<option  value="<{$list.class_sit_num}>,<{$list.name}>,<{$list.stud_id}>"><{$list.class_sit_num}>.<{$list.name}></option>
	<{/foreach}>
  </select>
  <label for="time_mode">年段班：</label>
    <{html_options name='grade_year'   class='form-control'  options=$AS_SET.grade_set    selected=$data.grade_year title='人數太少，才年段合併'}>
  <label for="time_mode">時段：</label>
    <{html_options name='time_mode'   class='form-control'  options=$AS_SET.time    selected=$data.def_time_mode  }>
    <label for="class_id_set">班別</label>
    <{html_options name='class_id_set'  class='form-control'  options=$AS_SET.class_set  title='開多班才選擇'   }>

    <label for="spec">減免</label>
    <{html_options name='spec'   class='form-control'   options=$AS_SET.decrease_set   }>

  <input name="ps" value=""  class='form-control'  placeholder="備註說明文字">
  <input name="admin_class_id" type="hidden" value="<{$data.sel_class}>" >
  <input name="class_id" type="hidden" value="<{$data.sel_class}>" >
  <input name="OCLASS_ID" type="hidden" value="<{$data.sel_class}>" >
  <button type="submit" class="btn btn-success" name='ADD' value='ADD' >新增</button>
</div>
</form>
 <{/if}>

<{$row_i++}>
 <div class="row" >

        <span class='col-md-1 col-xs-1'>年段班</span>
        <span class='col-md-1 col-xs-1'>原班</span>
        <span class='col-md-2 col-xs-2'>姓名</span>
        <span class='col-md-1 col-xs-1'>班別</span>
        <span class='col-md-1 col-xs-1'>時段</span>
        <span class='col-md-2 col-xs-2'>減免</span>
	<span class='col-md-1 col-xs-1' title='需等報名完成'>費用 </span>
        <span class='col-md-2 col-xs-2'>備註</span>


</div>
 <{foreach  key=key item=list   from= $data.sign_studs }>

<div class="row <{if ($list.joinfg) }>bg-danger<{/if}>" id="div_<{$list.id}>_<{$list.class_id_base}>_<{$row_i}>" >
        <span class='col-md-1 col-xs-2 '  id="grade_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.grade_year}>"  >
            <span class="badge badge-success"><{$row_i++}></span><{$list.grade_year}>
        </span>
        <span class='col-md-1 col-xs-1'  id="classidbase_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.class_id_base}>"> <{$data.class_list_c_s[$list.class_id_base]}> </span>
        <span class='col-md-2 col-xs-2' id="name_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.stud_name}>"><span class="edit" ><span class="glyphicon glyphicon-pencil" title="修改"></span></span>  <{$list.stud_name}> <span class="del" ><span class="glyphicon glyphicon-trash" title="刪除"></span></span> </span>
      	<span class='col-md-1 col-xs-1' id="classid_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.class_id}>"> <{$AS_SET.class_set[$list.class_id]}>  </span>
      	<span class='col-md-1 col-xs-1' id="time_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.time_mode}>"> <{$AS_SET.time[$list.time_mode]}> </span>
      	<span class='col-md-2 col-xs-2' id="spec_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.spec}>"><{$AS_SET.decrease_set[$list.spec]}>   </span>
      	<span class='col-md-1 col-xs-1'>	<{$list.pay_sum }>	</span>
       	<span class='col-md-2 col-xs-2' id="ps_<{$list.id}>_<{$list.class_id_base}>" data="<{$list.ps}>"> <{$list.ps}>  </span>
</div>

<{/foreach }>

</div>

<{if ($data.month_data.cando)}>
<script>



    //刪除----------------------------------------------------------------------------------
  $(document).on("click", ".del", function(){

    var div_id = $(this).parent().parent().attr("id")  ;
    	   if(confirm('是否確定要刪除？')) {
           	ajax_del(  div_id) ;  // 刪除動作
           	//把這個 div 隱藏起來
           	$(this).parent().parent().hide() ;
           }
 });

      var ajax_del=function( id ){
      //alert(id) ;
             var URLs="ajax_as_del.php?id=" + id ;

            $.ajax({
                url: URLs,
                type:"GET",
                dataType:'text',

                success: function(msg){
                    //alert(msg);
                },

                 error:function(xhr, ajaxOptions, thrownError){
                    alert('error:' + xhr.status);
                    alert(thrownError);
                 }
           })
        }



    //修改----------------------------------------------------------------------------------
  $(document).on("click", ".edit", function(){

    var div_id = $(this).parent().parent().attr("id")  ;
    // alert(div_id) ;


       //編修
	//$('#' +div_id).html(form_str) ;
 	ajax_edit(div_id) ;
 });

      var ajax_edit=function( div_id ){
      //alert(div_id) ;
             var URLs="ajax_as_edit.php?id=" + div_id ;

            $.ajax({
                url: URLs,
                type:"GET",
                dataType:'text',

                success: function(msg){
                    //alert(msg);
                    $('#' +div_id).html(msg) ;
                },

                 error:function(xhr, ajaxOptions, thrownError){
                    alert('error:' + xhr.status);
                    alert(thrownError);
                 }
           })
        }


   //按下 save  ----------------------------------------------------------------------------------
   $(document).on('click', '.save', function(){
    	var div_id = $(this).parent().parent().attr("id")  ;
    	var form_id = $(this).parent().attr("id")  ;
    	//alert(div_id +form_id ) ;
    	ajax_save( div_id , form_id ) ;
    	//$('#frm_add').show() ;
     });

     //ajax 存檔後，修改 div 內容 ----------------------------
      function ajax_save( div_id , form_id  ){
 	//alert ($('#'+form_id ).serialize()) ;
            $.ajax({
              	url: 'ajax_as_save.php',
              	 type: 'POST',
		dataType: 'html',
		data: $('#'+form_id ).serialize() ,

                success: function(data){
                    //alert(msg);
                    $('#'+div_id).html(data );
                },

                 error:function(xhr, ajaxOptions, thrownError){
                    alert('error:' + xhr.status);
                    alert(thrownError);
                 }
           })
        }




</script>
 <{/if}>
