<?php 
header("Content-Type: text/html;charset=utf-8");
 if(!empty($_COOKIE["isview"]))
{
    header('location:index.php');
 }
 else{
        if(isset($_POST["pwd"]))
        {   include_once("connect.php");
            $sql = "select * from `info` ";
            $query = mysql_query($sql);
            while ($row = mysql_fetch_array($query)) {
               $pw=md5(md5($_POST["pwd"]).$row["i_salt"]);
                if($row["i_password"] == $pw)
                {
                    setcookie("isview",$row["i_id"],time()+3600*3);
                    header('location:index.php');
                }
            }   
            $p = (empty($pw)) ? "需要密码才能查看，请输入密码。" : "<div style=\"color:#F00;\">密码不正确，请重新输入。</div>";
        }
        else{
            $p = "忘记老师名字到群里咨询。（´・ω・｀）"; }
    }?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link href="css/images/favicon.ico" rel="shortcut icon">
<title>通讯录3.0 beta</title>
<script src="http://apps.bdimg.com/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/tipso.min.js"></script>
<link rel="stylesheet" href="css/tipso.min.css">
<style type="text/css">
body{background: url("http://r.photo.store.qq.com/psb?/V13Znr771NKj2b/tQSog92xvlQ9gtE2Z3osTFQ677Dv1AWBIlljhHHolSw!/r/dOEAAAAAAAAA");
background-size:cover;
-webkit-background-size:cover;
-moz-background-size:cover;}
 body,table,td,tr,tbody{margin: 0;padding: 0;font-family:Verdana,微软雅黑; *font-family:微软雅黑,Verdana;}
.passport {
    background-color: #83C9E2;
    width: 600px;
    height: 200px;
    position: absolute;
    left: 49.9%;
    top: 49.9%;
    margin-left: -300px;
    margin-top: -55px;
    font-size: 14px;
    text-align: center;
    line-height: 30px;
    color: #1A1717;
    border-radius: 10px;
    filter:alpha(opacity=50);
    -moz-opacity:0.5;
    -khtml-opacity: 0.5;
    opacity: 0.5;
}
.passport:hover{
    filter:alpha(opacity=100);
    -moz-opacity:1;
    -khtml-opacity: 1;
    opacity:1;
}
.passport h1{color:#ff5353;}
.passport input{
            border:1px solid #D5BCFF;
            height: 24px;
            text-indent: 10px;
            font-size: 14px;
            font-weight: 900;
            color:#251262;
            border-radius: 12px;
            margin: 4px 0;
            box-shadow: 0px 8px 15px 0px rgba(79, 68, 98, 0.9);
        outline:0px
            
        }
#ibut{text-indent: 0px;}
#ietest{text-align: center;color: #f00;font-size: 16px;font-weight: bold;}
.tips{height: 30px;line-height: 30px;font-size: 14px;font-weight: bold;text-align: center;color: #f00;}
.tips span {text-decoration: underline;font-weight: 900;color: #000;}
</style>
<script type="text/javascript">
$(function(){
$("#sub").tipso({
        useTitle: false,
        position: 'top'
    });
        if (!$.support.leadingWhitespace) {
           var obj = $("<div>").attr("id","ietest").html("警告：你正在使用ie9以下ie浏览器;会严重影响体验!");
            $(".loginform").after(obj)
        }
});
        
</script>
</head>
<body>
<div class="passport">
<h1>2014届微电子制造工程一班通讯录</h1>
    
    <div class="loginform">
        <form action="" method="post" style="margin:0px;">输入查看密码
            <input  id="sub" data-tipso="输入数电老师名字可以进修改模式(；°○° )"  type="password" name="pwd" />
            <input id="ibut" type="submit" value="查看" />
        </form>
        <div class="tips">查看密码为<span>微电子概论</span>老师拼音</div>
        <script language=javascript>document.getElementById("sub").focus();</script> 
        <div><?php echo $p;?></div>    
    </div>
</div>
</body>
</html>  
