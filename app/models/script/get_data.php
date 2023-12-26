<?php
class get_data
{
    public function get_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $id_user = $_SESSION["user"]["id"];
        $username = $_SESSION["user"]["username"];
        $tahun_anggaran = $_SESSION["user"]["thn_aktif_anggaran"];
        //var_dump($_SESSION);
        $keyEncrypt = $_SESSION["user"]["key_encrypt"];
        //var_dump($_SESSION);
        //var_dump($keyEncrypt);
        $hasilServer = [
            1 => 'berhasil run',
            2 => 'berhasil tambah data',
            3 => 'berhasil update',
            4 => 'berhasil delete',
            5 => 'berhasil select',
            6 => 'berhasil insert/data ganda(berhasil update)',
            7 => 'berhasil impor file',
            8 => 'berhasil import file dengan catatan',
            9 => 'data sudah ada',
            10 => 'berhasil validasi',
            11 => 'berhasil data posting',
            12 => 'berhasil data jenis tabel',
            29 => 'gagal validasi',
            30 => 'gagal tambah data/data ganda',
            31 => 'gagal tambah data/berhasil update',
            32 => 'gagal tambah data',
            33 => 'gagal update',
            34 => 'gagal update/berhasil tambah data',
            35 => 'gagal delete',
            36 => 'gagal select/tidak ditemukan',
            37 => 'gagal tambah data/data ganda',
            38 => 'gagal import file',
            39 => 'gagal menentukan jenis data',
            40 => 'proses anda tidak dikenali',
            41 => 'data tidak ditemukan',
            45 => 'tabel yang digunakan tidak ditemukan',
            46 => 'gagal run',
            47 => 'data telah ada',
            48 => 'data telah ada dan telah diupdate',
            49 => 'data telah diproses kembali',
            50 => 'kode bisa digunakan',
            56 => 'belum ada dokumen pekerjaan yang aktif',
            100 => 'Continue', #server belum menolak header permintaan dan meminta klien untuk mengirim body (isi/konten) pesan
            //File
            701 => 'File Tidak Lengkap',
            702 => 'file yang ada terlalu besar',
            703 => 'type file tidak sesuai',
            704 => 'Gagal Upload',
            401  => 'Unauthorized', #pengunjung website tidak memiliki hak akses untuk file / folder yang diproteksi oleh password (kata kunci).
            403  => 'Forbidden', #pengunjung sama sekali tidak dapat mengakses ke folder tujuan. Angka 403 muncul disebabkan oleh kesalahan pengaturan hak akses pada folder.
            202  => 'Accepted',
            404  => 'Not Found', #bahwa file / folder yang diminta, tidak ditemukan didalam database pada suatu website.
            406  => 'Not Acceptable', #pernyataan bahwa permintaan dari browser tidak dapat dipenuhi oleh server.
            500  => ' Internal Server Error', #menyatakan bahwa ada kesalahan konfigurasi pada akun hosting.
            509  => 'Bandwidth Limit Exceeded', #penggunaan bandwidth pada account hosting sudah melebihi quota yang ditetapkan untuk akun hosting Anda
            //Bahasa Gaul
            530  => 'I Miss You', #I Miss You dalam bahasa Mandarin adalah Wo Xiang Ni
            831  => 'I Love You', #Memiliki jumlah 8 huruf dalam kalimat "I Love You",Kemudian ada 3 jumlah total kata dalam frasa "I Love You",Dan 1 memiliki satu makna, yaitu "Aku Cinta Kamu"
            24434   => 'Sudahkah anda sholat', #diambil dari jumlah rakaat di setiap Sholat lima waktu atau shalat fardhu
            1432  => 'I Love You Too', #1 artinya I, 4 artinya Love, 3 artinya You, 2 artinya Too. bisa diberikan untuk pasangan kekasih.
            224  => 'I Love You Too' #Artinya adalah Today, Tomorrow dan Forever.Angka 2 artinya two yang artinya twoday,today,
        ];
        //print_r('id user'.$id_user);
        //var_dump($_POST);
        //var_dump($_GET);
        //var_dump(sys_get_temp_dir());lokasi tempoerer
        $DB = DB::getInstance();
        //$data_user = $DB->getQuery( 'SELECT * FROM user_ahsp WHERE id = ?', [ $id_user ] );
        $tahun = $_SESSION["user"]["thn_aktif_anggaran"];
        $kd_proyek = '';
        $kd_proyek_aktif = $_SESSION["user"]["kd_proyek_aktif"];
        $status = 'user';
        if ($type_user === 'admin') {
            $status = 'admin';
        }
        $sukses = false;
        $code = 39;
        $pesan = 'posting kosong';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        $dataJson = array();
        $kolom = '*';
        if (!empty($_POST) && $id_user > 0) {
            if (isset($_POST['jenis'])) {
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
                switch ($tbl) {
                    case 'edit':
                        switch ($jenis) {
                            case 'analisa_alat_custom':
                            case 'analisa_sda':
                            case 'analisa_ck':
                            case 'analisa_bm':
                            case 'analisa_quarry':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'sanitize' => 'string',
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                break;
                            default:
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                switch ($jenis) {
                    case 'inbox':
                    case 'outbox':
                    case 'wall':
                        switch ($tbl) {
                            case 'get':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                if ($id_row != 'nol') {
                                    $id_row = $validate->setRules('id_row', 'id', [
                                        'required' => true,
                                        'numeric' => true,
                                        'min_char' => 1
                                    ]);
                                } else {
                                    $id_row = 0;
                                }
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
                        break;
                    case 'peraturan':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'rekanan':
                        switch ($tbl) {
                            case 'cek_kode':
                                $text = $validate->setRules('kode', 'Nama Rekanan/Perusahaan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                break;
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'sbu':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'lap-harian':
                        switch ($tbl) {
                            case 'get_list':
                                $tanggal = $validate->setRules('tanggal', 'Tanggal Laporan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', //'/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/',
                                    'min_char' => 8
                                ]);

                                break;
                            case 'x':
                                break;
                        }
                        break;
                    case 'proyek':
                        switch ($tbl) {
                            case 'cek_kode':
                                $text = $validate->setRules('text', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                break;
                            case 'get_tabel_proyek':
                                break;
                            case 'edit':
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'informasi_umum':
                    case 'satuan':
                        switch ($tbl) {
                            case 'cek_kode':
                                $text = $validate->setRules('text', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 250
                                ]);
                                break;
                            case 'edit':
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;




                    case 'harga_satuan':
                        switch ($tbl) {
                            case 'list':
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'analisa_alat':
                        switch ($tbl) {
                            case 'list':
                                break;
                            case 'edit':
                                break;
                            default:
                                # code...
                                break;
                        }
                    case 'analisa_bm':
                    case 'analisa_ck':
                    case 'analisa_sda':
                    case 'analisa_alat_custom':
                    case 'analisa_quarry':
                        switch ($tbl) {
                            case 'cek_kode':
                                $kolomCekUnique = 'kd_analisa';
                                switch ($jenis) {
                                    case 'analisa_alat_custom':
                                        $tabel_pakai =  'analisa_alat_custom';
                                        break;
                                    case 'analisa_alat':
                                        $tabel_pakai =  'analisa_alat';
                                        $kolomCekUnique = 'kode';
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
                                    default:
                                        # code...
                                        break;
                                }
                                $kode = $validate->setRules('kode', 'kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100,
                                    'uniqueArray' => [$tabel_pakai, $kolomCekUnique, [['kd_proyek', '=', $kd_proyek_aktif]]]
                                ]);
                                break;
                            case 'import':
                                $nama_dropdown = $validate->setRules('namadropdown', 'Atribut nama Dropdown', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'monev':
                        switch ($tbl) {
                            case 'lap_range':
                                $tanggal_awal = $validate->setRules('tanggal_awal', 'tanggal_awal', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', //'/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/',
                                    'min_char' => 8
                                ]);
                                $tanggal_akhir = $validate->setRules('tanggal_akhir', 'tanggal_akhir', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', //'/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/',
                                    'min_char' => 8
                                ]);
                                break;
                            case 'getRealisasi':
                                $id_rab = $validate->setRules('kode', 'kode', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'kode', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
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
                $Fungsi = new MasterFungsi();
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
                        case 'inbox':
                        case 'outbox':
                        case 'wall':
                            $tabel_pakai = 'ruang_chat';
                            break;
                        default:
                    }
                    $sukses = true;
                    $err = 0;
                    $kodePosting = '';
                    $jumlah_kolom = 0;
                    $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);
                    //var_dump($data_kd_proyek);
                    $kd_proyek = '';
                    $Tk = 0;
                    $MPP = 0;
                    $op = 0;
                    //$tahun_anggaran = 0;
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
                            $MPP = 0;
                            if ($jumlahArray > 0) {
                                foreach ($informasi as $key => $value) {
                                    ${$value->kode} = $value->nilai;
                                    $kode_informasi = $value->kode;
                                    switch ($kode_informasi) {
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
                            $data['dataProyek']->MPP = $MPP;
                        }
                    }
                    //var_dump($Tk);
                    //$data['keyEncrypt']=$keyEncrypt;


                    switch ($jenis) {
                        case 'inbox':
                            //encrypsi
                            $crypto = new CryptoUtils();
                            $dataWall = $Fungsi->panggilChat($id_row, $posisi, $limit, $jenis, $tabel_pakai = 'ruang_chat');
                            $dataWall = $crypto->encrypt($dataWall, $keyEncrypt);
                            $data['dataWall'] = $dataWall;
                            $code = 202;
                            break;
                        case 'outbox':
                            $crypto = new CryptoUtils();
                            $dataWall = $Fungsi->panggilChat($id_row, $posisi, $limit, $jenis, $tabel_pakai = 'ruang_chat');
                            $dataWall = $crypto->encrypt($dataWall, $keyEncrypt);
                            $data['dataWall'] = $dataWall;
                            $code = 202;
                            break;
                        case 'wall':
                            switch ($tbl) {
                                case 'get':
                                    $code = 202;
                                    $dataWall = '';
                                    if ($id_row > 0) {
                                        //ambil dahulu row id
                                        $condition = [['id', '=', $id_row], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
                                        $result = $DB->getWhereCustom($tabel_pakai, $condition);
                                        $waktu_inputId = $result[0]->waktu_input;

                                        //jalankan
                                        if ($posisi == 'top') {
                                            $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
                                            //disini dia

                                        } else {
                                            $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
                                        }
                                    } else {
                                        $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
                                    }

                                    $rowWall = $DB->getWhereCustom($tabel_pakai, $condition);
                                    $posisiLimit = $DB->posisilimit($limit, $rowWall, $halaman = 1);
                                    $DB->limit([$posisiLimit, $limit]);
                                    // if ($posisi == 'top') {
                                    //     $DB->orderBy('waktu_input', 'DESC');
                                    // } else {
                                    //     $DB->orderBy('waktu_input', 'ASC');
                                    // }
                                    $DB->orderBy('waktu_input', 'DESC');
                                    $rowWall = $DB->getWhereCustom($tabel_pakai, $condition);
                                    $jumlahArray = is_array($rowWall) ? count($rowWall) : 0;
                                    if ($jumlahArray) {
                                        //jika ada data sebelum post
                                        //tidak ada data sebelum post
                                        foreach ($rowWall as $key => $value) {
                                            $id = $value->id;
                                            $waktu_input = $value->waktu_input;
                                            $waktu_edit = $value->waktu_edit;
                                            $uraian = $value->uraian;

                                            $id_pengirim = $value->id_pengirim;
                                            $id_tujuan = $value->id_tujuan;
                                            $dibaca = $value->dibaca;
                                            $id_reply = $value->id_reply;
                                            $like = $value->like;
                                            $userWithId = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_pengirim]);
                                            $btnDel = '';
                                            $selisihWaktu = $Fungsi->selisihWaktu($waktu_input, date('Y-m-d H:i:s'));
                                            $dateSelisih = $waktu_input;
                                            $photo = $userWithId->photo;

                                            $photo = explode('/', $photo);
                                            ($photo[0] != 'img') ? $photo[0] = 'img' :  0;
                                            $photo = implode('/', $photo);
                                            $nama = $userWithId->nama;
                                            $uraian = $Fungsi->deskripsiText($uraian, $nama);
                                            if ($selisihWaktu['bulan'] > 0 || $selisihWaktu['tahun'] > 0) {
                                                $dateSelisih = $waktu_input;
                                            } else if ($selisihWaktu['hari'] > 0) {
                                                $dateSelisih = $selisihWaktu['hari'] . ' days ' . $selisihWaktu['jam'] . ' ago';
                                            } else if ($selisihWaktu['jam'] > 0) {
                                                $dateSelisih = $selisihWaktu['jam'] . ' hours ' . $selisihWaktu['menit'] . " minutes ago";
                                            } else if ($selisihWaktu['menit'] > 0) {
                                                $dateSelisih = $selisihWaktu['menit'] . " minutes ago";
                                            } else if ($selisihWaktu['detik'] > 0) {
                                                $dateSelisih = "just now";
                                            }

                                            if ($id_user == $id_pengirim) {
                                                $btnDel = '<a class="edit" name="chat" jns="wall" tbl="edit"><i class="edit icon"></i>Edit</a><a class="trash" name="del_row" jns="chat" tbl="del_row"><i class="trash icon"></i>Delete</a>';
                                            }

                                            $dataWall .= '<div class="comment" id_row="' . $id . '"><a class="avatar"><img src="' . $photo . '"></a><div class="content"><a class="author">' . $nama . '</a><div class="metadata"><div class="date">' . $dateSelisih . '</div><div class="rating"><i class="star icon"></i>5 Faves </div></div><div class="text">' . $uraian . '</div><div class="actions"><a class="reply" name="chat" jns="wall" tbl="reply">Reply</a><a class="hide" name="chat" jns="wall" tbl="hide">Hide</a>' . $btnDel . '</div></div>';
                                            $dataWall .= $Fungsi->recursiveChat($id);
                                            $dataWall .= '</div>';
                                        }
                                        //$data['dataWall'] = $dataWall;
                                        //var_dump($dataWall);
                                    }
                                    $data['dataWall'] = $dataWall;
                                    break;
                                case 'edit':
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'rekanan':
                            switch ($tbl) {
                                case 'cek_kode':
                                    // $data['count'] = $DB->getQuery('SELECT kd_proyek FROM `nama_pkt_proyek` WHERE kd_proyek = ?', [$text])->rowCount;
                                    $cek = $DB->getWhereOnce($tabel_pakai, ['nama_perusahaan', '=', $text]);
                                    if ($cek) { //data sudah ada
                                        $code = 9;
                                    } else {
                                        $code = 41;
                                    }
                                    break;
                                case 'get_list':
                                    $jumlah_kolom = 11;
                                    $like = "nama_perusahaan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR direktur LIKE CONCAT('%',?,'%') OR data_lain LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%')";
                                    $data_like = [$cari, $cari, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY nama_perusahaan, id ASC";
                                    $where1 = "id > ?";
                                    // $limit = 'all';
                                    $data_where1 =  [0];
                                    $kodePosting = 'buatTabel';
                                    $where2 = 'id > ?';
                                    $data_where2 = [0];
                                    $sum = $DB->getQuery("SELECT COUNT(id) AS countRekanan FROM $tabel_pakai WHERE $where2", $data_where2);
                                    $data['countRekanan'] = $sum[0]->countRekanan;
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "id = ?";
                                    $data_where1 =  [$id_row];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'lap-harian':
                            switch ($tbl) {
                                case 'get_list':
                                    //var_dump('ok');
                                    $like = "kd_proyek = ? AND tanggal = ? AND (uraian LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY type, no_sortir, tanggal, id ASC";
                                    $where1 = "kd_proyek = ? AND tanggal = ?";
                                    $data_where1 =  [$kd_proyek, $tanggal];
                                    $kodePosting = 'buatTabel';
                                    $jumlah_kolom = 6;
                                    $limit = 'all';
                                    break;
                                case 'x':
                                    break;
                            }
                            break;
                        case 'monev':
                            switch ($tbl) {
                                case 'lap_range':
                                    $dataku = $Fungsi->laporanPeriodik($kd_proyek, $tanggal_awal, $tanggal_akhir);
                                    //var_dump($dataku);
                                    $jumlah_kolom = 19;
                                    $dataTabel = $Fungsi->getTabel($jenis, $tbl, $dataku, 1, 1, $jumlah_kolom);
                                    //var_dump($dataTabel);
                                    $data = array_merge($dataTabel, $data);
                                    $code = 831;
                                    break;
                                case 'edit':
                                    $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $DB->orderBy('no_sortir');
                                    $rowMonev = $DB->getWhereCustom($tabel_pakai, $condition);
                                    $jumlahArray = is_array($rowMonev) ? count($rowMonev) : 0;
                                    if ($jumlahArray) {
                                    }
                                    $cari = '';
                                case 'input':
                                    $tabel_pakai = 'rencana_anggaran_biaya';
                                    $like = "kd_proyek = ? AND type = ? AND (uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, 'analisa', $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND type = ?"; //analisa, header, keterangan
                                    $data_where1 =  [$kd_proyek, 'analisa'];
                                    $kodePosting = 'get_row_json';
                                    $limit = 'all';
                                    break;
                                case 'getRealisasi':
                                    $where = "kd_proyek = ? AND id_rab = ?";
                                    $data_where =  [$kd_proyek, $id_rab];
                                    $sum = $DB->getQuery("SELECT SUM(realisasi_fisik) AS realisasiFisik, SUM(realisasi_keu) AS realisasiKeuangan FROM $tabel_pakai WHERE $where", $data_where);
                                    if ($sum) {
                                        $data = $sum[0];
                                        $code = 202;
                                    } else {
                                        $code = 404;
                                        $data = $sum;
                                    }
                                    //var_dump()
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'monev[informasi]':
                            switch ($tbl) {
                                case 'get_list':
                                    $kolom = "$tabel_pakai.kd_proyek,$tabel_pakai.uraian,$tabel_pakai.satuan,$tabel_pakai.tanggal,$tabel_pakai.realisasi_fisik,$tabel_pakai.realisasi_keu, rencana_anggaran_biaya.volume, rencana_anggaran_biaya.harga_dasar, rencana_anggaran_biaya.jumlah_op, rencana_anggaran_biaya.harga_satuan, rencana_anggaran_biaya.jumlah_harga";
                                    $nama_sheet2 = 'schedule';
                                    $nama_sheet3 = 'informasi';
                                    $where1 = "$tabel_pakai.kd_proyek = '$kd_proyek' AND $tabel_pakai.id_rab=rencana_anggaran_biaya.id ";
                                    $order = "ORDER BY $tabel_pakai.id_rab ASC";
                                    $query = "SELECT $kolom FROM $tabel_pakai,rencana_anggaran_biaya WHERE $where1 $order";
                                    $sum = $DB->getQuery($query);
                                    //var_dump($sum);
                                    $sumRealisasiFisik = 0;
                                    $sumRealisasiKeu = 0;
                                    if ($sum) {
                                        //$data['temp'] = $sum;
                                        foreach ($sum as $key => $val) {
                                            $sumRealisasiFisik += $val->realisasi_fisik * $val->harga_satuan;
                                            $sumRealisasiKeu += $val->realisasi_keu;
                                        }
                                        $code = 202;
                                    } else {
                                        $code = 404;
                                    }
                                    $data['sumRealisasiFisik'] = $sumRealisasiFisik;
                                    $data['sumRealisasiKeu'] = $sumRealisasiKeu;
                                    //ambil jumlah_harga di table rencana anggaran biaya
                                    $where2 = 'kd_proyek = ?';
                                    $data_where2 = [$kd_proyek];
                                    $sum = $DB->getQuery("SELECT SUM(jumlah_harga) FROM rencana_anggaran_biaya WHERE $where2", $data_where2);
                                    $data['sumRab'] = $sum[0]->{'SUM(jumlah_harga)'}; //cara mengambil value object di array
                                    //ambil nomor id semua user dan username untuk dropdown owner
                                    $DB->orderBy('id');
                                    $DB->select('id , username, nama');
                                    $rowUser = $DB->getWhereCustom('user_ahsp', [['aktif', '=', 1], ['aktif_edit', '=', 1, 'AND']]);
                                    $jumlahArray = is_array($rowUser) ? count($rowUser) : 0;
                                    if ($jumlahArray) {
                                        $dataJson = [];
                                        foreach ($rowUser as $key => $value) {
                                            $dataJson[] = ['name' => $value->nama, 'value' => $value->id];
                                        }
                                        $data['user_list'] = $dataJson;
                                    }
                                    //ambil data perusahaan
                                    $DB->orderBy('nama_perusahaan, id');
                                    $DB->select('id , nama_perusahaan, npwp,direktur,alamat');
                                    $rowUser = $DB->getWhereCustom('rekanan', [['id', '>', 0]]);
                                    $jumlahArray = is_array($rowUser) ? count($rowUser) : 0;
                                    if ($jumlahArray) {
                                        $dataJson = [];
                                        foreach ($rowUser as $key => $value) {
                                            $gabung = $value->direktur . "; " . $value->npwp;
                                            $dataJson[] = ['name' => $value->nama_perusahaan, 'value' => $value->id, 'description' => $gabung, 'text' => $value->nama_perusahaan];
                                        }
                                        $data['user_rekanan'] = $dataJson;
                                    }
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'monev[realisasi]':
                            switch ($tbl) {
                                case 'get_list':
                                    $like = "kd_proyek = ? AND (uraian LIKE CONCAT('%',?,'%') OR tanggal LIKE CONCAT('%',?,'%') OR tgl_input LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY tanggal ASC, id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'buatTabel';
                                    $jumlah_kolom = 8;
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'monev[laporan]':
                            switch ($tbl) {
                                case 'get_list':
                                    $kolom = "$tabel_pakai.kd_proyek,$tabel_pakai.uraian,$tabel_pakai.satuan,$tabel_pakai.tanggal,$tabel_pakai.realisasi_fisik,$tabel_pakai.realisasi_keu, rencana_anggaran_biaya.volume, rencana_anggaran_biaya.harga_dasar, rencana_anggaran_biaya.jumlah_op, rencana_anggaran_biaya.harga_satuan, rencana_anggaran_biaya.jumlah_harga";
                                    $nama_sheet2 = 'schedule';
                                    $nama_sheet3 = 'informasi';
                                    $where1 = "$tabel_pakai.kd_proyek = '$kd_proyek' AND $tabel_pakai.id_rab=rencana_anggaran_biaya.id ";
                                    $order = "ORDER BY $tabel_pakai.id_rab ASC";
                                    $query = "SELECT $kolom FROM $tabel_pakai,rencana_anggaran_biaya WHERE $where1 $order";
                                    $sum = $DB->getQuery($query);
                                    //var_dump($sum);
                                    $sumRealisasiFisik = 0;
                                    $sumRealisasiKeu = 0;
                                    if ($sum) {
                                        //$data['temp'] = $sum;
                                        foreach ($sum as $key => $val) {
                                            $sumRealisasiFisik += $val->realisasi_fisik * $val->harga_satuan;
                                            $sumRealisasiKeu += $val->realisasi_keu;
                                        }
                                        $code = 202;
                                    } else {
                                        $code = 404;
                                    }
                                    $data['sumRealisasiFisik'] = $sumRealisasiFisik;
                                    $data['sumRealisasiKeu'] = $sumRealisasiKeu;
                                    //ambil jumlah_harga di table rencana anggaran biaya
                                    $where2 = 'kd_proyek = ?';
                                    $data_where2 = [$kd_proyek];
                                    $sum = $DB->getQuery("SELECT SUM(jumlah_harga) FROM rencana_anggaran_biaya WHERE $where2", $data_where2);
                                    $data['sumRab'] = $sum[0]->{'SUM(jumlah_harga)'};
                                    //ambil data truncate tanggal di realisasi
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'peraturan':
                            switch ($tbl) {
                                case 'get_list':
                                    $like = "status != 'hidden' AND (type LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR status LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$cari, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, uraian, id ASC";
                                    $where1 = "status != ? OR status = ? OR kd_proyek = ?";
                                    $data_where1 =  ['hidden', 'umum', $kd_proyek];
                                    $kodePosting = 'buatList';
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY id ASC";
                                    $where1 = "id = ?";
                                    $data_where1 =  [$id_row];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'lokasi':
                        case 'lokasi-lokasi':
                        case 'lokasi-marker':
                        case 'lokasi-polyline':
                        case 'lokasi-polygon':
                            switch ($tbl) {
                                case 'get_list':
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'harga_satuan':
                            $tabel_pakai = 'harga_sat_upah_bahan';
                            switch ($tbl) {
                                case 'list': //dropdown
                                    $like = "kd_proyek = ? AND (jenis LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR sumber_data LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir,id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'buatTabel';
                                    $jumlah_kolom = 9;
                                    $sum_harga_satuan = $DB->getWhere($tabel_pakai, ['kd_proyek', '=', $kd_proyek]);
                                    $data['sum_harga_satuan'] = is_array($sum_harga_satuan) ? count($sum_harga_satuan) : 0;
                                    //$data['sum_harga_satuan'] = count((array)$sum_harga_satuan);
                                    break;
                                case 'edit':
                                    $data = $DB->getWhere($tabel_pakai, ['id', '=', $id_row])[0];
                                    //var_dump(count((array)$data));
                                    $jumlahArray = is_array((array)$data) ? count((array)$data) : 0;
                                    $code = $jumlahArray > 0 ? 202 : 36;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case "proyek":
                            switch ($tbl) {
                                case 'cekAktif':
                                    // buatkan code eksripsi
                                    //seharusnya data yang diambil jumlah proyek
                                    $data['sumProyek'] = $DB->getWhereArray('nama_pkt_proyek', [['nama_user', '=', $username]]);
                                    //var_dump($data['sumProyek']);
                                    $data['sumProyek'] = is_array($data['sumProyek']) ? count($data['sumProyek']) : 0;
                                    break;
                                case 'cek_kode':
                                    // $data['count'] = $DB->getQuery('SELECT kd_proyek FROM `nama_pkt_proyek` WHERE kd_proyek = ?', [$text])->rowCount;
                                    $cek = $DB->getWhereOnce($tabel_pakai, ['kd_proyek', '=', $text]);
                                    if ($cek) { //data sudah ada
                                        $code = 9;
                                    } else {
                                        $code = 41;
                                    }
                                    break;
                                case 'get_all_proyek': //tabel list proyek dan data proyek
                                    //$tabelBarang = $DB->select('nama_barang,id_barang')->getLike('barang','nama_barang','%k%');
                                    $like = "kd_proyek LIKE CONCAT('%',?,'%') OR nama_proyek LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%')";
                                    $data_like = [$cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir,id ASC";

                                    $where1 = ($type_user == 'admin') ? "status = ? OR JSON_CONTAINS(owner,?,'$')" : "nama_user = ? OR JSON_CONTAINS(owner,?,'$')";
                                    $data_where1 = ($type_user == 'admin') ? ['admin', $id_user] : [$username, $id_user];
                                    $kodePosting = 'buatTabel';
                                    $jumlah_kolom = 7;
                                    //ambil jumlah data proyek jika admin semua proyek admin bisa dilihat
                                    $data['sumProyek'] = $DB->getWhereArray('nama_pkt_proyek', [['nama_user', '=', $username]]);
                                    //var_dump($data['sumProyek']);
                                    $data['sumProyek'] = is_array($data['sumProyek']) ? count($data['sumProyek']) : 0;
                                    //$data['sumProyek'] = count((array)$data['sumProyek']); // cara jumlahkan object di php
                                    break;
                                case 'copy_proyek':
                                    //ambil data paket
                                    $DB->orderBy('nama_proyek, id');
                                    $DB->select('id, kd_proyek , nama_proyek, keterangan,tahun_anggaran');
                                    $rowUser = $DB->getWhereCustom('nama_pkt_proyek', [['status', '=', 'admin']]);
                                    $jumlahArray = is_array($rowUser) ? count($rowUser) : 0;
                                    if ($jumlahArray) {
                                        $dataJson = [];
                                        foreach ($rowUser as $key => $value) {
                                            $gabung = "kode: " . $value->kd_proyek . " ; " . $value->tahun_anggaran . "; " . $value->keterangan; //, 'description' => $gabung
                                            $dataJson[] = ['name' => $value->nama_proyek, 'value' => $value->kd_proyek, 'description' => $gabung, 'text' => $value->nama_proyek];
                                        }
                                        $data['user_proyek'] = $dataJson;
                                    }
                                    break;
                                case 'edit':
                                    $data = $DB->getWhere($tabel_pakai, ['id', '=', $id_row])[0];
                                    //var_dump($data);
                                    if ($data) { //data sudah ada
                                        $code = 202;
                                    } else {
                                        $code = 41;
                                    }
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'rab':
                            $tabel_pakai = 'rencana_anggaran_biaya';
                            switch ($tbl) {
                                case 'get_list': //tabel list proyek dan data proyek
                                    //$tabelBarang = $DB->select('nama_barang,id_barang')->getLike('barang','nama_barang','%k%');
                                    $like = "kd_proyek = ? AND (kd_analisa LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $jumlah_kolom = 8;
                                    $kodePosting = 'buatTabel';
                                    //ambil jumlah data proyek jika admin semua proyek admin bisa dilihat
                                    $data['sumProyek'] = $DB->getWhereArray('nama_pkt_proyek', [['nama_user', '=', $username]]);
                                    //var_dump($data['sumProyek']);
                                    $data['sumProyek'] = is_array($data['sumProyek']) ? count($data['sumProyek']) : 0;
                                    //$data['sumProyek'] = count((array)$data['sumProyek']); // cara jumlahkan object di php
                                    $where2 = 'kd_proyek = ?';
                                    $data_where2 = [$kd_proyek];
                                    $sum = $DB->getQuery("SELECT SUM(jumlah_harga) FROM $tabel_pakai WHERE $where2", $data_where2);
                                    //var_dump($sum);
                                    $data['sum'] = $sum[0]->{'SUM(jumlah_harga)'}; //cara mengambil value object di array
                                    $data_where2 = [$kd_proyek];
                                    $sum = $DB->getQuery("SELECT COUNT(jumlah_harga) AS sumRab FROM $tabel_pakai WHERE $where2", $data_where2);
                                    $data['sumRab'] = $sum[0]->sumRab;
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND id = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
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
                            $tabel_pakai = 'divisi';
                            switch ($tbl) {
                                case 'get_list': //tabel list proyek dan data proyek
                                    //$tabelBarang = $DB->select('nama_barang,id_barang')->getLike('barang','nama_barang','%k%');
                                    switch ($jenis) {
                                        case 'divisiCK':
                                            $bidang = 'ck';
                                            break;
                                        case 'divisiSDA':
                                            $bidang = 'sda';
                                            break;
                                        default:
                                            $bidang = 'bm';
                                            break;
                                    }
                                    $like = "tahun = ? AND bidang = ? AND (kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR tingkat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$tahun_anggaran, $bidang, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY id ASC"; //"ORDER BY Length(kode),kode, id ASC";//"ORDER BY Cast(kode as Unsigned),kode, id ASC"
                                    $where1 = "tahun = ? AND bidang = ?";
                                    $data_where1 =  [$tahun_anggaran, $bidang];
                                    $jumlah_kolom = 5;
                                    $kodePosting = 'buatTabel';
                                    //$data['sumProyek'] = count((array)$data['sumProyek']); // cara jumlahkan object di php
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY kode, id ASC";
                                    $where1 = "tahun = ? AND id = ?";
                                    $data_where1 =  [$tahun_anggaran, $id_row];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'schedule':
                            switch ($tbl) {
                                case 'get_list': //tabel list proyek dan data proyek
                                    //$tabelBarang = $DB->select('nama_barang,id_barang')->getLike('barang','nama_barang','%k%');
                                    $like = "kd_proyek = ? AND (kd_analisa LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $jumlah_kolom = 9;
                                    $kodePosting = 'buatTabel';
                                    //ambil jumlah data proyek jika admin semua proyek admin bisa dilihat
                                    $data['sumProyek'] = $DB->getWhereArray('nama_pkt_proyek', [['nama_user', '=', $username]]);
                                    //var_dump($data['sumProyek']);
                                    $data['MPP'] = $MPP;
                                    $data['sumProyek'] = is_array($data['sumProyek']) ? count($data['sumProyek']) : 0;
                                    //$data['sumProyek'] = count((array)$data['sumProyek']); // cara jumlahkan object di php
                                    break;
                                case 'edit':
                                    #ambil data kolom id,kd_analisa, uraian yang type analisa di tabel rab
                                    $DB->orderBy('no_sortir');
                                    $DB->select('id , kd_analisa, uraian,jumlah_harga');
                                    $rowUser = $DB->getWhereCustom('rencana_anggaran_biaya', [['kd_proyek', '=', $kd_proyek], ['type', '=', 'analisa', 'AND']]);
                                    $jumlahArray = is_array($rowUser) ? count($rowUser) : 0;
                                    if ($jumlahArray) {
                                        $dataJson = [];
                                        foreach ($rowUser as $key => $value) {
                                            $gabung = $value->kd_analisa . ", Jumlah : Rp." . number_format($value->jumlah_harga, 2, ',', '.');
                                            $dataJson[] = ['name' => $value->uraian, 'value' => $value->id, 'description' => $gabung, 'text' => $value->uraian];
                                        }
                                        $data['user_list'] = $dataJson;
                                    }
                                    //
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND id = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'sbu':
                            //dipisah jika dari rab edit
                            if ($tbl == 'edit') {
                                $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                $rowRAB = $DB->getWhereCustom('rencana_anggaran_biaya', $condition);
                                $jumlahArray = is_array($rowRAB) ? count($rowRAB) : 0;
                                $kd_analisa = '-';
                                if ($jumlahArray) {
                                    $kd_analisa = $rowRAB[0]->kd_analisa;
                                    $tbl = 'get';
                                    //Binamarga
                                    $condition = 'WHERE kd_proyek = ? AND kd_analisa = ?';
                                    $tableName = 'analisa_pekerjaan_bm';
                                    $bindValue = [$kd_proyek, $kd_analisa];
                                    $resul = $DB->get($tableName, $condition, $bindValue);
                                    $jumlahArray = is_array($resul) ? count($resul) : 0;
                                    $type = 'header'; //1. analisa,2.keterangan, 3.header
                                    if ($jumlahArray) {
                                        $tbl = 'bm';
                                    } else {
                                        //Cipta Karya
                                        $tableName = 'analisa_pekerjaan_ck';
                                        $bindValue = [$kd_proyek, $kd_analisa];
                                        $resul = $DB->get($tableName, $condition, $bindValue);
                                        $jumlahArray = is_array($resul) ? count($resul) : 0;
                                        if ($jumlahArray) {
                                            $tbl = 'ck';
                                        } else {
                                            //SDA
                                            $tableName = 'analisa_pekerjaan_sda';
                                            $bindValue = [$kd_proyek, $kd_analisa];
                                            $resul = $DB->get($tableName, $condition, $bindValue);
                                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                                            if ($jumlahArray) {
                                                $tbl = 'sda';
                                            }
                                        }
                                    }
                                }
                            }
                            $tabel_pakai = 'harga_sat_upah_bahan';
                            $tbl = strtolower($tbl);
                            $cari = strtolower($cari);
                            $order = "ORDER BY id ASC";
                            switch ($tbl) {
                                case 'get':
                                    $jenis_search = 'sbu';
                                    $posisi = strpos($cari, '>>');
                                    $jenis_search = strtolower(substr($cari, 0, $posisi));
                                    $kodePosting = 'get_row_json';
                                    break;
                                case 'bm':
                                    $jenis_search = 'bm';
                                    $kodePosting = 'get_row_json';
                                    break;
                                case 'ck':
                                    $jenis_search = 'ck';
                                    $kodePosting = 'get_row_json';
                                    break;
                                case 'sda':
                                    $jenis_search = 'sda';
                                    $kodePosting = 'get_row_json';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            //var_dump($jenis_search);
                            //bisa juga jika $cari diawali kata 'alat>>' maka dicari di tabel alat,'quarry>>' maka dicari di tabel quarry
                            switch ($jenis_search) {
                                case 'alat':
                                    $cari = str_replace('alat>>', '', $cari);
                                    $tabel_pakai = 'analisa_alat';
                                    $like = "kd_proyek = ? AND (kode LIKE CONCAT('%',?,'%') OR jenis_peralatan LIKE CONCAT('%',?,'%') OR ket_alat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari, $cari];
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    break;
                                case 'quarry':
                                    $cari = str_replace('quarry>>', '', $cari);
                                    $tabel_pakai = 'analisa_quarry';
                                    $like = "kd_proyek = ?  AND kd_analisa = nomor AND (kd_analisa LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $data_where1 =  [$kd_proyek];
                                    break;
                                case 'bm':
                                    $cari = str_replace('bm>>', '', $cari);
                                    $tabel_pakai = 'analisa_pekerjaan_bm';
                                    $like = "kd_proyek = ?  AND kd_analisa = nomor AND (kd_analisa LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $data_where1 =  [$kd_proyek];
                                    break;
                                case 'sda':
                                    $cari = str_replace('sda>>', '', $cari);
                                    $tabel_pakai = 'analisa_pekerjaan_sda';
                                    $like = "kd_proyek = ?  AND kd_analisa = nomor AND (kd_analisa LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $data_where1 =  [$kd_proyek];
                                    break;
                                case 'ck':
                                    $cari = str_replace('ck>>', '', $cari);
                                    $tabel_pakai = 'analisa_pekerjaan_ck';
                                    $like = "kd_proyek = ?  AND kd_analisa = nomor AND (kd_analisa LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $data_where1 =  [$kd_proyek];
                                    break;
                                default:
                                    $like = "kd_proyek = ? AND (kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    break;
                            }
                            break;
                        case 'satuan': //@audit cek_kode
                            $tabel_pakai = 'daftar_satuan';
                            switch ($tbl) {
                                case 'cek_kode':
                                    // $data['count'] = $DB->getQuery('SELECT kd_proyek FROM `nama_pkt_proyek` WHERE kd_proyek = ?', [$text])->rowCount;
                                    $cek = $DB->getWhereOnce($tabel_pakai, ['value', '=', $text]);
                                    if ($cek) { //data sudah ada
                                        $code = 9;
                                    } else {
                                        $code = 41;
                                    }
                                    break;
                                case 'list':
                                    $jumlah_kolom = 5;
                                    //$tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                                    $like = "value LIKE CONCAT('%',?,'%') OR item LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%')";
                                    $data_like = [$cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "";
                                    $data_where1 =  [];
                                    $kodePosting = 'buatTabel';
                                    //ambil jumlah data proyek jika admin semua proyek admin bisa dilihat
                                    if ($type_user == 'admin') {
                                        $data['sumProyek'] = $DB->getWhere('nama_pkt_proyek', ['status', '=', 'admin']);
                                    } else {
                                        $data['sumProyek'] = $DB->getWhereArray('nama_pkt_proyek', [['nama_user', '=', $username], ['status', '=', 'user', 'AND']]);
                                    }
                                    $data['sumProyek'] = is_array($data['sumProyek']) ? count($data['sumProyek']) : 0;
                                    //$data['sumProyek'] = count((array)$data['sumProyek']); // cara jumlahkan object di php
                                    //jumlah satuan
                                    $sum_harga_satuan = $DB->get($tabel_pakai);
                                    $data['sum_harga_satuan']  = is_array($sum_harga_satuan) ? count($sum_harga_satuan) : 0;
                                    break;
                                case 'get':
                                    $like = "value LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR item LIKE CONCAT('%',?,'%')";
                                    $data_like = [$cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "id > ?";
                                    $limit = 'all';
                                    $data_where1 =  [0];
                                    $kodePosting = 'get_row_json';
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "id = ?";
                                    $data_where1 =  [$id_row];
                                    $kodePosting = 'get_row';
                                    $limit = 'all';
                                    $cari = '';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'analisa_alat':
                            $tabel_pakai =  'analisa_alat';
                            switch ($tbl) {
                                case 'cek_kode':
                                    $code = 50;
                                    break;
                                case 'list':
                                    //$tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                                    $like = "kd_proyek = ? AND jenis_peralatan LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR ket_alat LIKE CONCAT('%',?,'%')";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'buatList';
                                    // mengambil jumlah alat yang sudah diinput sesuai proyek
                                    $sumRows = $DB->getWhereArray($tabel_pakai, [['kd_proyek', '=', $kd_proyek]]);
                                    $data['sumAlat'] = is_array($sumRows) ? count($sumRows) : 0;
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND id = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'buatTabel';
                                    $limit = 'all';
                                    $cari = '';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'analisa_quarry':
                            switch ($tbl) {
                                case 'cek_kode':
                                    $code = 50;
                                    break;
                                case 'list':
                                    //$tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                                    $like = "kd_proyek = ? AND kd_analisa = nomor AND (uraian LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $posisi = " LIMIT ?, ?";
                                    $order = "ORDER BY Length(kd_analisa),kd_analisa,no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'buatList';
                                    $klmDISTINCT = 'kd_analisa';
                                    // mengambil jumlah alat yang sudah diinput sesuai proyek
                                    $sumRows = $DB->getDistinct($tabel_pakai, 'kd_analisa', [['kd_proyek', '=', $kd_proyek]]);
                                    $data['sumquarry'] = is_array($sumRows) ? count($sumRows) : 0;
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND kd_analisa = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'buatTabel';
                                    $limit = 'all';
                                    $cari = '';
                                    $jumlah_kolom = 9;
                                    break;
                                case 'import': //ambil data dropdown analisa upah jenis royalty
                                    $tabel_pakai =  'harga_sat_upah_bahan';
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND jenis = ?";
                                    $data_where1 =  [$kd_proyek, 'royalty'];
                                    $kodePosting = 'buatDropdown';
                                    $jumlah_kolom_dropdown = 1;
                                    $jenisdropdown = 'list';
                                    break;
                                case 'get': //dropdown
                                    $limit = 'all';
                                    $tabel_pakai = 'harga_sat_upah_bahan';
                                    $like = "kd_proyek = ? AND jenis = ? AND (uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR jenis LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, "royalty", $cari, $cari, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND jenis = ?";
                                    $data_where1 =  [$kd_proyek, 'royalty'];
                                    $kodePosting = 'get_row_json';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;

                        case 'analisa_alat_custom':
                            $tabel_pakai =  'analisa_alat_custom';
                            switch ($tbl) {
                                case 'cek_kode':
                                    $code = 50;
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND kd_analisa = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'buatTabel';
                                    $limit = 'all';
                                    $cari = '';
                                    $jumlah_kolom = 9;
                                    break;
                                case 'import': //ambil data dropdown analisa upah jenis royalty
                                    $tabel_pakai =  'harga_sat_upah_bahan';
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND jenis = ?";
                                    $data_where1 =  [$kd_proyek, 'royalty'];
                                    $kodePosting = 'buatDropdown';
                                    $jumlah_kolom_dropdown = 1;
                                    $jenisdropdown = 'list';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'analisa_sda':
                        case 'analisa_bm':
                            //$tabel_pakai =  'analisa_pekerjaan_bm';
                            switch ($tbl) {
                                case 'cek_kode':
                                    $code = 50;
                                    break;
                                case 'list':
                                    //$tahun_anggaran = $data['dataProyek']['tahun_anggaran'];
                                    //$tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                                    $like = "kd_proyek = ? AND kd_analisa = ? AND(uraian LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, 'nomor', $cari, $cari, $cari];
                                    $posisi = " LIMIT ?, ?";
                                    $order = "ORDER BY Cast(kd_analisa as Unsigned),kd_analisa,no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'buatList';
                                    $klmDISTINCT = 'kd_analisa';
                                    // mengambil jumlah alat yang sudah diinput sesuai proyek
                                    $sumRows = $DB->getDistinct($tabel_pakai, 'kd_analisa', [['kd_proyek', '=', $kd_proyek]]);
                                    $data['sumanalisa'] = is_array($sumRows) ? count($sumRows) : 0;
                                    //$data['sumanalisa'] = count($sumRows);
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND kd_analisa = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'buatTabel';
                                    $limit = 'all';
                                    $cari = '';
                                    $jumlah_kolom = 9;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'analisa_ck':
                            $tabel_pakai =  'analisa_pekerjaan_ck';
                            switch ($tbl) {
                                case 'cek_kode':
                                    $code = 50;
                                    break;
                                case 'list':
                                    //$like = "kd_proyek = $kd_proyek AND CHAR_LENGTH(keterangan) > 0 AND uraian LIKE CONCAT('%','$cari','%') OR kd_analisa LIKE CONCAT('%','$cari','%') OR keterangan LIKE CONCAT('%','$cari','%')";
                                    $like = "kd_proyek = ? AND kd_analisa = nomor AND(uraian LIKE CONCAT('%',?,'%') OR kd_analisa LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari, $cari, $cari];
                                    $posisi = " LIMIT ?, ?";
                                    $order = "ORDER BY Cast(kd_analisa as Unsigned),kd_analisa,id ASC"; //order natural mysql
                                    $where1 = "kd_proyek = ? AND kd_analisa = nomor";
                                    $data_where1 =  [$kd_proyek];
                                    $kodePosting = 'buatList';
                                    $klmDISTINCT = 'kd_analisa';
                                    // mengambil jumlah analisa yang sudah diinput sesuai proyek
                                    $sumRows = $DB->getDistinct($tabel_pakai, 'kd_analisa', [['kd_proyek', '=', $kd_proyek]]);
                                    $data['sumanalisa'] = is_array($sumRows) ? count($sumRows) : 0;
                                    break;
                                case 'edit':
                                    $like = "";
                                    $data_like = [];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ? AND kd_analisa = ?";
                                    $data_where1 =  [$kd_proyek, $id_row];
                                    $kodePosting = 'buatTabel';
                                    $limit = 'all';
                                    $cari = '';
                                    $jumlah_kolom = 9;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case "informasi_umum":
                            $tabel_pakai = 'informasi_umum';

                            switch ($tbl) {
                                case 'cek_kode':
                                    $cek = $DB->getWhereOnce($tabel_pakai, ['kode', '=', $text]);
                                    if ($cek) { //data sudah ada
                                        $code = 9;
                                    } else {
                                        $code = 41;
                                    }
                                    break;
                                case 'list':
                                    $kodePosting = 'buatTabel';
                                    $jumlah_kolom = 9;
                                    $like = "kd_proyek = ? AND (uraian LIKE CONCAT('%',?,'%'))";
                                    $data_like = [$kd_proyek, $cari];
                                    $order = "ORDER BY no_sortir, id ASC";
                                    $where1 = "kd_proyek = ?";
                                    $data_where1 =  [$kd_proyek];
                                    $limit = 'all';
                                    break;
                                default:

                                    break;
                            }
                            break;
                        case 'user':
                            $tabel_pakai =  'user_ahsp';
                            switch ($tbl) {
                                case 'get_users_list': //untuk dropdown user kirim pesan
                                    $like = "nama LIKE CONCAT('%',?,'%') OR username LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%')";
                                    $data_like = [$cari, $cari, $cari];
                                    $order = "ORDER BY nama, id ASC";
                                    $where1 = "id > ?  AND id <> ?";
                                    $limit = 'all';
                                    $data_where1 =  [0, $id_user];
                                    $kodePosting = 'get_row_json';
                                    break;
                                case 'list':
                                    $like = "username LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%') OR nama LIKE CONCAT('%',?,'%') OR nama_org LIKE CONCAT('%',?,'%')";
                                    $data_like = [$cari, $cari, $cari, $cari];
                                    $order = "ORDER BY id ASC";
                                    $where1 = "username != ?";
                                    $data_where1 =  ['gila'];
                                    $kodePosting = 'buatTabel';
                                    $jumlah_kolom = 16;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case "profil":
                            $tabel_pakai =  'user_ahsp';
                            $where1 = "username = ?";
                            $data_where1 =  [$username];
                            $kolom = "username, email, nama, nama_org, type_user, photo, tgl_daftar, tgl_login, thn_aktif_anggaran, kd_proyek_aktif, ket, kontak_person, font_size, warna_tbl, scrolling_table";
                            $kodePosting = 'get_row';
                            break;
                        case "kd_":
                            break;
                        default:
                            $err = 6;
                    }
                    //================================================
                    //==========JENIS POST DATA/INSERT DATA===========
                    //================================================
                    switch ($kodePosting) {
                        case 'get_row': //  ambil data 1 baris 
                            $resul = $DB->getQuery("SELECT $kolom FROM $tabel_pakai WHERE $where1", $data_where1);
                            //var_dump($resul[0]);
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            if ($jumlahArray > 0) {
                                $code = 202; //202
                                $data['users'] = $resul[0];
                            }
                            switch ($jenis) {
                                case 'rab':
                                    switch ($tbl) {
                                        case 'edit':
                                            # cari bidang (dari tabel analisa_pekerjaan_bm ck sda) dari kode analisa di rab 
                                            #agar search analisa bisa menentukan dari bidang mana
                                            #ambil data di analisa
                                            //Binamarga
                                            $kd_analisa = $resul[0]->kd_analisa;
                                            $uraian = $resul[0]->uraian;
                                            //$kd_analisa=$data['users']'kd_analisa';
                                            $condition = 'WHERE kd_proyek = ? AND kd_analisa = ? AND uraian = ?';
                                            $tableName = 'analisa_pekerjaan_bm';
                                            $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                            $resul2 = $DB->get($tableName, $condition, $bindValue);
                                            $jumlahArray = is_array($resul2) ? count($resul2) : 0;
                                            if ($jumlahArray) {
                                                $bidang = 'bm';
                                            } else {
                                                //Cipta Karya
                                                $tableName = 'analisa_pekerjaan_ck';
                                                $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                                $resul2 = $DB->get($tableName, $condition, $bindValue);
                                                $jumlahArray = is_array($resul2) ? count($resul2) : 0;
                                                if ($jumlahArray) {
                                                    $bidang = 'ck';
                                                } else {
                                                    //SDA
                                                    $tableName = 'analisa_pekerjaan_sda';
                                                    $bindValue = [$kd_proyek, $kd_analisa, $uraian];
                                                    $resul2 = $DB->get($tableName, $condition, $bindValue);
                                                    $jumlahArray = is_array($resul2) ? count($resul2) : 0;
                                                    if ($jumlahArray) {
                                                        $bidang = 'sda';
                                                    }
                                                }
                                            }
                                            //var_dump($bidang);
                                            $data['bidang'] = $bidang;
                                            //array_push($resul[0][],(object)['bidang'=>$bidang]);
                                            // /$data['users'] = $resul[0];
                                            /*
                                            array_push($myArray, (object)[
                                                    'key1' => 'someValue',
                                                    'key2' => 'someValue2',
                                                    'key3' => 'someValue3',
                                            ]);
                                            */
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
                        case 'get_row2': // ambil data > 1 baris 
                            $data['rows'] = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1", $data_where1);
                            $code = 2;
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
                            //var_dump($get_data);
                            if ($get_data) {
                                $i = 0;
                                if ($jenis == 'user' && $tbl == 'get_users_list' && $type_user == 'admin') {
                                    $dataJson['results'][] = ['name' => 'pesan/pengumuman untuk semua user', 'value' => 'all', 'image' => './img/avatar/default.jpeg'];
                                }
                                foreach ($get_data as $key => $value) {
                                    //var_dump($key);
                                    switch ($jenis) {
                                        case 'monev':
                                            switch ($tbl) {
                                                case 'edit':
                                                    $dataJson['results']['rowMonev'] = $rowMonev;
                                                case 'input': //untuk dropdown uraian pekerjaan
                                                    //var_dump($value);
                                                    $desimal = ($Fungsi->countDecimals($value->volume) < 2) ? 2 : $Fungsi->countDecimals($value->volume);
                                                    $volume = number_format($value->volume, $desimal, ',', '.');
                                                    $desimal = ($Fungsi->countDecimals($value->jumlah_harga) < 2) ? 2 : $Fungsi->countDecimals($value->jumlah_harga);
                                                    $jumlah_harga = number_format($value->jumlah_harga, $desimal, ',', '.');
                                                    $gabung = "$value->kd_analisa;$volume;$value->satuan;$jumlah_harga;";
                                                    $dataJson['results'][] = ['name' => $value->uraian, 'value' => $value->id, 'description' => $gabung, 'text' => $value->uraian,];
                                                    break;
                                                default:
                                                    # code...
                                                    break;
                                            }
                                            break;
                                        case 'sbu':
                                            switch ($jenis_search) {
                                                case 'quarry':
                                                case 'sda':
                                                case 'bm':
                                                case 'ck':
                                                    $desimal = ($Fungsi->countDecimals($value->koefisien) < 2) ? 2 : $Fungsi->countDecimals($value->koefisien);
                                                    $gabung = $value->kd_analisa . ' (Rp.' . number_format($value->koefisien, $desimal, ',', '.') . ' | ' . $value->satuan . ')';
                                                    $dataJson['results'][] = [
                                                        'kode' => $value->kd_analisa,
                                                        'uraian' => $value->uraian,
                                                        'satuan' => $value->satuan,
                                                        'harga_satuan' => $value->koefisien,
                                                        'jumlah_harga' => $value->jumlah_harga,
                                                        'keterangan' => $value->keterangan,
                                                        'gabung' => $gabung,
                                                        'op' => $op
                                                    ];
                                                    //var_dump($dataJson['results']);
                                                    break;
                                                case 'alat':
                                                    $desimal = ($Fungsi->countDecimals($value->total_biaya_sewa) < 2) ? 2 : $Fungsi->countDecimals($value->total_biaya_sewa);
                                                    $gabung = $value->kode . ' (Rp.' . number_format($value->total_biaya_sewa, $desimal, ',', '.') . ' | kapasitas: ' . $value->kapasitas . ' ' . $value->sat_kapasitas . ' | HP: ' . $value->tenaga . ')';
                                                    $dataJson['results'][] = [
                                                        'kode' => $value->kode,
                                                        'uraian' => $value->jenis_peralatan,
                                                        'jenis' => $value->keterangan,
                                                        'satuan' => $value->sat_kapasitas,
                                                        'harga_satuan' => $value->total_biaya_sewa,
                                                        'spesifikasi' => $value->tenaga,
                                                        'keterangan' => $value->keterangan,
                                                        'gabung' => $gabung,
                                                        'op' => $op
                                                    ];
                                                    break;
                                                default:
                                                    # code...
                                                    $desimal = ($Fungsi->countDecimals($value->harga_satuan) < 2) ? 2 : $Fungsi->countDecimals($value->harga_satuan);
                                                    $gabung = $value->kode . ' (Rp.' . number_format($value->harga_satuan, $desimal, ',', '.') . ' | ' . $value->satuan . ' | ' . $value->jenis . ')';
                                                    $desimal = ($Fungsi->countDecimals($value->harga_satuan) < 2) ? 2 : $Fungsi->countDecimals($value->harga_satuan);
                                                    $dataJson['results'][] = ['kode' => $value->kode, 'jenis' => $value->jenis, 'uraian' => $value->uraian, 'satuan' => $value->satuan, 'harga_satuan' => $value->harga_satuan, 'sumber_data' => $value->sumber_data, 'spesifikasi' => $value->spesifikasi, 'keterangan' => $value->keterangan, 'gabung' => $gabung, 'op' => $op];
                                                    break;
                                            }
                                            break;
                                        case 'analisa_quarry':
                                            switch ($tbl) {
                                                case 'get':
                                                    $dataJson['results'][] = ['name' => $value->uraian, 'value' => $value->kode];
                                                    break;
                                                default:
                                                    # code...
                                                    break;
                                            }
                                            break;
                                        case 'user':
                                            switch ($tbl) {
                                                case 'get_users_list':
                                                    $photo = $value->photo;
                                                    $photo = explode('/', $photo);
                                                    ($photo[0] != 'img') ? $photo[0] = 'img' :  0;
                                                    $photo = implode('/', $photo);
                                                    $dataJson['results'][] = ['name' => $value->nama, 'value' => $value->id, 'image' => $photo]; //, 'description' => $value->nama_org
                                                    break;
                                                default:
                                                    break;
                                            }
                                            break;
                                        default:
                                            ($i == 0) ? $dataJson['results'][] = ['name' => $value->item, 'value' => $value->value] : $dataJson['results'][] = ['name' => $value->item, 'value' => $value->value];
                                            //$dataJson['results'][] = ['name' => $value->item, 'value' => $value->value];
                                            break;
                                    }
                                    $i++;
                                }
                            } else {
                                $dataJson['results'] = [];
                            }

                            //$dataJson['results']['dataProyek'] = $data['dataProyek'];
                            //$data=json_encode($data['results']);
                            //$data = $data['results'];
                            $code = 2;
                            break;
                        case 'insert':
                            $resul = $DB->insert($tabel_pakai, $set);
                            $code = 2;
                            break;
                        case 'buatTabel':
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
                            //var_dump($get_data);
                            //sesuaikan rumus
                            switch ($jenis) {
                                case 'analisa_alat':
                                    switch ($tbl) {
                                        case 'edit':
                                            //===================
                                            //hitung kembali 
                                            //ambil data suku bunga i dari informasi umum
                                            $sukuBunga_i = $DB->getWhereArray("informasi_umum", [['kd_proyek', '=', $kd_proyek], ['kode', '=', 'suku_bunga_i', 'AND']]);
                                            $sukuBunga_i = get_object_vars($sukuBunga_i[0])["nilai"];
                                            //bbm solar="M21", pelumas="M22",Upah Operator / Sopir="L04",Upah Pembantu Operator / Pmb.Sopir="L05"
                                            $bbmUpahsql = $DB->getQuery("SELECT * FROM harga_sat_upah_bahan WHERE kd_proyek =? AND (kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ?)", [$kd_proyek, 'M21', 'M22', 'm21', 'm22', 'L04', 'L05', 'l04', 'l5']);
                                            $bbmUpah = [];
                                            $M21 = 0;
                                            $M22 = 0;
                                            $L04 = 0;
                                            $L05 = 0;
                                            if (sizeof($bbmUpahsql) > 0) {
                                                foreach ($bbmUpahsql as $row) {
                                                    $namaVal = $row->kode;
                                                    $Valueku = $row->harga_satuan;
                                                    $bbmUpah[] = ["$namaVal" => $Valueku]; //ok
                                                    "$" . $namaVal = $Valueku;
                                                    //var_dump("$" . $namaVal);
                                                    //var_dump("$namaVal");
                                                    switch ($row->kode) {
                                                        case 'm21':
                                                        case 'M21':
                                                            $M21 = $row->harga_satuan;
                                                            break;
                                                        case 'm22':
                                                        case 'M22':
                                                            $M22 = $row->harga_satuan;
                                                            break;
                                                        case 'l04':
                                                        case 'L04':
                                                            $L04 = $row->harga_satuan;
                                                            break;
                                                        case 'l05':
                                                        case 'L05':
                                                            $L05 = $row->harga_satuan;
                                                            break;
                                                    }
                                                }
                                            } else {
                                                $data['note'] = 'bbm dan upah sopir tidak ditemukan';
                                            }
                                            //var_dump($get_data);
                                            $hitungKembali = $Fungsi->analisaAlat($kd_proyek, $get_data[0], $sukuBunga_i, $M22, $M21, $L04, $L05);
                                            //var_dump($hitungKembali);
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
                            //var_dump($get_data);
                            //$dataobjecttoarray = json_decode(json_encode($data['dataProyek']), TRUE);
                            $dataTabel = $Fungsi->getTabel($jenis, $tbl, $get_data, $jmlhalaman, $halaman, $jumlah_kolom, $type_user);
                            //var_dump($dataTabel);
                            $data = array_merge($dataTabel, $data);
                            break;
                        case 'buatList':
                            switch ($jenis) {
                                case 'analisa_quarry_x':
                                    //UNTUK DISTINCT
                                    //$query = "SELECT DISTINCT {$columnName} FROM {$tableName} WHERE {$queryArray} ";
                                    if ($limit !== "all") {
                                        if ($cari != '') {
                                            //var_dump($data_like);
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE ($like)");
                                            $get_data = $DB->getQuery("SELECT DISTINCT $klmDISTINCT FROM $tabel_pakai WHERE ($like)", $data_like);
                                        } else {
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE $where1");
                                            $get_data = $DB->getQuery("SELECT DISTINCT $klmDISTINCT FROM $tabel_pakai WHERE $where1", $data_where1);
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
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE ($like) $order ");
                                            $get_data = $DB->getQuery("SELECT DISTINCT $klmDISTINCT FROM $tabel_pakai WHERE ($like) $order ", $data_like, [$posisi, $limit]);
                                        } else {
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE $where1 $order ");
                                            $get_data = $DB->getQuery("SELECT DISTINCT $klmDISTINCT FROM $tabel_pakai WHERE $where1 $order ", $data_where1, [$posisi, $limit]);
                                        }
                                    } else {
                                        if ($cari != '') {
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE ($like) $order");
                                            $get_data = $DB->getQuery("SELECT DISTINCT $klmDISTINCT FROM $tabel_pakai WHERE ($like) $order", $data_like);
                                        } else {
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE $where1 $order ");
                                            $get_data = $DB->getQuery("SELECT DISTINCT $klmDISTINCT FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
                                        }
                                    }
                                    break;
                                case 'analisa_quarry':
                                case 'analisa_bm':
                                case 'analisa_sda':
                                    //case 'analisa_ck':
                                    //UNTUK DISTINCT
                                    //$query = "SELECT DISTINCT {$columnName} FROM {$tableName} WHERE {$queryArray} ";
                                    if ($limit !== "all") {
                                        if ($cari != '') {
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like)", $data_like);
                                        } else {
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1", $data_where1);
                                        }
                                        //getQuery($query, $bindValue = [], $limit = [])
                                        //var_dump($get_data);
                                        $jumlahArray = is_array($get_data) ? count($get_data) : 0;
                                        if ($jumlahArray > 0) {
                                            $jmldata = sizeof($get_data); //$get_data->fetchColumn();
                                        } else {
                                            $jmldata = 0; //$get_data->fetchColumn();
                                        }
                                        $jmlhalaman = ceil($jmldata / $limit);
                                        //var_dump($get_data);
                                        //var_dump('jmldata = '.$jmldata);
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
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE $where1 $order ");
                                            //$get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai WHERE $where1 $order LIMIT $posisi, $limit", [$posisi, $limit]);
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1, [$posisi, $limit]);
                                        }
                                    } else {
                                        if ($cari != '') {
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE ($like) $order");
                                            //$get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai WHERE ($like) $order ");
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order", $data_like);
                                        } else {
                                            //var_dump("SELECT DISTINCT FROM $tabel_pakai WHERE $where1 $order ");
                                            //$get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai WHERE $where1 $order ");
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
                                        }
                                    }
                                    //$get_data = $get_data[0];
                                    break;
                                default:
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
                                            //var_dump("SELECT * FROM $tabel_pakai WHERE ($like) $order");
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order", $data_like);
                                        } else {
                                            //var_dump("SELECT * FROM $tabel_pakai WHERE $where1 $order ");
                                            $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
                                        }
                                    }
                                    break;
                            }
                            //var_dump($jmlhalaman);
                            $dataTabel = $Fungsi->getList($jenis, $tbl, $get_data, $jmlhalaman, $halaman);
                            $data = array_merge($dataTabel, $data);
                            break;
                        case 'buatDropdown':
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
                    $hasilServer = [$code => 'Validasi Kembali :<br>' . $keterangan];
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
                        $item = array('code' => $code, 'message' => $hasilServer[$code]);
                        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
                        break;
                }
                break;

            case 'sbu':
            case 'analisa_quarry':
            case 'satuan':
                switch ($tbl) {
                    case 'sda':
                    case 'ck':
                    case 'bm':
                    case 'get':
                        $json = array('success' => $sukses,  'results' => $dataJson['results'],  'data' => $data);
                        break;
                    default:
                        $item = array('code' => $code, 'message' => $hasilServer[$code]);
                        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
                        break;
                }
                break;
            default:
                $item = array('code' => $code, 'message' => $hasilServer[$code]);
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
        //     $item = array('code' => $code, 'message' => $hasilServer[$code]);
        //     $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        //     return json_encode($json); //json_last_error();
        // }
    }
}
