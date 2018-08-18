    <section id="calculate">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 mx-auto">
            <h2>Tarif Pengiriman</h2>
            <p class="lead">
                Tarif Pengiriman terbagi menjadi dua fungsi, 
                yaitu perhitungan untuk barang-barang yang telah terdaftar 
                di sistem maupun yang belum terdaftar
            </p>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#registered">Registered Goods</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#unregistered">Unregistered Goods</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active" id="registered">
                    <div class="row">
                        <div class="col-lg-12">
                            <p>Anda dapat menghitung biaya pengiriman berdasarkan informasi dimensi pada data barang yang anda cari.</p>
                            <table id="grid_brg" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Principal</th>
                                        <th>Kode Brg</th>
                                        <th>Nama Brg</th>
                                        <th>Harga (Rp)</th>
                                        <th>Berat (Kg)</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>T</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $query = "SELECT p.principal_nama, b.brg_kode, b.brg_nama, b.brg_hrg, b.brg_berat, "
                                            . "b.brg_dim_p, b.brg_dim_l, b.brg_dim_t FROM barang AS b "
                                            . "INNER JOIN principal AS p ON b.principal_id = p.principal_id";
                                    $results = $database->get_results( $query );
                                    foreach( $results as $row )
                                    {
                                        $principal = nohtml($row["principal_nama"]);
                                        $kode = nohtml($row["brg_kode"]);
                                        $nama = nohtml($row["brg_nama"]);
//                                        $harga = format_IDR((int)$row["brg_hrg"]);
                                        $harga = (int)$row["brg_hrg"];
                                        $berat = $row["brg_berat"];
                                        $dim_p = (int)nohtml($row["brg_dim_p"]);                                            
                                        $dim_l = (int)nohtml($row["brg_dim_l"]);                                            
                                        $dim_t = (int)nohtml($row["brg_dim_t"]);

                                        echo '<tr>';
                                            echo '<td><button class="btn btn-primary">Pilih</button></td>';
                                            echo '<td>'.$principal.'</td>';
                                            echo '<td>'.$kode.'</td>';
                                            echo '<td>'.$nama.'</td>';
                                            echo '<td>'.$harga.'</td>';
                                            echo '<td>'.$berat.'</td>';
                                            echo '<td>'.$dim_p.'</td>';
                                            echo '<td>'.$dim_l.'</td>';
                                            echo '<td>'.$dim_t.'</td>';
                                        echo '</tr>';
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <span class="text-white">Hitung Tarif Pengiriman</span>
                                </div>
                                <div class="card-body">
                                    <form id="frm_calc_reg" class="form-horizontal" method="POST" action="#">
                                        <div class="form-row">
                                            <label class="col-md-2">Harga (Rp):</label>
                                            <div class="form-group col-md-3">
                                                <input type="number" name="fharga" id="fharga" class="form-control" min="0" placeholder="Harga">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Berat (Kg):</label>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fberat" id="fberat" class="form-control" min="0" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" placeholder="Berat">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Dimensi:</label>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fdim_p" id="fdim_p" class="form-control" min="0" placeholder="Panjang">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fdim_l" id="fdim_l" class="form-control" min="0" placeholder="Lebar">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fdim_t" id="fdim_t" class="form-control" min="0" placeholder="Tinggi">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Ekspedisi:</label>
                                            <div class="form-group col-md-3">
                                                <select name="fekspedisi" id="fekspedisi" class="form-control">
                                                    <option value="">Pilih Ekspedisi</option>
                                                    <?php
                                                        $query = "SELECT * FROM ekspedisi ORDER BY ekspedisi_nama";
                                                        $results = $database->get_results( $query );
                                                        foreach( $results as $row )
                                                        {
                                                            $id = (int) nohtml($row["ekspedisi_id"]);
                                                            $nama = nohtml($row["ekspedisi_nama"]);
                                                            
                                                            echo '<option value="'.$id.'">'.$nama.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Tujuan:</label>
                                            <div class="form-group col-md-6">
                                                <select name="ftujuan" id="ftujuan" class="form-control">
                                                    <option value="">Pilih Tujuan</option>
                                                    <?php
                                                        $query = "SELECT * FROM kabupaten ORDER BY nama_kabupaten";
                                                        $results = $database->get_results( $query );
                                                        foreach( $results as $row )
                                                        {
                                                            $id = (int) nohtml($row["id_kabupaten"]);
                                                            $nama = nohtml($row["nama_kabupaten"]);
                                                            
                                                            echo '<option value="'.$id.'">'.$nama.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-2 mx-auto">
                                                <button id="btn_tarif1" class="btn btn-success">Tampilkan Tarif</button>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <table id="grid_tarif1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Ekspedisi</th>
                                                            <th>Tujuan</th>
                                                            <th>Biaya Asuransi</th>
                                                            <th>Biaya Via Udara (Rp)</th>
                                                            <th>Biaya Via Darat (Rp)</th>
                                                            <th>Biaya Via Laut (Rp)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                                <div class="card-footer">
                                    <small>**)Tarif belum termasuk PPN</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane container fade" id="unregistered">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <span class="text-white">Hitung Tarif Pengiriman</span>
                                </div>
                                <div class="card-body">
                                    <form id="frm_calc_reg2" class="form-horizontal" method="POST" action="#">
                                        <div class="form-row">
                                            <label class="col-md-2">Harga (Rp):</label>
                                            <div class="form-group col-md-3">
                                                <input type="number" name="fharga2" id="fharga2" class="form-control" min="0" placeholder="Harga">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Berat (Kg):</label>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fberat2" id="fberat2" class="form-control" min="0" pattern="[0-9]+([\,|\.][0-9]+)?" step="0.01" placeholder="Berat">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Dimensi:</label>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fdim_p2" id="fdim_p2" class="form-control" min="0" placeholder="Panjang">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fdim_l2" id="fdim_l2" class="form-control" min="0" placeholder="Lebar">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number" name="fdim_t2" id="fdim_t2" class="form-control" min="0" placeholder="Tinggi">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Ekspedisi:</label>
                                            <div class="form-group col-md-3">
                                                <select name="fekspedisi2" id="fekspedisi2" class="form-control">
                                                    <option value="">Pilih Ekspedisi</option>
                                                    <?php
                                                        $query = "SELECT * FROM ekspedisi ORDER BY ekspedisi_nama";
                                                        $results = $database->get_results( $query );
                                                        foreach( $results as $row )
                                                        {
                                                            $id = (int) nohtml($row["ekspedisi_id"]);
                                                            $nama = nohtml($row["ekspedisi_nama"]);
                                                            
                                                            echo '<option value="'.$id.'">'.$nama.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <label class="col-md-2">Tujuan:</label>
                                            <div class="form-group col-md-6">
                                                <select name="ftujuan2" id="ftujuan2" class="form-control">
                                                    <option value="">Pilih Tujuan</option>
                                                    <?php
                                                        $query = "SELECT * FROM kabupaten ORDER BY nama_kabupaten";
                                                        $results = $database->get_results( $query );
                                                        foreach( $results as $row )
                                                        {
                                                            $id = (int) nohtml($row["id_kabupaten"]);
                                                            $nama = nohtml($row["nama_kabupaten"]);
                                                            
                                                            echo '<option value="'.$id.'">'.$nama.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-2 mx-auto">
                                                <button id="btn_tarif2" class="btn btn-success">Tampilkan Tarif</button>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <table id="grid_tarif2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Ekspedisi</th>
                                                            <th>Tujuan</th>
                                                            <th>Biaya Asuransi</th>
                                                            <th>Biaya Via Udara (Rp)</th>
                                                            <th>Biaya Via Darat (Rp)</th>
                                                            <th>Biaya Via Laut (Rp)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </form>
                                </div> 
                                <div class="card-footer">
                                    <small>**)Tarif belum termasuk PPN</small>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 mx-auto">
            <h2>Kontak Kami</h2>
            <p class="lead">Hubungi Kami di 021-999 9999</p>
          </div>
        </div>
      </div>
    </section>

<script type="text/javascript">
    function logic_tarif1(){
        var table = $('#grid_brg').DataTable({
            processing: true,
            order: [[ 2, "asc" ]], 
            columnDefs: [{ 
                orderable: false,
                targets: [ 0 ]
            }],
        });
        
        //function for datatables button
        $('#grid_brg tbody').on( 'click', 'button', function (e) {        
            var data = table.row( $(this).parents('tr') ).data();
            fharga = data[4];
            fberat = data[5];
            fp = data[6];
            fl = data[7];
            ft = data[8];
            $('#fharga').val(fharga);
            $('#fberat').val(fberat);
            $('#fdim_p').val(fp);
            $('#fdim_l').val(fl);
            $('#fdim_t').val(ft);
        });

        var table_tarif1 = $('#grid_tarif1').DataTable({
            dom: "<'row'<'col-md-12'B>>" + "<'row'<'col-md-12'tr>>" + "<'row'<'col-md-12'p>>",
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
            ajax: {                
                url: 'json_rsc/ajaxData.php?act=get_tarif',
                type: 'GET',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.fharga = $('#fharga').val();
                    d.fberat = $('#fberat').val();
                    d.fdim_p = $('#fdim_p').val();
                    d.fdim_l = $('#fdim_l').val();
                    d.fdim_t = $('#fdim_t').val();
                    d.fekspedisi = $('#fekspedisi').val();
                    d.ftujuan = $('#ftujuan').val();
                }
            },
            columns: [
                { "data": 'ekspedisi' },
                { "data": 'tujuan' },
                { "data": 'tarif_asuransi' },
                { "data": 'via_udara' },
                { "data": 'via_darat' },
                { "data": 'via_laut' },
            ],
            order: [[ 0, "asc" ]],
        });
        
        $("#btn_tarif1").on("click", function(e){
            e.preventDefault();
            table_tarif1.ajax.reload();
        });
    }
    
    function logic_tarif2(){
        var table_tarif2 = $('#grid_tarif2').DataTable({
            dom: "<'row'<'col-md-12'B>>" + "<'row'<'col-md-12'tr>>" + "<'row'<'col-md-12'p>>",
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
            ajax: {                
                url: 'json_rsc/ajaxData.php?act=get_tarif',
                type: 'GET',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                dataType: 'JSON',
                contentType:"application/json",
                data: function(d){
                    d.fharga = $('#fharga2').val();
                    d.fberat = $('#fberat2').val();
                    d.fdim_p = $('#fdim_p2').val();
                    d.fdim_l = $('#fdim_l2').val();
                    d.fdim_t = $('#fdim_t2').val();
                    d.fekspedisi = $('#fekspedisi2').val();
                    d.ftujuan = $('#ftujuan2').val();
                }
            },
            columns: [
                { "data": 'ekspedisi' },
                { "data": 'tujuan' },
                { "data": 'tarif_asuransi' },
                { "data": 'via_udara' },
                { "data": 'via_darat' },
                { "data": 'via_laut' },
            ],
            order: [[ 0, "asc" ]],
        });
        
        $("#btn_tarif2").on("click", function(e){
            e.preventDefault();
            table_tarif2.ajax.reload();
        });        
    }
</script>