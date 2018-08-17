    <section id="calculate">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 mx-auto">
            <h2>Kalkulasi Biaya Kirim</h2>
            <p class="lead">
                Kalkulasi biaya pengiriman terbagi menjadi dua fungsi, 
                yaitu perhitungan untuk barang-barang yang telah terdaftar 
                di sistem maupun yang belum terdaftar
            </p>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#registered">Registered</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#unregistered">Unregistered</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active" id="registered">
                    <p>Anda dapat menghitung biaya pengiriman berdasarkan informasi dimensi pada data barang yang anda cari.</p>
                    <form id="frm_reg" method="POST" action="#">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <table id="grid_brg" class="table table-striped table-bordered" style="width:100%">
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
                                            $harga = format_IDR($row["brg_hrg"]);
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
                    </form>
                </div>
                <div class="tab-pane container fade" id="unregistered">Unregistered</div>
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
            <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
          </div>
        </div>
      </div>
    </section>