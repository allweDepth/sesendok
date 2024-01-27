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
                    $kolom = '*';
                    $sukses = true;
                    $err = 0;
                    $kodePosting = '';
                    switch ($jenis) {
                        case 'get_tbl':
                            $kodePosting = 'get_tbl';
                            break;
                        case 'get_data':
                            $where1 = "nomor = ?";
                            $data_where1 =  [$text];
                            break;
                        case 'edit':
                            $kodePosting = 'get_data';
                            $where1 = "id = ?";
                            $data_where1 =  [$id_row];
                            break;
                        default:
                            #code...
                            break;
                    };
                    switch ($tbl) {
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
                            $like = "disable <= ? AND(judul LIKE CONCAT('%',?,'%') OR bentuk_singkat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nomor LIKE CONCAT('%',?,'%'))";
                            $data_like = [0, $cari, $cari, $cari, $cari];
                            $order = "ORDER BY kode ASC";
                            $posisi = " LIMIT ?, ?";
                            $where1 = "disable <= ?";
                            $data_where1 =  [0];
                            // $where = "nomor = ?";
                            // $data_where =  [$text];
                            $jumlah_kolom = 7;
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
                            $resul = $DB->getQuery("SELECT $kolom FROM $tabel_pakai WHERE $where1", $data_where1);
                            //var_dump($resul);
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            if ($jumlahArray > 0) {
                                $code = 202; //202
                                $data['users'] = $resul[0];
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
