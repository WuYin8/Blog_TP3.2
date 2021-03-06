<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>后台管理</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="/public/css/bootstrapAdmin.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="/public/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="/public/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> -->
</head>
<body>
    <div id="wrapper">
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php?m=index&a=index"><img src="/public/images/logo.png" height="50px" /></a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><img src="<?php echo ($_SESSION['adminPic']); ?>" width="50px" height="50px" /></li>
                        <li class = "adminName"><?php echo ($_SESSION['adminName']); ?></li>
                        <li><a href="/home/admin/logout">退出登录</a></li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center user-image-back">
                        <img src="assets/img/find_user.png" class="img-responsive" />
                     
                    </li>
                    <li>
                        <a href="/home/admin/index"><i class="fa fa-table "></i>用户管理</a>
                    </li>
                    <li>
                        <a href="/home/admin/content"><i class="fa fa-qrcode "></i>文章管理</a>
                    </li>
                    <li>
                        <a href="/home/admin/reply"><i class="fa fa-bar-chart-o"></i>回复管理</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-edit "></i>回收站<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="/home/admin/userDel">用户锁定管理</a>
                            </li>
                            <li>
                                <a href="/home/admin/contentDel">文章回收站</a>
                            </li>
                            <li>
                                <a href="/home/admin/replyDel">回复回收站</a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h2>
                            用户锁定管理
                        </h2>
                        <hr />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- <h5>Table  Sample One</h5> -->
                       <form action="/home/admin/userUndel" method = "post">
                        <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th>选择</th>
                                    <th>用户名</th>
                                    <th>头像</th>
                                    <th>手机号</th>
                                    <th>删除</th>
                                </tr>
                                 <?php if(!empty($users)): if(is_array($users)): foreach($users as $key=>$vUser): ?><tr>
                                    <?php if($vUser["undertype"] == 0): ?><td><input type = "checkbox" name = "uid[]" value = "<?php echo ($vUser["uid"]); ?>"/></td>
                                    <?php elseif($vUser["undertype"] == 1): ?>
                                        <td>管理员</td><?php endif; ?>
                                    <td><?php echo ($vUser["username"]); ?></td>
                                    <td><img src="<?php echo ($vUser["picture"]); ?>" style="width: 50px; height: 50px;" /></td>
                                    <td><?php echo ($vUser["phone"]); ?></td>
                                    <td>
                                        <a href="/home/admin/userShit?sid=<?php echo ($vUser["uid"]); ?>"  onclick="alert('该用户的文章与回复会一起删除')">删除
                                    </td>
                                </tr><?php endforeach; endif; endif; ?>
                        </table>
                         <!-- 分页 -->
                        <div class="pagination pagination__posts">
                            <a href="<?php echo ($pages['first']); ?>">首页</a>&nbsp;
                            <a href="<?php echo ($pages['prev']); ?>">前页</a>&nbsp;
                            <a href="<?php echo ($pages['next']); ?>">后页</a>&nbsp;
                            <a href="<?php echo ($pages['end']); ?>">尾页</a>
                        <div class="clearfix"></div>    
                        </div>
                        <!-- 分页 -->
                            <input type = "submit" value = "解锁" class="btn btn-danger btn-lg btn-block" />
                    </form>
                    </div>
                </div>
                    
                
                   
                <!-- /. ROW  -->
               
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="/public/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="/public/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="/public/js/jquery.metisMenu.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="/public/js/custom.js"></script>


</body>
</html>