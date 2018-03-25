<?php if (!defined('THINK_PATH')) exit();?><!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title><?php echo ($webTitle); ?> - 博客详页</title>
<link href="/public/css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<link href="/public/css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="/public/editor/css/editormd.css" />
		<script src="/public/editor/js/jquery.min.js"></script>
		<script src="/public/editor/editormd.min.js"></script>
		<script type="text/javascript">
			$(function() {
				testEditor = editormd("test-editormd", {
						width   : "100%",
						height  : 400,
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
				<a href="/index"><img src="/public/images/logo.png" class="img-responsive" alt=""></a>
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
							<li class="active"><a href="/home/blog/blog">博客</a></li>
							<?php if(empty($_SESSION['username'])): ?><li><a href="/home/user/user">登录</a></li>
							<?php else: ?>
							<li><a href="/home/person/person">个人资料</a></li>
								<?php if($_SESSION['grade'] == 1): ?><li><a href="/home/send/send">发表</a></li>
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
				  <div class="blog_grid">
		  	   	   <h2 class="post_title"><?php echo ($details[0]['title']); ?></h2>
		  	   	   <img src="/public<?php echo ($details[0]['path']); ?>" class="img-responsive" alt="" />
		  	   	   <p class="single"><?php echo ($details[0]['content']); ?></p>
		  	        <ul class="links">
				  		<li><i class="date"> </i><span class="icon_text"><?php echo (date("Y-m-d h:i:s",$details[0]['addtime'])); ?></span></li>
						<li><i class="admin"> </i><span class="icon_text"><?php echo ($author['username']); ?></span></li>
						<li><i class="comments"> </i><span class="icon_text"><?php echo ($replyCount); ?> 回复</span></li>
						<li><i class="views"> </i><span class="icon_text"><?php echo ($details[0]['hits']); ?> 浏览</span></li>
		  		    </ul>
		  		    <ul class="comment-list">
		  		    	<h5 class="post-author_head"><span color = "tomato">作者</span></h5>
		  		         <li><img src="<?php echo ($author[0]['picture']); ?>" width="50px" height="50px" class="img-responsive" alt="">
		  		        	<p class = "single">View all posts by: <?php echo ($author[0]['username']); ?></p>
		  		           <div class="clear"></div>
		  		         </li>
		  		     </ul>
					<?php if($reply != 0): if(is_array($reply)): foreach($reply as $key=>$vreply): ?><ul class="comment-list">
				  		    	<h5 class="post-author_head">回复 </h5>
				  		         <li><img src="<?php echo ($vreply["picture"]); ?>" width="50px" height="50px" class="img-responsive" alt="">
				  		         <span><?php echo ($vreply["username"]); ?>&nbsp;:&nbsp;<?php echo (date("Y-m-d h:i:s",$vreply["addtime"])); ?></span>
				  		        	<p class = "reply"><?php echo ($vreply["content"]); ?></p>
				  		           <div class="clear"></div>
				  		         </li>
				  		     </ul><?php endforeach; endif; endif; ?>
					<!-- 分页 -->
					<div class="pagination pagination__posts">
						<ul>
							<li class="first"><a href="<?php echo ($pages['first']); ?>">首页</a></li>
							<li class="prev"><a href="<?php echo ($pages['prev']); ?>">前页</a></li>
							<li class="next"><a href="<?php echo ($pages['next']); ?>">后页</a></li>
							<li class="last"><a href="<?php echo ($pages['end']); ?>">尾页</a></li>
								<div class="clearfix"></div>	
						</ul>
					</div>
		  		     <div class="comments-area">
	  		        		<?php if(empty($_SESSION['username'])): ?><h3>请登录后再发表回复</h3>
					<?php else: ?>
		  		        		<h3>发表回复</h3>
						<form method = "post" action = "/home/single/addReply?sid=<?php echo ($_GET['sid']); ?>" id="replyForm">
						<div class="replyMd" id="test-editormd">
							<textarea style="display:none;" name="content" id="content"></textarea>
						</div>
						<p>
							<input type="submit" value="回复" onclick="add_reply()">
							<script type="text/javascript">
								function add_reply()
								{
									if ($('#content').val().length == 0) {
										alert('回复内容不得为空');
										$('#replyForm').attr('onsubmit', 'return false');
										return false;
									}
								}
							</script>
						</p>
					</form><?php endif; ?>
					  </div>
		  	     </div>
		  	     
				</div>
				<div class="col-md-3 rsidebar span_1_of_3 services_list">
				    <ul>
				    	<h3>Note . Rember</h3>
						<li>Remeber , no russia</li>
						<li>请患者不要死在走廊上</li>
						<li>飞来的子弹有优先通行权</li> 
						<li>The cake is a lie</li>
						<li>万物皆虚，万物皆允</li>	
						<li>永远不要在敌人犯错误的时候打断他们</li>	                 
						<li>War , war never changes</li>	   
				    </ul>	
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