<?php
class get_data
{
    public function get_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $username = $_SESSION["user"]["username"];
        $id_user = $_SESSION["user"]["id"];
        $keyEncrypt = $_SESSION["user"]["key_encrypt"];
        //var_dump($keyEncrypt);
        //var_dump(sys_get_temp_dir());lokasi tempoerer
        $DB = DB::getInstance();

        $sukses = false;
        $code = 39;
        $pesan = 'posting kosong';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        $dataJson = array();
        if (!empty($_POST) && $id_user > 0) {
            if (isset($_POST['jenis']) && isset($_POST['tbl'])) {

                $validate = new Validate($_POST);
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
                $cari = '';
                if (!empty($_POST['cari'])) {
                    $cari = $validate->setRules('cari', 'Cari', [
                        'sanitize' => 'string',
                        'max_char' => 100
                    ]);
                }

                $halaman = 1;
                if (!empty($_POST['halaman'])) {
                    $halaman = $validate->setRules('halaman', 'Halaman aktif ', [
                        'numeric' => true
                    ]);
                }
                // jumlah baris
                $limit = 'all';
                if (!empty($_POST['rows'])) {
                    if ($_POST['rows'] != 'all') {
                        $limit = $validate->setRules('rows', 'Jumlah Halaman', [
                            'numeric' => true
                        ]);
                        // var_dump($limit);
                    };
                }
                //================
                //PROSES VALIDASI
                //================
                switch ($jenis) {
                    case 'get_data':
                        $text = $validate->setRules('text', 'text', [
                            'required' => true,
                            'sanitize' => 'string',
                            'min_char' => 1,
                            'max_char' => 255
                        ]);
                        break;
                    case 'edit':
                        $id_row = $validate->setRules('id_row', 'id_row', [
                            'required' => true,
                            'sanitize' => 'string',
                            'min_char' => 1,
                            'max_char' => 100
                        ]);
                        break;
                    case 'get_tbl':

                        break;
                    case 'get_rows':

                        break;
                    case 'get_search':

                        break;
                    case 'xxxx':
                        $posisi = $validate->setRules('posisi', 'posisi', [
                            'sanitize' => 'string',
                            'required' => true,
                            'in_array' => ['top', 'bottom']
                        ]);
                        break;
                    default:
                        # code...
                        break;
                }

                $jumlah_kolom = 0;
                //FINISH PROSES VALIDASI
                $Fungsi = new MasterFungsi();
                if ($validate->passed()) {
                    $rowUsername = $DB->getWhereOnce('user_ahsp', ['username', '=', $username]);
                    $tahun = (int) $rowUsername->tahun;
                    $kd_wilayah = $rowUsername->kd_wilayah;
                    $kd_skpd = $rowUsername->kd_organisasi;
                    $rowTahunAktif = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
                    //var_dump($rowTahunAktif);
                    if ($rowTahunAktif) {
                        $id_aturan_anggaran = $rowTahunAktif->aturan_anggaran;
                        $id_aturan_pengadaan = $rowTahunAktif->aturan_pengadaan;
                        $id_aturan_akun = $rowTahunAktif->aturan_akun;
                        $id_aturan_sub_kegiatan = $rowTahunAktif->aturan_sub_kegiatan;
                        $id_aturan_asb = $rowTahunAktif->aturan_asb;
                        $id_aturan_sbu = $rowTahunAktif->aturan_sbu;
                        $id_aturan_ssh = $rowTahunAktif->aturan_ssh;
                        $id_aturan_hspk = $rowTahunAktif->aturan_hspk;
                        $id_aturan_sumber_dana = $rowTahunAktif->aturan_sumber_dana;
                        $tahun_pengaturan = $rowTahunAktif->tahun;
                    } else {
                        $id_peraturan = 0;
                    }
                    //tabel pakai
                    switch ($tbl) {
                        case 'peraturan':
                            $tabel_pakai = 'peraturan_neo';
                            $jumlah_kolom = 4;
                            break;
                        case "akun":
                        case "akun_belanja":
                            $tabel_pakai = 'akun_neo';
                            $jumlah_kolom = 4;
                            break;
                        case "aset":
                            $tabel_pakai = 'aset_neo';
                            $jumlah_kolom = 4;
                            break;
                        case 'dpa':
                            $tabel_pakai = 'dpa_neo';
                            break;
                        case 'mapping_aset':
                        case 'mapping':
                            $tabel_pakai = 'mapping_aset_akun';
                            $jumlah_kolom = 10;
                            break;
                        case 'wilayah':
                            $tabel_pakai = 'wilayah_neo';
                            $jumlah_kolom = 10;
                            break;
                        case 'satuan':
                            $tabel_pakai = 'satuan_neo';
                            $jumlah_kolom = 5;
                            break;
                        case 'organisasi':
                            $tabel_pakai = 'organisasi_neo';
                            break;
                        case "pengaturan":
                            $tabel_pakai = 'pengaturan_neo';
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
                        case 'hspk':
                            $tabel_pakai = 'hspk_neo';
                            break;
                        case 'sbu':
                            $tabel_pakai = 'sbu_neo';
                            break;
                        case 'asb':
                            $tabel_pakai = 'asb_neo';
                            break;
                        case 'ssh':
                            $tabel_pakai = 'ssh_neo';
                            break;
                        case 'bidang_urusan':
                        case 'prog':
                        case 'keg':
                        case 'sub_keg':
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
                    $kolom = '*';
                    $sukses = true;
                    $err = 0;
                    $dataJson = [];
                    $kodePosting = '';
                    switch ($jenis) {
                        case 'get_pengaturan':
                            //$kodePosting = 'get_tbl';
                            $rowUsername = $DB->getWhereOnce('user_ahsp', ['username', '=', $username]);
                            //var_dump($rowUsername);

                            $tahun = (int) $rowUsername->tahun;
                            $rowTahunAktif = $DB->getWhereOnce($tabel_pakai, ['tahun', '=', $tahun]);

                            if ($rowTahunAktif) {
                                $rowTahun = $rowTahunAktif;
                            } else {
                                $rowTahun = "pengaturan tahun $tahun tidak ditemukan";
                            }
                            $data['tahun'] = $tahun;
                            $data['row_tahun'] = $rowTahun;
                            //pilih kolom yang diambil
                            $DB->select('id, judul, nomor, tgl_pengundangan, keterangan');
                            $peraturan = $DB->getWhereArray('peraturan_neo', [['disable', '=', 0], ['status', '=', 'umum', 'AND']]);
                            //var_dump(count($peraturan));
                            $jumlahArray = is_array($peraturan) ? count($peraturan) : 0;
                            //@audit
                            $dataJson['results'] = [];
                            if ($jumlahArray > 0) {
                                foreach ($peraturan as $row) {
                                    $dataJson['results'][] = ['name' => $row->judul, 'value' => $row->id, 'description' => $row->nomor];
                                }
                            }
                            //var_dump($peraturan);
                            $data['peraturan'] = $dataJson['results'];
                            break;
                        case 'get_tbl':
                            $kodePosting = 'get_tbl';
                            break;
                        case 'get_data':
                            $where1 = "nomor = ?";
                            $data_where1 =  [$text];
                            break;
                        case 'edit':
                            $kodePosting = 'get_data';
                            $where_row = "id = ?";
                            $data_where_row =  [$id_row];
                            

                            break;
                        default:
                            #code...
                            break;
                    };
                    switch ($tbl) {
                        case 'satuan':
                            $like = "disable <= ? AND(value LIKE CONCAT('%',?,'%') OR item LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR sebutan_lain LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY value ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            break;
                        case 'wilayah':
                            $like = "disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR status LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            break;
                        case 'organisasi':
                            $like = "disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nama_kepala LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 7;
                            break;
                        case 'peraturan':
                            $like = "disable <= ? AND(judul LIKE CONCAT('%',?,'%') OR bentuk_singkat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nomor LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY tgl_pengundangan ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 6;
                            break;
                        case 'sumber_dana':
                            $like = "disable <= ? AND(uraian LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 9;
                            break;
                        case "aset":
                        case 'akun_belanja':
                            $like = "disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 9;
                            break;
                        case 'bidang_urusan':
                            $like = "disable <= ? AND(bidang LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%')) AND prog < ?";
                            $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, 0];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ? AND prog <= ?";
                            $data_where1 =  [0, 0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 11;
                            break;
                        case 'prog':
                            $like = "disable <= ? AND(bidang LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%')) AND keg < ?";
                            $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, 0];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ? AND keg <= ?";
                            $data_where1 =  [0, 0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 11;
                            break;
                        case 'keg':
                            $like = "disable <= ? AND(bidang LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%')) AND sub_keg < ?";
                            $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, 0];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ? AND sub_keg <= ?";
                            $data_where1 =  [0, 0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 11;
                            break;
                        case 'sub_keg':
                            $like = "disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 11;
                            break;
                        case 'mapping':
                            $like = "disable <= ? AND(kode_aset LIKE CONCAT('%',?,'%') OR uraian_aset LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kode_akun LIKE CONCAT('%',?,'%') OR uraian_akun LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kode_aset ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 11;
                            break;
                        case 'hspk':
                        case 'sbu':
                        case 'ssh':
                        case 'asb':
                            $like = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND(kd_aset LIKE CONCAT('%',?,'%') OR uraian_kel LIKE CONCAT('%',?,'%') OR uraian_barang LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR harga_satuan LIKE CONCAT('%',?,'%') OR merek LIKE CONCAT('%',?,'%') OR kd_rek_akun_asli LIKE CONCAT('%',?,'%'))";
                            $data_like = [$kd_wilayah, $tahun, 0, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kd_aset ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "kd_wilayah = ? AND tahun = ? AND disable <= ?";
                            $data_where1 =  [$kd_wilayah, $tahun, 0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 7;
                            break;
                        case 'dpa':
                            $like = "kd_proyek = ? AND kd_analisa = nomor AND(uraian LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                            $data_like = [$kd_proyek, $cari, $cari, $cari];
                            $order = "ORDER BY id ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "username != ?";
                            $data_where1 =  ['gila'];
                            $jumlah_kolom = 4;
                            break;
                        default:
                            $kodePosting = '';
                            $err = 6;
                    }

                    // $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);

                    //$tahun_anggaran = 0;
                    // $jumlahArray = is_array($data_kd_proyek) ? count($data_kd_proyek) : 0;

                    //================================================
                    //==========JENIS POST DATA/INSERT DATA===========
                    //================================================
                    switch ($kodePosting) {
                        case 'get_data':
                        case 'get_row': //  ambil data 1 baris 
                            $resul = $DB->getQuery("SELECT $kolom FROM $tabel_pakai WHERE $where_row", $data_where_row);
                            //var_dump($resul);
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            if ($jumlahArray > 0) {
                                $code = 202; //202
                                $data['users'] = $resul[0];
                                switch ($jenis) {
                                    case 'edit':
                                        switch ($tbl) {
                                            case 'asb':
                                            case 'sbu':
                                            case 'hspk':
                                            case 'ssh':
                                                // ambil data kd_aset dari tabel aset dan satuan list ut dropdown
                                                $kd_aset = $resul[0]->kd_aset;
                                                $data['aset'] = $DB->getQuery("SELECT kode, uraian FROM aset_neo WHERE kode LIKE CONCAT('%',?,'%')", [$kd_aset]);
                                                $satuan = $resul[0]->satuan;
                                                $data['satuan'] = $DB->getQuery("SELECT value, item FROM satuan_neo WHERE value LIKE CONCAT('%',?,'%')", [$satuan ]);
                                                break;
                                            case 'value1':
                                                break;
                                            default:
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
                                $code = 41; //202
                            }
                            break;
                        case 'get_row_json': // ambil data > 1 baris 
                            if ($limit !== "all") {
                                if ($cari != '') {
                                    //var_dump($data_like);
                                    //var_dump("SELECT * FROM $tabel_pakai WHERE ($like)");
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like)", $data_like);
                                } else {
                                    //var_dump("SELECT * FROM $tabel_pakai WHERE $where1");
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1", $data_where1);
                                }
                                $jmldata = sizeof($get_data); //$get_data->fetchColumn();
                                $jmlhalaman = ceil($jmldata / $limit);
                            } else {
                                $jmlhalaman = 1;
                            }
                            if ($halaman > $jmlhalaman) {
                                $halaman = $jmlhalaman;
                            }
                            if (empty($halaman)) {
                                $posisi = 0;
                                $halaman = 1;
                            } else {
                                $posisi = ((int)$halaman - 1) * (int)$limit;
                            }
                            if ($limit != "all") {
                                if ($cari != '') {
                                    //var_dump("SELECT * FROM $tabel_pakai WHERE ($like) $order ");
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order ", $data_like, [$posisi, $limit]);
                                } else {
                                    //var_dump("SELECT * FROM $tabel_pakai WHERE $where1 $order ");
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1, [$posisi, $limit]);
                                }
                            } else {
                                if ($cari != '') {
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order", $data_like);
                                } else {
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
                                }
                            }
                            break;
                        case 'get_tbl':
                            //get data
                            // var_dump($limit);
                            if ($limit !== "all") {
                                if ($cari != '') {
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like)", $data_like);
                                } else {
                                    if (strlen($where1) > 3) {
                                        $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1", $data_where1);
                                    } else {
                                        $get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai");
                                    }
                                }
                                $jmldata = sizeof($get_data); //$get_data->fetchColumn();
                                $jmlhalaman = ceil($jmldata / $limit);
                            } else {
                                $jmlhalaman = 1;
                            }
                            if ($halaman > $jmlhalaman) {
                                $halaman = $jmlhalaman;
                            }
                            if (empty($halaman)) {
                                $posisi = 0;
                                $halaman = 1;
                            } else {
                                $posisi = ((int)$halaman - 1) * (int)$limit;
                            }
                            if ($limit != "all") {
                                if ($cari != '') {
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order ", $data_like, [$posisi, $limit]);
                                } else {
                                    if (strlen($where1) > 3) {
                                        $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1, [$posisi, $limit]);
                                    } else {
                                        $get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai $order LIMIT $posisi, $limit");
                                    }
                                }
                            } else {
                                if ($cari != '') {
                                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order", $data_like);
                                } else {
                                    if (strlen($where1) > 3) {
                                        $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
                                    } else {
                                        $get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai $order ");
                                    }
                                }
                            }
                            $jumlah_rows = is_array($get_data) ? count($get_data) : 0;
                            if ($jumlah_rows <= 0) {
                                $code = 404;
                            } else {
                                $code = 202; //202
                            }
                            $dataTabel = $Fungsi->getTabel($tbl, $tabel_pakai, $get_data, $jmlhalaman, $halaman, $jumlah_kolom, $type_user);
                            $data = array_merge($dataTabel, $data);
                            break;
                        case 'get_Dropdown':
                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
                            $dataTabel = $Fungsi->getDropdownItem($get_data, $tabel_pakai, $nama_dropdown, $jenisdropdown, $jumlah_kolom_dropdown, $type_user);
                            $data['dropdown'] = $dataTabel;
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
                }
            } else {
                $pesan = 'tidak didefinisikan';
                $code = 39;
            }
        }
        // cara menampilkan json
        switch ($jenis) {
            case 'user':
            case 'monev':
                switch ($tbl) {
                    case 'get_users_list':
                    case 'edit':
                    case 'input':
                        $json = array('success' => $sukses,  'results' => $dataJson['results'],  'data' => $data);
                        break;
                    default:
                        $item = array('code' => $code, 'message' => hasilServer[$code]);
                        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
                        break;
                }
                break;
            default:
                $item = array('code' => $code, 'message' => hasilServer[$code]);
                $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
                break;
        }
        //var_dump($json);
        return json_encode($json, JSON_HEX_APOS);
        //var_dump($json);
        // if (json_encode($json, JSON_HEX_APOS)) {
        //     return json_encode($json, JSON_HEX_APOS);
        // } else {
        //     foreach ($data as $key => $value) {
        //         $data[$key] = $Fungsi->safe_json_encode($value);
        //     }
        //     $item = array('code' => $code, 'message' => hasilServer[$code]);
        //     $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        //     return json_encode($json); //json_last_error();
        // }
    }
}
