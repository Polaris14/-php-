<?php
    require '../../common/tool.php';
    require '../../common/db.class.php';
    require '../../common/config.php';

    //是否登录
    isLogin();
    //查询所有分类
    $conn = new db($config['db']);
    $sql = "select * from cate";
    $dateCount = $conn->getAll($sql);
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
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
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
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body ">
                            <form action="../../controller/category.php?tag=add" method="post" class="layui-form layui-col-space5">
                                <div class="layui-input-inline layui-show-xs-block">
                                    <input class="layui-input" placeholder="分类名" name="cate_name"></div>
                                <div class="layui-input-inline layui-show-xs-block">
                                    <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加</button>
                                </div>
                            </form>
                            <hr>
                        </div>
                        <div class="layui-card-body ">
                            <table class="layui-table layui-form">
                              <thead>
                                <tr>
                                  <th width="70">ID</th>
                                  <th>栏目名</th>
                                  <th>创建时间</th>
                                  <th width="80">状态</th>
                                  <th width="250">操作</th>
                              </thead>
                              <tbody class="x-cate" id="list">
                                    <!--list表-->
                                    <?php
                                            foreach ($dateCount as $value){
                                    ?>
                                    <tr cate-id='<?php echo $value['id']; ?>' fid='0' >
                                        <td><?php echo $value['id']; ?></td>
                                        <td>
                                            <?php echo $value['catename']; ?>
                                        </td>
                                        <td><?php echo date('Y-m-d',$value['create_time']); ?></td>
                                        <td>
                                            <input type="checkbox" id="switch" name="switch"  lay-text="开启|停用" <?php if($value['state']){echo 'checked';}else{echo '';} ?> lay-skin="switch" lay-filter="switchGoodsID" switch_id="<?php echo $value['id']; ?>">
                                        </td>
                                        <td class="td-manage">
                                            <button class="layui-btn layui-btn layui-btn-xs"  onclick="xadmin.open('编辑','cate-edit.php?id=' + <?php echo $value['id']; ?>)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                            <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="member_del(this,<?php echo $value['id']; ?>)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                                        </td>
                                    </tr>
                                <?php }?>
                              </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
          layui.use(['form','laypage', 'layer'], function(){
              var form = layui.form;
              var laypage = layui.laypage
                  ,layer = layui.layer;
              form.on('switch(switchGoodsID)',function (data) {
                  //开关是否开启，true或者false
                  var checked = data.elem.checked;
                  var id = data.elem.attributes['switch_id'].nodeValue;
                  var state = 0;
                  if(checked){
                      state = 1;
                  }
                  $.ajax({
                     url:'../../controller/category.php?tag=state',
                      type:'post',
                      dataType:'json',
                      data:{'state':state,'id':id},
                      success:function(res){
                         if(!res.code){
                             alert(res.message);
                         }
                      }
                  });
              });
          });

           /*用户-删除*/
          function member_del(obj,id){
              layer.confirm('确认要删除吗？',function(index){
                  //发异步删除数据
                  $.ajax({
                      url:'../../controller/category.php?tag=deleteById',
                      type:'post',
                      dataType:'json',
                      data:{'id':id},
                      success:function(res){
                          if(!res.code){
                              alert(res.message);
                          }
                      }
                  });
                  $(obj).parents("tr").remove();
                  layer.msg('已删除!',{icon:1,time:1000});
                  location.reload();
              });
          }
        </script>
    </body>
</html>
