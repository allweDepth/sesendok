<?php

use Shuchkin\SimpleXLSX;
use FormulaParser\FormulaParser;

class Impor_xlsx
{
    public function import_xlsx()
    {
        include_once("class/SimpleXLSX.php");
        require_once("class/FormulaParser.php"); //__DIR__ . '/vendor/autoload.php';
        require 'init.php';
        //menampilkan error
        //error_reporting(E_ALL & E_STRICT);
        //ini_set('display_errors', '1');
        //ini_set('log_errors', '0');
        //ini_set('error_log', './');
        //end menampilkan error
        $user = new User();
        $user->cekUserSession();
        //math
        $type_user = $_SESSION["user"]["type_user"];
        $id_user = $_SESSION["user"]["id"];
        $DB = DB::getInstance();
        $Fungsi = new MasterFungsi();
        $kd_proyek = '';
        $type_user = $_SESSION["user"]["type_user"];
        $username = $_SESSION["user"]["username"];
        $status = $_SESSION["user"]["type_user"];
        $sukses = false;
        $code = 40;
        $pesan = 'posting kosong';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        $tambahan_pesan = '';
        //$data['note'] = [];
        //$data['add_row'] = [];
        //$data['row_update'] = [];
        //$data['gagal'] = [];
        //$data['note'][] = 'row update';
        //$data['note'][] = 'add row';
        //$data['note'][] = 'gagal';
        if (!empty($_POST) && $id_user > 0 && !empty($_FILES["file"]["name"])) {
            if (isset($_POST['jenis'])) {
                $targetDir = "uploads/";
                $fileName = basename($_FILES["file"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                $allowTypes = array('xlsx');
                if (in_array($fileType, $allowTypes)) {
                    if ($_FILES["file"]["size"] > 0) {
                        $validate = new Validate($_POST);
                        //$kd_proyek = 'master'; diambil dari tabel username
                        $jml_header = $validate->setRules('jml_header', 'jml_header', [
                            'required' => true,
                            'numeric' => true
                        ]);
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
                        switch ($tbl) {
                            case 'divisi':
                                $tahun = $validate->setRules('tahun', 'Tahun', [
                                    'required' => true,
                                    'numeric' => true,
                                    'max_char' => 4
                                ]);
                                $bidang = $validate->setRules('bidang', 'Bidang', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 3,
                                    'in_array' => ['bm', 'ck', 'sda']
                                ]);
                                //var_dump('disini');
                                break;
                            case 'satuan':
                                /*$tahun = $validate->setRules('tahun', 'Tahun', [
                            'required' => true,
                            'numeric' => true
                        ]);*/
                                break;
                            case 'analisa_bm':
                                $kd_analisa = $validate->setRules('kd_analisa', 'Kode Analisa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                $jenis_pek = $validate->setRules('jenis_pek', 'Jenis', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 200
                                ]);
                                $satuan_pembayaran = $validate->setRules('satuan_pembayaran', 'Satuan Pembayaran', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 200
                                ]);
                                break;
                            default:
                                break;
                        }
                        $kodePosting = '';
                        if ($validate->passed()) {
                            // mulai proses impor
                            $filename = $_FILES["file"]["tmp_name"];
                            if ($xlsx = SimpleXLSX::parse($filename)) {

                                //var_dump($tahun_anggaran);
                                $code = 1;
                                $sukses = true;
                                $RowHeaderValidate = [];
                                //tentukan peraturan yang membutuhkan
                                switch ($tbl) {
                                    case 'akun_belanja':
                                    case 'sub_kegiatan':
                                    case 'asb':
                                    case 'sbu':
                                    case 'ssh':
                                    case 'hspk':
                                    case 'sumber_dana':
                                    case 'sub_keg':
                                        $rowUsername = $DB->getWhereOnce('user_ahsp', ['username', '=', $username]);
                                        //var_dump($rowUsername);

                                        $tahun = (int) $rowUsername->tahun;
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
                                        } else {
                                            $id_peraturan = 0;
                                        }
                                        break;
                                }
                                //menentukan data
                                switch ($tbl) {
                                    case 'peraturan':
                                        $count_col_min = 10;
                                        $tabel_pakai = 'peraturan_neo';
                                        $RowHeaderValidate = ['Type Dok', 'Judul', 'Nomor', 'Bentuk', 'Bentuk singkat', 'Tempat Penetapan', 'Tanggal Penetapan', 'Tanggal Pengundangan', 'Keterangan', 'Status Data'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'sumber_dana':
                                        $count_col_min = 8;
                                        $tabel_pakai = 'sumber_dana_neo';
                                        $RowHeaderValidate = ['Sumber Dana', 'Kelompok', 'Jenis', 'Objek', 'Rincian Objek', 'Sub Rincian Objek', 'Uraian Akun', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'akun_belanja':
                                        $tabel_pakai = 'akun_neo';
                                        $RowHeaderValidate = ['Akun', 'Kelompok', 'Jenis', 'Objek', 'Rincian Objek', 'Sub Rincian Objek', 'Uraian Akun', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'aset':
                                        $tabel_pakai = 'aset_neo';
                                        $RowHeaderValidate = ['Akun', 'Kelompok', 'Jenis', 'Objek', 'Rincian Objek', 'Sub Rincian Objek', 'Uraian Akun', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'sub_keg':
                                        $tabel_pakai = 'sub_kegiatan_neo';
                                        $RowHeaderValidate = ['URUSAN/UNSUR', 'BIDANG URUSAN/BIDANG UNSUR', 'PROGRAM', 'KEGIATAN', 'SUB KEGIATAN', 'NOMENKLATUR URUSAN', 'KINERJA', 'INDIKATOR', 'SATUAN', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'rekanan':
                                        $tabel_pakai = 'rekanan';
                                        $RowHeaderValidate = ['Nama Perusahaan/Pribadi', 'Alamat', 'Email', 'NPWP', 'Nomor Rekening Bank', 'Nama Bank Rekening', 'Atas Nama Bank Rekening', 'Nama Penanggung Jawab', 'Jabatan', 'Nomor KTP', 'Alamat Penanggung Jawab', 'Nomor Akta Pendirian', 'Tanggal Akta Pendirian', 'Lokasi Notaris Pendirian', 'Nama Notaris Pendirian', 'Nomor Akta Perubahan', 'Tanggal Akta Perubahan', 'Lokasi Notaris Perubahan', 'Nama Notaris Perubahan', 'Nama Pelaksana', 'Jabatan Pelaksana', 'KETERANGAN'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'harga_satuan':
                                        $count_col_min = 8;
                                        $tabel_pakai = 'harga_sat_upah_bahan';
                                        $arrayStruktur = ['kd_proyek', 'kode', 'jenis', 'uraian', 'satuan', 'harga_satuan', 'sumber_data', 'spesifikasi', 'keterangan', 'tahun', 'periode', 'status'];
                                        $RowHeaderValidate = ['Jenis', 'Kode', 'Uraian', 'Satuan', 'Harga Satuan', 'Sumber Data', 'Spesifikasi', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'satuan':
                                        $count_col_min = 4;
                                        $tabel_pakai = 'daftar_satuan';
                                        $RowHeaderValidate = ['value', 'item', 'Keterangan', 'Sebutan lain'];
                                        break;
                                    case 'x':
                                        break;
                                    default:
                                }
                                //finish menentukan data
                                $sum = 0;
                                $no_sort = 1;
                                //var_dump($xlsx->rows());
                                $jumlahBaris = sizeof($xlsx->rows());
                                //var_dump();
                                $item['row'] = "";
                                $validateTabel = '';
                                foreach ($xlsx->rows() as $r => $getData) {
                                    $sum++; //kolom
                                    //bisa di
                                    //============================
                                    //PROSES VALIDASI BARIS HEADER
                                    //============================
                                    //validasi row header excel
                                    //var_dump(((int)$jml_header - 1));
                                    if ($r == ($jml_header - 1) && count($RowHeaderValidate) > 0) {
                                        $getData = array_map('strtolower', $getData); //menjadikan huruf kecil semua value
                                        //var_dump($getData);
                                        $validateTabel = new Validate($getData);
                                        //var_dump($validateTabel);
                                        foreach ($RowHeaderValidate as $keyValid => $valueValid) {
                                            $headerValid = preg_replace('/(\s\s+|\t|\n)/', ' ', strtolower($valueValid));
                                            $data_column = $validateTabel->setRules($keyValid, "header kolom $headerValid", [
                                                'sanitize' => 'string',
                                                'required' => true,
                                                'min_char' => 1,
                                                'max_char' => 100,
                                                'in_array' => [$headerValid]
                                            ]);
                                            //var_dump($valueValid);
                                            //var_dump($data_column);
                                        }
                                    }
                                    if ($r > ($jml_header - 1)) {
                                        if ($validateTabel->passed()) {
                                            $validateRow = new Validate($getData);
                                            if ($r >= $jml_header) {
                                                //var_dump($jumlah_kolom);
                                                $jumlah_kolom = is_array($getData) ? count($getData) : 0;
                                                //var_dump($jumlah_kolom);
                                                // $jumlah_kolom = count($getData);
                                                if ($jumlah_kolom >= $count_col_min) {
                                                    //============================
                                                    //PROSES VALIDASI CELL EXCELL
                                                    //============================
                                                    switch ($tbl) {
                                                        case 'peraturan':
                                                            $type_dok = $validateRow->setRules(0, 'type', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'in_array' => ['peraturan_undang_undang_pusat', 'peraturan_menteri_lembaga', 'peraturan_daerah', 'pengumuman', 'artikel', 'lain']
                                                            ]);
                                                            $judul = $validateRow->setRules(1, 'judul', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 4
                                                            ]);
                                                            $nomor = $validateRow->setRules(2, 'nomor', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 4
                                                            ]);
                                                            $bentuk = $validateRow->setRules(3, 'bentuk', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 4
                                                            ]);
                                                            $bentuk_singkat = $validateRow->setRules(4, 'bentuk_singkat', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 4
                                                            ]);
                                                            $t4_penetapan = $validateRow->setRules(5, 'tempat', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 4
                                                            ]);
                                                            $tgl_penetapan = $validateRow->setRules(6, 'tanggal penetapan', [
                                                                'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/',
                                                                'required' => true,
                                                                'max_char' => 100,
                                                                'min_char' => 8
                                                            ]);
                                                            $tgl_penetapan = $Fungsi->tanggal($tgl_penetapan)['tanggalMysql'];
                                                            $tgl_pengundangan = $validateRow->setRules(7, 'tanggal pengundangan', [
                                                                'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/',
                                                                'required' => true,
                                                                'max_char' => 100,
                                                                'min_char' => 8
                                                            ]);
                                                            $tgl_pengundangan  = $Fungsi->tanggal($tgl_pengundangan)['tanggalMysql'];
                                                            $kode  = "$nomor:$tgl_pengundangan";
                                                            $keterangan = $validateRow->setRules(8, 'keterangan', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $status = $validateRow->setRules(9, 'status', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'in_array' => ['rahasia', 'umum', 'proyek']
                                                            ]);

                                                            $arrayDataRows = [
                                                                'kode' => $kode,
                                                                'type_dok' => $type_dok,
                                                                'judul' => $judul,
                                                                'nomor' => $nomor,
                                                                'bentuk' => $bentuk,
                                                                'bentuk_singkat' => $bentuk_singkat,
                                                                't4_penetapan' => $t4_penetapan,
                                                                'tgl_penetapan' => $tgl_penetapan,
                                                                'tgl_pengundangan' => $tgl_pengundangan,
                                                                'disable' => 0,
                                                                'keterangan' => $keterangan,
                                                                'status' => $status,
                                                                'tanggal' => date('Y-m-d H:i:s'),
                                                                'username' => $_SESSION["user"]["username"]
                                                            ];
                                                            $update_arrayData = [['judul', '=', $judul], ['nomor', '=', $nomor, 'AND'], ['tgl_pengundangan', '=', $tgl_pengundangan, 'AND']];
                                                            $getWhereArrayData = [['judul', '=', $judul], ['nomor', '=', $nomor, 'AND'], ['tgl_pengundangan', '=', $tgl_pengundangan, 'AND']];
                                                            $no_sort++;
                                                            break;
                                                        case 'sumber_dana':
                                                            $sumber_dana = $validateRow->setRules(0, 'sumber dana', [
                                                                'required' => true,
                                                                'numeric' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $kode = $sumber_dana;
                                                            $kelompok = $validateRow->setRules(1, 'kelompok', [
                                                                'numeric_zero' => true,
                                                            ]);
                                                            if ($kelompok) {
                                                                $kode .= ".$kelompok";
                                                            }
                                                            $jenis = 0;
                                                            $objek = 0;
                                                            $rincian_objek = 0;
                                                            $sub_rincian_objek = 0;
                                                            if ($kelompok) {
                                                                $jenis = $validateRow->setRules(2, 'jenis', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                if ($jenis) {
                                                                    $kode .= ".$jenis";
                                                                }
                                                                if ($jenis) {
                                                                    $objek = $validateRow->setRules(3, 'objek', [
                                                                        'numeric_zero' => true,
                                                                    ]);
                                                                    if ($objek) {
                                                                        $kode .= "." . $Fungsi->zero_pad($objek, 2);
                                                                    }
                                                                    if ($objek) {
                                                                        $rincian_objek = $validateRow->setRules(4, 'rincian objek', [
                                                                            'numeric_zero' => true,
                                                                        ]);
                                                                        if ($rincian_objek) {
                                                                            $kode .= "." . $Fungsi->zero_pad($rincian_objek, 2);
                                                                        }
                                                                        if ($rincian_objek) {
                                                                            $sub_rincian_objek = $validateRow->setRules(5, 'sub rincian_objek', [
                                                                                'numeric_zero' => true,
                                                                            ]);
                                                                            if ($sub_rincian_objek) {
                                                                                $kode .= "." . $Fungsi->zero_pad($sub_rincian_objek, 2);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }

                                                            $uraian = $validateRow->setRules(6, 'uraian', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $keterangan = $validateRow->setRules(7, 'keterangan', [
                                                                'sanitize' => 'string',
                                                            ]);


                                                            $arrayDataRows = [
                                                                'sumber_dana' => (int)$sumber_dana,
                                                                'kelompok' => (int)$kelompok,
                                                                'jenis' => (int)$jenis,
                                                                'objek' => (int)$objek,
                                                                'rincian_objek' => (int)$rincian_objek,
                                                                'sub_rincian_objek' => (int)$sub_rincian_objek,
                                                                'uraian' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian),
                                                                'peraturan' => $id_aturan_sumber_dana,
                                                                'kode' => $kode,
                                                                'disable' => 0,
                                                                'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                'tanggal' => date('Y-m-d H:i:s'),
                                                                'username' => $_SESSION["user"]["username"]
                                                            ];
                                                            // var_dump($arrayDataRows);
                                                            $update_arrayData = [['kode', '=', $kode]];
                                                            $getWhereArrayData = [['kode', '=', $kode]];
                                                            $no_sort++;
                                                            break;
                                                        case 'aset':
                                                        case 'akun_belanja':
                                                        case 'akun':
                                                            $akun = $validateRow->setRules(0, 'akun', [
                                                                'required' => true,
                                                                'numeric' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $kode = "$akun";
                                                            $kelompok = $validateRow->setRules(1, 'kelompok', [
                                                                'numeric_zero' => true,
                                                            ]);
                                                            if ($kelompok) {
                                                                $kode .= ".$kelompok";
                                                            }
                                                            $jenis = 0;
                                                            $objek = 0;
                                                            $rincian_objek = 0;
                                                            $sub_rincian_objek = 0;
                                                            if ($kelompok) {
                                                                $jenis = $validateRow->setRules(2, 'jenis', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                if ($jenis) {
                                                                    $kode .= "." . $Fungsi->zero_pad($jenis, 2);
                                                                    $objek = $validateRow->setRules(3, 'objek', [
                                                                        'numeric_zero' => true,
                                                                    ]);
                                                                    if ($objek) {
                                                                        $kode .= "." . $Fungsi->zero_pad($objek, 2);
                                                                        $rincian_objek = $validateRow->setRules(4, 'rincian objek', [
                                                                            'numeric_zero' => true,
                                                                        ]);
                                                                        if ($rincian_objek) {
                                                                            $kode .= "." . $Fungsi->zero_pad($rincian_objek, 2);
                                                                            $sub_rincian_objek = $validateRow->setRules(5, 'sub rincian_objek', [
                                                                                'numeric_zero' => true,
                                                                            ]);
                                                                            if ($sub_rincian_objek) {
                                                                                $kode .= "." . $Fungsi->zero_pad($sub_rincian_objek, 4);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $uraian = $validateRow->setRules(6, 'uraian', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $keterangan = $validateRow->setRules(7, 'keterangan', [
                                                                'sanitize' => 'string',
                                                            ]);
                                                            $arrayDataRows = [
                                                                'akun' => (int)$akun,
                                                                'kelompok' => (int)$kelompok,
                                                                'jenis' => (int)$jenis,
                                                                'objek' => (int)$objek,
                                                                'rincian_objek' => (int)$rincian_objek,
                                                                'sub_rincian_objek' => (int)$sub_rincian_objek,
                                                                'uraian' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian),
                                                                'peraturan' => $id_aturan_akun,
                                                                'kode' => $kode,
                                                                'disable' => 0,
                                                                'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                'tanggal' => date('Y-m-d H:i:s'),
                                                                'username' => $_SESSION["user"]["username"]
                                                            ];
                                                            //$string = preg_replace('/\s/', ' ', $string);
                                                            $update_arrayData = [['kode', '=', $kode]];
                                                            $getWhereArrayData = [['kode', '=', $kode]];
                                                            $no_sort++;
                                                            break;
                                                        case 'sub_keg':
                                                            $urusan_temporer = strtolower($getData[0]);
                                                            if ($urusan_temporer == 'x') {
                                                                $urusan = $urusan_temporer;
                                                            } else {
                                                                $urusan = $validateRow->setRules(0, 'urusan', [
                                                                    'required' => true,
                                                                    'numeric' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                            }
                                                            $kode = "$urusan";
                                                            $kd_prov = "$urusan";
                                                            $kd_kab = "$urusan";
                                                            $bidang_temporer = strtolower($getData[1]);
                                                            if ($bidang_temporer == 'xx') {
                                                                $bidang = $bidang_temporer;
                                                            } else {
                                                                $bidang = $validateRow->setRules(1, 'bidang', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                            }
                                                            if ($bidang) {
                                                                $kode .= ".$bidang";
                                                                $kd_prov .= ".$bidang";
                                                                $kd_kab .= ".$bidang";
                                                            }
                                                            $prog = 0;
                                                            $keg = 0;
                                                            $sub_keg = 0;
                                                            if ($bidang) {
                                                                $prog = $validateRow->setRules(2, 'prog', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                if ($prog) {
                                                                    $kode .= ".$prog";
                                                                    $kd_prov .= ".$prog";
                                                                    $kd_kab .= ".$prog";
                                                                    $keg_temporer = $getData[3];
                                                                    // var_dump($keg_temporer);
                                                                    if (strpos($keg_temporer, '.')) {
                                                                        $arrayKeg = explode('.', $keg_temporer);
                                                                        // var_dump($arrayKeg);$sum
                                                                        // var_dump("baris:{$sum}");
                                                                        // var_dump((int) $arrayKeg[1]);
                                                                        $keg = $validateRow->setRules((int)$arrayKeg[1], 'keg', [
                                                                            'numeric' => true,
                                                                        ]);
                                                                    } else {
                                                                        $keg = $validateRow->setRules(3, 'keg', [
                                                                            'numeric_zero' => true,
                                                                        ]);
                                                                    }
                                                                    if ((int) $keg > 0) {
                                                                        $kode .= "." . $Fungsi->zero_pad($keg, 2);
                                                                        $kd_prov .= ".1." . $Fungsi->zero_pad($keg, 2);
                                                                        $kd_kab .= ".2." . $Fungsi->zero_pad($keg, 2);
                                                                        $sub_keg = $validateRow->setRules(4, 'sub_keg', [
                                                                            'numeric_zero' => true,
                                                                        ]);
                                                                        if ($sub_keg) {
                                                                            $kode .= "." . $Fungsi->zero_pad($sub_keg, 4);
                                                                            $kd_prov .= "." . $Fungsi->zero_pad($sub_keg, 4);
                                                                            $kd_kab .= "." . $Fungsi->zero_pad($sub_keg, 4);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $nomenklatur_urusan = $validateRow->setRules(5, 'nomenklatur urusan', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $kinerja = $validateRow->setRules(6, 'kinerja', [
                                                                'sanitize' => 'string',
                                                            ]);
                                                            $indikator = $validateRow->setRules(7, 'indikator', [
                                                                'sanitize' => 'string',
                                                            ]);
                                                            $satuan = $validateRow->setRules(8, 'satuan', [
                                                                'sanitize' => 'string',
                                                            ]);
                                                            $keterangan = $validateRow->setRules(9, 'keterangan', [
                                                                'sanitize' => 'string',
                                                            ]);
                                                            $arrayDataRows = [
                                                                'urusan' => $urusan,
                                                                'bidang' => $bidang,
                                                                'prog' => (int)$prog,
                                                                'keg' => (int)$keg,
                                                                'sub_keg' => (int)$sub_keg,
                                                                'nomenklatur_urusan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $nomenklatur_urusan),
                                                                'kinerja' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kinerja),
                                                                'indikator' => preg_replace('/(\s\s+|\t|\n)/', ' ', $indikator),
                                                                'satuan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $satuan),
                                                                'peraturan' => (int)$id_aturan_sub_kegiatan,
                                                                'kode' => $kode,
                                                                'kd_prov' => $kd_prov,
                                                                'kd_kab' => $kd_kab,
                                                                'disable' => 0,
                                                                'keterangan' => $keterangan,
                                                                'tanggal' => date('Y-m-d H:i:s'),
                                                                'username' => $_SESSION["user"]["username"]
                                                            ];
                                                            $update_arrayData = [['kode', '=', $kode]];
                                                            $getWhereArrayData = [['kode', '=', $kode]];
                                                            $no_sort++;
                                                            break;
                                                        case 'rekanan':
                                                            $nama_perusahaan = $validateRow->setRules(0, $RowHeaderValidate[0], [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 5,
                                                                'max_char' => 255
                                                            ]);
                                                            $alamat = $validateRow->setRules(1, $RowHeaderValidate[1], [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 4,
                                                                'max_char' => 255
                                                            ]);
                                                            $email = $validateRow->setRules(2, $RowHeaderValidate[2], [
                                                                'sanitize' => 'string',
                                                            ]);
                                                            $npwp = $validateRow->setRules(3, $RowHeaderValidate[3], [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 16,
                                                                'max_char' => 21
                                                            ]);
                                                            $no_rekening = $validateRow->setRules(4, $RowHeaderValidate[4], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $bank_rekening = $validateRow->setRules(5, $RowHeaderValidate[5], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $atas_nama_rekening = $validateRow->setRules(6, $RowHeaderValidate[6], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $direktur = $validateRow->setRules(7, $RowHeaderValidate[7], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $jabatan = $validateRow->setRules(8, $RowHeaderValidate[8], [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 3,
                                                                'max_char' => 255
                                                            ]);
                                                            $no_ktp = $validateRow->setRules(9, $RowHeaderValidate[9], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]); //
                                                            $alamat_dir = $validateRow->setRules(10, $RowHeaderValidate[10], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $no_akta_pendirian = $validateRow->setRules(11, $RowHeaderValidate[11], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $no_akta_perubahan = $validateRow->setRules(16, $RowHeaderValidate[15], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $nama_pelaksana = $validateRow->setRules(19, $RowHeaderValidate[19], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $keterangan = $validateRow->setRules(21, $RowHeaderValidate[21], [
                                                                'sanitize' => 'string',
                                                                'max_char' => 255
                                                            ]);
                                                            $arrayDataRows = [
                                                                'nama_perusahaan' => $nama_perusahaan,
                                                                'alamat' => $alamat,
                                                                'email' => $email,
                                                                'npwp' => $npwp,
                                                                'no_rekening' => $no_rekening,
                                                                'bank_rekening' => $bank_rekening,
                                                                'atas_nama_rekening' => $atas_nama_rekening,
                                                                'direktur' => $direktur,
                                                                'jabatan' => $jabatan,
                                                                'no_ktp' => $no_ktp,
                                                                'alamat_dir' => $alamat_dir,
                                                                'keterangan' => $keterangan
                                                            ];
                                                            // var_dump($getData[12]);
                                                            // var_dump(preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $getData[12]));
                                                            if (strlen($no_akta_pendirian) > 0 && preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $getData[12])) {

                                                                $tgl_akta_pendirian = $validateRow->setRules(12, $RowHeaderValidate[12], [
                                                                    'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/',
                                                                    'max_char' => 255
                                                                ]);
                                                                $tgl_akta_pendirian = $Fungsi->tanggal($tgl_akta_pendirian)['tanggalMysql'];
                                                                $lokasi_notaris_pendirian = $validateRow->setRules(13, $RowHeaderValidate[13], [
                                                                    'sanitize' => 'string',
                                                                    'max_char' => 255
                                                                ]);
                                                                $nama_notaris_pendirian = $validateRow->setRules(14, $RowHeaderValidate[14], [
                                                                    'sanitize' => 'string',
                                                                    'max_char' => 255
                                                                ]);
                                                                $arrayDataRows['no_akta_pendirian'] = $no_akta_pendirian;
                                                                $arrayDataRows['tgl_akta_pendirian'] = $tgl_akta_pendirian;
                                                                $arrayDataRows['lokasi_notaris_pendirian'] = $lokasi_notaris_pendirian;
                                                                $arrayDataRows['nama_notaris_pendirian'] = $nama_notaris_pendirian;
                                                            }
                                                            if (strlen($no_akta_perubahan) > 0 && preg_match('/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', $getData[16])) {
                                                                $tgl_akta_perubahan = $validateRow->setRules(16, $RowHeaderValidate[16], [
                                                                    'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/',
                                                                    'max_char' => 100
                                                                ]);
                                                                $tgl_akta_perubahan = $Fungsi->tanggal($tgl_akta_perubahan)['tanggalMysql'];
                                                                $lokasi_notaris_perubahan = $validateRow->setRules(17, $RowHeaderValidate[17], [
                                                                    'sanitize' => 'string',
                                                                    'max_char' => 255
                                                                ]);
                                                                $nama_notaris_perubahan = $validateRow->setRules(18, $RowHeaderValidate[18], [
                                                                    'sanitize' => 'string',
                                                                    'max_char' => 255
                                                                ]);
                                                                $notaris_perubahan = new stdClass;
                                                                $notaris_perubahan->{'nomor[1]'} = $no_akta_perubahan;
                                                                $notaris_perubahan->{'tanggal[1]'} = $tgl_akta_perubahan;
                                                                $notaris_perubahan->{'alamat_notaris[1]'} = $lokasi_notaris_perubahan;
                                                                $notaris_perubahan->{'notaris[1]'} = $nama_notaris_perubahan;
                                                                $arrayDataRows['notaris_perubahan'] = json_encode($notaris_perubahan);
                                                            }
                                                            if (strlen($nama_pelaksana) > 0) {
                                                                $jabatan_pelaksana = $validateRow->setRules(20, $RowHeaderValidate[20], [
                                                                    'sanitize' => 'string',
                                                                    'max_char' => 255
                                                                ]);
                                                                $data_lain = new stdClass;
                                                                $data_lain->{'pelaksana[nama][1]'} = $nama_pelaksana;
                                                                $data_lain->{'pelaksana[jabatan][1]'} = $tgl_akta_perubahan;
                                                                $arrayDataRows['data_lain'] = json_encode($data_lain);
                                                            }
                                                            $update_arrayData = [['npwp', "=", $npwp]];
                                                            $getWhereArrayData = [['npwp', "=", $npwp]];
                                                            $no_sort++;
                                                            //var_dump($arrayDataRows);
                                                            /*KTP
                                                    Kesatu, 6 digit pertama adalah kode wilayah dimana NIK pertama kali didaftarkan (2 digit pertama untuk kode provinsi, 2 digit kedua untuk kode kabupaten/kota, dan 2 digit ketiga untuk kode kecamatan).
                                                    Kedua, 6 digit berikutnya adalah tanggal lahir pemilik NIK (2 digit untuk tanggal, 2 digit untuk bulan, dan 2 digit untuk tahun). Untuk penduduk berjenis kelamin perempuan, ditambahkan angka 40 pada tanggal lahir. 
                                                    Ketiga, 4 digit selanjutnya adalah nomor urut yang ditentukan secara sistem. 
                                                    */
                                                            break;
                                                        case 'monev':
                                                            $kd_analisa_temporer = $getData[0];
                                                            //var_dump($kd_analisa_temporer);
                                                            if (strlen($kd_analisa_temporer) > 0) {
                                                                $kd_analisa = $validateRow->setRules(0, 'Kode Analisa', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1,
                                                                    'max_char' => 100
                                                                ]);
                                                                $volume = $validateRow->setRules(2, 'volume', [
                                                                    'required' => true,
                                                                    'numeric' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $jumlah_harga = $validateRow->setRules(4, 'jumlah harga', [
                                                                    'required' => true,
                                                                    'numeric' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                // cari id_rab
                                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['volume', '=', $volume, 'AND'], ['jumlah_harga', '=', $jumlah_harga, 'AND']];
                                                                $resultDataMonv = $DB->getWhereCustom('rencana_anggaran_biaya', $condition);
                                                                $jumlahArray = is_array($resultDataMonv) ? count($resultDataMonv) : 0;
                                                                if ($jumlahArray) {
                                                                    foreach ($resultDataMonv as $key => $value) {
                                                                        $id_rab = $value->id;
                                                                        $kd_analisa = $value->kd_analisa;
                                                                        $uraian = $value->uraian;
                                                                        $volume = $value->volume;
                                                                        $satuan = $value->satuan;
                                                                        $jumlah_harga = $value->jumlah_harga;
                                                                    };
                                                                    if ($id_rab <= 0) {
                                                                        //hanya berfungsi agar cath validate
                                                                        $cath = $validateRow->setRules(1, '(id RAB tidak ditemukan)', [
                                                                            'error' => true
                                                                        ]);
                                                                    }
                                                                }
                                                            }

                                                            $tanggal = $validateRow->setRules(5, 'Tanggal Realisasi', [
                                                                'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/', 'required' => true,
                                                                'max_char' => 100
                                                            ]);
                                                            $tanggal = $Fungsi->tanggal($tanggal)['tanggalMysql'];

                                                            $realisasi_fisik = 0;
                                                            if (!empty($getData[6])) {
                                                                $realisasi_fisik = $validateRow->setRules(6, 'Realisasi Fisik', [
                                                                    'required' => true,
                                                                    'numeric' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                            }
                                                            $realisasi_keu = 0;
                                                            if (!empty($getData[7])) {
                                                                $realisasi_keu = $validateRow->setRules(7, 'Realisasi Keuangan', [
                                                                    'numeric' => true
                                                                ]);
                                                            }
                                                            $keterangan = $validateRow->setRules(8, 'keterangan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            //mulai menghitung
                                                            //$getWhereArrayData
                                                            $arrayDataRows = [
                                                                'kd_proyek' => $kd_proyek,
                                                                'id_rab' => $id_rab,
                                                                'uraian' => $uraian,
                                                                'satuan' => $satuan,
                                                                'tanggal' => $tanggal,
                                                                'realisasi_fisik' => $realisasi_fisik,
                                                                'realisasi_keu' => $realisasi_keu,
                                                                'tgl_input' => date('Y-m-d H:i:s'),
                                                                'keterangan' => $keterangan
                                                            ];
                                                            //var_dump($arrayDataRows);
                                                            $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['id_rab', "=", $id_rab, 'AND'], ['tanggal', "=", $tanggal, 'AND']];
                                                            $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['id_rab', "=", $id_rab, 'AND'], ['tanggal', "=", $tanggal, 'AND']];
                                                            break;
                                                        case 'divisi':
                                                            $kode = $validateRow->setRules(0, 'Kode', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 100
                                                            ]);
                                                            $uraian = $validateRow->setRules(1, 'Uraian', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 400
                                                            ]);
                                                            $satuan = $validateRow->setRules(2, 'Satuan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $keterangan = $validateRow->setRules(3, 'Keterangan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $tingkat = count(explode(".", $kode));
                                                            $arrayDataRows = [
                                                                'tahun' => $tahun,
                                                                'kode' => $kode,
                                                                'uraian' => $uraian,
                                                                'satuan' => $satuan,
                                                                'bidang' => $bidang,
                                                                'tingkat' => (int)$tingkat,
                                                                'keterangan' => $keterangan,
                                                                'no_sortir' => $no_sort
                                                            ];
                                                            //var_dump($arrayDataRows);
                                                            $update_arrayData = [['tahun', "=", $tahun], ['kode', "=", $kode, 'AND'], ['bidang', "=", $bidang, 'AND']];
                                                            $getWhereArrayData = [['tahun', "=", $tahun], ['kode', "=", $kode, 'AND'], ['bidang', "=", $bidang, 'AND']];
                                                            $no_sort++;
                                                            break;
                                                        case 'satuan':
                                                            $value = $validateRow->setRules(0, 'value', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $item = $validateRow->setRules(1, 'item', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $keterangan = $validateRow->setRules(2, 'keterangan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $sebutan_lain = $validateRow->setRules(3, 'Sebutan Lain', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $arrayDataRows = [
                                                                'value' => $value,
                                                                'item' => $item,
                                                                'keterangan' => $keterangan,
                                                                'sebutan_lain' => $sebutan_lain,
                                                                'no_sortir' => $no_sort
                                                            ];
                                                            $update_arrayData = [['value', "=", $value]];
                                                            $getWhereArrayData = [['value', "=", $value]];
                                                            $no_sort++;
                                                            break;
                                                        case 'harga_satuan':
                                                            // $target_tahun5 = $validateRow->setRules( 18, $val[ 1 ], [
                                                            //'numeric' => true
                                                            // ] );
                                                            $jenis_row = $validateRow->setRules(0, $sum, [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 100,
                                                                'in_array' => ['upah', 'royalty', 'bahan', 'peralatan']
                                                            ]);
                                                            $kode = $validateRow->setRules(1, 'kode', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 100,
                                                                'uniqueArray' => ['harga_sat_upah_bahan', 'kode', [['kd_proyek', '=', $kd_proyek], ['status', '=', $status, 'AND']]]
                                                            ]);
                                                            $uraian = $validateRow->setRules(2, 'uraian', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 100
                                                            ]);
                                                            $satuan = $validateRow->setRules(3, 'satuan', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 100
                                                            ]);
                                                            $harga_sat = $validateRow->setRules(4, 'harga sat', [
                                                                'numeric' => true
                                                            ]);
                                                            $sumber_data = $validateRow->setRules(5, 'sumber data', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $spesifikasi = $validateRow->setRules(6, 'spesifikasi', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $keterangan = $validateRow->setRules(7, 'keterangan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $tahun = $data['dataProyek']->tahun_anggaran; //$data_kd_proyek[0]->kd_proyek_aktif;
                                                            if ($type_user === 'admin') {
                                                                $status = 'admin';
                                                            } else {
                                                                $status = 'user';
                                                            }
                                                            $arrayDataRows = [
                                                                'kd_proyek' => $kd_proyek,
                                                                'jenis' => $jenis_row,
                                                                'kode' => $kode,
                                                                'uraian' => $uraian,
                                                                'satuan' => $satuan,
                                                                'harga_satuan' => (float)$harga_sat,
                                                                'sumber_data' => $sumber_data,
                                                                'spesifikasi' => $spesifikasi,
                                                                'keterangan' => $keterangan,
                                                                'tahun' => $tahun,
                                                                'status' => $status,
                                                                'no_sortir' => $no_sort
                                                            ];
                                                            $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                                            $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                                            $no_sort++;
                                                            break;
                                                        case 'x':
                                                            break;
                                                        default:
                                                    }
                                                    //FINISH PROSES VALIDASI
                                                    //=====================================
                                                    //PROSES SEARCH DATA LAMA/INSERT/UPDATE
                                                    //=====================================
                                                    if ($validateRow->passed()) {
                                                        switch ($tbl) {
                                                            case 'sub_keg':
                                                            case 'sumber_dana':
                                                            case 'peraturan':
                                                            case 'akun_belanja':
                                                                //var_dump($tabel_pakai);
                                                                $sumRows = $DB->getWhereArray($tabel_pakai, $getWhereArrayData);
                                                                $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                                                                //var_dump($jumlahArray);
                                                                if ($jumlahArray <= 0) {
                                                                    $resul = $DB->insert($tabel_pakai, $arrayDataRows);
                                                                    //$jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                                                    //var_dump($DB->count());
                                                                    if ($DB->count()) {
                                                                        $data['add_row'][] = $sum;
                                                                    }
                                                                } else {
                                                                    //update row
                                                                    $resul = $DB->update_array($tabel_pakai, $arrayDataRows, $update_arrayData);
                                                                    $jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                                                    if ($jumlahArray) {
                                                                        //array_push($data['row_update'], $sum);
                                                                        $data['row_update'][] = $sum;
                                                                        //$data['note']['row update'][] = $sum;
                                                                    } else {
                                                                        //array_push($data['gagal'], $sum);
                                                                        $data['gagal'][] = $sum;
                                                                        //$data['note']['gagal'][] = $sum;
                                                                    }
                                                                }

                                                                break;
                                                            default:
                                                                # code...
                                                                break;
                                                        }
                                                    } else {
                                                        //jika data pengenal dobel update data
                                                        //$validateRow->getError()[0]='';
                                                        //$data['note'][$sum] .= $validateRow->getError()[1];
                                                        //str_contains('How are you', 'are') untuk php 8
                                                        switch ($tbl) {
                                                            case 'harga_satuan':
                                                            case 'analisa_alat':
                                                                //var_dump($validateRow->getError());
                                                                //var_dump(array_keys($validateRow->getError())[0]);
                                                                $array_key0 = array_keys($validateRow->getError())[0];
                                                                $haystack = $validateRow->getError()[$array_key0];
                                                                $needle = 'silahkan pilih nama lain';
                                                                if (strpos($haystack, $needle) !== false) {
                                                                    //update row
                                                                    $resul = $DB->update_array($tabel_pakai, $arrayDataRows, [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']]);
                                                                    //var_dump($resul);
                                                                    if ($DB->count()) {
                                                                        $data['row_update'][] = $sum;
                                                                        $code = 3;
                                                                    }
                                                                }
                                                                break;
                                                            default:
                                                                //var_dump($validateRow->getError()[0]);
                                                                //var_dump('error:'.$sum);
                                                                $array_key0 = array_keys($validateRow->getError())[0];
                                                                $haystack = $validateRow->getError()[$array_key0];
                                                                $data['pesan_validasi'][$r] = $haystack;
                                                                break;
                                                        }
                                                    }
                                                } else {
                                                    $item['row'] .= "jumlah kolom baris $sum < $count_col_min";
                                                }
                                            }
                                        } else {
                                            $data['pesan_validasi'][] = $validateTabel->getError();
                                            $sukses = false;
                                            $code = 401;
                                            $keterangan = '<ol class="ui horizontal ordered suffixed list">';
                                            foreach ($RowHeaderValidate as $key => $value) {
                                                $keterangan .= '<li class="item">' . $value . '</li>';
                                            }
                                            $keterangan .= '</ol>';
                                            $tambahan_pesan = [401 => 'ikuti format header(kop) tabel :<br>' . $keterangan];
                                        }
                                    }
                                }
                            } else {
                                $code = 44;
                                $data = SimpleXLSX::parseError();
                            }
                            // akhir proses impor
                        } else {
                            $code = 29;
                            $pesan = $validate->getError();
                        }
                    } else {
                        $code = 42;
                    }
                } else {
                    $code = 43;
                }
            } else {
                $code = 39;
            }
        } else {
            $code = 41;
        }
        $item = array('code' => $code, 'message' => hasilServer[$code] . " $tambahan_pesan");
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
    }
}
