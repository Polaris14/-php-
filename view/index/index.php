<?php
    require '../../common/db.class.php';
    require '../../common/config.php';

    //查询所有文章
    $conn = new db($config['db']);
    $sql = "select * FROM article";
    $articleList = $conn->getAll($sql);

    //站长推荐
    $sql = "select * from article where is_top = '1' order by create_time desc LIMIT 0,8";
    $top = $conn->getAll($sql);

    //文章分类
    $sql = "select * FROM cate where state = '1'";
    $cate = $conn->getAll($sql);

    //单个分类统计
    $result = [];
    foreach ($cate as $value){
        $sql = "select count(*) as count FROM article where cate = ".$value['id'];
        $value['count'] =  $conn->getOne($sql);
        $result[] = $value;
    }

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="keywords" content="个人博客" />
    <meta name="description" content="个人博客" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/base.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link href="css/m.css" rel="stylesheet">
    <link rel="stylesheet" href="../admin/css/font.css">
    <link rel="stylesheet" href="../admin/css/xadmin.css">
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/comm.js"></script>
    <script src="../admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="../admin/js/xadmin.js"></script>
    <!--[if lt IE 9]>
    <script src="js/modernizr.js"></script>
    <![endif]-->
</head>
<body>
<header class="header-navigation" id="header">
  <nav><div class="logo"><a href="index.php">个人博客</a></div>
    <h2 id="mnavh"><span class="navicon"></span></h2>
    <ul id="starlist">
      <li><a href="index.php"></a></li>
    </ul>
</nav>
</header>
<article>
  <aside class="l_box">
      <div class="about_me">
        <h2>关于我</h2>
        <ul>
          <i><img src="../../upload/timg.jpg"></i>
          <p><b>唐长辉</b>，一个90后站长！19年毕业。一直潜心研究web后端技术，一边学习一边积累经验，分享一些个人心得。</p>
        </ul>
      </div>
      <div class="fenlei">
        <h2>文章分类</h2>
        <ul>
            <?php foreach ($result as $value){ ?>
                <li><a href="javascript:;" onclick="getList('<?php echo $value['id']?>','<?php echo $value['count']?>')"><?php echo $value['catename']?>（<?php echo $value['count']?>）</a></li>
            <?php } ?>
        </ul>
      </div>
      <div class="tuijian">
        <h2>站长推荐</h2>
        <ul>
            <?php foreach ($top as $value){ ?>
                <li><a href="info.php?id=<?php echo $value['id'];?>"><?php echo $value['title']?></a></li>
            <?php } ?>
        </ul>
      </div>
      <div class="guanzhu">
        <h2>关注我 么么哒</h2>
        <ul>
          <img src="images/wx.png">
        </ul>
      </div>
  </aside>
  <main class="r_box">
      <div id="list">
      </div>
      <div class="layui-card-body ">
          <div id="demo1"></div>
      </div>
  </main>
</article>
    <a href="#" class="cd-top">Top</a>
</body>
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
<script>
    var laypage = '';
    layui.use('laypage',function() {
        laypage = layui.laypage;
        //分页
        laypage.render({
            elem: 'demo1'
            ,count: <?php echo count($articleList);?> //数据总数
            ,limit:10
            ,jump: function(obj){
                console.log(obj)
                $.ajax({
                    url:'../../controller/article.php?tag=page',
                    dataType:'json',
                    type:'post',
                    data:{'curr':obj.curr,'size':obj.limit},
                    success:function(res){
                        var html = "";
                        $.each(res,function(key,value){
                            html += '<li>';
                            if(value.pic != '' && value.pic != null){
                                html += '<i><a href="info.php?id='+value.id+'"><img src="../../upload/'+value.pic+'"></a></i>';
                            }
                            html += '<h3><a href="info.php?id='+value.id+'">'+value.title+'</a></h3>';
                            html += '<p>'+value.content.replace(/<[^>]*>|/g,"")+'</p>';
                            html += '</li>';
                        });
                        $('#list').html(html);
                    }
                });
            }
        });
    });

    function getList(cid,count) {
        laypage.render({
            elem: 'demo1'
            ,count: count //数据总数
            ,limit:10
            ,jump: function(obj){
                console.log(obj)
                $.ajax({
                    url:'../../controller/article.php?tag=page',
                    dataType:'json',
                    type:'post',
                    data:{'curr':obj.curr,'size':obj.limit,'cid':cid},
                    success:function(res){
                        var html = "";
                        var count = 0;
                        $.each(res,function(key,value){
                            html += '<li>';
                            if(value.pic != '' && value.pic != null){
                                html += '<i><a href="/"><img src="../../upload/'+value.pic+'"></a></i>';
                            }
                            html += '<h3><a href="/">'+value.title+'</a></h3>';
                            html += '<p>'+value.content+'</p>';
                            html += '</li>';
                            count++;
                        });
                        $('#list').html(html);
                    }
                });
            }
        });

    }
</script>
</html>
