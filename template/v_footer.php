    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; <?php echo WEB_TITLE; ?> 2018</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- DataTables -->
    <script type="text/javascript" src="assets/vendor/DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/vendor/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="assets/vendor/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js"></script>

    <!-- Buttons examples -->
    <script src="assets/vendor/DataTables/dataTables.buttons.min.js"></script>
    <script src="assets/vendor/DataTables/buttons.bootstrap4.min.js"></script>
    <script src="assets/vendor/DataTables/jszip.min.js"></script>
    <script src="assets/vendor/DataTables/pdfmake.min.js"></script>
    <script src="assets/vendor/DataTables/vfs_fonts.js"></script>
    <script src="assets/vendor/DataTables/buttons.html5.min.js"></script>
    <script src="assets/vendor/DataTables/buttons.print.min.js"></script>
        
    <!-- Custom JavaScript for this theme -->
    <script src="assets/js/scrolling-nav.js"></script>
    <script type="text/javascript">
        function isEmpty(val){
            return (val === undefined || val == null || val.length <= 0) ? true : false;
        }
        $(document).ready(function() {
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                searching: true,
                paginate: true,
                autoWidth: false,
                columnDefs: [{ 
                    orderable: false,
                    targets: [ 0 ]
                }],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                }
            });
            $('#grid_brg').DataTable({
                processing: true,
                order: [[ 2, "asc" ]], 
                columnDefs: [{ 
                    orderable: false,
                    targets: [ 0 ]
                }],
            });
            $('#grid_tarif1').DataTable({
                dom: "<'row'<'col-sm-12'B>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-6'p><'col-sm-6'i>>",
                destroy: true,
                stateSave: false,
                deferRender: true,
                processing: true,
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i>',
                        titleAttr: 'Copy',
    //                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            }
                        },
                        footer:false
                    }, 
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',
    //                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            }
                        },
                        footer:false
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        titleAttr: 'PDF',
    //                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            }
                        },
                        footer:false
                    }, 
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel-o"></i> All Page',
                        titleAttr: 'Excel All Page',
    //                    exportOptions: { columns: ':visible:not(:last-child)' }, //last column has the action types detail/edit/delete
                        footer:false
                    }
                ],
                processing: true,
                order: [[ 0, "asc" ]],
            });
        } );
    </script>

  </body>

</html>