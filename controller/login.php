<?php

use MongoDB\Driver\Query;

    require '../common/tool.php';
    require '../common/conn.php';

    $get = $_GET;

    $tag = isset($get['tag']) ? $get['tag'] : '';

    $return = [
        'code'    => 0,
        'message' => '',
    ];


    if($tag == 'login'){
        $post = $_POST;
        //判断数据是否为空
        if(empty($post) || empty($post['username']) || empty($post['password'])){
            $return['message'] = '用户名或密码不能为空！';
            echo json_encode($return);
            exit();
        }

        //用户登录验证
        $username = $post['username'];
        $pwd = $post['password'];
        $sql = "select * from admin where username = '" . $username ."' and password ='" . $pwd ."'";
        $user = $conn->getRow($sql);
        if(!empty($user)){
            $_SESSION['admin'] = $user;
            $return['code'] = 1;
            $return['message'] = '登录成功';
            echo json_encode($return);
            exit();
        }else{
            $return['message'] = '用户名或密码错误！';
            echo json_encode($return);
            exit();
        }
    }

    //退出登录
    if($get['tag'] == 'loginOut'){
        session_destroy();
        echo "<script>location.href = '../view/admin/login.html'</script>";
        exit();
    }
