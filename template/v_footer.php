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
    <script src="assets/vendor/DataTables/Buttons-1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="assets/vendor/DataTables/Buttons-1.5.2/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/vendor/DataTables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="assets/vendor/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="assets/vendor/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="assets/vendor/DataTables/Buttons-1.5.2/js/buttons.html5.min.js"></script>
    <script src="assets/vendor/DataTables/Buttons-1.5.2/js/buttons.print.min.js"></script>
        
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
            
            logic_tarif1();
            logic_tarif2();
        } );
    </script>

  </body>

</html>