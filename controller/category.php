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

    //增加分类
    if($tag == 'add'){
        $cate = $post['cate_name'];
        $sql = "insert into cate (catename,create_time) value ('". $cate ."',". time() .")";
        $date = $conn->query($sql);
        if(!$date){
            echo "<script>alert('添加失败')</script>";
        }
        echo "<script>location.href = '../../view/admin/cate.php';</script>";
        exit();
    }

    //更新状态
    if($tag == 'state'){
        $state = $post['state'];
        $id = $post['id'];
        $sql = "update cate set state = '". $state ."' where id=". $id ;
        $update = $conn->query($sql);
        if($update){
            $return['code'] = 1;
            $return['message'] = '';
            echo json_encode($return);
            exit();
        }else{
            $return['message'] = '修改失败';
            echo json_encode($return);
            exit();
        }
    }

    //更新分类
    if($tag == 'update'){
        $cate = $post['catename'];
        $id = $post['cate_id'];
        $sql = "update cate set catename = '". $cate ."' where id =". $id;
        $updateCate = $conn->query($sql);
        if($updateCate){
            $return['code'] = 1;
            echo json_encode($return);
            exit();
        }else{
            $return['message'] = '修改失败';
            echo json_encode($return);
            exit();
        }
    }

    //根据id删除
    if($tag == 'deleteById'){
        $id = $post['id'];
        $sql = "delete from cate where id =". $id;
        $delete = $conn->query($sql);
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
