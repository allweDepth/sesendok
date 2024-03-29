<?php
class print_pdf
{
    private $_jenis = '';
    private $_tbl = '';
    private $_tabel_pakai = '';
    private $_data = array();
    private function __construct($data)
    {
        $this->_data = $data;
        if (isset($this->_data['jenis'])) {
            $this->_jenis = trim($this->_data['jenis']);
        }
        if (isset($this->_data['tbl'])) {
            $this->_tbl = trim($this->_data['tbl']);
            require 'init.php';
            $Fungsi = new MasterFungsi();
            $this->_tabel_pakai = $Fungsi->tabel_pakai($this->_tbl)['tabel_pakai'];
        }
        if (array_key_exists('cry', $this->_data)) {
            require 'init.php';
            $query = new Query($data);
            foreach ($this->_data as $key => $rowValue) {
                switch ($key) {
                    case 'tbl':
                        $this->_tbl = $rowValue;
                        break;
                    case 'jenis':
                        $this->_jenis = $rowValue;
                        break;
                    default:
                        $formValue = $query->encrypt($rowValue);
                        $this->_data[$key] = $formValue;
                        break;
                }
            }
        }
    }
    public static function createInstance($data)
    {
        return new self($data);
    }
    public static function print_pdf()
    {
        // var_dump($_POST);
        require 'init.php';
        $user = new User();
        $Fungsi = new MasterFungsi();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $username = $_SESSION["user"]["username"];
        $id_user = $_SESSION["user"]["id"];
        $keyEncrypt = $_SESSION["user"]["key_encrypt"];
        $kd_opd = $_SESSION["user"]["kd_organisasi"];
        //var_dump($keyEncrypt);
        //var_dump(sys_get_temp_dir());lokasi tempoerer
        $DB = DB::getInstance();
        $message_tambah = '';
        $sukses = false;
        $code = 39;
        $pesan = 'posting kosong';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        $dataJson = array();
        //ambil row user
        $rowUsername = $DB->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
        // var_dump($_SESSION["user"]);
        $dataJson['results'] = [];
        $rowPengaturan = false;
        $tambahan_pesan = [];
        if ($rowUsername !== false) {
            foreach ($rowUsername as $key => $value) {
                ${$key} = $value;
            }
            $tahun = (int) $rowUsername->tahun;
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
        if (!empty($_POST) && $id_user > 0 && $code != 407) {
            if (isset($_POST['jenis']) && isset($_POST['tbl'])) {
                $code = 40;
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
                $Fungsi = new MasterFungsi();
                //================
                //PROSES VALIDASI
                //================
                require_once('class/TCPDF-main/tcpdf.php');
                switch ($jenis) {
                    case 'cetak':

                        $page_size = $validate->setRules('ukuran_kertas', 'ukuran kertas', [
                            'required' => true,
                            // 'sanitize' => 'string',
                            'min_char' => 1,
                            'max_char' => 100,
                            'in_array' => ['F4', 'custom', 'A3', 'A4', 'LETTER', 'LEGAL', 'f4', 'CUSTOM', 'a3', 'a4', 'letter', 'legal']
                        ]);
                        $PDF_MARGIN_LEFT = $validate->setRules('margin_kanan', 'margin kanan', [
                            'numeric' => true,
                            'required' => true
                        ]);
                        $PDF_MARGIN_RIGHT = $validate->setRules('margin_kiri', 'margin kiri', [
                            'numeric' => true,
                            'required' => true
                        ]);
                        $PDF_MARGIN_TOP = $validate->setRules('margin_top', 'margin atas', [
                            'numeric' => true,
                            'required' => true
                        ]);
                        $PDF_MARGIN_BOTTOM = $validate->setRules('margin_bottom', 'margin bawah', [
                            'numeric' => true,
                            'required' => true
                        ]);
                        $orientasi = $validate->setRules('orientasi', 'orientasi', [
                            'sanitize' => 'string',
                            'required' => true,
                            'in_array' => ['portrait', 'lanscape'],
                        ]);
                        // create a PDF object
                        $pdf = new TCPDF($orientasi, 'mm', $page_size);
                        $header = $validate->setRules('header', 'header', [
                            'sanitize' => 'string',
                            'numeric' => true,
                            'in_array' => ['off', 'on']
                        ]);

                        if ($header === 'on') {
                            $PDF_MARGIN_HEADER = $validate->setRules('margin_header', 'margin header', [
                                'numeric' => true,
                                'required' => true
                            ]);
                        } else {
                            $PDF_MARGIN_HEADER = 20;
                            $pdf->setPrintHeader(false);
                        }
                        $footer = $validate->setRules('footer', 'footer', [
                            'sanitize' => 'string',
                            'numeric' => true,
                            'in_array' => ['off', 'on']
                        ]);
                        if ($footer === 'on') {
                            $PDF_MARGIN_FOOTER = $validate->setRules('margin_footer', 'margin header', [
                                'numeric' => true,
                                'required' => true
                            ]);
                        } else {
                            $pdf->setPrintFooter(false);
                            $PDF_MARGIN_FOOTER = 20;
                        }
                        if ($page_size == 'CUSTOM') {
                            //$lebar = ( float )$_POST[ 'tinggi' ]; // lebar kertas
                            $tinggi = $validate->setRules('tinggi', 'tinggi custom page size', [
                                'required' => true,
                                'numeric' => true
                            ]);
                            $lebar = $validate->setRules('lebar', 'lebar custom page size', [
                                'required' => true,
                                'numeric' => true
                            ]);
                            $t_awal = (float)$tinggi * 2.8346456693;
                            $l_awal = (float)$lebar * 2.8346456693;
                            if ($t_awal <= 0) {
                                $t_awal = 330 * 2.8346456693;
                            }
                            if ($l_awal <= 0) {
                                $l_awal = 215 * 2.8346456693;
                            }
                            $page_size = array($l_awal, $t_awal);
                            $pf = $page_size;
                            $page_size = array((float)$_POST['lebar'], (float)$_POST['tinggi']);
                        } else {
                            $pf = TCPDF_STATIC::getPageSizeFromFormat($page_size);
                        }
                        if ($orientasi == 'portrait') {
                            $lebar = $pf[0]; // lebar kertas
                        } else {
                            $lebar = $pf[1]; // tinggi kertas
                        }
                        // var_dump($lebar);
                        $lebar_net = $lebar / 2.8346456693 - floatval($PDF_MARGIN_LEFT) - floatval($PDF_MARGIN_RIGHT);
                        switch ($tbl) {
                            case 'sk_asn':
                                $tabel_pakai_temporer = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                $id_row = $validate->setRules('id_row', 'pilih surat keputusan', [
                                    'numeric' => true,
                                    'required' => true
                                ]);
                                $id_row = $validate->setRules('id_row', 'pilih surat keputusan', [
                                    'inDB' => [$tabel_pakai_temporer, 'id', [['id', "=", $id_row], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND']]]
                                ]);
                                break;
                            default:
                                break;
                        }
                        break;
                    default:
                        # code...
                        break;
                }
                if ($validate->passed()) {
                    // set margins
                    // set document information
                    // $pdf->SetCreator('PDF_CREATOR');
                    // $pdf->SetAuthor('Nicola Asuni');
                    // $pdf->SetTitle('TCPDF Example 007');
                    // $pdf->SetSubject('TCPDF Tutorial');
                    // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
                    $pdf->SetMargins($PDF_MARGIN_LEFT, $PDF_MARGIN_TOP, $PDF_MARGIN_RIGHT);
                    $pdf->SetHeaderMargin($PDF_MARGIN_HEADER);
                    $pdf->SetFooterMargin($PDF_MARGIN_FOOTER);
                    // set auto page breaks
                    $pdf->SetAutoPageBreak(true, $PDF_MARGIN_BOTTOM);
                    // kop surat
                    //dd content
                    $html = '<style>h1{ color: navy; font-family: times; font-size: 24pt; text-decoration: underline}p.first{ color: #003300; font-family: helvetica; font-size: 12pt}p.first span{ color: #006600; font-style: italic}p#second{ color: rgb(00,63,127); font-family: times; font-size: 12pt; text-align: justify}p#second >span{ background-color: #FFFFAA}table.first{ color: #003300; font-family: helvetica; font-size: 8pt; border-left: 3px solid red; border-right: 3px solid #FF00FF; border-top: 3px solid green; border-bottom: 3px solid blue; background-color: #ccffcc; vertical-align: middle}th{ border: 0.5px solid black; font-family: helvetica}.thback{ border: 0.5px solid black; background-color: grey; font-family: helvetica}td{ border: 0.5px solid black; height: 20px; line-height:10px}.tdback{ border: 0.5px solid grey; background-color: #ffffee; height: 20px; line-height:10px}td.second{ border: 2px dashed green}div.test{ color: #CC0000; background-color: #FFFF66; font-family: helvetica; font-size: 10pt; border-style: solid solid solid solid; border-width: 2px 2px 2px 2px; border-color: green #FF00FF blue red; text-align: center}.lowercase{ text-transform: lowercase}.uppercase{ text-transform: uppercase}.capitalize{ text-transform: capitalize}.w-2{width:' . (0.2 / 10 * $lebar_net * 2.8346456693) . '}.w-5{width:' . (0.5 / 10 * $lebar_net * 2.8346456693) . '}.w-7{width:' . (0.75 / 10 * $lebar_net * 2.8346456693) . '}.w-10{width:' . (1 / 10 * $lebar_net * 2.8346456693) . '}.w-15{width:' . (1.5 / 10 * $lebar_net * 2.8346456693) . '}.w-20{width:' . (2 / 10 * $lebar_net * 2.8346456693) . '}.w-25{width:' . (2.5 / 10 * $lebar_net * 2.8346456693) . '}.w-30{width:' . (3 / 10 * $lebar_net * 2.8346456693) . '}.w-35{width:' . (3.5 / 10 * $lebar_net * 2.8346456693) . '}.w-40{width:' . (4 / 10 * $lebar_net * 2.8346456693) . '}.w-45{width:' . (4.5 / 10 * $lebar_net * 2.8346456693) . '}.w-50{width:' . (5 / 10 * $lebar_net * 2.8346456693) . '}.w-60{width:' . (6 / 10 * $lebar_net * 2.8346456693) . '}.w-65{width:' . (6.5 / 10 * $lebar_net * 2.8346456693) . '}.w-70{width:' . (7 / 10 * $lebar_net * 2.8346456693) . '}.w-75{width:' . (7.5 / 10 * $lebar_net * 2.8346456693) . '}.w-80{width:' . (8 / 10 * $lebar_net * 2.8346456693) . '}.w-85{width:' . (8.5 / 10 * $lebar_net * 2.8346456693) . '}.w-90{width:' . (9 / 10 * $lebar_net * 2.8346456693) . '}.w-95{width:' . (9.5 / 10 * $lebar_net * 2.8346456693) . '}.w-100{width:' . ($lebar_net * 2.8346456693) . '}</style>';
                    $tujuh = 0.75 / 10 * $lebar_net * 2.8346456693;
                    $dua = 0.2 / 10 * $lebar_net * 2.8346456693;
                    $tiga = 0.3 / 10 * $lebar_net * 2.8346456693;
                    $empat = 0.4 / 10 * $lebar_net * 2.8346456693;
                    $lima = 0.5 / 10 * $lebar_net * 2.8346456693;
                    $tujuh = 0.7 / 10 * $lebar_net * 2.8346456693;
                    $sepuluh = 1 / 10 * $lebar_net * 2.8346456693;
                    $lima_belas = 1.5 / 10 * $lebar_net * 2.8346456693;
                    $dua_puluh = 2 / 10 * $lebar_net * 2.8346456693;
                    $dua_lima = 2.5 / 10 * $lebar_net * 2.8346456693;
                    $tiga_puluh = 3 / 10 * $lebar_net * 2.8346456693;
                    $tiga_lima = 3.5 / 10 * $lebar_net * 2.8346456693;
                    $empat_puluh = 4 / 10 * $lebar_net * 2.8346456693;
                    $empat_lima = 4.5 / 10 * $lebar_net * 2.8346456693;
                    $lima_puluh = 5 / 10 * $lebar_net * 2.8346456693;
                    $lima_lima = 5.5 / 10 * $lebar_net * 2.8346456693;
                    $enam_puluh = 6 / 10 * $lebar_net * 2.8346456693;
                    $enam_lima = 6.5 / 10 * $lebar_net * 2.8346456693;
                    $tujuh_puluh = 7 / 10 * $lebar_net * 2.8346456693;
                    $tujuh_lima = 7.5 / 10 * $lebar_net * 2.8346456693;
                    $delapan_puluh = 8 / 10 * $lebar_net * 2.8346456693;
                    $delapan_lima = 8.5 / 10 * $lebar_net * 2.8346456693;
                    $sembilan_puluh = 9 / 10 * $lebar_net * 2.8346456693;
                    $sembilan_lima = 9.5 / 10 * $lebar_net * 2.8346456693;
                    $seratus = $lebar_net * 2.8346456693;
                    $style_css = "<style>.w-2{ width: $dua;} .w-3{ width: $dua;} .w-4{ width: $empat;} .w-5{ width: $lima;} .w-7{ width: $tujuh;} .w-10{ width: $sepuluh;} .w-15{ width: $lima_belas;} .w-20{ width: $dua_puluh;} .w-25{ width: $dua_lima;} .w-30{ width: $tiga_puluh;} .w-35{ width: $tiga_lima;} .w-40{ width: $empat_puluh;} .w-45{ width: $empat_lima;} .w-50{ width: $lima_puluh;} .w-60{ width: $enam_puluh;} .w-65{ width: $enam_lima;} .w-70{ width: $tujuh_puluh;} .w-75{ width: $tujuh_lima;} .w-80{ width: $delapan_puluh;} .w-85{ width: $delapan_lima ;} .w-90{ width: $sembilan_puluh;} .w-95{ width: $sembilan_lima;} .w-100{ width: $seratus;}</style>";
                    $style_table = "<style>.border{ border: 1rem solid #000000;} .borderCenter{ border: 1rem solid #000000; text-align: center;} .borderBoldCenter{ border: 1rem solid #000000; text-align: center; font-weight: bold;} .borderJustify{ border: 1rem solid #000000; text-align: justify;} .borderNone{ text-align: justify;} .borderCenter{ border: 1rem solid #000000; text-align: center;} .borderLRJustify{ border-left: 1rem solid #000000; border-right: 1rem solid #000000; text-align: justify;} .borderLJustify{ border-left: 1rem solid #000000; text-align: justify;} .borderRJustify{ border-right: 1rem solid #000000; text-align: justify;} .borderLBJustify{ border-leff: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderRBJustify{ border-right: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderRTJustify{ border-right: 1rem solid #000000; border-top: 1rem solid #000000; text-align: justify;} .borderLTJustify{ border-left: 1rem solid #000000; border-top: 1rem solid #000000; text-align: justify;} .borderTJustify{ border-top: 1rem solid #000000; text-align: justify;} .borderBJustify{ border-bottom: 1rem solid #000000; text-align: justify;} .borderRBJustify{ border-right: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderLRBotJustify{ border-left: 1rem solid #000000; border-right: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderTBLJustify{ border-left: 1rem solid #000000; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderTBRJustify{ border-right: 1rem solid #000000; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderTBJustify{ border-top: 1rem solid #000000; border-bottom: 1rem solid #000000; text-align: justify;} .borderLRTJustify{ border-top: 1rem solid #000000; border-left: 1rem solid #000000; border-right: 1rem solid #000000; text-align: justify;} .bordertbgray{ border-top: 0.5rem solid lightgray; border-bottom: 1rem solid lightgray; text-align: justify;} .bordergray{ border: 0.5rem solid lightgray; text-align: justify;} .kop{ font-family: helvetica; font-size: 12pt; border-left: 1px solid red; border-right: 1px solid #FF00FF; border-top: 1px solid green; border-bottom: 1px solid blue; background-color: #ccffcc; vertical-align: middle;} .kopkiri{ font-family: helvetica; font-size: 12pt; border-left: none; border-right: 1rem solid #000000; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000; background-color: #ccffcc; vertical-align: middle;} .kopmid{ font-family: helvetica; font-size: 12pt; border-left: 1rem solid #000000; border-right: 1rem solid #000000; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000; background-color: #ccffcc; vertical-align: middle;} .kopkanan{ font-family: helvetica; font-size: 12pt; border-left: 1rem solid #000000; border-right: none; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000; background-color: #ccffcc; vertical-align: middle;} .nonekiri{ font-family: helvetica; font-size: 12pt; border-left: none; border-right: 1rem solid #000000; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000;} .nonemid{ font-family: helvetica; font-size: 12pt; border-left: 1rem solid #000000; border-right: 1rem solid #000000; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000;} .nonekanan{ font-family: helvetica; font-size: 12pt; border-left: 1rem solid #000000; border-right: none; border-top: 1rem solid #000000; border-bottom: 1rem solid #000000;}</style>";
                    $code = 55;
                    $pdf->SetFont('helvetica', '', 11 * $font_size);
                    $pdf->setListIndentWidth(5);
                    // set document information
                    $pdf->SetCreator('allwe');
                    $pdf->SetAuthor('Alwi Mansyur');
                    $pdf->SetTitle('dokumen');
                    $pdf->SetSubject('dokumen siSendok');
                    $pdf->SetKeywords('dokumen siSendok');
                    switch ($jenis) {
                        case 'cetak':
                            switch ($tbl) {
                                case 'sk_asn':
                                    $pdf->SetFont('helvetica', '', 11 * $font_size);
                                    $pdf->SetFillColor(255, 255, 255);
                                    $pdf->setListIndentWidth(5);
                                    // create new PDF document
                                    // Add a page
                                    // This method has several options, check the source code documentation for more information.

                                    // ---------------------------------------------------------

                                    // set default font subsetting mode
                                    $pdf->setFontSubsetting(true);

                                    // Set font
                                    // dejavusans is a UTF-8 Unicode font, if you only need to
                                    // print standard ASCII chars, you can use core fonts like
                                    // helvetica or times to reduce file size.
                                    $pdf->SetFont('dejavusans', '', 14, '', true);

                                    // Add a page
                                    // This method has several options, check the source code documentation for more information.
                                    $pdf->AddPage();

                                    // set text shadow effect
                                    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

                                    // Set some content to print
                                    $html = '<h1>Welcome to Pasangkayu</h1><p>hubungi administrator </p>';
                                    break;
                                default:
                                    $pdf->SetFillColor(255, 255, 255);
                                    $pdf->setListIndentWidth(5);
                                    $pdf->setFontSubsetting(true);
                                    $pdf->SetFont('dejavusans', '', 14, '', true);
                                    $pdf->AddPage();
                                    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
                                    $html = '<h1>Welcome to Pasangkayu</h1><p>hubungi administrator </p>';
                                    break;
                            }
                            break;
                        default:
                            $html = '<h1>Welcome to Pasangkayu</h1><p>hubungi administrator </p>';
                            break;
                    }
                    // Print text using writeHTMLCell()
                    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
                    // ---------------------------------------------------------
                    // output PDF dokumen
                    $sukses = true;
                    $data['pdf'] = base64_encode($pdf->Output('dokumenku', 'S'));
                    //============================================================+
                    // END OF FILE
                    //============================================================+
                } else {
                    $code = 29;
                    $pesan = $validate->getError();
                    //var_dump($pesan);
                    $data['error_validate'][] = $pesan;
                    $keterangan = '<ol class="ui horizontal ordered suffixed list">';
                    foreach ($pesan as $key => $value) {
                        $keterangan .= '<li class="item">' . $value . '</li>';
                    }
                    $keterangan .= '</ol>';
                    $tambahan_pesan = [$code => 'Validasi Kembali :<br>' . $keterangan];
                }
            } else {
                $pesan = 'tidak didefinisikan';
                $code = 39;
            }
        } else {
            $code = 407;
        }
        $tambahanNote = (is_array($tambahan_pesan)) ? implode($tambahan_pesan) : $tambahan_pesan;
        $item = array('code' => $code, 'message' => hasilServer[$code] . ", " . $tambahanNote, "note" => $tambahan_pesan);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json, JSON_HEX_APOS);
    }
    public function headerku($pdf, $lebar_net, $status_pemerintah, $nama_pemerintah, $nama_opd, $alamat)
    {
        $lebar_image = 0.12 / 10 * $lebar_net;
        $image_file = '<img src="../images/logo.png" style="display:block;" width="50" height="50"/>';
        $pdf->SetY(5);
        $isi_header = '<table>
                <tbody>
					<tr nobr="true">
						<td rowspan="4" width="12%" style="border-bottom: 1rem double #000000;">' . $image_file . '</td>
                        <td height="10" width="88%" align="center" style="font-family: Arial; font-size: 12px; width:100%;font-weight:bold;">PEMERINTAH ' . strtoupper($status_pemerintah) . ' ' . strtoupper($nama_pemerintah) . '</td>
					</tr>
                    <tr nobr="true">
                        <td height="20" width="88%" align="center"  style="font-family: Arial; font-size: 16px; width:100%;font-weight:bold">' . strtoupper($nama_opd) . '</td>
					</tr>
                    <tr nobr="true">
                        <td height="20" width="88%" align="center"  style="font-family: Arial; font-size: 8px; width:100%;border-bottom: 1rem double #000000;padding: 0px 0px 0px 9px;">' . ucwords($alamat) . '</td>
					</tr>
                </tbody>
            </table>';
        $pdf->writeHTML($isi_header, true, false, false, false, '');
    }

    public function header_customk($pdf, $path_files, $lebar_net_px)
    {
        $image_file = '<img src="../' . $path_files . '" style="display:block;" height="70"/>';
        //$image_file = '<img src="../' . $path_files . '" style="display:block;" width="' . $lebar_net_px . '" height="50"/>';
        $pdf->SetY(10);
        $isi_header = '<table>
                <tbody>
					<tr nobr="true">
						<td align="center" width="100%">' . $image_file . '</td>
                        
					</tr>
                    
                </tbody>
            </table>';
        $pdf->writeHTML($isi_header, true, false, false, false, '');
    }
    //FUNGSI KOP SURAT
    public function kop_suratku($jenis_kop_surat, $lebar_net, $PDF_MARGIN_TOP, $SetY, $status_pemerintah, $nama_pemerintah, $nama_opd, $alamat, $pdf = '')
    {
        require_once('class/TCPDF-main/tcpdf.php');
        require 'init.php';
        $user = new User();
        $Fungsi = new MasterFungsi();
        if ($jenis_kop_surat == 'custom' && !empty($_FILES['file']['name'])) {
            $sourcePath = $_FILES['file']['tmp_name'];
            //impor_file( array( 'jenis' => 'temporer' ) );
            $ok = $Fungsi->impor_file(array('jenis' => 'temporer'));
            //var_dump($ok);
            $file_path = $ok['data']['path'];
            $l = $lebar_net * 2.8346456693;
        } else {
            //headerku($pdf, $lebar_net, $status_pemerintah, $nama_pemerintah, $nama_opd, $alamat_opd, $PDF_MARGIN_TOP+5);
            $lebar_image = 0.12 / 10 * $lebar_net;
            $image_file = '<img src="img/logo.jpg" style="display:block;align:center;" width="50" height="50"/>';
            //hilangkan sesendok $pdf->SetY($SetY);
            $isi_header = '<table>
                    <tbody>
                        <tr nobr="true">
                            <td rowspan="4" width="12%" style="border-bottom: 1rem double #000000;text-align: center;"><div style="margin-right:auto;margin-left:auto;"> ' . $image_file . '</div></td>
                            <td height="10" width="88%" align="center" style="font-family: Arial; font-size: 12px; width:100%;font-weight:bold;">PEMERINTAH ' . strtoupper($status_pemerintah) . ' ' . strtoupper($nama_pemerintah) . '</td>
                        </tr>
                        <tr nobr="true">
                            <td height="20" width="88%" align="center"  style="font-family: Arial; font-size: 16px; width:100%;font-weight:bold">' . strtoupper($nama_opd) . '</td>
                        </tr>
                        <tr nobr="true">
                            <td height="20" width="88%" align="center"  style="font-family: Arial; font-size: 8px; width:100%;border-bottom: 1rem double #000000;padding: 0px 0px 0px 9px;">' . ucwords($alamat) . '</td>
                        </tr>
                    </tbody>
                </table>';
            //hilangkan sesendok $pdf->writeHTML($isi_header, true, false, false, false, '');
            return $isi_header;
        }
    }
}
