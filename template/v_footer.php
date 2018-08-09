	<script src="assets/js/jquery-1.11.1.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<!--
	<script src="assets/js/chart.min.js"></script>
	<script src="assets/js/chart-data.js"></script>
	<script src="assets/js/easypiechart.js"></script>
	<script src="assets/js/easypiechart-data.js"></script>
	-->
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<!-- DataTables -->
        <script type="text/javascript" src="assets/plugins/DataTables/datatables.min.js"></script>
        <script type="text/javascript" src="assets/plugins/DataTables/DataTables-1.10.16/js/dataTables.bootstrap.min.js"></script>
	<!--<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/datatables.min.js"></script>-->
	
        <!-- Summernote -->
        <script src="assets/plugins/summernote/summernote.js"></script>
        
	<script src="assets/js/custom.js"></script>

	<script>
	$(document).ready(function(){
            $('#example').DataTable({
                "columnDefs": [
                  { "orderable": false, "targets": 0 }
                ]
            });
            $('.summernote').summernote({
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']]
                  ]
            });
	});
	</script>

</body>
</html>