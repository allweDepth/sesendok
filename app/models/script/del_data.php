<?php
class del_data
{
    public function del_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $id_user = $_SESSION["user"]["id"];
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
        //ambil row user
        $rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
        if ($rowUsername != false) {
            foreach ($rowUsername as $key => $value) {
                ${$key} = $value;
            }
            $tahun = (int) $rowUsername->tahun;
            $kd_wilayah = $rowUsername->kd_wilayah;
            $kd_opd = $rowUsername->kd_organisasi;
            $id_user = $rowUsername->id;
            $rowPengaturan = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
            if ($rowPengaturan != false) {
                foreach ($rowPengaturan as $key => $value) {
                    ${$key} = $value;
                }
            } else {
                $id_user = 0;
                $code = 407;
            }
        } else {
            $id_user = 0;
            $code = 407;
        }

        if (!empty($_POST) && $id_user > 0 && $code != 407) {
            $code = 11;
            if (isset($_POST['jenis'])) {
                $code = 12;
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
                //================
                //PROSES VALIDASI
                //================
                switch ($jenis) {
                    case 'del_row':
                        switch ($tbl) {
                            case 'rekanan':
                            case 'peraturan':
                                $id_row = $validate->setRules('id_row', 'id_row', [
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
                    default:
                        break;
                }
                //FINISH PROSES VALIDASI
                $kodePosting = '';
                if ($validate->passed()) {
                    //tabel pakai
                    $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                    $code = 10;
                    $sukses = true;

                    $columnName = "*";
                    switch ($tbl) {
                        case 'wall':
                        case 'chat':
                            switch ($jenis) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_row':
                                    $kondisi = [['id', '=', $id_row], ['id_reply', '=', $id_row, 'OR']];
                                    $kodePosting = 'del_row';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'rekanan':
                            switch ($jenis) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                case 'del_row':
                                    $kodePosting = 'del_row';
                                    $kondisi = [['id', '=', $id_row]];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        default:
                            switch ($jenis) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                case 'del_row':
                                    $kodePosting = 'del_row';
                                    $kondisi = [['id', '=', $id_row]];
                                    break;
                                default:
                                    $code = 406;
                                    break;
                            }
                            break;
                    }
                    //===========================
                    // jika isi tbl === del_row
                    //===========================
                    //JENIS POST DATA/INSERT DATA
                    switch ($kodePosting) {
                        case 'del_row':
                            switch ($jenis) {
                                case 'monev':
                                    //1.ambil data
                                    $ambil_data = $DB->getWhere($tabel_pakai, ['id', '=', $id_row]);
                                    $id_rab = $ambil_data[0]->id_rab;
                                    $ambil_data = $DB->getWhere('rencana_anggaran_biaya', ['id', '=', $id_rab]);
                                    $kd_analisa_del = $ambil_data[0]->kd_analisa; //kode proyek yang ingin dihapus
                                    //2.delete row
                                    $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisi);
                                    if ($DB->count() > 0) {
                                        //3.
                                        $code = 4;
                                        //update laporan harian
                                        // $condition = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa_del, 'AND']];
                                        // $data['lap_harian'] = $DB->delete_array('laporan_harian', $condition);
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                                default:
                                    //standar delete id
                                    $data_row = $DB->getWhereCustom($tabel_pakai, $kondisi);
                                    // var_dump($data_row);
                                    $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisi);
                                    if ($DB->count() > 0) {
                                        $code = 4;
                                        switch ($tbl) {
                                            case 'peraturan':
                                                // $file = $Fungsi->importFile($jenis, $kd_proyek = '');
                                                // if ($file['result'] == 'ok') {
                                                //     $set['photo'] = $file['file'];
                                                // }
                                                break;
                                            case 'value1':
                                                #code...
                                                break;
                                            default:
                                                #code...
                                                break;
                                        };
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                            }
                            break;
                        case 'reset':
                            if ($tabel_pakai) {
                                $resul = $DB->runQuery2("TRUNCATE TABLE $tabel_pakai");
                                //var_dump($resul);
                                $code =  4;
                            } else {
                                $code = 45;
                            };
                            break;
                        case 'delete_all_table':
                            if ($tabel_pakai) {
                                $resul = $DB->runQuery2("DELETE FROM $tabel_pakai");
                                //var_dump($resul);
                                if ($resul) {
                                    $code = 2;
                                } else {
                                    $code = 46;
                                };
                            } else {
                                $code = 45;
                            };
                            break;
                        case 'delete_all_proyek':
                            if ($tabel_pakai) {
                                $resul = $DB->delete_array($tabel_pakai, $kondisi);
                                //var_dump($resul);
                                if ($resul) {
                                    $code = 2;
                                } else {
                                    $code = 46;
                                };
                            } else {
                                $code = 45;
                            };
                            break;
                        default:
                            # code...
                            break;
                    }
                } else {
                    $err = 29;
                    $pesan = $validate->getError();
                    $data['error_validate'][] = $pesan;
                }
            } else {
                $pesan = 'tidak didefinisikan';
                $code = 39;
            }
        }
        $item = array('code' => $code, 'message' => hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
    }
}
