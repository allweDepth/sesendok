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
        $hasilServer = hasilServer[$code];
        $pesan = 'posting kosong';
        $tambahan_pesan = '';
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
                                $type_dok = $validate->setRules('type_dok', 'type', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['peraturan_undang_undang_pusat', 'peraturan_menteri_lembaga', 'peraturan_daerah', 'pengumuman', 'artikel', 'lain']
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
                                $bentuk = $validate->setRules('bentuk', 'bentuk', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $bentuk_singkat = $validate->setRules('bentuk_singkat', 'bentuk_singkat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $t4_penetapan = $validate->setRules('t4_penetapan', 'tempat', [
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
                                $tgl_pengundangan = $validate->setRules('tgl_pengundangan', 'tanggal pengundangan', [
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
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
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

                //FINISH PROSES VALIDASI
                $kodePosting = '';

                if ($validate->passed()) {

                    //tabel pakai
                    switch ($tbl) {
                        case 'peraturan':
                            $tabel_pakai = 'peraturan_neo';
                            $jumlah_kolom = 4;
                            break;
                        case "akun":
                            $tabel_pakai = 'akun_neo';
                            $jumlah_kolom = 4;
                            break;
                        case 'bidang_urusan':
                            $tabel_pakai = 'bidang_urusan_neo';
                            break;
                        case 'dpa':
                            $tabel_pakai = 'dpa_neo';
                            break;
                        case 'hspk':
                            $tabel_pakai = 'hspk_neo';
                            break;
                        case 'kegiatan':
                            $tabel_pakai = 'kegiatan_neo';
                            break;
                        case 'mapping_aset':
                            $tabel_pakai = 'mapping_aset_akun';
                            break;
                        case 'organisasi':
                            $tabel_pakai = 'organisasi_neo';
                            break;
                        case "peraturan":
                            $tabel_pakai = 'peraturan_neo';
                            break;
                        case 'program':
                            $tabel_pakai = 'program_neo';
                            break;
                        case 'satuan':
                            $tabel_pakai = 'satuan_neo';
                            break;
                        case 'sbu':
                            $tabel_pakai = 'sbu_neo';
                            break;
                        case 'ssh':
                            $tabel_pakai = 'ssh_neo';
                            break;
                        case 'sub_kegiatan':
                            $tabel_pakai = 'sub_kegiatan_neo';
                            break;
                        case 'sumber_dana':
                            $tabel_pakai = 'sumber_dana_neo';
                            break;
                        case 'user':
                            $tabel_pakai = 'user_ahsp';
                            break;
                        case 'inbox':
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

                    //start buat property
                    switch ($tbl) {
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
                            switch ($jenis) {
                                case 'edit':
                                    $kondisi = [['id', '=', $id_row]];
                                    $kodePosting = 'update_row';
                                case 'add':
                                    if ($jenis == 'add') {
                                        $kondisi = [['judul', '=', $judul], ['nomor', '=', $nomor, 'AND']];
                                        $kodePosting = 'cek_insert';
                                    }
                                    $set = [
                                        'type_dok' => $type_dok,
                                        'judul' => $judul,
                                        'nomor' => $nomor,
                                        'bentuk' => $bentuk,
                                        'bentuk_singkat' => $bentuk_singkat,
                                        't4_penetapan' => $t4_penetapan,
                                        'tgl_penetapan' => $tgl_penetapan,
                                        'tgl_pengundangan' => $tgl_pengundangan,
                                        'disable' => $disable,
                                        'keterangan' => $keterangan,
                                        'status' => $status,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                    //var_dump($set);
                                    //pengolahan file
                                    if ($_FILES['file']) {

                                        $file = $Fungsi->importFile($tbl, '');
                                        //var_dump($file);
                                        if ($file['result'] == 'ok') {
                                            $set['file'] = $file['file'];
                                        }else{
                                            $tambahan_pesan = "(".$file['file'].")" ;
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
                            } else {
                                $code = 33;
                            }
                            break;
                        case 'cek_insert': //cek data klo tidak ada teruskan insert
                            $ListRow = $DB->select_array($tabel_pakai, $kondisi, $columnName);
                            var_dump($ListRow);

                            $jumlahArray = is_array($ListRow) ? count($ListRow) : 0;
                            if ($jumlahArray) {
                                //update row
                                $hasilUpdate = $DB->update_array($tabel_pakai, $set, $kondisi);
                                //var_dump($hasilUpdate);
                                if ($DB->count()) {
                                    $code = 3;
                                    $data['update'] = $DB->count(); //$DB->count();
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
                            break;
                        default:
                            # code...
                            break;
                    }
                    $hasilServer = hasilServer[$code];
                } else {
                    $code = 29;
                    $pesan = $validate->getError();
                    //var_dump($pesan);
                    $data['error_validate'][] = $pesan;
                    $keterangan = '<ol class="ui horizontal ordered suffixed list">';
                    foreach ($pesan as $key => $value) {
                        $keterangan .= '<li class="item">' . $value . '</li>';
                    }
                    $keterangan .= '</ol>';
                    $tambahan_pesan = [$code => 'Validasi Kembali :<br>' . $keterangan];
                }
            } else {
                $pesan = 'tidak didefinisikan';
                $code = 39;
            }
        }
        $item = array('code' => $code, 'message' => hasilServer[$code]." $tambahan_pesan" );
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        // return json_encode($json);
        return json_encode($json, JSON_HEX_APOS);
    }
}
