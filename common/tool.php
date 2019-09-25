<?php
    session_start();
    //格式化输出
    function format($data){
        echo '<pre>';
        print_r($data);
        exit();
    }

    //判断用户是否登录
    function isLogin(){
        if(empty($_SESSION['admin'])){
            echo "<script>location.href = 'login.html'</script>";
        }
    }
