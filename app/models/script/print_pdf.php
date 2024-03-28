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
                switch ($jenis) {
                    case 'cetak_pdf':
                        $page_size = $validate->setRules('ukuran_kertas', 'ukuran kertas', [
                            'required' => true,
                            'sanitize' => 'string',
                            'min_char' => 1,
                            'max_char' => 100,
                            'in_array' => ['custom', 'F4', 'A3', 'A4', 'LETTER', 'LEGAL'],
                        ]);
                        $PDF_MARGIN_LEFT = $validate->setRules('margin_left', 'margin kanan', [
                            'numeric' => true,
                            'required' => true
                        ]);
                        $PDF_MARGIN_RIGHT = $validate->setRules('margin_right', 'margin kiri', [
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
                        $orientasi = $validate->setRules('margin_bottom', 'margin bawah', [
                            'sanitize' => 'string',
                            'required' => true,
                            'in_array' => ['portrait', 'lanscape'],
                        ]);
                        switch ($tbl) {
                            case 'sk_asn':
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
                    $code = 55;
                    require_once('class/TCPDF-main/tcpdf.php');
                }
            } else {
                $pesan = 'tidak didefinisikan';
                $code = 39;
            }
        } else {
            $code = 407;
        }
        
        $item = array('code' => $code, 'message' => hasilServer[$code]);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        return json_encode($json, JSON_HEX_APOS);
    }
}
