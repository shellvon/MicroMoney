
<div id="see-user-info">
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2015 <a href="#">内部记账使用</a>.</strong> All rights reserved.
</footer>



<textarea id="remind_tpl" style="display: none">
     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
         <i class="fa fa-envelope-o"></i>
         <span class="label label-success notify-cnt"><%this.reminds.count == 0 ? '' : this.reminds.count%></span>
     </a>
    <ul class="dropdown-menu">
        <li class="header">您有<%this.reminds.count%>条未读消息</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
                <%var notify_ids=[];%>
                <% for (var idx in this.reminds.list){
                    remind = this.reminds.list[idx];
                    notify_ids.push(remind.notify_id);
                %>
                <li>
                    <a href="#">
                        <div class="pull-left">
                            <img src="<?php echo $site_info['static_resource_path']?>/<%remind.user.avatar%>" class="img-circle" alt="User Image">
                        </div>
                        <h4>
                            <%remind.user.nickname%>
                            <small><i class="fa fa-clock-o"></i> <%remind.friendly_date%></small>
                        </h4>
                        <p><%remind.content%></p>
                    </a>
                </li>
                <%}%>
            </ul>
        </li>
        <li class="footer"><a href="#" class="clear-msg" data-target='#remind-message' data-notify-ids="<%notify_ids.join(',')%>">清空</a></li>
    </ul>
</textarea>

<textarea id="sys_msg_tpl" style="display: none">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning notify-cnt"><%this.notifications.count == 0 ?  '' :this.notifications.count%></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">您有<%this.notifications.count%>个系统通知</li>
        <li>
            <ul class="menu">
                <% var notify_ids  = [];%>
                <% for (var idx in this.notifications.list){
                    notification = this.notifications.list[idx];
                    notify_ids.push(notification.notify_id);
                    var css = '';
                    switch(notification.action) {
                        case 'register':
                            css = 'fa fa-users text-aqua';
                            break;
                        case 'update-profile':
                            css = 'fa fa-user text-red';
                            break;
                        case 'single_deal':
                        case 'batch_deal':
                        case 'update_deal':
                            css = 'fa fa-warnings text-yellow';

                    }%>
                <li>
                    <a href="#">
                        <i class="<%css%>"></i> <%notification.content%>
                    </a>
                </li>
                <%}%>
            </ul>
        </li>
        <li class="footer"><a href="#" class="clear-msg" data-target='#sys-message'data-notify-ids="<%notify_ids.join(',')%>">清空</a></li>
    </ul>
</textarea>

<textarea id="sys-message-empty-tpl" style="display: none">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning notify-cnt"></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">您有0个系统通知</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu"></ul>
        </li>
        <li class="footer"><a href="#">清空</a></li>
    </ul>
</textarea>

<textarea id="remind-message-empty-tpl" style="display: none">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success notify-cnt"></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">您有0条未读消息</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu"></ul>
        </li>
        <li class="footer"><a href="#">清空</a></li>
    </ul>
</textarea>

<textarea id="other-user-info-tpl" style="display: none;">
            <div class="modal fade" id="other-user-info-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <img src="<?php echo $site_info['static_resource_path']?>/<%this.user.avatar%>" class="center-block img-circle" alt="User Image" />
                                    <div class="text-center text-blue">
                                        <%this.user.nickname%>-<%this.user.job%>
                                        <div class="text-center text-black">Member since <%this.user.reg_time%></div>
                                    </div>
                                </div>
                                <!-- Menu Body -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn center-block btn-primary">确定</button>
                        </div>
                    </div>
                </div>
            </div>

        </textarea>

