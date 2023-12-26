<?php
include_once("class/SimpleXLSXGen.php");

use Shuchkin\SimpleXLSXGen;

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
    42 => 'ukuran file < 0',
    43 => 'Extension File tidak sesuai',
    44 => 'Kesalahan Pada Sistem',
    45 => 'data telah ada',
    46 => 'data telah ada dan telah diupdate'
];
$type_user = $_SESSION["user"]["type_user"];
$edit_user = $_SESSION["user"]["aktif_edit"];
$id_user = $_SESSION["user"]["id"];
$DB = DB::getInstance();

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
$filename = 'nabiila.xlsx';
$folder = '../temp/';
$code = 44;
$sukses = false;
if ($validate->passed()) {
    $sukses = true;
    $data_kd_proyek = $DB->getWhere('user_ahsp', ['id', '=', $id_user]);
    $kd_proyek = $data_kd_proyek[0]->kd_proyek_aktif;
    $data['dataProyek'] = $DB->getWhere('nama_pkt_proyek', ['kd_proyek', '=', $kd_proyek])[0];
    switch ($jenis) {
        case 'harga_satuan':
            $tabel_pakai = 'harga_sat_upah_bahan';
            break;
        case 'satuan':
            $tabel_pakai = 'daftar_satuan';
            break;
        case "proyek":
            $tabel_pakai = 'nama_pkt_proyek';
            break;
        case 'analisa_alat':
            $tabel_pakai =  'analisa_alat';
            break;
        case 'analisa_quarry':
            $tabel_pakai =  'analisa_quarry';
            break;
        case 'analisa_bm':
            $tabel_pakai =  'analisa_pekerjaan_bm';
            break;
        case 'analisa_sda':
            $tabel_pakai =  'analisa_pekerjaan_sda';
            break;
        case 'analisa_ck':
            $tabel_pakai =  'analisa_pekerjaan_ck';
            break;
        case "proyek":
            $tabel_pakai = 'nama_pkt_proyek';
            break;
        default:
            $filename = 'nabiila.xlsx';
            break;
    }
} else {
    $code = 39;
}
$filename = $folder . $filename;


$writer_data = [
    ['Normal', '12345.67'],
    ['Bold', '<b>12345.67</b>'],
    ['Italic', '<i>12345.67</i>'],
    ['Underline', '<u>12345.67</u>'],
    ['Strike', '<s>12345.67</s>'],
    ['Bold + Italic', '<b><i>12345.67</i></b>'],
    ['Hyperlink', 'https://github.com/shuchkin/simplexlsxgen'],
    ['Italic + Hyperlink + Anchor', '<i><a href="https://github.com/shuchkin/simplexlsxgen">SimpleXLSXGen</a></i>'],
    ['Green', '<style color="#00FF00">12345.67</style>'],
    ['Bold Red Text', '<b><style color="#FF0000">12345.67</style></b>'],
    ['Blue Text and Yellow Fill', '<style bgcolor="#FFFF00" color="#0000FF">12345.67</style>'],
    ['Border color', '<style border="#000000">Black Thin Border</style>'],
    ['<top>Border style</top>', '<style border="medium"><wraptext>none, thin, medium, dashed, dotted, thick, double, hair, mediumDashed, dashDot,mediumDashDot, dashDotDot, mediumDashDotDot, slantDashDot</wraptext></style>'],
    ['Border sides', '<style border="none dotted#0000FF medium#FF0000 double">Top No + Right Dotted + Bottom medium + Left double</style>'],
    ['Left', '<left>12345.67</left>'],
    ['Center', '<center>12345.67</center>'],
    ['Right', '<right>Right Text</right>'],
    ['Center + Bold', '<center><b>Name</b></center>'],
    ['Row height', '<style height="50">Row Height = 50</style>'],
    ['Top', '<style height="50"><top>Top</top></style>'],
    ['Middle + Center', '<style height="50"><middle><center>Middle + Center</center></middle></style>'],
    ['Bottom + Right', '<style height="50"><bottom><right>Bottom + Right</right></bottom></style>'],
    ['<center>MERGE CELLS MERGE CELLS MERGE CELLS MERGE CELLS MERGE CELLS</center>', null],
    ['<top>Word wrap</top>', "<wraptext>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book</wraptext>"]
];


SimpleXLSXGen::fromArray($writer_data)
    ->setDefaultFont('Courier New')
    ->setDefaultFontSize(14)
    ->setColWidth(1, 35)
    ->mergeCells('A20:B20')
    ->saveAs($filename);
$item = array('code' => $code, 'message' => $hasilServer[$code]);
$json = array('success' => $sukses, 'data' => $data, 'error' => $item);
echo json_encode($json);
