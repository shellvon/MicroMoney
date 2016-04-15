<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2015 <a href="#">内部记账使用</a>.</strong> All rights reserved.
</footer>


<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo $site_info['static_resource_path']?>/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
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

<style type="text/css">
    .datepicker{
        z-index:1151 !important;
    }
</style>
<script type="text/javascript">
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
    $(function () {
        $("#cost-money").DataTable();
        $('#reservation').daterangepicker();
        $(".select2").select2();
        $('.datepicker').datepicker( {
            format:'yyyy-mm-dd',
            autoclose: true,
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
    });
</script>
</body>
</html>