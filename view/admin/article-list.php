<?php
require '../../common/tool.php';
require '../../common/db.class.php';
require '../../common/config.php';

    //是否登录
    isLogin();
    //查询所有文章
    $conn = new db($config['db']);
    $sql = "select * FROM article";
    $count = $conn->getAll($sql);

    //查询分类
    $sql = "select id,catename from cate where state = '1'";
    $cate = $conn->getAll($sql);
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
        <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <script src="./lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/xadmin.js"></script>
    </head>
    
    <body>
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">演示</a>
                <a>
                    <cite>导航元素</cite></a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <button class="layui-btn" onclick="xadmin.open('添加用户','./article-add.php',800,600)">
                                <i class="layui-icon"></i>添加</button></div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                                <thead>
                                    <tr>
                                        <th>文章ID</th>
                                        <th>标题</th>
                                        <th>标签</th>
                                        <th>是否热推</th>
                                        <th>文章分类</th>
                                        <th>创建时间</th>
                                        <th>操作</th></tr>
                                </thead>
                                <tbody id="list">
                                    <!--列表-->
                                </tbody>
                            </table>
                        </div>
                        <div class="layui-card-body ">
                            <div id="demo1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        layui.use(['form','laypage', 'layer'],function() {
            var form = layui.form;
            var laypage = layui.laypage
                ,layer = layui.layer;
            //分页
            laypage.render({
                elem: 'demo1'
                ,count: <?php echo count($count);?> //数据总数
                ,limit:5
                ,jump: function(obj){
                    console.log(obj)
                    $.ajax({
                        url:'../../controller/article.php?tag=page',
                        dataType:'json',
                        type:'post',
                        data:{'curr':obj.curr,'size':obj.limit},
                        success:function(res){
                            var html = "";
                            var cate = <?php echo json_encode($cate);?>;
                            $.each(res,function(key,value){

                                //获取状态
                                var top = '否';
                                if(value.is_top == '1'){
                                    top = '是';
                                }

                                //时间格式转换
                                var date = new Date(value.create_time * 1000);

                                //获取分类
                                var category = '';
                                $.each(cate,function(index,object){
                                    if(object.id == value.cate){
                                        category = object.catename;
                                    }
                                });
                                html += '<tr>';
                                html += '<td>'+value.id+'</td>';
                                html += '<td>'+value.title+'</td>';
                                html += '<td>'+value.tags+'</td>';
                                html += '<td>'+top+'</td>';
                                html += '<td>'+category+'</td>';
                                html += '<td>'+date.getFullYear()+ '-' + (date.getMonth()+1) + '-'+date.getDate()+'</td>';
                                html += '<td class="td-manage">';
                                html += '<a title="查看" onclick=' + 'xadmin.open("编辑","article-view.php?id='+ value.id +'") href="javascript:;">'
                                html += '<i class="layui-icon">&#xe63c;</i></a>';
                                html += '<a title="删除" onclick="member_del(this,'+value.id+')" href="javascript:;">';
                                html += '<i class="layui-icon">&#xe640;</i></a>';
                                html += '</td></tr>';
                            });
                            $('#list').html(html);
                        }
                    });
                }
            });
        });

        /*用户-停用*/
        function member_stop(obj, id) {
            layer.confirm('确认要停用吗？',
            function(index) {

                if ($(obj).attr('title') == '启用') {

                    //发异步把用户状态进行更改
                    $(obj).attr('title', '停用');
                    $(obj).find('i').html('&#xe62f;');

                    $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                    layer.msg('已停用!', {
                        icon: 5,
                        time: 1000
                    });

                } else {
                    $(obj).attr('title', '启用');
                    $(obj).find('i').html('&#xe601;');

                    $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                    layer.msg('已启用!', {
                        icon: 5,
                        time: 1000
                    });
                }

            });
        }

        /*用户-删除*/
        function member_del(obj, id) {
            layer.confirm('确认要删除吗？',
            function(index) {
                //发异步删除数据
                $.ajax({
                    url:'../../controller/article.php?tag=deleteById',
                    data:{'id':id},
                    dataType: 'json',
                    type:'post',
                    success:function(res){
                        if(res.code){
                            $(obj).parents("tr").remove();
                            layer.msg('已删除!', {
                                icon: 1,
                                time: 1000
                            });
                            location.reload();
                        }else{
                            alert(res.message);
                        }
                    }
                });
            });
        }

        function delAll(argument) {

            var data = tableCheck.getData();

            layer.confirm('确认要删除吗？' + data,
            function(index) {
                //捉到所有被选中的，发异步进行删除
                layer.msg('删除成功', {
                    icon: 1
                });
                $(".layui-form-checked").not('.header').parents('tr').remove();
            });
        }</script>

</html>