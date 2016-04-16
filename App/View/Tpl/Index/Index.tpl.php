<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            消费记录
            <small>所有历史</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li class="active">消费记录</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">个人暂未结算金额<small>(您消费了<span class='text-red' style='font-size:1.5em;'><?php echo $user_info['cost'];?></span>元,需要结算<span class='text-red' style='font-size:1.5em;'><?php echo $user_info['settlement'];?></span>元)</small></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>姓名</th>
                                <th>支付金额</th>
                                <th>支付所占比</th>
                                <th>消费总金额</th>
                                <th>需要结算金额</th>
                            </tr>
                            <?php
                            $cnt = count($user_lst);
                            foreach ($user_data as $name => $row) {
                                $settlement = number_format($row['cost'] - $row['benefit'], 2, '.', '');
                                $row['benefit'] = number_format($row['benefit'], 2, '.', '');
                                echo <<<EOT
                    	<tr>
                      <td>{$name}</td>
                      <td>{$row['cost']}</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: {$row['percent']}%"></div>
                        </div><span>{$row['percent']}%</span>
                      </td>

                      <td>{$row['benefit']}</td>
                      <td>{$settlement}</td>
                    </tr>
EOT;
                            }
                            ?>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-md-6"><div class="box">
                    <div class="box-header">
                        <h3 class="box-title">各类型消费统计</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>消费人</th>
                                <th>消费金额</th>
                                <th>消费所占比</th>
                                <th>人均消费</th>
                            </tr>
                            <?php

                            foreach ($each_type_cost as $row) {
                                $settlement = 100;//$row['cost']/count($type_map[$row['type']]);
                                $settlement = number_format($settlement, 2, '.', '');
                                echo <<<EOT
                    	<tr>
                      <td>{$row['who']}</td>
                      <td>{$row['cost']}</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: {$row['percent']}%"></div>
                        </div><span>{$row['percent']}%</span>
                      </td>
                      <td>{$settlement}</td>
                    </tr>
EOT;
                            }
                            ?>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-xs-12">
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
                                            <div class="text-center text-black">Member since Nov. 2012</div>
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
                                                                echo '<option value='.$cost_type['id'].'>'.$cost_type['who'].'</option>';
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
                <div class="box col">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">这尼玛就是你们花的钱</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">

                            <form class="form-group" method="get" action="index.php">

                                <div class="input-group input-prepend input-append">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <div class="col-xs-4" style="padding-left:0px">
                                        <input type="text" name="daterange" class="form-control pull-right" id="reservation" value="<?php
                                        echo isset($date_range) ? $date_range : '';
                                        ?>"/>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-search"></i>查询
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <?php if (empty($records)): ?>
                                <div class="info alert-info text-center">暂无数据</div>
                            <?php else: ?>
                                <table id="cost-money" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <?php echo '<th>'.implode('</th><th>', $header).'</th>'?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($records as $row) {
                                        $row['type'] = implode(',', $type_map[$row['type']]);
                                        $row['when'] = date('Y-m-d', strtotime($row['when']));
                                        $is_deal = (bool) $row['is_deal'];
                                        $span_css = $is_deal ? 'danger' : 'success';
                                        $span = '<span class="label label-'.$span_css.'">';
                                        $row['is_deal'] = $is_deal ? $span.'已经结算</span>' : $span.'暂未结算</span>';
                                        $opreations = '';
                                        if (!$is_deal) {
                                            $opreations .= '<a class="btn btn-warning" href="#" data-action="deal" data-id="'.$row['id'].'" data-toggle="modal" data-target="#dealModal">结帐</a>';
                                            $opreations .= '<a class="btn btn-danger" data-action="update" data-id="'.$row['id'].'" data-toggle="modal" data-target="#updateModal"  href="#">修改</a>';
                                        } else {
                                            $opreations = '<span class="label label-info">无权操作</span>';
                                        }
                                        $row_str = implode('</td><td>', $row);
                                        echo "<tr id='row-".$row['id']."'><td>".$row_str.'</td><td>'.$opreations.'</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            <?php endif;?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $('a[data-toggle="modal"]').click(function(){
        var id = $(this).data('id');
        var action = $(this).data('action');
        var form = $("#updateModal").find('.form-group');
        var title_el =  $("#updateModal").find('h4.modal-title');
        console.log('click me:'+id);
        if (action == 'update') {
            var el = $('#row-'+id+'>td:not(:first)');
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
            var default_name = "<?php echo $user_info['nickname'];?>";
            //默认为当前用户
            $(form[0]).find('option[value="'+default_name+'"]').attr('selected','selected');
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
        console.log(params);
        if ('deal' != params.action && 'dealBatch' != params.action) {
            var form = $("#updateModal").find('.form-group');
            params['paid_username'] =  $(form[0]).find('option:selected').val();
            params['cost'] =  $(form[1]).find('input').val();
            params['when'] =  $(form[2]).find('input').val();
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