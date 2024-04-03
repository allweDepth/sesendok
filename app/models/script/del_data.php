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
        $dataJson['results'] = [];
        if ($rowUsername != false) {
            foreach ($rowUsername as $key => $value) {
                ${$key} = $value;
            }
            $tahun = (int) $rowUsername->tahun;
            $kd_wilayah = $rowUsername->kd_wilayah;
            $kd_opd = $rowUsername->kd_organisasi;
            $id_user = $rowUsername->id;
            $rowPengaturan = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
            if ($rowPengaturan !== false) {
                foreach ($rowPengaturan as $key => $value) {
                    ${$key} = $value;
                }
            } else {
                // $id_user = 0;
                // $code = 407;
            }
        } else {
            $id_user = 0;
            $code = 407;
        }
        $rowTahunAktif = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
        // var_dump($rowTahunAktif);
        $group_by = "";
        if ($rowTahunAktif !== false) {
            foreach ($rowTahunAktif as $key => $value) {
                ${$key} = $value;
            }
            $tahun_pengaturan = $rowTahunAktif->tahun;
        } else {
            $id_peraturan = 0;
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
                            case "hspk":
                            case "ssh":
                            case "sbu":
                            case "asb":
                            case 'peraturan':
                            case 'wilayah':
                                //must admin
                                if ($type_user !== 'admin') {
                                    $andabukanadmin = $validate->setRules('nabiilainayah_bilang_anda_bukan_admin', 'anda bukan admin', [
                                        'error' => true
                                    ]);
                                }
                            case 'rekanan':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            default:
                                $adaerror = $validate->setRules('bbbb', 'anda tidak diijinkan melakukan hapus data', [
                                    'error' => true,
                                ]);
                                break;
                        }
                        break;
                    case 'reset':
                    case 'del_all':
                        if ($type_user !== 'admin') {
                            $andabukanadmin = $validate->setRules('nabiilainayah_bilang_anda_bukan_admin', 'anda bukan admin', [
                                'error' => true
                            ]);
                        }
                    default:
                        if ($type_user !== 'admin') {
                            $andabukanadmin = $validate->setRules('bukantempatandadelete', 'anda tidak diijinkan melakukan hapus data', [
                                'error' => true,
                            ]);
                        }
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
                            switch ($tbl) {
                                case "hspk":
                                case "ssh":
                                case "sbu":
                                case "asb":
                                    $kondisi = [['id', '=', $id_row], ['kd_wilayah', '=', $kd_wilayah, 'AND']];
                                    break;
                                case 'wilayah':
                                    $kondisi = [['id', '=', $id_row]];
                                    break;
                                case 'wall':
                                case 'chat':
                                    $kondisi = [['id', '=', $id_row], ['id_reply', '=', $id_row, 'OR']];
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            $code = 406;
                            break;
                    }

                    //===========================
                    // jika isi tbl === del_row
                    //===========================
                    //JENIS POST DATA/INSERT DATA
                    switch ($kodePosting) {
                        case 'del_row':
                            switch ($tbl) {
                                case 'sbu':
                                case 'ssh':
                                case 'asb':
                                case 'hspk':
                                case 'wilayah':
                                    //standar delete id
                                    $data_row = $DB->getWhereCustom($tabel_pakai, $kondisi);
                                    $data[$tbl] = $DB->delete_array($tabel_pakai, $kondisi);
                                    if ($DB->count() > 0) {

                                        $code = 4;
                                        switch ($tbl) {
                                            case 'daftar_kontrak':

                                                break;
                                            default:
                                                break;
                                        };
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'reset':
                            if ($tabel_pakai) {
                                $resul = $DB->runQuery2("TRUNCATE TABLE $tabel_pakai");
                                switch ($tbl) {
                                    case 'daftar_kontrak':
                                        $tabel_pakai = $Fungsi->tabel_pakai('uraian_paket')['tabel_pakai'];
                                        $DB->runQuery2("TRUNCATE TABLE $tabel_pakai");
                                        $tabel_pakai = $Fungsi->tabel_pakai('realisasi')['tabel_pakai'];
                                        $DB->runQuery2("TRUNCATE TABLE $tabel_pakai");
                                        break;
                                    default:
                                        break;
                                };
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
                                    switch ($tbl) {
                                        case 'daftar_kontrak':
                                            $DB->runQuery2("DELETE FROM daftar_uraian_paket");
                                            break;
                                        default:
                                            break;
                                    };
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
                    $code = 39;
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
