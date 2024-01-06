<?php
class get_data
{
    public function get_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];

        $id_user = $_SESSION["user"]["id"];
        $keyEncrypt = $_SESSION["user"]["key_encrypt"];
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
                switch ($tbl) {
                    case 'peraturan':
                        $like = "disable <= ? AND (judul LIKE CONCAT('%',?,'%') OR bentuk_singkat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nomor LIKE CONCAT('%',?,'%'))";
                        $data_like = [0, $cari, $cari, $cari, $cari];
                        $order = "ORDER BY tgl_pengundangan ASC";
                        $posisi = " LIMIT ?, ?";
                        $where1 = "disable <= ?";
                        $data_where1 =  [0];
                        $jumlah_kolom = 4;
                        break;
                    case 'sbu':

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
                        $err = 6;
                }
                //FINISH PROSES VALIDASI
                $Fungsi = new MasterFungsi();
                if ($validate->passed()) {
                    //tabel pakai
                    switch ($tbl) {
                        case 'peraturan':
                            $tabel_pakai = 'peraturan_neo';
                            break;
                        case "akun":
                            $tabel_pakai = 'akun_neo';
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
                    $sukses = true;
                    $err = 0;
                    
                    $jumlah_kolom = 0;
                    // $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);

                    //$tahun_anggaran = 0;
                    // $jumlahArray = is_array($data_kd_proyek) ? count($data_kd_proyek) : 0;

                    //================================================
                    //==========JENIS POST DATA/INSERT DATA===========
                    //================================================
                    switch ($jenis) {
                        case 'get_row': //  ambil data 1 baris 
                            $resul = $DB->getQuery("SELECT $kolom FROM $tabel_pakai WHERE $where1", $data_where1);
                            //var_dump($resul[0]);
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            if ($jumlahArray > 0) {
                                $code = 202; //202
                                $data['users'] = $resul[0];
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
                            $dataTabel = $Fungsi->getTabel($tbl, $tbl, $get_data, $jmlhalaman, $halaman, $jumlah_kolom, $type_user);
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
