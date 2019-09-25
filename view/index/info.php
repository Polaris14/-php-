<?php
    require '../../common/db.class.php';
    require '../../common/config.php';

    $get = $_GET;
    $id = isset($get['id']) ? $get['id'] : '';
    if($id != ''){
        $conn = new db($config['db']);
        //根据id查询文章
        $sql = "select * FROM article where id = ".$id;
        $article = $conn->getRow($sql);

        //增加阅读
        $count = $article['count_a'];
        if($count == null || $count == ''){
            $count == 0;
        }
        $count += 1;
        $sql = "update article set count_a = ".$count." where id = ".$id;
        $conn->query($sql);

        //根据id查询文章
        $sql = "select * FROM article where id = ".$id;
        $article = $conn->getRow($sql);

        //根据id查询评论
        $sql = "select * from comment where article_id = ".$id." and state = '1' order by create_time desc";
        $comment = $conn->getAll($sql);
    }else{
        echo "<script>location.href = 'index.php'</script>";
    }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>内容页</title>
<meta name="keywords" content="个人博客,杨青个人博客,个人博客模板,杨青" />
<meta name="description" content="杨青个人博客，是一个站在web前端设计之路的女程序员个人网站，提供个人博客模板免费资源下载的个人原创网站。" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/base.css" rel="stylesheet">
<link href="css/index.css" rel="stylesheet">
<link href="css/info.css" rel="stylesheet">
<link href="css/m.css" rel="stylesheet">
<link href="css/layui.css" rel="stylesheet">
<script src="js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/comm.js"></script>
<!--[if lt IE 9]>
<script src="js/modernizr.js"></script>
<![endif]-->
</head>
<body>
<header class="header-navigation" id="header">
  <nav>
    <div class="logo"><a href="index.php">个人博客</a></div>
    <h2 id="mnavh"><span class="navicon"></span></h2>
    <ul id="starlist">
      <li><a href="index.php"></a></li>
    </ul>
  </nav>
</header>
<article>
  <main style="width: 100%">
    <div class="infosbox">
      <div class="newsview">
        <h3 class="news_title"><?php echo $article['title']?></h3>
        <div class="bloginfo">
          <ul>
            <li class="author">作者：<a href="/">唐长辉</a></li>
            <li class="lmname"><a href="/">学无止境</a></li>
            <li class="timer">时间：<?php echo date('Y-m-d',$article['create_time'])?></li>
            <li class="view"><?php echo (int)$article['count_a']?>人已阅读</li>
          </ul>
        </div>
        <div class="tags"><a href="javascript:;"><?php echo $article['tags']?></a></div>
        <div class="news_about"><strong>简介</strong>个人博客，用来做什么？我刚开始就把它当做一个我吐槽心情的地方，也就相当于一个网络记事本，写上一些关于自己生活工作中的小情小事，也会放上一些照片，音乐。每天工作回家后就能访问自己的网站，一边听着音乐，一边写写文章。</div>
        <div class="news_con">
            <?php echo $article['content']?>
        </div>
      </div>

      <div class="news_pl">
        <h2>文章评论</h2>
        <div class="gbko" id="list">
            <?php foreach ($comment as $value){?>
              <div class="fb">
                <ul>
                  <p class="fbtime"><span><?php echo date('Y-m-d',$value['create_time'])?></span><?php echo $value['username']?></p>
                  <p class="fbinfo"><?php echo $value['content']?></p>
                </ul>
              </div>
            <?php }?>
          <script>
                function sub(){
                    var html = '';
                    var username = document.getElementById('username').value;
                    var content = document.getElementById('saytext').value;
                    var article_id = document.getElementById('article_id').value;
                    if(username != '' && content != '' && article_id != ''){
                        $.ajax({
                            url:'../../controller/comment.php?tag=add',
                            dataType:'json',
                            data:{'username':username,'content':content,'article_id':article_id},
                            type:'post',
                            success:function (res) {
                                var date = new Date();
                                html += '<div class="fb">';
                                html += '<ul>';
                                html += '<p class="fbtime"><span>'+ date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()+'</span>'+username+'</p>';
                                html += '<p class="fbinfo">'+content+'</p>';
                                html += '</ul>';
                                html += '</div>';
                                var old = $('#list').html();
                                $('#list').html(html + old);
                            }
                        });
                    }else{
                        alert('用户名或内容为空');
                    }
                }
		  </script>
          <form>
            <div id="plpost">
              <p class="saying">来说两句吧...</p>
              <p class="yname"><span>用户名:</span>
                <input name="username" type="text" id="username" value="" size="16">
              </p>
              <textarea name="content" rows="6" id="saytext" value=""></textarea>
              <input class="layui-btn layui-btn-primary" type="button" onclick="sub()" value="提交">
              <input type="hidden" id="article_id" value="<?php echo $id?>">
            </div>
          </form>
        </div>
      </div>

    </div>
  </main>
</article>
<a href="#" class="cd-top">Top</a>
</body>
</html>
