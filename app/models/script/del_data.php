<?php
class del_data
{
    public function del_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $id_user = $_SESSION["user"]["id"];
        $btn_edit = '';
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
        /*
Ada 5 jenis kode internet yang umum dijumpai, yaitu:
1xx (informasi) ‒ server menerima permintaan dan sedang memprosesnya.
2xx (berhasil) ‒ server berhasil menerima permintaan dan mengirimkan kembali respons yang diharapkan.
3xx (redirect) ‒ server memerlukan tindakan tambahan untuk menyelesaikan permintaan karena ada perubahan pada resource.
4xx (client error) ‒ server tidak bisa memenuhi permintaan karena sintaksis yang buruk (bad syntax).
5xx (server error) ‒ server gagal memenuhi permintaan yang valid.
*/
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
                switch ($tbl) {
                    case 'del_row':
                        switch ($jenis) {
                            case 'rekanan':
                            case 'chat':
                            case 'wall':
                            case 'informasi_umum':
                            case 'rab':
                            case 'analisa_alat':
                            case 'harga_satuan':
                            case 'satuan':
                            case 'monev':
                            case 'lap-harian':
                            case 'proyek':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                break;
                            case 'analisa_ck':
                            case 'analisa_bm':
                            case 'analisa_sda':
                            case 'analisa_quarry':
                            case 'analisa_alat_custom':
                                $id_row = $validate->setRules('id_row', 'id_row', [
                                    'sanitize' => 'string',
                                    'required' => true,
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
                        case "monev[informasi]":
                        case "monev[realisasi]":
                        case "monev[laporan]":
                            $tabel_pakai = 'monev';
                            break;
                        case 'lap-harian':
                            $tabel_pakai = 'laporan_harian';
                            break;
                        case 'chat':
                        case 'wall':
                            $tabel_pakai = 'ruang_chat';
                            break;
                        default:
                    }
                    $code = 10;
                    $sukses = true;
                    $err = 0;
                    $kd_proyek = '';
                    $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);
                    $tahun_anggaran = 0;
                    $jumlahArray = is_array($data_kd_proyek) ? count($data_kd_proyek) : 0;
                    if ($jumlahArray > 0) {
                        $kd_proyek = $data_kd_proyek[0]->kd_proyek_aktif;
                        $data['dataProyek'] = $DB->getWhere('nama_pkt_proyek', ['kd_proyek', '=', $kd_proyek]);
                        //var_dump($tahun_anggaran);
                        $jumlahArray = is_array($data['dataProyek']) ? count($data['dataProyek']) : 0;
                        if ($jumlahArray > 0) {
                            $data['dataProyek'] = $data['dataProyek'][0];
                            $tahun_anggaran = $data['dataProyek']->tahun_anggaran;
                        }
                    }
                    $columnName = "*";
                    switch ($jenis) {
                        case 'lap-harian':
                            switch ($tbl) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_row':
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
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
                        case 'wall':
                        case 'chat':
                            switch ($tbl) {
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

                        case 'proyek':
                            switch ($tbl) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_row':
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
                        case 'harga_satuan':
                            switch ($tbl) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                case 'del_row':
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;


                        case 'analisa_alat_custom':
                        case 'analisa_ck':
                        case 'analisa_quarry':
                        case 'analisa_sda':
                        case 'analisa_bm':
                            //var_dump($tabel_pakai);
                            switch ($tbl) {
                                case 'del_row': //hapus analisa
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    break;
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;


                        case 'informasi_umum':
                            switch ($tbl) {
                                case 'del_row':
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    $janganHapus = ['ppn', 'op', 'MPP', 'Tk', 'APDLL', 'suku_bunga_i', 'kegiatan', 'Lokasi', 'kab/kota/prov'];
                                    //cari data
                                    $cari = array_search("apaini", $janganHapus);
                                    break;
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'list':
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;

                        case 'analisa_alat':
                            switch ($tbl) {
                                case 'del_row':
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    break;
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;

                        case 'rab':
                            $tabel_pakai =  'rencana_anggaran_biaya';
                            switch ($tbl) {
                                case 'del_row':
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    //hapus juga di row schedule_table
                                    $tabel_pakai2 = 'schedule_table';
                                    $kondisi2 = [['kd_proyek', '=', $kd_proyek], ['id_rab', '=', $id_row, 'AND']];
                                    break;
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'divisi':
                            switch ($tbl) {
                                case 'del_row':
                                    $kondisi = [['tahun', '=', $tahun], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    //hapus juga di row schedule_table
                                    $tabel_pakai2 = 'schedule_table';
                                    $kondisi2 = [['tahun', '=', $tahun], ['id_rab', '=', $id_row, 'AND']];
                                    break;
                                case 'reset':
                                    $kodePosting = 'reset';
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
                            switch ($tbl) {
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
                        case 'satuan':
                            switch ($tbl) {
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                case 'del_row':
                                    $kodePosting = 'del_row';
                                    $kondisi = [['tahun', '=', $tahun_anggaran], ['id', '=', $id_row, 'AND']];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'profil':
                            $tabel_pakai =  'user_ahsp';
                            $maxsize = 1024 * 8000; // maksimal 2000 KB (1KB = 1024 Byte)
                            if (!empty($_POST) && $id_user > 0 && !empty($_FILES["file"]["name"])) {
                                $uploadedFile  = '';
                                if ($_FILES['file']['size'] <= $maxsize) {
                                    if (!empty($_FILES["file"]["type"])) {
                                        $valid_extensi = array("pdf", "PDF", "jpeg", "jpg", "png", "JPEG", "JPG", "PNG");
                                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                                            $os_c = 'windows';
                                            $fileee1 = "\\script"; //untuk windows
                                            $targetPath1 = "\\images\\avatar\\";
                                            $folder = "images/avatar/"; //delete file
                                        } else {
                                            $os_c = 'os_xx';
                                            $fileee1 = "/script"; // akali ut linux osx
                                            $targetPath1 = "/images/avatar/";
                                            $folder = '\\images\\avatar\\';
                                        }
                                        $temporary = explode(".", $_FILES["file"]["name"]);
                                        $file_extensi = strtolower(end($temporary));
                                        $fileee = realpath(dirname(__FILE__));
                                        $fileee = str_replace($fileee1, '', $fileee);
                                        if (in_array($file_extensi, $valid_extensi)) {
                                            $sourcePath = $_FILES['file']['tmp_name'];
                                            //$targetPath = "$fileee/dokumen_upl/kontrak/" . $fileName; rumus awal
                                            $targetPath = $fileee . $targetPath1 . $fileName;
                                            //$image = imagecreatefromjpeg( $sourcePath );
                                            $filename = $sourcePath;
                                            //var_dump($sourcePath);
                                            if ($file_extensi == 'jpg' || $file_extensi == 'jpeg') {
                                                imagejpeg($thumb, $filename, 100); // 100 persen bagus khusus jpg klo png hilangkan
                                            }
                                            if ($file_extensi == 'png') {
                                                imagepng($thumb, $filename);
                                            }
                                            //simpan file
                                            if (move_uploaded_file($sourcePath, $targetPath)) {
                                                $uploadedFile = $fileName;
                                                // cari dulu nama file lama dan hapus jika ada
                                                if (strlen($nama_files_hapus) > 2) {
                                                    unlink($fileee . $fileee1 . $nama_files_hapus);
                                                }
                                            } else {
                                                $code = 704;
                                            }
                                        }
                                    } else {
                                        $code = 703;
                                    }
                                } else {
                                    $code = 702;
                                }
                            } else {
                                $code = 701;
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
                        case 'monev':
                            switch ($tbl) {
                                case 'del_row':
                                    $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'del_row';
                                    //hapus laporan harian menurut data $id_row monev
                                    $tabel_pakai2 = 'laporan_harian';
                                    $kondisi2 = [['kd_proyek', '=', $kd_proyek], ['id_rab', '=', $id_row, 'AND']];
                                    break;
                                case 'reset':
                                    $kodePosting = 'reset';
                                    break;
                                case 'del_all':
                                    $kodePosting = 'delete_all_table';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        default:
                            $code = 406;
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
                                case 'proyek': //1.AMBIL DATA untuk kode proyek; 2.delete data proyek; 3.delete upah, analisa yang mengunakan kd proyek yang sama
                                    //1.ambil data
                                    $ambil_data = $DB->getWhere($tabel_pakai, ['id', '=', $id_row]);
                                    //var_dump($ambil_data[0]->kd_proyek);
                                    $kd_proyek_del = $ambil_data[0]->kd_proyek; //kode proyek yang ingin dihapus
                                    //2.delete row
                                    $data[$tabel_pakai] = $DB->delete($tabel_pakai, ['kd_proyek', '=', $kd_proyek_del]);
                                    if ($DB->count() > 0) {
                                        //3.
                                        $code = 4;
                                        $data['informasi_umum'] = $DB->delete('informasi_umum', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['harga_sat_upah_bahan'] = $DB->delete('harga_sat_upah_bahan', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['alat'] = $DB->delete('analisa_alat', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['rab'] = $DB->delete('rencana_anggaran_biaya', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['analisa_item_pekerjaan'] = $DB->delete('analisa_item_pekerjaan', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['bm'] = $DB->delete('analisa_pekerjaan_bm', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['ck'] = $DB->delete('analisa_pekerjaan_ck', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['sda'] = $DB->delete('analisa_pekerjaan_sda', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['sch'] = $DB->delete('schedule_table', ['kd_proyek', '=', $kd_proyek_del]);
                                        $data['lokasi'] = $DB->delete('lokasi_proyek', ['kd_proyek', '=', $kd_proyek_del]);
                                        # code...
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                                case 'analisa_alat_custom':
                                    $kondisi2 = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $id_row, 'AND']];
                                    $data['analisa alat'] = $DB->delete_array('analisa_alat', $kondisi2);
                                    if ($DB->count() > 0) {
                                        $code = 4;
                                        $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisi);
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                                case 'rab':
                                    //$kondisi2 = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $id_row, 'AND']];
                                    $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisi);
                                    if ($DB->count() > 0) {
                                        $code = 4;
                                        $data[$tabel_pakai2] = $DB->delete_array($tabel_pakai2, $kondisi2);
                                        //hapus tabel monev dan laporan_harian
                                        $kondisi = [['kd_proyek', '=', $kd_proyek], ['id_rab', '=', $id_row, 'AND']];
                                        $data['monev'] = $DB->delete_array('monev', $kondisi);
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                                    //$kondisiSchedule
                                default:
                                    //standar delete id
                                    $data[$tabel_pakai] = $DB->delete_array($tabel_pakai, $kondisi);
                                    if ($DB->count() > 0) {
                                        $code = 4;
                                    } else {
                                        $code = 35;
                                    }
                                    break;
                            }
                            break;
                        case 'reset':
                            //$resul = $DB->delete($tabel_pakai);
                            //var_dump($tabel_pakai);
                            if ($tabel_pakai) {
                                $resul = $DB->runQuery2("TRUNCATE TABLE $tabel_pakai");
                                //var_dump($resul);
                                $code =  15;
                                switch ($jenis) {
                                    case 'proyek':
                                        $tabel = ["analisa_alat", "analisa_alat_custom", "analisa_pekerjaan_bm", "analisa_pekerjaan_ck", "analisa_pekerjaan_sda", "analisa_quarry", "informasi_umum", "harga_sat_upah_bahan", "lokasi_proyek", "rencana_anggaran_biaya", "schedule_table", "monev"];
                                        foreach ($tabel as $key => $value) {
                                            $resul = $DB->runQuery2("TRUNCATE TABLE $value");
                                        };

                                        break;
                                    case 'analisa_alat':
                                        $resul = $DB->runQuery2("TRUNCATE TABLE analisa_alat_custom");
                                        break;
                                    case 'rab':
                                        $resul = $DB->runQuery2("TRUNCATE TABLE schedule_table");
                                        break;
                                    case 'monev':
                                        $resul = $DB->runQuery2("TRUNCATE TABLE laporan_harian");
                                        break;
                                    default:

                                        break;
                                }
                                if ($resul) {
                                } else {
                                    //$code = 46;
                                };
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
        $item = array('code' => $code, 'message' => $hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
    }
}
