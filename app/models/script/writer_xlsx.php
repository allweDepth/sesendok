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

        $type_user = $_SESSION["user"]["type_user"];
        $username = $_SESSION["user"]["username"];
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

        $id_user = $_SESSION["user"]["id"];
        $userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user], ['username', '=', $username, 'AND']]);
        $jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
        if ($jumlahArray > 0) {
            foreach ($userAktif[0] as $key => $value) {
                ${$key} = $value;
            }
            $id_user = $id;
            $kd_opd = $kd_organisasi;
        } else {
            $id_user = 0;
            $code = 407;
        }


        $filename = 'nabiilaInayah.xlsx';



        $writer = new XLSXWriter();

        // hanya text bold tengah
        $LTRB = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true');
        $LTRB_20 = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'height' => 20);
        $LTRB_fill = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#C9C9C9');
        $LTRB_vc_fillRed_b_20 = array('border' => 'left,right,top,bottom', 'color' => '#ffffff', 'border-style' => 'thin', 'wrap_text' => 'true', 'fill' => '#ee3939', 'font-style' => 'bold', 'height' => 20, 'valign' => 'center'); //'#8e2f2f'
        //cetak kotak dan tulisan ditengah tanpa style
        $LTRB_hc = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'wrap_text' => 'true');
        $LTRB_vt = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'valign' => 'top', 'wrap_text' => 'true');
        //
        $style_Non_Data = array('border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#ffc', 'wrap_text' => 'true', 'height' => 25, 'font-size' => 16);
        //wrap text and all center warna kuning
        $LTRB_hcvcwrap_b_fill = array('wrap_text' => 'true', 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#ffc');
        $data = [];
        $where1 = "";
        $kolom = '*';
        $nama_sheet = 'Inayah';

        if ($validate->passed()) {
            $rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
            $tahun = (int) $rowUsername->tahun;
            $kd_wilayah = $rowUsername->kd_wilayah;
            $kd_skpd = $rowUsername->kd_organisasi;
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
                $tahun_pengaturan = $rowTahunAktif->tahun;
            } else {
                $id_peraturan = 0;
            }
            $sukses = true;
            //var_dump('Content');
            $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
            $query = '';
            switch ($tbl) {
                case 'sub_keg_dpa':
                case 'sub_keg_renja':
                    $filename = 'sub keg renja.xlsx';
                    $nama_sheet = 'sub keg';
                    switch ($jenis) {
                        case 'dok':
                            $order = "ORDER BY kd_sub_keg ASC";
                            $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                            $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0];
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    $headerSet = array(
                        'SUB KEGIATAN' => 'string', //
                        '2' => 'string', //
                        '3' => 'string', //
                        '4' => 'string', //
                        '5' => '#,##0.####0',
                        '6' => '#,##0.####0',//'D/MM/YYYY',
                        '7' => 'string'
                    );
                    $row_header = ['KODE', 'URAIAN', 'TOLAK UKUR CAPAIAN KEG', 'INDIKATOR', 'JUMLAH', 'JUMLAH PERUBAHAN', 'KETERANGAN'];
                    $jmlKolom = 7;
                    break;
                case 'sub_keg':
                    $filename = 'sub kegiatan.xlsx';
                    $nama_sheet = 'sub_keg';
                    switch ($jenis) {
                        case 'dok':
                            $where1 = "disable = ? AND id > ?";
                            $order = "ORDER BY kode ASC";
                            $data_where1 =  [0, 0];
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    $headerSet = array(
                        'DAFTAR SUB KEGIATAN' => 'string', //
                        '2' => 'string', //
                        '3' => 'string', //
                        '4' => 'string', //
                        '5' => 'string',
                        '6' => 'string',
                        '7' => 'string'
                    );
                    $row_header = ['KODE', 'NOMENKLATUR URUSAN', 'KINERJA', 'INDIKATOR', 'SATUAN', 'PERATURAN', 'KETERANGAN'];
                    $jmlKolom = 7;
                    break;
                case 'peraturan':
                    $filename = 'peraturan.xlsx';
                    $nama_sheet = 'Peraturan';
                    switch ($jenis) {
                        case 'dok':
                            $where1 = "kd_wilayah = ? AND id > ?";
                            $order = "ORDER BY kode ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            $data_where1 =  [$kd_wilayah, 0];
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
                case 'sumber_dana':
                    $tabel_pakai = 'sumber_dana_neo';
                    $filename = 'sumber_dana.xlsx';
                    $nama_sheet = 'sumberdana';
                    switch ($jenis) {
                        case 'dok':
                            $where1 = "id > ?";
                            $order = "ORDER BY kode ASC";
                            $data_where1 =  [0];
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    //data cetak
                    $headerSet = array(
                        'SUMBER DANA' => '0', //No
                        '2' => 'string', //type_dok
                        '3' => 'string', //judul
                        '4' => 'string', //nomor
                        '5' => 'string', //bentuk
                        '6' => 'string', //bentuk_singkat
                        '7' => 'string', //t4_penetapan
                        '8' => 'string', //status
                        '9' => 'string', //disable
                    );
                    $row_header = ['NO.', 'SUMBER DANA', 'KELOMPOK', 'JENIS', 'OBJEK', 'RICIAN OBJEK', 'SUB RICIAN OBJEK', 'URAIAN', 'KETERANGAN'];
                    $jmlKolom = 9;

                    break;
                case 'akun_belanja':
                    $tabel_pakai = 'akun_neo';
                    $filename = 'akun.xlsx';
                    $nama_sheet = 'akun';
                    switch ($jenis) {
                        case 'dok':
                            $where1 = "id > ?";
                            $data_where1 =  [0];
                            $order = "ORDER BY akun ASC, kelompok ASC, jenis ASC, objek ASC, rincian_objek ASC, sub_rincian_objek ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    //data cetak
                    $headerSet = array(
                        'AKUN BELANJA' => '0', //No
                        '2' => 'string', //type_dok
                        '3' => 'string', //judul
                        '4' => 'string', //nomor
                        '5' => 'string', //bentuk
                        '6' => 'string', //bentuk_singkat
                        '7' => 'string', //t4_penetapan
                        '8' => 'string', //status
                        '9' => 'string', //disable
                    );
                    $row_header = ['NO.', 'AKUN', 'KELOMPOK', 'JENIS', 'OBJEK', 'RICIAN OBJEK', 'SUB RICIAN OBJEK', 'URAIAN', 'KETERANGAN'];
                    $jmlKolom = 9;

                    break;
                case 'aset':
                    $tabel_pakai = 'aset_neo';
                    $filename = 'aset.xlsx';
                    $nama_sheet = 'kd aset';
                    switch ($jenis) {
                        case 'dok':
                            $where1 = "id > ?";
                            $data_where1 = [0];
                            $order = "ORDER BY akun ASC, kelompok ASC, jenis ASC, objek ASC, rincian_objek ASC, sub_rincian_objek ASC";
                            $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                            break;
                        default:
                            # code...
                            break;
                    }
                    //data cetak
                    $headerSet = array(
                        'AKUN ASET' => '0', //No
                        '2' => 'string', //type_dok
                        '3' => 'string', //judul
                        '4' => 'string', //nomor
                        '5' => 'string', //bentuk
                        '6' => 'string', //bentuk_singkat
                        '7' => 'string', //t4_penetapan
                        '8' => 'string', //status
                        '9' => 'string', //disable
                    );
                    $row_header = ['NO.', 'AKUN', 'KELOMPOK', 'JENIS', 'OBJEK', 'RICIAN OBJEK', 'SUB RICIAN OBJEK', 'URAIAN', 'KETERANGAN'];
                    $jmlKolom = 9;

                    break;
                case 'rekanan':
                    $tabel_pakai =  'rekanan';
                    $filename = 'rekanan.xlsx';
                    $nama_sheet = 'rekanan';
                    $where1 = "id > ? AND kd_wilayah = ?";
                    $data_where1 = [0, $kd_wilayah];
                    $order = "ORDER BY nama_perusahaan ASC";
                    $query = "SELECT $kolom FROM $tabel_pakai WHERE $where1 $order";
                    break;
                default:
                    $filename = 'nabiila.xlsx';
                    break;
            }
            //ambil quary
            if (strlen($where1) > 0) {
                $get_data = $DB->runQuery($query, $data_where1)->fetchAll(PDO::FETCH_ASSOC);
                // var_dump($get_data);
                // var_dump($get_data[0]);
                //$code = 100;
                //$hasilServer['100'] = $get_data;
                $jumlahArray = is_array($get_data) ? count($get_data) : 0;
                if ($jumlahArray > 0) {

                    #=============================
                    #==== HEADER KOP TABEL =======
                    #=============================
                    switch ($tbl) {
                        case 'sub_keg_dpa':
                        case 'sub_keg_renja':
                            switch ($jenis) {
                                case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                    $writer->writeSheetHeader($nama_sheet, $headerSet, $col_options = array('widths' => [25, 60, 40, 40, 25, 25, 40], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 4, 'freeze_columns' => 1, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $jmlKolom - 1);
                                    $writer->writeSheetRow($nama_sheet, $rowdata = array('OPD', ': ' . "($kd_opd) $nama_org"), ['font-style' => 'bold', 'font-size' => 12]);

                                    for ($x = 1; $x <= $jmlKolom; ++$x) {
                                        $colHeader[] = '="(' . $x . ')"';
                                    }
                                    $writer->writeSheetRow($nama_sheet, $row_header, ['height' => 40, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#d76e6e', 'wrap_text' => true, 'freeze_rows' => 1]);
                                    $writer->writeSheetRow($nama_sheet, $colHeader, $LTRB_hc);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'sub_keg':
                            switch ($jenis) {
                                case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                    $writer->writeSheetHeader($nama_sheet, $headerSet, $col_options = array('widths' => [25, 60, 40, 40, 15, 20, 40], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 4, 'freeze_columns' => 1, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $jmlKolom - 1);
                                    $writer->writeSheetRow($nama_sheet, $rowdata = array('OPD', '', ': ' . 'SKPD'), ['font-style' => 'bold', 'font-size' => 12]);

                                    for ($x = 1; $x <= $jmlKolom; ++$x) {
                                        $colHeader[] = '="(' . $x . ')"';
                                    }
                                    $writer->writeSheetRow($nama_sheet, $row_header, ['height' => 40, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#d76e6e', 'wrap_text' => true, 'freeze_rows' => 1]);
                                    $writer->writeSheetRow($nama_sheet, $colHeader, $LTRB_hc);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'peraturan':
                            switch ($jenis) {
                                case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                    $writer->writeSheetHeader($nama_sheet, $rowdata = array(
                                        'DAFTAR PERATURAN' => '0', //No
                                        '2' => 'string', //type_dok
                                        '3' => 'string', //judul
                                        '4' => 'string', //nomor
                                        '5' => 'string', //bentuk
                                        '6' => 'string', //bentuk_singkat
                                        '7' => 'string', //t4_penetapan
                                        '8' => 'D/MM/YYYY', //tgl_penetapan
                                        '9' => 'D/MM/YYYY', //tgl_pengundangan
                                        '10' => 'string', //status
                                        '11' => 'string', //disable
                                        '12' => 'string' //keterangan
                                    ), $col_options = array('widths' => [10, 30, 50, 40, 40, 40, 30, 20, 20, 20, 20, 40], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 4, 'freeze_columns' => 2, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 11);
                                    $writer->writeSheetRow($nama_sheet, $rowdata = array('OPD', '', ': ' . 'SKPD'), ['font-style' => 'bold', 'font-size' => 12]);

                                    $row_tabel = ['NO.', 'TYPE DOK', 'JUDUL', 'NOMOR', 'BENTUK', 'BENTUK SINGKAT', 'LOKASI PENETAPAN', '="TANGGAL PENETAPAN"', '="TANGGAL PENGUNDANGAN"', 'STATUS', 'DISABLE', 'KETERANGAN'];
                                    for ($x = 1; $x <= 12; ++$x) {
                                        $colHeader[] = '="(' . $x . ')"';
                                    }
                                    $writer->writeSheetRow($nama_sheet, $row_tabel, ['height' => 40, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#d76e6e', 'wrap_text' => true, 'freeze_rows' => 1]);
                                    $writer->writeSheetRow($nama_sheet, $colHeader, $LTRB_hc);
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'akun_belanja':
                        case 'sumber_dana':
                        case 'aset':
                            switch ($jenis) {
                                case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                    $writer->writeSheetHeader($nama_sheet, $headerSet, $col_options = array('widths' => [10, 10, 10, 10, 10, 10, 10, 80, 70], 'color' => '#323232', 'collapsed' => true, 'freeze_rows' => 4, 'freeze_columns' => 1, 'height' => 40, 'font-style' => 'bold', 'font-size' => 16, 'halign' => 'center', 'valign' => 'center'));
                                    $writer->markMergedCell($nama_sheet, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 11);
                                    $writer->writeSheetRow($nama_sheet, $rowdata = array('OPD', '', ': ' . 'SKPD'), ['font-style' => 'bold', 'font-size' => 12]);

                                    for ($x = 1; $x <= $jmlKolom; ++$x) {
                                        $colHeader[] = '="(' . $x . ')"';
                                    }
                                    $writer->writeSheetRow($nama_sheet, $row_header, ['height' => 40, 'border' => 'left,right,top,bottom', 'border-style' => 'thin', 'halign' => 'center', 'valign' => 'center', 'font-style' => 'bold', 'fill' => '#d76e6e', 'wrap_text' => true, 'freeze_rows' => 1]);
                                    $writer->writeSheetRow($nama_sheet, $colHeader, $LTRB_hc);
                                    break;
                                default:
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
                        switch ($tbl) {
                            case 'sub_keg_dpa':
                            case 'sub_keg_renja':
                                switch ($jenis) {
                                    case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                        $rowdata = array(
                                            $row['kd_sub_keg'],
                                            $row['uraian'],
                                            $row['tolak_ukur_keluaran'],
                                            $row['target_kinerja_keluaran'],
                                            $row['jumlah_pagu'],
                                            $row['jumlah_pagu_p'],
                                            $row['keterangan']
                                        );

                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'sub_keg':
                                switch ($jenis) {
                                    case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                        $rowdata = array(
                                            $row['kode'],
                                            $row['nomenklatur_urusan'],
                                            $row['kinerja'],
                                            $row['indikator'],
                                            $row['satuan'],
                                            $row['peraturan'],
                                            $row['keterangan']
                                        );

                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'peraturan':
                                switch ($jenis) {
                                    case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                        $rowdata = array(
                                            $myrow,
                                            $row['type_dok'],
                                            $row['judul'],
                                            $row['nomor'],
                                            $row['bentuk'],
                                            $row['bentuk_singkat'],
                                            $row['t4_penetapan'],
                                            $row['tgl_penetapan'],
                                            $row['tgl_pengundangan'],
                                            $row['status'],
                                            $row['disable'],
                                            $row['keterangan']
                                        );
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                        break;
                                    default:
                                        break;
                                }
                                break;
                            case 'sumber_dana':
                            case 'akun_belanja':
                                switch ($jenis) {
                                    case 'dok': //mengambil seluruh data harga satuan sesuai proyek
                                        switch ($tbl) {
                                            case 'sumber_dana':
                                                $rowdata = array(
                                                    $myrow,
                                                    $row['sumber_dana'],
                                                    $row['kelompok'],
                                                    $row['jenis'],
                                                    $row['objek'],
                                                    $row['rincian_objek'],
                                                    $row['sub_rincian_objek'],
                                                    $row['uraian'],
                                                    $row['keterangan']
                                                );
                                                break;
                                            case 'aset':
                                            case 'akun_belanja':
                                                $rowdata = array(
                                                    $myrow,
                                                    $row['akun'],
                                                    $row['kelompok'],
                                                    $row['jenis'],
                                                    $row['objek'],
                                                    $row['rincian_objek'],
                                                    $row['sub_rincian_objek'],
                                                    $row['uraian'],
                                                    $row['keterangan']
                                                );
                                                break;
                                            default:
                                                #code...
                                                break;
                                        };

                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                        break;
                                    default:
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
                                        $writer->writeSheetRow($nama_sheet, $rowdata, $LTRB_vt);
                                        break;
                                    default:
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
            $writer->writeSheetRow($nama_sheet, $rowdata = array('DATA YANG DIMINTA TIDAK SIKON', '', '', '', '', '', '', ''), $style_Non_Data);
            $code = 39;
        }
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $writer->setAuthor('Nabiila Inayah');
        $writer->setCompany('Hamba Allah');
        $writer->writeToStdOut();
        exit(0);

        /* jika menggunakan json ajax
        if (!$writer->writeToFile($folder . $filename)) { //if (!$writer->writeToFile($folder . $filename)) {
            $code = 705;
        } else {
            $code = 707;
        }
        $data['filename'] = $folderTemp . $filename;
        $item = array('code' => $code, 'message' => $hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json);
        exit(0);
        */
    }
}
