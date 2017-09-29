<?php 
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('PRC');
if(!$_COOKIE["isview"]){
    header('Location: login.php');
     exit();  
}
include("connect.php");
$sql = "select * from `c_list` ORDER BY `c_list`.`id` ASC";
mysql_query("set names 'utf8'");
$query = mysql_query($sql);
function chge($content)
{   
    $content = str_replace(',','<br>',$content);
    return $content;
}
function checktime($dbtime){
    $uptime = strtotime($dbtime);
    $now=time();
    $fromnow = $now - $uptime;
    $wpoint  = 3600 * 24 * 200;
    $rpoint  = 3600 * 24 * 300;
    $dpoint  = 3600 * 24 * 500;
    if($fromnow > $rpoint && $fromnow < $dpoint ){
        $res = "errortime";
        return $res;
    }elseif ($fromnow < $rpoint && $fromnow > $wpoint ) {
        $res = "warntime";
        return $res;
    }
    elseif ($fromnow > $dpoint) {
        $res = "deletetime";
        return $res;
    }
    else{return "";}

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link href="css/images/favicon.ico" rel="shortcut icon">
    <title>通讯录3.0 beta</title>
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/jquery.freezeheader.js"></script>
    <script type="text/javascript" src="js/tableExport.js"></script>
    <script type="text/javascript" src="js/mybase64.js"></script>
    <style type="text/css">
        body,table,td,tr,tbody,ul,li{margin: 0;padding: 0;font-family:Verdana,微软雅黑; *font-family:微软雅黑,Verdana;}
        body{background: #F8F8F8}
        li{list-style: none}
        a{text-decoration: none;}
        h1,h5,td{text-align: center;}
        table{border-collapse:collapse;margin:0 auto 10px;}
        tr{height: 25px;}
        tr:nth-child(even){background-color: #ccc}
        tbody tr:hover{background:#00A1D6;}
        thead{height: 40px;font-size: 16px;font-weight: 900;color: #00f;background-color: #83C9E2;border: none;}
        th{height: 40px;}
        #list a{color:#483D8B;}
        .merry {color: #f00;font-weight: bold;}
        .logout{position: absolute;right:0px;top: 0px;margin: 5px;}
        .contain{width: 100%;mini-height:250px;}
        .clearfix:after{content:".";display:block;height:0;clear:both;visibility:hidden}
        .clearfix{*+height:1%;}
        #ietest{text-align: center;color: #f00;font-size: 16px;font-weight: bold;}
        .errortime{background:url("data:image/gif;base64,R0lGODlhEwAZAIAAAP93eQAAACH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTQgNzkuMTUxNDgxLCAyMDEzLzAzLzEzLTEyOjA5OjE1ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo5QTc4NjY4ODNGNjExMUU2OUQ0M0YyMzc1NTM3Q0IwMiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo5QTc4NjY4NzNGNjExMUU2OUQ0M0YyMzc1NTM3Q0IwMiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSI2NDFGMkQyQTQ4OTQzN0Y4QjJDRTM0M0U3OTQ5RUYxRCIgc3RSZWY6ZG9jdW1lbnRJRD0iNjQxRjJEMkE0ODk0MzdGOEIyQ0UzNDNFNzk0OUVGMUQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4B//79/Pv6+fj39vX08/Lx8O/u7ezr6uno5+bl5OPi4eDf3t3c29rZ2NfW1dTT0tHQz87NzMvKycjHxsXEw8LBwL++vby7urm4t7a1tLOysbCvrq2sq6qpqKempaSjoqGgn56dnJuamZiXlpWUk5KRkI+OjYyLiomIh4aFhIOCgYB/fn18e3p5eHd2dXRzcnFwb25tbGtqaWhnZmVkY2JhYF9eXVxbWllYV1ZVVFNSUVBPTk1MS0pJSEdGRURDQkFAPz49PDs6OTg3NjU0MzIxMC8uLSwrKikoJyYlJCMiISAfHh0cGxoZGBcWFRQTEhEQDw4NDAsKCQgHBgUEAwIBAAAh+QQAAAAAACwAAAAAEwAZAAACFISPqcvtD6OctNqLs968+w+GolMAADs=") no-repeat right;}
        .warntime{background:url("data:image/gif;base64,R0lGODlhEwAZALMAAPjxlv//S///pPv8Rv3/Ovf/N/3/Rv//Nv78R/7/O//9SP/+QgAAAAAAAAAAAAAAACH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTQgNzkuMTUxNDgxLCAyMDEzLzAzLzEzLTEyOjA5OjE1ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCMkQwQjFBMzNGNjExMUU2QjU4Mjk2QTZEN0ZEMEY3MSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpCMkQwQjFBMjNGNjExMUU2QjU4Mjk2QTZEN0ZEMEY3MSIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSI4RkZDNERFODkzQjM5OTVGRTZCNkFERTJFMTQ2MEU0RCIgc3RSZWY6ZG9jdW1lbnRJRD0iOEZGQzRERTg5M0IzOTk1RkU2QjZBREUyRTE0NjBFNEQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4B//79/Pv6+fj39vX08/Lx8O/u7ezr6uno5+bl5OPi4eDf3t3c29rZ2NfW1dTT0tHQz87NzMvKycjHxsXEw8LBwL++vby7urm4t7a1tLOysbCvrq2sq6qpqKempaSjoqGgn56dnJuamZiXlpWUk5KRkI+OjYyLiomIh4aFhIOCgYB/fn18e3p5eHd2dXRzcnFwb25tbGtqaWhnZmVkY2JhYF9eXVxbWllYV1ZVVFNSUVBPTk1MS0pJSEdGRURDQkFAPz49PDs6OTg3NjU0MzIxMC8uLSwrKikoJyYlJCMiISAfHh0cGxoZGBcWFRQTEhEQDw4NDAsKCQgHBgUEAwIBAAAh+QQAAAAAACwAAAAAEwAZAAAEaFCAQgYK6BylUjLGIkqUhWmcB4oLWV3Z1n3hOL2nrNbtbcYpGsv1Q81WthLMuBv6ljphEgc88ojQILKnzGmvT6/V2a02p8XoFis+c6lM6Tv9JcPV4HJ8HTbL2X58enh2dGNoWYdziW4RADs=") no-repeat right;}
        .deletetime{background:url("data:image/gif;base64,R0lGODlhEwAZAIAAAAAAAP///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTQgNzkuMTUxNDgxLCAyMDEzLzAzLzEzLTEyOjA5OjE1ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSIxMjk3RjA1QUFEMkU3RTcwMUJCMEU1OTg4OTBFQ0ZGQyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpBNERGQjUwMjNGNUYxMUU2QjRBNzk1Q0YxRkU1OEExQyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpBNERGQjUwMTNGNUYxMUU2QjRBNzk1Q0YxRkU1OEExQyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgV2luZG93cyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjMzOTFmZGZkLTg1OTItZTQ0OC04OTFjLTk2ZmFmMjFhNzhlMyIgc3RSZWY6ZG9jdW1lbnRJRD0iMTI5N0YwNUFBRDJFN0U3MDFCQjBFNTk4ODkwRUNGRkMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4B//79/Pv6+fj39vX08/Lx8O/u7ezr6uno5+bl5OPi4eDf3t3c29rZ2NfW1dTT0tHQz87NzMvKycjHxsXEw8LBwL++vby7urm4t7a1tLOysbCvrq2sq6qpqKempaSjoqGgn56dnJuamZiXlpWUk5KRkI+OjYyLiomIh4aFhIOCgYB/fn18e3p5eHd2dXRzcnFwb25tbGtqaWhnZmVkY2JhYF9eXVxbWllYV1ZVVFNSUVBPTk1MS0pJSEdGRURDQkFAPz49PDs6OTg3NjU0MzIxMC8uLSwrKikoJyYlJCMiISAfHh0cGxoZGBcWFRQTEhEQDw4NDAsKCQgHBgUEAwIBAAAh+QQAAAAAACwAAAAAEwAZAAACFISPqcvtD6OctNqLs968+w+GolMAADs=") no-repeat right;}


        /*jqueryUI*/
        #dialog{overflow: inherit;min-height:370px;}
        #dialog-link span.ui-icon {
        margin: 0 5px 0 0;
        position: absolute;
        left: .2em;
        top: 50%;
        margin-top: -8px;
    }
    .ui-dialog{
        box-shadow: 0px 0px 25px 12px rgba(0, 0, 0, 0.6);min-height: 370px;
    }
    .ui-dialog .ui-dialog-buttonpane button{font-size: 14px;font-weight: bold;}
    .ui-dialog .ui-dialog-titlebar{padding: 5px 0px 5px 20px;}
    /* editor */

    .name{margin: 5px;}
    .content .top{margin-bottom: 10px;}
    .content .top span{margin-right: 10px;}
    .content .top .name{font-size: 30px;color: rgb(252, 0, 0);}
    .content .top .num{}
    .content .bot li{width: 300px;clear: both;position: relative;}
    .content .bot span{
        display: inline-block;
        border:none;
        width: 80px;
        height: 30px;
        line-height: 30px;
        font-size: 16px;
        text-align: right;
        margin-right: 5px;
        font-weight: bold;
    }
    .content .bot input{
        float: right;
        border:none;
        width: 200px;
        height: 24px;
        text-indent: 10px;
        font-size: 14px;
        font-weight: 900;
        color:#251262;
        border-radius: 12px;
        margin: 4px 0;
        box-shadow: 0px 8px 15px 0px rgba(79, 68, 98, 0.9);
        outline:0px;
        
    }
    .content .sub{text-align: right;padding-right: 20px}
    .content .sub button{background-color: #3366cc;color:#fff;width: 60px;height: 30px; line-height: 18px;font-size: 16px;text-align: center;text-indent: 0;border-radius: 7px;}

    .content .bot input.addbt {
        width: 25px;
        position: absolute;
        top: 0px;
        right: -30px;
        text-indent: 0px;
        font-weight: 900;
        color: #25BFAE;
        cursor: pointer;
    }
    .content .bot input.minusbt{
        width: 25px;
        position: absolute;
        top: 0px;
        right: -60px;
        text-indent: 0px;
        font-weight: 900;
        color: #000;
        cursor: pointer;
    }
      .content .bot input.addbt,.content .bot input.minusbt{box-shadow: 1px 2px 4px 0px rgba(0, 0, 0, 1);border: none;}
    </style>
</head>
<body>
<div class="logout">
<a href="javascript:;" onClick ="$('#list').tableExport({type:'excel',escape:'false'});" title="导出">导出为excel</a>
<a href="javascript:logout('isview');" title="退出">退出</a>
<script type="text/javascript">
$(document).ready(function () {
    $("#list").freezeHeader();
    if (!$.support.leadingWhitespace) {
           var obj = $("<div>").attr("id","ietest").html("警告：你正在使用ie9以下ie浏览器;会严重影响体验!");
            $(".logout").after(obj)
        } 
});
function logout(name) {  
    document.cookie= name + "="+""+";expires="+"-1"; 
    location.href = "login.php"
}  </script>
    
</div>
<h1>通讯录3.0 beta</h1>
<h5>(建议使用ie9及以上;360、qq、猎豹等极速模式;chrome和Firefox最新版本浏览器进行查看)</h5>
    <table id="list" bordercolor="#000000" cellspacing="0" cellpadding="0" align="center" border="0" width="1340">
    <thead>
        <tr>
            <th width="80">姓名</th>
            <th width="170">学号</th>
            <th width="60">性别</th>
            <th width="160">电话号码</th>
            <th width="150">QQ</th>
            <th width="240">Weixin</th>
            <th width="230" >所在地</th>
            <th width="90">婚姻状况</th>
            <th width="170">最后更新时间</th>  
        </tr> </thead><tbody>
<?php while($rs = mysql_fetch_array($query)){ 
        echo "<tr>";
            if($_COOKIE["isview"]==2){?>
            <td ><a class="dialoglink" href="#" name="<?php echo $rs['id']?>"><?php echo $rs['C_NAME']?></a></td>
            <?php }else{ ?>
            <td ><?php echo $rs['C_NAME']?></td><?php }?>
            <td ><?php echo $rs['C_NUM']?></td>
            <td ><?php echo ($rs['C_SEX']==1)?"男":"女";?></td>
            <td ><?php echo chge($rs['C_TEL'])?></td>
            <td ><?php echo chge($rs['C_QQ'])?></td>
            <td ><?php echo chge($rs['C_WEIXIN'])?></td>
            <td ><?php echo $rs['C_LOCAL']?></td>
            <td ><?php echo $rs['C_MERRY']?></td>
            <?php $ud = explode("-",$rs['C_UPDATE']);$res = checktime($rs['C_UPDATE']);echo "<td class='".$res."'>".$ud[0]."年".$ud[1]."月".$ud[2]."日"."</td>";?>   
        </tr> 
<?php }?>
    </tbody>     
</table>
<?php if($_COOKIE["isview"]==2){?>
    <!-- ui-dialog -->
    <link rel="stylesheet" href="css/jquery-ui.min.css">
<div class="ui-overlay" style="display: none"><div class="ui-widget-overlay"></div></div>
<div id="dialog" title="信息修改">
<div class="contain">
        <div class="content">
            <div class="top">
                <span class="name"><b></b></span>
                <span class="sex"><b></b></span>
                <span class="num"><b></b></span>
            </div>
                    
            <div class="bot">
            <!-- <?php //$_SERVER['PHP_SELF'] ?>-->
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" id="iform" accept-charset="utf-8">
                <ul class="clearfix">
                    <li id="qqli"><span>QQ</span><input id="iqq" name="iqq" type="text" value="" /></li>
                    <li id="telli"><span>电话</span><input id="itel" name="itel" type="text" value="" /></li>
                    <li id="wxli"><span>微信</span><input id="iwx" name="iwx" type="text" value=""/></li>
                    <li><span>所在地</span><input id="iloc" name="iloc" type="text" value=""/></li>
                    <li><span>婚姻状况</span><input id="imerry" name="imerry" type="text" value=""/></li>
                    <input type="hidden" name="id" id="iid" value="">

                </ul>
            </form>
            </div>  
          </div>
</div>
</div>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.inputadd.js" type="text/javascript"></script>
<script src="js/tool.js" type="text/javascript"></script>


<?php 

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])){
    $ciloc = $_POST['iloc'];
    $cmerry = $_POST['imerry'];
    $cid = $_POST['id'];
    $cqq0 = empty($_POST['iqq0'])?null:','.$_POST['iqq0'];
    $ctel0 = empty($_POST['itel0'])?null:','.$_POST['itel0'];
    $ctel1 = empty($_POST['itel1'])?null:','.$_POST['itel1'];
    $cwx0 = empty($_POST['iwx0'])?null:','.$_POST['iwx0'];
    $cqq = $_POST['iqq'].$cqq0;
    $ctel = $_POST['itel'].$ctel0.$ctel1;
    $cwx = $_POST['iwx'].$cwx0;

$sql = "update `c_list` set `C_QQ`='$cqq',`C_TEL`='$ctel',`C_WEIXIN`='$cwx',`C_LOCAL`='$ciloc',`C_MERRY`='$cmerry',`C_UPDATE`= CURDATE() where `id`='$cid'";
mysql_query("set names 'utf8'");
$query = mysql_query($sql);
}


}?>
</body>
<script type="text/javascript">
    var oTable = document.getElementById("list");
    var oTd = oTable.getElementsByTagName("td");
    var reg = /婚/ig;
        for (var i = 0; i < oTd.length; i++) {      
    if (reg.exec(oTd[i].innerHTML)) {
        oTd[i].setAttribute("class","merry");
    }
}

</script>
</html>