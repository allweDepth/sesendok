<?php
class writer_xlsx
{
    public function writer_xlsx()
    {
        include_once("class/xlsxwriter.class.php");
        require_once("class/FormulaParser.php"); //__DIR__ . '/vendor/autoload.php';
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
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
            56 => 'belum ada dokumen pekerjaan yang aktif',
            100 => 'Continue', #server belum menolak header permintaan dan meminta klien untuk mengirim body (isi/konten) pesan
            //File
            701 => 'File Tidak Lengkap',
            702 => 'file yang ada terlalu besar',
            703 => 'type file tidak sesuai',
            704 => 'Gagal Upload',
            705 => 'File Telah dibuat',
            707 => 'File Gagal dibuat',
            401  => 'Unauthorized', #pengunjung website tidak memiliki hak akses untuk file / folder yang diproteksi oleh password (kata kunci).
            403  => 'Forbidden', #pengunjung sama sekali tidak dapat mengakses ke folder tujuan. Angka 403 muncul disebabkan oleh kesalahan pengaturan hak akses pada folder.
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
        $type_user = $_SESSION["user"]["type_user"];
        $username = $_SESSION["user"]["username"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $id_user = $_SESSION["user"]["id"];
        $kd_proyek = $_SESSION["user"]["kd_proyek_aktif"];
        $DB = DB::getInstance();
        $validate = new Validate($_POST);
        $Fungsi = new MasterFungsi();
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

        $code = 24434;
        $sukses = false;
        $folderTemp = 'temp/';
        $filename = 'nabiila.xlsx';
        $folder = 'temp/';
        $filename = $folder . $filename;

        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $writer = new XLSXWriter();
        $writer->setAuthor('Nabiila Inayah');
        $writer->setCompany('Hamba Allah');
        // hanya text bold tengah
        $TB_b_h20 = array('border' => 'top,bottom', 'valign' => 'center', 'border-style' => 'thin', 'font-style' => 'bold', 'height' => 20);
        $hcvc_b_h20 = array('halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'height' => 20, 'wrap_text' => 'true');
        $vc_b_h20 = array('valign' => 'center', 'font-style' => 'bold', 'height' => 20);
        $vc_wrap_b_h20 = array('valign' => 'center', 'font-style' => 'bold', 'height' => 20, 'wrap_text' => 'true');
        //cetak kotak dan biasa
        $LTRB = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true');
        $LTRB_20 = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'height' => 20);
        $LTRB_fill = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#C9C9C9');
        $LTRB_fillRed_b = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#8e2f2f', 'font-style' => 'bold');
        $LTRB_fillRed_b_20 = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#8e2f2f', 'font-style' => 'bold', 'height' => 20);
        $LTRB_vc_fillRed_b_20 = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#ee3939', 'font-style' => 'bold', 'height' => 20, 'valign' => 'center'); //'#8e2f2f'
        $LTRB_hcvc_fillRed_b_20 = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#8e2f2f', 'font-style' => 'bold', 'height' => 20, 'valign' => 'center', 'halign' => 'center');
        $LTRB_fillRed = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#8e2f2f');
        $LTRB_vt = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'valign' => 'top');
        $LTRB_vt_b = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'valign' => 'top', 'font-style' => 'bold');
        $LTRB_hcvcwrap_b_fillRed = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'valign' => 'center', 'wrap_text' => 'true', 'fill' => '#8e2f2f', 'halign' => 'center');
        $LTRB_vcwrap = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'valign' => 'center');
        $LTRB_vc = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'center');
        $LTRB_vc_b = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold');
        $LTRB_b = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'font-style' => 'bold');
        //cetak kotak dan tulisan ditengah tanpa style
        $LTRB_hc = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'wrap_text' => 'true');
        //
        $style_Non_Data = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#ffc', 'wrap_text' => 'true', 'height' => 25, 'font-size' => 16);
        //border all, center,wrap, fill abu2
        $LTRB_hlvc_b_fabu = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'left', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#F1E32A');
        $LTRB_hcvc_b_fabu = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#C9C9C9');
        //border all, text left,
        $LTRB_hcvcwrap = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center');
        //border [left,right,top], text left,
        $styles7 = array('wrap_text' => 'true', 'border' => 'left,right,top', 'border-style' => 'thin', 'halign' => 'left', 'valign' => 'center');
        //border [left,right,top], text left,bold
        $LTR_vc = array('wrap_text' => 'true', 'border' => 'left,right,top', 'border-style' => 'thin',  'valign' => 'center');
        //border [left,right,top], text left bold,
        $LTR_vc_b = array('wrap_text' => 'true', 'border' => 'left,right,top', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold');
        //border [left,right,top], text left,bold
        $LTR_hcvc = array('wrap_text' => 'true', 'border' => 'left,right,top', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center');
        //border [left,right,top], text left bold,
        $LTR_hcvc_b = array('wrap_text' => 'true', 'border' => 'left,right,top', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold');
        //border [left,top], text left,
        $LT_vcwrap = array('wrap_text' => 'true', 'border' => 'left,top', 'border-style' => 'thin', 'halign' => 'left', 'valign' => 'center');
        //border [left,top], text left, bold
        $LT_vcwrap_b = array('wrap_text' => 'true', 'border' => 'left,top', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold');
        //border [right,top], text left,
        $TR_hlvcwrap = array('wrap_text' => 'true', 'border' => 'right,top', 'border-style' => 'thin', 'halign' => 'left', 'valign' => 'center');
        $TR_hlvcwrap_b = array('wrap_text' => 'true', 'border' => 'right,top', 'border-style' => 'thin', 'halign' => 'left', 'valign' => 'center', 'font-style' => 'bold');
        //wrap text and all center warna kuning
        $LTRB_hcvcwrap_b_fill = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#ffc');
        $LTRB_hcvcwrap = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center');
        $LTRB_hcvcwrap_b = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold');
        $LTRB_hcvcwrap_b_fill_h20 = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#ffc', 'height' => 20);
        $LTRB_hcvcwrap_b_fill_h40 = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#ffc', 'height' => 40);
        $LTRB_vcwrap = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'center');
        $LTRB_vcwrap_b = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold');
        $LTRB_hcvc_b_h20 = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'height' => 20);
        $LTRB_hcvc_h20 = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'height' => 20);
        $LTRB_vt_h20 = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'top', 'wrap_text' => 'true', 'height' => 20);
        $LTRB_vt_b_h20 = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'top', 'font-style' => 'bold', 'wrap_text' => 'true', 'height' => 20);
        //border left,right
        $LR_vc = array('border' => 'left,right', 'border-style' => 'thin', 'valign' => 'center');
        $LR_vcwrap = array('border' => 'left,right', 'border-style' => 'thin', 'valign' => 'center', 'wrap_text' => 'true');
        $LR_vcwrap_b = array('border' => 'left,right', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold', 'wrap_text' => 'true');
        $R_vcwrap = array('border' => 'right', 'border-style' => 'thin', 'valign' => 'center', 'wrap_text' => 'true');
        $R_vcwrap_b = array('border' => 'right', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold', 'wrap_text' => 'true');
        $L_vcwrap = array('border' => 'left', 'border-style' => 'thin', 'valign' => 'center', 'wrap_text' => 'true');
        $L_vcwrap_b = array('border' => 'left', 'border-style' => 'thin', 'valign' => 'center', 'font-style' => 'bold', 'wrap_text' => 'true');
        $LR_hcvc = array('border' => 'left,right', 'border-style' => 'thin', 'valign' => 'center', 'halign' => 'center', 'wrap_text' => 'true');
        $LR_hcvc_b = array('border' => 'left,right', 'border-style' => 'thin', 'valign' => 'center', 'halign' => 'center', 'font-style' => 'bold', 'wrap_text' => 'true');
        $LRB_hcvc = array('wrap_text' => 'true', 'border' => 'left,right,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center');
        $LB_hcvc = array('wrap_text' => 'true', 'border' => 'left,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center');
        $RB_hcvc = array('wrap_text' => 'true', 'border' => 'right,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center');
        $data = [];
        $where1 = "";
        $kolom = '*';
        $nama_sheet = 'Inayah';

        if ($validate->passed()) {
            $sukses = true;
            //var_dump('Content');
            $tgl_spm = 0;
            $no_kontrak = '';
            $pelaksana = '';
            if (strlen($kd_proyek) > 0) {
                $dataProyek = $DB->getWhere('nama_pkt_proyek', ['kd_proyek', '=', $kd_proyek])[0];
                $data['dataProyek'] = $dataProyek;
                //@audit ambil semua data proyek
                $tgl_spm = $data['dataProyek']->tgl_spm;
                foreach ($dataProyek as $key => $value) {
                    ${$key} = $value;
                }
            }
            $query = '';
            switch ($jenis) {
                case 'harga_satuan':
                    $tabel_pakai = 'harga_sat_upah_bahan';
                    $filename = 'harga_sat_upah.xlsx';
                    $nama_sheet = 'Basic Price';
                    switch ($tbl) {
                        case 'dok':
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY no_sortir, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'satuan':
                    $tabel_pakai = 'daftar_satuan';
                    break;
                case "proyek":
                    $tabel_pakai = 'nama_pkt_proyek';
                    break;
                case 'analisa_alat':
                    $tabel_pakai =  'analisa_alat';
                    $filename = 'analisa_alat.xlsx';
                    $nama_sheet = 'Analisa Alat';
                    $nama_sheet2 = 'Alat custom';
                    switch ($tbl) {
                        case 'one_by_one': //semua alat tapi dengan detail
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY no_sortir, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            $nama_sheet = 'Analisa Alat';
                            break;
                        case 'tabel': //semua alat dan dibuatkan tabel
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1";
                            $nama_sheet = 'Tebel Alat';
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'analisa_quarry':
                    $tabel_pakai =  'analisa_quarry';
                    $filename = 'quarry.xlsx';
                    $nama_sheet = 'Analisa';
                    $nama_sheet2 = 'Perekaman';
                    switch ($tbl) {
                        case 'one_by_one': //semua alat tapi dengan detail
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = nomor";
                            $order = "ORDER BY kd_analisa ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        case 'tabel': //semua alat dan dibuatkan tabel
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = nomor";
                            $order = "ORDER BY id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'analisa_bm':
                    $tabel_pakai =  'analisa_pekerjaan_bm';
                    $filename = 'hsp_binamarga.xlsx';
                    $nama_sheet = 'Analisa';
                    $nama_sheet2 = 'Perekaman';
                    switch ($tbl) {
                        case 'one_by_one': //semua alat tapi dengan detail
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = nomor";
                            $order = "ORDER BY kd_analisa ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        case 'tabel': //semua alat dan dibuatkan tabel
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = nomor";
                            $order = "ORDER BY id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'analisa_sda':
                    $tabel_pakai =  'analisa_pekerjaan_sda';
                    $filename = 'hsp_sda.xlsx';
                    $nama_sheet = 'Analisa';
                    $nama_sheet2 = 'Perekaman';
                    switch ($tbl) {
                        case 'one_by_one': //semua alat tapi dengan detail
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = nomor";
                            $order = "ORDER BY kd_analisa ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        case 'tabel': //semua alat dan dibuatkan tabel
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = nomor";
                            $order = "ORDER BY id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'analisa_ck':
                    $tabel_pakai =  'analisa_pekerjaan_ck';
                    $filename = 'hsp_ciptakarya.xlsx';
                    $nama_sheet = 'Analisa';
                    switch ($tbl) {
                        case 'one_by_one': //semua alat tapi dengan detail
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = kode";
                            $order = "ORDER BY kd_analisa ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        case 'tabel': //semua alat dan dibuatkan tabel
                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = kode";
                            $order = "ORDER BY id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case "rab":
                    $tabel_pakai = 'rencana_anggaran_biaya';
                    $filename = 'Bill_Of_Quantity.xlsx';
                    $nama_sheet = 'BOQ';
                    $kolom = 'rencana_anggaran_biaya.kd_proyek,rencana_anggaran_biaya.kd_analisa,rencana_anggaran_biaya.uraian,rencana_anggaran_biaya.volume, rencana_anggaran_biaya.satuan,rencana_anggaran_biaya.harga_dasar,rencana_anggaran_biaya.harga_satuan,rencana_anggaran_biaya.jumlah_harga,rencana_anggaran_biaya.keterangan,rencana_anggaran_biaya.no_sortir,schedule_table.data,schedule_table.durasi,schedule_table.mulai,schedule_table.bobot,schedule_table.bobot_selesai,schedule_table.dependent,schedule_table.type,schedule_table.keterangan AS keterangan_schedule';
                    $nama_sheet2 = 'schedule';
                    $nama_sheet3 = 'informasi';
                    $where1 = "rencana_anggaran_biaya.kd_proyek = '$kd_proyek' AND rencana_anggaran_biaya.id = schedule_table.id_rab";
                    $order = "ORDER BY rencana_anggaran_biaya.no_sortir ASC, rencana_anggaran_biaya.id ASC";
                    $query = "SELECT $kolom FROM $tabel_pakai,schedule_table WHERE $where1 $order";
                    //SELECT * FROM rencana_anggaran_biaya,schedule_table WHERE rencana_anggaran_biaya.kd_proyek = 'M001' AND rencana_anggaran_biaya.id = schedule_table.id_rab ORDER BY rencana_anggaran_biaya.no_sortir ASC, rencana_anggaran_biaya.id ASC
                    //SELECT rencana_anggaran_biaya.kd_proyek,rencana_anggaran_biaya.kd_analisa,rencana_anggaran_biaya.uraian,rencana_anggaran_biaya.volume, rencana_anggaran_biaya.satuan,rencana_anggaran_biaya.harga_satuan,rencana_anggaran_biaya.jumlah_harga,rencana_anggaran_biaya.keterangan,rencana_anggaran_biaya.no_sortir,schedule_table.durasi,schedule_table.mulai,schedule_table.bobot,schedule_table.bobot_selesai,schedule_table.dependent,schedule_table.type,schedule_table.keterangan AS keterangan_schedule FROM rencana_anggaran_biaya,schedule_table WHERE rencana_anggaran_biaya.kd_proyek = 'M001' AND rencana_anggaran_biaya.id = schedule_table.id_rab ORDER BY rencana_anggaran_biaya.no_sortir ASC, rencana_anggaran_biaya.id ASC;
                    break;
                case "monev":
                    $tabel_pakai = 'monev';
                    $filename = 'monev.xlsx';
                    $nama_sheet = 'monev';
                    switch ($tbl) {
                        case 'import_realisasi':
                            $tabel_pakai = 'rencana_anggaran_biaya';
                            $filename = 'realisasi_import.xlsx';
                            $nama_sheet = 'realisasi';
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY no_sortir ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        case 'lap_harian':
                            $sumDates = $DB->getDistinct('laporan_harian', 'tanggal', [['kd_proyek', '=', $kd_proyek]]);
                            break;
                        case 'lap_periodik':
                            $tanggal_awal = $validate->setRules('tanggal_awal', 'tanggal_awal', [
                                'sanitize' => 'string',
                                'required' => true,
                                'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                'min_char' => 8
                            ]);
                            $tanggal_akhir = $validate->setRules('tanggal_akhir', 'tanggal_akhir', [
                                'sanitize' => 'string',
                                'required' => true,
                                'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                'min_char' => 8
                            ]);
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY tanggal ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        case 'tabel': //tabel input realisasi
                            $filename = 'tabel_realisasi.xlsx';
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY tanggal ASC, id ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'rekanan':
                    $tabel_pakai =  'rekanan';
                    $filename = 'rekanan.xlsx';
                    $nama_sheet = 'rekanan';
                    $where1 = "id > 0";
                    $order = "ORDER BY nama_perusahaan ASC";
                    $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                    break;
                default:
                    $filename = 'nabiila.xlsx';
                    break;
            }
            //ambil quary
            if (strlen($where1) > 0) {
                $get_data = $DB->runQuery2($query);
                switch ($jenis) {
                    case 'monev':
                        switch ($tbl) {
                            case 'lap_periodik':
                                $get_data = $Fungsi->laporanPeriodik($kd_proyek, $tanggal_awal, $tanggal_akhir);
                                break;
                            default:
                                break;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                //$code = 100;
                //$hasilServer['100'] = $get_data[0][3];
                $jumlahArray = is_array($get_data) ? count($get_data) : 0;
                if ($jumlahArray > 0) {
                    #informasi umum
                    $informasi = $DB->getWhere('informasi_umum', ['kd_proyek', '=', $kd_proyek]);
                    $Tk = 0;
                    $Fungsi = new MasterFungsi();
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
                                case 'op':
                                    $op = $value->nilai; // overhead dan keuntungan
                                    break;
                                case 'kegiatan':
                                case 'lokasi':
                                case 'kab/kota/prov':
                                    ${$value->kode} = $value->keterangan; // overhead dan keuntungan
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }
                    }
                    #=============================
                    #==== HEADER KOP TABEL =======
                    #=============================
                    switch ($jenis) {
                        case 'rekanan':

                            break;
                        case "monev":
                            switch ($tbl) {
                                case 'lap_periodik':
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'LAPORAN REALISASI FISIK DAN KEUANGAN ANGGARAN' => '0', //No. MataPembayaran
                                        '1' => 'string', //uraian
                                        '2' => 'string', //satuan
                                        '3' => '#,##0.####0', //kuantitas
                                        '4' => '#,##0.####0', //Harga Satuan
                                        '5' => '#,##0.####0', //total Harga
                                        '6' => '#,##0.####0', //bobot
                                        '7' => '#,##0.####0', //PVSPL Fisik
                                        '8' => '#,##0.####0', //PVSPL Keu
                                        '9' => '#,##0.####0', //PVPI Fisik
                                        '10' => '#,##0.####0', //PVPI Keu
                                        '11' => '#,##0.####0', //PVSPI Fisik
                                        '12' => '#,##0.####0', //PVSPI Keu
                                        '13' => '#,##0.####0', //BTSPL Fisik
                                        '14' => '#,##0.####0', //BTSPL Keu
                                        '15' => '#,##0.####0', //BTPI Fisik
                                        '16' => '#,##0.####0', //BTPI Keu
                                        '17' => '#,##0.####0', //BTSPI Fisik
                                        '18' => '#,##0.####0', //BTSPI Keu
                                        '19' => 'string' //keterangan
                                    ), $col_options = array('widths' => [15, 70, 10, 15, 20, 20, 20, 10, 20, 10, 20, 10, 20, 10, 10, 10, 10, 10, 10, 35], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 5, 'freeze_columns' => 2, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $tglArraySPM = $Fungsi->tanggal($tgl_spm, $MPP);
                                    //":  $no_kontrak , ( " . number_format($MPP, 0, ',', '.') . " hari kalender )"
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 19);
                                    $tgl_awal = $Fungsi->tanggal($tanggal_awal);
                                    $tgl_akhir = $Fungsi->tanggal($tanggal_akhir);
                                    $phpdate = strtotime($tgl_spm);
                                    $mysqldate = date('Y-m-d', $phpdate);
                                    $writer->writeSheetRow($nama_sheet, ['NAMA PAKET', '', ': ' . strtoupper($kegiatan)], $vc_b_h20);
                                    $writer->writeSheetRow($nama_sheet, ['PENYEDIA JASA', '', ': ' . strtoupper($pelaksana)], $vc_b_h20);
                                    $writer->writeSheetRow($nama_sheet, ['NO. KONTRAK', '', ': ' .  $no_kontrak], $vc_b_h20);
                                    $writer->writeSheetRow($nama_sheet, ['TANGGAL KONTRAK', '', ': ' . $tgl_awal['tgl'] . ' s/d ' . $tgl_akhir['tgl']], $vc_b_h20);
                                    $writer->writeSheetRow($nama_sheet, ['LAPORAN PERIODE', '', ': ' . $tgl_awal['tgl'] . ' s/d ' . $tgl_akhir['tgl']], $vc_b_h20);
                                    $rowdata = array('NO. MATA PEMBAYARAN', 'URAIAN', 'SATUAN', 'KONTRAK', '', '', '', 'PROGGRES VOLUME', '', '', '', '', '', 'BOBOT TERCAPAI', '', '', '', '', '', 'KETERANGAN',);
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvc_fillRed_b_20); //$LTRB_hcvcwrap_b_fill_h20
                                    $arrayMerger = [
                                        ['start_row' => 6, 'start_col' => 0, 'end_row' => 8, 'end_col' => 0],
                                        ['start_row' => 6, 'start_col' => 1, 'end_row' => 8, 'end_col' => 1],
                                        ['start_row' => 6, 'start_col' => 2, 'end_row' => 8, 'end_col' => 2],
                                    ];
                                    foreach ($arrayMerger as $key => $value) {
                                        $writer->markMergedCell($nama_sheet, $value['start_row'], $value['start_col'], $value['end_row'], $value['end_col']);
                                    }
                                    $arrayMerger = [
                                        ['start_row' => 7, 'start_col' => 7, 'end_row' => 7, 'end_col' => 8],
                                        ['start_row' => 7, 'start_col' => 9, 'end_row' => 7, 'end_col' => 10],
                                        ['start_row' => 7, 'start_col' => 11, 'end_row' => 7, 'end_col' => 12],
                                        ['start_row' => 7, 'start_col' => 13, 'end_row' => 7, 'end_col' => 14],
                                        ['start_row' => 7, 'start_col' => 15, 'end_row' => 7, 'end_col' => 16],
                                        ['start_row' => 7, 'start_col' => 17, 'end_row' => 7, 'end_col' => 18]
                                    ];
                                    foreach ($arrayMerger as $key => $value) {
                                        $writer->markMergedCell($nama_sheet, $value['start_row'], $value['start_col'], $value['end_row'], $value['end_col']);
                                    }
                                    $rowdata = array('', '', '', 'Kuantitas', 'Harga Satuan', 'Total Harga', 'Bobot (%)', 'Sd. Periode Lalu', '', 'Periode Ini', '', 'Sd. Periode Ini', '', 'Sd. Periode Lalu', '', 'Periode Ini', '', 'Sd. Periode Ini', '', '',);
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvc_fillRed_b_20);
                                    $rowdata = array('', '', '', '', '', '', '', 'Fisik', 'Keu.', 'Fisik', 'Keu.', 'Fisik', 'Keu.', 'Fisik', 'Keu.', 'Fisik', 'Keu.', 'Fisik', 'Keu.', '',);
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvc_fillRed_b_20);
                                    $arrayMerger = [
                                        ['start_row' => 7, 'start_col' => 3, 'end_row' => 8, 'end_col' => 3],
                                        ['start_row' => 7, 'start_col' => 4, 'end_row' => 8, 'end_col' => 4],
                                        ['start_row' => 7, 'start_col' => 5, 'end_row' => 8, 'end_col' => 5],
                                        ['start_row' => 7, 'start_col' => 6, 'end_row' => 8, 'end_col' => 6]
                                    ];
                                    foreach ($arrayMerger as $key => $value) {
                                        $writer->markMergedCell($nama_sheet, $value['start_row'], $value['start_col'], $value['end_row'], $value['end_col']);
                                    }
                                    $writer->markMergedCell($nama_sheet, 6, 3, 6, 6);
                                    $writer->markMergedCell($nama_sheet, 6, 7, 6, 12);
                                    $writer->markMergedCell($nama_sheet, 6, 13, 6, 18);
                                    $writer->markMergedCell($nama_sheet, 6, 19, 8, 19);
                                    break;
                                case 'tabel':
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'REALISASI ANGGARAN' => '0', //nomor
                                        '1' => 'string', //uraian
                                        '2' => 'D/MM/YYYY', //tanggal
                                        '4' => 'string', //satuan
                                        '3' => '#,##0.####0', //realisasi fisik
                                        '5' => '#,##0.####0', //realisasi keuangan
                                        '6' => 'string', //file
                                        '7' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 50, 20, 10, 20, 25, 30, 35], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 2, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 7);
                                    //header tabel
                                    $rowdata = array(
                                        'No.',
                                        'URAIAN',
                                        '="TANGGAL"',
                                        'SAT.',
                                        'REALISASI FISIK',
                                        'REALISASI KEUANGAN (Rp)',
                                        'FILE',
                                        'KETERANGAN'
                                    );
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                    break;
                                case 'import_realisasi':
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'INPUT REALISASI ANGGARAN' => '0', //kd_analisa
                                        '1' => 'string', //uraian
                                        '2' => 'string', //volume
                                        '3' => 'string', //satuan
                                        '4' => '#,##0.####0', //jumlah harga
                                        '5' => 'D/MM/YYYY', //tanggal realisasi
                                        '6' => '#,##0.####0', //realisasi fisik
                                        '7' => '#,##0.####0', //realisasi keuangan
                                        '8' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 50, 15, 15, 20, 20, 20, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 2, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);
                                    //header tabel
                                    $rowdata = array(
                                        'MATA PEMBAYARAN',
                                        'URAIAN',
                                        'VOLUME',
                                        'SATUAN',
                                        'JUMLAH HARGA (Rp) (NON PPN)',
                                        '="TANGGAL"',
                                        'REALISASI FISIK (sat)',
                                        'REALISASI KEUANGAN (Rp) (NON PPN)',
                                        'KETERANGAN'
                                    );
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);

                                    break;
                                case 'lap_harian':
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'rab':
                            //SHEET BOQ
                            $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                'DAFTAR KUANTITAS DAN HARGA' => '0', //kode
                                '1' => 'string', //uraian
                                '2' => '#,##0.####0', //volume(a)
                                '3' => 'string', //satuan
                                '4' => '#,##0.####0', //harga dasar
                                '5' => '#,##0.####0', //profit dan overhead
                                '6' => '#,##0.####0', //harga dasar+profit(b)
                                '7' => '#,##0.00', //pajak(c)
                                '8' => '#,##0.####0', //b*a*((100+c)/100)
                                '9' => 'string' //keterangan
                            ), $col_options = array('widths' => [10, 50, 20, 15, 20, 20, 20, 10, 25, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                            $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 9);
                            //header tabel
                            $rowdata = array(
                                'KODE',
                                'URAIAN',
                                'VOLUME',
                                'SATUAN',
                                'HARGA DASAR',
                                'PROFIT & OVERHEAD',
                                'HARGA SATUAN',
                                'PAJAK',
                                'JUMLAH',
                                'KETERANGAN'
                            );
                            $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                            $rowdata = array(
                                '(a)',
                                '(b)',
                                '(c)',
                                '(d)',
                                '(e)',
                                '(f)',
                                '(g)=(e)+(f)',
                                '(h):(%)',
                                '(i)=(c)x(g)x(h+100)/100',
                                '(j)'
                            );
                            $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill);
                            //SHEET SCHEDULE
                            $lebarKolom = [10, 40, 20, 15, 25, 15, 15, 15, 15, 15];
                            $klmSchedule = [
                                'KODE',
                                'URAIAN',
                                'VOLUME',
                                'SATUAN',
                                'JUMLAH',
                                'BOBOT', 'DURASI', 'MULAI', 'REALISASI', 'DEPENDENT'
                            ];
                            $klmSchedule2 = [
                                '(a)',
                                '(b)',
                                '(c)',
                                '(d',
                                '(e)',
                                '(f)', '(g)', '(h)', '(i)', '(j)'
                            ];
                            for ($x = 0; $x < $MPP; $x++) {
                                $lebarKolom[] = 5;
                                $klmSchedule[] = $x + 1;
                                $klmSchedule2[] = $x + 1;
                            }
                            $klmSchedule[] = 'KET.';
                            $klmSchedule2[] = '(k)';
                            $writer->writeSheetHeader($nama_sheet2, $rowdata = array(
                                'WAKTU PELAKSANAAN' => '0', //No
                                '2' => 'string', //uraian
                                '3' => '#,##0.####0', //rumus
                                '4' => 'string', //kode
                                '5' => '#,##0.####0', //koefisien
                                '6' => '#,##0.####0', //satuan
                                '7' => '#,##0.00', //harga_satuan
                                '8' => 'string' //keterangan
                            ), $col_options = array('widths' => $lebarKolom, 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'freeze_columns' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                            $writer->markMergedCell($nama_sheet2, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 7);
                            $writer->writeSheetRow($nama_sheet2, $klmSchedule, $LTRB_hcvcwrap_b_fill_h40);
                            $writer->writeSheetRow($nama_sheet2, $klmSchedule2, $LTRB_hcvcwrap_b_fill);
                            //SHEET INFORMASI
                            $writer->writeSheetHeader($nama_sheet3, $rowdata = array(
                                'INFORMASI UMUM' => '0', //No
                                '1' => 'string', //uraian
                                '2' => 'string', //kode
                                '3' => '#,##0.####0', //nilai
                                '4' => 'string', //satuan
                                '5' => 'string' //keterangan
                            ), $col_options = array('widths' => [10, 50, 20, 15, 15, 35], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                            $writer->markMergedCell($nama_sheet3, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 5);
                            $rowdata = array(
                                'No.',
                                'URAIAN',
                                'KODE',
                                'KOEFISIEN',
                                'SATUAN',
                                'KETERANGAN'
                            );
                            $writer->writeSheetRow($nama_sheet3, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                            $rowdata = array(
                                '(a)',
                                '(b)',
                                '(c)',
                                '(d)',
                                '(e)',
                                '(f)'
                            );
                            $writer->writeSheetRow($nama_sheet3, $rowdata, $LTRB_hcvcwrap_b_fill);
                            break;
                        case 'analisa_quarry':
                            switch ($tbl) {
                                case 'one_by_one': //semua alat tapi dengan detail
                                    //SHEET ANALISA
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'ANALISA HARGA DASAR SATUAN BAHAN' => '0', //No
                                        '2' => 'string', //uraian
                                        '3' => 'string', //rumus
                                        '4' => 'string', //kode
                                        '5' => '#,##0.####0', //koefisien
                                        '6' => 'string', //satuan
                                        '7' => '#,##0.00', //harga_satuan
                                        '8' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 40, 20, 15, 15, 10, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 7);
                                    break;
                                case 'tabel': //semua alat dan dibuatkan tabel
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'ANALISA HARGA DASAR SATUAN BAHAN' => 'string', //kode
                                        '3' => 'string', //uraian
                                        '4' => 'string', //satuan
                                        '5' => '#,##0.00', //harga_satuan
                                        '6' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 40, 15, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);
                                    $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                    $rowdata = array(
                                        'KODE',
                                        'Uraian',
                                        'SATUAN',
                                        'HARGA SATUAN',
                                        'KETERANGAN'
                                    );
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'analisa_sda':
                        case 'analisa_bm':
                            switch ($tbl) {
                                case 'one_by_one': //semua alat tapi dengan detail
                                    //SHEET ANALISA
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'DAFTAR HARGA SATUAN PEKERJAAN' => '0', //No
                                        '2' => 'string', //uraian
                                        '3' => 'string', //rumus
                                        '4' => 'string', //kode
                                        '5' => '#,##0.00', //koefisien
                                        '6' => 'string', //satuan
                                        '7' => '#,##0.00', //harga_satuan
                                        '8' => '#,##0.00', //jumlah_harga
                                        '9' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 40, 20, 15, 15, 10, 20, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 6, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);
                                    //SHEET PEREKAMAN
                                    $writer->writeSheetHeader($nama_sheet2, $rowdata = array(
                                        'FORMULIR STANDAR UNTUK PEREKAMAN ANALISA MASING-MASING HARGA SATUAN' => 'string', //No
                                        '2' => 'string', //uraian
                                        '3' => 'string', //kode
                                        '4' => 'string', //satuan
                                        '5' => '#,##0.######0', //koefisien
                                        '6' => '#,##0.00', //harga_satuan
                                        '7' => '#,##0.00' //jumlah_harga
                                    ), $col_options = array('widths' => [10, 35, 10, 10, 15, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 7, 'height' => 20, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet2, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 6);
                                    $writer->writeSheetRow($nama_sheet2, [''], $vc_b_h20);
                                    break;
                                case 'tabel': //semua alat dan dibuatkan tabel
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'DAFTAR HARGA SATUAN PEKERJAAN' => 'string', //kode
                                        '3' => 'string', //uraian
                                        '4' => 'string', //satuan
                                        '5' => '#,##0.00', //harga_satuan
                                        '6' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 40, 15, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);
                                    $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                    $rowdata = array(
                                        'KODE',
                                        'Uraian',
                                        'SATUAN',
                                        'HARGA SATUAN',
                                        'KETERANGAN'
                                    );
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'analisa_ck':
                            switch ($tbl) {
                                case 'one_by_one':
                                    //SHEET PEREKAMAN
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'FORMULIR STANDAR UNTUK PEREKAMAN ANALISA MASING-MASING HARGA SATUAN' => 'string', //No
                                        '2' => 'string', //uraian
                                        '3' => 'string', //kode
                                        '4' => 'string', //satuan
                                        '5' => '#,##0.######0', //koefisien
                                        '6' => '#,##0.00', //harga_satuan
                                        '7' => '#,##0.00' //jumlah_harga
                                    ), $col_options = array('widths' => [10, 45, 15, 15, 15, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 6, 'height' => 20, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 6);
                                    $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                    break;
                                case 'tabel': //semua alat dan dibuatkan tabel
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'DAFTAR HARGA SATUAN PEKERJAAN' => 'string', //kode
                                        '3' => 'string', //uraian
                                        '4' => 'string', //satuan
                                        '5' => '#,##0.00', //harga_satuan
                                        '6' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 40, 15, 20, 25], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);
                                    $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                    $rowdata = array(
                                        'KODE',
                                        'Uraian',
                                        'SATUAN',
                                        'HARGA SATUAN',
                                        'KETERANGAN'
                                    );
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        case 'harga_satuan':
                            switch ($tbl) {
                                case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'DAFTAR HARGA DASAR SATUAN UPAH, BAHAN DAN PERALATAN' => '0', //No
                                        '2' => 'string', //kode
                                        '3' => 'string', //jenis
                                        '4' => 'string', //uraian
                                        '5' => 'string', //satuan
                                        '6' => '#,##0.00', //harga_sat
                                        '7' => 'string', //sumber_data
                                        '8' => 'string', //spesifikasi
                                        '9' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 15, 15, 40, 20, 30, 30, 30, 30], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 4, 'freeze_columns' => 2, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);
                                    $writer->writeSheetRow($nama_sheet, $rowdata = array('KEGIATAN', '', ': ' . $data['dataProyek']->nama_proyek), ['font-style' => 'bold', 'font-size' => 12]);
                                    $styles = array($LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu, $LTRB_hlvc_b_fabu);
                                    $lebar = [30, 100];
                                    $row_tabel = ['NO.', 'KODE', 'JENIS', 'URAIAN', 'SATUAN', 'HARGA SATUAN', 'SUMBER DATA', 'SPESIFIKASI', 'KETERANGAN'];
                                    $rowdata = array("(1)", "(2)", "(3)", "(4)", "(5)", "(6)", "(7)", "(8)", "(9)");
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, ['height' => 40, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#BFBFBF', 'wrap_text' => true, 'freeze_rows' => 1]);
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hc);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'analisa_alat':
                            switch ($tbl) {
                                case 'one_by_one': //semua alat tapi dengan detail
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'ANALISIS PERALATAN' => '0', //No
                                        '2' => 'string', //kode
                                        '3' => 'string', //jenis
                                        '4' => 'string', //uraian
                                        '5' => '#,##0.00', //satuan
                                        '6' => '#,##0.00', //harga_sat
                                        '7' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 35, 25, 10, 20, 10, 20], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    //shet alat custom
                                    $writer->writeSheetHeader($nama_sheet2, $rowdata = array(
                                        'ANALISIS PERALATAN' => '0', //No
                                        '2' => 'string', //uraian
                                        '3' => 'string', //rumus
                                        '4' => 'string', //kode
                                        '5' => '#,##0.00', //koefisien
                                        '6' => 'string', //satuan
                                        '7' => '#,##0.00', //harga_sat
                                        '8' => '#,##0.00', //harga_sat
                                        '9' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 35, 25, 10, 20, 10, 20, 20, 20], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 3, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet2, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 8);
                                    #ambil data suku bunga(i)=>informasi umum, upah operator/pembantu, bensin, solar dan pelumas
                                    #ambil data suku bunga i dari informasi umum
                                    $sukuBunga_i = $DB->getWhereArray("informasi_umum", [['kd_proyek', '=', $kd_proyek], ['kode', '=', 'suku_bunga_i', 'AND']]);
                                    $sukuBunga_i = get_object_vars($sukuBunga_i[0])["nilai"];
                                    //bbm solar="M21", pelumas="M22",Upah Operator / Sopir="L04",Upah Pembantu Operator / Pmb.Sopir="L05"
                                    $bbmUpahsql = $DB->getQuery("SELECT * FROM harga_sat_upah_bahan WHERE kd_proyek =? AND (kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ? OR kode = ?)", [$kd_proyek, 'M20', 'M21', 'M22', 'm21', 'm22', 'L04', 'L05', 'l04', 'l5']);
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 6);
                                    $M20 = 0; //bensin
                                    $M21 = 0;
                                    $M22 = 0;
                                    $L04 = 0;
                                    $L05 = 0;
                                    $jumlahArray = is_array($bbmUpahsql) ? count($bbmUpahsql) : 0;
                                    if ($jumlahArray > 0) { //if (sizeof($bbmUpahsql) > 0) {
                                        foreach ($bbmUpahsql as $row) {
                                            $namaVal = $row->kode;
                                            $Valueku = $row->harga_satuan;
                                            $bbmUpah[] = ["$namaVal" => $Valueku]; //ok
                                            "$" . $namaVal = $Valueku;
                                            //var_dump("$" . $namaVal);
                                            //var_dump("$namaVal");
                                            switch ($row->kode) {
                                                case 'm20':
                                                case 'M20':
                                                    $M20 = $row->harga_satuan;
                                                    break;
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
                                    break;
                                case 'tabel': //semua alat dan dibuatkan tabel
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'ANALISA BIAYA SEWA PERALATAN PER JAM KERJA' => '0', //No
                                        '2' => 'string', //jenis_peralatan
                                        '3' => 'string', //kode
                                        '4' => '#,##0.00', //tenaga
                                        '5' => '#,##0.00', //kapasitas
                                        '6' => 'string', //sat_kapasitas
                                        '7' => '#,##0.00', //harga_pakai
                                        '8' => '#,##0.00', //umur
                                        '9' => '#,##0.00', //jam_kerja_1_tahun
                                        '1000' => '#,##0.00', //nilai_sisa
                                        '110' => '#,##0.00', //faktor_pengembalian_mdl
                                        '12' => '#,##0.#####0', //biaya_pengembalian_mdl
                                        '13' => '#,##0.00', //asuransi
                                        '14' => '#,##0.00', //total_biaya_pasti
                                        '15' => '#,##0.00', //bahan_bakar
                                        '16' => '#,##0.#####0', //bahan_bakar1
                                        '17' => '#,##0.00', //bahan_bakar2
                                        '18' => '#,##0.00', //bahan_bakar3
                                        '19' => '#,##0.00', //minyak_pelumas
                                        '20' => '#,##0.#####0', //biaya_bbm
                                        '21' => '#,##0.00', //koef_workshop
                                        '22' => '#,##0.#####0', //biaya_workshop
                                        '23' => '#,##0.#####0', //koef_perbaikan
                                        '24' => '#,##0.00', //biaya_perbaikan
                                        '25' => '#,##0.00', //jumlah_operator
                                        '26' => '#,##0.00', //upah_operator
                                        '27' => '#,##0.00', //jumlah_pembantu_ope
                                        '28' => '#,##0.00', //upah_pembantu_ope
                                        '29' => '#,##0.00', //total_biaya_operasi
                                        '30' => '#,##0.00', //total_biaya_sewa
                                        '99' => '#,##0.00', //total_biaya_sewa
                                        '100' => 'string', //keterangan
                                    ), $col_options = array('widths' => [10, 35, 10, 10, 10, 10, 20, 10, 10, 20, 20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 10, 15, 10, 15, 20, 20, 15], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 8, 'freeze_columns' => 3, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 31); //kop
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 0, $end_row = 6, $end_col = 0); //No.
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 1, $end_row = 6, $end_col = 1); //JENIS PERALATAN
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 2, $end_row = 6, $end_col = 2); //KODE
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 3, $end_row = 3, $end_col = 3); //TENAGA
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 4, $end_row = 3, $end_col = 5); //KAPASITAS dan satuan
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 6, $end_row = 3, $end_col = 6); //harga
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 7, $end_row = 1, $end_col = 9); //ALAT  YANG  DIPAKAI
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 10, $end_row = 3, $end_col = 10); //NILAI sisa
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 11, $end_row = 3, $end_col = 11); //FAKTOR pengemb.
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 12, $end_row = 1, $end_col = 14); //BIAYA PASTI PER JAM
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 15, $end_row = 1, $end_col = 29); //BIAYA OPERASI PER JAM KERJA
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 30, $end_row = 3, $end_col = 30); //TOTAL BIAYA SEWA
                                    $writer->markMergedCell($nama_sheet, $start_row = 1, $start_col = 31, $end_row = 6, $end_col = 31); //ket.
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 7, $end_row = 3, $end_col = 7); //UMUR
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 8, $end_row = 3, $end_col = 8); //JAM
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 9, $end_row = 3, $end_col = 9); //HARGA
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 12, $end_row = 3, $end_col = 12); //BIAYA PENGEM-
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 13, $end_row = 3, $end_col = 13); //BIAYA PENGEM-
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 14, $end_row = 3, $end_col = 14); //BIAYA PENGEM-
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 15, $end_row = 2, $end_col = 20); //BAHAN BAKAR & PELUMAS
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 21, $end_row = 2, $end_col = 22); //WORKSHOP
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 23, $end_row = 2, $end_col = 24); //PERBAIKAN dan PERAWATAN 
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 25, $end_row = 2, $end_col = 28); //UPAH 
                                    $writer->markMergedCell($nama_sheet, $start_row = 2, $start_col = 29, $end_row = 3, $end_col = 29); //TOTALBIAYAOPERASI
                                    $row_tabel = ['NO.', 'JENIS PERALATAN', 'KODE ALAT', 'TENAGA ALAT', 'KAPASITAS ALAT', '', 'HARGA ALAT', 'ALAT YANG DIPAKAI', '', '', 'NILAI SISA ALAT', 'FAKTOR PENGEMBALIAN MODAL', 'BIAYA PASTI PER JAM', '', '', 'BIAYA OPERASI PER JAM KERJA', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TOTAL BIAYA SEWA ALAT PERJAM KERJA', 'KET.'];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill);
                                    $row_tabel = ['', '', '', '', '', '', '', 'UMUR ALAT', 'JAM KERJA 1 TAHUN', 'HARGA ALAT', '', '', 'BIAYA PENGEMBALIAN MODAL', 'ASURANSI DAN LAIN-LAIN', 'TOTAL BIAYA PASTI / JAM', 'BAHAN BAKAR & PELUMAS', '', '', '', '', '', 'WORKSHOP', '', 'PERBAIKAN dan PERAWATAN ', '', 'UPAH', '', '', '', 'TOTAL BIAYA OPERASI / JAM', '', ''];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill);
                                    $row_tabel = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'KOEF BAHAN BAKAR', 'BAHAN BAKAR I', 'BAHAN BAKAR II', 'BAHAN BAKAR III', 'KOEF MINYAK PELUMAS', 'BIAYA', 'KOEF.', 'BIAYA', 'KOEF', 'BIAYA', 'JUMLAH', 'OPERATOR / SOPIR', 'JUMLAH', 'PEMBANTU / SOPIR', '', '', ''];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill);
                                    $row_tabel = ['', '', '', 'HP', '', 'SAT.', '(Rp.)', '(Tahun)', '(Jam)', '(Rp.)', '(Rp.)', '-', '(Rp.)', '(Rp.)', '(Rp.)', '%', 'Rp', 'Rp', 'Rp', '%', '(Rp.)', '(-)', '(Rp.)', '(-)', '(Rp.)', '(n)', '(Rp.)', '(n)', '(Rp.)', '(Rp.)', '(Rp.)', ''];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill); //SATUAN
                                    $row_tabel = ['', '', '', '', '', '', '', '', '', '', '(10% X B)', '(i(1+i)^A))/((1+i)^A-1)', '((B - C) x D)/W', '(0.002 x B)/W', '(e1 + e2)', '10 s/d 12', '', '', '', '0,25 s/d 0,35', '', '', '', '', '', '', '', '', '', '', '', ''];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill); //rumus
                                    $row_tabel = ['', '', '', '', '', '', '', '', '', '', 'C', 'D', 'e1', 'e2', 'E', 'f1', '', '', '', 'f2', 'F', '', '', '', '', '', '', '', '', '', '', ''];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill); //kode
                                    $row_tabel = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32'];
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, $LTRB_hcvcwrap_b_fill); //nomor kolom
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        default:
                            break;
                    }
                    #=============================
                    #==== mulai cetak baris=======
                    #=============================
                    $sumSheet1 = 0;
                    $sumSheet2 = 0;
                    $myrow = 0;
                    $jumlahGet_data = count($get_data);

                    foreach ($get_data as $row) {
                        $myrow++;
                        //var_dump($row);
                        switch ($jenis) {
                            case "monev":
                                switch ($tbl) {
                                    case 'lap_periodik':
                                        $rowdata = array(
                                            $row->kd_analisa,
                                            $row->uraian,
                                            $row->satuan,
                                            (float)$row->volume,
                                            (float)$row->harga_satuan,
                                            (float)$row->jumlah_harga,
                                            (float)$row->Bobot,
                                            (float)$row->RealisasiFisikSebelum,
                                            (float)$row->RealisasiKeuSebelum,
                                            (float)$row->RealisasiFisikPeriode,
                                            (float)$row->RealisasiKeuPeriode,
                                            (float)$row->proggVolSdPeriode_Fisik,
                                            (float)$row->proggVolSdPeriode_Keu,
                                            (float)$row->BtSdPeriodeLaluFisik,
                                            (float)$row->BtSdPeriodeLaluKeu,
                                            (float)$row->BtSdPeriodeIniFisik,
                                            (float)$row->BtSdPeriodeIniKeu,
                                            (float)$row->TotalFisik,
                                            (float)$row->TotalKeu,
                                            '',
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                        break;
                                    case 'import_realisasi':
                                        $phpdate = strtotime($tgl_spm);
                                        $mysqldate = date('Y-m-d', $phpdate);
                                        $tanggal = ($mysqldate > date('Y-m-d')) ? $tgl_spm : '=NOW()';
                                        $rowdata = array(
                                            $row['kd_analisa'],
                                            $row['uraian'],
                                            $row['volume'],
                                            $row['satuan'],
                                            $row['jumlah_harga'],
                                            $tanggal,
                                            0,
                                            0,
                                            ''
                                        );
                                        $style = [$LTRB_fillRed_b, $LTRB_vt, $LTRB_fillRed_b, $LTRB_fillRed_b, $LTRB_fillRed_b, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB];
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $style);
                                        break;
                                    case 'lap_harian':
                                        break;
                                    case 'tabel':
                                        $rowdata = array(
                                            $myrow + 1,
                                            $row['uraian'],
                                            $row['tanggal'],
                                            $row['satuan'],
                                            $row['realisasi_fisik'],
                                            $row['realisasi_keu'],
                                            $row['file'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                break;
                            case 'rab':
                                //var_dump((((float)$row['harga_satuan'] + (float)$row['jumlah_op']) * (float)$row['volume']) * ((100 + $ppn) / 100))
                                $harga_dasar = (float)$row['harga_dasar'];
                                $harga_op = ($op / 100) * $harga_dasar;
                                $harga_satuan = round($harga_op + $harga_dasar, 2);
                                $jumlahHarga = ($harga_satuan * (float)$row['volume']) * ((100 + $ppn) / 100);
                                $sumSheet1 += $jumlahHarga;
                                $rowdata = array(
                                    $row['kd_analisa'],
                                    $row['uraian'],
                                    $row['volume'],
                                    $row['satuan'],
                                    $row['harga_dasar'],
                                    $harga_op,
                                    $harga_satuan,
                                    $ppn,
                                    $jumlahHarga,
                                    $row['keterangan']
                                );
                                $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                //schedule
                                $rowSchedule = $myrow + 3;
                                $cellJumlah = $jumlahGet_data + 4;
                                $bobot = "=(E$rowSchedule/E$cellJumlah)*100";
                                $durasi = $row['durasi'];
                                $mulai = $row['mulai'];
                                $rowdata = array(
                                    $row['kd_analisa'],
                                    $row['uraian'],
                                    $row['volume'],
                                    $row['satuan'],
                                    $jumlahHarga,
                                    $bobot,
                                    $durasi,
                                    $mulai,
                                    $row['bobot_selesai'],
                                    $row['dependent']
                                );
                                $style = [$LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB];
                                $batasCell = $mulai + $durasi;
                                $bobotCell = "=F$rowSchedule/G$rowSchedule";
                                $dataSchedule = (array)json_decode($row['data']);
                                $jumlahArray = is_array($dataSchedule) ? count($dataSchedule) : 0;
                                if ($jumlahArray > 0) {
                                    # code...
                                }
                                for ($x = 0; $x < $MPP; $x++) {
                                    $styleCell = $LTRB_vt;
                                    if ($x >= ($mulai - 1) && $x < ($batasCell - 1)) {
                                        $styleCell = $LTRB_fill;
                                        $rowdata[] = $bobotCell;
                                    } else {
                                        $rowdata[] = '';
                                    }
                                    $style[] = $styleCell;
                                }
                                $rowdata[] = $row['keterangan'];
                                $style[] = $LTRB_vt;
                                $writer->writeSheetRow($nama_sheet2, $rowdata, $style);
                                break;
                            case 'analisa_quarry_awal': //tanpa perekaman
                                switch ($tbl) {
                                    case 'one_by_one': //semua alat tapi dengan detail
                                        $kd_analisa = $row['kd_analisa'];
                                        $keterangan = $row['keterangan'];
                                        //var_dump($keterangan);
                                        $lokasi = 'Quarry';
                                        $tujuan = 'Base Camp';
                                        if (preg_match("/[lokasi][tujuan]/", $keterangan)) { //jika nomor ada huruf 'jenis peralatan' ubah menjadi
                                            //$resultArray = explode(",",$keterangan);
                                            $dataArray = json_decode($keterangan);
                                            $lokasi = $dataArray->lokasi;
                                            $tujuan = $dataArray->tujuan;
                                            //var_dump($dataArray);
                                        }
                                        $writer->writeSheetRow($nama_sheet, ['ANALISA HARGA DASAR SATUAN BAHAN'], $hcvc_b_h20);
                                        $sumSheet2++;
                                        $writer->markMergedCell($nama_sheet, $start_row = $sumSheet2, $start_col = 0, $end_row = $sumSheet2, $end_col = 7);
                                        $jenis_quarry = $row['uraian'];
                                        $writer->writeSheetRow($nama_sheet, ['Jenis', ": $kd_analisa, $jenis_quarry"], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, ['Lokasi', ": $lokasi"], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, ['Tujuan', ": $tujuan "], $vc_b_h20);
                                        $sumSheet2 += 3;
                                        $rowdata = array(
                                            'Nomor',
                                            'Uraian',
                                            'RUMUS',
                                            'KODE',
                                            'KOEF.',
                                            'SATUAN',
                                            'HARGA SATUAN',
                                            'KET.'
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                        $sumSheet2++;
                                        //ambil data di analisa_alat custom
                                        //MEMANGGIL ANALISA DARI KODE ANALISA
                                        $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = '$kd_analisa'";
                                        $order = "ORDER BY no_sortir, id ASC";
                                        $query2 = "SELECT * FROM $tabel_pakai WHERE $where1 $order";
                                        $get_data2 = $DB->runQuery2("$query2");
                                        //var_dump($query2);
                                        // cetak baris analisa
                                        $jumlahArray = is_array($get_data2) ? count($get_data2) : 0;
                                        if ($jumlahArray > 0) {
                                            foreach ($get_data2 as $baris) {
                                                $keterangan = $baris['nomor'] == $kd_analisa ? '' : $baris['keterangan'];
                                                $harga_satuan = $baris['harga_satuan'] > 0 ? $baris['harga_satuan'] : '';
                                                $rowdata = array(
                                                    $baris['nomor'],
                                                    $baris['uraian'],
                                                    $baris['rumus'],
                                                    $baris['kode'],
                                                    $baris['koefisien'],
                                                    $baris['satuan'],
                                                    $harga_satuan,
                                                    $keterangan
                                                );
                                                $uraian = strtolower($baris['uraian']);
                                                $nomor = $baris['nomor'];
                                                $style = $LTRB_vt;
                                                if (preg_match("/[A-Z]/", $nomor)) { //jika nomor ada huruf besar tebalkan huruf
                                                    $style = $LTRB_vt_b;
                                                }
                                                if (preg_match("/jenis peralatan/", $uraian)) { //jika nomor ada huruf 'jenis peralatan' ubah menjadi
                                                    $style = [$LTRB_vt, $LTRB_vt, $LTRB_vt_b, $LTRB_hcvc_b_fabu, $LTRB_vt_b, $LTRB_vt_b, $LTRB_vt_b, $LTRB_vt_b, $LTRB_vt_b];
                                                    $writer->markMergedCell($nama_sheet, $start_row = $sumSheet2 + 1, $start_col = 3, $end_row = $sumSheet2 + 1, $end_col = 6);
                                                }
                                                $writer->writeSheetRow($nama_sheet, $rowdata, $style);
                                                $sumSheet2++;
                                            }
                                            //tambahkan space
                                            $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                            $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                            $sumSheet2 += 2;
                                        };
                                        break;
                                    case 'tabel': //semua alat dan dibuatkan tabel
                                        $rowdata = array(
                                            $row['nomor'],
                                            $row['uraian'],
                                            $row['satuan'],
                                            $row['koefisien'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                break;
                            case 'analisa_quarry':
                            case 'analisa_sda':
                            case 'analisa_bm':
                                $dataPerekaman = array();
                                switch ($tbl) {
                                    case 'one_by_one': //semua alat tapi dengan detail
                                        $kd_analisa = $row['kd_analisa'];
                                        $jenis_pekerjaan = $row['uraian'];
                                        $satuan_pembayaran = $row['satuan'];
                                        //SHEET ANALISA
                                        $writer->writeSheetRow($nama_sheet, ['ITEM PEMBAYARAN', '', ': ' . strtoupper($jenis_pekerjaan) . ' (' . $kd_analisa . ')'], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, ['JENIS PEKERJAAN', '', ': PENGADAAN ' . strtoupper($jenis_pekerjaan)], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, ['SATUAN PEMBAYARAN', '', ': ' . $satuan_pembayaran], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                        $rowdata = array(
                                            'Nomor',
                                            'Uraian',
                                            '',
                                            'KODE',
                                            'KOEFISIEN',
                                            'SATUAN',
                                            'HARGA SATUAN',
                                            'JUMLAH HARGA',
                                            'KETERANGAN'
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                        $sumSheet1 += 5;
                                        $writer->markMergedCell($nama_sheet, $start_row = $sumSheet1, $start_col = 1, $end_row = $sumSheet1, $end_col = 2);
                                        //==================
                                        //==SHEET PEREKAMAN=
                                        //==================
                                        // cek dahulu apa perlu tabel perekaman jika di dalam kode analisa pada kolom nomor ada kode '>>'
                                        $getDataku = $DB->getWhereArray($tabel_pakai, [['kd_proyek', '=', $kd_proyek], ['kd_analisa', '=', $kd_analisa, 'AND'], ['nomor', '=', '>>', 'AND']]);
                                        //$writer->writeSheetRow($nama_sheet2, ['dataPerekaman',json_encode($getDataku),sizeof($getDataku)], $LTRB);
                                        $needPerekaman = 0;
                                        if (sizeof($getDataku) > 0) {
                                            $writer->writeSheetRow($nama_sheet2, ['ITEM PEMBAYARAN', '', ': ' . strtoupper($jenis_pekerjaan) . ' (' . $kd_analisa . ')'], $vc_b_h20);
                                            $writer->writeSheetRow($nama_sheet2, ['JENIS PEKERJAAN', '', ': PENGADAAN ' . strtoupper($jenis_pekerjaan)], $vc_b_h20);
                                            $writer->writeSheetRow($nama_sheet2, ['SATUAN PEMBAYARAN', '', ': ' . $satuan_pembayaran], $vc_b_h20);
                                            $writer->writeSheetRow($nama_sheet2, [''], $vc_b_h20);
                                            $rowdata = array(
                                                'Nomor',
                                                'KOMPONEN',
                                                '',
                                                'SATUAN',
                                                'PERKIRAAN KUANTITAS',
                                                'HARGA SATUAN (Rp.)',
                                                'JUMLAH HARGA (Rp.)'
                                            );
                                            $writer->writeSheetRow($nama_sheet2, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                            $sumSheet2 += 6;
                                            $writer->markMergedCell($nama_sheet2, $start_row = $sumSheet2, $start_col = 1, $end_row = $sumSheet2, $end_col = 2);
                                            $needPerekaman = 1;
                                        }
                                        //MEMANGGIL ANALISA DARI KODE ANALISA
                                        $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = '$kd_analisa'";
                                        $order = "ORDER BY no_sortir, id ASC";
                                        $query2 = "SELECT * FROM $tabel_pakai WHERE $where1 $order";
                                        $get_data2 = $DB->runQuery2("$query2");
                                        // cetak baris analisa
                                        if (sizeof($get_data2) > 0) {
                                            foreach ($get_data2 as $row2) {
                                                $nomor = $row2['nomor'];
                                                $uraian = strtolower($row2['uraian']);
                                                $keterangan = $row2['keterangan'];
                                                if ($row2['nomor'] === '>>') { //masukkan di perekaman jika nomor : '>>'
                                                    $dataPerekaman[] = $row2;
                                                    //$writer->writeSheetRow($nama_sheet2, ['dataPerekaman',json_encode($row2)], $LTRB);
                                                    $nomor = '';
                                                }
                                                //jumlah sebelum pajak
                                                if ($row2['nomor'] === $kd_analisa) { //masukkan di perekaman jika nomor : '>>'
                                                    $jumlahSebelumProfit = $row2['koefisien'];
                                                    //$writer->writeSheetRow($nama_sheet2, ['dataPerekaman',json_encode($row2)], $LTRB);
                                                    $nomor = '';
                                                    $keterangan = '';
                                                }
                                                //mulai cetak
                                                $rowdata = array(
                                                    $nomor,
                                                    $row2['uraian'],
                                                    $row2['rumus'],
                                                    $row2['kode'],
                                                    $row2['koefisien'],
                                                    $row2['satuan'],
                                                    $row2['harga_satuan'],
                                                    $row2['jumlah_harga'],
                                                    $keterangan
                                                );
                                                $style = $LTRB_vt;
                                                if (preg_match("/[A-Z]/", $nomor)) { //jika nomor ada huruf besar tebalkan huruf
                                                    $style = $LTRB_vt_b;
                                                }

                                                $writer->writeSheetRow($nama_sheet, $rowdata, $style);
                                                $sumSheet1++;
                                            }
                                            // jika tdk dibutuhkan perekaman buat 2 baris tambahan untuk mengakomodit jumlah, profit dan keuntungan dan jumlah analisa
                                            // jika analisa quarry tidak dibutuhkan ini
                                            if ($jenis != 'analisa_quarry') {
                                                if ($needPerekaman == 0) {
                                                    $rowdata = array(
                                                        '',
                                                        'TOTAL BIAYA',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $jumlahSebelumProfit,
                                                        ''
                                                    );
                                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                    $biayaOverhead = $jumlahSebelumProfit * ($op / 100);
                                                    $rowdata = array(
                                                        '',
                                                        'OVERHEAD & PROFIT',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $biayaOverhead,
                                                        ''
                                                    );
                                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                    $rowdata = array(
                                                        '',
                                                        'HARGA SATUAN PEKERJAAN  ( E + F )',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $jumlahSebelumProfit + $biayaOverhead,
                                                        ''
                                                    );
                                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                    $sumSheet1 += 3;
                                                }
                                            }
                                            //tambahkan space
                                            $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                            $sumSheet1 += 1;
                                        }
                                        // data perekaman
                                        if (sizeof($dataPerekaman) > 0) {
                                            $dataPerekamanFinal = array();
                                            $sumPerekaman = 0;
                                            foreach ($dataPerekaman as $row_rekam) {
                                                $kode = $row_rekam['kode'];
                                                //cari analisa alat atau harga satuan untuk mendapatkan harga
                                                //upah basic price
                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kode, 'AND'], ['jenis', '!=', "royalty", 'AND']];
                                                $get_data_rek = $DB->getWhereArray('harga_sat_upah_bahan', $condition);
                                                //$writer->writeSheetRow($nama_sheet2, ['kode sum perekaman',json_encode($dataPerekaman[$sumPerekaman]['id'])], $LTRB);
                                                if (count((array)$get_data_rek) > 0) {
                                                    $dataPerekaman[$sumPerekaman]['harga_satuan'] = $get_data_rek[0]->harga_satuan;
                                                    $dataPerekaman[$sumPerekaman]['satuan'] = $get_data_rek[0]->satuan;
                                                    $dataPerekaman[$sumPerekaman]['uraian'] = $get_data_rek[0]->uraian;
                                                    $jenisUpahAlatBahan = $get_data_rek[0]->jenis;
                                                    if ($jenisUpahAlatBahan == 'upah') {
                                                        $dataPerekaman[$sumPerekaman]['jenis'] = 'tenaga';
                                                        $dataPerekamanFinal['tenaga'][] = $dataPerekaman[$sumPerekaman];
                                                    } else if ($jenisUpahAlatBahan == 'bahan') {
                                                        $dataPerekaman[$sumPerekaman]['jenis'] = 'bahan';
                                                        $dataPerekamanFinal['bahan'][] = $dataPerekaman[$sumPerekaman];
                                                    } else {
                                                        $dataPerekaman[$sumPerekaman]['jenis'] = 'peralatan';
                                                        $dataPerekamanFinal['peralatan'][] = $dataPerekaman[$sumPerekaman];
                                                    }
                                                }
                                                //alat
                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kode, 'AND']];
                                                $get_data_rek = $DB->getWhereArray('analisa_alat', $condition);
                                                if (count((array)$get_data_rek) > 0) {
                                                    $dataPerekaman[$sumPerekaman]['harga_satuan'] = $get_data_rek[0]->total_biaya_sewa;
                                                    //$dataPerekaman[$sumPerekaman]['satuan'] = $get_data_rek[0]->satuan;
                                                    $dataPerekaman[$sumPerekaman]['uraian'] = $get_data_rek[0]->jenis_peralatan;
                                                    $dataPerekaman[$sumPerekaman]['jenis'] = 'peralatan';
                                                    $dataPerekamanFinal['peralatan'][] = $dataPerekaman[$sumPerekaman];
                                                }
                                                //analisa quarry
                                                $condition = [['kd_proyek', '=', $kd_proyek], ['kode', '=', $kode, 'AND'], ['kd_analisa', '=', 'nomor', 'AND']];
                                                $get_data_rek = $DB->getWhereArray('analisa_quarry', $condition);
                                                if (count((array)$get_data_rek) > 0) {
                                                    $dataPerekaman[$sumPerekaman]['harga_satuan'] = $get_data_rek[0]->koefisien;
                                                    //$dataPerekaman[$sumPerekaman]['satuan'] = $get_data_rek[0]->satuan;
                                                    $dataPerekaman[$sumPerekaman]['uraian'] = $get_data_rek[0]->uraian;
                                                    $dataPerekaman[$sumPerekaman]['jenis'] = 'bahan';
                                                    $dataPerekamanFinal['peralatan'][] = $dataPerekaman[$sumPerekaman];
                                                }
                                                $sumPerekaman++;
                                            }
                                            //$writer->writeSheetRow($nama_sheet2, ['kode sum perekaman',json_encode($dataPerekamanFinal)],$LTRB);
                                            if (sizeof($dataPerekamanFinal) > 0) {
                                                #cetak tenaga
                                                $writer->writeSheetRow($nama_sheet2, ['A.', 'TENAGA', '', '', '', '', ''], $LTRB_vc_b);
                                                $sumPerekaman++;
                                                //$writer->writeSheetRow($nama_sheet2, ['kode sum perekaman',json_encode($dataPerekamanFinal['tenaga'])],$LTRB);
                                                $nomorUrut = 0;
                                                if (array_key_exists("tenaga", $dataPerekamanFinal)) {
                                                    foreach ($dataPerekamanFinal['tenaga'] as $row_rekam) {
                                                        $nomorUrut++;
                                                        $writer->writeSheetRow($nama_sheet2, [$nomorUrut, $row_rekam['uraian'], $row_rekam['kode'], $row_rekam['satuan'], $row_rekam['koefisien'], $row_rekam['harga_satuan'], $row_rekam['jumlah_harga']], $LTRB_vt);
                                                        $sumPerekaman++;
                                                    }
                                                    $writer->writeSheetRow($nama_sheet2, ['B.', 'BAHAN', '', '', '', '', ''], $LTRB_vc_b);
                                                    $sumPerekaman++;
                                                    $nomorUrut = 0;
                                                }
                                                if (array_key_exists("bahan", $dataPerekamanFinal)) {
                                                    foreach ($dataPerekamanFinal['bahan'] as $row_rekam) {
                                                        $nomorUrut++;
                                                        //$writer->writeSheetRow($nama_sheet2, [$row_rekam->nomor,$row_rekam->uraian,$row_rekam->kode,$row_rekam->satuan,$row_rekam->koefisien,$row_rekam->harga_satuan,$row_rekam->jumlah_harga],$LTRB);
                                                        $writer->writeSheetRow($nama_sheet2, [$nomorUrut, $row_rekam['uraian'], $row_rekam['kode'], $row_rekam['satuan'], $row_rekam['koefisien'], $row_rekam['harga_satuan'], $row_rekam['jumlah_harga']], $LTRB_vt);
                                                        $sumPerekaman++;
                                                    }
                                                    $writer->writeSheetRow($nama_sheet2, ['C.', 'PERALATAN', '', '', '', '', ''], $LTRB_vc_b);
                                                    $sumPerekaman++;
                                                    $nomorUrut = 0;
                                                }
                                                if (array_key_exists("peralatan", $dataPerekamanFinal)) {
                                                    foreach ($dataPerekamanFinal['peralatan'] as $row_rekam) {
                                                        $nomorUrut++;
                                                        //$writer->writeSheetRow($nama_sheet2, [$row_rekam->nomor,$row_rekam->uraian,$row_rekam->kode,$row_rekam->satuan,$row_rekam->koefisien,$row_rekam->harga_satuan,$row_rekam->jumlah_harga],$LTRB);
                                                        $writer->writeSheetRow($nama_sheet2, [$nomorUrut, $row_rekam['uraian'], $row_rekam['kode'], $row_rekam['satuan'], $row_rekam['koefisien'], $row_rekam['harga_satuan'], $row_rekam['jumlah_harga']], $LTRB_vt);
                                                        $sumPerekaman++;
                                                    }
                                                }
                                                // jika tdk dibutuhkan perekaman buat 3 baris tambahan untuk mengakomodir jumlah, profit dan keuntungan dan jumlah analisa
                                                if ($needPerekaman > 0) {
                                                    $rowdata = array(
                                                        '',
                                                        'TOTAL BIAYA',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $jumlahSebelumProfit
                                                    );
                                                    $writer->writeSheetRow($nama_sheet2, $rowdata, $LTRB_vc_b);
                                                    $biayaOverhead = $jumlahSebelumProfit * ($op / 100);
                                                    $rowdata = array(
                                                        '',
                                                        'OVERHEAD & PROFIT',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $biayaOverhead
                                                    );
                                                    $writer->writeSheetRow($nama_sheet2, $rowdata, $LTRB_vc_b);
                                                    $rowdata = array(
                                                        '',
                                                        'HARGA SATUAN PEKERJAAN  ( E + F )',
                                                        '',
                                                        '',
                                                        '',
                                                        '',
                                                        $jumlahSebelumProfit + $biayaOverhead,
                                                    );
                                                    $writer->writeSheetRow($nama_sheet2, $rowdata, $LTRB_vc_b);
                                                    $sumSheet2 += 3;
                                                    //tambahkan space
                                                    $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                                    $sumSheet2 += 1;
                                                }
                                            }
                                        }
                                        break;
                                    case 'tabel': //semua alat dan dibuatkan tabel
                                        $rowdata = array(
                                            $row['nomor'],
                                            $row['uraian'],
                                            $row['satuan'],
                                            $row['koefisien'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                break;
                            case 'analisa_ck':
                                $dataPerekaman = array();
                                switch ($tbl) {
                                    case 'one_by_one': //semua alat tapi dengan detail
                                        $kd_analisa = $row['kd_analisa'];
                                        $jenis_pekerjaan = $row['uraian'];
                                        $satuan_pembayaran = $row['satuan'];
                                        //==================
                                        //==SHEET PEREKAMAN=
                                        //==================
                                        $writer->writeSheetRow($nama_sheet, ['ITEM PEMBAYARAN', '', ': ' . $kd_analisa], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, ['JENIS PEKERJAAN', '', ': ' . strtoupper($jenis_pekerjaan)], $vc_b_h20);
                                        $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                        $rowdata = array(
                                            'NOMOR',
                                            'KOMPONEN',
                                            'KODE',
                                            'SATUAN',
                                            'KOEFISIEN',
                                            'HARGA SATUAN (Rp.)',
                                            'JUMLAH HARGA (Rp.)'
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                        $sumSheet2 += 6;
                                        //$writer->markMergedCell($nama_sheet, $start_row = $sumSheet2, $start_col = 1, $end_row = $sumSheet2, $end_col = 2);
                                        //MEMANGGIL ANALISA DARI KODE ANALISA
                                        $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = '$kd_analisa'";
                                        $order = "ORDER BY no_sortir, id ASC";
                                        $query2 = "SELECT * FROM $tabel_pakai WHERE $where1 $order";
                                        $get_data2 = $DB->runQuery2("$query2");
                                        // cetak baris analisa
                                        if (sizeof($get_data2) > 0) {
                                            // data perekaman
                                            $dataPerekamanFinal = array();
                                            $sumPerekaman = 0;
                                            foreach ($get_data2 as $row_rekam) {
                                                $kode = $row_rekam['kode'];
                                                if ($kode == $kd_analisa) {
                                                    $jumlahSebelumProfit = (float)$row_rekam['koefisien'];
                                                }
                                                $dataPerekaman[] = $row_rekam;
                                                $dataPerekaman[$sumPerekaman]['jenis'] = $row_rekam['jenis_kode'];
                                                $dataPerekamanFinal[$row_rekam['jenis_kode']][] = $dataPerekaman[$sumPerekaman];
                                                $sumPerekaman++;
                                            }
                                            //$writer->writeSheetRow($nama_sheet, ['kode sum perekaman',json_encode($dataPerekamanFinal)],$LTRB);
                                            if (sizeof($dataPerekamanFinal) > 0) {
                                                #cetak tenaga
                                                $writer->writeSheetRow($nama_sheet, ['A.', 'TENAGA KERJA', '', '', '', '', ''], $LTRB_vc_b);
                                                $sumPerekaman++;
                                                $nomorUrut = 0;
                                                if (!empty($dataPerekamanFinal['upah'])) {
                                                    foreach ($dataPerekamanFinal['upah'] as $row_rekam) {
                                                        $nomorUrut++;
                                                        $writer->writeSheetRow($nama_sheet, [$nomorUrut, $row_rekam['uraian'], $row_rekam['kode'], $row_rekam['satuan'], $row_rekam['koefisien'], $row_rekam['harga_satuan'], $row_rekam['jumlah_harga']], $LTRB_vt);
                                                        $sumPerekaman++;
                                                    }
                                                }

                                                $writer->writeSheetRow($nama_sheet, ['B.', 'BAHAN', '', '', '', '', ''], $LTRB_vc_b);
                                                $sumPerekaman++;
                                                $nomorUrut = 0;
                                                if (!empty($dataPerekamanFinal['bahan'])) {
                                                    foreach ($dataPerekamanFinal['bahan'] as $row_rekam) {
                                                        $nomorUrut++;
                                                        $writer->writeSheetRow($nama_sheet, [$nomorUrut, $row_rekam['uraian'], $row_rekam['kode'], $row_rekam['satuan'], $row_rekam['koefisien'], $row_rekam['harga_satuan'], $row_rekam['jumlah_harga']], $LTRB_vt);
                                                        $sumPerekaman++;
                                                    }
                                                }

                                                $writer->writeSheetRow($nama_sheet, ['C.', 'PERALATAN', '', '', '', '', ''], $LTRB_vc_b);
                                                $sumPerekaman++;
                                                $nomorUrut = 0;
                                                if (!empty($dataPerekamanFinal['peralatan'])) {
                                                    foreach ($dataPerekamanFinal['peralatan'] as $row_rekam) {
                                                        $nomorUrut++;
                                                        //$writer->writeSheetRow($nama_sheet2, [$row_rekam->nomor,$row_rekam->uraian,$row_rekam->kode,$row_rekam->satuan,$row_rekam->koefisien,$row_rekam->harga_satuan,$row_rekam->jumlah_harga],$LTRB);
                                                        $writer->writeSheetRow($nama_sheet, [$nomorUrut, $row_rekam['uraian'], $row_rekam['kode'], $row_rekam['satuan'], $row_rekam['koefisien'], $row_rekam['harga_satuan'], $row_rekam['jumlah_harga']], $LTRB_vt);
                                                        $sumPerekaman++;
                                                    }
                                                }

                                                $rowdata = array(
                                                    '',
                                                    '',
                                                    '',
                                                    '',
                                                    '',
                                                    '',
                                                    ''
                                                );
                                                $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                // jika tdk dibutuhkan perekaman buat 3 baris tambahan untuk mengakomodir jumlah, profit dan keuntungan dan jumlah analisa
                                                $rowdata = array(
                                                    '',
                                                    'TOTAL BIAYA',
                                                    '',
                                                    '',
                                                    '',
                                                    '',
                                                    $jumlahSebelumProfit
                                                );
                                                $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                $biayaOverhead = $jumlahSebelumProfit * ($op / 100);
                                                $rowdata = array(
                                                    '',
                                                    'OVERHEAD & PROFIT',
                                                    '',
                                                    '',
                                                    '',
                                                    '',
                                                    $biayaOverhead
                                                );
                                                $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                $rowdata = array(
                                                    '',
                                                    'HARGA SATUAN PEKERJAAN  ( E + F )',
                                                    '',
                                                    '',
                                                    '',
                                                    '',
                                                    $jumlahSebelumProfit + $biayaOverhead,
                                                );
                                                $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_b);
                                                $sumSheet2 += 3;
                                                //tambahkan space
                                                $writer->writeSheetRow($nama_sheet, [''], $vc_b_h20);
                                                $sumSheet2 += 1;
                                            }
                                        }
                                        break;
                                    case 'tabel': //semua alat dan dibuatkan tabel
                                        $rowdata = array(
                                            $row['nomor'],
                                            $row['uraian'],
                                            $row['satuan'],
                                            $row['koefisien'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                break;
                            case 'harga_satuan':
                                switch ($tbl) {
                                    case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                        $rowdata = array(
                                            $myrow,
                                            $row['kode'],
                                            $row['jenis'],
                                            $row['uraian'],
                                            $row['satuan'],
                                            $row['harga_satuan'],
                                            $row['sumber_data'],
                                            $row['spesifikasi'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB);
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'analisa_alat':
                                switch ($tbl) {
                                    case 'one_by_one': //semua alat tapi dengan detail
                                        //$sumSheet1++;
                                        $keterangan = $row['keterangan'];
                                        if ($keterangan == 'analisa_alat_custom') {
                                            $kd_analisa = $row['kode'];
                                            $writer->writeSheetRow($nama_sheet2, ['URAIAN ANALISA ALAT CUSTOM'], $hcvc_b_h20);
                                            $sumSheet2++;
                                            $writer->markMergedCell($nama_sheet2, $start_row = $sumSheet2, $start_col = 0, $end_row = $sumSheet2, $end_col = 8);
                                            $rowdata = array(
                                                'Nomor',
                                                'Uraian',
                                                'RUMUS',
                                                'KODE',
                                                'KOEF.',
                                                'SATUAN',
                                                'HARGA SATUAN',
                                                'JUMLAH',
                                                'KET.'
                                            );
                                            $writer->writeSheetRow($nama_sheet2, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                            $sumSheet2++;
                                            //ambil data di analisa_alat custom
                                            //MEMANGGIL ANALISA DARI KODE ANALISA
                                            $where1 = "kd_proyek = '$kd_proyek' AND kd_analisa = '$kd_analisa'";
                                            $order = "ORDER BY no_sortir, id ASC";
                                            $query2 = "SELECT * FROM analisa_alat_custom WHERE $where1 $order";
                                            $get_data2 = $DB->runQuery2("$query2");
                                            //$writer->writeSheetRow($nama_sheet2, ['dataPerekaman',json_encode($get_data2),sizeof($get_data2),$query2], $LTRB);
                                            //$writer->writeSheetRow($nama_sheet2, $get_data2, $LTRB_hcvcwrap_b_fill_h40);
                                            // cetak baris analisa
                                            $jumlahArray = is_array($get_data2) ? count($get_data2) : 0;
                                            if ($jumlahArray > 0) {
                                                // data perekaman
                                                $dataPerekamanFinal = array();
                                                $sumPerekaman = 0;
                                                foreach ($get_data2 as $baris) {
                                                    $rowdata = array(
                                                        $baris['nomor'],
                                                        $baris['uraian'],
                                                        $baris['rumus'],
                                                        $baris['kode'],
                                                        $baris['koefisien'],
                                                        $baris['satuan'],
                                                        $baris['harga_satuan'],
                                                        $baris['jumlah_harga'],
                                                        $baris['keterangan']
                                                    );
                                                    $uraian = strtolower($baris['uraian']);
                                                    $nomor = $baris['nomor'];
                                                    $style = $LTRB_vt;
                                                    if (preg_match("/[A-Z]/", $nomor)) { //jika nomor ada huruf besar tebalkan huruf
                                                        $style = $LTRB_vt_b;
                                                    }
                                                    if (preg_match("/jenis peralatan/", $uraian)) { //jika nomor ada huruf 'jenis peralatan' ubah menjadi
                                                        $style = [$LTRB_vt, $LTRB_vt, $LTRB_vt_b, $LTRB_hcvc_b_fabu, $LTRB_vt_b, $LTRB_vt_b, $LTRB_vt_b, $LTRB_vt_b, $LTRB_vt_b];
                                                        $writer->markMergedCell($nama_sheet2, $start_row = $sumSheet2 + 1, $start_col = 3, $end_row = $sumSheet2 + 1, $end_col = 6);
                                                    }
                                                    $writer->writeSheetRow($nama_sheet2, $rowdata, $style);
                                                    $sumSheet2++;
                                                }
                                            };
                                        } else {
                                            $writer->writeSheetRow($nama_sheet, ['URAIAN ANALISA ALAT'], $hcvc_b_h20);
                                            $rowdata = array(
                                                'Nomor',
                                                'Uraian',
                                                '',
                                                'KODE',
                                                'KOEF',
                                                'SATUAN',
                                                'KET.'
                                            );
                                            $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_hcvcwrap_b_fill_h40);
                                            $writer->markMergedCell($nama_sheet, $start_row = $sumSheet1 + 1, $start_col = 0, $end_row = $sumSheet1 + 1, $end_col = 6);
                                            $writer->markMergedCell($nama_sheet, $start_row = $sumSheet1 + 2, $start_col = 1, $end_row = $sumSheet1 + 2, $end_col = 2);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['A', 'URAIAN', '', '', '', '', ''], [$LTR_hcvc_b, $LT_vcwrap_b, $TR_hlvcwrap_b, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTR_hcvc_b]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['1', 'Jenis Peralatan', '', $row['jenis_peralatan'], '', '', $row['kode']], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LTRB_hcvc_b_fabu, $LTRB_hcvcwrap_b, $LTRB_hcvcwrap_b, $LR_hcvc_b]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['2', 'Tenaga', '', 'Pw', $row['tenaga'], 'HP', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LTR_hcvc, $LTR_vc, $LTR_hcvc, $LR_hcvc_b]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['3', 'Kapasitas', '', 'Cp', $row['kapasitas'], $row['sat_kapasitas'], ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['4', 'Alat', 'a. umur ekonomis', 'A', $row['umur'], 'Tahun', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', 'b. Jam Kerja Dalam 1 Tahun', 'W', $row['jam_kerja_1_tahun'], 'Jam', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', 'c. Harga Alat', 'B', $row['harga_pakai'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->markMergedCell($nama_sheet, $start_row = $sumSheet1 + 4, $start_col = 3, $end_row = $sumSheet1 + 4, $end_col = 5);
                                            // cek kolom ketentuan_tambahan
                                            $sumSheet1 += 9;
                                            $arrayVal = json_decode($row['ketentuan_tambahan'], true);
                                            if (array_key_exists('X', $arrayVal) && $arrayVal['X']['uraian'] && $arrayVal['X']['koef']) {
                                                $writer->writeSheetRow($nama_sheet, $rowdata = ['5', $arrayVal['X']['uraian'], '', 'Ca', $arrayVal['X']['koef'], $arrayVal['X']['satuan'], ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                                $sumSheet1++;
                                            }
                                            //lanjut form standar
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['B', 'BIAYA PASTI PER JAM KERJA', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['1', 'Nilai Sisa Alat', '= 10 % x B', 'C', $row['nilai_sisa'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['2', 'Faktor Angsuran Modal', '(i x (1 + i)^A))/((1 + i)^A - 1)', 'D', $row['faktor_pengembalian_mdl'], '=', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['3', 'Biaya Pasti per Jam  :', '', '', '', '=', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', 'a. Biaya Pengembalian Modal', '(( B - C ) x D)/W', 'E', $row['biaya_pengembalian_mdl'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', 'b. Asuransi, dll', '(0,002 x B)/W', 'F', $row['asuransi'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', 'Biaya Pasti per Jam', 'E+F', 'G', $row['total_biaya_pasti'], 'Rupiah', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc_b, $LR_vcwrap_b, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['C', 'BIAYA OPERASI PER JAM KERJA', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['1', 'Bahan Bakar', '(10%-12%) x Pw x M21', 'H1', $row['bahan_bakar1'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $sumSheet1 += 11;
                                            //bahan bakar 2 dan 3
                                            if (array_key_exists('bahan_bakar2', $arrayVal) && $arrayVal['bahan_bakar2']['uraian']) {
                                                $sumSheet1++;
                                                $writer->writeSheetRow($nama_sheet, $rowdata = ['', $arrayVal['bahan_bakar2']['uraian'], $arrayVal['bahan_bakar2']['rumus'], 'H2', $row['bahan_bakar2'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            }
                                            if (array_key_exists('bahan_bakar3', $arrayVal) && $arrayVal['bahan_bakar3']['uraian']) {
                                                $sumSheet1++;
                                                $writer->writeSheetRow($nama_sheet, $rowdata = ['', $arrayVal['bahan_bakar3']['uraian'], $arrayVal['bahan_bakar3']['rumus'], 'H3', $row['bahan_bakar3'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            }
                                            //lanjutkan form standar
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['2', 'Pelumas', '(0,25%-0,35%) x Pw x M22', 'I', $row['minyak_pelumas'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['3', 'Biaya bengkel', '((2,2% - 2,8%) x B)/W)', 'J', $row['biaya_workshop'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['4', 'Biaya perbaikan', '((6,4 % - 9 %) x B)/W)', 'K', $row['biaya_perbaikan'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $jumlahOperator = $row['jumlah_operator'];
                                            $jumlahPembOperator = $row['jumlah_pembantu_ope'];
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['5', 'Operator', "( $jumlahOperator Orang / Jam ) x L04", 'L', $row['upah_operator'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['6', 'Pembantu Operator', "( $jumlahPembOperator Orang / Jam ) x L05", 'M', $row['upah_pembantu_ope'], 'Rupiah', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', 'Biaya Operasi per Jam', '(H+I+J+K+L+M)', 'P', $row['total_biaya_operasi'], 'Rupiah', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc_b, $LR_vcwrap_b, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            //$LR_hcvc_b['height'] = 40;
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['D', 'TOTAL BIAYA SEWA ALAT / JAM', '( G + P )', 'T', $row['total_biaya_sewa'], 'Rupiah', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LTRB_vcwrap_b, $LTRB_vcwrap_b, $LTRB_vcwrap_b, $LR_hcvc, 'height' => 25]);
                                            $sumSheet1 += 8;
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['D', 'LAIN - LAIN', '', '', '', '', ''], [$LR_hcvc_b, $L_vcwrap_b, $R_vcwrap_b, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['1', 'Tingkat Suku Bunga', "", 'i', $sukuBunga_i, '% / Tahun', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['2', 'Upah Operator / Sopir', "", 'L04', $L04, 'Rp./Jam', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['3', 'Upah Pembantu Operator / Pmb.Sopir', "", 'L05', $L05, 'Rp./Jam', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['4', 'Bahan Bakar Bensin', "", 'M20', $M20, 'Rp./liter', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['5', 'Bahan Bakar Solar', "", 'M21', $M21, 'Rp./liter', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['6', 'Minyak Pelumas', "", 'M22', $M22, 'Rp./liter', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['7', 'PPN diperhitungkan pada lembar Rekapitulasi Biaya Pekerjaan', "", '', '', '', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['7', 'Bahan bakar Batubara', "", '', '', '', ''], [$LR_hcvc, $L_vcwrap, $R_vcwrap, $LR_hcvc, $LR_vc, $LR_hcvc, $LR_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = ['', '', "", '', '', '', ''], [$LRB_hcvc, $LB_hcvc, $RB_hcvc, $LRB_hcvc, $LRB_hcvc, $LRB_hcvc, $LRB_hcvc]);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = [''], []);
                                            $writer->writeSheetRow($nama_sheet, $rowdata = [''], []);
                                            $sumSheet1 += 13;
                                        }
                                        break;
                                    case 'tabel': //semua alat dan dibuatkan tabel
                                        $rowdata = array(
                                            $myrow, //No
                                            $row['jenis_peralatan'],
                                            $row['kode'],
                                            $row['tenaga'],
                                            $row['kapasitas'],
                                            $row['sat_kapasitas'],
                                            $row['harga_pakai'],
                                            $row['umur'],
                                            $row['jam_kerja_1_tahun'],
                                            $row['harga_pakai'],
                                            $row['nilai_sisa'],
                                            $row['faktor_pengembalian_mdl'],
                                            $row['biaya_pengembalian_mdl'],
                                            $row['asuransi'],
                                            $row['total_biaya_pasti'],
                                            $row['bahan_bakar'],
                                            $row['bahan_bakar1'],
                                            $row['bahan_bakar2'],
                                            $row['bahan_bakar3'],
                                            $row['minyak_pelumas'],
                                            $row['biaya_bbm'],
                                            $row['koef_workshop'],
                                            $row['biaya_workshop'],
                                            $row['koef_perbaikan'],
                                            $row['biaya_perbaikan'],
                                            $row['jumlah_operator'],
                                            $row['upah_operator'],
                                            $row['jumlah_pembantu_ope'],
                                            $row['upah_pembantu_ope'],
                                            $row['total_biaya_operasi'],
                                            $row['total_biaya_sewa'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    #=====================
                    #==== TAMBAHAN =======
                    #=====================
                    switch ($jenis) {
                        case 'monev':
                            switch ($tbl) {
                                case 'lap_periodik':
                                    $baris_akhir = $myrow + 5;
                                    $rowdata = array(
                                        '',
                                        '',
                                        '',
                                        '',
                                        'JUMLAH',
                                        "=SUM(F6:F$baris_akhir)",
                                        "=SUM(G6:G$baris_akhir)",
                                        '',
                                        "=SUM(I6:I$baris_akhir)",
                                        '',
                                        "=SUM(K6:K$baris_akhir)",
                                        '',
                                        "=SUM(M6:M$baris_akhir)",
                                        "=SUM(N6:N$baris_akhir)",
                                        "=SUM(O6:O$baris_akhir)",
                                        "=SUM(P6:P$baris_akhir)",
                                        "=SUM(Q6:Q$baris_akhir)",
                                        "=SUM(R6:R$baris_akhir)",
                                        "=SUM(S6:S$baris_akhir)",
                                        '',
                                    );
                                    $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vc_fillRed_b_20);
                                    //ambil data laporan harian dar
                                    //$tanggal_awal, $tanggal_akhir);

                                    // $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '>=',$tanggal_awal, 'AND'], ['tanggal', '<=',$tanggal_akhir, 'AND']];
                                    // $DB->orderByString("DATE(tanggal) ASC, FIELD(type, 'upah', 'bahan', 'peralatan', 'cuaca', 'note')");//field digunakan untuk sortir custom
                                    // $resultDataHarian = $DB->getWhereCustom('laporan_harian', $condition);
                                    // $jumlahArray = is_array($resultDataHarian) ? count($resultDataHarian) : 0;
                                    // if ($jumlahArray) {
                                    //     foreach ($resultDataHarian as $row) {
                                    //         $rowdata = array(
                                    //             $myrow + 1,
                                    //             $row->kd_analisa,
                                    //             $row->kode,
                                    //             $row->tanggal,
                                    //             $row->type,
                                    //             $row->uraian,
                                    //             json_encode($row->value),
                                    //             $row->keterangan
                                    //         );
                                    //         $writer->writeSheetRow('lap harian', $rowdata, $LTRB);
                                    //     }
                                    // }
                                    //$sumDates = $DB->getDistinct('laporan_harian', 'tanggal', [['kd_proyek', '=', $kd_proyek]]);
                                    $sumDates = $DB->getDistinct('laporan_harian', 'tanggal', [['kd_proyek', '=', $kd_proyek], ['tanggal', '>=', $tanggal_awal, 'AND'], ['tanggal', '<=', $tanggal_akhir, 'AND']]);
                                    $jumlahArray = is_array($sumDates) ? count($sumDates) : 0;
                                    if ($jumlahArray) {
                                        //ambil jumlah type
                                        $rowsLapHarian = 0;
                                        foreach ($sumDates as $row) {
                                            $tanggalLaporan = $row->tanggal;
                                            $selisihTanggal = $Fungsi->selisihTanggal($tgl_spm, $tanggalLaporan);
                                            $tglArray = $Fungsi->tanggal($tanggalLaporan);
                                            $tglArraySPM = $Fungsi->tanggal($tgl_spm, $MPP);
                                            //cetak kop laporan harian
                                            $writer->writeSheetHeader('lap harian', $rowdata = array(
                                                'LAPORAN HARIAN' => 'string', //No 
                                                '2' => 'string', //No. MP.
                                                '3' => 'string', //Kode
                                                '4' => 'string', //TENAGA KERJA
                                                '5' => '#,##0.####0', //JUMLAH
                                                '6' => 'string', //No. MP.
                                                '7' => '#,##0.####0', //Kode
                                                '8' => '#,##0.####0', //ALAT YANG DIGUNAKAN
                                                '9' => 'string', //UNIT/SAT
                                                '10' => 'string', //TYPE/MERK
                                                '11' => '#,##0.####0', //TERIMA
                                                '12' => '#,##0.####0', //TOLAK
                                                '13' => 'string', //ALASAN
                                                '14' => '#,##0.####0', //JAM
                                                '15' => '#,###', //C
                                                '16' => '#,###', //M
                                                '17' => '#,###', //A
                                                '18' => '#,###', //G
                                                '19' => '#,###', //H
                                                '20' => '#,###', //L
                                            ), $col_options = array('widths' => [10, 10, 10, 15, 10, 10, 10, 20, 10, 15, 10, 10, 15, 10, 5, 5, 5, 5, 5, 5], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 5, 'height' => 30, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center')); //, 'freeze_columns' => 2
                                            $writer->markMergedCell('lap harian', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 19);
                                            //cetak kop ket plaporan
                                            $rowdata = array(
                                                'KEGIATAN/PEKERJAAN',
                                                '', //No. MP.
                                                '', //Kode
                                                '', //TENAGA KERJA
                                                ":  " . strtoupper($kegiatan), //JUMLAH
                                                '', //No. MP.
                                                '', //Kode
                                                '', //ALAT YANG DIGUNAKAN
                                                '', //UNIT/SAT
                                                '', //TYPE/MERK
                                                '', //TERIMA
                                                '', //TOLAK
                                                '', //ALASAN
                                                'HARI', //JAM
                                                '', //C
                                                ":  " . $tglArray['hari'], //M
                                                '', //A
                                                '', //G
                                                '', //H
                                                '' //L
                                            );
                                            $writer->writeSheetRow('lap harian', $rowdata, $TB_b_h20);
                                            $rowdata = array(
                                                'PELAKSANA',
                                                '', //No. MP.
                                                '', //Kode
                                                '', //TENAGA KERJA
                                                ":  " . strtoupper($pelaksana), //JUMLAH
                                                '', //No. MP.
                                                '', //Kode
                                                '', //ALAT YANG DIGUNAKAN
                                                '', //UNIT/SAT
                                                '', //TYPE/MERK
                                                '', //TERIMA
                                                '', //TOLAK
                                                '', //ALASAN
                                                'TANGGAL', //JAM
                                                '', //C
                                                ":  {$tglArray['tanggal']} {$tglArray['bulan']} {$tglArray['tahun']}", //M
                                                '', //A
                                                '', //G
                                                '', //H
                                                '' //L
                                            );
                                            $writer->writeSheetRow('lap harian', $rowdata, $TB_b_h20);

                                            $rowdata = array(
                                                'NOMOR KONTRAK (WAKTU PELAKSANAAN)',
                                                '', //No. MP.
                                                '', //Kode
                                                '', //TENAGA KERJA
                                                ":  $no_kontrak , ( " . number_format($MPP, 0, ',', '.') . " hari kalender )", //JUMLAH
                                                '', //No. MP.
                                                '', //Kode
                                                '', //ALAT YANG DIGUNAKAN
                                                '', //UNIT/SAT
                                                '', //TYPE/MERK
                                                '', //TERIMA
                                                '', //TOLAK
                                                '', //ALASAN
                                                'MINGGU KE', //JAM
                                                '', //C
                                                ":  " . $selisihTanggal['weekends'], //M
                                                '', //A
                                                '', //G
                                                '', //H
                                                '' //L
                                            );
                                            $writer->writeSheetRow('lap harian', $rowdata, $TB_b_h20);
                                            $rowdata = array(
                                                'MASA KONTRAK',
                                                '', //No. MP.
                                                '', //Kode
                                                '', //TENAGA KERJA
                                                ":  {$tglArraySPM['tgl']} sd {$tglArraySPM['tgl_plus_add']}", //JUMLAH
                                                '', //No. MP.
                                                '', //Kode
                                                '', //ALAT YANG DIGUNAKAN
                                                '', //UNIT/SAT
                                                '', //TYPE/MERK
                                                '', //TERIMA
                                                '', //TOLAK
                                                '', //ALASAN
                                                'BULAN KE', //JAM
                                                '', //C
                                                ":  " . $selisihTanggal['bulan'], //M//$selisihTanggal['bulan']  json_encode($selisihTanggal)
                                                '', //A
                                                '', //G
                                                '', //H
                                                '' //L
                                            );
                                            $writer->writeSheetRow('lap harian', $rowdata, $TB_b_h20);
                                            $rowdata = array(
                                                'No',
                                                'No. MP.', //No. MP.
                                                'Kode', //Kode
                                                'TENAGA KERJA', //TENAGA KERJA
                                                'JUMLAH', //JUMLAH
                                                'No. MP.', //No. MP.
                                                'Kode', //Kode
                                                'ALAT YANG DIGUNAKAN', //ALAT YANG DIGUNAKAN
                                                'UNIT', //UNIT/SAT
                                                'TYPE/MERK', //TYPE/MERK
                                                'TERIMA', //TERIMA
                                                'TOLAK', //TOLAK
                                                'ALASAN', //ALASAN
                                                'CUACA', //JAM
                                                '', //C
                                                '', //M
                                                '', //A
                                                '', //G
                                                '', //H
                                                '' //L
                                            );
                                            $writer->writeSheetRow('lap harian', $rowdata, $LTRB_hcvcwrap_b_fillRed);
                                            $rowsLapHarian += 5;
                                            $writer->markMergedCell('lap harian', $start_row = $rowsLapHarian, $start_col = 13, $end_row = $rowsLapHarian, $end_col = 19);

                                            //$sum = $DB->getQuery("SELECT COUNT(type = 'upah') AS countUpah, COUNT(type = 'bahan') AS countBahan, COUNT(type = 'peralatan') AS countPeralatan, COUNT(type = 'cuaca') AS countCuaca, COUNT(type = 'note') AS countNote FROM laporan_harian WHERE $where1 GROUP BY type", $data_where1);
                                            $where1 = 'kd_proyek = ? AND tanggal = ?';
                                            $data_where1 = [$kd_proyek, $tanggalLaporan];
                                            $sumType = $DB->getQuery("SELECT type,
                                    sum(case when type = 'upah' then 1 else 0 end) AS countUpah, 
                                    sum(case when type = 'bahan' then 1 else 0 end) AS countBahan, 
                                    sum(case when type = 'peralatan' then 1 else 0 end) AS countPeralatan, 
                                    sum(case when type = 'cuaca' then 1 else 0 end) AS countCuaca, 
                                    sum(case when type = 'note' then 1 else 0 end) AS countNote 
                                    FROM laporan_harian WHERE $where1 GROUP BY type", $data_where1);
                                            //$rowdata = array(json_encode($sum));
                                            //$writer->writeSheetRow('lap harian', $rowdata, $LTRB);
                                            $upah = 0;
                                            $bahan = 0;
                                            $peralatan = 0;
                                            $cuaca = 0;
                                            $note = 0;
                                            foreach ($sumType as $key2 => $value2) {
                                                switch ($value2->type) {
                                                    case 'upah':
                                                        ${$value2->type} = $value2->countUpah;
                                                        break;
                                                    case 'bahan':
                                                        ${$value2->type} = $value2->countBahan;
                                                        break;
                                                    case 'peralatan':
                                                        ${$value2->type} = $value2->countPeralatan;
                                                        break;
                                                    case 'cuaca':
                                                        ${$value2->type} = $value2->countCuaca;
                                                        break;
                                                    case 'note':
                                                        ${$value2->type} = $value2->countNote;
                                                        break;
                                                }
                                            };
                                            // $rowdata = array(json_encode($sumType), $upah, $bahan, $peralatan, $cuaca, $note);
                                            // $writer->writeSheetRow('lap harian', $rowdata, $LTRB);
                                            //ambil data pekerjaan yang diselesaikan sesuai $tanggalLaporan
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND']];
                                            $DB->orderBy("id_rab");
                                            $listPek = $DB->getWhereCustom('monev', $condition);
                                            $jumlahArray = is_array($listPek) ? count($listPek) : 0;
                                            $countListPek = 0;
                                            if ($jumlahArray > 0) {
                                                //$listPek = $listPek;
                                                $countListPek = count($listPek);
                                            }
                                            $maxRowsAll = 25;
                                            $maxRowsAlat = 8;
                                            $maxRowsBahan = 6;
                                            $maxRowsNote = 8;
                                            $maxRowsPek = 8;
                                            $maxRowsAlat = ($peralatan > $maxRowsAlat) ? $peralatan : $maxRowsAlat;
                                            $maxRowsBahan = ($bahan > $maxRowsBahan) ? $bahan : $maxRowsBahan;
                                            $maxRowsPek = ($note > $maxRowsPek) ? $note : $maxRowsPek;
                                            //jumlah baris listPek
                                            //$maxRowsUpahBahan = ($upah > $maxRowsUpahBahan) ? $upah : $maxRowsUpahBahan;
                                            //cetak upah bahan
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND']];
                                            $DB->orderByString("FIELD(type, 'upah', 'bahan', 'peralatan', 'cuaca', 'note')");
                                            //$DB->getWhereCustom('laporan_harian', $condition);
                                            //$resultDataHarian = $DB->getQuery("SELECT type, kd_analisa, kode, uraian, GROUP_CONCAT(value) as listType FROM laporan_harian WHERE $where1 GROUP BY type ORDER BY FIELD(type, 'upah', 'bahan', 'peralatan', 'cuaca', 'note')", $data_where1);
                                            $resultDataHarian = $DB->getQuery("SELECT type, GROUP_CONCAT(CONCAT('{id:\"', id, '\", value:\"',value,'\", kd_analisa:\"',kd_analisa,'\", kode:\"',kode,'\", uraian:\"',uraian,'\", keterangan:\"',keterangan,'\"}')) as listType FROM laporan_harian WHERE $where1 GROUP BY type ORDER BY FIELD(type, 'upah', 'bahan', 'peralatan', 'cuaca', 'note')", $data_where1);
                                            $ListUpah = [];
                                            $ListBahan = [];
                                            $ListPeralatan = [];
                                            $ListCuaca = [];
                                            $ListNote = [];
                                            $valueUpah = [];
                                            $valueBahan = [];
                                            $valuePeralatan = [];
                                            $valueCuaca = [];
                                            $valueNote = [];
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND'], ['type', '=', 'upah', 'AND']];
                                            $ListUpah = $DB->getWhereCustom('laporan_harian', $condition);
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND'], ['type', '=', 'bahan', 'AND']];
                                            $ListBahan = $DB->getWhereCustom('laporan_harian', $condition);
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND'], ['type', '=', 'peralatan', 'AND']];
                                            $ListPeralatan = $DB->getWhereCustom('laporan_harian', $condition);
                                            $DB->orderBy("kode");
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND'], ['type', '=', 'cuaca', 'AND']];
                                            $ListCuaca = $DB->getWhereCustom('laporan_harian', $condition);
                                            $condition = [['kd_proyek', '=', $kd_proyek], ['tanggal', '=', $tanggalLaporan, 'AND'], ['type', '=', 'note', 'AND']];
                                            $ListNote = $DB->getWhereCustom('laporan_harian', $condition);



                                            $countListUpah = is_array($ListUpah) ? count($ListUpah) : 0; //count($listUpah); //jumlah baris listUpah
                                            $countListBahan = is_array($ListBahan) ? count($ListBahan) : 0; //count($listBahan); //jumlah baris listUpah
                                            $countListPeralatan = is_array($ListPeralatan) ? count($ListPeralatan) : 0; //count($listPeralatan); //jumlah baris listUpah
                                            $countListCuaca = is_array($ListCuaca) ? count($ListCuaca) : 0; //count($listCuaca); //jumlah baris listUpah
                                            $countListNote = is_array($ListNote) ? count($ListNote) : 0; //count($listNote); //jumlah baris listUpah
                                            $countListPek = is_array($listPek) ? count($listPek) : 0; //count($listPek); //jumlah baris listUpah
                                            $batasInputBahanPeralatan = $maxRowsAlat + $maxRowsPek + 1;
                                            $iBahan = 0;
                                            $iPeke = 0;
                                            $iJam = 0;
                                            for ($i = 0; $i < $maxRowsAll; $i++) {

                                                if ($iJam > 24) {
                                                    $iJam = 0;
                                                }
                                                //cuaca
                                                if ($i < 24 && $iJam >= 0) {
                                                    // $value = $ListCuaca[$i]->value;
                                                    // $value = json_decode($value);
                                                    $nilai = '';
                                                    foreach ($ListCuaca as $key4 => $value4) {
                                                        $jam = $value4->kode;
                                                        $strJam = (int) date('G', strtotime($jam));
                                                        if ($strJam == $i - 1) {
                                                            $nilai = $value4->uraian;
                                                        }
                                                    }
                                                    switch ($nilai) {
                                                        case 'cerah':
                                                            $dataCuacaInser = [
                                                                'X', //C
                                                                '', //M
                                                                '', //A
                                                                '', //G
                                                                '', //H
                                                                '' //L
                                                            ];
                                                            break;
                                                        case 'mendung':
                                                            $dataCuacaInser = [
                                                                '', //C
                                                                'X', //M
                                                                '', //A
                                                                '', //G
                                                                '', //H
                                                                '' //L
                                                            ];
                                                            break;
                                                        case 'angin_kencang':
                                                            $dataCuacaInser = [
                                                                '', //C
                                                                '', //M
                                                                'X', //A
                                                                '', //G
                                                                '', //H
                                                                '' //L
                                                            ];
                                                            break;
                                                        case 'gerimis':
                                                            $dataCuacaInser = [
                                                                '', //C
                                                                '', //M
                                                                '', //A
                                                                'X', //G
                                                                '', //H
                                                                '' //L
                                                            ];
                                                            break;
                                                        case 'hujan_lebat':
                                                            $dataCuacaInser = [
                                                                '', //C
                                                                '', //M
                                                                '', //A
                                                                '', //G
                                                                'X', //H
                                                                '' //L
                                                            ];
                                                            break;
                                                        case 'lainnya':
                                                            $dataCuacaInser = [
                                                                '', //C
                                                                '', //M
                                                                '', //A
                                                                '', //G
                                                                '', //H
                                                                'X' //L
                                                            ];
                                                            break;
                                                        default:
                                                            $dataCuacaInser = [
                                                                '', //C
                                                                '', //M
                                                                '', //A
                                                                '', //G
                                                                '', //H
                                                                '' //L
                                                            ];
                                                            break;
                                                    }
                                                } else {
                                                    $dataCuacaInser = [
                                                        '', //C
                                                        '', //M
                                                        '', //A
                                                        '', //G
                                                        '', //H
                                                        '' //L
                                                    ];
                                                }
                                                //upah
                                                if ($i < $countListUpah) {
                                                    $value = $ListUpah[$i]->value;
                                                    $value = json_decode($value);
                                                    $dataUpahInser = [
                                                        $i + 1,
                                                        $ListUpah[$i]->kd_analisa, //No. MP.
                                                        $ListUpah[$i]->kode, //Kode
                                                        $ListUpah[$i]->uraian, //TENAGA KERJA
                                                        $value->jumlah, //JUMLAH
                                                    ];
                                                } elseif ($i <= ($maxRowsAlat + $maxRowsBahan)) {
                                                    $dataUpahInser = [
                                                        '',
                                                        '', //No. MP.
                                                        '', //Kode
                                                        '', //TENAGA KERJA
                                                        '', //JUMLAH
                                                    ];
                                                }
                                                //peralatan
                                                if ($i < $countListPeralatan) {
                                                    $value = $ListPeralatan[$i]->value;
                                                    $value = json_decode($value);
                                                    $dataPeralatanInser = [
                                                        $ListPeralatan[$i]->kd_analisa, //No. MP.
                                                        $ListPeralatan[$i]->kode, //Kode
                                                        $ListPeralatan[$i]->uraian, //ALAT YANG DIGUNAKAN
                                                        $value->satuan, //UNIT/SAT
                                                        $value->merk_type, //TYPE/MERK
                                                        $value->diterima, //TERIMA
                                                        $value->ditolak, //TOLAK
                                                        $ListPeralatan[$i]->keterangan, //ALASAN
                                                    ];
                                                } elseif ($i <= $maxRowsAlat) {
                                                    $dataPeralatanInser = [
                                                        '', //No. MP.
                                                        '', //Kode
                                                        '', //ALAT YANG DIGUNAKAN
                                                        '', //UNIT/SAT
                                                        '', //TYPE/MERK
                                                        '', //TERIMA
                                                        '', //TOLAK
                                                        '', //ALASAN
                                                    ];
                                                }
                                                //jika $i=0 cetak di cuaca
                                                if ($i == 0) {
                                                    $rowdata = array(
                                                        'JAM', //JAM
                                                        'C', //C
                                                        'M', //M
                                                        'A', //A
                                                        'G', //G
                                                        'H', //H
                                                        'L' //L
                                                    );
                                                    $rowdata = array_merge($dataUpahInser, $dataPeralatanInser, $rowdata);
                                                    $style = array($LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed);
                                                } else if ($i == $maxRowsAlat) {
                                                    $rowdata = array(
                                                        'No. MP.', //No. MP.
                                                        'Kode', //Kode
                                                        'BAHAN', //ALAT YANG DIGUNAKAN
                                                        'SAT.', //UNIT/SAT
                                                        'TYPE/MERK', //TYPE/MERK
                                                        'TERIMA', //TERIMA
                                                        'TOLAK', //TOLAK
                                                        'ALASAN', //ALASAN
                                                        (($iJam - 1) <= 0) ? "=0" : ($iJam - 1), //JAM

                                                    );
                                                    $rowdata = array_merge($dataUpahInser, $rowdata, $dataCuacaInser);
                                                    $style = array($LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt);
                                                } elseif ($i == ($maxRowsAlat + $maxRowsPek)) {
                                                    $rowdata = array(
                                                        'No. MP.',
                                                        'PEKERJAAN YANG DILAKSANAKAN', //No. MP.
                                                        '', //Kode
                                                        '', //TENAGA KERJA
                                                        '', //JUMLAH
                                                        '', //No. MP.
                                                        '', //Kode
                                                        'VOLUME', //ALAT YANG DIGUNAKAN
                                                        'SAT.', //UNIT/SAT
                                                        'CATATAN', //TYPE/MERK
                                                        '', //TERIMA
                                                        '', //TOLAK
                                                        '', //ALASAN
                                                        (($iJam - 1) <= 0) ? "=0" : ($iJam - 1), //JAM
                                                    );
                                                    $rowdata = array_merge($rowdata, $dataCuacaInser);
                                                    $style = array($LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt);
                                                } else if ($i == ($maxRowsAlat + $maxRowsPek + 1)) {
                                                    $rowdata = array(
                                                        '',
                                                        '', //No. MP.
                                                        '', //Kode
                                                        '', //TENAGA KERJA
                                                        '', //JUMLAH
                                                        '', //No. MP.
                                                        '', //Kode
                                                        '', //ALAT YANG DIGUNAKAN
                                                        '', //UNIT/SAT
                                                        'URAIAN', //TYPE/MERK
                                                        'KETERANGAN', //TERIMA
                                                        '', //TOLAK
                                                        '', //ALASAN
                                                        (($iJam - 1) <= 0) ? "=0" : ($iJam - 1), //JAM
                                                    );
                                                    $rowdata = array_merge($rowdata, $dataCuacaInser);
                                                    $style = array($LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_hcvcwrap_b_fillRed, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt);
                                                } else if ($i > ($maxRowsAlat) && $i < ($batasInputBahanPeralatan)) {
                                                    //Bahan
                                                    if ($iBahan < $countListBahan) {
                                                        $value = $ListBahan[$iBahan]->value;
                                                        $value = json_decode($value);
                                                        $dataBahanInser = [
                                                            $ListBahan[$iBahan]->kd_analisa, //No. MP.
                                                            $ListBahan[$iBahan]->kode, //Kode
                                                            $ListBahan[$iBahan]->uraian, //ALAT YANG DIGUNAKAN
                                                            $value->satuan, //UNIT/SAT
                                                            '', //TYPE/MERK $value->merk_type
                                                            $value->diterima, //TERIMA
                                                            $value->ditolak, //TOLAK
                                                            $ListBahan[$iBahan]->keterangan, //ALASAN
                                                        ];
                                                    } else {
                                                        $dataBahanInser = [
                                                            '', //No. MP.
                                                            '', //Kode
                                                            '', //ALAT YANG DIGUNAKAN
                                                            '', //UNIT/SAT
                                                            '', //TYPE/MERK
                                                            '', //TERIMA
                                                            '', //TOLAK
                                                            '', //ALASAN
                                                        ];
                                                    }
                                                    $rowdata = array(
                                                        (($iJam - 1) <= 0) ? "=0" : ($iJam - 1), //JAM
                                                    );
                                                    $rowdata = array_merge($dataUpahInser, $dataBahanInser, $rowdata, $dataCuacaInser);
                                                    $style = $LTRB_vt;
                                                    $iBahan++;
                                                } else if ($i > $batasInputBahanPeralatan) {
                                                    // cetak pekerjaan
                                                    if ($iPeke < $countListPek) {
                                                        $id_rab = $listPek[$iPeke]->id_rab;
                                                        $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_rab, 'AND']];
                                                        $rowRAB = $DB->getWhereCustom('rencana_anggaran_biaya', $condition);
                                                        $jumlahArray = is_array($rowRAB) ? count($rowRAB) : 0;
                                                        $NoMP = '-';
                                                        if ($jumlahArray) {
                                                            $NoMP = $rowRAB[0]->kd_analisa;
                                                        }
                                                        $dataPekInser = [
                                                            $NoMP, //No.
                                                            $listPek[$iPeke]->uraian, //No. MP.
                                                            '', //Kode
                                                            '', //TENAGA KERJA
                                                            '', //JUMLAH
                                                            '', //No. MP.
                                                            '', //Kode
                                                            $listPek[$iPeke]->realisasi_fisik, //ALASAN
                                                            $listPek[$iPeke]->satuan, //unit
                                                        ];
                                                    } else {
                                                        $dataPekInser = [
                                                            '',
                                                            '', //No. MP.
                                                            '', //Kode
                                                            '', //TENAGA KERJA
                                                            '', //JUMLAH
                                                            '', //No. MP.
                                                            '', //Kode
                                                            '', //ALAT YANG DIGUNAKAN
                                                            '', //UNIT/SAT
                                                        ];
                                                    }
                                                    // cetak note
                                                    if ($iPeke < $countListNote) {
                                                        $dataNoteInser = [
                                                            $ListNote[$iPeke]->uraian, //$listPek[$iPeke]->kd_analisa ambil di rab
                                                            $ListNote[$iPeke]->keterangan,
                                                            '',
                                                            '',
                                                        ];
                                                    } else {
                                                        $dataNoteInser = [
                                                            '', //TYPE/MERK
                                                            '', //TERIMA
                                                            '', //TOLAK
                                                            '', //ALASAN
                                                        ];
                                                    }
                                                    $rowdata = array(
                                                        (($iJam - 1) <= 0) ? "=0" : ($iJam - 1), //JAM
                                                    );
                                                    $rowdata = array_merge($dataPekInser, $dataNoteInser, $rowdata, $dataCuacaInser);
                                                    $style = $LTRB_vt;
                                                    $iPeke++;
                                                } else if ($i < $batasInputBahanPeralatan) {
                                                    $rowdata = array(
                                                        (($iJam - 1) <= 0) ? "=0" : ($iJam - 1), //JAM
                                                    );
                                                    $rowdata = array_merge($dataUpahInser, $dataPeralatanInser, $rowdata, $dataCuacaInser);
                                                    $style = $LTRB_vt;
                                                }

                                                //$rowdata = array_merge($rowdata,$a2)
                                                $writer->writeSheetRow('lap harian', $rowdata, $style);
                                                if ($i == ($maxRowsAlat + $maxRowsPek + 1)) {
                                                    $batasRow = $rowsLapHarian;
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 0, $end_row = $batasRow + 1, $end_col = 0);
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 1, $end_row = $batasRow + 1, $end_col = 6);
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 7, $end_row = $batasRow + 1, $end_col = 7);
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 8, $end_row = $batasRow + 1, $end_col = 8);
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 9, $end_row = $batasRow, $end_col = 12);
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow + 1, $start_col = 10, $end_row = $batasRow + 1, $end_col = 12);
                                                }
                                                if ($i > $batasInputBahanPeralatan) {
                                                    $batasRow = $rowsLapHarian;
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 1, $end_row = $batasRow, $end_col = 6);
                                                    $writer->markMergedCell('lap harian', $start_row = $batasRow, $start_col = 10, $end_row = $batasRow, $end_col = 12);
                                                }
                                                $iJam++;
                                                $rowsLapHarian++;
                                            }
                                            $writer->markMergedCell('lap harian', $start_row = $rowsLapHarian, $start_col = 1, $end_row = $rowsLapHarian, $end_col = 6);
                                            $writer->markMergedCell('lap harian', $start_row = $rowsLapHarian, $start_col = 10, $end_row = $rowsLapHarian, $end_col = 12);
                                            //$rowdata = array_merge($rowdata, $a2);
                                            //space
                                            $writer->writeSheetRow('lap harian', ['']);
                                            $writer->writeSheetRow('lap harian', ['']);
                                            $writer->writeSheetRow('lap harian', ['']);
                                            $writer->writeSheetRow('lap harian', ['']);
                                            $writer->writeSheetRow('lap harian', ['']);
                                            $rowsLapHarian += 5;
                                        };
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'rab':
                            # schedule
                            $sumBobot = $cellJumlah - 1;
                            $rowdata = array(
                                '',
                                '',
                                '',
                                'JUMLAH',
                                $sumSheet1,
                                "=SUM(F4:F$sumBobot)",
                                '',
                                '',
                                'RENCANA',
                                ''
                            );
                            $rowdata2 = array(
                                '',
                                '',
                                '',
                                '',
                                '',
                                "",
                                '',
                                '',
                                'RENCANA KUMULATIF',
                                ''
                            );
                            /*
                    ADDRESS(ROW();COLUMN()) //outputs $A$1
                    ADDRESS(ROW();COLUMN();1) //outputs $A$1
                    ADDRESS(ROW();COLUMN();2) //outputs A$1
                    ADDRESS(ROW();COLUMN();3) //outputs $A1
                    ADDRESS(ROW();COLUMN();4) //outputs A1
                    */
                            $style = [$LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt, $LTRB_vt];
                            for ($x = 0; $x < $MPP; $x++) {
                                $styleCell = $LTRB_vt;
                                //$rowdata[] = "=SUM(ADDRESS(ROW()-$jumlahGet_data-2,COLUMN(),4):ADDRESS(ROW()-2,COLUMN(),4))";
                                $rumus = array();
                                for ($y = 1; $y <= $jumlahGet_data; $y++) {
                                    $rumus[] = "INDIRECT(ADDRESS(ROW()-$y,COLUMN(),4))";
                                }
                                //$rowdata[] = "=SUM(INDIRECT(ADDRESS(ROW()-$jumlahGet_data-2,COLUMN(),4)):INDIRECT(ADDRESS(ROW()-2,COLUMN(),4)))";
                                $rowdata2[] = "=INDIRECT(ADDRESS(ROW()-1,COLUMN(),4))+INDIRECT(ADDRESS(ROW(),COLUMN()-1,4))";
                                $rowdata[] = "=" . implode('+', $rumus);
                                //$rowdata[] = "=ADDRESS(ROW(),COLUMN(),4)";
                                //$rowdata[] = "opo";
                                $style[] = $styleCell;
                            }
                            $rowdata[] = '';
                            $style[] = $LTRB_vt;
                            $writer->writeSheetRow($nama_sheet2, $rowdata, $style);
                            $writer->writeSheetRow($nama_sheet2, $rowdata2, $LTRB_vt);
                            # informasi umum
                            $where1 = "kd_proyek = '$kd_proyek'";
                            $order = "ORDER BY no_sortir, id ASC";
                            $query2 = "SELECT * FROM informasi_umum WHERE $where1 $order";
                            $get_data2 = $DB->runQuery2("$query2");
                            // cetak baris informasi
                            //$writer->writeSheetRow($nama_sheet3, ['dataPerekaman',json_encode($get_data2),sizeof($get_data2),$query2], $LTRB);
                            $jumlahArray = is_array($get_data2) ? count($get_data2) : 0;
                            if ($jumlahArray > 0) {
                                // data perekaman
                                foreach ($get_data2 as $baris) {
                                    $rowdata = array(
                                        $baris['nomor_uraian'],
                                        $baris['uraian'],
                                        $baris['kode'],
                                        $baris['nilai'],
                                        $baris['satuan'],
                                        $baris['keterangan']
                                    );
                                    $uraian = strtolower($baris['uraian']);
                                    $nomor = $baris['nomor_uraian'];
                                    $style = $LTRB_vt;

                                    $writer->writeSheetRow($nama_sheet3, $rowdata, $style);
                                    // $sumSheet3++;
                                }
                            };
                            break;
                        default:
                            # code...
                            break;
                    }
                } else {
                    $writer->writeSheetRow($nama_sheet, $rowdata = array('DATA TIDAK DITEMUKAN', '', '', '', '', '', '', ''), $style_Non_Data);
                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 7);
                    $code = 701;
                    //json_encode($get_data, true), $query
                }
            } else {
                $writer->writeSheetRow($nama_sheet, $rowdata = array('DATA QUERY TIDAK DITEMUKAN', '', '', '', '', '', '', ''), $style_Non_Data);
                $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 7);
                $code = 701;
            }
        } else {
            $code = 39;
        }
        //$writer->writeToStdOut();
        //CETAK FILE
        //$writer->writeToFile($folder . $filename);
        // var_dump($folder . $filename);
        //var_dump($folder);
        if (!$writer->writeToFile($folder . $filename)) { //if (!$writer->writeToFile($folder . $filename)) {
            $code = 705;
        } else {
            $code = 707;
        }
        //$writer->writeToFile($folder . $filename);
        //$writer->writeToStdOut();
        $data['filename'] = $folderTemp . $filename;
        //$writer->writeToFile('output.xlsx');
        //$writer->writeToStdOut();
        //$writer->writeToFile('example.xlsx');
        //$writer->writeToString();
        $item = array('code' => $code, 'message' => $hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        // echo json_encode($json);

        return json_encode($json);
        exit(0);
    }
}
