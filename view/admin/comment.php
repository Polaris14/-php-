<?php
    require '../../common/tool.php';
    require '../../common/db.class.php';
    require '../../common/config.php';

    //是否登录
    isLogin();
    //查询所有评论
    $conn = new db($config['db']);
    $sql = "select * FROM comment";
    $count = $conn->getAll($sql);

    //查询文章
    $sql = "select id,title from article";
    $article = $conn->getAll($sql);
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
        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
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
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>ID</th>
                                  <th>内容</th>
                                  <th>文章标题</th>
                                  <th>用户名</th>
                                  <th>评论时间</th>
                                  <th>状态</th>
                                  <th>操作</th>
                              </thead>
                              <tbody id="list">
                                <!--list-->
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
      layui.use(['form','laypage', 'layer'], function(){
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
                      url:'../../controller/comment.php?tag=page',
                      dataType:'json',
                      type:'post',
                      data:{'curr':obj.curr,'size':obj.limit},
                      success:function(res){
                          var html = "";
                          var article = <?php echo json_encode($article);?>;
                          $.each(res,function(key,value){

                              //获取状态
                              var isState = {
                                  'clz':'layui-btn-disabled',
                                  'pic':'&#xe62f;',
                                  'title':'启用',
                                  'btn':'已停用'
                              };
                              var top = '否';
                              if(value.state == '1'){
                                  isState.clz = '';
                                  isState.pic = '&#xe601;';
                                  isState.title = '停用';
                                  isState.btn = '已启用';
                              }

                              //时间格式转换
                              var date = new Date(value.create_time * 1000);

                              //获取分类
                              var title = '';
                              $.each(article,function(index,object){
                                  if(object.id == value.article_id){
                                      title = object.title;
                                  }
                              });
                              html += '<tr>';
                              html += '<td>'+value.id+'</td>';
                              html += '<td>'+value.content+'</td>';
                              html += '<td>'+title+'</td>';
                              html += '<td>'+value.username+'</td>';
                              html += '<td>'+date.getFullYear()+ '-' + date.getMonth()+'-'+date.getDay()+'</td>';
                              html += '<td class="td-status">';
                              html += '<span class="layui-btn layui-btn-normal layui-btn-mini '+isState.clz+'">'+isState.btn+'</span></td>';
                              html += '<td class="td-manage">';
                              html += '<a onclick="member_stop(this,\''+value.id+'\')" href="javascript:;"  title="'+isState.title+'">';
                              html += '<i class="layui-icon">'+isState.pic+'</i>';
                              html += '</a>';
                              html += '<a title="删除" onclick="member_del(this,\''+value.id+'\')" href="javascript:;">';
                              html += '<i class="layui-icon">&#xe640;</i>';
                              html += '</a>';
                              html += '</td>';
                              html += '</tr>';
                          });
                          $('#list').html(html);
                      }
                  });
              }
          });
      });

       /*用户-停用*/
      function member_stop(obj,id){
          layer.confirm('确认要修改吗？',function(index){

              if($(obj).attr('title')=='启用'){
                  $.ajax({
                      url:'../../controller/comment.php?tag=state',
                      data:{'id':id,'state':1},
                      dataType: 'json',
                      type:'post',
                      success:function(res){
                          if(res.code){
                              $(obj).attr('title','启用')
                              $(obj).find('i').html('&#xe601;');

                              $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                              layer.msg('已启用!',{icon: 5,time:1000});
                          }else{
                              alert(res.message);
                          }
                      }
                  });
              }else{
                  $.ajax({
                      url:'../../controller/comment.php?tag=state',
                      data:{'id':id,'state':0},
                      dataType: 'json',
                      type:'post',
                      success:function(res){
                          if(res.code){
                              $(obj).attr('title','停用')
                              $(obj).find('i').html('&#xe62f;');

                              $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                              layer.msg('已停用!',{icon: 5,time:1000});
                          }else{
                              alert(res.message);
                          }
                      }
                  });
              }
              
          });
      }

      /*用户-删除*/
      function member_del(obj,id){
          layer.confirm('确认要删除吗？',function(index){
              //发异步删除数据
              $.ajax({
                  url:'../../controller/comment.php?tag=deleteById',
                  data:{'id':id},
                  dataType: 'json',
                  type:'post',
                  success:function(res){
                      if(res.code){
                          $(obj).parents("tr").remove();
                          layer.msg('已删除!',{icon:1,time:1000});
                          location.reload();
                      }else{
                          alert(res.message);
                      }
                  }
              });
          });
      }



      function delAll (argument) {

        var data = tableCheck.getData();
  
        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
</html>