<?php

require("base_conf.php");
include("$BASE_path/includes/base_include.inc.php");
include_once("$BASE_path/base_db_common.php");
include_once("$BASE_path/base_common.php");

$errorMsg      = "";
$displayError  = 0;
$noDisplayMenu = 1;


if (isset($_POST['submit'])) {
    $debug_mode = 0; 
    $BASEUSER   = new BaseUser();
    $user       = filterSql($_POST['login']);
    $pwd        = filterSql($_POST['password']);

    if (($BASEUSER->Authenticate($user, $pwd)) == 0) {
        header("Location: index.html");
	exit();
    }

    $displayError = 1;
    $errorMsg     = _LOGINERROR;
} 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html class="x-admin-sm">
  <head>
 
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(_CHARSET); ?>" />
    <meta http-equiv="pragma" content="no-cache" />
    <title><?php echo(_TITLE . $BASE_VERSION); ?></title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="./css/font.css">
    <link rel="stylesheet" href="./css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="./lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>
    <script type="text/javascript" src="./js/cookie.js"></script>

  </head>

  <body class="login-bg" onload="javascript:document.loginform.login.focus();">
  
     <div class="login layui-anim layui-anim-up">
        <div class="message">入侵检测系统控制台登录</div>
          <div id="darkbannerwrap"></div>
             <form action="index.php" method="post" name="loginform" class="layui-form">
              <input type="text"  placeholder="用户名" name="login" id="entry_name">
              <hr class="hr15">
              <input type="password" name="password"  placeholder="密码" id="entry_password" />
              <hr class="hr15">
              <input value="登录"  name="submit" style="width:100%;" type="submit">
              <hr class="hr20" >
             <?php
             if ($displayError == 1)
             {
                echo "<div class='errorMsg' align='center' style='color: red;'>$errorMsg</div>";
             }
             ?>
            </form>
         </div>
       </div>
     </div>


    <script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              form.on('submit(login)', function(data){
                layer.msg(JSON.stringify(data.field),function(){
                    location.href='index.html'
                });
                return false;
              });
            });
        })
    </script>

    <script>
    //百度统计可去掉
    var _hmt = _hmt || [];
    (function() {
      var hm = document.createElement("script");
      hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
      var s = document.getElementsByTagName("script")[0]; 
      s.parentNode.insertBefore(hm, s);
    })();
    </script>
   
</body>
</html>
