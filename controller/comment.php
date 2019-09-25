<?php
    require '../common/tool.php';
    require '../common/conn.php';

    $get = $_GET;
    $post = $_POST;

    $tag = isset($get['tag']) ? $get['tag'] : '';

    //分页
    if($tag == 'page'){
        $curr = $post['curr'];
        $size = $post['size'];
        $start = ($curr - 1) * $size;
        $sql = "select * from comment";
        $data = $conn->getLimit($sql,$start,$size);
        echo json_encode($data);
        exit();
    }

    //更新状态
    if($tag == 'state'){
        $state = $post['state'];
        $id = $post['id'];
        $sql = "update comment set state = '". $state ."' where id=". $id ;
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

    //根据id删除
    if($tag == 'deleteById'){
        $id = $post['id'];
        $sql = "delete from comment where id =". $id;
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

    //增加评论
    if($tag == 'add'){
        $username = $post['username'];
        $content = $post['content'];
       $article_id = $post['article_id'];
        $sql = "insert into comment (content,article_id,username,create_time) value ('". $content ."'," . $article_id . ",'" . $username . "',".time().")";
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
