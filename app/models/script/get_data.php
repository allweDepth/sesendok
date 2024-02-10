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
        $kd_opd = $_SESSION["user"]["kd_organisasi"];
        //var_dump($keyEncrypt);
        //var_dump(sys_get_temp_dir());lokasi tempoerer
        $DB = DB::getInstance();

        $message_tambah = '';
        $sukses = false;
        $code = 39;
        $pesan = 'posting kosong';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        $dataJson = array();
        //ambil row user
        $rowUsername = $DB->getWhereOnce('user_ahsp', ['username', '=', $username]);
        if ($rowUsername != false) {
            $tahun = (int) $rowUsername->tahun;
            $kd_wilayah = $rowUsername->kd_wilayah;
            $kd_opd = $rowUsername->kd_organisasi;
            $id_user = $rowUsername->id;
        } else {
            $id_user = 0;
            $code = 407;
        }
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
                    $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                    $jumlah_kolom = $Fungsi->tabel_pakai($tbl)['jumlah_kolom'];
                    $kolom = '*';
                    $sukses = true;
                    $err = 0;
                    $dataJson = [];
                    $kodePosting = '';
                    switch ($jenis) {
                        case 'get_pengaturan':
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
                        case 'get_rows':
                            $kodePosting = 'get_data';
                            break;

                            break;
                        default:
                            #code...
                            break;
                    };
                    switch ($tbl) {
                        case 'tujuan_renstra':
                            $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                            if ($rowOrganisasi) {
                                $tahun_renstra = $rowOrganisasi->tahun_renstra;
                                if ($tahun_renstra > 2000) {
                                    $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (nama_perusahaan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR direktur LIKE CONCAT('%',?,'%') OR data_lain LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, $cari, $cari, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY nama_perusahaan, id ASC";
                                    $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                                    $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                                    $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                                    $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                                } else {
                                    $message_tambah = ' (atur tahun renstra OPD)';
                                    $code = 70;
                                    $kodePosting = '';
                                }
                            } else {
                                $message_tambah = ' (atur organisasi OPD)';
                                $kodePosting = '';
                                $code = 70;
                            }

                            break;
                        case 'sasaran':
                            $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (nama_perusahaan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR direktur LIKE CONCAT('%',?,'%') OR data_lain LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                            $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, $cari, $cari, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY nama_perusahaan, id ASC";
                            $where1 = "kd_wilayah = ? AND disable <= ?";
                            $data_where1 =  [$kd_wilayah, 0];

                            break;
                        case 'rekanan':
                            $like = "kd_wilayah = ? AND nama_perusahaan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR direktur LIKE CONCAT('%',?,'%') OR data_lain LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%')";
                            $data_like = [$kd_wilayah, $cari, $cari, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY nama_perusahaan, id ASC";
                            $where1 = "kd_wilayah = ? AND disable <= ?";
                            $data_where1 =  [$kd_wilayah, 0];

                            break;
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
                            $like = "kd_wilayah = ? AND disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nama_kepala LIKE CONCAT('%',?,'%'))";
                            $data_like = [$kd_wilayah, 0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "kd_wilayah = ? AND disable <= ?";
                            $data_where1 =  [$kd_wilayah, 0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 7;
                            break;
                        case 'peraturan':
                            $like = "kd_wilayah = ? AND disable <= ? AND(judul LIKE CONCAT('%',?,'%') OR bentuk_singkat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nomor LIKE CONCAT('%',?,'%'))";
                            $data_like = [$kd_wilayah, 0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY tgl_pengundangan ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "kd_wilayah = ? AND disable <= ?";
                            $data_where1 = [$kd_wilayah, 0];
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
                    //================================================
                    //==========JENIS POST DATA/INSERT DATA===========
                    //================================================
                    switch ($kodePosting) {
                        case 'getAllValJson':
                            //pilih kolom yang diambil
                            $DB->select('id, judul, nomor, tgl_pengundangan, keterangan');
                            $peraturan = $DB->getWhereArray($tabel_pakai, [['disable', '=', 0], ['status', '=', 'umum', 'AND']]);
                            //var_dump(count($peraturan));
                            $jumlahArray = is_array($peraturan) ? count($peraturan) : 0;
                            //@audit
                            $dataJson['results'] = [];
                            if ($jumlahArray > 0) {
                                foreach ($peraturan as $row) {
                                    $dataJson['results'][] = ['name' => $row->judul, 'value' => $row->id, 'description' => $row->nomor];
                                }
                            }
                            break;
                        case 'get_data':
                        case 'get_row': //  ambil data 1 baris 
                            $resul = $DB->getQuery("SELECT $kolom FROM $tabel_pakai WHERE $where_row", $data_where_row);
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
                                                $data['satuan'] = $DB->getQuery("SELECT value, item FROM satuan_neo WHERE value LIKE CONCAT('%',?,'%')", [$satuan]);
                                                break;
                                            case 'value1':
                                                break;
                                            case 'value1':
                                                break;
                                            default:
                                                break;
                                        };
                                        break;
                                    case 'get_rows':
                                        switch ($tbl) {
                                            case 'tujuan_renstra':
                                                // buatkan json dropdown tujuan renstra
                                                foreach ($resul as $row) {
                                                }
                                                break;
                                            case 'value1':
                                                #code...
                                                break;
                                            default:
                                                #code...
                                                break;
                                        };

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
        } else {
            $code = 407;
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
                        $item = array('code' => $code, 'message' => hasilServer[$code] . $message_tambah);
                        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
                        break;
                }
                break;
            default:
                $item = array('code' => $code, 'message' => hasilServer[$code] . $message_tambah);
                $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
                break;
        }
        //var_dump($json);
        return json_encode($json, JSON_HEX_APOS);
    }
}
