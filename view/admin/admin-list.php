<?php
    require '../../common/tool.php';
    require '../../common/db.class.php';
    require '../../common/config.php';

    //是否登录
    isLogin();
    //查询所有用户
    $conn = new db($config['db']);
    $sql = "select * FROM admin";
    $admin = $conn->getAll($sql);
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
                    <div class="layui-card"><?php
                        $check = $_SESSION['admin'];
                        if($check['is_super']){
                            echo '<div class="layui-card-header">
                                    <button class="layui-btn" onclick="xadmin.open(\'添加用户\',\'./admin-add.php\',600,400)"><i class="layui-icon"></i>添加</button>
                                </div>';
                        }
                        ?>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th>ID</th>
                                  <th>登录名</th>
                                  <th>密码</th>
                                  <th>昵称</th>
                                  <th>邮件</th>
                                  <th>是否超级管理员</th>
                                  <th>状态</th>
                                  <th>创建时间</th>
                                    <?php
                                        $check = $_SESSION['admin'];
                                        if($check['is_super']){
                                            echo '<th>操作</th>';
                                        }
                                    ?>
                              </thead>
                              <tbody>
                              <?php foreach ($admin as $user){?>
                                <tr>
                                  <td><?php echo $user['id'] ?></td>
                                  <td><?php echo $user['username'] ?></td>
                                  <td><?php echo $user['password'] ?></td>
                                  <td><?php echo $user['nickname'] ?></td>
                                  <td><?php echo $user['email'] ?></td>
                                  <td>
                                      <?php
                                        if($user['is_super']){
                                            echo '超级管理员';
                                        }else{
                                            echo '编辑人员';
                                        }
                                      ?>
                                  </td>
                                  <td class="td-status">
                                      <?php
                                          if($user['status']){
                                              echo '<span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>';
                                          }else{
                                              echo '<span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span>';
                                          }
                                      ?>
                                  </td>
                                    <td><?php if(!empty($user['create_time'])){echo date('Y-m-d',$user['create_time']);} ?></td>
                                   <?php
                                            $check = $_SESSION['admin'];
                                          if($check['is_super']){
                                              echo '<td class="td-manage">';
                                              if($user['status']){
                                                  echo '<a onclick="member_stop(this,\''.$user['id'].'\')" href="javascript:;"  title="停用">
                                                        <i class="layui-icon">&#xe601;</i>
                                                    </a>';
                                              }else{
                                                  echo '<a onclick="member_stop(this,\''.$user['id'].'\')" href="javascript:;"  title="启用">
                                                        <i class="layui-icon">&#xe62f;</i>
                                                    </a>';
                                              }
                                              echo '<a title="编辑"  onclick="xadmin.open(\'编辑\',\'admin-edit.php?id='. $user['id'] .'\')" href="javascript:;">
                                                      <i class="layui-icon">&#xe642;</i>
                                                    </a>
                                                    <a title="删除" onclick="member_del(this,\''. $user['id'] .'\')" href="javascript:;">
                                                      <i class="layui-icon">&#xe640;</i>
                                                    </a>';
                                              echo '</td>';
                                          }
                                  ?>
                                </tr>
                              <?php } ?>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </body>
    <script>
      layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var form = layui.form;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
      });

       /*用户-停用*/
      function member_stop(obj,id){
          layer.confirm('确认要修改吗？',function(index){

              if($(obj).attr('title')=='停用'){
                  $.ajax({
                      url:'../../controller/user.php?tag=state',
                      data:{'id':id,'state':0},
                      dataType: 'json',
                      type:'post',
                      success:function(res){
                          if(res.code){
                              $(obj).attr('title','启用')
                              $(obj).find('i').html('&#xe62f;');

                              $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                              layer.msg('已停用!',{icon: 5,time:1000});
                          }else{
                              alert(res.message);
                          }
                      }
                  });
                //发异步把用户状态进行更改
              }else{
                  $.ajax({
                      url:'../../controller/user.php?tag=state',
                      data:{'id':id,'state':1},
                      dataType: 'json',
                      type:'post',
                      success:function(res){
                          if(res.code){
                              $(obj).attr('title','停用')
                              $(obj).find('i').html('&#xe601;');

                              $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                              layer.msg('已启用!',{icon: 5,time:1000});
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
                  url:'../../controller/user.php?tag=deleteById',
                  data:{'id':id},
                  dataType:'json',
                  type:'post',
                  success:function(res){
                      if(res.code){
                          $(obj).parents("tr").remove();
                          layer.msg('已删除!',{icon:1,time:1000});
                          parent.location.reload();
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
</html>