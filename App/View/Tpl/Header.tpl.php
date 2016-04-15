<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo $site_info['static_resource_path']?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="<?php echo $site_info['static_resource_path']?>/dist/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- DATA TABLES -->
    <link href="<?php echo $site_info['static_resource_path']?>/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo $site_info['static_resource_path']?>/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $site_info['static_resource_path']?>/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo $site_info['static_resource_path']?>/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $site_info['static_resource_path']?>/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $site_info['static_resource_path']?>/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />

</head>
<body class="skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>钱</b>呢</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>钱呢</b>去哪里呀</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo $site_info['static_resource_path']?>/dist/img/user2-160x160.jpg" class="user-image" alt="User Image" />
                                <span class="hidden-xs"> <?php echo $user_info['nickname']?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo $site_info['static_resource_path']?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo $user_info['nickname']."-".$user_info['job']?>
                                        <small>Member since Nov. 2012</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat" data-toggle="modal" data-target="#userinfoModal" >个人资料</a>
                                    </div>
                                    <div class="pull-right">
                                        <form action="/index/logout" method="post">
                                            <button class="btn btn-danger btn-flat">退出平台</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo $site_info['static_resource_path']?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                    </div>
                    <div class="pull-left info">
                        <p><?php echo $user_info['nickname'];?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> 你好</a>
                    </div>
                </div>
                <!-- search form -->
                <form action="/index/search" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search..." />
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
                    </div>
                </form>
                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-tags"></i>
                            <span>权限操作</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#" data-action="add" data-toggle="modal" data-target="#updateModal" ><i class="fa fa-circle-o"></i> 添加新记录</a></li>
                            <li><a href="#" data-action="dealBatch" data-toggle="modal" data-target="#dealModal"><i class="fa fa-circle-o"></i> 结算所有</a></li>
                            <li><a href="/logs/view"><i class="fa fa-circle-o"></i> 查看操作日志</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-home"></i>
                            <span>个人中心</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#" data-toggle="modal" data-target="#userinfoModal"><i class="fa fa-circle-o"></i> 个人资料</a></li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> 修改密码</a></li>
                        </ul>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
