$(function(){
    $("#dialog").dialog({
    autoOpen: false,
    resizable: false,
    width: 400,
    show: {
        effect: "drop",
        duration: 500
      },
    hide: {
        effect: "drop",
        duration: 500
      },
    buttons: [{
        text: "提交",
        click: function() {
            //d_check();
            if(d_check()){
                isubmit();
            }
            else {return false}
        }
    }, {
        text: "放弃",
        click: function() {
            $(this).dialog("close");
            $(".ui-overlay").css("display", "none");
            $.removeAllAdd();
        }
    }]
});
$("button.ui-button:nth-child(2)").click(function() {
    $(".ui-overlay").css("display", "none");
    $.removeAllAdd();
});
/*按下esc*/
$(document).keydown(function(event){
        if(event.keyCode == 27){
            $.removeAllAdd();
            $(".ui-overlay").css("display", "none");}
    });
// Link to open the dialog
$(".dialoglink").click(function(event) {
    $("#dialog").dialog("open");
    $("#qqli").addminus("有那么多qq么，加1个就够了_(:зゝ∠)_",1);
    $("#telli").addminus("3个不能再多了，淘汰座机吧",2);
    $("#wxli").addminus("男(女)朋友的微信可以直接告诉我",1);
    $(".ui-overlay").css("display", "block");   
    var that = $(this);
    d_show(that);
    event.preventDefault();
});
function d_show(that) {
    var d_id = that.attr('name');
    var d_name = that.html();
    var allbro = that.parent().nextAll();
    var d_num = allbro.eq(0).html();
    var d_sex = allbro.eq(1).html();
    dataHandle(allbro.eq(2),'itel');
    dataHandle(allbro.eq(3),'iqq');
    dataHandle(allbro.eq(4),'iwx');
    var d_loc = allbro.eq(5).html();
    var d_merry = allbro.eq(6).html();
    $(".top .name b").html(d_name);
    $(".top .sex b").html(d_sex);
    $(".top .num b").html(d_num);
    $("#iloc").val(d_loc);
    $("#imerry").val(d_merry);
    $("#iid").val(d_id);
}
//警告说明
$(".warntime").attr('title','距上次更新超过200天');
$(".errortime").attr('title','距上次更新超过300天');
$(".deletetime").parent().find("td:nth-child(4)").css({"background":"red","text-decoration":"line-through"}).attr('title', '超500天，可能无效');

$("#iform li").each(function(index, el) {
    var ele = $(el);
    var text = "输入一个" + ele.find('span').text() + "号";
    ele.find('input').attr('placeholder', text);        
});

});
function dataHandle(source,id){
    var addlen = $('#'+id).nextAll('input').length-2;
    for (var i = 0; i < addlen; i++) {
        $('#'+id+i).remove();
            } 
           
    source.tmpArr = new Array;
    source.tmpArr = source.html().split('<br>');
    
    if(source.tmpArr.length>1){
        $('#'+id).parent().children(".minusbt").show("fast");
        $('#'+id).val(source.tmpArr[0]);
        for (var i = 0; i < source.tmpArr.length-1; i++) {   
        $('#'+id).after($("<input>").attr({
                type: "text",
                name: $('#'+id).attr("name") + i,
                id: $('#'+id).attr("id") + i,
                value:source.tmpArr[i+1]
            }));
    }
    }else{
        $('#'+id).val(source.tmpArr[0]);
    }
}
/*提交函数*/
function isubmit(){  
        var form = $("#iform");
        $.ajax({  
            url:form.attr('action'),  
            type:form.attr('method'),  
            data:form.serialize(), 
            success:function(data){  
                alert("修改成功"); 
                window.location.reload();
            },  
            error:function(){  
                $("#dialog").dialog("close");  
                alert("服务器连接失败"); 
            }  
        })        
    }
function d_check(){
    var tmp = $("#iform").find("input[type='text']");
    var teltmp = $("#telli").find("input[type='text']");
    var reg = /^1[34578]\d{9}$/;
   for(var i =0 ;i<tmp.length;i++){
       $.trim(tmp.eq(i).val());
        if(tmp.eq(i).val()==""){
            tmp.eq(i).focus().stop().Shake(2,3);
            return false;
        }
    }
    for (var i = 0; i < teltmp.length; i++) {
        if(!reg.test(teltmp.eq(i).val()))
        { 
            alert("请输入正确的11位手机号")
            teltmp.eq(i).focus().stop().Shake(2,3);
            return false;
        }
    }

    return true;
    }
