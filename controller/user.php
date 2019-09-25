<?php
    require '../common/tool.php';
    require '../common/conn.php';

    $get = $_GET;
    $post = $_POST;

    $tag = isset($get['tag']) ? $get['tag'] : '';

    $return = [
        'code' => 0,
        'message' => ''
    ];

    //增加文章
    if($tag == 'add'){
        $username = $post['username'];
        $pwd = $post['password'];
        $nickname = $post['nickname'];
        $email = $post['email'];
        $isSuper = $post['is_super'];
        $sql = "insert into admin (username,password,nickname,email,is_super,create_time) value ('". $username ."','" . $pwd . "','" . $nickname . "','" . $email . "','" . $isSuper . "',". time() . ")";
        $date = $conn->query($sql);
        if(!$date){
            $return['message'] = "添加失败";
            echo json_encode($return);
            exit();
        }
        $return['code'] = 1;
        echo json_encode($return);
        exit();
    }

    //根据id删除
    if($tag == 'deleteById'){
        $id = $post['id'];
        //根据id查询
        $sql = "select id from admin where id =". $id;
        $userId = $conn->getRow($sql);

        //根据id删除
        $sql = "delete from admin where id =". $id;
        $delete = $conn->query($sql);

        if($userId['id'] == $id){
            $_SESSION['admin'] = '';
        }
        if($delete){
            $return['code'] = 1;
            echo json_encode($return);
            exit();
        }else{
            $return['message'] = '删除失败';
            echo json_encode($return);
            exit();
        }
    }

    //更新状态
    if($tag == 'state'){
        $state = $post['state'];
        $id = $post['id'];
        $sql = "update admin set status = '". $state ."' where id=". $id ;
        $update = $conn->query($sql);
        if($update){
            $return['code'] = 1;
            echo json_encode($return);
            exit();
        }else{
            $return['message'] = '修改失败';
            echo json_encode($return);
            exit();
        }
    }

    //更新状态
    if($tag == 'updateById'){
        $username = $post['username'];
        $pwd = $post['password'];
        $nickname = $post['nickname'];
        $email = $post['email'];
        $isSuper = $post['is_super'];
        $id = $post['id'];
        $sql = "update admin set username = '". $username ."',password='".$pwd."',nickname='".$nickname."',email='".$email."',is_super='".$isSuper."' where id=". $id ;
        $update = $conn->query($sql);
        if($update){
            $return['code'] = 1;
            echo json_encode($return);
            exit();
        }else{
            $return['message'] = '修改失败';
            echo json_encode($return);
            exit();
        }
    }
