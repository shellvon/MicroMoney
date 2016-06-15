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
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo $site_info['static_resource_path']?>/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>

    <style type="text/css">
        .datepicker{
            z-index:1151 !important;
        }
        .#operation-logs{
            word-wrap: break-word;
            word-break: break-all;
         }
        .log-data{
            overflow-x: scroll;
            width: 240px;
        }
        a.table-actions {
            display: block;
            float: left;
            width: 15%;
        }
    </style>
</head>
<body class="skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?php echo $site_info['site_logo']['mini']?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?php echo $site_info['site_logo']['large']?></span>
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
                        <!-- Messages: style can be found in dropdown.less-->
                        <li id='remind-message' class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success"></span>
                            </a>

                        </li>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li id='sys-message' class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning"></span>
                            </a>
                        </li>

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo $site_info['static_resource_path'].'/'.$user_info['avatar']?>" class="user-image" alt="User Image" />
                                <span class="hidden-xs"> <?php echo $user_info['nickname']?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo $site_info['static_resource_path'].'/'.$user_info['avatar']?>" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo $user_info['nickname'].'-'.$user_info['job']?>
                                        <small>Member since <?php echo date('F, Y', $user_info['register_time'])?></small>
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
                                        <form action="/user/logout" method="post">
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
                        <img src="<?php echo $site_info['static_resource_path'].'/'.$user_info['avatar']?>" class="img-circle" alt="User Image" />
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
        <!-- Modal -->
        <div class="modal fade" id="dealModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">结算</h4>
                    </div>
                    <div class="modal-body">
                        <h3 class="text-center alert alert-danger">您真的要结算这笔交易么?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" data-action='deal' class="btn btn-danger">确认结算(Danger)</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userinfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">个人资料</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box">
                            <!-- User image -->
                            <div class="user-header">
                                <img src="<?php echo $site_info['static_resource_path'].'/'.$user_info['avatar']?>" class="center-block img-circle" alt="User Image" />
                                <div class="text-center text-blue">
                                    <?php echo $user_info['nickname'].'-'.$user_info['job']?>
                                    <div class="text-center text-black">Member since <?php echo date('F, Y', $user_info['register_time'])?></div>
                                </div>
                            </div>
                            <!-- Menu Body -->

                            <div class="user-body">
                                <div class="text-center alert alert-danger">想修改密码么？然而我并没有实现~啊哈哈</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn center-block btn-primary">确定</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">修改记录</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body table-responsive ">
                            <div class="box-body">
                                <div class="row form-horizontal">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">支付人</label><div class="col-sm-6">
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($user_lst as $uid => $user) {
                                                        echo "<option value='$uid'>".$user['nickname'].'</option>';
                                                    }
                                                    ?>
                                                </select></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">支付金额</label>
                                            <div class="col-sm-6">
                                                <input class="form-control" type="text" placeholder="金额">
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">支付时间</label>
                                            <div class="col-sm-6">
                                                <input class="form-control datepicker" type="text" placeholder="时间"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">消费人</label>
                                            <div class="col-sm-6">
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($type_map as $cost_type) {
                                                        echo '<option value='.$cost_type['id'].'>'.$cost_type['description'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">支付描述</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" id="modified" data-action='update' class="btn btn-primary">保存记录</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).on('click', 'a[data-toggle="modal"]', function(){
                var id = $(this).data('id');
                var action = $(this).data('action');
                var form = $("#updateModal").find('.form-group');
                var title_el =  $("#updateModal").find('h4.modal-title');
                if (action == 'update') {
                    var el = $('#row-'+id+'>td');
                    $("#modified").data('id',id);
                    $("#modified").data('action',action);
                    //修改标题
                    $(title_el).text('更新记录');
                    //修改选择人名字
                    $(form[0]).find('option[value="'+$(el[0]).text()+'"]').attr('selected','selected');
                    //金额
                    $(form[1]).find('input').val($(el[1]).text());
                    //支付时间
                    $(form[2]).find('input').val($(el[2]).text());
                    //消费人
                    $(form[3]).find('option').filter(function(index){
                        return $(this).text().trim() == $(el[3]).text().trim();
                    }).attr('selected', 'selected');
                    //支付描述
                    $(form[4]).find('textarea').val($(el[4]).text());
                } else if(action == 'add') {

                    $(title_el).text('新增记录');
                    $("#modified").data('action',action);
                    var default_id = "<?php echo $user_info['id'];?>";
                    //默认为当前用户
                    $(form[0]).find('option[value="'+default_id+'"]').attr('selected','selected');
                    //默认为当前时间
                    Date.prototype.Y_m_d = function() {
                        var yyyy = this.getFullYear().toString();
                        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
                        var dd  = this.getDate().toString();
                        return yyyy +'-'+ (mm[1]?mm:"0"+mm[0]) + '-'+ (dd[1]?dd:"0"+dd[0]); // padding
                    };
                    $(form[2]).find('input').attr('value',new Date().Y_m_d());
                    //支付描述，默认为买菜
                    $(form[4]).find('textarea').val("买菜");
                } else if (action == 'deal') {
                    $("#dealModal").find('.modal-body>h3').text('您真的要结算这笔交易么？');
                    $("#dealModal").find('button[data-action]').data('action','deal').data('id',id);

                } else if (action == 'dealBatch') {
                    $("#dealModal").find('.modal-body>h3').text('您真的要结算所有么？');
                    $("#dealModal").find('button[data-action]').data('action','dealBatch');
                }
            });
            $('button[data-action]').click(function(){
                var params = {id:$(this).data('id'),action:$(this).data('action')};
                var form = $("#updateModal").find('.form-group');
                // console.log(params);
                if ('deal' != params.action && 'dealBatch' != params.action) {
                    var form = $("#updateModal").find('.form-group');
                    params['paid_uid'] =  $(form[0]).find('option:selected').val();
                    params['cost'] =  $(form[1]).find('input').val();
                    params['paid_day'] =  $(form[2]).find('input').val();
                    params['type'] = $(form[3]).find('option:selected').val();
                    params['description'] = $(form[4]).find('textarea').val();
                    for(var el in params){
                        if (el!='id' && !params[el]){
                            alert('参数不能为空，请填写所有字段！');
                            console.log(el);
                            return false;
                        }
                    }
                    if(!params.id && params.action=='update') {
                        alert('不合法的操作!');
                        return false;
                    }
                }
                $.post('/index/command',params,function(data){
                    alert(data.msg);
                    if(!data.error){
                        window.location.reload();
                    }
                });
            });
        </script>