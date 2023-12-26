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
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $id_user = $_SESSION["user"]["id"];
        $btn_edit = '';
        //var_dump($_POST);
        //mencoba math/FormulaParser
        /*
$formula = '((i/100)*((1+(i/100))^c))/((1+(i/100))^c-1)';
$precision = 2; // Number of digits after the decimal point
try {
    $parser = new FormulaParser($formula, $precision);
    $parser->setVariables(['i' => 10.18, 'c' => 10]);
    $result = $parser->getResult(); // [0 => 'done', 1 => 16.38]
    var_dump($result);
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}
*/
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
            15 => 'berhasil reset',
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
            51 => 'data telah di salin',
            48 => 'data telah ada dan telah diupdate',
            49 => 'data telah diproses kembali',
            50 => 'kode bisa digunakan',
            51 => 'data telah di salin',
            56 => 'belum ada dokumen pekerjaan yang aktif',
            100 => 'Continue', #server belum menolak header permintaan dan meminta klien untuk mengirim body (isi/konten) pesan
            200 => 'data telah diproses kembali',
            405 => 'data telah diproses kembali tapi tidak menghasilkan result',
            //File
            701 => 'File Tidak Lengkap',
            702 => 'file yang ada terlalu besar',
            703 => 'type file tidak sesuai',
            704 => 'Gagal Upload',
            705 => 'File Telah dibuat',
            707 => 'File Gagal dibuat',
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
        $DB = DB::getInstance();
        $tahun = $_SESSION["user"]["thn_aktif_anggaran"];
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
                        switch ($jenis) {
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
                            case 'analisa_quarry':
                                $kd_analisa = $validate->setRules('kode_quarry', 'Kode Analisa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                $lokasiQuarry = $validate->setRules('lokasi', 'Lokasi', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 200
                                ]);
                                //var_dump($lokasi);
                                $tujuan = $validate->setRules('tujuan', 'Tujuan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 200
                                ]);
                                break;
                            case 'analisa_alat_custom':
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
                                $kapasitas = $validate->setRules('kapasitas', 'kapasitas alat', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $sat_kapasitas = $validate->setRules('sat_kapasitas', 'Satuan Kapasitas', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 200
                                ]);
                                $satuan_pembayaran = '';
                                break;
                            case 'analisa_sda':
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
                        $fileDokumenKegiatan = 0; //untuk melanjutkan yang membutuhkan kd_proyek aktif seperti import harga satuan dll
                        if ($validate->passed()) {
                            // mulai proses impor
                            $filename = $_FILES["file"]["tmp_name"];
                            if ($xlsx = SimpleXLSX::parse($filename)) {
                                //ambil data proyek
                                $tahun_anggaran = 0;
                                $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);
                                $jumlahArray = is_array($data_kd_proyek) ? count($data_kd_proyek) : 0;
                                if ($jumlahArray) {
                                    $kd_proyek = $data_kd_proyek[0]->kd_proyek_aktif;
                                    if (strlen($kd_proyek) > 0) {
                                        # code...
                                        $data['dataProyek'] = $DB->getWhere('nama_pkt_proyek', ['kd_proyek', '=', $kd_proyek]);
                                        //
                                        $jumlahArray = is_array($data['dataProyek']) ? count($data['dataProyek']) : 0;
                                        if ($jumlahArray > 0) {
                                            $fileDokumenKegiatan = 1;
                                            $data['dataProyek'] = $data['dataProyek'][0];
                                            $tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                                            #informasi umum
                                            $informasi = $DB->getWhere('informasi_umum', ['kd_proyek', '=', $kd_proyek]);
                                            $Tk = 0;
                                            $jumlahArray = is_array($informasi) ? count($informasi) : 0;
                                            if ($jumlahArray > 0) {
                                                foreach ($informasi as $key => $value) {
                                                    $kode_informasi = $value->kode;
                                                    ${$value->kode} = $value->nilai;
                                                    switch ($kode_informasi) {
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
                                        } else {
                                            //jangan lanjutkan import harga satuan/analisa, jika $fileDokumenKegiatan == 0;
                                            switch ($jenis) {
                                                case 'analisa_bm':
                                                case 'analisa_ck':
                                                case 'analisa_sda':
                                                case 'analisa_alat_custom':
                                                case 'analisa_alat':
                                                case 'analisa_quarry':
                                                case 'harga_satuan':
                                                case 'monev':
                                                    $code = 56;
                                                    $item = array('code' => $code, 'message' => $hasilServer[$code]);
                                                    $json = array('success' => false, 'data' => $data, 'error' => $item);
                                                    echo json_encode($json);
                                                    return;
                                                    break;
                                            }
                                        }
                                    }

                                    //$tahun_anggaran = $data['dataProyek'][0]->tahun_anggaran;
                                }
                                //var_dump($tahun_anggaran);
                                $code = 1;
                                $sukses = true;
                                $RowHeaderValidate = [];
                                //menentukan data
                                switch ($jenis) {
                                    case 'proyek':
                                        break;
                                    case 'rekanan':
                                        $tabel_pakai = 'rekanan';
                                        $RowHeaderValidate = ['Nama Perusahaan/Pribadi', 'Alamat', 'Email', 'NPWP', 'Nomor Rekening Bank', 'Nama Bank Rekening', 'Atas Nama Bank Rekening', 'Nama Penanggung Jawab', 'Jabatan', 'Nomor KTP', 'Alamat Penanggung Jawab', 'Nomor Akta Pendirian', 'Tanggal Akta Pendirian', 'Lokasi Notaris Pendirian', 'Nama Notaris Pendirian', 'Nomor Akta Perubahan', 'Tanggal Akta Perubahan', 'Lokasi Notaris Perubahan', 'Nama Notaris Perubahan', 'Nama Pelaksana', 'Jabatan Pelaksana', 'KETERANGAN'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'monev':
                                        if (strlen($kd_proyek) <= 0) {
                                        }
                                        $tabel_pakai = 'monev';
                                        $RowHeaderValidate = ['MATA PEMBAYARAN', 'URAIAN', 'VOLUME', 'SATUAN', 'JUMLAH HARGA (Rp) (NON PPN)', 'TANGGAL', 'REALISASI FISIK (sat)', 'REALISASI KEUANGAN (Rp) (NON PPN)', 'KETERANGAN'];
                                        //var_dump($RowHeaderValidate);
                                        $count_col_min = count($RowHeaderValidate);
                                        $id_rab = 0;
                                        $kd_analisa = '';
                                        $uraian = '';
                                        $volume = 0;
                                        $satuan = '';
                                        $jumlah_harga = 0;
                                        break;
                                    case 'divisi':
                                        $tabel_pakai = 'divisi';
                                        $RowHeaderValidate = ['kode', 'Uraian', 'satuan', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'harga_satuan':
                                        $count_col_min = 8;
                                        $tabel_pakai = 'harga_sat_upah_bahan';
                                        $arrayStruktur = ['kd_proyek', 'kode', 'jenis', 'uraian', 'satuan', 'harga_satuan', 'sumber_data', 'spesifikasi', 'keterangan', 'tahun', 'periode', 'status'];
                                        $RowHeaderValidate = ['Jenis', 'Kode', 'Uraian', 'Satuan', 'Harga Satuan', 'Sumber Data', 'Spesifikasi', 'Keterangan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'analisa_alat':
                                        $tabel_pakai = 'analisa_alat';
                                        $arrayStruktur = ['kd_proyek', 'kode', 'jenis_peralatan', 'tenaga', 'kapasitas', 'sat_kapasitas', 'harga', 'umur', 'jam_kerja_1_tahun', 'nilai_sisa', 'faktor_pengembalian_mdl', 'biaya_pengembalian_mdl', 'asuransi', 'total_biaya_pasti', 'bahan_bakar', 'bahan_bakar2', 'bahan_bakar3', 'minyak_pelumas', 'biaya_bbm', 'koef_workshop', 'biaya_workshop', 'koef_perbaikan', 'biaya_perbaikan', 'jumlah_operator', 'upah_operator', 'jumlah_pembantu_ope', 'upah_pembantu_ope', 'total_biaya_operasi', 'total_biaya_sewa', 'keterangan', 'ket_alat', 'ketentuan_tambahan'];
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
                                        //print_r($bbmUpah);
                                        //var_dump($M21);
                                        //ambil data hatga satuan upah
                                        $RowHeaderValidate = ['JENIS PERALATAN', 'KODE ALAT', 'TENAGA ALAT(HP)', 'KAPASITAS ALAT (Cp)', 'SATUAN KAPASITAS ALAT', 'HARGA ALAT YANG DIGUNAKAN', 'UMUR ALAT (TAHUN)', 'JAM KERJA 1 TAHUN', 'Jumlah Operator', 'Jumlah Pembantu Operator', 'Keterangan Alat', 'Ketentuan Tambahan'];
                                        $count_col_min = count($RowHeaderValidate);
                                        break;
                                    case 'satuan':
                                        $count_col_min = 4;
                                        $tabel_pakai = 'daftar_satuan';
                                        $RowHeaderValidate = ['value', 'item', 'Keterangan', 'Sebutan lain'];
                                        break;
                                    case 'analisa_quarry':
                                        $jenisImporData = 'koef_harga';
                                        $count_col_min = 8;
                                        $tabel_pakai = 'analisa_quarry';
                                        $getWhereDelete = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND']];
                                        $RowHeaderValidate = ['Nomor', 'Uraian', 'kode', 'Satuan', 'Koefisien', 'Harga Satuan', 'Rumus', 'Keterangan'];
                                        break;
                                    case 'analisa_alat_custom':
                                        $jenisImporData = 'koef';
                                        $count_col_min = 8;
                                        $tabel_pakai = 'analisa_alat_custom';
                                        $getWhereDelete = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND']];
                                        //ambil data suku bunga i dari informasi umum
                                        //$sukuBunga_i=i
                                        $sukuBunga_i = $DB->getWhereArray("informasi_umum", [['kd_proyek', '=', $kd_proyek], ['kode', '=', 'suku_bunga_i', 'AND']]);
                                        $sukuBunga_i = get_object_vars($sukuBunga_i[0])["nilai"];
                                        $i = $sukuBunga_i;
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
                                        $RowHeaderValidate = ['Nomor', 'Uraian', 'kode', 'Satuan', 'Koefisien', 'Harga Satuan', 'Rumus', 'Keterangan'];
                                        break;
                                    case 'analisa_bm':
                                        $jenisImporData = 'koef_harga';
                                        $count_col_min = 8;
                                        $tabel_pakai = 'analisa_pekerjaan_bm';
                                        $getWhereDelete = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND']];
                                        $RowHeaderValidate = ['Nomor', 'Uraian', 'kode', 'Satuan', 'Koefisien', 'Harga Satuan', 'Rumus', 'Keterangan'];
                                        break;
                                    case 'analisa_sda':
                                        $jenisImporData = 'koef_harga';
                                        $count_col_min = 8;
                                        $tabel_pakai = 'analisa_pekerjaan_sda';
                                        $getWhereDelete = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND']];
                                        $RowHeaderValidate = ['Nomor', 'Uraian', 'kode', 'Satuan', 'Koefisien', 'Harga Satuan', 'Rumus', 'Keterangan'];
                                        break;
                                    case 'analisa_ck':
                                        $kd_analisa = '';
                                        $count_col_min = 7; //kd_analisa,kode,uraian, satuan, koefjenis pek, rumus
                                        $tabel_pakai = 'analisa_pekerjaan_ck';
                                        $RowHeaderValidate = ['Kode Analisa', 'Kode Uraian', 'Uraian', 'Satuan', 'Koefisien',    'JENIS PEKERJAAN', 'Rumus Harga Satuan'];
                                        break;
                                    case 'x':
                                        break;
                                    default:
                                }
                                //finish menentukan data
                                $sum = 0;
                                $rumusKode = [];
                                $rumusKode['Tk'] = $Tk;
                                $DataKodeKoefHarga = [];
                                $arrayDataRowsArray = [];
                                $tampungKode = [];
                                $tampungArrayDataRows = [];
                                $ketTampung = '';
                                $sum_jumlah_harga = 0;
                                //array_push($data['note'],'row update','add row','gagal');
                                //array_push($data['note'],'row update','add row','gagal');
                                //$data['note'][] = 'row update';
                                //$data['note'][] = 'add row';
                                //$data['note'][] = 'gagal';
                                //tampung baris yang nilai kolom nomor == kd_analisa
                                $rowKlmNomorsamaKdAnalisa = [];
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
                                    if ($r == ($jml_header - 1)) {
                                        if (count($RowHeaderValidate) > 0) {
                                            $getData = array_map('strtolower', $getData); //menjadikan huruf kecil semua value
                                            //var_dump($getData);
                                            $validateTabel = new Validate($getData);
                                            //var_dump($validateTabel);
                                            foreach ($RowHeaderValidate as $keyValid => $valueValid) {
                                                $headerValid = strtolower($valueValid);
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
                                                    switch ($jenis) {
                                                        case 'rekanan':
                                                            //$RowHeaderValidate = ['Nama Perusahaan/Pribadi', 'Alamat', 'Email', 'NPWP', 'Nomor Rekening Bank', 'Nama Bank Rekening', 'Atas Nama Bank Rekening', 'Nama Penanggung Jawab', 'Jabatan', 'Nomor KTP', 'Alamat Penanggung Jawab', 'Nomor Akta Pendirian', 'Tanggal Akta Pendirian', 'Lokasi Notaris Pendirian', 'Nama Notaris Pendirian', 'Nomor Akta Perubahan', 'Tanggal Akta Perubahan', 'Lokasi Notaris Perubahan', 'Nama Notaris Perubahan', 'Nama Pelaksana', 'Jabatan Pelaksana', 'KETERANGAN'];
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
                                                            ]);//
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
                                                                'regexp' => '/(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/','required' => true,
                                                                'max_char' => 100
                                                            ]);
                                                            $tanggal = $Fungsi->tanggal($tanggal)['tanggalMysql'];

                                                            // $tanggal = $validateRow->setRules(5, 'Tanggal Realisasi', [
                                                            //     'sanitize' => 'string',
                                                            //     'required' => true,
                                                            //     'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', //'/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/',
                                                            //     'min_char' => 8
                                                            // ]);
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
                                                        case 'analisa_alat_custom':
                                                        case 'analisa_sda':
                                                        case 'analisa_quarry':
                                                        case 'analisa_bm':
                                                            $nomor = $validateRow->setRules(0, 'Nomor', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $uraian = $validateRow->setRules(1, 'Uraian', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $kode = $validateRow->setRules(2, 'Kode Uraian', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $satuan = $validateRow->setRules(3, 'Satuan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            //$getData[4]=($getData[4]) ? : 0 ;
                                                            $koefisien = $validateRow->setRules(4, 'Koefisien', [
                                                                'numeric' => true
                                                            ]);
                                                            //$getData[5]=($getData[5]) ? : 0 ;
                                                            //var_dump($validateRow->setRules(5));
                                                            $harga_satuan = $validateRow->setRules(5, 'Harga Satuan', [
                                                                'numeric' => true
                                                            ]);
                                                            $rumus = $validateRow->setRules(6, 'Rumus', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $keterangan = $validateRow->setRules(7, 'Rumus', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $jumlah_harga = (float)$koefisien * (float)$harga_satuan;
                                                            if ($nomor == $kd_analisa) {
                                                                if ($jenis == 'analisa_quarry') {
                                                                    $keterangan = '{"lokasi":"' . $lokasiQuarry . '", "tujuan":"' . $tujuan . '"}'; //`{"lokasi":"$lokasi", "tujuan":"$tujuan"}`;
                                                                }
                                                            }
                                                            $arrayDataRows = [
                                                                'kd_proyek' => $kd_proyek,
                                                                'kd_analisa' => $kd_analisa,
                                                                'nomor' => $nomor,
                                                                'uraian' => $uraian,
                                                                'kode' => $kode,
                                                                'satuan' => $satuan,
                                                                'koefisien' => (float)$koefisien,
                                                                'harga_satuan' => (float)$harga_satuan,
                                                                'jumlah_harga' => (float)$jumlah_harga,
                                                                'rumus' => $rumus,
                                                                'jenis_kode' => null,
                                                                'keterangan' => $keterangan,
                                                                'no_sortir' => $no_sort
                                                            ];

                                                            if ($validateRow->passed()) {
                                                                $arrayDataRowsArray[$sum] = $arrayDataRows;
                                                                if ($nomor == $kd_analisa) {
                                                                    $rowKlmNomorsamaKdAnalisa[] = $arrayDataRows;
                                                                    if ($jenis == 'analisa_quarry') {
                                                                        # code...
                                                                    }
                                                                }
                                                            } else {
                                                                //var_dump($validateRow->getError());
                                                                $array_key0 = array_keys($validateRow->getError())[0];
                                                                $haystack = $validateRow->getError()[$array_key0];
                                                                $data['gagal'][] = [$sum, $haystack];
                                                            }
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
                                                        case 'analisa_ck':
                                                            $nomor = "";
                                                            $kd_analisa_temporer = $getData[0];
                                                            $keterangan = $validateRow->setRules(5, 'keterangan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            if (strlen($keterangan) > 0 && strlen($kd_analisa_temporer) > 0) {
                                                                $ketTampung = $keterangan;
                                                            }
                                                            //var_dump('$kd_analisa_temporer = '.$kd_analisa_temporer);
                                                            if (strlen(($kd_analisa_temporer) > 0 || strlen($kd_analisa) < 0)) {
                                                                #1.buatkan jumlah analisa kd_analisa yang sama dan tampung di kolom koefisien
                                                                if (!empty($kd_analisa)) {
                                                                    if (strlen($kd_analisa) > 0) {
                                                                        # code buat jumlah analisa
                                                                        $getWhere = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['kode', '=', $kd_analisa, 'AND']];
                                                                        $sumRows = $DB->getWhereArray($tabel_pakai, $getWhere);
                                                                        //var_dump(count($sumRows));
                                                                        $no_sort++;
                                                                        $dataInsert = [
                                                                            'kd_proyek' => $kd_proyek,
                                                                            'kd_analisa' => $kd_analisa,
                                                                            'kode' => $kd_analisa,
                                                                            'nomor' => $kd_analisa,
                                                                            'uraian' => $ketTampung,
                                                                            'koefisien' => $sum_jumlah_harga,
                                                                            'satuan' => '',
                                                                            'harga_satuan' => 0,
                                                                            'jumlah_harga' => 0,
                                                                            'jenis_kode' => 'summary:' . $kd_analisa,
                                                                            'no_sortir' => $no_sort
                                                                        ];
                                                                        $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                                                                        if ($jumlahArray <= 0) {
                                                                            $resul = $DB->insert($tabel_pakai, $dataInsert);
                                                                            //var_dump($DB->count());
                                                                            if ($DB->count()) {
                                                                                //array_push($data['add_row'], $Sum);
                                                                                $data['add_row'][] = $sum;
                                                                            }
                                                                            //var_dump('insert:' . $resul);
                                                                        } else {
                                                                            //update row
                                                                            $resul = $DB->update_array($tabel_pakai, $dataInsert, $getWhere);
                                                                            if ($DB->count()) {
                                                                                //array_push($data['row_update'], $sum);
                                                                                $data['row_update'][] = $sum;
                                                                                //$data['note']['row update'][] = $sum;
                                                                            } else {
                                                                                array_push($data['gagal'], $sum);
                                                                                $data['gagal'][] = $sum;
                                                                                //$data['note']['gagal'][] = $sum;
                                                                            }
                                                                            //var_dump($resul);
                                                                        }
                                                                    }
                                                                }

                                                                #2.selesaikan rumus di $tampungArrayDataRows
                                                                $kd_analisa = $validateRow->setRules(0, 'Kode Analisa', [
                                                                    'sanitize' => 'string',
                                                                    'required' => true,
                                                                    'min_char' => 1,
                                                                    'max_char' => 100
                                                                ]);
                                                                //var_dump('$kd_analisa = '.$kd_analisa);
                                                                //var_dump($tampungArrayDataRows);
                                                                $sum_jumlah_harga = 0;
                                                                $tampungArrayDataRows = [];
                                                                $no_sort = 0; //nomor sortir
                                                            }
                                                            $kode = $validateRow->setRules(1, 'Kode Uraian', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $uraian = $validateRow->setRules(2, 'Uraian', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $satuan = $validateRow->setRules(3, 'Satuan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $koefisien = $validateRow->setRules(4, 'Koefisien', [
                                                                'numeric' => true
                                                            ]);
                                                            $rumus = $validateRow->setRules(6, 'Rumus', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            // jumlahkan jenis upah, bahan
                                                            //cari analisa alat atau harga satuan untuk mendapatkan harga
                                                            $TempData = 0;
                                                            $harga_satuan = 0;
                                                            //cari di upah, bahan
                                                            //var_dump('sampe1');
                                                            $jenis_kode = $kode;
                                                            if (strlen($rumus) > 0) {
                                                                $rumus_temporer = $rumus;
                                                                //var_dump($rumus_temporer);
                                                                foreach ($tampungArrayDataRows as $baris) {
                                                                    //var_dump($baris['kode']);
                                                                    //var_dump($baris['jumlah_harga']);
                                                                    $rumus_temporer = str_replace($baris['kode'], $baris['jumlah_harga'], $rumus_temporer);
                                                                    //var_dump('$rumus_temporer II:'.$rumus_temporer);
                                                                }
                                                                try {
                                                                    $parser = new FormulaParser($rumus_temporer);
                                                                    //$parser->setVariables($setVariabel);
                                                                    $result = $parser->getResult();
                                                                    if ($result[1] !== 'Syntax error') {
                                                                        $harga_satuan = (float)$result[1];
                                                                    }
                                                                } catch (\Exception $e) {
                                                                    $harga_satuan = 0;
                                                                    //echo $e->getMessage(), "\n";
                                                                    //$sum
                                                                }
                                                            } else {
                                                                //cari basic price
                                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kode, 'AND'], ['jenis', '!=', "royalty", 'AND']];
                                                                $get_data = $DB->getWhereArray('harga_sat_upah_bahan', $condition);
                                                                if (count((array)$get_data) > 0) {
                                                                    $harga_satuan = $get_data[0]->harga_satuan;
                                                                    $jenis_kode = $get_data[0]->jenis;
                                                                }
                                                                //cari di analisa alat
                                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kode, 'AND']];
                                                                $get_data = $DB->getWhereArray('analisa_alat', $condition);
                                                                if (count((array)$get_data) > 0) {
                                                                    $harga_satuan = $get_data[0]->total_biaya_sewa;
                                                                    $jenis_kode = 'peralatan';
                                                                }
                                                                //cari di analisa quarry
                                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kode, 'AND'], ['kd_analisa', '=', 'nomor', 'AND']];
                                                                $get_data = $DB->getWhereArray('analisa_quarry', $condition);
                                                                if (count((array)$get_data) > 0) {
                                                                    $harga_satuan = $get_data[0]->koefisien;
                                                                }
                                                            }
                                                            //var_dump('sampe2');
                                                            $jumlah_harga = (float)$koefisien * (float)$harga_satuan;
                                                            $sum_jumlah_harga += (float)$jumlah_harga;
                                                            $no_sort++;
                                                            if ($kd_analisa) {
                                                                $arrayDataRows = [
                                                                    'kd_proyek' => $kd_proyek,
                                                                    'kd_analisa' => $kd_analisa,
                                                                    'kode' => $kode,
                                                                    'nomor' => $nomor,
                                                                    'uraian' => $uraian,
                                                                    'koefisien' => (float)$koefisien,
                                                                    'satuan' => $satuan,
                                                                    'harga_satuan' => (float)$harga_satuan,
                                                                    'jumlah_harga' => (float)$jumlah_harga,
                                                                    'rumus' => $rumus,
                                                                    'jenis_kode' => $jenis_kode,
                                                                    'keterangan' => $keterangan,
                                                                    'no_sortir' => $no_sort
                                                                ];
                                                                $tampungArrayDataRows[$kode] = $arrayDataRows;
                                                                //var_dump($arrayDataRows);
                                                                $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['kd_analisa', "=", $kd_analisa, 'AND'], ['kode', "=", $kode, 'AND']];
                                                                $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kd_analisa', "=", $kd_analisa, 'AND'], ['kode', "=", $kode, 'AND']];
                                                            }

                                                            // buatkan penampungan array untuk kd_analisa yang sama
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
                                                        case 'proyek':
                                                            break;
                                                        case 'analisa_alat':
                                                            $jenis_peralatan = $validateRow->setRules(0, 'Jenis Peralatan', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1
                                                            ]);
                                                            $kode = $validateRow->setRules(1, 'kode', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 100,
                                                                'uniqueArray' => [$tabel_pakai, 'kode', [['kd_proyek', '=', $kd_proyek]]]
                                                            ]);
                                                            $tenaga = $validateRow->setRules(2, 'Tenaga Alat', [
                                                                'numeric' => true
                                                            ]);
                                                            $kapasitas = $validateRow->setRules(3, 'Kapasitas Alat Alat', [
                                                                'numeric' => true
                                                            ]);
                                                            $sat_kapasitas = $validateRow->setRules(4, 'satuan kapasitas', [
                                                                'sanitize' => 'string',
                                                                'required' => true,
                                                                'min_char' => 1,
                                                                'max_char' => 200
                                                            ]);
                                                            $harga_pakai = $validateRow->setRules(5, 'Harga Pakai Alat', [
                                                                'numeric' => true
                                                            ]);
                                                            $umur = $validateRow->setRules(6, 'Umur Alat(tahun)', [
                                                                'numeric' => true
                                                            ]);
                                                            $jam_kerja_1_tahun = $validateRow->setRules(7, 'jam kerja 1 tahun', [
                                                                'numeric' => true
                                                            ]);
                                                            $jumlah_operator = $validateRow->setRules(8, 'jumlah_operator', [
                                                                'numeric' => true
                                                            ]);
                                                            $jumlah_pembantu_ope = $validateRow->setRules(9, 'jumlah_pembantu_ope', [
                                                                'numeric' => true
                                                            ]);
                                                            $ket_alat = $validateRow->setRules(10, 'Keterangan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            $ketentuan_tambahan = $validateRow->setRules(11, 'Tambahan', [
                                                                'sanitize' => 'string'
                                                            ]);
                                                            //Mulai hitung alat
                                                            $nilai_sisa = 0.1 * $harga_pakai;
                                                            if ($umur == 0) {
                                                                $faktor_pengembalian_mdl = 0;
                                                            } else {
                                                                $faktor_pengembalian_mdl = (($sukuBunga_i / 100) * pow((1 + ($sukuBunga_i / 100)), $umur)) / (pow((1 + ($sukuBunga_i / 100)), $umur) - 1);
                                                                //($sukuBunga_i÷100×(1+$sukuBunga_i÷100)^$umur )÷((1+$sukuBunga_i÷100)^$umur −1)
                                                            }
                                                            if ($jam_kerja_1_tahun < 1300) {
                                                                $keterangan = 'ringan';
                                                            } else if ($jam_kerja_1_tahun < 1700) {
                                                                $keterangan = 'sedang';
                                                            } else if ($jam_kerja_1_tahun < 2100) {
                                                                $keterangan = 'berat';
                                                            } else {
                                                                $keterangan = 'berat';
                                                            }
                                                            $biaya_pengembalian_mdl = (($harga_pakai - $nilai_sisa) * $faktor_pengembalian_mdl) / $jam_kerja_1_tahun;
                                                            $asuransi = (0.002 * $harga_pakai) / $jam_kerja_1_tahun;
                                                            $total_biaya_pasti = $biaya_pengembalian_mdl + $asuransi;
                                                            if ($keterangan == 'berat') {
                                                                $koef_workshop = 0.028;
                                                            } else if ($keterangan == 'ringan') {
                                                                $koef_workshop = 0.022;
                                                            } else {
                                                                $koef_workshop = (0.028 + 0.022) / 2;
                                                            }
                                                            $biaya_workshop = ($koef_workshop * $harga_pakai) / $jam_kerja_1_tahun;
                                                            //$koef_perbaikan=IF(AM78="Pek. Berat";0,09;IF(AM78="Pek. Ringan";0,064;(0,064+0,09)/2))
                                                            if ($keterangan == 'berat') {
                                                                $koef_perbaikan = 0.09;
                                                            } else if ($keterangan == 'ringan') {
                                                                $koef_perbaikan = 0.064;
                                                            } else {
                                                                $koef_perbaikan = (0.09 + 0.064) / 2;
                                                            }
                                                            $biaya_perbaikan = ($koef_perbaikan * $harga_pakai) / $jam_kerja_1_tahun;
                                                            if ($keterangan == 'berat') {
                                                                $bahan_bakar = 0.12;
                                                            } else if ($keterangan == 'ringan') {
                                                                $bahan_bakar = 0.1;
                                                            } else {
                                                                $bahan_bakar = (0.1 + 0.12) / 2;
                                                            }
                                                            //$minyak_pelumas= ($keterangan=='berat') ? 0.0035 : $bahan_bakar=($keterangan=='ringan') ? 0.0025 : (0.0025+0.0035)/2;
                                                            if ($keterangan == 'berat') {
                                                                $minyak_pelumas = 0.0035;
                                                            } else if ($keterangan == 'ringan') {
                                                                $minyak_pelumas = 0.0025;
                                                            } else {
                                                                $minyak_pelumas = (0.0035 + 0.0025) / 2;
                                                            }
                                                            $bahan_bakar2 = 0;
                                                            $bahan_bakar1 = 0;
                                                            $bahan_bakar3 = 0;
                                                            $upah_operator = 0;
                                                            $upah_pembantu_ope = 0;
                                                            $total_biaya_operasi = 0;
                                                            $total_biaya_sewa = 0;
                                                            $p = $tenaga;
                                                            $c = $kapasitas;
                                                            $a = $umur;
                                                            $w = $jam_kerja_1_tahun;
                                                            $b = $harga_pakai;
                                                            $y = $nilai_sisa;
                                                            $d = $faktor_pengembalian_mdl;
                                                            $v = $biaya_pengembalian_mdl;
                                                            $f = $asuransi;
                                                            $g = $total_biaya_pasti;
                                                            $h = $bahan_bakar;
                                                            $l = $minyak_pelumas;
                                                            $o = $upah_operator;
                                                            $r = $upah_pembantu_ope;
                                                            $t = $total_biaya_operasi;
                                                            $u = $total_biaya_sewa;
                                                            $x = 0;
                                                            $setVariabel = ['p' => $p, 'c' => $c, 'a' => $a, 'w' => $w, 'b' => $b, 'y' => $y, 'd' => $d, 'v' => $v, 'f' => $f, 'x' => $x];
                                                            $resultRumus = [];
                                                            //BUATKAN DUMMY SELURUH Value yang ada kodenya
                                                            $cekJson = NULL;
                                                            //$arrayVal = json_decode($ketentuan_tambahan, true);
                                                            // var_dump(substr_count($ketentuan_tambahan, '"'));
                                                            // var_dump(substr_count($ketentuan_tambahan, "&#34;"));//utf bukan utf8 general ci
                                                            // var_dump($ketentuan_tambahan);
                                                            $ketentuan_tambahan = str_replace("&#34;", '"', $ketentuan_tambahan);
                                                            $arrayVal = ($ketentuan_tambahan == NULL) ? json_decode('{}', true) : json_decode($ketentuan_tambahan, true);
                                                            $arrayVal = ($arrayVal == NULL) ? json_decode('{}', true) : json_decode($ketentuan_tambahan, true);
                                                            // var_dump($arrayVal);
                                                            $rumus = NULL;
                                                            if (count($arrayVal) > 0) {
                                                                foreach ($arrayVal as $key => $val) {
                                                                    //var_dump($key);
                                                                    //var_dump($val);
                                                                    $tingkat1 = $val;
                                                                    $pengenal = $key;
                                                                    if (array_key_exists('koef', $tingkat1) && $key == 'X') {
                                                                        $x = $tingkat1['koef'];
                                                                    }
                                                                    foreach ($tingkat1 as $key2 => $val2) {
                                                                        //var_dump($key2);
                                                                        //var_dump($val2);
                                                                        if ($key2 == 'rumus') {
                                                                            $rumus = str_replace("M22", (string)$M22, $val2);
                                                                            $rumus = str_replace('M21', $M21, $rumus);
                                                                            $rumus = str_replace("L04", (string)$L04, $rumus);
                                                                            $rumus = str_replace("L04", (string)$L05, $rumus);
                                                                            $rumus = strtolower($rumus);
                                                                            $setVariabel = ['p' => $p, 'c' => $c, 'a' => $a, 'w' => $w, 'b' => $b, 'y' => $y, 'd' => $d, 'v' => $v, 'f' => $f, 'x' => $x];
                                                                            //var_dump($setVariabel);
                                                                            try {
                                                                                $parser = new FormulaParser($rumus);
                                                                                $parser->setVariables($setVariabel);
                                                                                $resultRumus[$pengenal] = $parser->getResult();
                                                                            } catch (\Exception $e) {
                                                                                //echo $e->getMessage(), "\n";
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                $cekJson = count($arrayVal);
                                                            }
                                                            //var_dump($resultRumus);
                                                            if (array_key_exists('bahan_bakar2', $resultRumus)) {
                                                                $bahan_bakar2 = $resultRumus['bahan_bakar2'][1];
                                                            }
                                                            if (array_key_exists('bahan_bakar3', $resultRumus)) {
                                                                $bahan_bakar3 = $resultRumus['bahan_bakar3'][1];
                                                            }
                                                            //var_dump($resultRumus);
                                                            $bahan_bakar1 = ($bahan_bakar * $tenaga * $M21) + ($minyak_pelumas * $tenaga * $M22);
                                                            $biaya_bbm = $bahan_bakar1 + $bahan_bakar2 + $bahan_bakar3;
                                                            $upah_operator = $jumlah_operator * $L04;
                                                            $upah_pembantu_ope = $jumlah_pembantu_ope * $L05;
                                                            $total_biaya_operasi = $biaya_bbm + $biaya_workshop + $biaya_perbaikan + $upah_operator + $upah_pembantu_ope;
                                                            $total_biaya_sewa = $total_biaya_pasti + $total_biaya_operasi;
                                                            $arrayDataRows = [
                                                                'kd_proyek' => $kd_proyek,
                                                                'kode' => $kode,
                                                                'jenis_peralatan' => $jenis_peralatan,
                                                                'tenaga' => $tenaga, 'kapasitas' => $kapasitas,
                                                                'sat_kapasitas' => $sat_kapasitas,
                                                                'harga_pakai' => $harga_pakai,
                                                                'umur' => $umur,
                                                                'jam_kerja_1_tahun' => $jam_kerja_1_tahun,
                                                                'nilai_sisa' => $nilai_sisa,
                                                                'faktor_pengembalian_mdl' => $faktor_pengembalian_mdl,
                                                                'biaya_pengembalian_mdl' => $biaya_pengembalian_mdl,
                                                                'asuransi' => $asuransi,
                                                                'total_biaya_pasti' => $total_biaya_pasti,
                                                                'bahan_bakar' => $bahan_bakar,
                                                                'bahan_bakar1' => $bahan_bakar1,
                                                                'bahan_bakar2' => $bahan_bakar2,
                                                                'bahan_bakar3' => $bahan_bakar3,
                                                                'minyak_pelumas' => $minyak_pelumas,
                                                                'biaya_bbm' => $biaya_bbm,
                                                                'koef_workshop' => $koef_workshop,
                                                                'biaya_workshop' => $biaya_workshop,
                                                                'koef_perbaikan' => $koef_perbaikan,
                                                                'biaya_perbaikan' => $biaya_perbaikan,
                                                                'jumlah_operator' => $jumlah_operator,
                                                                'upah_operator' => $upah_operator,
                                                                'jumlah_pembantu_ope' => $jumlah_pembantu_ope,
                                                                'upah_pembantu_ope' => $upah_pembantu_ope,
                                                                'total_biaya_operasi' => $total_biaya_operasi,
                                                                'total_biaya_sewa' => $total_biaya_sewa,
                                                                'keterangan' => $keterangan,
                                                                'ket_alat' => $ket_alat,
                                                                'ketentuan_tambahan' => $ketentuan_tambahan
                                                            ];
                                                            //var_dump($arrayDataRows);
                                                            $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                                            $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
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
                                                            //var_dump($arrayDataRows);
                                                            break;
                                                         //realisasi monev
                                                            //$RowHeaderValidate = ['MATA PEMBAYARAN', 'URAIAN', 'VOLUME', 'SATUAN', 'JUMLAH HARGA (Rp) (NON PPN)', 'TANGGAL', 'REALISASI FISIK (sat)', 'REALISASI KEUANGAN (Rp) (NON PPN)', 'KETERANGAN'];

                                                            
                                                        case 'x':
                                                            break;
                                                        default:
                                                    }
                                                    //FINISH PROSES VALIDASI
                                                    //=====================================
                                                    //PROSES SEARCH DATA LAMA/INSERT/UPDATE
                                                    //=====================================
                                                    if ($validateRow->passed()) {
                                                        switch ($jenis) {
                                                            case 'rekanan':
                                                            case 'divisi':
                                                            case 'satuan':
                                                            case 'harga_satuan':
                                                            case 'analisa_alat':
                                                            case 'analisa_ck':
                                                            case 'monev':
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
                                                        switch ($jenis) {
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
                                            $hasilServer = [401 => 'ikuti format header(kop) tabel :<br>' . $keterangan];
                                        }
                                    }
                                }
                                //tambaham 1 baris untuk analisa CK
                                if ($jenis == 'analisa_ck' && $validateTabel->passed()) {
                                    if ($validateRow->passed()) {
                                        $getWhere = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['kode', '=', $kd_analisa, 'AND']];
                                        $sumRows = $DB->getWhereArray($tabel_pakai, $getWhere);
                                        //var_dump(count($sumRows));
                                        $no_sort++;
                                        $dataInsert = [
                                            'kd_proyek' => $kd_proyek,
                                            'kd_analisa' => $kd_analisa,
                                            'kode' => $kd_analisa,
                                            'nomor' => $kd_analisa,
                                            'uraian' => $ketTampung,
                                            'koefisien' => $sum_jumlah_harga,
                                            'satuan' => '',
                                            'harga_satuan' => 0,
                                            'jumlah_harga' => 0,
                                            'jenis_kode' => 'summary:' . $kd_analisa,
                                            'no_sortir' => $no_sort
                                        ];
                                        if (count($sumRows) <= 0) {
                                            $resul = $DB->insert($tabel_pakai, $dataInsert);
                                            //var_dump($DB->count());
                                            if ($DB->count()) {
                                                //array_push($data['add_row'], $Sum);
                                                $data['add_row'][] = $sum;
                                            }
                                            //var_dump('insert:' . $resul);
                                        } else {
                                            //update row
                                            $resul = $DB->update_array($tabel_pakai, $dataInsert, $getWhere);
                                            if ($DB->count()) {
                                                //array_push($data['row_update'], $sum);
                                                $data['row_update'][] = $sum;
                                                //$data['note']['row update'][] = $sum;
                                            } else {
                                                array_push($data['gagal'], $sum);
                                                $data['gagal'][] = $sum;
                                                //$data['note']['gagal'][] = $sum;
                                            }
                                            //var_dump($resul);
                                        }
                                    }
                                }
                                //===============================================
                                //jika selesai pembacaan data baris hitung rumus
                                //khusus untuk analisa yang menggunakan rumus
                                //===============================================
                                if ($validateTabel->passed()) {
                                    switch ($jenis) {
                                        case 'analisa_alat_custom':
                                        case 'analisa_sda':
                                        case 'analisa_quarry':
                                        case 'analisa_bm':
                                            // buatkan row baru jika tidak ada baris yang kolom nomor == $kd_analisa
                                            //$rowKlmNomorsamaKdAnalisa

                                            //var_dump($arrayDataRowsArray);
                                            $kondisiDel = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND']];
                                            $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisiDel);
                                            if ($DB->count() > 0) {
                                                $code = 4;
                                            } else {
                                                $code = 35;
                                            }
                                            //var_dump($arrayDataRowsArray);
                                            $hasil_insert = $Fungsi->analisa($kd_proyek, $kd_analisa, $arrayDataRowsArray, $jenisImporData, $jenis);
                                            $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                            $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                            //var_dump($hasil_insert);
                                            //insert data
                                            $subSum = 1;
                                            foreach ($hasil_insert as $key => $value) {
                                                //$kode = $hasil_insert['kode'];
                                                $kode = $value['kode'];
                                                $update_arrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                                $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $kode, 'AND']];
                                                $jumlahArray = 0;
                                                if (strlen($kode) > 0) {
                                                    $sumRows = $DB->getWhereArray($tabel_pakai, $getWhereArrayData);
                                                    $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                                                }
                                                $kd_analisa_value = $value['kd_analisa'];
                                                $nomor_value = $value['nomor'];
                                                if ($jumlahArray <= 0) {
                                                    $resul = $DB->insert($tabel_pakai, $value);
                                                    if ($DB->count()) {
                                                        $data['add_row'][] =  $subSum;
                                                        //jika nomor=kd_analisa
                                                    }
                                                } else {
                                                    //update row
                                                    $resul = $DB->update_array($tabel_pakai, $value, $update_arrayData);
                                                    $jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                                    if ($resul) {
                                                        //array_push($data['row_update'], $sum);
                                                        $data['row_update'][] =  $subSum;
                                                        //$data['note']['row update'][] = $sum;
                                                    } else {
                                                        //array_push($data['gagal'],  $subSum);
                                                        $data['gagal'][] =  $subSum;
                                                        //$data['note']['gagal'][] = $sum;
                                                    }
                                                    //var_dump($resul);
                                                }
                                                if ($jenis == 'analisa_alat_custom' && $kd_analisa_value == $nomor_value) {
                                                    $getWhereArrayData = [['kd_proyek', "=", $kd_proyek], ['kode', "=", $nomor_value, 'AND']];
                                                    $sumRows = $DB->getWhereArray('analisa_alat', $getWhereArrayData);
                                                    $jumlahArray = is_array($sumRows) ? count($sumRows) : 0;
                                                    if ($jumlahArray) {
                                                        //update data
                                                        $kondisi2 = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kd_analisa_value, 'AND'], ['keterangan', '=', 'analisa_alat_custom', 'AND']];
                                                        $set2 = ['total_biaya_sewa' => $value['koefisien'], 'jenis_peralatan' => $value['uraian']];
                                                        $DB->update_array('analisa_alat', $set2, $kondisi2);
                                                    } else {
                                                        //insert data
                                                        $set2 = [
                                                            'kd_proyek' => $kd_proyek,
                                                            'kode' => $kd_analisa_value,
                                                            'kapasitas' => $kapasitas,
                                                            'sat_kapasitas' => $sat_kapasitas,
                                                            'jenis_peralatan' => $value['uraian'],
                                                            'total_biaya_sewa' => $value['koefisien'],
                                                            'keterangan' => 'analisa_alat_custom'
                                                        ];
                                                        $DB->insert('analisa_alat', $set2);
                                                    }
                                                }
                                                $subSum++;
                                            }
                                            break;
                                        case 'awal_analisa':
                                            function sortin($a, $b)
                                            {
                                                return strlen($b) - strlen($a);
                                            }
                                            usort($tampungKode, 'sortin'); //sortir kode dari yang terpanjang kodenya agar rumus tidak menimpa
                                            //var_dump($tampungKode);
                                            $rumusAfter = [];
                                            foreach ($rumusKode as $key => $value) {
                                                $dataValue = $value;
                                                //var_dump($dataValue);
                                                foreach ($tampungKode as $key2 => $value2) {
                                                    $koefKode = $DataKodeKoefHarga[$value2]['koef'];
                                                    $hargaKode = $DataKodeKoefHarga[$value2]['harga'];
                                                    if ((float)$hargaKode > 0) {
                                                        $dataValue = str_replace($value2, $hargaKode, $dataValue);
                                                    } elseif ((float)$koefKode != 0) {
                                                        $dataValue = str_replace($value2, $koefKode, $dataValue);
                                                    }
                                                }
                                                $rumusAfter["$key"] = $dataValue;
                                                try {
                                                    $parser = new FormulaParser($dataValue);
                                                    //$parser->setVariables($setVariabel);
                                                    $result = $parser->getResult();
                                                    if ($result[1] == 'Syntax error') {
                                                    } else {
                                                        $DataKodeKoefHarga[$key]['koef'] = (float)$result[1];
                                                    }
                                                } catch (\Exception $e) {
                                                    //echo $e->getMessage(), "\n";
                                                    //$sum
                                                }
                                                //var_dump($DataKodeKoefHarga);
                                            }
                                            //var_dump($DataKodeKoefHarga);
                                            //================================================================
                                            //===================START INSERT /UPDATE ========================
                                            //$arrayDataRowsArray;
                                            //var_dump($DataKodeKoefHarga);
                                            foreach ($DataKodeKoefHarga as $key2 => $value2) {
                                                //var_dump('$key2:'.$key2);
                                                //var_dump('$arrayDataRowsArray:'.$arrayDataRowsArray[$key2]['koefisien']);
                                                //var_dump('$value2[koef]:'.$value2['koef']);
                                                $arrayDataRowsArray[$key2]['koefisien'] = (float)$value2['koef'];
                                                //var_dump('$arrayDataRowsArray:'.$arrayDataRowsArray[$key2]['koefisien']);
                                            }
                                            //var_dump($arrayDataRowsArray);
                                            ////DELETE row dengan kd_analisa yang sama
                                            $sumRowsDel = $DB->delete_array($tabel_pakai, $getWhereDelete); //delete_array($tableName, $condition)
                                            $subSum = 0;
                                            $sumPerekaman = 0;
                                            foreach ($arrayDataRowsArray as $key2 => $value2) {
                                                $subSum++;
                                                $kode = $value2['kode'];
                                                $cek = array_key_exists("kd_proyek", $value2);
                                                if ($cek) {
                                                    switch ($jenis) {
                                                        case 'analisa_quarry':
                                                            if ($value2['nomor'] == $kd_analisa) {
                                                                $satuan_pembayaran = $value2['satuan'];
                                                                //$value2['koefisien'] = $sumPerekaman;
                                                                // $value2['uraian'] = $kd_analisa;
                                                                $value2['keterangan'] = '{"lokasi":"' . $lokasi . '", "tujuan":"' . $tujuan . '"}';
                                                                //var_dump('koef:'.$value2['koefisien']);
                                                            }
                                                            break;
                                                        case 'analisa_bm':
                                                        case 'analisa_sda':
                                                            // kalikan nomor yang bertanda '>>' koef dan harga
                                                            if ($value2['nomor'] == '>>' || strlen($value2['rumus']) <= 0) {
                                                                $sumPerekaman += $value2['koefisien'] * $value2['harga_satuan'];
                                                                $value2['jumlah_harga'] = $value2['koefisien'] * $value2['harga_satuan'];
                                                            }
                                                            //jika nomor sama dengan kd_analisa ganti koefisien menjadi =$sumPerekaman, uraian menjadi jenis, satuan menjadi $satuan_pembayaran
                                                            if ($value2['nomor'] == $kd_analisa) {
                                                                $satuan_pembayaran = $value2['satuan'];
                                                                $value2['koefisien'] = $sumPerekaman;
                                                                $value2['uraian'] = $jenis_pek;
                                                                $value2['satuan'] = $satuan_pembayaran;
                                                                $value2['keterangan'] = $satuan_pembayaran;
                                                                //var_dump('koef:'.$value2['koefisien']);
                                                            }
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                    $getWhereArrayData = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['kode', '=', $kode, 'AND'], ['kode', '!=', '', 'AND']];
                                                    $update_arrayData = $getWhereArrayData;
                                                    $sumRows = $DB->getWhereArray($tabel_pakai, $getWhereArrayData);
                                                    if (count($sumRows) <= 0) {
                                                        $resul = $DB->insert($tabel_pakai, $value2);
                                                        if ($resul) {
                                                            $data['add_row'][] = $subSum;
                                                            // inser di analisa_alat jika analisa alat custom kolom nomor = kd_analisa
                                                            if ($jenis == 'analisa_alat_custom' && $value2['nomor'] == $value2['kd_analisa']) {
                                                                # insert/cek sdh ada atau belum di analisa alat dengan keterangan analisa_alat_custom
                                                                $getWhere = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kd_analisa, 'AND']];
                                                                $sumRows = $DB->getWhereArray('analisa_alat', $getWhere);
                                                                //var_dump($sumRows);
                                                                if (count($sumRows) <= 0) {
                                                                    $dataInsert = ['kd_proyek' => $kd_proyek, 'kode' => $kd_analisa, 'jenis_peralatan' => $jenis_pek, 'total_biaya_sewa' => $value2['koefisien'], 'keterangan' => 'analisa_alat_custom'];
                                                                    $resul2 = $DB->insert('analisa_alat', $dataInsert);
                                                                    //var_dump($DB->count());
                                                                } else {
                                                                    # update
                                                                    $dataUpdate = ['kd_proyek' => $kd_proyek, 'kode' => $kd_analisa, 'jenis_peralatan' => $jenis_pek, 'total_biaya_sewa' => $value2['koefisien'], 'keterangan' => 'analisa_alat_custom'];
                                                                    $resul = $DB->update_array('analisa_alat', $dataUpdate, [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kd_analisa, 'AND']]);
                                                                    if ($resul) {
                                                                        $data['row_update'][] = $subSum;
                                                                    } else {
                                                                        $data['gagal'][] = $subSum;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        //update row
                                                        $resul = $DB->update_array($tabel_pakai, $value2, $update_arrayData);
                                                        if ($resul) {
                                                            $data['row_update'][] = $subSum;
                                                        } else {
                                                            $data['gagal'][] = $subSum;
                                                        }
                                                        //var_dump($resul);
                                                    }
                                                }
                                            }
                                            break;
                                        default:
                                            break;
                                    }
                                };
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
        $item = array('code' => $code, 'message' => $hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
    }
}
