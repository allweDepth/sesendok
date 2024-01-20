<?php
class post_data
{
    public function post_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];

        $id_user = $_SESSION["user"]["id"];
        $tahun_anggaran = $_SESSION["user"]["tahun"];
        $keyEncrypt = $_SESSION["user"]["key_encrypt"];
        $btn_edit = '';

        $DB = DB::getInstance();
        $Fungsi = new MasterFungsi();

        $type_user = $_SESSION["user"]["type_user"];
        $username = $_SESSION["user"]["username"];
        $status = 'user';
        if ($type_user === 'admin') {
            $status = 'admin';
        }
        $sukses = false;
        $code = 40;
        $pesan = 'posting kosong';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        if (!empty($_POST) && $id_user > 0) {
            $code = 11;
            if (isset($_POST['jenis'])) {
                $code = 12;
                $validate = new Validate($_POST);
                //var_dump($_POST);
                //var_dump($validate );
                $jenis = $validate->setRules('jenis', 'jenis', [
                    'sanitize' => 'string',
                    'required' => true,
                    'min_char' => 1,
                    'max_char' => 100
                ]);
                $tbl = $validate->setRules('tbl', 'tbl', [
                    'sanitize' => 'string',
                    'required' => true,
                    'min_char' => 1,
                    'max_char' => 100
                ]);
                //var_dump($tbl);
                //================
                //PROSES VALIDASI
                //================
                switch ($tbl) {
                    case 'outbox':
                        switch ($jenis) {
                            case 'add_coment':
                                //id reply jika tbl edit id_row
                                $id_tujuan = $validate->setRules('users', 'user penerima', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $uraian = $validate->setRules('uraian', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 400
                                ]);
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'wall':
                        switch ($jenis) {
                            case 'edit':
                            case 'reply':
                                //id reply jika tbl edit id_row
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add_coment':
                                $uraian = $validate->setRules('text', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 400
                                ]);
                                break;
                            case 'like':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $like = $validate->setRules('like', 'like', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['like', 'unlike', 'non']
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'peraturan':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $type_dok = $validate->setRules('type', 'type', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['peraturan_undang_undang_pusat','peraturan_menteri_lembaga','peraturan_daerah', 'pengumuman', 'artikel', 'lain']
                                ]);
                                $judul = $validate->setRules('judul', 'judul', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $nomor = $validate->setRules('nomor', 'nomor', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $bentuk = $validate->setRules('judul', 'judul', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $bentuk_singkat = $validate->setRules('bentuk_singkat', 'bentuk_singkat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $t4_penetapan = $validate->setRules('tempat', 'tempat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $tgl_penetapan = $validate->setRules('tgl_penetapan', 'tanggal penetapan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $tgl_pengundangan = $validate->setRules('tgl_pengundangan', 'tanggal', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $status = $validate->setRules('status', 'status', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['rahasia', 'umum', 'proyek']
                                ]);

                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'rekanan':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $nama_perusahaan = $validate->setRules('nama_perusahaan', 'nama_perusahaan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //var_dump($_POST['alamat']);

                                $alamat = $validate->setRules('alamat', 'alamat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);

                                $npwp = $validate->setRules('npwp', 'npwp', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 16
                                ]);
                                $direktur = $validate->setRules('direktur', 'direktur', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $no_ktp = $validate->setRules('no_ktp', 'Nomor KTP Direktur', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $alamat_dir = $validate->setRules('alamat_dir', 'Alamat Direktur', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $no_akta_pendirian = $validate->setRules('no_akta_pendirian', 'alamat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $tgl_akta_pendirian = $validate->setRules('tgl_akta_pendirian', 'Tanggal Akta Pendirian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $lokasi_notaris_pendirian = $validate->setRules('lokasi_notaris_pendirian', 'lokasi_notaris_pendirian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $nama_notaris_pendirian = $validate->setRules('nama_notaris_pendirian', 'nama_notaris_pendirian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                //perlu cara khusus JSON
                                $notaris_perubahan = $validate->setRules('notaris_perubahan', 'Akta Perubahan', [
                                    'json' => true,
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $data_lain = $validate->setRules('data_lain', 'Data lainnya', [
                                    'json' => true,
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //$dataArray = json_decode($dataArray);
                                // $notaris_perubahan = '{}';
                                // $data_lain = '{}';
                                // $file = '';
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'informasi_umum':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $kode = $validate->setRules('kode', 'kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                $nomor_uraian = $validate->setRules('nomor_uraian', 'Nomor', [
                                    'sanitize' => 'string'
                                ]);
                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $satuan = $validate->setRules('satuan', 'satuan', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $nilai = $validate->setRules('nilai', 'nilai', [
                                    'numeric' => true
                                ]);
                                $type = 'custom';
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'profil':
                        switch ($tbl) {
                            case 'edit':
                                $id_user = $validate->setRules('id_row', 'ID', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $type_user = $validate->setRules('type_user', 'type user', [
                                    'sanitize' => 'string',
                                    'min_char' => 2, //on-off
                                    'in_array' => ['user', 'admin', 'super']
                                ]);
                                //break;
                            case 'profil':
                                $nama = $validate->setRules('nama', 'nama', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6
                                ]);
                                $username = $validate->setRules('username', 'username', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6,
                                    'max_char' => 100,
                                    'regexp' => "/^[A-Za-z_]{6,}$/",
                                    'uniqueArray' => ['user_ahsp', 'username', [['id', '!=', $id_user]]]
                                ]);
                                $email = $validate->setRules('email', 'email', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6,
                                    'max_char' => 255,
                                    'email' => true,
                                    'uniqueArray' => ['user_ahsp', 'email', [['id', '!=', $id_user]]]
                                ]);
                                $kontak_person = $validate->setRules('kontak_person', 'kontak person', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6
                                ]);
                                $nama_org = $validate->setRules('nama_org', 'Nama Organisasi', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6
                                ]);

                                $ket = $validate->setRules('ket', 'Keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                if ($tbl == 'profil') {
                                    $font = $validate->setRules('font', 'uraian', [
                                        'required' => true,
                                        'numeric' => true
                                    ]);
                                    $warna_tbl = $validate->setRules('warna_tbl', 'warna Tabel', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 3
                                    ]);
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'copy':
                        switch ($tbl) {
                            case 'analisa_quarry':
                            case 'analisa_sda':
                            case 'analisa_ck':
                            case 'analisa_bm':
                            case 'analisa_alat':
                            case 'analisa_alat_custom':
                                $kolomCekUnique = 'kd_analisa';
                                switch ($tbl) {
                                    case 'analisa_alat':
                                        $tabel_pakai =  'analisa_alat';
                                        $kolomCekUnique = 'kode';
                                        break;
                                    case 'analisa_alat_custom':
                                        $tabel_pakai =  'analisa_alat_custom';
                                        break;
                                    case 'analisa_quarry':
                                        $tabel_pakai =  'analisa_quarry';
                                        $lokasiQuarry = $validate->setRules('lokasi', 'Lokasi Quarry', [
                                            'sanitize' => 'string',
                                            'required' => true,
                                            'min_char' => 1,
                                            'max_char' => 200
                                        ]);
                                        //var_dump($lokasi);
                                        $tujuanQuarry = $validate->setRules('tujuan', 'Tujuan Quarry', [
                                            'sanitize' => 'string',
                                            'required' => true,
                                            'min_char' => 1,
                                            'max_char' => 200
                                        ]);
                                        break;
                                    case 'analisa_bm':
                                        $tabel_pakai =  'analisa_pekerjaan_bm';
                                        break;
                                    case 'analisa_ck':
                                        $tabel_pakai =  'analisa_pekerjaan_ck';
                                        break;
                                    case 'analisa_sda':
                                        $tabel_pakai =  'analisa_pekerjaan_sda';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                $kd_analisa_copy = $validate->setRules('kode_copy', 'kode analisa yang ingin di copy', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                $kode = $validate->setRules('kode', 'kode', [ //kode baru
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100,
                                    'uniqueArray' => [$tabel_pakai, $kolomCekUnique, [['kd_proyek', '=', $kd_proyek_aktif]]]
                                ]);
                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            case 'copy_lap_harian':
                                $tanggal_copy = $validate->setRules('tanggal_copy', 'Tanggal copy', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', //'/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/',
                                    'min_char' => 8
                                ]);
                                $tanggal = $validate->setRules('tanggal', 'Tanggal paste', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', //'/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/',
                                    'min_char' => 8
                                ]);
                                break;
                            case 'proyek':
                                $kd_proyek_copy = $validate->setRules('kd_proyek_copy', 'kode dokumen yang ingin dicopy', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                //kode proyek baru yang di insert
                                $kode = $validate->setRules('kode', 'kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100,
                                    'unique' => ['nama_pkt_proyek', 'kd_proyek']
                                ]);
                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $tahun_proyek = $validate->setRules('tahun', 'tahun', [
                                    'required' => true,
                                    'numeric' => true
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $copy_realisasi_proyek = $validate->setRules('copy_realisasi_proyek', 'Salin Realisasi', [
                                    'sanitize' => 'string',
                                    'in_array' => ['on', 'off']
                                ]);
                                $aktifkan_proyek = $validate->setRules('aktifkan_proyek', 'aktifkan_proyek', [
                                    'sanitize' => 'string',
                                    'in_array' => ['on', 'off']
                                ]);
                                break;
                        }
                        break;
                    case 'lokasi':
                        switch ($tbl) {
                            case 'add_row':
                            case 'update':
                                $dataArray = $validate->setRules('data', 'dataArray', [
                                    'json' => true,
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //var_dump($dataArray);
                                $dataArray_awal = $dataArray;
                                $dataArray = json_decode($dataArray);

                                break;
                            case 'edit':
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'satuan':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $valueSatuan = $validate->setRules('value', 'value', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $item = $validate->setRules('item', 'item', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $sebutan_lain = $validate->setRules('sebutan_lain', 'sebutan_lain', [
                                    'sanitize' => 'string'
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'harga_satuan':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $kode = $validate->setRules('kode', 'kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $jenis_basic_price = $validate->setRules('jenis_basic_price', 'jenis basic price', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100,
                                    'in_array' => ['upah', 'royalty', 'bahan', 'peralatan']
                                ]);
                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $satuan = $validate->setRules('satuan', 'satuan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $harga_sat = $validate->setRules('harga_satuan', 'harga satuan', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $sumber_data = $validate->setRules('sumber_data', 'sumber data', [
                                    'sanitize' => 'string'
                                ]);
                                $spesifikasi = $validate->setRules('spesifikasi', 'spesifikasi', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                break;
                            case 'y':
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'analisa_alat':
                        switch ($tbl) {
                            case 'edit':
                                $id = $validate->setRules('id', 'id row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $dataArray = $validate->setRules('dataArray', 'dataArray', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $dataArray = json_decode($dataArray);
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'analisa_quarry':
                    case 'analisa_sda':
                    case 'analisa_ck':
                    case 'analisa_bm':
                    case 'analisa_alat_custom':
                        switch ($tbl) {
                            case 'edit':
                                $kd_analisa = $validate->setRules('kd_analisa', 'Kode Analisa Alat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //var_dump($dataArray);
                                $jenis_pek = $validate->setRules('jenis_pek', 'jenis_pek', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                if ($jenis == 'analisa_alat_custom') {
                                    $kapasitas = $validate->setRules('kapasitas', 'kapasitas alat', [
                                        'required' => true,
                                        'numeric' => true,
                                        'min_char' => 1
                                    ]);
                                    $sat_kapasitas = $validate->setRules('sat_kapasitas', 'Satuan Kapasitas', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 1,
                                        'max_char' => 200
                                    ]);
                                }

                                $id = $validate->setRules('id_row', 'id row', [ //ini juga bersisi kode analisa
                                    'required' => true,
                                    'sanitize' => 'string',
                                    'min_char' => 1
                                ]);
                                $dataArray = $validate->setRules('dataArray', 'dataArray', [
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $dataArray = json_decode($dataArray);
                                if ($jenis == 'analisa_quarry') {
                                    $lokasiQuarry = $validate->setRules('lokasi', 'Lokasi Quarry', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 1,
                                        'max_char' => 200
                                    ]);
                                    //var_dump($lokasi);
                                    $tujuanQuarry = $validate->setRules('tujuan', 'Tujuan Quarry', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 1,
                                        'max_char' => 200
                                    ]);
                                }
                                //var_dump($dataArray);
                                break;
                            case 'input':
                                $jenis_pek = $validate->setRules('jenis_pek', 'jenis_pek', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $kd_analisa = $validate->setRules('kd_analisa', 'Kode Analisa Alat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                if ($jenis == 'analisa_alat_custom') {
                                    $kapasitas = $validate->setRules('kapasitas', 'kapasitas alat', [
                                        'required' => true,
                                        'numeric' => true,
                                        'min_char' => 1
                                    ]);
                                    $sat_kapasitas = $validate->setRules('sat_kapasitas', 'Satuan Kapasitas', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 1,
                                        'max_char' => 200
                                    ]);
                                }
                                $dataArray = $validate->setRules('dataArray', 'dataArray', [
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $dataArray = json_decode($dataArray);
                                if ($jenis == 'analisa_quarry') {
                                    $lokasiQuarry = $validate->setRules('lokasi', 'Lokasi Quarry', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 1,
                                        'max_char' => 200
                                    ]);
                                    //var_dump($lokasi);
                                    $tujuanQuarry = $validate->setRules('tujuan', 'Tujuan Quarry', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 1,
                                        'max_char' => 200
                                    ]);
                                }
                                //var_dump($dataArray);
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'divisi':
                    case 'divisiBM':
                    case 'divisiCK':
                    case 'divisiSDA':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $tahun = $validate->setRules('tahun', 'tahun', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $bidang = $validate->setRules('bidang', 'Bidang', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 3,
                                    'in_array' => ['bm', 'ck', 'sda']
                                ]);
                                $kode = $validate->setRules('kode', 'Kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $uraian = $validate->setRules('uraian', 'Uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'rab':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'ID', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add_row':
                                $kd_analisa = $validate->setRules('kode', 'Kode Analisa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $uraian = $validate->setRules('uraian', 'Uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $volume = $validate->setRules('volume', 'Volume', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $satuan = $validate->setRules('satuan', 'Satuan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'Keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'schedule':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'ID', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $kd_analisa = $validate->setRules('kd_analisa', 'Kode Analisa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $durasi = $validate->setRules('durasi', 'durasi', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 0
                                ]);
                                $mulai = $validate->setRules('mulai', 'mulai', [
                                    'numeric' => true
                                ]);
                                $dataArray = $validate->setRules('dataArray', 'dataArray', [
                                    'json' => true,
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //$dataArray = json_decode($dataArray);
                                $dependent = $validate->setRules('dependent', 'dependent', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'Keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'x':
                        switch ($tbl) {
                            case 'y':
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    default:
                        $err = 6;
                }
                switch ($tbl) {
                    case 'sortir':
                        $id_row = $validate->setRules('id_row', 'id_row', [
                            'required' => true,
                            'numeric' => true,
                            'min_char' => 1
                        ]);
                        $mode_sortir = $validate->setRules('srt', 'Mode Sortir', [
                            'sanitize' => 'string',
                            'required' => true,
                            'min_char' => 6,
                            'max_char' => 8,
                            'in_array' => ['up_row', 'down_row']
                        ]);
                        break;
                    default:
                        # code...
                        break;
                }
                //FINISH PROSES VALIDASI
                $kodePosting = '';
                if ($validate->passed()) {
                    //tabel pakai
                    switch ($jenis) {
                        case 'rekanan':
                            $tabel_pakai = 'rekanan';
                            break;
                        case "proyek":
                            $tabel_pakai = 'nama_pkt_proyek';
                            break;
                        case 'tabel_satuan':
                        case 'satuan':
                            $tabel_pakai =  'daftar_satuan';
                            break;
                        case 'sbu':
                        case 'harga_satuan':
                            $tabel_pakai = 'harga_sat_upah_bahan';
                            break;
                        case 'divisi':
                        case 'divisiBM':
                        case 'divisiCK':
                        case 'divisiSDA':
                            $tabel_pakai = 'divisi';
                            break;
                        case 'informasi_umum':
                        case 'tabel_informasi':
                            $tabel_pakai =  'informasi_umum';
                            break;
                        case 'analisa_alat':
                            $tabel_pakai =  'analisa_alat';
                            break;
                        case 'analisa_alat_custom':
                            $tabel_pakai =  'analisa_alat_custom';
                            break;
                        case 'analisa_quarry':
                            $tabel_pakai =  'analisa_quarry';
                            break;
                        case 'analisa_bm':
                            $tabel_pakai =  'analisa_pekerjaan_bm';
                            break;
                        case 'analisa_ck':
                            $tabel_pakai =  'analisa_pekerjaan_ck';
                            break;
                        case 'analisa_sda':
                            $tabel_pakai =  'analisa_pekerjaan_sda';
                            break;
                        case 'rab':
                            $tabel_pakai = 'rencana_anggaran_biaya';
                            break;
                        case 'schedule':
                        case 'tabel_schedule':
                            $tabel_pakai = 'schedule_table';
                            break;
                        case 'lokasi':
                        case 'lokasi-lokasi':
                        case 'lokasi-marker':
                        case 'lokasi-polyline':
                        case 'lokasi-polygon':
                            $tabel_pakai = 'lokasi_proyek';
                            break;
                        case 'peraturan':
                            $tabel_pakai = 'peraturan_data_umum';
                            break;
                        case 'monev':
                        case 'monev[informasi]':
                        case 'monev[realisasi]':
                        case 'monev[laporan]':
                            $tabel_pakai = 'monev';
                            break;
                        case 'lap-harian':
                            $tabel_pakai = 'laporan_harian';
                            break;
                        case 'profil':
                            $tabel_pakai =  'user_ahsp';
                            break;
                        case 'outbox':
                        case 'wall':
                            $tabel_pakai = 'ruang_chat';
                            break;
                        default:
                    }
                    $code = 10;
                    $sukses = true;
                    $err = 0;
                    $columnName = "*";
                    $Tk = 0;
                    $op = 0;
                    //$tahun_anggaran = 0;
                    $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);
                    $jumlahArray = is_array($data_kd_proyek) ? count($data_kd_proyek) : 0;
                    if ($jumlahArray > 0) {
                        $kd_proyek = $data_kd_proyek[0]->kd_proyek_aktif;
                        $data['dataProyek'] = $DB->getWhere('nama_pkt_proyek', ['kd_proyek', '=', $kd_proyek]);
                        //var_dump($tahun_anggaran);
                        $jumlahArray = is_array($data['dataProyek']) ? count($data['dataProyek']) : 0;
                        if ($jumlahArray > 0) {
                            $data['dataProyek'] = $data['dataProyek'][0];
                            $tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                            #informasi umum
                            $informasi = $DB->getWhere('informasi_umum', ['kd_proyek', '=', $kd_proyek]);
                            $jumlahArray = is_array($informasi) ? count($informasi) : 0;
                            if ($jumlahArray > 0) {
                                foreach ($informasi as $key => $value) {
                                    $kode_informasi = $value->kode;
                                    ${$value->kode} = $value->nilai;
                                    switch ($kode_informasi) {
                                        case 'sukuBunga_i':
                                            $sukuBunga_i = $value->sukuBunga_i;
                                            break;
                                        case 'op': //overhead and profit
                                            $op = $value->nilai; // jam kerja efektif
                                            break;
                                        case 'Tk':
                                            $Tk = $value->nilai; // jam kerja efektif
                                            break;
                                        case 'MPP':
                                            $MPP = $value->nilai; // waktu pelaksanaan
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                }
                            }
                        }
                    }
                    if ($tbl == 'sortir') {
                        $jenis = 'sortir';
                    }
                    //start buat property
                    switch ($jenis) {
                        case 'outbox':
                            switch ($tbl) {
                                case 'add_coment':
                                    $set = [
                                        'waktu_input' => date('Y-m-d H:i:s'),
                                        'uraian' => $Fungsi->enskripsiText($uraian, $encryption_key = $_SESSION["user"]["nama"]),
                                        'id_tujuan' => $id_tujuan,
                                        'id_pengirim' => $_SESSION["user"]["id"]
                                    ];
                                    $kodePosting = 'insert';
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'wall':
                            switch ($tbl) {
                                case 'reply':
                                    $kodePosting = 'insert';
                                    $set = [
                                        'waktu_input' => date('Y-m-d H:i:s'),
                                        'uraian' => $Fungsi->enskripsiText($uraian, $_SESSION["user"]["nama"]),
                                        'id_pengirim' => $_SESSION["user"]["id"],
                                        'id_reply' => $id_row
                                    ];
                                    // var_dump($set);
                                    break;
                                case 'add_coment':
                                    $datetime = mb_convert_encoding(html_entity_decode(date('Y-m-d H:i:s')), "UTF-8");
                                    //catatan uraian ganti menjadi jenis blob di mysql
                                    $set = [
                                        'waktu_input' => $datetime,
                                        'uraian' => $Fungsi->enskripsiText($uraian, $encryption_key = $_SESSION["user"]["nama"]),
                                        'id_pengirim' => $_SESSION["user"]["id"]
                                    ];
                                    $kodePosting = 'insert';
                                    //var_dump($set);
                                    break;
                                case 'edit':
                                    $kondisi = [['id', '=', $id_row]];
                                    $kodePosting = 'update_row';
                                    $set = [
                                        'waktu_edit' => date('Y-m-d H:i:s'),
                                        'uraian' => $Fungsi->enskripsiText($uraian, $encryption_key = $_SESSION["user"]["nama"]),
                                        'id_pengirim' => $_SESSION["user"]["id"],
                                    ];
                                    break;
                                case 'like':
                                    //$like ['like', 'unlike', 'non']
                                    $id_pengirim = $_SESSION["user"]["id"];
                                    $set = [
                                        'like' => "JSON_INSERT(like, '$.like', '{id:$id_pengirim}')" //$klm = JSON_INSERT($klm, '$.$kode ', ?)
                                    ];
                                    break;
                                case 'pesan':
                                    $set = [
                                        'waktu_input' => date('Y-m-d H:i:s'),
                                        'uraian' => $Fungsi->enskripsiText($uraian, $encryption_key = $_SESSION["user"]["nama"]),
                                        'id_pengirim' => $_SESSION["user"]["id"],
                                        'id_tujuan' => $id_tujuan,
                                        'dibaca' => $dibaca,
                                        'like' => $like
                                    ];

                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'peraturan':
                            switch ($tbl) {
                                case 'edit':
                                    $kondisi = [['id', '=', $id_row]];
                                    $kodePosting = 'update_row';
                                case 'input':
                                    if ($tbl == 'input') {
                                        $kondisi = [['uraian', '=', $uraian]];
                                        $kodePosting = 'cek_insert';
                                    }
                                    $set = [
                                        'uraian' => $uraian,
                                        'keterangan' => $keterangan,
                                        'type' => $type,
                                        'tanggal' => $tanggal,
                                        'tanggal_upload' => date('Y-m-d H:i:s'),
                                        'status' => $status,
                                        'id_user' => $id_user,
                                        'kd_proyek' => $kd_proyek, //jika status = proyek
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                    //pengolahan file

                                    if ($_FILES['file']) {
                                        //var_dump($_FILES['file']);
                                        $file = $Fungsi->importFile($jenis, '');
                                        if ($file['result'] == 'ok') {
                                            $set['file'] = $file['file'];
                                        }
                                    }

                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'rekanan':
                            switch ($tbl) {
                                case 'edit':
                                    $kondisi = [['id', '=', $id_row]];
                                    $kodePosting = 'update_row';
                                case 'input':
                                    if ($tbl == 'input') {
                                        $kondisi = [['nama_perusahaan', '=', $nama_perusahaan]];
                                        $kodePosting = 'cek_insert';
                                    }
                                    //var_dump($notaris_perubahan);
                                    $set = [
                                        'nama_perusahaan' => $nama_perusahaan,
                                        'alamat' => $alamat,
                                        'npwp' => $npwp,
                                        'direktur' => $direktur,
                                        'no_ktp' => $no_ktp,
                                        'alamat_dir' => $alamat_dir,
                                        'no_akta_pendirian' => $no_akta_pendirian,
                                        'tgl_akta_pendirian' => $tgl_akta_pendirian,
                                        'lokasi_notaris_pendirian' => $lokasi_notaris_pendirian,
                                        'nama_notaris_pendirian' => $nama_notaris_pendirian,
                                        'notaris_perubahan' => $notaris_perubahan,
                                        'data_lain' => $data_lain,
                                        'keterangan' => $keterangan
                                    ];
                                    //pengolahan file
                                    if ($_FILES['file']) {
                                        $file = $Fungsi->importFile($jenis, '');
                                        //var_dump($file);
                                        if ($file['result'] == 'ok') {
                                            $set['file'] = $file['file'];
                                        }
                                    }

                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'copy':
                            switch ($tbl) {
                                case 'analisa_quarry':
                                case 'analisa_sda':
                                case 'analisa_ck':
                                case 'analisa_bm':
                                case 'analisa_alat_custom':
                                case 'analisa_alat':
                                    $setTabelCopy = [$tabel_pakai];
                                    $kodePosting = 'insert_select';
                                    break;
                                case 'copy_lap_harian':
                                    $setTabelCopy = ['laporan_harian'];
                                    $kodePosting = 'insert_select';
                                    break;
                                case 'proyek':
                                    $setTabelCopy = ['analisa_alat', 'analisa_alat_custom', 'analisa_pekerjaan_bm', 'analisa_pekerjaan_ck', 'analisa_pekerjaan_sda', 'analisa_quarry', 'harga_sat_upah_bahan', 'informasi_umum', 'lokasi_proyek', 'rencana_anggaran_biaya', 'schedule_table'];
                                    $kodePosting = 'insert_select';
                                    break;
                            }
                            break;
                        case 'monev':
                            switch ($tbl) {
                                case 'lap-harian_edit':
                                    $tabel_pakai = 'laporan_harian';
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $set = [
                                        'kd_proyek' => $kd_proyek,
                                        'kd_analisa' => $kd_analisa, //jika cuaca kd analisa='cuaca', catatan='note'
                                        'kode' => $kode, //jika cuaca kd analisa='cuaca', catatan='note'
                                        'tanggal' => $tanggal,
                                        'type' => $type, //upah, bahan, peralatan,quarry=bahan,cuaca, catatan
                                        'uraian' => $uraian,
                                        'value' => $value_encode,
                                        'keterangan' => $keterangan
                                    ];
                                    $kodePosting = 'cek_insert';
                                    break;
                                case 'lap-harian': //input manual laporan harian
                                    $tabel_pakai = 'laporan_harian';
                                    //lalu json_decode untuk decode object.
                                    $set = [
                                        'kd_proyek' => $kd_proyek,
                                        'kd_analisa' => $kd_analisa, //jika cuaca kd analisa='cuaca', catatan='note'
                                        'kode' => $kode, //jika cuaca kd analisa='cuaca', catatan='note'
                                        'tanggal' => $tanggal,
                                        'type' => $type, //upah, bahan, peralatan,quarry=bahan,cuaca, catatan
                                        'uraian' => $uraian,
                                        'value' => $value_encode,
                                        'keterangan' => $keterangan
                                    ];
                                    $kodePosting = 'insert';
                                    break;
                                case 'edit':
                                case 'input':
                                    //ambil data rab
                                    $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_rab, 'AND']];
                                    $DB->orderBy('no_sortir');
                                    $result = $DB->getWhereCustom('rencana_anggaran_biaya', $condition);
                                    $jumlahArray = is_array($result) ? count($result) : 0;
                                    if ($jumlahArray) {
                                        foreach ($result as $key => $value) {
                                            $kd_analisa = $value->kd_analisa;
                                            $uraian = $value->uraian;
                                            $volume = $value->volume;
                                            $satuan = $value->satuan;
                                            $jumlah_harga = $value->jumlah_harga;
                                        };
                                        //ambil realisasi item pek di tabel monev
                                        $where = "kd_proyek = ? AND id_rab = ?";
                                        $data_where =  [$kd_proyek, $id_rab];
                                        $sum = $DB->getQuery("SELECT SUM(realisasi_fisik) AS realisasiFisik, SUM(realisasi_keu) AS realisasiKeuangan FROM $tabel_pakai WHERE $where", $data_where);
                                        if ($sum) {
                                            $sumRealisasi_fisik = (float)$sum[0]->realisasiFisik;
                                            $sumRealisasi_keu = (float)$sum[0]->realisasiKeuangan;
                                            //olah data dan insert
                                            $sisaVolume = $volume - $sumRealisasi_fisik;
                                            $sisaVolume = ($sisaVolume >= 0) ? $sisaVolume : 0;
                                            $sisaKeu = $jumlah_harga - $sumRealisasi_keu;
                                            $sisaKeu = ($sisaKeu >= 0) ? $sisaKeu : 0;
                                            $realisasi_fisik = ($realisasi_fisik <= $sisaVolume) ? $realisasi_fisik : $sisaVolume;
                                            $realisasi_keu = ($realisasi_keu <= $sisaKeu) ? $realisasi_keu : $sisaKeu;
                                            $file = ''; // masih maintenance
                                            $where1 = 'kd_proyek = ?';
                                            $data_where1 = [$kd_proyek];
                                            $max = $DB->getQuery("SELECT MAX(no_sortir) AS max_no_sortir FROM $tabel_pakai WHERE $where1", $data_where1);
                                            $max_no_sortir = $max[0]->{'max_no_sortir'} + 1;
                                            $no_sortir = $max_no_sortir;
                                            $set = [
                                                'kd_proyek' => $kd_proyek,
                                                'id_rab' => $id_rab,
                                                'uraian' => $uraian,
                                                'satuan' => $satuan,
                                                'tanggal' => $tanggal,
                                                'realisasi_fisik' => $realisasi_fisik,
                                                'realisasi_keu' => $realisasi_keu,
                                                'tgl_input' => date('Y-m-d H:i:s'),
                                                'keterangan' => $keterangan,
                                                'no_sortir' => $no_sortir
                                            ];
                                            //pengolahan file
                                            if ($_FILES['file']) {
                                                $file = $Fungsi->importFile($jenis, $kd_proyek);
                                                if ($file['result'] == 'ok') {
                                                    $set['file'] = $file['file'];
                                                }
                                            }

                                            //================================================
                                            //tambahkan juga tenaga alat dan bahan dari analisa di tabel laporan_harian
                                            //================================================
                                            //Binamarga
                                            $condition = 'WHERE kd_proyek = ? AND kd_analisa = ? AND nomor = ?';
                                            $tableName = 'analisa_pekerjaan_bm';
                                            $bindValue = [$kd_proyek, $kd_analisa, '>>'];
                                            $DB->orderBy('jenis_kode');
                                            $resulAnalisa = $DB->get($tableName, $condition, $bindValue);
                                            //var_dump($bindValue);
                                            //var_dump($resulAnalisa);
                                            $jumlahArray = is_array($resulAnalisa) ? count($resulAnalisa) : 0;
                                            $type = 'header'; //1. analisa,2.keterangan, 3.header
                                            if ($jumlahArray) {
                                                $type = 'analisa';
                                            } else {
                                                //Cipta Karya
                                                $condition = 'WHERE kd_proyek = ? AND kd_analisa = ? AND uraian != ?';
                                                $tableName = 'analisa_pekerjaan_ck';
                                                $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                                $resulAnalisa = $DB->get($tableName, $condition, $bindValue);
                                                $jumlahArray = is_array($resulAnalisa) ? count($resulAnalisa) : 0;
                                                if ($jumlahArray) {
                                                    $type = 'analisa';
                                                } else {
                                                    //SDA
                                                    $condition = 'WHERE kd_proyek = ? AND kd_analisa = ? AND nomor = ?';
                                                    $tableName = 'analisa_pekerjaan_sda';
                                                    $bindValue = [$kd_proyek, $kd_analisa, '>>'];
                                                    $resulAnalisa = $DB->get($tableName, $condition, $bindValue);
                                                    $jumlahArray = is_array($resulAnalisa) ? count($resulAnalisa) : 0;
                                                    if ($jumlahArray) {
                                                        $type = 'analisa';
                                                    }
                                                }
                                            }
                                            //var_dump($resulAnalisa);
                                            $code = 202;
                                            if (($realisasi_fisik + $realisasi_keu) > 0) {
                                                switch ($tbl) {
                                                    case 'input':
                                                        $kodePosting = 'insert';
                                                        break;
                                                    case 'edit':
                                                        //var_dump($kd_proyek);
                                                        $kodePosting = 'cek_insert';
                                                        //$kondisi, $columnName
                                                        $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                                        //$columnName = '';
                                                        break;
                                                    default:
                                                        #code...
                                                        break;
                                                };
                                            } else {
                                                $code = 405;
                                            }
                                        } else {
                                            $code = 404;
                                            $data = $sum;
                                        }
                                        $code = 202;
                                    } else {
                                        $code = 36;
                                    }
                                    break;
                            }
                            break;
                        case 'monev[informasi]':
                            //$menetapkan_1 = mb_convert_encoding(strip_tags($_POST['tabel_menetapkan_1']), 'UTF-8', 'ISO-8859-1');//utf8_encode(strip_tags($_POST['tabel_menetapkan_1']));
                            switch ($tbl) {
                                case 'get_list':
                                    $condition = [['kd_proyek', '=', $kd_proyek]];
                                    $DB->orderBy('no_sortir');
                                    $result = $DB->getWhereCustom('nama_pkt_proyek', $condition);
                                    $jumlahArray = is_array($result) ? count($result) : 0;
                                    //var_dump($result);
                                    if ($jumlahArray) {
                                        $data['users'] = $result;
                                        $code = 202;
                                    } else {
                                        $code = 36;
                                    }
                                    break;
                                case 'edit':
                                    //cek kode proyek
                                    $cek = $DB->getWhereOnce('nama_pkt_proyek', ['kd_proyek', '=', $kd_proyek]);
                                    if ($cek) { //data sudah ada
                                        # update data$nilai_kontrak
                                        $set = ['id_pelaksana' => $id_pelaksana, 'id_konsultan' => $id_konsultan, 'nilai_kontrak' => $nilai_kontrak, 'no_kontrak' => $no_kontrak, 'tgl_kontrak' => $tgl_kontrak, 'no_spm' => $no_spm, 'tgl_spm' => $tgl_spm, 'no_pho' => $no_pho, 'tgl_pho' => $tgl_pho, 'no_fho' => $no_fho, 'tgl_fho' => $tgl_fho, 'addendum' => $dataAddendum, 'owner' => "[$owner]"];
                                        //var_dump($set);
                                        $condition = [['kd_proyek', '=', $kd_proyek], ['id_user', '=', $id_user, 'AND']];
                                        if ($type_user == 'admin') {
                                            $condition = [['kd_proyek', '=', $kd_proyek]];
                                        }
                                        $cek = $DB->update_array('nama_pkt_proyek', $set, $condition);
                                        $code = ($cek) ? $code = 3 : $code = 33;
                                    } else {
                                        $code = 404;
                                    }
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'monev[realisasi]':
                        case 'monev[laporan]':
                            switch ($tbl) {
                                case 'get_list':
                                    # code...
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'sortir':
                            $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                            $DB->orderBy('no_sortir');
                            $result = $DB->getWhereCustom($tabel_pakai, $condition);

                            $jumlahArray = is_array($result) ? count($result) : 0;
                            //var_dump($result);
                            if ($jumlahArray) {
                                foreach ($result[0] as $row) {
                                    $no_sortir_awal = (int)$row->no_sortir;
                                }
                                switch ($mode_sortir) {
                                    case 'up_row':
                                        $no_sortir_change = $no_sortir_awal + 1; //
                                        $no_sortir_final = $no_sortir_awal - 1; //utama
                                        break;
                                    case 'down_row':
                                        $no_sortir_change = $no_sortir_awal - 1;
                                        $no_sortir_final = $no_sortir_awal + 1;
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                switch ($tabel_pakai) {
                                    case 'analisa_alat':
                                        # code...
                                        break;
                                    case 'analisa_alat_custom':
                                        # code...
                                        break;
                                    case 'daftar_satuan':
                                        # code...
                                        break;
                                    case 'rencana_anggaran_biaya':
                                        //cari dan update nomor sortir yang sama dengan $no_sortir_final dan ubah minus atau tambah
                                        $kondisi = [['kd_proyek', '=', $kd_proyek], ['no_sortir', '=', $no_sortir_final, 'AND']];
                                        $set = [
                                            'no_sortir' => $no_sortir_change
                                        ];
                                        $kondisi2 = $condition;
                                        $set2 = [
                                            'no_sortir' => $no_sortir_final
                                        ];
                                        break;
                                    case 'schedule_table':
                                        # code...
                                        break;
                                    case 'lokasi_proyek':
                                        # code...
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                                $kodePosting = 'update_row';
                            } else {
                                $code = 404;
                            }
                            break;
                        case 'lokasi':
                        case 'lokasi-lokasi':
                        case 'lokasi-marker':
                        case 'lokasi-trase':
                            switch ($tbl) {
                                case 'add_row':
                                    $type = $dataArray->geometry->type;
                                    $kode = $dataArray->properties->kode;
                                    //var_dump($type);
                                    switch ($type) {
                                        case 'LineString':
                                            $klm = 'polyline';
                                            break;
                                        case 'Polygon':
                                            $klm = 'polygon';
                                            break;
                                        case 'Point':
                                            $klm = 'marker';
                                            if (property_exists($dataArray->properties, 'radius')) {
                                                $Radius = $dataArray->properties->radius;
                                                $type = 'Circle';
                                                $klm = 'circle';
                                            }
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }

                                    $geometry = json_encode($dataArray); //$dataArray_awal = $dataArray;
                                    //$geometry = $dataArray;
                                    //ambil jumlah data json dengan SELECT JSON_LENGTH(sort) FROM channels WHERE bouquet='["28"]';
                                    $query = "SELECT JSON_LENGTH($klm) AS jumlah FROM $tabel_pakai WHERE kd_proyek = ?";
                                    $result = $DB->getQuery($query, [$kd_proyek]); //$resul = $DB->runQuery($query, $bindValue);
                                    $jumlahDataJson = $result[0]->jumlah;
                                    //query insert
                                    $query = "UPDATE $tabel_pakai SET $klm = JSON_INSERT($klm, '$.$kode', ?) WHERE kd_proyek = ?";
                                    //$query = "UPDATE $tabel_pakai SET $klm = JSON_UNQUOTE(JSON_INSERT($klm, '$.$kode', ?)) WHERE kd_proyek = ?";
                                    $bindValue = [$geometry, $kd_proyek];
                                    $kodePosting = 'insert_json';
                                    break;
                                case 'update':
                                    $uraianDataArray = $dataArray->properties->uraian;
                                    //var_dump($uraianDataArray);
                                    switch ($uraianDataArray) {
                                        case 'lokasi proyek':
                                            //var_dump($kd_proyek);
                                            $validate = new Validate($dataArray->geometry->coordinates);
                                            //var_dump($validate );
                                            $latitude = $validate->setRules(0, 'latitude', [
                                                'required' => true,
                                                'numeric' => true,
                                                'min_char' => 1
                                            ]);
                                            $longitude = $validate->setRules(1, 'longitude', [
                                                'required' => true,
                                                'numeric' => true,
                                                'min_char' => 1
                                            ]);
                                            //var_dump($latitude);
                                            if ($validate->passed()) {
                                                $kodePosting = 'update_row';
                                                $kondisi = [['kd_proyek', '=', $kd_proyek]];
                                                $set = [
                                                    'sta_pengenal_X' => (float)$latitude,
                                                    'sta_pengenal_Y' => (float)$longitude,
                                                    'keterangan' => $uraianDataArray
                                                ];
                                            } else {
                                                //var_dump($validate->getError());
                                            }

                                            break;
                                        case 'value':
                                            # code...
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                    break;
                                case 'edit':
                                    $kodePosting = 'update_row';
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $set = [
                                        'sta_pengenal_X' => $durasi,
                                        'sta_pengenal_Y' => $mulai,
                                        'bobot' => $bobot,
                                        'bobot_selesai' => $bobot_selesai,
                                        'dependent' => $dependent,
                                        'keterangan' => $keterangan
                                    ];
                                    break;
                                default:

                                    break;
                            }
                            break;
                        case 'schedule':
                            $tabel_pakai = 'schedule_table';
                            switch ($tbl) {
                                case 'edit':
                                    $kodePosting = 'update_row';
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $set = [
                                        'data' => $dataArray,
                                        'durasi' => $durasi,
                                        'mulai' => $mulai,
                                        'dependent' => "[$dependent]", //json format array
                                        'keterangan' => $keterangan
                                    ];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'divisi':
                        case 'divisiBM':
                        case 'divisiCK':
                        case 'divisiSDA':
                            switch ($tbl) {
                                case 'edit':
                                    break;
                                case 'input':
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'rab':
                            switch ($tbl) {
                                case 'edit':
                                case 'add_row':
                                    // analisa bisa > 1 tiap RAB makanya langsung insert
                                    //
                                    #ambil data di analisa
                                    //Binamarga
                                    $condition = 'WHERE kd_proyek = ? AND kd_analisa = ? AND uraian = ?';
                                    $tableName = 'analisa_pekerjaan_bm';
                                    $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                    $resul = $DB->get($tableName, $condition, $bindValue);
                                    $jumlahArray = is_array($resul) ? count($resul) : 0;
                                    $type = 'header'; //1. analisa,2.keterangan, 3.header
                                    if ($jumlahArray) {
                                        $type = 'analisa';
                                    } else {
                                        //Cipta Karya
                                        $tableName = 'analisa_pekerjaan_ck';
                                        $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                        $resul = $DB->get($tableName, $condition, $bindValue);
                                        $jumlahArray = is_array($resul) ? count($resul) : 0;
                                        if ($jumlahArray) {
                                            $type = 'analisa';
                                        } else {
                                            //SDA
                                            $tableName = 'analisa_pekerjaan_sda';
                                            $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                            $resul = $DB->get($tableName, $condition, $bindValue);
                                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                                            if ($jumlahArray) {
                                                $type = 'analisa';
                                            }
                                        }
                                    }
                                    // var_dump((array)$resul);
                                    //$resul = json_decode(json_encode($resul), TRUE);
                                    //var_dump($resul);
                                    $jumlahArray = is_array((array)$resul[0]) ? count((array)$resul[0]) : 0;
                                    if ($jumlahArray) {
                                        $resul = (array)$resul[0];
                                        //$satuan = $satuan;
                                        $harga_dasar = (float)$resul['koefisien'];
                                        $volume = (float)$volume;

                                        $jumlah_op = $op / 100 * $harga_dasar;
                                        $harga_satuan = $jumlah_op + $harga_dasar;
                                        $jumlah_harga = (float)$harga_satuan * $volume; //diluar ppn
                                        $keterangan = $resul['keterangan'];
                                    } else {
                                        $harga_dasar = 0;
                                        $jumlah_op = 0;
                                        $harga_satuan = 0;
                                        $jumlah_harga = 0; //diluar ppn
                                    }
                                    switch ($tbl) {
                                        case 'edit':
                                            $kodePosting = 'update_row';
                                            $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                            $set = [
                                                'kd_analisa' => $kd_analisa,
                                                'uraian' => $uraian,
                                                'volume' => $volume,
                                                'satuan' => $satuan,
                                                'harga_dasar' => $harga_dasar,
                                                'harga_satuan' => $harga_satuan,
                                                'jumlah_harga' => $jumlah_harga,
                                                'jumlah_op' => $jumlah_op,
                                                'keterangan' => $keterangan
                                            ];
                                            break;
                                        case 'add_row':
                                            // ambil data nor_sortir maksimal tambahkan 1 
                                            $where1 = 'kd_proyek = ?';
                                            $data_where1 = [$kd_proyek];
                                            $max = $DB->getQuery("SELECT MAX(no_sortir) AS max_no_sortir FROM $tabel_pakai WHERE $where1", $data_where1);
                                            $max_no_sortir = $max[0]->{'max_no_sortir'} + 1; //cara mengambil value object di array
                                            //var_dump($max_no_sortir);
                                            $set = ['kd_proyek' => $kd_proyek, 'kd_analisa' => $kd_analisa, 'uraian' => $uraian, 'volume' => $volume, 'satuan' => $satuan, 'harga_dasar' => $harga_dasar, 'harga_satuan' => $harga_satuan, 'jumlah_harga' => $jumlah_harga, 'jumlah_op' => $jumlah_op, 'keterangan' => $keterangan, 'no_sortir' => $max_no_sortir, 'type' => $type];
                                            $kodePosting = 'insert';
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'proyek':
                            switch ($tbl) {
                                case 'edit':
                                    //cek kode proyek
                                    $cek = $DB->getWhereOnce($tabel_pakai, ['kd_proyek', '=', $kode]);
                                    //var_dump($cek);
                                    if ($cek) { //data sudah ada
                                        # update data
                                        $set = ['tahun_anggaran' => $tahun_proyek, 'nama_proyek' => $uraian, 'tanggal_buat' => date('Y-m-d H:i:s'), 'id_user' => $id_user, 'nama_user' => $username, 'keterangan' => $keterangan, 'status' => $status_proyek];
                                        $cek = $DB->update_array($tabel_pakai, $set, [['kd_proyek', '=', $kode], ['id_user', '=', $id_user, 'AND']]);
                                        $code = ($cek) ? $code = 31 : 33;
                                    } else {
                                        $kodePosting = 'insert';
                                        $set = ['kd_proyek' => $kode, 'tahun_anggaran' => $tahun_proyek, 'nama_proyek' => $uraian, 'tanggal_buat' => date('Y-m-d H:i:s'), 'id_user' => $id_user, 'nama_user' => $username, 'keterangan' => $keterangan, 'status' => $status_proyek];
                                    }
                                    //update data user kode proyek aktif
                                    if ($aktifkan_proyek == 'on') {
                                        $cek = $DB->update('user_ahsp', ['kd_proyek_aktif' => $kode], ['username', '=', $username]);
                                        $kode = ($cek) ? $_SESSION["user"]["kd_proyek_aktif"] = $kode : $cek;
                                    }
                                    //tambahkan informasi umum
                                    break;
                                case 'tambah_proyek':
                                    //cek kode proyek
                                    $cek = $DB->getWhereOnce($tabel_pakai, ['kd_proyek', '=', $kode]);
                                    //var_dump($cek);
                                    if ($cek) { //data sudah ada
                                        # update data
                                        $code = 30;
                                    } else {
                                        $kodePosting = 'insert';
                                        $set = ['kd_proyek' => $kode, 'tahun_anggaran' => $tahun_proyek, 'nama_proyek' => $uraian, 'tanggal_buat' => date('Y-m-d H:i:s'), 'id_user' => $id_user, 'nama_user' => $username, 'keterangan' => $keterangan, 'status' => $status_proyek];
                                    }
                                    //update data user kode proyek aktif
                                    if ($aktifkan_proyek == 'on') {
                                        $cek = $DB->update('user_ahsp', ['kd_proyek_aktif' => $kode], ['username', '=', $username]);
                                        $retVal = ($cek) ? $_SESSION["user"]["kd_proyek_aktif"] = $kode : $cek;
                                    }
                                    //tambahkan informasi umum
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'profil':
                            switch ($tbl) {
                                case 'edit':
                                    $kondisi = [['id', '=', $id_user]];
                                    $set = [
                                        'username' => $username,
                                        'email' => $email,
                                        'nama' => $nama,
                                        'kontak_person' => $kontak_person,
                                        'nama_org' => $nama_org,
                                        'type_user' => $type_user,
                                        'ket' => $ket
                                    ];
                                    $kodePosting = 'cek_insert';
                                    break;
                                case 'profil':
                                    $kondisi = [['id', '=', $id_user], ['type_user', '=', $type_user, 'AND']];
                                    $set = [
                                        'nama' => $nama,
                                        'nama_org' => $nama_org,
                                        //'photo'=>$nama,
                                        'ket' => $ket,
                                        'kontak_person' => $kontak_person,
                                        'font_size' => $font,
                                        'warna_tbl' => $warna_tbl,
                                    ];
                                    if ($_FILES['file']) {
                                        $file = $Fungsi->importFile($jenis, $kd_proyek = '');
                                        if ($file['result'] == 'ok') {
                                            $set['photo'] = $file['file'];
                                        }
                                    }

                                    $kodePosting = 'cek_insert';
                                    //var_dump($file);
                                    break;
                                default:
                                    # code...
                                    break;
                            }

                            break;
                        case 'x':
                            switch ($tbl) {
                                case 'y':
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        default:
                            $code = 406;
                    }
                    //JENIS POST DATA/INSERT DATA
                    switch ($kodePosting) {
                        case 'insert_select': //copy rows dan insert kembali dengan tabel yang sama
                            switch ($tbl) {
                                case 'proyek':
                                    # untuk proyek insert dulu data di tabel nama_pkt_proyek setelah berhasil lalu insert select tabel lain
                                    $tabel_pakai = 'nama_pkt_proyek';
                                    $set = ['kd_proyek' => $kode, 'tahun_anggaran' => $tahun_proyek, 'nama_proyek' => $uraian, 'tanggal_buat' => date('Y-m-d H:i:s'), 'id_user' => $id_user, 'nama_user' => $username, 'keterangan' => $keterangan, 'status' => $type_user];
                                    $resul = $DB->insert($tabel_pakai, $set);
                                    if ($DB->lastInsertId()) {
                                        $data['note']['add row'] = $DB->lastInsertId(); //$resul->count;
                                        //update data user kode proyek aktif
                                        if ($aktifkan_proyek == 'on') {
                                            $cek = $DB->update('user_ahsp', ['kd_proyek_aktif' => $kode], ['username', '=', $username]);
                                            $retVal = ($cek) ? $_SESSION["user"]["kd_proyek_aktif"] = $kode : $cek;
                                        }
                                        $code = 2;
                                    }
                                    //
                                    $where = 'kd_proyek = ?';
                                    $data_where = [$kd_proyek_copy];
                                    break;
                                case 'copy_lap_harian':
                                    $where = 'kd_proyek = ? AND tanggal = ?';
                                    $data_where = [$kd_proyek, $tanggal_copy];
                                    break;
                                case 'analisa_quarry':
                                case 'analisa_sda':
                                case 'analisa_ck':
                                case 'analisa_bm':
                                case 'analisa_alat_custom':
                                    $where = 'kd_proyek = ? AND kd_analisa = ?';
                                    $data_where = [$kd_proyek, $kd_analisa_copy];
                                    break;
                                case 'analisa_alat':
                                    $where = 'kd_proyek = ? AND kode = ?';
                                    $data_where = [$kd_proyek, $kd_analisa_copy];
                                    break;
                                default:
                                    break;
                            }
                            //$setTabelCopy : kumpulan tabel yang akan di copy paste row
                            foreach ($setTabelCopy as $key => $value) {
                                $tableName = $value;
                                //var_dump($tableName);
                                //ambil nama kolom
                                /*
                        SELECT `COLUMN_NAME` 
                        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                        WHERE `TABLE_SCHEMA`='yourdatabasename' 
                        AND `TABLE_NAME`='yourtablename';
                        */
                                $namaKolomArr = $DB->runQuery2("SHOW columns FROM $tableName");
                                //var_dump($namaKolomArr);
                                $namaKolom = [];
                                foreach ($namaKolomArr as $key1 => $value1) {
                                    //var_dump($key1);
                                    //var_dump($value1);
                                    if ($namaKolomArr[$key1]['Field']) {
                                        $namaKolom[] = $namaKolomArr[$key1]['Field'];
                                    }
                                }
                                if (($key = array_search('id', $namaKolom)) !== false) {
                                    unset($namaKolom[$key]);
                                }
                                $columnArrayInsert = $namaKolom;
                                switch ($tbl) {
                                    case 'proyek':
                                        //keluarkan jg kd_proyek dengan kd_proyek baru
                                        if (($key = array_search('kd_proyek', $columnArrayInsert)) !== false) {
                                            $columnArrayInsert[$key] = "'$kode'";
                                        }
                                        break;
                                    case 'copy_lap_harian':
                                        if (($key = array_search('tanggal', $columnArrayInsert)) !== false) {
                                            $columnArrayInsert[$key] = "'$tanggal'";
                                        }
                                        break;
                                    case 'analisa_quarry':
                                    case 'analisa_bm':
                                    case 'analisa_sda':
                                    case 'analisa_ck':
                                    case 'analisa_alat_custom':
                                        if (($key = array_search('kd_analisa', $columnArrayInsert)) !== false) {
                                            $columnArrayInsert[$key] = "'$kode'";
                                        }
                                        break;
                                    case 'analisa_alat':
                                        if (($key = array_search('kode', $columnArrayInsert)) !== false) {
                                            $columnArrayInsert[$key] = "'$kode'";
                                        }
                                        if (($key = array_search('jenis_peralatan', $columnArrayInsert)) !== false) {
                                            $columnArrayInsert[$key] = "'$uraian'";
                                        }
                                        break;
                                    default:
                                        break;
                                }
                                $namaKolom = implode(",", $namaKolom); //array_map('strval', $namaKolom);
                                $columnArray = $namaKolom;
                                $columnArrayInsert = implode(",", $columnArrayInsert);
                                //$data_where = '';
                                $bindValue = $data_where;
                                $query = "INSERT INTO $tableName ($columnArray) SELECT $columnArrayInsert FROM $tableName WHERE $where";
                                //var_dump($bindValue);
                                //var_dump($query);
                                $resul = $DB->runQuery($query, $bindValue);
                                $code = 50;
                                // harus dirubah kolom kode dan uraian untuk ck atau kolom nomor ut bm/sda/quarry/alat_custom 
                                //yang mempunyai nilai sama dengan $kd_analisa_copy
                                //harus di rubah id terakhir yang dirubah jangan semua
                                switch ($tbl) {
                                    case 'analisa_quarry':
                                    case 'analisa_bm':
                                    case 'analisa_sda':
                                    case 'analisa_ck':
                                    case 'analisa_alat_custom':
                                        switch ($tbl) {
                                            case 'analisa_quarry':
                                                $keterangan = '{"lokasi":"' . $lokasiQuarry . '", "tujuan":"' . $tujuanQuarry . '"}';
                                                $setUpdate = ['nomor' => $kode, 'uraian' => $uraian, 'keterangan' => $keterangan, 'jenis_kode' =>  'summary:' . $kode];
                                                $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kode, 'AND'], ['nomor', '=', $kd_analisa_copy, 'AND']];
                                                break;
                                            case 'analisa_ck':
                                                $setUpdate = ['kode' => $kode, 'nomor' => $kode, 'uraian' => $uraian, 'jenis_kode' =>  'summary:' . $kode];
                                                //$kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kode, 'AND'], ['nomor', '=', $kd_analisa_copy, 'AND'], ['kode', '=', $kd_analisa_copy, 'OR']];
                                                $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kode, 'AND'], ['nomor', '=', $kd_analisa_copy, 'AND'], ['kode', '=', $kd_analisa_copy, 'AND']];
                                                break;
                                            case 'analisa_bm':
                                            case 'analisa_sda':
                                            case 'analisa_alat_custom':
                                                $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kode, 'AND'], ['nomor', '=', $kd_analisa_copy, 'AND']];
                                                $setUpdate = ['nomor' => $kode, 'uraian' => $uraian, 'jenis_kode' =>  'summary:' . $kode];
                                                //insert ke tabel analisa alat
                                                $dataInsertAlat = [
                                                    'kd_proyek' => $kd_proyek,
                                                    'kode' => $kode,
                                                    'kapasitas' => $kapasitas,
                                                    'sat_kapasitas' => $sat_kapasitas,
                                                    'jenis_peralatan' => $uraian,
                                                    'total_biaya_sewa' => 0,
                                                    'keterangan' => 'analisa_alat_custom'
                                                ];
                                                $resul = $DB->insert('analisa_alat', $dataInsertAlat);
                                                break;
                                                // case 'analisa_alat':
                                                //     break;
                                            default:
                                                break;
                                        }
                                        $resul = $DB->update_array($tableName, $setUpdate, $kondisi);

                                        break;
                                    default:
                                        break;
                                }
                            }
                            break;
                        case 'update_analisa': // untuk analisa
                            //var_dump($hasil);
                            //hapus id row yang terdapat di $id_delete (kumpulan row dihapus)
                            if ($id_delete) {
                                foreach ($id_delete as $key_del => $val_del) {
                                    $condition = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['id', '=', $val_del, 'AND']];
                                    $resul_del = $DB->delete_array($tabel_pakai, $condition);
                                }
                            }
                            //rekam ulang/update
                            foreach ($hasil as $key => $val) {
                                //var_dump($val);
                                if (array_key_exists('id', $val)) {
                                    $id_rows = $val['id'];
                                }
                                $sumRows = 0;
                                if ($id_rows) {
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['id', '=', $id_rows, 'AND']];
                                    //cari id klo ditemukan update kalo tidak ditemukan insert baru
                                    //$set = unset($val["id"], $val["kd_proyek"], $val["key3"]);
                                    $val_update = $val;
                                    unset($val_update["id"], $val_update["kd_proyek"], $val_update["kd_analisa"]);
                                    $set = $val_update;
                                    //var_dump($set);
                                    $sumRows = $DB->getWhereArray($tabel_pakai, $kondisi);
                                    //var_dump($sumRows);
                                }
                                $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                                if ($jumlahArray) {
                                    $resul = $DB->update_array($tabel_pakai, $set, $kondisi);
                                    //var_dump($DB->count());
                                    //$jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                    if ($DB->count()) {
                                        $code = 3;
                                        $data['update'][] = $id_rows; //$DB->count();
                                        // ambil data yang diupdate untuk tabel website
                                        $data['dtupdate'][] = $val;
                                        //jika analisa peralatan custom update row tabel analisa_alat
                                        if ($jenis == 'analisa_alat_custom') {
                                            $nomor_row = $val_update['nomor'];
                                            if ($nomor_row == $id) {
                                                $kondisi2 = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $id, 'AND'], ['keterangan', '=', 'analisa_alat_custom', 'AND']];
                                                $set2 = ['total_biaya_sewa' => $val_update['koefisien']];
                                                $DB->update_array('analisa_alat', $set2, $kondisi2);
                                            }
                                        }
                                    } else {

                                        $data['NA'][] = $id_rows; //$DB->count();
                                    }
                                } else {
                                    // data id tidak terupdate di server
                                    $val_insert = $val;
                                    unset($val_insert["id"]);
                                    $resul = $DB->insert($tabel_pakai, $val_insert);
                                    //$jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                    if ($DB->count()) {
                                        $data['add_row'][] = $key;
                                        //jika nomor=kd_analisa
                                        //jika jns analisa alat_custom masukkan di peralatan
                                    } else {
                                        $data['NA'][] = $id_rows;
                                    }
                                }
                            }
                            break;
                        case 'update_rows': // untuk banyak row
                            foreach ($dataArray as $key => $val) {
                                $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                                $set = [
                                    'value' => $value,
                                    'item' => $item,
                                    'keterangan' => $keterangan,
                                    'sebutan_lain' => $sebutan_lain
                                ];
                                $resul = $DB->update_array($tabel_pakai, $set, $kondisi);
                                //var_dump($DB->count());
                                $jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                if ($DB->count()) {
                                    $code = 3;
                                    $data['update'] = $DB->count();
                                } else {
                                    $code = 33;
                                }
                            }
                            break;
                        case 'update_row': //untuk 1 row
                            $resul = $DB->update_array($tabel_pakai, $set, $kondisi);
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            //var_dump($resul);
                            //var_dump($DB->count());
                            if ($DB->count()) {
                                $code = 3;
                                $data['update'] = $DB->count();
                                switch ($jenis) {
                                    case 'sortir':
                                        //update sortir
                                        $resul = $DB->update_array($tabel_pakai, $set2, $kondisi2);
                                        break;
                                    case 'rab':
                                        $data['rows'] = $set;
                                        $where1 = 'kd_proyek = ?';
                                        $data_where1 = [$kd_proyek];
                                        $sum = $DB->getQuery("SELECT SUM(jumlah_harga) FROM $tabel_pakai WHERE $where1", $data_where1);
                                        $data['sum'] = $sum[0]->{'SUM(jumlah_harga)'}; //cara mengambil value object di array
                                        break;
                                    case 'schedule':
                                        switch ($tbl) {
                                            case 'edit':
                                                $data['set'] = $set;
                                                //ambil jumlah rab
                                                break;
                                            default:
                                                # code...
                                                break;
                                        }
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            } else {
                                $code = 33;
                            }
                            break;
                        case 'del_insert_rows':
                            $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisi_delete);
                            //insert rows baru
                            $dataInsert = ['kd_proyek' => $kd_proyek, 'kode' => $kd_analisa, 'keterangan' => 'analisa_alat_custom'];
                            $okDummy = false;
                            foreach ($hasil_insert as $key => $value) {
                                //tambahkan object kd_proyek dan kd_analisa di value
                                //var_dump($value);
                                $value['kd_proyek'] = $kd_proyek;
                                $value['kd_analisa'] = $kd_analisa;
                                //var_dump($value);
                                if (strlen($value['uraian']) > 0 || strlen($value['kode'])) {
                                    $resul = $DB->insert($tabel_pakai, $value);
                                    $data['note']['add row'] = $DB->lastInsertId();
                                    if ($value['nomor'] == $kd_analisa) {
                                        $dataInsert['total_biaya_sewa'] = $value['koefisien'];
                                        $dataInsert['jenis_peralatan'] = $jenis_pek;
                                        $okDummy = true;
                                    }
                                }
                            }
                            if ($okDummy == false) {
                                $dataInsert['total_biaya_sewa'] = 0;
                                $dataInsert['jenis_peralatan'] = $jenis_pek;
                            }
                            //$resul = $DB->insert('analisa_alat', $dataInsert);
                            $code = 2;
                            $tabel_pakai2 = 'analisa_alat';
                            $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                            $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                            $sumRows = $DB->getWhereArray($tabel_pakai2, $getWhereArrayData);
                            $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                            if ($jumlahArray <= 0) {
                                $resul = $DB->insert($tabel_pakai2, $dataInsert);
                                $jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                if ($jumlahArray) {
                                    $data['add_row'][] = $sum;
                                }
                            } else {
                                //update row
                                $resul = $DB->update_array($tabel_pakai2, $dataInsert, $update_arrayData);
                                $jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                if ($resul) {
                                    $data['row_update'][] = $sum;
                                } else {
                                    array_push($data['gagal'], $sum);
                                    $data['gagal'][] = $sum;
                                }
                            }
                            break;
                        case 'cek_insert': //cek data klo tidak ada teruskan insert
                            $ListRow = $DB->select_array($tabel_pakai, $kondisi, $columnName);
                            //var_dump(sizeof($resul));

                            $jumlahArray = is_array($ListRow) ? count($ListRow) : 0;
                            if ($jumlahArray) {
                                //update row
                                $hasilUpdate = $DB->update_array($tabel_pakai, $set, $kondisi);
                                //var_dump($hasilUpdate);
                                if ($DB->count()) {
                                    $code = 3;
                                    $data['update'] = $DB->count(); //$DB->count();
                                    switch ($tbl) {
                                        case 'monev':
                                            switch ($tbl) {
                                                case 'edit':
                                                    // input file jika ada
                                                    // input/update tenaga bahan, peralatan di tabel laporan_harian dikalikan volume 
                                                    //var_dump($resulAnalisa);
                                                    foreach ($resulAnalisa as $r => $getData) {
                                                        $kode = $getData->kode;
                                                        $kd_analisa = $getData->kd_analisa;
                                                        $uraian = $getData->uraian;
                                                        $jenis_kode = $getData->jenis_kode;
                                                        $type = $jenis_kode;
                                                        $koefisien = $getData->koefisien;
                                                        $satuan = $getData->satuan;
                                                        $value = new stdClass;
                                                        //var_dump("$realisasi_fisik * $koefisien");
                                                        //var_dump($value);
                                                        switch ($jenis_kode) {
                                                            case 'upah':
                                                                $value->jumlah = round($realisasi_fisik * $koefisien, 12);
                                                                $value->satuan = $satuan;
                                                                break;
                                                            case 'bahan':
                                                                $value->diterima = round($realisasi_fisik * $koefisien, 12);
                                                                $value->ditolak = 0;
                                                                $value->satuan = $satuan;
                                                                break;
                                                            case 'peralatan':
                                                                $value->diterima = round($realisasi_fisik * $koefisien, 12);
                                                                $value->ditolak = 0; //alasan di keterangan
                                                                $value->merk_type = '';
                                                                $value->satuan = $satuan;
                                                                break;
                                                            case 'quarry':
                                                                //$type = 'bahan';
                                                                $value->diterima = round($realisasi_fisik * $koefisien, 12);
                                                                $value->ditolak = 0;
                                                                $value->satuan = $satuan;
                                                                break;
                                                            case 'cuaca':
                                                                break;
                                                            case 'note':
                                                                break;
                                                            default:
                                                                $type = 'NA';
                                                                break;
                                                        }
                                                        if ($type != 'NA') {
                                                            $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['kode', '=', $kode, 'AND'], ['tanggal', '=', $tanggal, 'AND']];
                                                            //var_dump($value);
                                                            $value_encode = json_encode($value); //lalu json_decode untuk decode object.
                                                            $set = [
                                                                'kd_proyek' => $kd_proyek,
                                                                'kd_analisa' => $kd_analisa, //jika cuaca kd analisa='cuaca', catatan='note'
                                                                'kode' => $kode, //jika cuaca kd analisa='cuaca', catatan='note'
                                                                'tanggal' => $tanggal,
                                                                'type' => $type, //upah, bahan, peralatan,quarry=bahan,cuaca, catatan
                                                                'uraian' => $uraian,
                                                                'value' => $value_encode,
                                                                'keterangan' => ''
                                                            ];
                                                            //dapatkan dari $ListRow data realisasi edit
                                                            $valueListRow = json_decode($ListRow[0]->value);
                                                            $resul = $DB->select_array('laporan_harian', $kondisi, $columnName);
                                                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                                                            if ($jumlahArray) {
                                                                //ubah kembali kolom value tambhkan data baru+data lama
                                                                //var_dump($resul);
                                                                $valueResul = json_decode($resul[0]->value);
                                                                //var_dump($value);
                                                                switch ($jenis_kode) {
                                                                    case 'upah':
                                                                        $value->jumlah = round((float)$valueResul->jumlah - $valueListRow->jumlah + (float) $value->jumlah, 6);
                                                                        break;
                                                                    case 'bahan':
                                                                        $value->diterima = round((float)$valueResul->diterima - $valueListRow->jumlah + (float) $value->diterima, 6);
                                                                        break;
                                                                    case 'peralatan':
                                                                        $value->diterima = round((float)$valueResul->diterima - $valueListRow->diterima + (float) $value->diterima, 6);
                                                                        break;
                                                                    case 'quarry':
                                                                        $value->diterima = round((float)$valueResul->diterima - $valueListRow->diterima + (float) $value->diterima, 6);
                                                                        break;
                                                                    case 'cuaca':
                                                                        break;
                                                                    case 'note':
                                                                        break;
                                                                    default:
                                                                        break;
                                                                }
                                                                //var_dump($value);
                                                                $set['value'] = json_encode($value);
                                                                //update data
                                                                $resul = $DB->update_array('laporan_harian', $set, $kondisi);
                                                                if ($DB->count()) {
                                                                    $code = 3;
                                                                    $data['update'] = $DB->count();
                                                                } else {
                                                                    $code = 33;
                                                                }
                                                            } else {
                                                                // inser row
                                                                $resul = $DB->insert('laporan_harian', $set);
                                                                $jumlahArray = is_array($resul) ? count($resul) : 0;
                                                                $data['note']['add row'] = $jumlahArray;
                                                                $code = 2;
                                                            }
                                                        }
                                                    }
                                                    break;
                                                default:
                                                    #code...
                                                    break;
                                            };
                                            break;
                                        case 'value1':
                                            #code...
                                            break;
                                        default:
                                            #code...
                                            break;
                                    };
                                } else {
                                    $code = 33;
                                }
                            } else {
                                // inser row
                                $resul = $DB->insert($tabel_pakai, $set);
                                $data['note']['add row'] = $DB->lastInsertId();
                                $code = 2;
                            }
                            break;
                        case 'cekdouble_insert': //cek data klo tidak ada teruskan insert jika ada jangan update
                            $resul = $DB->getWhereCustom($tabel_pakai, $kondisi);
                            //var_dump(sizeof($resul));
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            if ($jumlahArray) {
                                $code = 37;
                            } else {
                                // inser row
                                $resul = $DB->insert($tabel_pakai, $set);
                                $data['note']['add row'] = $DB->lastInsertId();
                                $code = 2;
                            }
                            break;
                        case 'insert_json':
                            $resul = $DB->runQuery($query, $bindValue);
                            break;
                        case 'insert':
                            $resul = $DB->insert($tabel_pakai, $set);
                            if ($DB->lastInsertId()) {
                                $data['note']['add row'] = $DB->lastInsertId(); //$resul->count;
                                $code = 2;
                            } else {
                                $code = 32;
                            }
                            if ($code == 2) {
                                switch ($jenis) {
                                    case 'monev':
                                        switch ($tbl) {
                                            case 'input':
                                                // input file jika ada
                                                // input/update tenaga bahan, peralatan di tabel laporan_harian di kalikan volume 
                                                //var_dump($resulAnalisa);
                                                foreach ($resulAnalisa as $r => $getData) {
                                                    $kode = $getData->kode;
                                                    $kd_analisa = $getData->kd_analisa;
                                                    $uraian = $getData->uraian;
                                                    $jenis_kode = $getData->jenis_kode;
                                                    $type = $jenis_kode;
                                                    $koefisien = $getData->koefisien;
                                                    $satuan = $getData->satuan;
                                                    $value = new stdClass;
                                                    //var_dump("$realisasi_fisik * $koefisien");
                                                    //var_dump($value);
                                                    switch ($jenis_kode) {
                                                        case 'upah':
                                                            $value->jumlah = round($realisasi_fisik * $koefisien, 12);
                                                            $value->satuan = $satuan;
                                                            break;
                                                        case 'bahan':
                                                            $value->diterima = round($realisasi_fisik * $koefisien, 12);
                                                            $value->ditolak = 0;
                                                            $value->satuan = $satuan;
                                                            break;
                                                        case 'peralatan':
                                                            $value->diterima = round($realisasi_fisik * $koefisien, 12);
                                                            $value->ditolak = 0; //alasan di keterangan
                                                            $value->merk_type = '';
                                                            $value->satuan = $satuan;
                                                            break;
                                                        case 'quarry':
                                                            //$type = 'bahan';
                                                            $value->diterima = round($realisasi_fisik * $koefisien, 12);
                                                            $value->ditolak = 0;
                                                            $value->satuan = $satuan;
                                                            break;
                                                        case 'cuaca':
                                                            break;
                                                        case 'note':
                                                            break;
                                                        default:
                                                            $type = 'NA';
                                                            break;
                                                    }
                                                    if ($type != 'NA') {
                                                        $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['kode', '=', $kode, 'AND'], ['tanggal', '=', $tanggal, 'AND']];
                                                        //var_dump($value);
                                                        $value_encode = json_encode($value); //lalu json_decode untuk decode object.
                                                        $set = [
                                                            'kd_proyek' => $kd_proyek,
                                                            'kd_analisa' => $kd_analisa, //jika cuaca kd analisa='cuaca', catatan='note'
                                                            'kode' => $kode, //jika cuaca kd analisa='cuaca', catatan='note'
                                                            'tanggal' => $tanggal,
                                                            'type' => $type, //upah, bahan, peralatan,quarry=bahan,cuaca, catatan
                                                            'uraian' => $uraian,
                                                            'value' => $value_encode,
                                                            'keterangan' => ''
                                                        ];
                                                        $resul = $DB->select_array('laporan_harian', $kondisi, $columnName);
                                                        $jumlahArray = is_array($resul) ? count($resul) : 0;
                                                        if ($jumlahArray) {
                                                            //ubah kembali kolom value tambhkan data baru+data lama
                                                            //var_dump($resul);
                                                            $valueResul = json_decode($resul[0]->value);
                                                            //var_dump($value);
                                                            switch ($jenis_kode) {
                                                                case 'upah':
                                                                    $value->jumlah = round((float)$valueResul->jumlah + (float) $value->jumlah, 6);
                                                                    break;
                                                                case 'bahan':
                                                                    $value->diterima = round((float)$valueResul->diterima + (float) $value->diterima, 6);
                                                                    break;
                                                                case 'peralatan':
                                                                    $value->diterima = round((float)$valueResul->diterima + (float) $value->diterima, 6);
                                                                    break;
                                                                case 'quarry':
                                                                    $value->diterima = round((float)$valueResul->diterima + (float) $value->diterima, 6);
                                                                    break;
                                                                case 'cuaca':
                                                                    break;
                                                                case 'note':
                                                                    break;
                                                                default:
                                                                    break;
                                                            }
                                                            //var_dump($value);
                                                            $set['value'] = json_encode($value);
                                                            //update data
                                                            $resul = $DB->update_array('laporan_harian', $set, $kondisi);
                                                            if ($DB->count()) {
                                                                $code = 3;
                                                                $data['update'] = $DB->count();
                                                            } else {
                                                                $code = 33;
                                                            }
                                                        } else {
                                                            // inser row
                                                            $resul = $DB->insert('laporan_harian', $set);
                                                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                                                            $data['note']['add row'] = $jumlahArray;
                                                            $code = 2;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        break;
                                    case 'proyek':
                                        //tambahkan informasi umum
                                        $setData = [
                                            ['kd_proyek' => $kode, 'kode' => 'kegiatan', 'nomor_uraian' => 1, 'uraian' => 'Nama Paket', 'nilai' => 0, 'satuan' => '-', 'keterangan' => $uraian],
                                            ['kd_proyek' => $kode, 'kode' => 'lokasi', 'nomor_uraian' => 2, 'uraian' => 'Lokasi', 'nilai' => 0, 'satuan' => '-', 'keterangan' => 'Pasangkayu'],
                                            ['kd_proyek' => $kode, 'kode' => 'kab/kota/prov', 'nomor_uraian' => 3, 'uraian' => 'Propinsi / Kabupaten / Kotamadya', 'nilai' => 0, 'satuan' => '-', 'keterangan' => 'Pasangkayu'],
                                            ['kd_proyek' => $kode, 'kode' => 'KJL', 'nomor_uraian' => 4, 'uraian' => 'Kondisi jalan lama', 'nilai' => 0, 'satuan' => '-', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'Leff', 'nomor_uraian' => 5, 'uraian' => 'Panjang efektif', 'nilai' => 14.9, 'satuan' => 'km', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'LJL', 'nomor_uraian' => 6, 'uraian' => 'Lebar jalan lama', 'nilai' => 5.5, 'satuan' => 'm', 'keterangan' => '( bahu + perkerasan + bahu )', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'BJLKN', 'nomor_uraian' => 0, 'uraian' => 'Bahu', 'nilai' => 0.5, 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'PJL', 'nomor_uraian' => 0, 'uraian' => 'Perkerasan', 'nilai' => '4.5', 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'BJLKR', 'nomor_uraian' => 0, 'uraian' => 'Bahu', 'nilai' => 0.5, 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'LJR', 'nomor_uraian' => 7, 'uraian' => 'Lebar Rencana', 'nilai' => 8, 'satuan' => 'm', 'keterangan' => '( bahu + perkerasan + bahu )', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'BJRKN', 'nomor_uraian' => 0, 'uraian' => 'Bahu', 'nilai' => 1, 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'PJR', 'nomor_uraian' => 0, 'uraian' => 'Perkerasan', 'nilai' => 6, 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'BJRKN', 'nomor_uraian' => 0, 'uraian' => 'Bahu', 'nilai' => 1, 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'PJ', 'nomor_uraian' => 8, 'uraian' => 'Penampang jalan, jenis dan volume pekerjaan pokok', 'nilai' => 0, 'satuan' => '-', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'MPP', 'nomor_uraian' => 9, 'uraian' => 'Jangka waktu pelaksanaan pekerjaan', 'nilai' => 180, 'satuan' => 'hari', 'keterangan' => '-'],
                                            ['kd_proyek' => $kode, 'kode' => 'LBCLP', 'nomor_uraian' => 10, 'uraian' => 'Jarak rata-rata Base Camp ke lokasi pekerjaan', 'nilai' => 8.725, 'satuan' => 'km', 'keterangan' => '-'],
                                            ['kd_proyek' => $kode, 'kode' => 'Tk', 'nomor_uraian' => 11, 'uraian' => 'Jam kerja efektif dalam 1 hari', 'nilai' => 7.0, 'satuan' => 'jam', 'keterangan' => '-'],
                                            ['kd_proyek' => $kode, 'kode' => 'APDLL', 'nomor_uraian' => 12, 'uraian' => 'Asuransi, Pajak, dsb. untuk Peralatan', 'nilai' => 0.002, 'satuan' => '-', 'keterangan' => 'x  Harga Pokok Alat'],
                                            ['kd_proyek' => $kode, 'kode' => 'suku_bunga_i', 'nomor_uraian' => 13, 'uraian' => 'Tingkat Suku Bunga Investasi Alat', 'nilai' => 10.18, 'satuan' => '%', 'keterangan' => '-'],
                                            ['kd_proyek' => $kode, 'kode' => 'op', 'nomor_uraian' => 14, 'uraian' => 'Biaya Umum dan Keuntungan', 'nilai' => 10.00, 'satuan' => '%', 'keterangan' => '% x Biaya Langsung'], //OVERHEAD & PROFIT
                                            ['kd_proyek' => $kode, 'kode' => 'ppn', 'nomor_uraian' => 15, 'uraian' => 'Pajak Pertambahan Nilai', 'nilai' => 11.00, 'satuan' => '%', 'keterangan' => '% x Biaya Langsung'],
                                            ['kd_proyek' => $kode, 'kode' => 'nJbt', 'nomor_uraian' => 16, 'uraian' => 'Jumlah Jembatan', 'nilai' => 0, 'satuan' => 'Buah', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'lJbt', 'nomor_uraian' => 17, 'uraian' => 'Total Bentang Jembatan', 'nilai' => 0, 'satuan' => 'm', 'keterangan' => '-', 'type' => 'custom'],
                                            ['kd_proyek' => $kode, 'kode' => 'RMP', 'nomor_uraian' => 18, 'uraian' => 'RINGKASAN METODE PELAKSANAAN', 'nilai' => '0', 'satuan' => '-', 'keterangan' => '-']
                                        ];
                                        foreach ($setData as $r => $getData) {
                                            $DB->insert('informasi_umum', $getData);
                                        }
                                        // jika menjadi proyek aktif update di tabel user tahun anggaran aktif
                                        //$DB->insert('informasi_umum', $set);
                                        //inset dipeta lokasi default [-1.18327, 119.36295]
                                        $set = ['kd_proyek' => $kode, 'sta_pengenal_Y' => -1.18327, 'sta_pengenal_X' => 119.36295, 'polyline' => '{}', 'polygon' => '{}', 'marker' => '{}', 'circle' => '{}', 'keterangan' => $uraian];
                                        $DB->insert('lokasi_proyek', $set);
                                        break;
                                    case 'rab':
                                        //tambahkan baris di schedule jika berhasil tambah data di rab
                                        if ($DB->lastInsertId()) {
                                            $lastInsertId = $DB->lastInsertId();
                                            $warna = 'blue';
                                            //elemen row
                                            $desimal = ($Fungsi->countDecimals($harga_satuan) < 2) ? 2 : $Fungsi->countDecimals($harga_satuan);
                                            $harga_satuan = number_format($harga_satuan, $desimal, ',', '.');
                                            $desimal = ($Fungsi->countDecimals($jumlah_harga) < 2) ? 2 : $Fungsi->countDecimals($jumlah_harga);
                                            $jumlah_harga = number_format($jumlah_harga, $desimal, ',', '.');
                                            //var_dump($lastInsertId);
                                            $data['tbody'] = '<tr id_row="' . $lastInsertId . '"><td>' . $kd_analisa . '</td><td>' . $uraian . '</td><td klm="volume"><div contenteditable rms onkeypress="return rumus(event);">' . $volume . '</div></td><td klm="satuan"><div contenteditable>' . $satuan . '</div></td><td klm="harga_satuan">' . $harga_satuan . '</td><td klm="jumlah_harga">' . $jumlah_harga . '</td><td klm="keterangan"><div contenteditable>' . $keterangan . '</div></td><td><div class="ui icon buttons"><button class="ui ' . $warna . ' mini button" name="flyout" name="flyout" jns="' . $jenis . '" tbl="edit" id_row="' . $lastInsertId . '"><i class="folder open outline icon"></i></button><button class="ui red mini button" name="del_row" jns="' . $jenis . '" tbl="del_row" id_row="' . $lastInsertId . '"><i class="trash alternate outline icon"></i></button><button class="ui mini button up_row"><i class="angle double up icon"></i></button></div></td></tr>';
                                            $set2 = ['kd_proyek' => $kd_proyek, 'kd_analisa' => $kd_analisa, 'id_rab' => $DB->lastInsertId(), 'uraian' => $uraian, 'type' => 'analisa', 'no_sortir' => $max_no_sortir];
                                            $DB->insert('schedule_table', $set2);
                                            //tambahkan
                                        }
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            }
                            break;
                        default:
                            # code...
                            break;
                    }
                } else {
                    $code = 29;
                    $pesan = $validate->getError();
                    $data['error_validate'][] = $pesan;
                    $keterangan = '<ol class="ui horizontal ordered suffixed list">';
                    foreach ($pesan as $key => $value) {
                        $keterangan .= '<li class="item">' . $value . '</li>';
                    }
                    $keterangan .= '</ol>';
                    $hasilServer = [$code => 'Validasi Kembali :<br>' . $keterangan];
                }
            } else {
                $pesan = 'tidak didefinisikan';
                $code = 39;
            }
        }
        $item = array('code' => $code, 'message' => $hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
    }
}
