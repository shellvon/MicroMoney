
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            操作日志
            <small>所有历史</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> 主页</a></li>
            <li class="active">操作日志</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box col">

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">操作日志记录</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <table class="table table-bordered table-striped" id="operation-logs">
                            <thead>
                            <th width="5%">
                                ID
                            </th>
                            <th width="10%">
                                操作
                            </th>
                            <th width="15%">
                                创建时间
                            </th>
                            <th width="10%">
                                操作人
                            </th>
                            <th width="20%">
                                操作前数据
                            </th>
                            <th width="20%">
                                操作后数据
                            </th>
                            <th width="20%">
                                管理
                            </th>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($operation_logs as $log) {
                                    $create_time = date('Y-m-d H:i:s', $log['create_time']);
                                    $action = $action_map[$log['action']];
                                    echo "<tr>";
                                    // id
                                    echo "<td>{$log['id']}</td>";
                                    // action
                                    echo "<td>{$action}</td>";
                                    // time
                                    echo "<td>{$create_time}</td>";
                                    // 操作人
                                    echo "<td><a class='see-user-info' href='#'  data-uid='".$log['operator_id']."'>{$log['operator_name']}</a></td>";
                                    // old data
                                    echo "<td width='15%'><pre class='log-data'>{$log['old_data']}</pre></td>";
                                    // new_data
                                    echo "<td width='120px'><pre class='log-data'>{$log['new_data']}</pre></td>";
                                    echo  '<td class="actions">'.
                                                '<div class="action-buttons">'.
                                                '<a class="table-actions" href="#" title="对比差异" data-toggle="modal" data-target="#diff-modal"><i class="fa fa-exchange"></i><a class="table-actions" href="/logs/view?id='.$log['id'].'"><i class="fa fa-eye"></i></a><a class="table-actions" href=""><i class="fa fa-pencil"></i></a><a class="table-actions" href="#" data-toggle="modal" data-target="#delete-log-modal" id="delete-log" data-id="'.$log['id'].'"><i class="fa fa-trash-o"></i></a>'.
                                           '</div> </td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="diff-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">差异比较</h4>
                </div>
                <div class="modal-body">
                    <!--...-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary">确定</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="delete-log-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">删除日志</h4>
                </div>
                <div class="modal-body">
                    <h3 class="text-center alert alert-danger">您真的要删除这条记录么?</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="button" class="btn btn-primary" onclick="delLogs(this);" id="delete-log-id">确定</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // 删除日志.
    $('#delete-log').click(function(){
        var id = $(this).data('id');
        $('#delete-log-id').data('id', id);
    });
    function delLogs(el){
        console.log($(el).data('id'));
        $.post('/logs/delete', {'id': $(el).data('id')}, function (data) {
            alert(data.msg);
            if (!data.error) {
                window.location.href='/logs/view';
            }
        })
    }

</script>