<?php
require '../../common/tool.php';
require '../../common/db.class.php';
require '../../common/config.php';

    //是否登录
    isLogin();
    //查询所有分类
    $conn = new db($config['db']);
    $sql = "select * FROM cate WHERE state = '1';";
    $date = $conn->getAll($sql);
?>
<!DOCTYPE html>
<html class="x-admin-sm">
    
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
        <script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/xadmin.js"></script>
        <script type="text/javascript" src="./js/wangEditor.min.js"></script>
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
            <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
            <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]--></head>
    
    <body>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <label for="title" class="layui-form-label">
                            <span class="x-red">*</span>标题</label>
                        <div class="layui-input-inline">
                            <input type="text" id="title" name="title" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="tags" class="layui-form-label">
                            <span class="x-red">*</span>标签</label>
                        <div class="layui-input-inline">
                            <input type="text" id="tags" name="tags" required="" lay-verify="required" autocomplete="off" class="layui-input"></div>
                    </div>
                    <div class="layui-form-item">
                        <label for="is_top" class="layui-form-label">
                            <span class="x-red">*</span>是否推荐</label>
                        <div class="layui-input-inline">
                            <input type="checkbox" id="is_top" name="is_top"  lay-text="on|off" lay-skin="switch">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label for="cate" class="layui-form-label">
                            <span class="x-red">*</span>文章分类</label>
                        <div class="layui-input-inline">
                            <select id="cate" name="cate" class="valid">
                                <?php foreach ($date as $value){ ?>
                                    <option value="<?php echo $value['id']?>"><?php echo $value['catename']?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
        <!--图片上传-->
                    <div class="layui-form-item">
                        <label for="test1" class="layui-form-label">
                            <span class="x-red">*</span>上传图片</label>
                        <div class="layui-input-inline">
                            <div class="layui-upload">
                                <button type="button"  class="layui-btn" id="test1">上传图片</button>
                                <div class="layui-upload-list">
                                    <img class="layui-upload-img" id="demo1" width="100px" height="80px">
                                    <p id="demoText"></p>
                                </div>
                            </div>
                        </div>
                    </div>



             <div class="layui-form-item layui-form-text">
            <label for="editor" class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <div id="editor""></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label"></label>
            <button class="layui-btn" lay-filter="add" lay-submit="">增加</button></div>
        </form>
        </div>
        </div>
        <script>
            layui.use(['form', 'layer','upload'],function() {
                var $ = layui.jquery,upload = layui.upload;
                var form = layui.form,
                layer = layui.layer;

                //定义图片
                var pic = '';

                //普通图片上传
                var uploadInst = upload.render({
                    elem: '#test1'
                    , url: '../../controller/upload.php'
                    , before: function (obj) {
                        //预读本地文件示例，不支持ie8
                        obj.preview(function (index, file, result) {
                            $('#demo1').attr('src', result); //图片链接（base64）
                        });
                    }
                    , done: function (res) {
                        if (res.code) {
                            pic = res.url;
                            return layer.msg('上传成功');
                        }
                        return layer.msg(res.msg);
                    }
                    , error: function () {
                        //演示失败状态，并实现重传
                        var demoText = $('#demoText');
                        demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                        demoText.find('.demo-reload').on('click', function () {
                            uploadInst.upload();
                        });
                    }
                });

                //自定义验证规则
                form.verify({
                    nikename: function(value) {
                        if (value.length < 5) {
                            return '昵称至少得5个字符啊';
                        }
                    },
                    pass: [/(.+){6,12}$/, '密码必须6到12位'],
                    repass: function(value) {
                        if ($('#L_pass').val() != $('#L_repass').val()) {
                            return '两次密码不一致';
                        }
                    }
                });


                //富文本编辑器
                var E = window.wangEditor;
                var editor = new E('#editor');
                editor.customConfig.uploadImgShowBase64 = true
                editor.customConfig.showLinkImg = false;
                editor.create();

                //监听提交
                form.on('submit(add)',function(data) {
                    var top = data.field.is_top;
                    if(top == 'on'){
                        data.field.is_top = '1';
                    }else{
                        data.field.is_top = '0';
                    }
                    //图片
                    data.field.pic = pic;
                    console.log(data);
                    //富文本编辑器内容
                    //console.log(editor.txt.html());
                    data.field.content = editor.txt.html();
                    //发异步，把数据提交给php
                    $.ajax({
                        url:'../../controller/article.php?tag=add',
                        type:'post',
                        dataType:'json',
                        data:data.field,
                        success:function(res){
                            if(res.code){
                                layer.alert("增加成功", {
                                        icon: 6
                                    },
                                    function() {
                                        // 获得frame索引
                                        var index = parent.layer.getFrameIndex(window.name);
                                        //关闭当前frame
                                        parent.layer.close(index);
                                        parent.location.reload();
                                    });
                            }else{
                                alert(res.message);
                            }
                        }
                    });
                    return false;
                });

            });
        </script>
    </body>

</html>