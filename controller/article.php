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
        $title = $post['title'];
        $tags = $post['tags'];
        $content = $post['content'];
        $isTop = $post['is_top'];
        $cate = $post['cate'];
        $pic = $post['pic'];
        if($pic == null){
            $pic = '';
        }
        $sql = "insert into article (title,tags,content,is_top,cate,create_time,update_time,pic) value ('". $title ."','" . $tags . "','" . $content . "','" . $isTop . "'," . $cate . ",". time() . ",".time().",'".$pic."')";
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
        $sql = "delete from article where id =". $id;
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

    //分页
    if($tag == 'page'){
        $curr = $post['curr'];
        $size = $post['size'];
        $where = '';
        if (isset($post['cid'])){
            $where = 'where cate = '.$post['cid'];
        }
        $start = ($curr - 1) * $size;
        $sql = "select * from article $where order by create_time desc";
        $data = $conn->getLimit($sql,$start,$size);
        echo json_encode($data);
        exit();
    }
