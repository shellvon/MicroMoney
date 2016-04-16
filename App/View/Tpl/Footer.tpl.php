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
    $(function () {
        $("#cost-money").DataTable();
        $('#reservation').daterangepicker();
        $(".select2").select2();
        $('.datepicker').datepicker( {
            format:'yyyy-mm-dd',
            autoclose: true,
        });
    });
</script>
</body>
</html>