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
                            foreach ($user_data as $uid => $row) {
                                $settlement = number_format($row['cost'] - $row['benefit'], 2, '.', '');
                                $row['benefit'] = number_format($row['benefit'], 2, '.', '');
                                echo <<<EOT
                    	<tr>
                      <td>{$row['nickname']}</td>
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
                      <td>{$row['description']}</td>
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
                                        <?php
                                            $header = array(/*'ID',*/ '支付人', '支付金额', '支付时间', '消费人', '支付描述', /* '操作人', '创建时间', '更新时间',*/ '是否结算', '操作');
                                            echo '<th>'.implode('</th><th>', $header).'</th>';
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($records as $row) {
                                        // 支付类型就是消费人.
                                        $who = $type_map[$row['type']]['description'];
                                        $paid_day = date('Y-m-d', strtotime($row['paid_day']));
                                        $nickname = $user_lst[$row['paid_uid']]['nickname'];
                                        $operator = $user_lst[$row['operator_uid']]['nickname'];
                                        $is_deal = (bool) $row['is_deal'];
                                        $span_css = $is_deal ? 'danger' : 'success';
                                        $span = '<span class="label label-'.$span_css.'">';
                                        $deal_str = $is_deal ? $span.'已经结算</span>' : $span.'暂未结算</span>';
                                        $operations = '';
                                        if (!$is_deal) {
                                            $operations .= '<a class="btn btn-warning" href="#" data-action="deal" data-id="'.$row['id'].'" data-toggle="modal" data-target="#dealModal">结帐</a>';
                                            $operations .= '<a class="btn btn-danger" data-action="update" data-id="'.$row['id'].'" data-toggle="modal" data-target="#updateModal"  href="#">修改</a>';
                                        } else {
                                            $operations = '<span class="label label-info">无权操作</span>';
                                        }
                                        $create_time = date('Y-m-d H:i:s', $row['create_time']);
                                        $update_time = date('Y-m-d H:i:s', $row['update_time']);
                                        // ugly output.
                                        echo "<tr id='row-{$row['id']}'>";
                                        //echo "<td>{$row['id']}</td>"; // ID
                                        echo "<td>{$nickname}</td>"; // 支付人
                                        echo "<td>{$row['cost']}</td>"; //支付金额
                                        echo "<td>{$paid_day}</td>"; // 支付时间
                                        echo "<td>{$who}</td>"; // 消费人
                                        echo "<td>{$row['description']}</td>"; // XSS
                                        //echo "<td>{$operator}</td>"; //操作人
                                        //echo "<td>{$create_time}</td>";//创建时间';
                                        //echo "<td>{$update_time}</td>";//更新时间',
                                        echo "<td>{$deal_str}</td>";// '是否结算'
                                        echo "<td>{$operations}</td>"; // 操作
                                        echo '</tr>';
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
