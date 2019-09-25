<?php
    require '../common/uuid.php';

    // 允许上传的图片后缀
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);     // 获取文件后缀名

    $return = [
        'code'  => 0,
        'url'   => '',
        'msg'   => ''
    ];

    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 204800)   // 小于 200 kb
        && in_array($extension, $allowedExts))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            $return['msg'] = $_FILES["file"]["error"];
            echo json_encode($return);
        }
        else
        {
            $name = guid() . "." .$extension;
            move_uploaded_file($_FILES["file"]["tmp_name"], "../upload/" . $name);
            $return['code'] = 1;
            $return['url'] = $name;
            echo json_encode($return);
        }
    }
    else
    {
        $return['msg'] = '非法的文件格式';
        echo json_encode($return);
    }
