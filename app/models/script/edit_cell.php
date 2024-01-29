<?php
class Edit_cell
{
    public function edit_cell()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $id_user = $_SESSION["user"]["id"];
        $btn_edit = '';
       
        $DB = DB::getInstance();
        $Fungsi = new MasterFungsi();
        //$data_user = $DB->getQuery( 'SELECT * FROM user_ahsp WHERE id = ?', [ $id_user ] );
        $tahun = $_SESSION["user"]["thn_aktif_anggaran"];
        $kd_proyek = '';
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
            if (isset($_POST['jenis'])) {
                $validate = new Validate($_POST);
                $tbl = $validate->setRules('tbl', 'tbl', [
                    'sanitize' => 'string',
                    'required' => true,
                    'min_char' => 1,
                    'max_char' => 100
                ]);
                $klm = $validate->setRules('klm', 'klm', [
                    'sanitize' => 'string',
                    'required' => true,
                    'min_char' => 1,
                    'max_char' => 100
                ]);

                switch ($klm) {
                    case 'aktif_chat':
                    case 'aktif_edit':
                    case 'aktif': //untuk user
                        $txt = $validate->setRules('txt', 'on off', [
                            'in_array' => ["0", "1"]
                        ]);
                        break;
                    default:
                        $txt = $validate->setRules('txt', 'txt', [
                            'sanitize' => 'string',
                            //'required' => true,
                            'max_char' => 400
                        ]);
                        break;
                }
                $ubah = $validate->setRules('ubah', 'ubah txt cell', [
                    'sanitize' => 'string',
                    'required' => true,
                    'min_char' => 1
                ]);
                $jenis = $validate->setRules('jenis', 'Jenis', [
                    'sanitize' => 'string',
                    'required' => true,
                    'min_char' => 1,
                    'max_char' => 200
                ]);
                $id = $validate->setRules('id', 'id row', [
                    'required' => true,
                    'numeric' => true,
                    'min_char' => 1
                ]);
                //================
                //PROSES VALIDASI
                //================
                switch ($tbl) {
                    case 'analisa_quarry':
                    case 'analisa_alat':
                    case 'analisa_ck':
                    case 'analisa_sda':
                    case 'analisa_alat_custom':
                        if ($ubah != 'non') {
                            $dataArray = $validate->setRules('dataArray', 'dataArray', [
                                'required' => true,
                                'min_char' => 1
                            ]);
                            $dataArray = json_decode($dataArray);
                        }
                        break;
                    case 'informasi_umum':
                        break;
                    case 'analisa_alat':
                        break;
                    case 'analisa_alat_custom':
                        break;
                    case 'analisa_quarry':
                        break;
                    default:
                }
                //FINISH PROSES VALIDASI
                $kodePosting = '';
                if ($validate->passed()) {
                    //tabel pakai
                    switch ($tbl) {
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
                        case 'tabel_harga_satuan':
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
                        case "monev[informasi]":
                        case "monev[realisasi]":
                        case "monev[laporan]":
                            $tabel_pakai = 'monev';
                            break;
                        case 'lap-harian':
                            $tabel_pakai = 'laporan_harian';
                            break;
                        case 'profil':
                            $tabel_pakai =  'user_ahsp';
                            break;
                        default:
                    }
                    $sukses = true;
                    $code = 1;
                    $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);
                    $kd_proyek = $data_kd_proyek[0]->kd_proyek_aktif;
                    $caraUpdate = '';
                    //var_dump('resul'.$resul);
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
                    $jumlahArray = is_array($bbmUpahsql) ? count($bbmUpahsql) : 0;
                    if ($jumlahArray > 0) {
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
                    switch ($tbl) {
                        case 'schedule':
                        case 'tabel_schedule':
                            $kondisiOnce = ['id', '=', $id];
                            $kondisiUpdateArray = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                            $dataColumn = [
                                $klm => $txt
                            ];
                            $caraUpdate = 'standar';
                            $caraUpdate = 'standar';
                            break;
                        case 'proyek':
                            break;
                        case 'tabel_harga_satuan':
                        case 'harga_satuan':
                            $kondisiOnce = ['id', '=', $id];
                            $kondisiUpdateArray = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                            $dataColumn = [
                                $klm => $txt
                            ];
                            $caraUpdate = 'standar';
                            break;
                        case 'analisa_alat_custom':
                        case 'analisa_quarry':
                        case 'analisa_bm':
                        case 'analisa_ck':
                        case 'analisa_sda':
                            //menentukan $jenisImporData = 'koef_harga';
                            if ($ubah != 'non') {
                                switch ($tbl) {
                                    case 'analisa_sda':
                                    case 'analisa_bm':
                                        $jenisImporData = 'koef_harga';
                                        break;
                                    case 'analisa_quarry':
                                        $jenisImporData = 'koef_harga';
                                        break;
                                    case 'analisa_alat_custom':
                                        $jenisImporData = 'koef';
                                        break;
                                    case 'analisa_ck':
                                        $jenisImporData = 'harga';
                                        break;
                                    default:
                                        break;
                                }
                                //start
                                $idKlmSamaAnalisa = 'NAN';
                                $idKlmSamaDenganAnalisa = 'NAN';
                                $no_sortir_max = 0;
                                //ambil data kd_analisa dan lainnya
                                $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                                $DB->orderBy('no_sortir');
                                $result = $DB->getWhereCustom($tabel_pakai, $condition);
                                $jumlahArray = is_array($result) ? count($result) : 0;
                                if ($jumlahArray > 0 && count((array)$dataArray) > 0) {
                                    $kd_analisa = $result[0]->kd_analisa;
                                    //var_dump($result[0]->kd_analisa);
                                    // mulai
                                    $condition = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND']];
                                    $DB->orderBy('no_sortir');
                                    $result = $DB->getWhereCustom($tabel_pakai, $condition);
                                    //var_dump($result);
                                    $keys = array_column($result, 'id'); // kumpulkan key 
                                    $id_delete = []; //kumpulan row dihapus
                                    $id_delete = array_column($result, 'id'); //kumpulan row dihapus
                                    $dataArrayHasil = [];
                                    //var_dump($dataArray);
                                    foreach ($dataArray as $key => $val) {
                                        $idKlmSamaAnalisa = 'NAN';
                                        $keyResult = array_search($key, $keys);
                                        if ($keyResult !== NULL) {
                                            unset($id_delete[$keyResult]);
                                        }
                                        $nomor_id = $key;
                                        $val->id = $nomor_id;
                                        $val->kd_proyek = $kd_proyek;
                                        $val->kd_analisa = $kd_analisa;

                                        //cari id kolom nomor == kd_analisa jika tidak ditemukan tambahakan
                                        switch ($tbl) {
                                            case 'analisa_ck':
                                                if ($val->kode == $kd_analisa) {
                                                    $idKlmSamaAnalisa = $val->id;
                                                }
                                                break;
                                            default:
                                                if ($val->nomor == $kd_analisa) {
                                                    $idKlmSamaAnalisa = $val->id;
                                                }
                                        }
                                        if ($idKlmSamaAnalisa != 'NAN') {
                                            $idKlmSamaDenganAnalisa = 1;
                                            switch ($jenis) {
                                                case 'analisa_ck':
                                                    $val->jenis_kode = 'summary:' . $kd_analisa;
                                                    break;
                                                case 'analisa_quarry':
                                                    $val->keterangan = '{"lokasi":"' . $lokasiQuarry . '", "tujuan":"' . $tujuanQuarry . '"}';
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }
                                        $dataArrayHasil[] = $val;
                                        $no_sortir = $val->no_sortir;
                                        $no_sortir_max = ($no_sortir_max > $no_sortir) ? $no_sortir_max : $no_sortir;
                                    }
                                    if ($idKlmSamaDenganAnalisa == 'NAN') {
                                        $jenis_kodeInsert = '';
                                        $keteranganInsert = '';
                                        switch ($tbl) {
                                            case 'analisa_ck':
                                                $jenis_kode = 'summary:' . $kd_analisa;
                                                break;
                                            case 'analisa_alat_custom':
                                                $keteranganInsert = '{"lokasi":"' . $lokasiQuarry . '", "tujuan":"' . $tujuanQuarry . '"}';;
                                                break;
                                            default:
                                                break;
                                        }
                                        $dataInsert = [
                                            'kd_proyek' => $kd_proyek,
                                            'kd_analisa' => $kd_analisa,
                                            'kode' => $kd_analisa,
                                            'nomor' => $kd_analisa,
                                            'uraian' => $jenis_pek,
                                            'koefisien' => 0,
                                            'satuan' => '',
                                            'harga_satuan' => 0,
                                            'jumlah_harga' => 0,
                                            'jenis_kode' => $jenis_kodeInsert,
                                            'keterangan' => $keteranganInsert,
                                            'no_sortir' => $no_sortir_max + 1
                                        ];
                                        $dataArrayHasil[] = $dataInsert;
                                    }
                                    
                                    //var_dump($hasil);
                                    $jumlahArray = is_array($hasil) ? count($hasil) : 0;
                                    if ($jumlahArray) {
                                        $caraUpdate = 'update_analisa';
                                    } else {
                                        $code = 41;
                                    }
                                }
                            } else {
                                //ubah cell tanpa rumus
                                $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                                $DB->orderBy('no_sortir');
                                $result = $DB->getWhereCustom($tabel_pakai, $condition);
                                $jumlahArray = is_array($result) ? count($result) : 0;
                                if ($jumlahArray) {

                                    $kondisiUpdateArray = [['id', '=', $id]];
                                    $dataColumn = [
                                        $klm => $txt
                                    ];
                                    $kondisiOnce = ['id', '=', $id];
                                    $caraUpdate = 'standar';
                                } else {
                                    $code = 404;
                                }
                            }


                            break;
                        case 'informasi_umum':
                            break;
                        case 'analisa_alat':
                            switch ($jenis) {
                                case 'update_row':
                                    if ($ubah != 'non') {
                                        $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                                        $result = $DB->select_array($tabel_pakai, $condition);
                                        //var_dump($dataArray);
                                        foreach ($dataArray as $key => $val) {
                                            //var_dump($key . ' ' . $val);
                                            $result[0]->{$key} = $val;
                                        }
                                        // //var_dump($result[0]);
                                        // $data = $Fungsi->analisaAlat($kd_proyek, $result[0], $sukuBunga_i, $M22, $M21, $L04, $L05);
                                        // if (count($data[0]) > 0) {
                                        //     $code =  200;
                                        // } else {
                                        //     $code = 405;
                                        // }
                                    } elseif ($ubah == 'non') {
                                        $kondisiOnce = ['id', '=', $id];
                                        $kondisiUpdateArray = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                                        $dataColumn = [
                                            $klm => $txt
                                        ];
                                        $caraUpdate = 'standar';
                                    }

                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'rab':
                            break;
                        case 'tabel_satuan':
                        case 'satuan':
                            if ($type_user === 'admin') {
                                $kondisiOnce = ['id', '=', $id];
                                $kondisiUpdateArray = [['id', '=', $id]];
                                $dataColumn = [
                                    $klm => $txt
                                ];
                                $caraUpdate = 'standar';
                            } else {
                                $code = 2001;
                            }
                            break;
                        case 'tabel_informasi':
                            $kondisiOnce = ['id', '=', $id];
                            $kondisiUpdateArray = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                            $dataColumn = [
                                $klm => $txt
                            ];
                            $caraUpdate = 'standar';
                            break;
                        case 'profil':
                            $kondisiUpdateArray = [['id', '=', $id]];
                            $dataColumn = [
                                $klm => $txt
                            ];
                            $caraUpdate = 'standar';
                            $kondisiOnce = ['id', '=', $id];
                            break;
                        default:
                            $code = 6;
                    }
                    // update cell standar
                    switch ($caraUpdate) {
                        case 'update_analisa': // untuk analisa
                            //var_dump($hasil);
                            //hapus id row yang terdapat di $id_delete (kumpulan row dihapus)
                            foreach ($id_delete as $key_del => $val_del) {
                                $condition = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['id', '=', $val_del, 'AND']];;
                                $resul_del = $DB->delete_array($tabel_pakai, $condition);
                            }
                            //rekam ulang/update
                            foreach ($hasil as $key => $val) {
                                $id_rows = $val['id'];
                                $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['id', '=', $id_rows, 'AND']];
                                //cari id klo ditemukan update kalo tidak ditemukan insert baru
                                //$set = unset($val["id"], $val["kd_proyek"], $val["key3"]);
                                $val_update = $val;
                                unset($val_update["id"], $val_update["kd_proyek"], $val_update["kd_analisa"]);
                                $set = $val_update;
                                //var_dump($set);
                                $sumRows = $DB->getWhereArray($tabel_pakai, $kondisi);
                                //var_dump($sumRows);
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
                                                $kondisi2 = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kd_analisa, 'AND'], ['keterangan', '=', 'analisa_alat_custom', 'AND']];
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
                                        $data['add_row'][] = $sum;
                                        //jika nomor=kd_analisa
                                    } else {
                                        $data['NA'][] = $id_rows;
                                    }
                                }
                            }
                            break;
                        case 'standar':
                            $result = $DB->getWhereOnce($tabel_pakai, $kondisiOnce);
                            if ($result) {
                                $hasil = $DB->update_array($tabel_pakai, $dataColumn,  $kondisiUpdateArray);
                                $code = 3;
                                switch ($tbl) {
                                    case 'tabel_harga_satuan':
                                    case 'harga_satuan':
                                        //update analisa dan quarry/alat
                                        if ($ubah != 'non') {
                                            
                                        }
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            } else {
                                $code = 36;
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
                    $hasilServer = [$code => 'Validasi Data yang dikirim :' . $keterangan];
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
