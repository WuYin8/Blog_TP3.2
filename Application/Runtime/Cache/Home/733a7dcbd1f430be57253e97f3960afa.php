<?php if (!defined('THINK_PATH')) exit();?><!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo ($webTitle); ?> - 发表文章</title>
<link href="/public/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<link href="/public/css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="/public/editor/css/editormd.css" />
		<script src="/public/editor/js/jquery.min.js"></script>
		<script src="/public/editor/editormd.min.js"></script>
		<script type="text/javascript">
			$(function() {
				testEditor = editormd("test-editormd", {
						width   : "100%",
						height  : 600,
						syncScrolling : "single",
						path    : "/public/editor/lib/"
					});

			});
		</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Ladies Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="/public/css/flexslider.css" type="text/css" media="screen" />
<script src="/public/js/jquery.min.js"></script>
<link rel="stylesheet" href="/public/css/swipebox.css">
 <!------ Light Box ------>
    <script src="/public/js/jquery.swipebox.min.js"></script> 
    <script type="text/javascript">
		jQuery(function($) {
			$(".swipebox").swipebox();
		});
	</script>
    <!------ Eng Light Box ------>
	 <script src="/public/js/responsiveslides.min.js"></script>
	<script>
    $(function () {
      $("#slider").responsiveSlides({
      	auto: true,
      	nav: true,
      	speed: 500,
        namespace: "callbacks",
        pager: true,
      });
    });
  </script>

</head>
<body>
<!-- header -->
<div class="header">
		<div class="container">
			<div class="logo">
				<a href="index.html"><img src="/public/images/logo.png" class="img-responsive" alt=""></a>
				<div class="user">
				<?php if(!empty($_SESSION['username'])): ?><table >
					<tr>
						<td rowspan="2"><img src="<?php echo ($_SESSION['pic']); ?>" width="50px" height="50px" /></td>
						<td width="10px"></td>
						<td><?php echo ($_SESSION['username']); ?></td>
					</tr>
					<tr>
						<td width="10px"></td>
						<td><a href="/home/user/logout">退出登录</a></td>
					</tr>
					</table><?php endif; ?>
				</div>
			</div>
			<div class="header-bottom">
				<div class="head-nav">
					<span class="menu"> </span>
						<ul class="cl-effect-3">
							<li><a href="/index">首页</a></li>
							<li><a href="/home/blog/blog">博客</a></li>
							<?php if(empty($_SESSION['username'])): ?><li><a href="/home/user/user">登录</a></li>
							<?php else: ?>
							<li><a href="/home/person/person">个人资料</a></li>
								<?php if($_SESSION['grade'] == 1): ?><li class="active"><a href="/home/send/send">发表</a></li>
									<li><a href="/home/admin/login">管理</a></li><?php endif; endif; ?>
								<div class="clearfix"></div>
						</ul>
				</div>
				<!-- script-for-nav -->
					<script>
						$( "span.menu" ).click(function() {
						  $( ".head-nav ul" ).slideToggle(300, function() {
							// Animation complete.
						  });
						});
					</script>
				<!-- script-for-nav -->

				<div class="search2">
					<form method = "get" action = "/home/index/search" id="searchForm">
						<input type="text" id="searchStr" name = "sMsg" placeholder = "Search..">
						<input type="submit" value="" onclick="search_btn()">
						<script type="text/javascript">
							function search_btn()
							{
								$('#searchForm').attr('onsubmit' , '');
								if ($('#searchStr').val().length == 0) {
									alert('搜索内容不得为空');
									$('#searchForm').attr('onsubmit' , 'return false');
									return false;
								}
							}
						</script>
					</form>
				</div>
					<div class="clearfix"></div>
			</div>
		</div>
	</div>
