<?php
class post_data
{
    public function post_data()
    {
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $id_user = $_SESSION["user"]["id"];
        $tahun_anggaran = $_SESSION["user"]["tahun"];
        $keyEncrypt = $_SESSION["user"]["key_encrypt"];
        $btn_edit = '';
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
        $hasilServer = hasilServer[$code];
        $pesan = 'posting kosong';
        $tambahan_pesan = '';
        $item = array('code' => "1", 'message' => $pesan);
        $json = array('success' => $sukses, 'error' => $item);
        $data = array();
        //ambil row user
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
        if (!empty($_POST) && $id_user > 0) {
            $code = 11;
            if (isset($_POST['jenis']) && isset($_POST['tbl'])) {
                $code = 12;
                $validate = new Validate($_POST);
                //var_dump($_POST);
                //var_dump($validate );
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
                //var_dump($tbl);
                //================
                //PROSES VALIDASI
                //================
                switch ($tbl) {
                    case 'asb':
                    case 'sbu':
                    case 'hspk':
                    case 'ssh':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $kd_aset = $validate->setRules('kd_aset', 'kode aset', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['aset_neo', 'kode', [['kode', '=', $_POST['kd_aset']]]],
                                    'min_char' => 4
                                ]);

                                $uraian_barang = $validate->setRules('uraian_barang', 'uraian barang', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $spesifikasi = $validate->setRules('spesifikasi', 'spesifikasi', [
                                    'sanitize' => 'string'
                                ]);
                                $satuan = $validate->setRules('satuan', 'satuan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['satuan_neo', 'value', [['value', "=", $_POST['satuan']]]]
                                ]);
                                $harga_satuan = $validate->setRules('harga_satuan', 'harga satuan', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $tkdn = $validate->setRules('tkdn', 'Tingkat Komponen Dalam Negeri (TKDN)', [
                                    'numeric_zero' => true
                                ]);
                                $merek = $validate->setRules('merek', 'merek', [
                                    'sanitize' => 'string'
                                ]);
                                // urai kode akun
                                $kd_akun = $validate->setRules('kd_akun', 'kode akun', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                if ($kd_akun) {
                                    $formValueExplode = explode(',', $kd_akun);
                                    $kd_akun = '';
                                    foreach ($formValueExplode as $key_row => $row) {
                                        $kondisi_result = [['kode', '=', trim($row)]];
                                        $row_sub = $DB->getWhereOnceCustom('akun_neo', $kondisi_result);
                                        // var_dump( $kondisi_result);
                                        if ($row_sub !== false) {
                                            if ($key_row == 0) {
                                                $kd_akun = $row_sub->kode;
                                            } else {
                                                $kd_akun .= ',' . $row_sub->kode;
                                            }
                                        }
                                    }
                                }
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            default:
                                $untuk_paksa_error = $validate->setRules('inayah_nabiila45557', 'jenis', [
                                    'required' => true,
                                    'min_char' => 200
                                ]);
                                break;
                        }
                        break;
                    case 'dpa':
                    case 'renja':
                    case 'dppa':
                    case 'renja_p':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $objek_belanja = $validate->setRules('objek_belanja', 'objek belanja', [
                                    'sanitize' => 'string',
                                    'in_array' => ['gaji', 'barang_jasa_modal', 'bunga', 'subsidi', 'hibah_barang_jasa', 'hibah_uang', 'sosial_barang_jasa', 'sosial_uang', 'keuangan_umum', 'keuangan_khusus', 'btt', 'bos_pusat', 'blud', 'lahan'],
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $kd_akun = $validate->setRules('kd_akun', 'kd_akun', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['akun_neo', 'kode', [['kode', '=', $_POST['kd_akun']]]],
                                    'min_char' => 4
                                ]);
                                switch ($tbl) {
                                    case 'dpa':
                                    case 'dppa':
                                        $tabel_pakai_temporerSubkeg = 'sub_keg_dpa_neo';
                                        break;
                                    case 'renja':
                                    case 'renja_p':
                                        $tabel_pakai_temporerSubkeg = 'sub_keg_renja_neo';
                                        break;
                                };
                                $id_sub_keg = $validate->setRules('id_sub_keg', 'sub kegiatan', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'required' => true,
                                    'inDB' => [$tabel_pakai_temporerSubkeg, 'id', [['id', '=', (int)$_POST['id_sub_keg']], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]]
                                ]);
                                $row_kd_sub_keg = $DB->getWhereOnceCustom($tabel_pakai_temporerSubkeg, [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]);
                                $kd_sub_keg = ($row_kd_sub_keg) ? $row_kd_sub_keg->kd_sub_keg : 'data sub kegiatan tidak ditemukan';
                                $jenis_kelompok = $validate->setRules('jenis_kelompok', 'jenis kelompok belanja', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['kelompok', 'paket'],
                                    'min_char' => 3
                                ]);

                                $kelompok = $validate->setRules('kelompok', 'kelompok belanja', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inLikeConcatDB' => [$tabel_pakai_temporerSubkeg, 'kelompok_json', [['kelompok_json', "LIKE CONCAT('%',?,'%')", $_POST['kelompok']], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['kd_opd', '= ?', $kd_opd, 'AND'], ['tahun', '= ?', $tahun, 'AND']]]
                                ]);

                                $sumber_dana = $validate->setRules('sumber_dana', 'sumber dana', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDBJsonMultiple' => [$tabel_pakai_temporerSubkeg, 'sumber_dana', "sumber_dana", [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]]
                                ]);
                                $jenis_standar_harga = $validate->setRules('jenis_standar_harga', 'jenis komponen', [
                                    'sanitize' => 'string',
                                    'in_array' => ['ssh', 'sbu', 'hspk', 'asb'],
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $tabel_pakai_temporer = $Fungsi->tabel_pakai($jenis_standar_harga)['tabel_pakai'];
                                $id_standar_harga = $validate->setRules('komponen', 'komponen', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1,
                                    'inLikeConcatDB' => [$tabel_pakai_temporer, 'id', [['id', "LIKE CONCAT('%',?,'%')", $_POST['komponen']], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['tahun', '= ?', $tahun, 'AND']]]
                                ]);
                                if ($id_standar_harga) {
                                    $harga_row = $DB->getWhereOnceCustom($tabel_pakai_temporer, [['id', '=', $id_standar_harga], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['tahun', '=', $tahun, 'AND']]);
                                    $harga_satuan = ($harga_row) ? $harga_row->harga_satuan : 0;
                                    $komponen = ($harga_row) ? $harga_row->uraian_barang : 'komponen tidak ada di database';
                                    $spesifikasi = ($harga_row) ? $harga_row->spesifikasi : 'komponen tidak ada di database';
                                    $tkdn = ($harga_row) ? $harga_row->tkdn : 'komponen tidak ada di database';
                                }
                                $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                $uraian = $validate->setRules('uraian', 'uraian belanja', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inLikeConcatDB' => [$tabel_pakai_temporerSubkeg, 'keterangan_json', [['keterangan_json', "LIKE CONCAT('%',?,'%')", $_POST['uraian']], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['kd_opd', '= ?', $kd_opd, 'AND'], ['tahun', '= ?', $tahun, 'AND']]]
                                ]);
                                $vol_1 = $validate->setRules('vol_1', 'koefisien perkalian 1', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $sat_1 = $validate->setRules('sat_1', 'satuan koefisien perkalian 1', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['satuan_neo', 'value', [['value', "=", $_POST['sat_1']]]]
                                ]);
                                $vol_2 = 0;
                                $vol_2 = $validate->setRules('vol_2', 'koefisien perkalian 2', [
                                    'sanitize' => 'string',
                                    'numeric_zero' => true,
                                ]);
                                $sat_2 = '';
                                if ($vol_2 > 0) {
                                    $sat_2 = $validate->setRules('sat_2', 'satuan koefisien perkalian 2', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'inDB' => ['satuan_neo', 'value', [['value', "=", $_POST['sat_2']]]]
                                    ]);
                                }
                                $vol_3 = 0;
                                $vol_3 = $validate->setRules('vol_3', 'koefisien perkalian 3', [
                                    'sanitize' => 'string',
                                    'numeric_zero' => true,
                                ]);
                                $sat_3 = '';
                                if ($vol_3 > 0) {
                                    $sat_3 = $validate->setRules('sat_3', 'satuan koefisien perkalian 3', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'inDB' => ['satuan_neo', 'value', [['value', "=", $_POST['sat_3']]]]
                                    ]);
                                }
                                $vol_4 = 0;
                                $vol_4 = $validate->setRules('vol_3', 'koefisien perkalian 3', [
                                    'sanitize' => 'string',
                                    'numeric_zero' => true,
                                ]);
                                $sat_4 = '';
                                if ($vol_4 > 0) {
                                    $sat_4 = $validate->setRules('sat_4', 'satuan koefisien perkalian 4', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'inDB' => ['satuan_neo', 'value', [['value', "=", $_POST['sat_4']]]]
                                    ]);
                                }
                                $vol_1_kali = ($vol_1) ? $vol_1 : 1;
                                $vol_2_kali = ($vol_2) ? $vol_2 : 1;
                                $vol_3_kali = ($vol_3) ? $vol_3 : 1;
                                $vol_4_kali = ($vol_4) ? $vol_4 : 1;
                                $volume = $vol_1_kali * $vol_2_kali * $vol_3_kali * $vol_4_kali;
                                $jumlah = $volume * $harga_satuan;
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $pajak = $validate->setRules('pajak', 'pajak', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $pajak = ($pajak == 'on') ? 1 : 0;
                                break;
                            default:
                                $untuk_paksa_error = $validate->setRules('inayah_nabiila45557', 'jenis', [
                                    'required' => true,
                                    'min_char' => 200
                                ]);
                                break;
                        }
                        break;
                    case 'sub_keg_dpa':
                    case 'sub_keg_renja':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $kd_sub_keg = $validate->setRules('kd_sub_keg', 'sub kegiatan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['sub_kegiatan_neo', 'kode', [['kode', '=', $_POST['kd_sub_keg']]]],
                                    'min_char' => 4
                                ]);
                                //$kel_rek
                                $tolak_ukur_hasil = $validate->setRules('tolak_ukur_hasil', 'tolak_ukur_hasil', [
                                    'sanitize' => 'string'
                                ]);

                                $target_kinerja_hasil = $validate->setRules('target_kinerja_hasil', 'target kinerja hasil', [
                                    'sanitize' => 'string'
                                ]);
                                $keluaran_sub_keg = $validate->setRules('keluaran_sub_keg', 'keluaran sub keg', [
                                    'sanitize' => 'string'
                                ]);
                                $jumlah_pagu = $validate->setRules('jumlah_pagu', 'jumlah pagu', [
                                    'sanitize' => 'string',
                                    'numeric_zero' => true,
                                ]);
                                $jumlah_pagu_p = $validate->setRules('jumlah_pagu_p', 'jumlah pagu perubahan', [
                                    'sanitize' => 'string',
                                    'numeric_zero' => true,
                                ]);
                                $lokasi = $validate->setRules('lokasi', 'lokasi', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            case 'add_field_json':
                                $uraian_field = $validate->setRules('uraian', 'sub kegiatan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $uraian_field =  preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian_field);
                                $nama_kolom = $validate->setRules('klm', 'nama kolom', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $tabel_pakai_temporer = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                $id_sub_keg = $validate->setRules('id_sub_keg', 'sub kegiatan', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'required' => true,
                                    'inDB' => [$tabel_pakai_temporer, 'id', [['id', '=', (int)$_POST['id_sub_keg']], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]]
                                ]);
                                $nama_kolom = $validate->setRules('klm', 'kolom', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                switch ($nama_kolom) {
                                    case 'kelompok_json':
                                        $jenis_kelompok = $validate->setRules('jns_kel', 'jenis kelompok belanja', [
                                            'sanitize' => 'string',
                                            'required' => true,
                                            'in_array' => ['kelompok', 'paket'],
                                            'min_char' => 3
                                        ]);
                                        break;
                                    default:
                                        // $jenis_kelompok nama key json
                                        $jenis_kelompok = $validate->setRules('jns_kel', 'jenis kelompok belanja', [
                                            'sanitize' => 'string',
                                            'required' => true,
                                            'min_char' => 1
                                        ]);
                                        break;
                                }
                                break;
                            default:
                                $untuk_paksa_error = $validate->setRules('inayah_nabiila45557', 'jenis', [
                                    'required' => true,
                                    'min_char' => 200
                                ]);
                                break;
                        }
                        break;
                    case 'tujuan_sasaran_renstra':
                    case 'sasaran_renstra':
                    case 'tujuan_renstra':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $kelompok = $validate->setRules('kelompok', 'kelompok', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['tujuan', 'sasaran'],
                                    'min_char' => 4
                                ]);
                                if ($kelompok == 'sasaran') {
                                    $id_tujuan = $validate->setRules('id_tujuan', 'tujuan', [
                                        'numeric' => true,
                                        'required' => true,
                                        'min_char' => 1
                                    ]);
                                }
                                $text = $validate->setRules('text', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'renstra':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                                if ($rowOrganisasi) {
                                    $tahun_renstra = $rowOrganisasi->tahun_renstra;
                                }
                                $kd_sub_keg = $validate->setRules('kd_sub_keg', 'sub kegiatan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['sub_kegiatan_neo', 'kode', [['kode', '=', $_POST['kd_sub_keg']]]],
                                    'min_char' => 4
                                ]);
                                $sasaran = $validate->setRules('sasaran', 'sasaran', [
                                    'numeric' => true,
                                    'required' => true,
                                    'inDB' => ['tujuan_sasaran_renstra_neo', 'id', [['id', '=', $_POST['sasaran']], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['tahun', '=', $tahun_renstra, 'AND']]],
                                    'min_char' => 1
                                ]);
                                $indikator = $validate->setRules('indikator', 'indikator', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $satuan = $validate->setRules('satuan', 'satuan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $data_capaian_awal = $validate->setRules('data_capaian_awal', 'data capaian awal', [
                                    'numeric_zero' => true,
                                    'min_char' => 1
                                ]);
                                $target_thn_1 = $validate->setRules('target_thn_1', 'Target tahun pertama', [
                                    'numeric_zero' => true,
                                ]);
                                $dana_thn_1 = $validate->setRules('dana_thn_1', 'Dana tahun pertama', [
                                    'numeric_zero' => true,
                                ]);
                                $target_thn_2 = $validate->setRules('target_thn_2', 'Target tahun kedua', [
                                    'numeric_zero' => true,
                                ]);
                                $dana_thn_2 = $validate->setRules('dana_thn_2', 'Dana tahun kedua', [
                                    'numeric_zero' => true,
                                ]);
                                $target_thn_3 = $validate->setRules('target_thn_3', 'Target tahun ketiga', [
                                    'numeric_zero' => true,
                                ]);
                                $dana_thn_3 = $validate->setRules('dana_thn_3', 'Dana tahun ketiga', [
                                    'numeric_zero' => true,
                                ]);
                                $target_thn_4 = $validate->setRules('target_thn_4', 'Target tahun keempat', [
                                    'numeric_zero' => true,
                                ]);
                                $dana_thn_4 = $validate->setRules('dana_thn_4', 'Dana tahun keempat', [
                                    'numeric_zero' => true,
                                ]);
                                $target_thn_5 = $validate->setRules('target_thn_5', 'Target tahun kelima', [
                                    'numeric_zero' => true,
                                ]);
                                $dana_thn_5 = $validate->setRules('dana_thn_5', 'Dana tahun kelima', [
                                    'numeric_zero' => true,
                                ]);
                                $lokasi = $validate->setRules('lokasi', 'lokasi', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'organisasi':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $kode = $validate->setRules('kode', 'kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $alamat = $validate->setRules('alamat', 'alamat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $nama_kepala = $validate->setRules('nama_kepala', 'Nama Kepala SKPD', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 3
                                ]);
                                $nip_kepala = $validate->setRules('nip_kepala', 'Nip Kepala SKPD', [
                                    'numeric' => true,
                                    'required' => true,
                                    'min_char' => 18
                                ]);
                                $tahun_renstra = $validate->setRules('tahun_renstra', 'Tahun Renstra', [
                                    'numeric' => true,
                                    'required' => true,
                                    'min_char' => 4,
                                    'max_char' => 4
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'outbox':
                        switch ($jenis) {
                            case 'add_coment':
                                //id reply jika tbl edit id_row
                                $id_tujuan = $validate->setRules('users', 'user penerima', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $uraian = $validate->setRules('uraian', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 400
                                ]);
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'wall':
                        switch ($jenis) {
                            case 'edit':
                            case 'reply':
                                //id reply jika tbl edit id_row
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add_coment':
                                $uraian = $validate->setRules('text', 'text', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 400
                                ]);
                                break;
                            case 'like':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $like = $validate->setRules('like', 'like', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['like', 'unlike', 'non']
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'peraturan':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $type_dok = $validate->setRules('type_dok', 'type', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['peraturan_undang_undang_pusat', 'peraturan_menteri_lembaga', 'peraturan_daerah', 'pengumuman', 'artikel', 'lain']
                                ]);
                                $judul = $validate->setRules('judul', 'judul', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $nomor = $validate->setRules('nomor', 'nomor', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $bentuk = $validate->setRules('bentuk', 'bentuk', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $bentuk_singkat = $validate->setRules('bentuk_singkat', 'bentuk_singkat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $t4_penetapan = $validate->setRules('t4_penetapan', 'tempat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $tgl_penetapan = $validate->setRules('tgl_penetapan', 'tanggal penetapan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                //date time  ^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])(?:( [0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$
                                $tgl_pengundangan = $validate->setRules('tgl_pengundangan', 'tanggal pengundangan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                $status = $validate->setRules('status', 'status', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['rahasia', 'umum', 'proyek']
                                ]);
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'pengaturan':
                        switch ($jenis) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'add':
                                $tahun = $validate->setRules('tahun', 'tahun', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 4,
                                    'max_char' => 4
                                ]);
                                $aturan_anggaran = $validate->setRules('aturan_anggaran', 'Peraturan anggaran', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_anggaran']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_pengadaan = $validate->setRules('aturan_pengadaan', 'Peraturan pengadaan', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_pengadaan']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_akun = $validate->setRules('aturan_akun', 'Peraturan Akun', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_akun']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_sumber_dana = $validate->setRules('aturan_sumber_dana', 'Peraturan sumber dana', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_sumber_dana']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_sub_kegiatan = $validate->setRules('aturan_sub_kegiatan', 'Peraturan sub kegiatan', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_sub_kegiatan']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_ssh = $validate->setRules('aturan_ssh', 'Peraturan ssh', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_ssh']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_hspk = $validate->setRules('aturan_hspk', 'Peraturan hspk', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_hspk']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_asb = $validate->setRules('aturan_asb', 'Peraturan asb', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_asb']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $aturan_sbu = $validate->setRules('aturan_sbu', 'Peraturan sbu', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_sbu']]]],
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $awal_renstra = $validate->setRules('awal_renstra', 'tanggal awal renstra', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $akhir_renstra = $validate->setRules('akhir_renstra', 'tanggal akhir renstra', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $awal_renja = $validate->setRules('awal_renja', 'tanggal awal renja', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                //'/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',2024-2-16 10:10:0
                                $akhir_renja = $validate->setRules('akhir_renja', 'tanggal akhir renja', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $awal_renja_p = $validate->setRules('awal_renja_p', 'tanggal awal renja perubahan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $akhir_renja_p = $validate->setRules('akhir_renja_p', 'tanggal akhir renja perubahan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $awal_dpa = $validate->setRules('awal_dpa', 'tanggal awal dpa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $akhir_dpa = $validate->setRules('akhir_dpa', 'tanggal akhir dpa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $awal_dppa = $validate->setRules('awal_dppa', 'tanggal awal dppa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $akhir_dppa = $validate->setRules('akhir_dppa', 'tanggal akhir dppa', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'rekanan':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $nama_perusahaan = $validate->setRules('nama_perusahaan', 'nama_perusahaan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //var_dump($_POST['alamat']);
                                $alamat = $validate->setRules('alamat', 'alamat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $npwp = $validate->setRules('npwp', 'npwp', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 16
                                ]);
                                $direktur = $validate->setRules('direktur', 'direktur', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $no_ktp = $validate->setRules('no_ktp', 'Nomor KTP Direktur', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $alamat_dir = $validate->setRules('alamat_dir', 'Alamat Direktur', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $no_akta_pendirian = $validate->setRules('no_akta_pendirian', 'alamat', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $tgl_akta_pendirian = $validate->setRules('tgl_akta_pendirian', 'Tanggal Akta Pendirian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);
                                $lokasi_notaris_pendirian = $validate->setRules('lokasi_notaris_pendirian', 'lokasi_notaris_pendirian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $nama_notaris_pendirian = $validate->setRules('nama_notaris_pendirian', 'nama_notaris_pendirian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                //perlu cara khusus JSON
                                $notaris_perubahan = $validate->setRules('notaris_perubahan', 'Akta Perubahan', [
                                    'json' => true,
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $data_lain = $validate->setRules('data_lain', 'Data lainnya', [
                                    'json' => true,
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                //$dataArray = json_decode($dataArray);
                                // $notaris_perubahan = '{}';
                                // $data_lain = '{}';
                                // $file = '';
                                break;
                            default:
                                # code...
                                break;
                        }
                        break;
                    case 'informasi_umum':
                        switch ($tbl) {
                            case 'edit':
                                $id_row = $validate->setRules('id_row', 'id', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                            case 'input':
                                $kode = $validate->setRules('kode', 'kode', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1,
                                    'max_char' => 100
                                ]);
                                $nomor_uraian = $validate->setRules('nomor_uraian', 'Nomor', [
                                    'sanitize' => 'string'
                                ]);
                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $satuan = $validate->setRules('satuan', 'satuan', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $nilai = $validate->setRules('nilai', 'nilai', [
                                    'numeric' => true
                                ]);
                                $type = 'custom';
                                break;
                            default:
                                break;
                        }
                        break;
                    case 'profil':
                        switch ($tbl) {
                            case 'edit':
                                $id_user = $validate->setRules('id_row', 'ID', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $type_user = $validate->setRules('type_user', 'type user', [
                                    'sanitize' => 'string',
                                    'min_char' => 2, //on-off
                                    'in_array' => ['user', 'admin', 'super']
                                ]);
                                //break;
                            case 'profil':
                                $nama = $validate->setRules('nama', 'nama', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6
                                ]);
                                $username = $validate->setRules('username', 'username', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6,
                                    'max_char' => 100,
                                    'regexp' => "/^[A-Za-z_]{6,}$/",
                                    'uniqueArray' => ['user_sesendok_biila', 'username', [['id', '!=', $id_user]]]
                                ]);
                                $email = $validate->setRules('email', 'email', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6,
                                    'max_char' => 255,
                                    'email' => true,
                                    'uniqueArray' => ['user_sesendok_biila', 'email', [['id', '!=', $id_user]]]
                                ]);
                                $kontak_person = $validate->setRules('kontak_person', 'kontak person', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6
                                ]);
                                $nama_org = $validate->setRules('nama_org', 'Nama Organisasi', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 6
                                ]);
                                $ket = $validate->setRules('ket', 'Keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                if ($tbl == 'profil') {
                                    $font = $validate->setRules('font', 'uraian', [
                                        'required' => true,
                                        'numeric' => true
                                    ]);
                                    $warna_tbl = $validate->setRules('warna_tbl', 'warna Tabel', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'min_char' => 3
                                    ]);
                                }
                                break;
                            default:
                                # code...
                                break;
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
                    default:
                        $err = 6;
                }
                //FINISH PROSES VALIDASI
                $kodePosting = '';
                if ($validate->passed()) {
                    $code = 55;
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
                    //tabel pakai
                    $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                    $jumlah_kolom = $Fungsi->tabel_pakai($tbl)['jumlah_kolom'];
                    $code = 10;
                    $sukses = true;
                    $err = 0;
                    $columnName = "*";
                    switch ($jenis) {
                        case 'edit':
                            $kondisi = [['id', '=', $id_row]];
                            $kodePosting = 'update_row';
                            break;
                        case 'add':
                            if ($_FILES) {
                                if ($_FILES['file']) {
                                    $file = $Fungsi->importFile($tbl, '');
                                    //var_dump($file);
                                    if ($file['result'] == 'ok') {
                                        $set['file'] = $file['file'];
                                    } else {
                                        $tambahan_pesan = "(" . $file['file'] . ")";
                                    }
                                }
                            }
                            break;
                        case 'add_field_json':

                            break;
                        default:
                            break;
                    }
                    //start buat property
                    switch ($tbl) {
                        case 'asb':
                        case 'sbu':
                        case 'hspk':
                        case 'ssh':
                            switch ($jenis) {
                                case 'add_field_json':
                                    break;
                                case 'add':
                                case 'edit':
                                    if ($jenis == 'add') {
                                        $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_aset', '=', $kd_aset, 'AND'], ['tahun', '=', $tahun, 'AND']];
                                        $kodePosting = 'cek_insert';
                                    }
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'tahun' => $tahun,
                                        'kd_aset' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kd_aset),
                                        'uraian_barang' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian_barang),
                                        'spesifikasi' => preg_replace('/(\s\s+|\t|\n)/', ' ', $spesifikasi),
                                        'satuan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $satuan),
                                        'harga_satuan' => $harga_satuan,
                                        'tkdn' => $tkdn,
                                        'kd_akun' => preg_replace('/(\s\s+|\t|\n)/', ' ', $kd_akun),
                                        'peraturan' => $id_aturan_[$tbl],
                                        'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                        'disable' => 0,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                    
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'dppa':
                        case 'renja_p':
                        case 'dpa':
                        case 'renja':
                            switch ($jenis) {
                                case 'add_field_json':
                                    $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                                    $kodePosting = $jenis;
                                    break;
                                case 'value1':
                                    #code...
                                    break;
                                default:
                                    $row_progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $kd_sub_keg]]);
                                    // $uraian = ($row_progkeg) ? $row_progkeg->nomenklatur_urusan : 'data sub kegiatan tidak ditemukan';
                                    switch ($tabel_pakai) {
                                        case 'sub_keg_dpa_neo':
                                        case 'sub_keg_renja_neo':
                                            $kolomJumlah = 'jumlah';
                                            break;
                                        case 'dpa_neo':
                                        case 'renja_neo':
                                            $kolomJumlah = 'jumlah';
                                            $kolomVol_1 = 'vol_1';
                                            $kolomVol_2 = 'vol_2';
                                            $kolomVol_3 = 'vol_3';
                                            $kolomVol_4 = 'vol_4';
                                            $kolomVol_5 = 'vol_5';
                                            $kolomHarga_satuan = 'harga_satuan';
                                            $kolomVolume = 'volume';
                                            $kolomSat_1 = 'sat_1';
                                            $kolomSat_2 = 'sat_2';
                                            $kolomSat_3 = 'sat_3';
                                            $kolomSat_4 = 'sat_4';
                                            $kolomSat_5 = 'sat_5';
                                            break;
                                        case 'dppa_neo':
                                        case 'renja_p_neo':
                                            $kolomJumlah = 'jumlah_p';
                                            $kolomVol_1 = 'vol_1_p';
                                            $kolomVol_2 = 'vol_2_p';
                                            $kolomVol_3 = 'vol_3_p';
                                            $kolomVol_4 = 'vol_4_p';
                                            $kolomVol_5 = 'vol_5_p';
                                            $kolomHarga_satuan = 'harga_satuan_p';
                                            $kolomVolume = 'volume_p';
                                            $kolomSat_1 = 'sat_1_p';
                                            $kolomSat_2 = 'sat_2_p';
                                            $kolomSat_3 = 'sat_3_p';
                                            $kolomSat_4 = 'sat_4_p';
                                            $kolomSat_5 = 'sat_5_p';
                                            break;
                                        case 'renstra_neo':
                                            $kolomJumlah = 'jumlah';
                                            break;
                                        default:
                                            break;
                                    };
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'kd_opd' => $kd_opd,
                                        'tahun' => $tahun,
                                        'kd_sub_keg' => $kd_sub_keg,
                                        'kd_akun' => $kd_akun,
                                        'kel_rek' => 'uraian', //kd
                                        'objek_belanja' => $objek_belanja,
                                        'uraian' => $uraian,
                                        'jenis_kelompok' => $jenis_kelompok,
                                        'kelompok' => $kelompok,
                                        'jenis_standar_harga' => $jenis_standar_harga,
                                        'id_standar_harga' => $id_standar_harga,
                                        'komponen' => $komponen,
                                        'spesifikasi' => $spesifikasi,
                                        'tkdn' => (float)$tkdn,
                                        'pajak' => $pajak,
                                        $kolomHarga_satuan => $harga_satuan,
                                        $kolomVol_1 => $vol_1,
                                        $kolomVol_2 => $vol_2,
                                        $kolomVol_3 => $vol_3,
                                        $kolomVol_4 => $vol_4,
                                        $kolomSat_1 => $sat_1,
                                        $kolomSat_2 => $sat_2,
                                        $kolomSat_3 => $sat_3,
                                        $kolomSat_4 => $sat_4,
                                        $kolomVolume => $volume,
                                        $kolomJumlah => $jumlah,
                                        'sumber_dana' => $sumber_dana,
                                        'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                        'disable' => 0,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'tgl_update' => date('Y-m-d H:i:s'),
                                        'username_input' => $_SESSION["user"]["username"],
                                        'username_update' => $_SESSION["user"]["username"]
                                    ];
                                    $dinamic = ['tbl' => $tbl, 'kd_sub_keg' => $kd_sub_keg, 'set' => $set, 'kd_akun' => $kd_akun, 'kd_wilayah' => $kd_wilayah, 'kd_opd' => $kd_opd, 'tahun' => $tahun];
                                    $insertKodeRek = $Fungsi->kelolaRekSubKegDanAkun($dinamic);
                                    $data = $insertKodeRek;
                                    $kodePosting = '';
                                    break;
                            };


                            break;
                        case 'sub_keg_dpa':
                        case 'sub_keg_renja':
                            switch ($jenis) {
                                case 'add_field_json':
                                    $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                                    $kodePosting = $jenis;
                                    break;
                                case 'value1':
                                    #code...
                                    break;
                                default:
                                    $dinamic = ['kode' => $kd_sub_keg];
                                    $kodeRekUbah = $Fungsi->kelolaRek($dinamic);
                                    if (array_key_exists('kel_rek', $kodeRekUbah)) {
                                        $kel_rek = $kodeRekUbah['kel_rek'];
                                    }
                                    $progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $kd_sub_keg]]);
                                    $uraian = ($progkeg) ? $progkeg->nomenklatur_urusan : 'data sub kegiatan tidak ditemukan';
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'kd_opd' => $kd_opd,
                                        'tahun' => $tahun,
                                        'kd_sub_keg' => $kd_sub_keg,
                                        'uraian' => $uraian,
                                        'kel_rek' => $kel_rek,
                                        'tolak_ukur_hasil' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_hasil),
                                        'target_kinerja_hasil' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_hasil),
                                        'keluaran_sub_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keluaran_sub_keg),
                                        'jumlah_pagu' => $jumlah_pagu,
                                        'jumlah_pagu_p' => $jumlah_pagu_p,
                                        'lokasi' => preg_replace('/(\s\s+|\t|\n)/', ' ', $lokasi),
                                        'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                        'disable' => $disable,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'tgl_update' => date('Y-m-d H:i:s'),
                                        'username' => $_SESSION["user"]["username"]
                                    ];

                                    $dinamic = ['tbl' => $tbl, 'kd_sub_keg' => $kd_sub_keg, 'set' => $set, 'kd_wilayah' => $kd_wilayah, 'kd_opd' => $kd_opd, 'tahun' => $tahun];
                                    $cekKodeRek = $Fungsi->kelolaRekSubKegDanAkun($dinamic);
                                    $data = $cekKodeRek;
                                    $kodePosting = '';
                                    break;
                            };


                            break;
                        case 'tujuan_renstra':
                        case 'tujuan_sasaran_renstra':
                        case 'sasaran_renstra':
                            if ($kelompok == 'tujuan') {
                                $id_tujuan = 0;
                            }
                            $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                            if ($rowOrganisasi) {
                                $tahun_renstra = $rowOrganisasi->tahun_renstra;
                                if ($tahun_renstra > 2000) {
                                    if ($jenis == 'add') {
                                        $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['kelompok', '=', $kelompok, 'AND'], ['text', '=', $text, 'AND']];
                                        $kodePosting = 'cek_insert';
                                    }
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'kd_opd' => $kd_opd,
                                        'tahun' => $tahun_renstra,
                                        'id_tujuan' => $id_tujuan,
                                        'kelompok' => $kelompok,
                                        'text' => $text,
                                        'disable' => $disable,
                                        'keterangan' => $keterangan,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'tgl_update' => date('Y-m-d H:i:s'),
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                } else {
                                    $message_tambah = ' (atur tahun renstra OPD)';
                                    $code = 70;
                                    $kodePosting = '';
                                }
                            } else {
                                $message_tambah = ' (atur organisasi OPD)';
                                $kodePosting = '';
                                $code = 70;
                            }
                            break;
                        case 'renstra':
                            $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                            if ($rowOrganisasi) {
                                $tahun_renstra = $rowOrganisasi->tahun_renstra;
                                $unit_kerja = $rowOrganisasi->uraian;
                                if ($tahun_renstra > 2000) {
                                    if ($jenis == 'add') {
                                        $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['kode', '=', $kode, 'AND']];
                                        $kodePosting = 'cek_insert';
                                    }
                                    // id tujuan
                                    $tujuan = $DB->getWhereOnceCustom('tujuan_sasaran_renstra_neo', [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['id', '=', $sasaran, 'AND']]);
                                    $id_tujuan = ($tujuan) ? $tujuan->id_tujuan : 0;
                                    $sasaran_txt = ($tujuan) ? $tujuan->text : '';
                                    //uraian_prog_keg
                                    $progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $kd_sub_keg]]);
                                    $uraian_prog_keg = ($progkeg) ? $progkeg->nomenklatur_urusan : 'data tidak ditemukan';
                                    // $uraian_prog_keg = $progkeg->nomenklatur_urusan;
                                    //kondisi_akhir
                                    $kondisi_akhir = (float)$data_capaian_awal + (float)$target_thn_1 + (float)$target_thn_2 + (float)$target_thn_3 + (float)$target_thn_3 + (float)$target_thn_5;
                                    $jumlah = (float)$dana_thn_1 + (float)$dana_thn_2 + (float)$dana_thn_3 + (float)$dana_thn_4 + (float)$dana_thn_5;
                                    $dinamic = ['kode' => $kd_sub_keg];
                                    $kodeRekUbah = $Fungsi->kelolaRek($dinamic);
                                    if (array_key_exists('kel_rek', $kodeRekUbah)) {
                                        $kel_rek = $kodeRekUbah['kel_rek'];
                                    }
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'kd_opd' => $kd_opd,
                                        'tahun' => $tahun_renstra,
                                        'kd_sub_keg' => $kd_sub_keg,
                                        'kel_rek' => $kel_rek,
                                        'tujuan' => $id_tujuan,
                                        'sasaran' => $sasaran,
                                        'sasaran_txt' => $sasaran_txt,
                                        'uraian_prog_keg' => $uraian_prog_keg,
                                        'indikator' => $indikator,
                                        'satuan' => $satuan,
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
                                        'jumlah' => (float)$jumlah,
                                        'lokasi' => $lokasi,
                                        'keterangan' => $keterangan,
                                        'disable' => $disable,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'tgl_update' => date('Y-m-d H:i:s'),
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                    $dinamic = ['tbl' => $tbl, 'kd_sub_keg' => $kd_sub_keg, 'set' => $set, 'kd_wilayah' => $kd_wilayah, 'kd_opd' => $kd_opd, 'tahun' => $tahun_renstra];
                                    $cekKodeRek = $Fungsi->kelolaRekSubKegDanAkun($dinamic);
                                    $kodePosting = '';
                                } else {
                                    $message_tambah = ' (atur tahun renstra OPD)';
                                    $code = 70;
                                    $kodePosting = '';
                                }
                            } else {
                                $message_tambah = ' (atur organisasi OPD)';
                                $kodePosting = '';
                                $code = 70;
                            }
                            break;
                        case 'organisasi':
                            if ($jenis == 'add') {
                                $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_organisasi, 'AND']];
                                $kodePosting = 'cek_insert';
                            }
                            $set = [
                                'kd_wilayah' => $kd_wilayah,
                                'kode' => $kode,
                                'uraian' => $uraian,
                                'alamat' => $alamat,
                                'nama_kepala' => $nama_kepala,
                                'nip_kepala' => $nip_kepala,
                                'peraturan' =>  $id_aturan_anggaran,
                                'tahun_renstra' => $tahun_renstra,
                                'disable' => $disable,
                                'keterangan' => $keterangan,
                                'tanggal' => date('Y-m-d H:i:s'),
                                'username' => $_SESSION["user"]["username"]
                            ];
                            break;
                        case 'peraturan':
                            if ($jenis == 'add') {
                                $kondisi = [['judul', '=', $judul], ['nomor', '=', $nomor, 'AND']];
                                $kodePosting = 'cek_insert';
                            }
                            $kode  = "$nomor:$tgl_pengundangan";
                            $set = [
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
                                'disable' => $disable,
                                'keterangan' => $keterangan,
                                'status' => $status,
                                'tanggal' => date('Y-m-d H:i:s'),
                                'username' => $_SESSION["user"]["username"]
                            ];
                            //var_dump($set);
                            //pengolahan file
                            if ($_FILES['file']) {
                                $file = $Fungsi->importFile($tbl, '');
                                //var_dump($file);
                                if ($file['result'] == 'ok') {
                                    $set['file'] = $file['file'];
                                } else {
                                    $tambahan_pesan = "(" . $file['file'] . ")";
                                }
                            }
                            break;
                        case 'pengaturan':
                            if ($jenis == 'add') {
                                $kondisi = [['tahun', '=', $tahun]];
                                $kodePosting = 'cek_insert';
                            }
                            $set = [
                                'kd_wilayah' => $kd_wilayah,
                                'tahun' => $tahun,
                                'aturan_anggaran' => $aturan_anggaran,
                                'aturan_pengadaan' => $aturan_pengadaan,
                                'aturan_akun' => $aturan_akun,
                                'aturan_asb' => $aturan_asb,
                                'aturan_sbu' => $aturan_sbu,
                                'aturan_ssh' => $aturan_ssh,
                                'aturan_hspk' => $aturan_hspk,
                                'aturan_sumber_dana' => $aturan_sumber_dana,
                                'aturan_sub_kegiatan' => $aturan_sub_kegiatan,
                                'awal_renstra' => $awal_renstra,
                                'akhir_renstra' => $akhir_renstra,
                                'awal_renja' => $awal_renja,
                                'akhir_renja' => $akhir_renja,
                                'awal_renja_p' => $awal_renja_p,
                                'akhir_renja_p' => $akhir_renja_p,
                                'awal_dpa' => $awal_dpa,
                                'akhir_dpa' => $akhir_dpa,
                                'awal_dppa' => $awal_dppa,
                                'akhir_dppa' => $akhir_dppa,
                                'disable' => $disable,
                                'keterangan' => $keterangan,
                                'tanggal' => date('Y-m-d H:i:s'),
                                'username' => $_SESSION["user"]["username"]
                            ];
                            break;
                        case 'rekanan':
                            switch ($jns) {
                                case 'edit':
                                    $kondisi = [['id', '=', $id_row]];
                                    $kodePosting = 'update_row';
                                case 'input':
                                    if ($tbl == 'input') {
                                        $kondisi = [['nama_perusahaan', '=', $nama_perusahaan]];
                                        $kodePosting = 'cek_insert';
                                    }
                                    //var_dump($notaris_perubahan);
                                    $set = [
                                        'nama_perusahaan' => $nama_perusahaan,
                                        'alamat' => $alamat,
                                        'npwp' => $npwp,
                                        'direktur' => $direktur,
                                        'no_ktp' => $no_ktp,
                                        'alamat_dir' => $alamat_dir,
                                        'no_akta_pendirian' => $no_akta_pendirian,
                                        'tgl_akta_pendirian' => $tgl_akta_pendirian,
                                        'lokasi_notaris_pendirian' => $lokasi_notaris_pendirian,
                                        'nama_notaris_pendirian' => $nama_notaris_pendirian,
                                        'notaris_perubahan' => $notaris_perubahan,
                                        'data_lain' => $data_lain,
                                        'keterangan' => $keterangan
                                    ];
                                    //pengolahan file
                                    if ($_FILES['file']) {
                                        $file = $Fungsi->importFile($jenis, '');
                                        //var_dump($file);
                                        if ($file['result'] == 'ok') {
                                            $set['file'] = $file['file'];
                                        }
                                    }
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case 'sortir':
                            $condition = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id_row, 'AND']];
                            $DB->orderBy('no_sortir');
                            $result = $DB->getWhereCustom($tabel_pakai, $condition);
                            $jumlahArray = is_array($result) ? count($result) : 0;
                            //var_dump($result);
                            if ($jumlahArray) {
                                foreach ($result[0] as $row) {
                                    $no_sortir_awal = (int)$row->no_sortir;
                                }
                                switch ($mode_sortir) {
                                    case 'up_row':
                                        $no_sortir_change = $no_sortir_awal + 1; //
                                        $no_sortir_final = $no_sortir_awal - 1; //utama
                                        break;
                                    case 'down_row':
                                        $no_sortir_change = $no_sortir_awal - 1;
                                        $no_sortir_final = $no_sortir_awal + 1;
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                switch ($tabel_pakai) {
                                    case 'analisa_alat':
                                        # code...
                                        break;
                                    case 'analisa_alat_custom':
                                        # code...
                                        break;
                                    case 'daftar_satuan':
                                        # code...
                                        break;
                                    case 'rencana_anggaran_biaya':
                                        //cari dan update nomor sortir yang sama dengan $no_sortir_final dan ubah minus atau tambah
                                        $kondisi = [['kd_proyek', '=', $kd_proyek], ['no_sortir', '=', $no_sortir_final, 'AND']];
                                        $set = [
                                            'no_sortir' => $no_sortir_change
                                        ];
                                        $kondisi2 = $condition;
                                        $set2 = [
                                            'no_sortir' => $no_sortir_final
                                        ];
                                        break;
                                    case 'schedule_table':
                                        # code...
                                        break;
                                    case 'lokasi_proyek':
                                        # code...
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                $kodePosting = 'update_row';
                            } else {
                                $code = 404;
                            }
                            break;
                        case 'profil':
                            switch ($tbl) {
                                case 'edit':
                                    $kondisi = [['id', '=', $id_user]];
                                    $set = [
                                        'username' => $username,
                                        'email' => $email,
                                        'nama' => $nama,
                                        'kontak_person' => $kontak_person,
                                        'nama_org' => $nama_org,
                                        'type_user' => $type_user,
                                        'ket' => $ket
                                    ];
                                    $kodePosting = 'cek_insert';
                                    break;
                                case 'profil':
                                    $kondisi = [['id', '=', $id_user], ['type_user', '=', $type_user, 'AND']];
                                    $set = [
                                        'nama' => $nama,
                                        'nama_org' => $nama_org,
                                        //'photo'=>$nama,
                                        'ket' => $ket,
                                        'kontak_person' => $kontak_person,
                                        'font_size' => $font,
                                        'warna_tbl' => $warna_tbl,
                                    ];
                                    if ($_FILES['file']) {
                                        $file = $Fungsi->importFile($jenis, $kd_proyek = '');
                                        if ($file['result'] == 'ok') {
                                            $set['photo'] = $file['file'];
                                        }
                                    }
                                    $kodePosting = 'cek_insert';
                                    //var_dump($file);
                                    break;
                                default:
                                    # code...
                                    break;
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
                        default:
                            $code = 406;
                    }
                    //JENIS POST DATA/INSERT DATA
                    switch ($kodePosting) {
                        case 'add_field_json':
                            //ambil data
                            $data_klm = $DB->readJSONField($tabel_pakai, $nama_kolom, $jenis_kelompok, $dataKondisiField); //sdh ok
                            // Menghapus tanda kutip tunggal yang tidak valid
                            //cari index di array
                            $key = false;
                            if ($data_klm) {
                                $data_klm = json_decode($data_klm, true);
                                $key = array_search($uraian_field, $data_klm, true);
                                $kode_Field = 'updateJSONField';
                            } else {
                                $data_klm = array();
                                $kode_Field  = 'insertJSONField';
                            }
                            if ($key === false) {
                                $data_klm[] = $uraian_field;
                                $uraian_field_insert = json_encode($data_klm);
                                // var_dump($uraian_field_insert);
                                if ($kode_Field  == 'insertJSONField') {
                                    $tambah = $DB->insertJSONField($tabel_pakai, $nama_kolom, $uraian_field_insert, $jenis_kelompok, $dataKondisiField);
                                } else {
                                    $tambah = $DB->updateJSONField($tabel_pakai, $nama_kolom, $uraian_field_insert, $jenis_kelompok, $dataKondisiField);
                                }
                                if ($tambah) {
                                    $code = 3;
                                } else {
                                    $code = 33;
                                }
                            } else {
                                $code = 37;
                            }
                            break;
                        case 'update_rows': // untuk banyak row
                            foreach ($dataArray as $key => $val) {
                                $kondisi = [['kd_proyek', '=', $kd_proyek], ['id', '=', $id, 'AND']];
                                $set = [
                                    'value' => $value,
                                    'item' => $item,
                                    'keterangan' => $keterangan,
                                    'sebutan_lain' => $sebutan_lain
                                ];
                                $resul = $DB->update_array($tabel_pakai, $set, $kondisi);
                                //var_dump($DB->count());
                                $jumlahArray = is_array($DB->count()) ? count($DB->count()) : 0;
                                if ($DB->count()) {
                                    $code = 3;
                                    $data['update'] = $DB->count();
                                } else {
                                    $code = 47;
                                }
                            }
                            break;
                        case 'update_row': //untuk 1 row
                            $resul = $DB->update_array($tabel_pakai, $set, $kondisi);
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            //var_dump($resul);
                            //var_dump($DB->count());
                            if ($DB->count()) {
                                $code = 3;
                                $data['update'] = $DB->count();
                            } else {
                                $code = 33;
                            }
                            break;
                        case 'cek_insert': //cek data klo tidak ada teruskan insert
                            $ListRow = $DB->select_array($tabel_pakai, $kondisi, $columnName);
                            $jumlahArray = is_array($ListRow) ? count($ListRow) : 0;
                            if ($jumlahArray) {
                                //update row
                                $hasilUpdate = $DB->update_array($tabel_pakai, $set, $kondisi);
                                //var_dump($hasilUpdate);
                                if ($DB->count()) {
                                    $code = 3;
                                    $data['update'] = $DB->count(); //$DB->count();
                                } else {
                                    $code = 33;
                                }
                            } else {
                                // inser row
                                $resul = $DB->insert($tabel_pakai, $set);
                                $data['note']['add row'] = $DB->lastInsertId();
                                $code = 2;
                            }
                            break;
                        case 'cekdouble_insert': //cek data klo tidak ada teruskan insert jika ada jangan update
                            $resul = $DB->getWhereCustom($tabel_pakai, $kondisi);
                            //var_dump(sizeof($resul));
                            $jumlahArray = is_array($resul) ? count($resul) : 0;
                            if ($jumlahArray) {
                                $code = 37;
                            } else {
                                // inser row
                                $resul = $DB->insert($tabel_pakai, $set);
                                $data['note']['add row'] = $DB->lastInsertId();
                                $code = 2;
                            }
                            break;
                        case 'insert_json':
                            $resul = $DB->runQuery($query, $bindValue);
                            break;
                        case 'insert':
                            $resul = $DB->insert($tabel_pakai, $set);
                            if ($DB->lastInsertId()) {
                                $data['note']['add row'] = $DB->lastInsertId(); //$resul->count;
                                $code = 2;
                            } else {
                                $code = 32;
                            }
                            break;
                        default:
                            # code...
                            break;
                    }
                    $hasilServer = hasilServer[$code];
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
        }
        $tambahanNote = (is_array($tambahan_pesan)) ? implode($tambahan_pesan) : $tambahan_pesan;
        $item = array('code' => $code, 'message' => hasilServer[$code] . ", " . $tambahanNote, "note" => $tambahan_pesan);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        // return json_encode($json);
        return json_encode($json, JSON_HEX_APOS);
    }
}