<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<script src="<?php echo $site_info['static_resource_path']?>/plugins/timeago/jquery.timeago.js" type="text/javascript"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo $site_info['static_resource_path']?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- DATA TABES SCRIPT -->
<script src="<?php echo $site_info['static_resource_path']?>/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo $site_info['static_resource_path']?>/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="<?php echo $site_info['static_resource_path']?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo $site_info['static_resource_path']?>/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo $site_info['static_resource_path']?>/dist/js/app.min.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $site_info['static_resource_path']?>/dist/js/demo.js" type="text/javascript"></script>
<script src="//cdn.bootcss.com/moment.js/2.10.6/moment.min.js" type="text/javascript"></script>
<script src="<?php echo $site_info['static_resource_path']?>/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="<?php echo $site_info['static_resource_path']?>/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?php echo $site_info['static_resource_path']?>/plugins/select2/select2.full.min.js" type="text/javascript"></script>


<script type="text/javascript">
    var global_user_info = {};
    $(function () {
        $("#cost-money").DataTable();
        $("#operation-logs").DataTable();
        $('#reservation').daterangepicker();
        $(".select2").select2();
        $('.datepicker').datepicker( {
            format:'yyyy-mm-dd',
            autoclose: true,
        });

        // 简单的JS模版引擎.
        // see => http://krasimirtsonev.com/blog/article/Javascript-template-engine-in-just-20-line
        var simpleTemplateEngine = function(html, options) {
            var re = /<%([^%>]+)?%>/g, reExp = /(^( )?(var|if|for|else|switch|case|break|{|}))(.*)?/g, code = 'var r=[];\n', cursor = 0, match;
            var add = function(line, js) {
                js? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') :
                    (code += line != '' ? 'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
                return add;
            }
            while(match = re.exec(html)) {
                add(html.slice(cursor, match.index))(match[1], true);
                cursor = match.index + match[0].length;
            }
            add(html.substr(cursor, html.length - cursor));
            code += 'return r.join("");';
            return new Function(code.replace(/[\r\t\n]/g, '')).apply(options);
        }


        // 获取消息.
        var getRemind = function(){
            $.get('/user/remind', function (data) {
                if (data.error) {
                    return;
                }
                reminds = data.data;
                var html = simpleTemplateEngine($('#remind_tpl').text(), {'reminds': reminds})
                $('#remind-message').html(html);
            });
        };

        // 获取系统通知.
        var getSysMessage = function(){
            $.get('/user/message', function (data) {
                if (data.error) {
                    return;
                }
                notifications = data.data;
                var html = simpleTemplateEngine($('#sys_msg_tpl').text(), {'notifications': notifications})
                $('#sys-message').html(html);
            });
        }

        getRemind();
        getSysMessage();

        // 定时.
        setInterval(getRemind, 10 * 60 * 1000);
        setInterval(getSysMessage, 10 * 60 * 1000);

        // http://stackoverflow.com/questions/1359018/in-jquery-how-to-attach-events-to-dynamic-html-elements
        // live 函数在1.9中被remove掉了.
        // $('.clear-msg') not work.
        $('body').on('click', 'a.clear-msg',function(){
            var ids = $(this).data('notify-ids');
            var target= $(this).data('target');
            if (ids.length == 0) {
                return;
            }
             $.post('/user/read', {'notify_ids' : ids}, function(data) {
                 if (data.error) {
                     alert('操作失败,稍后重试');
                     return;
                 }
                 $(target).html($(target+'-empty-tpl').text())
             });
        });


        // 查看他人资料信息.
        // TODO:和查看自己的资料结合.
        $('body').on('click', 'a.see-user-info', function(){
            var user_id = $(this).data('uid');
            var user = {
                'nickname': null,
                'reg_time': null,
                'job': null,
                'avatar': null,
            }
            if (global_user_info[user_id]) {
                var html = simpleTemplateEngine($('#other-user-info-tpl').text(), {'user':global_user_info[user_id]});
                $('#see-user-info').html(html);
                $('#other-user-info-modal').modal();
            } else {
                $.get('/user/profile', {'uid' : user_id}, function(data) {
                    if (data.error) {
                        alert(data.msg);
                        return;
                    }
                    global_user_info[user_id] = data.data;
                    var html = simpleTemplateEngine($('#other-user-info-tpl').text(), {'user':data.data});
                    $('#see-user-info').html(html);
                    $('#other-user-info-modal').modal();
                });
            }
        });

    });
</script>
</body>
</html>