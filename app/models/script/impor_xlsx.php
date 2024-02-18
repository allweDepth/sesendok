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
        $tambahan_pesan = '.';
        //$data['note'] = [];
        //$data['add_row'] = [];
        //$data['row_update'] = [];
        //$data['gagal'] = [];
        //$data['note'][] = 'row update';
        //$data['note'][] = 'add row';
        //$data['note'][] = 'gagal';
        $rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
        if ($rowUsername != false) {
            $tahun = (int) $rowUsername->tahun;
            $kd_wilayah = $rowUsername->kd_wilayah;
            $kd_opd = $rowUsername->kd_organisasi;
            $id_user = $rowUsername->id;
        } else {
            $id_user = 0;
            $code = 407;
        }
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

                        $kodePosting = '';
                        if ($validate->passed()) {
                            // mulai proses impor
                            $disableImport = 0;
                            $filename = $_FILES["file"]["tmp_name"];
                            if ($xlsx = SimpleXLSX::parse($filename)) {
                                //var_dump($tahun_anggaran);
                                $code = 1;
                                $sukses = true;
                                $RowHeaderValidate = [];

                                $rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
                                $tahun = (int) $rowUsername->tahun;
                                $kd_wilayah = $rowUsername->kd_wilayah;
                                $kd_skpd = $rowUsername->kd_organisasi;
                                $kd_opd = $rowUsername->kd_organisasi;
                                //tentukan peraturan yang membutuhkan
                                switch ($tbl) {
                                    case 'sub_keg_renja':
                                    case 'sub_keg_dpa':
                                    case 'renja':
                                    case 'dpa':
                                    case 'renja_p':
                                    case 'dppa':
                                    case 'tujuan_sasaran_renstra':
                                    case 'akun_belanja':
                                    case 'sub_kegiatan':
                                    case 'asb':
                                    case 'sbu':
                                    case 'ssh':
                                    case 'hspk':
                                    case 'sumber_dana':
                                    case 'sub_keg':
                                    case 'aset':
                                    case 'mapping':
                                    case 'organisasi':
                                        // case 'peraturan':
                                    case 'satuan':
                                    case 'rekanan':
                                        $rowTahunAktif = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
                                        // var_dump($rowTahunAktif);
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
                                            $tahun = $rowTahunAktif->tahun;
                                        } else {
                                            $id_peraturan = 0;
                                            $disableImport = 1;
                                            // throw new Exception("Pengaturan Tahun Anggaran tidak ditemukan", 1);
                                        }
                                        break;
                                }
                                if ($disableImport <= 0) {
                                    //menentukan data
                                    $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                    switch ($tbl) {
                                        case 'sub_keg_renja':
                                        case 'sub_keg_dpa':
                                            $RowHeaderValidate = ['KODE SUB KEGIATAN', 'PROGRAM DAN KEGIATAN', 'TOLAK UKUR CAPAIAN KEG.', 'TARGET KINERJA CAPAIAN KEG.', 'TOLAK UKUR KELUARAN', 'TARGET KINERJA KELUARAN', 'TOLAK UKUR HASIL', 'TARGET KINERJA HASIL', 'SUMBER DANA', 'LOKASI', 'KELUARAN SUB KEG.', 'AWAL PELAKSANAAN', 'AKHIR PELAKSANAAN', 'JUMLAH PAGU', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'renstra':
                                            $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                                            $tahun_renstra = ($rowOrganisasi) ? $rowOrganisasi->tahun_renstra : 0;
                                            $unit_kerja = ($rowOrganisasi) ? $rowOrganisasi->uraian : 'unit kerja tidak ditemukan';
                                            $RowHeaderValidate = ['SASARAN', 'KODE', 'PROGRAM DAN KEGIATAN', 'SATUAN', 'INDIKATOR', 'DATA CAPAIAN AWAL', 'TARGET TAHUN 1', 'DANA TAHUN 1', 'TARGET TAHUN 2', 'DANA TAHUN 2', 'TARGET TAHUN 3', 'DANA TAHUN 3', 'TARGET TAHUN 4', 'DANA TAHUN 4', 'TARGET TAHUN 5', 'DANA TAHUN 5', 'LOKASI', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'tujuan_sasaran_renstra':
                                            $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                                            $tahun_renstra = $rowOrganisasi->tahun_renstra;
                                            $RowHeaderValidate = ['KELOMPOK', 'TUJUAN/SASARAN', 'INDIKATOR SASARAN', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'rekanan':
                                            $RowHeaderValidate = ['Nama Perusahaan/Pribadi', 'Alamat', 'Email', 'NPWP', 'Nomor Rekening Bank', 'Nama Bank Rekening', 'Atas Nama Bank Rekening', 'Nama Penanggung Jawab', 'Jabatan', 'Nomor KTP', 'Alamat Penanggung Jawab', 'Nomor Akta Pendirian', 'Tanggal Akta Pendirian', 'Lokasi Notaris Pendirian', 'Nama Notaris Pendirian', 'Nomor Akta Perubahan', 'Tanggal Akta Perubahan', 'Lokasi Notaris Perubahan', 'Nama Notaris Perubahan', 'Nama Pelaksana', 'Jabatan Pelaksana', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'hspk':
                                            $id_aturan = $id_aturan_hspk;
                                            $RowHeaderValidate = ['KODE KELOMPOK BARANG', 'URAIAN KELOMPOK BARANG', 'URAIAN BARANG', 'SPESIFIKASI', 'SATUAN', 'HARGA SATUAN', 'TKDN', 'KODE REKENING', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'ssh':
                                            $id_aturan = $id_aturan_ssh;
                                            $RowHeaderValidate = ['KODE KELOMPOK BARANG', 'URAIAN KELOMPOK BARANG', 'URAIAN BARANG', 'SPESIFIKASI', 'SATUAN', 'HARGA SATUAN', 'TKDN', 'KODE REKENING', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'asb':
                                            $id_aturan = $id_aturan_asb;
                                            $RowHeaderValidate = ['KODE KELOMPOK BARANG', 'URAIAN KELOMPOK BARANG', 'URAIAN BARANG', 'SPESIFIKASI', 'SATUAN', 'HARGA SATUAN', 'TKDN', 'KODE REKENING', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'sbu':
                                            $id_aturan = $id_aturan_sbu;
                                            $RowHeaderValidate = ['KODE KELOMPOK BARANG', 'URAIAN KELOMPOK BARANG', 'URAIAN BARANG', 'SPESIFIKASI', 'SATUAN', 'HARGA SATUAN', 'TKDN', 'KODE REKENING', 'KETERANGAN'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'satuan':
                                            $RowHeaderValidate = ['Value', 'Item', 'Keterangan', 'Sebutan Lain'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'wilayah':
                                            $RowHeaderValidate = ['KODE', 'NAMA KABUPATEN / KOTA', 'STATUS', 'KECAMATAN', 'KELURAHAN', 'DESA', 'LUAS WILAYAH (km2)', 'JUMLAH PENDUDUK (jiwa)', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'organisasi':
                                            $RowHeaderValidate = ['KODE', 'ORGANISASI', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'mapping':
                                            $RowHeaderValidate = ['Kode Neraca', 'Uraian Neraca', 'Kode Akun', 'Uraian Akun', 'Kelompok', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'peraturan':
                                            $RowHeaderValidate = ['Type Dok', 'Judul', 'Nomor', 'Bentuk', 'Bentuk singkat', 'Tempat Penetapan', 'Tanggal Penetapan', 'Tanggal Pengundangan', 'Keterangan', 'Status Data'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'sumber_dana':
                                            $count_col_min = 8;
                                            $RowHeaderValidate = ['Sumber Dana', 'Kelompok', 'Jenis', 'Objek', 'Rincian Objek', 'Sub Rincian Objek', 'Uraian Akun', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'akun_belanja':
                                            $RowHeaderValidate = ['Akun', 'Kelompok', 'Jenis', 'Objek', 'Rincian Objek', 'Sub Rincian Objek', 'Uraian Akun', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'aset':
                                            $RowHeaderValidate = ['Akun', 'Kelompok', 'Jenis', 'Objek', 'Rincian Objek', 'Sub Rincian Objek', 'Uraian Akun', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
                                            break;
                                        case 'sub_keg':
                                            $RowHeaderValidate = ['URUSAN/UNSUR', 'BIDANG URUSAN/BIDANG UNSUR', 'PROGRAM', 'KEGIATAN', 'SUB KEGIATAN', 'NOMENKLATUR URUSAN', 'KINERJA', 'INDIKATOR', 'SATUAN', 'Keterangan'];
                                            $count_col_min = count($RowHeaderValidate);
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
                                    $id_tujuan_sasaran = 0;
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
                                                // var_dump($keyValid);
                                                // var_dump($valueValid);
                                                // var_dump($headerValid);
                                                $data_column = $validateTabel->setRules($keyValid, "header kolom $headerValid", [
                                                    'sanitize' => 'string',
                                                    'required' => true,
                                                    'min_char' => 1,
                                                    'max_char' => 255,
                                                    'in_array' => [$headerValid]
                                                ]);
                                                //var_dump($valueValid);
                                                //var_dump($data_column);
                                            }
                                        }
                                        if ($r > ($jml_header - 1)) {
                                            if ($validateTabel->passed()) {
                                                // atur kembali rekening sub kegiatan $getData[0](kode sub kegiatan) antisipasi beda 00
                                                switch ($tbl) {
                                                        // atur kembali rekening sub kegiatan $getData[0](kode sub kegiatan) antisipasi beda 00
                                                    case 'sub_keg_renja':
                                                    case 'sub_keg_dpa':
                                                        $kodeRek = $getData[0];
                                                        $dinamic = ['kode' => $kodeRek];
                                                        $kodeRekUbah = $Fungsi->kelolaRek($dinamic);
                                                        $getData[0] = $kodeRekUbah['kode'];
                                                        //cek key eksis
                                                        //$dataRek['kd_sub_keg_x_xx']
                                                        if (array_key_exists('kel_kd_sub_keg', $kodeRekUbah)) {
                                                            $kel_kd_sub_keg = $kodeRekUbah['kel_kd_sub_keg'];
                                                            $kd_sub_keg_x_xx = $kodeRekUbah['kd_sub_keg_x_xx'];
                                                        }
                                                        
                                                        break;
                                                    case 'value':
                                                        # code...
                                                        break;
                                                    default:
                                                        # code...
                                                        break;
                                                }
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
                                                            case 'sub_keg_renja':
                                                            case 'sub_keg_dpa': //@audit impor sub_keg_renja
                                                                $arrayValidateRow = (array)$validateRow;
                                                                // $arrayDg = [];
                                                                // foreach ($arrayValidateRow as $key => $Id) {
                                                                //     $arrayDg[$key] = $arrayValidateRow[$key];
                                                                // }
                                                                $keyArray = array_keys($arrayValidateRow);
                                                                $kd_sub_keg_temp = $arrayValidateRow[$keyArray[1]][0];
                                                                // cari di kode x.xx ganti dulu
                                                                //$kd_sub_keg_x_xx
                                                                // checkArray( $tableName, $columnName, $condition )
                                                                $cek_x_xx=$DB->checkArray('sub_kegiatan_neo', 'kode', [['kode', '=', $kd_sub_keg_x_xx]],$kd_sub_keg_x_xx);
                                                                if ($cek_x_xx) {
                                                                    $kd_sub_keg = $kd_sub_keg_x_xx;
                                                                }else{
                                                                    $kd_sub_keg = $validateRow->setRules(0, "sub kegiatan($kd_sub_keg_temp)", [
                                                                        'sanitize' => 'string',
                                                                        'required' => true,
                                                                        'inDB' => ['sub_kegiatan_neo', 'kode', [['kode', '=', $kd_sub_keg_temp]]],
                                                                        'min_char' => 1
                                                                    ]);
                                                                }
                                                                $uraian = $validateRow->setRules(1, 'uraian program dan kegiatan', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $tolak_ukur_capaian_keg = $validateRow->setRules(2, 'tolak ukur_capaian keg', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $target_kinerja_capaian_keg = $validateRow->setRules(3, 'target kinerja capaian keg', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $tolak_ukur_keluaran = $validateRow->setRules(4, 'tolak ukur keluaran', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $target_kinerja_keluaran = $validateRow->setRules(5, 'target kinerja keluaran', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $tolak_ukur_hasil = $validateRow->setRules(6, 'tolak ukur_hasil', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $target_kinerja_hasil = $validateRow->setRules(7, 'target kinerja hasil', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $sumber_dana_temp = $arrayValidateRow[$keyArray[1]][8];
                                                                $sumber_dana = $validateRow->setRules(8, 'sumber dana', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'inDB' => ['sumber_dana_neo', 'kode', [['kode', '=', $sumber_dana_temp]]],
                                                                    'min_char' => 1
                                                                ]);
                                                                $lokasi = $validateRow->setRules(9, 'lokasi', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $keluaran_sub_keg = $validateRow->setRules(10, 'keluaran sub keg', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $awal_pelaksanaan = $validateRow->setRules(11, 'tanggal awal pelaksanaan', [
                                                                    'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/',
                                                                    'required' => true,
                                                                    'max_char' => 100,
                                                                    'min_char' => 8
                                                                ]);
                                                                $akhir_pelaksanaan = $validateRow->setRules(12, 'tanggal akhir pelaksanaan', [
                                                                    'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/',
                                                                    'required' => true,
                                                                    'max_char' => 100,
                                                                    'min_char' => 8
                                                                ]);
                                                                $jumlah_pagu = $validateRow->setRules(13, 'jumlah_pagu', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $keterangan = $validateRow->setRules(14, 'keterangan', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                //uraian_prog_keg
                                                                $progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $kd_sub_keg]]);
                                                                $uraian_prog_keg = ($progkeg) ? $progkeg->nomenklatur_urusan : 'data tidak ditemukan';
                                                                $arrayDataRows = [
                                                                    'kd_wilayah' => $kd_wilayah,
                                                                    'kd_opd' => $kd_opd,
                                                                    'tahun' => $tahun,
                                                                    'kd_sub_keg' => $kd_sub_keg,
                                                                    'kel_kd_sub_keg' => $kel_kd_sub_keg,
                                                                    'uraian' => $uraian_prog_keg,
                                                                    'tolak_ukur_capaian_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_capaian_keg),
                                                                    'target_kinerja_capaian_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_capaian_keg),
                                                                    'tolak_ukur_keluaran' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_keluaran),
                                                                    'target_kinerja_keluaran ' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_keluaran),
                                                                    'tolak_ukur_hasil' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_hasil),
                                                                    'target_kinerja_hasil' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_hasil),
                                                                    'sumber_dana' =>  json_encode(array("01" => $sumber_dana)),
                                                                    'lokasi' => preg_replace('/(\s\s+|\t|\n)/', ' ', $lokasi),
                                                                    'keluaran_sub_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keluaran_sub_keg),
                                                                    'awal_pelaksanaan' => $awal_pelaksanaan,
                                                                    'akhir_pelaksanaan' => $akhir_pelaksanaan,
                                                                    'jumlah_pagu' => (float)$jumlah_pagu,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'disable' => 0,
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'tgl_update' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                $arrayDataRows_nonSubKeg = [
                                                                    'kd_wilayah' => $kd_wilayah,
                                                                    'kd_opd' => $kd_opd,
                                                                    'tahun' => $tahun,
                                                                    'kd_sub_keg' => $kd_sub_keg,
                                                                    'kel_kd_sub_keg' => $kel_kd_sub_keg,
                                                                    'uraian' => $uraian_prog_keg,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'disable' => 0,
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'tgl_update' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kd_sub_keg', '=', $kd_sub_keg, 'AND']];
                                                                $getWhereArrayData = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kd_sub_keg', '=', $kd_sub_keg, 'AND']];
                                                                $no_sort++;
                                                                break;
                                                            case 'renstra':
                                                                /*
                                                                $sasaran = $validateRow->setRules(0, 'sasaran', [
                                                                    'numeric' => true,
                                                                    'required' => true,
                                                                    'inDB' => ['tujuan_sasaran_renstra_neo', 'id', [['id', '=', $_POST['sasaran']], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['tahun', '=', $tahun_renstra, 'AND']]],
                                                                    'min_char' => 1
                                                                ]);
                                                                */
                                                                $sasaran = $validateRow->setRules(0, 'sasaran', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $kode_temporer = strtolower($getData[1]);
                                                                $kode = $validateRow->setRules(1, 'sub kegiatan', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'inDB' => ['sub_kegiatan_neo', 'kode', [['kode', '=', $kode_temporer]]],
                                                                    'min_char' => 1
                                                                ]);
                                                                $uraian_prog_keg = $validateRow->setRules(2, 'uraian program dan kegiatan', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $satuan = $validateRow->setRules(3, 'satuan', [
                                                                    'sanitize' => 'string',
                                                                    // 'required' => true,
                                                                    // 'min_char' => 1
                                                                ]);
                                                                $indikator = $validateRow->setRules(4, 'indikator', [
                                                                    'sanitize' => 'string',
                                                                    // 'required' => true,
                                                                    // 'min_char' => 4
                                                                ]);

                                                                $data_capaian_awal = $validateRow->setRules(5, 'data capaian awal', [
                                                                    'numeric_zero' => true,
                                                                    // 'numeric' => true,
                                                                    // 'required' => true,
                                                                    // 'min_char' => 1
                                                                ]);
                                                                $target_thn_1 = $validateRow->setRules(6, 'Target tahun pertama', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $dana_thn_1 = $validateRow->setRules(7, 'Dana tahun pertama', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $target_thn_2 = $validateRow->setRules(8, 'Target tahun kedua', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $dana_thn_2 = $validateRow->setRules(9, 'Dana tahun kedua', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $target_thn_3 = $validateRow->setRules(10, 'Target tahun ketiga', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $dana_thn_3 = $validateRow->setRules(11, 'Dana tahun ketiga', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $target_thn_4 = $validateRow->setRules(12, 'Target tahun keempat', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $dana_thn_4 = $validateRow->setRules(13, 'Dana tahun keempat', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $target_thn_5 = $validateRow->setRules(14, 'Target tahun kelima', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $dana_thn_5 = $validateRow->setRules(15, 'Dana tahun kelima', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $lokasi = $validateRow->setRules(16, 'lokasi', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $keterangan = $validateRow->setRules(17, 'keterangan', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $tujuan = $DB->getWhereOnceCustom('tujuan_sasaran_renstra_neo', [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['id', '=', $sasaran, 'AND']]);
                                                                $id_tujuan = ($tujuan) ? $tujuan->id_tujuan : 0;
                                                                //uraian_prog_keg
                                                                $progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $kode]]);
                                                                $uraian_prog_keg = ($progkeg) ? $progkeg->nomenklatur_urusan : 'data tidak ditemukan';
                                                                //kondisi_akhir
                                                                $kondisi_akhir = (float)$data_capaian_awal + (float)$target_thn_1 + (float)$target_thn_2 + (float)$target_thn_3 + (float)$target_thn_3 + (float)$target_thn_5;
                                                                $arrayDataRows = [
                                                                    'kd_wilayah' => $kd_wilayah,
                                                                    'kd_opd' => $kd_opd,
                                                                    'tahun' => $tahun_renstra,
                                                                    'tujuan' => (int)$id_tujuan,
                                                                    'sasaran' => (int)$sasaran,
                                                                    'kode' => $kode,
                                                                    'uraian_prog_keg' => $uraian_prog_keg,
                                                                    'indikator' => preg_replace('/(\s\s+|\t|\n)/', ' ', $indikator),
                                                                    'satuan' =>  strtolower($satuan),
                                                                    'data_capaian_awal' => (float)$data_capaian_awal,
                                                                    'target_thn_1' => (float)$target_thn_1,
                                                                    'dana_thn_1' => (float)$dana_thn_1,
                                                                    'target_thn_2' => (float)$target_thn_2,
                                                                    'dana_thn_2' => (float)$dana_thn_2,
                                                                    'target_thn_3' => (float)$target_thn_3,
                                                                    'dana_thn_3' => (float)$dana_thn_3,
                                                                    'target_thn_4' => (float)$target_thn_4,
                                                                    'dana_thn_4' => (float)$dana_thn_4,
                                                                    'target_thn_5' => (float)$target_thn_5,
                                                                    'dana_thn_5' => (float)$dana_thn_5,
                                                                    'kondisi_akhir' => (float)$kondisi_akhir,
                                                                    'lokasi' => preg_replace('/(\s\s+|\t|\n)/', ' ', $lokasi),
                                                                    'unit_kerja' => $unit_kerja,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'disable' => 0,
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'tgl_update' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['kode', '=', $kode, 'AND']];
                                                                $getWhereArrayData = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['kode', '=', $kode, 'AND']];
                                                                $no_sort++;
                                                                break;
                                                            case 'tujuan_sasaran_renstra':
                                                                $kelompok = $validateRow->setRules(0, 'kelompok', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1,
                                                                    'in_array' => ['tujuan', 'sasaran', 'TUJUAN', 'SASARAN', 'Tujuan', 'Sasaran']
                                                                ]);

                                                                if (strtolower($kelompok) == 'tujuan') {
                                                                    $id_tujuan = 0;
                                                                } else {
                                                                    $id_tujuan = $id_tujuan_sasaran;
                                                                }
                                                                $text = $validateRow->setRules(1, 'Uraian Tujuan/Sasaran', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $indikator = $validateRow->setRules(2, 'indikator sasaran', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $keterangan = $validateRow->setRules(3, 'keterangan', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $arrayDataRows = [
                                                                    'kd_wilayah' => $kd_wilayah,
                                                                    'kd_opd' => $kd_opd,
                                                                    'tahun' => $tahun_renstra,
                                                                    'id_tujuan' => $id_tujuan,
                                                                    'kelompok' => strtolower(preg_replace('/(\s\s+|\t|\n)/', ' ', $kelompok)),
                                                                    'text' => preg_replace('/(\s\s+|\t|\n)/', ' ', $text),
                                                                    'indikator' => preg_replace('/(\s\s+|\t|\n)/', ' ', $indikator),
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'disable' => 0,
                                                                    'tgl_update' => date('Y-m-d H:i:s'),
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['kelompok', '=', $kelompok], ['text', '=', $text, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND']];
                                                                $getWhereArrayData = [['kelompok', '=', $kelompok], ['text', '=', $text, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND']];
                                                                $no_sort++;
                                                                break;
                                                            case 'hspk':
                                                            case 'ssh':
                                                            case 'asb':
                                                            case 'sbu':
                                                                $kd_aset = $validateRow->setRules(0, 'kd_aset', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $uraian_kel = $validateRow->setRules(1, 'uraian kelompok', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $uraian_barang = $validateRow->setRules(2, 'uraian_barang', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $spesifikasi = $validateRow->setRules(3, 'spesifikasi', [
                                                                    'sanitize' => 'string'
                                                                ]);
                                                                $satuan = $validateRow->setRules(4, 'satuan', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $harga_satuan = $validateRow->setRules(5, 'harga satuan', [
                                                                    'numeric' => true,
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $tkdn = $validateRow->setRules(6, 'TKDN', [
                                                                    'numeric_zero' => true
                                                                ]);
                                                                $kd_rek_standar = $validateRow->setRules(7, 'kd rek standar', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                // ubah $kd_rek_standar menjadi array dan ubah ke json mysql
                                                                $array_kd_rek = explode(",", $kd_rek_standar);
                                                                $keterangan = $validateRow->setRules(8, 'keterangan', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $arrayDataRows = [
                                                                    'kd_wilayah' => $kd_wilayah,
                                                                    'tahun' => $tahun,
                                                                    'kd_aset' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kd_aset),
                                                                    'uraian_kel' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian_kel),
                                                                    'uraian_barang' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian_barang),
                                                                    'spesifikasi' => preg_replace('/(\s\s+|\t|\n)/', ' ', $spesifikasi),
                                                                    'satuan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $satuan),
                                                                    'harga_satuan' => $harga_satuan,
                                                                    'tkdn' => $tkdn,
                                                                    'satuan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $satuan),
                                                                    'kd_rek_akun' => json_encode($array_kd_rek),
                                                                    'kd_rek_akun_asli' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kd_rek_standar),
                                                                    'peraturan' => $id_aturan,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'disable' => 0,
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['uraian_barang', '=', $uraian_barang], ['kd_aset', '=', $kd_aset, 'AND'], ['harga_satuan', '=', $harga_satuan, 'AND']];
                                                                $getWhereArrayData = [['uraian_barang', '=', $uraian_barang], ['kd_aset', '=', $kd_aset, 'AND'], ['harga_satuan', '=', $harga_satuan, 'AND']];
                                                                $no_sort++;
                                                                break;
                                                            case 'satuan':
                                                                $value = $validateRow->setRules(0, 'Value', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $item = $validateRow->setRules(1, 'uraian', [
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $keterangan = $validateRow->setRules(2, 'keterangan', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $sebutan_lain = $validateRow->setRules(3, 'sebutan lainnya', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $arrayDataRows = [
                                                                    'value' => preg_replace('/(\s\s+|\t|\n)/', ' ', $value),
                                                                    'item' => preg_replace('/(\s\s+|\t|\n)/', ' ', $item),
                                                                    'sebutan_lain' => preg_replace('/(\s\s+|\t|\n)/', ' ', $sebutan_lain),
                                                                    'peraturan' => $id_aturan_anggaran,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'disable' => 0,
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['value', '=', $value]];
                                                                $getWhereArrayData = [['value', '=', $value]];
                                                                $no_sort++;
                                                                break;
                                                            case 'wilayah':
                                                                $kode = $validateRow->setRules(0, 'kode Wilayah', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1
                                                                ]);
                                                                $uraian = $validateRow->setRules(1, 'uraian', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $status = $validateRow->setRules(2, 'status', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 3,
                                                                    'in_array' => ['prov', 'kab', 'kota', 'kel', 'kec', 'desa', 'dusun', 'lain']
                                                                ]);
                                                                $jml_kec = $validateRow->setRules(3, 'Jumlah Kecamatan', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $jml_kel = $validateRow->setRules(4, 'Jumlah Kelurahan', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $jml_desa = $validateRow->setRules(5, 'Jumlah Desa', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $luas = $validateRow->setRules(6, 'Luas (km2)', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $penduduk = $validateRow->setRules(7, 'Jumlah Penduduk (jiwa)', [
                                                                    'numeric_zero' => true,
                                                                ]);
                                                                $keterangan = $validateRow->setRules(8, 'keterangan', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $arrayDataRows = [
                                                                    'kode' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kode),
                                                                    'uraian' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian),
                                                                    'status' => preg_replace('/(\s\s+|\t|\n)/', ' ', $status),
                                                                    'jml_kec' => $jml_kec,
                                                                    'jml_kel' => $jml_kel,
                                                                    'jml_desa' => $jml_desa,
                                                                    'luas' => $luas,
                                                                    'penduduk' => $penduduk,
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
                                                            case 'organisasi':
                                                                $kode = $validateRow->setRules(0, 'kode OPD', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $uraian = $validateRow->setRules(1, 'uraian', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $keterangan = $validateRow->setRules(2, 'keterangan', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $arrayDataRows = [
                                                                    'kd_wilayah' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kd_wilayah),
                                                                    'kode' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kode),
                                                                    'uraian' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian),
                                                                    'disable' => 0,
                                                                    'peraturan' => $id_aturan_anggaran,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['kode', '=', $kode]];
                                                                $getWhereArrayData = [['kode', '=', $kode]];
                                                                $no_sort++;
                                                                break;
                                                            case 'mapping':
                                                                $kode_aset = $validateRow->setRules(0, 'kode neraca', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $uraian_aset = $validateRow->setRules(1, 'uraian neraca', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $kode_akun = $validateRow->setRules(2, 'kode akun', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $uraian_akun = $validateRow->setRules(3, 'uraian akun', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 4
                                                                ]);
                                                                $kelompok = $validateRow->setRules(4, 'kelompok', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'in_array' => ['ssh', 'hspk', 'peraturan_daerah', 'asb', 'sbu', 'lain'],
                                                                    'min_char' => 3
                                                                ]);
                                                                $keterangan = $validateRow->setRules(5, 'keterangan', [
                                                                    'sanitize' => 'string',
                                                                ]);
                                                                $arrayDataRows = [
                                                                    'kode_aset' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kode_aset),
                                                                    'uraian_aset' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian_aset),
                                                                    'kode_akun' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kode_akun),
                                                                    'uraian_akun' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian_akun),
                                                                    'kelompok' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kelompok),
                                                                    'disable' => 0,
                                                                    'peraturan' => $id_aturan_anggaran,
                                                                    'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
                                                                ];
                                                                //$string = preg_replace('/\s/', ' ', $string);
                                                                $update_arrayData = [['kode_aset', '=', $kode_aset]];
                                                                $getWhereArrayData = [['kode_aset', '=', $kode_aset]];
                                                                $no_sort++;
                                                                break;
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
                                                                    'kd_wilayah' => $kd_wilayah,
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
                                                                }
                                                                $prog = 0;
                                                                $keg = 0;
                                                                $sub_keg = 0;
                                                                if ($bidang) {
                                                                    $prog = $validateRow->setRules(2, 'prog', [
                                                                        'numeric_zero' => true,
                                                                    ]);
                                                                    if ($prog) {
                                                                        $kode .= "." . $Fungsi->zero_pad($prog, 2);
                                                                        $keg = $validateRow->setRules(3, 'kd keg', [
                                                                            'sanitize' => 'string',
                                                                        ]);
                                                                        if ((int) $keg > 0) {
                                                                            $kode .= "." . $keg;
                                                                            $sub_keg = $validateRow->setRules(4, 'sub_keg', [
                                                                                'numeric_zero' => true,
                                                                            ]);
                                                                            if ($sub_keg) {
                                                                                $kode .= "." . $Fungsi->zero_pad($sub_keg, 4);
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
                                                                    'keg' => $keg,
                                                                    'sub_keg' => (int)$sub_keg,
                                                                    'nomenklatur_urusan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $nomenklatur_urusan),
                                                                    'kinerja' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kinerja),
                                                                    'indikator' => preg_replace('/(\s\s+|\t|\n)/', ' ', $indikator),
                                                                    'satuan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $satuan),
                                                                    'peraturan' => (int)$id_aturan_sub_kegiatan,
                                                                    'kode' => $kode,
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
                                                                    'kd_wilayah' => $kd_wilayah,
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
                                                                    'keterangan' => $keterangan,
                                                                    'tanggal' => date('Y-m-d H:i:s'),
                                                                    'username' => $_SESSION["user"]["username"]
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
                                                                case 'rekanan':
                                                                case 'hspk':
                                                                case 'ssh':
                                                                case 'asb':
                                                                case 'sbu':
                                                                case 'satuan':
                                                                case 'wilayah':
                                                                case 'organisasi':
                                                                case 'mapping':
                                                                case 'aset':
                                                                case 'sub_keg':
                                                                case 'sumber_dana':
                                                                case 'peraturan':
                                                                case 'akun_belanja':
                                                                case 'tujuan_sasaran_renstra':
                                                                case 'renstra':
                                                                    //var_dump($tabel_pakai);
                                                                    $sumRows = $DB->getWhereCustom($tabel_pakai, $getWhereArrayData);
                                                                    $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                                                                    //var_dump($arrayDataRows);
                                                                    if ($jumlahArray <= 0) {
                                                                        $resul = $DB->insert($tabel_pakai, $arrayDataRows);
                                                                        // var_dump($resul);
                                                                        // var_dump($DB->count());
                                                                        if ($DB->count()) {
                                                                            $data['add_row'][] = $sum;
                                                                        } else {
                                                                            $data['gagal'][] = $sum;
                                                                            $data['gagal'][$sum] = $arrayDataRows;
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
                                                                            $data['gagal'][$sum] = $arrayDataRows;
                                                                            //$data['note']['gagal'][] = $sum;
                                                                        }
                                                                    }
                                                                    switch ($tbl) {
                                                                        case 'tujuan_sasaran_renstra':
                                                                            if ($arrayDataRows['kelompok'] == 'tujuan') {
                                                                                $id_tujuan_sasaran = $DB->lastInsertId();
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
                                                                case 'sub_keg_renja':
                                                                case 'sub_keg_dpa':
                                                                    $dinamicData = ['tbl' => $tbl, 'kode' => $kd_sub_keg, 'set' => $arrayDataRows, 'set_non' => $arrayDataRows_nonSubKeg];
                                                                    $cekKodeRek = $Fungsi->kd_sub_keg($dinamicData);
                                                                    break;
                                                                default:
                                                                    # code...
                                                                    break;
                                                            }
                                                        } else {
                                                            switch ($tbl) {
                                                                case 'harga_satuan':
                                                                case 'analisa_alat':

                                                                    break;
                                                                default:
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
                                    $code = 405;
                                    $tambahanNote = 'pengaturan tahun anggaran belum di input';
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
        if ($disableImport) {
            $sukses = false;
            $code = 405;
            $tambahan_pesan = 'pengaturan tahun anggaran belum di input';
        }
        $tambahanNote = (is_array($tambahan_pesan)) ? implode($tambahan_pesan) : $tambahan_pesan;
        $item = array('code' => $code, 'message' => hasilServer[$code] . ", " . $tambahanNote, "note" => $tambahan_pesan);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
    }
}