<!-- header -->
	<div class="main">
		<div class="container">
		   <div class="content">	 	 
		 		<div class="section group">
				<div class="col-md-9 cont span_2_of_3">
				  <div class="contact-form">
					<form  method = "post" action = "/home/send/addDetails" id="sendForm">
						<div class="contact-form-row">
							<div class = "title">
								<span class = 'tspan'>标题 :</span>
								<input type="text" class="text" name = "title" id="title" maxlength="30" placeholder="标题长度不得超过30字节" />
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="clearfix"> </div>
						<div class="contact-form-row2">
							<span class = 'tspan'>正文 :</span>
							<div class="replyMd" id="test-editormd">
							<textarea style="display:none;" name="content" id="content"></textarea>
							</div>
							<div>
								<span class = 'tspan'>网站会从图库中随机为文章选择配图</span>
							</div>
						</div>
						<input type="submit" value="发&nbsp;表" onclick="add_details()" />
						<script type="text/javascript">
							function add_details()
							{
								// 博客标题格式判断
								if ($('#title').val().length == 0) {
									alert('博客标题不得为空');
									$('#sendForm').attr('onsubmit', 'return false');
									return false;
								}
								$('#sendForm').attr('onsubmit', '');
								if ($('#title').val().length > 30) {
									alert('博客标题长度不得超过30个字符');
									$('#sendForm').attr('onsubmit', 'return false');
									return false;
								}
								// 博客内容格式判断
								$('#sendForm').attr('onsubmit', '');
								if ($('#content').val().length == 0) {
									alert('博客内容不得为空');
									$('#sendForm').attr('onsubmit', 'return false');
									return false;
								}
							}
						</script>
						<br /><br />
					</form>
						</div>
				<div class="col-md-3 rsidebar span_1_of_3 services_list">
				    
				  </div>	
					<div class="clearfix"> </div>				  
		      </div>
			</div>
		</div>
	</div>
<!-- footer -->
<div class="footer">
	<div class="container">
		<div class="col-md-4 social">
			<h4>友情链接</h4>
			<ul>
				<?php if(is_array($link)): foreach($link as $key=>$vlink): ?><li>
						<a href="<?php echo ($vlink["url"]); ?>"><?php echo ($vlink["name"]); ?></a>
						<br />
						"——<?php echo ($vlink["description"]); ?>"
					</li>
					<!-- <div class="clearfix"></div>	 --><?php endforeach; endif; ?>
			</ul>
		</div>
		<div class="col-md-4 information">
			<h4>网站介绍</h4>
			<p><?php echo ($webInfo); ?></p>
		</div>
		<div class="col-md-4 searby">
			<h4>快速搜索</h4>
			<div class="col-md-6 by1">
					<li><a href="/home/index/search?sMsg=帖子">帖子</a></li>
					<li><a href="/home/index/search?sMsg=侠盗飞车5">侠盗飞车5 </a></li>
					<li><a href="/home/index/search?sMsg=刺客信条">刺客信条</a></li>
					<li><a href="/home/index/search?sMsg=彩虹六号：围攻">彩虹六号：围攻</a></li>
					<li><a href="/home/index/search?sMsg=幽灵行动：荒野">幽灵行动：荒野</a></li>
					<li><a href="/home/index/search?sMsg=Switch">Switch</a></li>
					<li><a href="/home/index/search?sMsg=看门狗">看门狗</a></li>
				</div>
				<div class="col-md-6 by1">
					<li><a href="/home/index/search?sMsg=回复">回复</a></li>
					<li><a href="/home/index/search?sMsg=steam">steam</a></li>
					<li><a href="/home/index/search?sMsg=细胞分裂6">细胞分裂6</a></li>
					<li><a href="/home/index/search?sMsg=荣耀战魂">荣耀战魂</a></li>
					<li><a href="/home/index/search?sMsg=尼尔：机械纪元">尼尔：机械纪元</a></li>
					<li><a href="/home/index/search?sMsg=使命召唤14">使命召唤14</a></li>
					<li><a href="/home/index/search?sMsg=">战地1</a></li>
					
				</div>
				
					<div class="clearfix"> </div>
		</div>
			<div class="clearfix"></div>
			<div class="bottom">
					<p>Copyrights © 2015 <?php echo ($webName); ?> | Template by <a href="<?php echo ($webUrl); ?>"><?php echo ($webTitle); ?></a></p>
				</div>
	</div>
</div>
<!-- footer -->
</body>
</html>