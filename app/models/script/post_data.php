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
            foreach ($rowUsername as $key => $value) {
                ${$key} = $value;
            }
            $tahun = (int) $rowUsername->tahun;
            $kd_wilayah = $rowUsername->kd_wilayah;
            $kd_opd = $rowUsername->kd_organisasi;
            $id_user = $rowUsername->id;
            $rowPengaturan = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
            if ($rowPengaturan != false) {
                foreach ($rowPengaturan as $key => $value) {
                    ${$key} = $value;
                }
            }
        } else {
            $id_user = 0;
            $code = 407;
        }

        if (!empty($_POST) && $id_user > 0 && $code != 407) {
            $code = 11;
            if (isset($_POST['jenis']) && isset($_POST['tbl'])) {
                $code = 12;
                $validate = new Validate($_POST);
                $validate_temp = new Validate($_POST); //untuk tanggal dan mengakali lainnya
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
                switch ($jenis) {
                    case 'upload':
                        switch ($tbl) {
                            case 'daftar_paket':
                                $val_in_array = ['file_kontrak', 'file_addendum', 'file_pho', 'file_fho', 'file_laporan', 'file_dokumentasi0', 'file_dokumentasi50', 'file_dokumentasi100'];
                                break;
                            case 'asn':
                                $val_in_array = ['file_akta_lahir', 'file_ktp', 'file_kk', 'file_npwp', 'file_buku_nikah', 'file_karpeg', 'file_karsi_karsu', 'file_photo', 'pend_file_sd', 'pend_file_smp', 'pend_file_smu', 'pend_file_s1', 'pend_file_s2', 'pend_file_s3'];
                                break;
                            default:
                                # code...
                                break;
                        }
                        $id_row = $validate->setRules('id_row', 'dokumen', [
                            'required' => true,
                            'numeric' => true,
                            'min_char' => 1
                        ]);
                        $dok = $validate->setRules('dok', 'jenis dokumen', [
                            'required' => true,
                            'sanitize' => 'string',
                            'min_char' => 1,
                            'in_array' => $val_in_array
                        ]);
                        break;
                    case 'unkunci':
                    case 'unsetujui':
                    case 'kunci':
                    case 'setujui':
                        if ($type_user === 'admin') {
                            $tahun_dokumen = $validate->setRules('tahun', 'tahun dokumen', [
                                'required' => true,
                                'numeric' => true,
                                'max_char' => 4,
                                'min_char' => 4
                            ]);
                        } else {
                            $tahun_dokumen = $validate->setRules('nabiilainayah_ok', 'anda bukan admin', [
                                'required' => true,
                                'numeric' => true,
                                'max_char' => 8,
                                'min_char' => 9
                            ]);
                        }
                        break;
                    case 'edit':
                        switch ($tbl) {
                            case 'profil':
                                $type_user = $validate->setRules('type_user', 'type user', [
                                    'sanitize' => 'string',
                                    'min_char' => 2, //on-off
                                    'in_array' => ['user', 'admin', 'super']
                                ]);
                                break;
                            case 'value':
                                # code...
                                break;
                            default:
                                # code...
                                break;
                        }
                        $id_row = $validate->setRules('id_row', 'id', [
                            'required' => true,
                            'numeric' => true,
                            'min_char' => 1
                        ]);
                    case 'add':
                        switch ($tbl) {
                            case 'berita':
                                $kelompok = $validate->setRules('kelompok', 'kelompok', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inArray' => ['berita', 'pelayanan', 'data_teknis', 'organisasi','anggaran']
                                ]);
                                $judul = $validate->setRules('judul', 'Judul Berita', [
                                    'del_2_spasi' => true,
                                    'sanitize' => 'string',
                                    'required' => true,
                                ]);
                                $tanggal = $validate->setRules('tanggal', 'tanggal berita', [
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'required' => true,
                                    'max_char' => 100,
                                    'min_char' => 8
                                ]);
                                $uraian_html = $validate->setRules('uraian_html', 'Markup HTML', [
                                    'del_2_spasi' => true,
                                    'required' => true,
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'del_2_spasi' => true,
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $urutan = $validate->setRules('urutan', 'Nomor Urut', [
                                    'numeric' => true,
                                    'required' => true
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            case 'asn':
                                $nama = $validate->setRules('nama', 'nama', [
                                    'del_2_spasi' => true,
                                    'sanitize' => 'string',
                                    'required' => true,
                                ]);
                                $gelar = $validate->setRules('gelar', 'gelar', [
                                    'del_2_spasi' => true,
                                    'sanitize' => 'string'
                                ]);
                                $jabatan = $validate->setRules('jabatan', 'alamat', [
                                    'sanitize' => 'string'
                                ]);
                                $nip = $validate->setRules('nip', 'nip', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'max_char' => 18,
                                    'min_char' => 18,
                                    // 'unique' => ['db_asn_pemda_neo', 'nip']
                                ]);
                                $golongan = $validate->setRules('golongan', 'golongan', [
                                    'max_char' => 1,
                                    'min_char' => 1,
                                    'numeric' => true,
                                    'required' => true,
                                    'inArray' => [1, 2, 3, 4]
                                ]);
                                $ruang = $validate->setRules('ruang', 'golongan', [
                                    'strtolower' => true,
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inArray' => ['a', 'b', 'c', 'd', 'e'],
                                    'max_char' => 1,
                                    'min_char' => 1
                                ]);
                                $t4_lahir = $validate->setRules('t4_lahir', 'tempat lahir', [
                                    'strtolower' => true,
                                    'sanitize' => 'string'
                                ]);
                                $tgl_lahir = $validate->setRules('tgl_lahir', 'tanggal lahir', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                    'min_char' => 8
                                ]);

                                $agama = $validate->setRules('agama', 'golongan', [
                                    'strtolower' => true,
                                    'sanitize' => 'string',
                                    'inArray' => ['islam', 'kristen', 'katolik', 'protestan', 'yahudi', 'budha', 'konghucu', 'hindu', 'kepercayaan']
                                ]);
                                $kelamin = $validate->setRules('kelamin', 'golongan', [
                                    'strtolower' => true,
                                    'sanitize' => 'string',
                                    'inArray' => ['pria', 'wanita']
                                ]);
                                $jenis_kepeg = $validate->setRules('jenis_kepeg', 'jenis kepegawaian', [
                                    'strtolower' => true,
                                    'sanitize' => 'string',
                                    'inArray' => ['pnsp', 'pnsd1', 'pnsd2', 'pnsp_dpb1', 'pnsp_dpb2', 'pnsp_dpk1', 'pnsp_dpk2', 'pnsd_dpb_pusat', 'pnsd_dpk_pusat', 'swasta']
                                ]);
                                $status_kepeg = $validate->setRules('status_kepeg', 'status kepegawaian', [
                                    'strtolower' => true,
                                    'sanitize' => 'string',
                                    'inArray' => ['capeg', 'peg_tetap', 'mpp', 'pen_uang_tunggu', 'peg_seorsing', 'cuti', 'peg_sementara', 'peg_bulanan']
                                ]);
                                $status = $validate->setRules('status', 'status kepegawaian', [
                                    'strtolower' => true,
                                    'sanitize' => 'string',
                                    'inArray' => ['duda', 'janda', 'duda-janda', 'lajang', 'menikah']
                                ]);
                                $no_ktp = $validate->setRules('no_ktp', 'ktp', [
                                    'preg_replace' => ["/[^0-9]/", ""],
                                    'sanitize' => 'string',
                                ]);
                                $npwp = $validate->setRules('npwp', 'npwp', [
                                    'preg_replace' => ["/[^0-9]/", ""],
                                    'sanitize' => 'string',
                                ]);
                                $alamat = $validate->setRules('alamat', 'alamat', [
                                    'del_2_spasi' => true,
                                    'sanitize' => 'string'
                                ]);
                                $kontak_person = $validate->setRules('kontak_person', 'kontak person', [
                                    'preg_replace' => ["/[^0-9]/", ""],
                                    'sanitize' => 'string'
                                ]);
                                // $kontak_person = preg_replace("/[^0-9]/", "", $getData[15]);
                                $email = $validate->setRules('email', 'email', [
                                    'sanitize' => 'string',
                                    'email' => true
                                ]);
                                $kelompok = $validate->setRules('kelompok', 'kelompok', [
                                    'numeric' => true,
                                    'inArray' => [1, 2, 3, 4, 5, 6]
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'del_2_spasi' => true,
                                    'sanitize' => 'string'
                                ]);
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            case 'daftar_paket':
                                $id_uraian = $validate->setRules('id_uraian', 'uraian paket', [
                                    'min_char' => 8,
                                    'required' => true,
                                    'json_repair' => true
                                ]);

                                $uraian = $validate->setRules('uraian', 'uraian', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 4
                                ]);
                                $uraian = preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian);
                                $volume = $validate->setRules('volume', 'volume', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 1
                                ]);
                                $satuan = $validate->setRules('satuan', 'satuan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['satuan_neo', 'value', [['value', "=", $_POST['satuan']]]]
                                ]);
                                $id_rekanan = $validate->setRules('id_rekanan', 'Rekanan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['rekanan_neo', 'id', [['id', "=", $_POST['id_rekanan']], ['kd_wilayah', '=', $kd_wilayah, 'AND']]]
                                ]);
                                $kondisi = [['id', '=', $id_rekanan], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['disable', '<=', 0, 'AND']];
                                $row_sub = $DB->getWhereOnceCustom('rekanan_neo', $kondisi);
                                $nama_rekanan = '';
                                if ($row_sub !== false) {
                                    $nama_rekanan = $row_sub->nama_perusahaan;
                                }
                                $metode_pengadaan = $validate->setRules('metode_pengadaan', 'metode pengadaan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'in_array' => ['penyedia', 'swakelola']
                                ]);

                                if ($metode_pengadaan === 'penyedia') {
                                    $metode_pemilihan = $validate->setRules('metode_pemilihan', 'metode pengadaan', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'in_array' => ['e_purchasing', 'pengadaan_langsung', 'penunjukan', 'tender_cepat', 'tender', 'seleksi']
                                    ]);
                                    $pengadaan_penyedia = $validate->setRules('pengadaan_penyedia', 'pengadaan penyedia', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'in_array' => ['barang', 'konstruksi', 'konsultansi', 'konsultansi_non_konst', 'jasa_lainnya']
                                    ]);
                                    switch ($pengadaan_penyedia) {
                                        case 'barang':
                                        case 'jasa_lainnya':
                                            $jns_kontrak = $validate->setRules('jns_kontrak', 'jenis kontrak', [
                                                'sanitize' => 'string',
                                                'required' => true,
                                                'in_array' => ['lumsum', 'harga_satuan', 'gabungan_lum_sat', 'biaya_plus_imbalan', 'payung']
                                            ]);
                                            break;
                                        case 'konstruksi':
                                            $jns_kontrak = $validate->setRules('jns_kontrak', 'jenis kontrak', [
                                                'sanitize' => 'string',
                                                'required' => true,
                                                'in_array' => ['lumsum', 'harga_satuan', 'gabungan_lum_sat', 'biaya_plus_imbalan', 'putar_kunci']
                                            ]);
                                            break;
                                        case 'konsultansi':
                                            $jns_kontrak = $validate->setRules('jns_kontrak', 'jenis kontrak', [
                                                'sanitize' => 'string',
                                                'required' => true,
                                                'in_array' => ['lumsum', 'waktu_penugasan']
                                            ]);
                                            break;
                                        case 'konsultansi_non_konst':
                                            $jns_kontrak = $validate->setRules('jns_kontrak', 'jenis kontrak', [
                                                'sanitize' => 'string',
                                                'required' => true,
                                                'in_array' => ['lumsum', 'waktu_penugasan', 'payung']
                                            ]);
                                            break;
                                    }
                                } else {
                                    //SWAKELOLA
                                    $metode_pemilihan = $validate->setRules('metode_pemilihan', 'metode pengadaan', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'in_array' => ['sw_type_1', 'sw_type_2', 'sw_type_3', 'sw_type_4']
                                    ]);
                                    $pengadaan_penyedia = $validate->setRules('pengadaan_penyedia', 'pengadaan penyedia', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'in_array' => ['swakelola']
                                    ]);
                                    $jns_kontrak = $validate->setRules('jns_kontrak', 'jenis kontrak', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'in_array' => ['swakelola']
                                    ]);
                                }
                                //akali tanggal 
                                $tgl_kontrak = '0000-00-00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_kontrak'] ?? '') > 7) {
                                    $tgl_kontrak = $validate->setRules('tgl_kontrak', 'tanggal kontrak', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                        'min_char' => 7
                                    ]);
                                }
                                $no_kontrak = $validate->setRules('no_kontrak', 'nomor kontrak', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_persiapan_kont = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_persiapan_kont'] ?? '') > 7) {
                                    $tgl_persiapan_kont = $validate->setRules('tgl_persiapan_kont', 'tanggal persiapan kontrak', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_persiapan_kont = $validate->setRules('no_persiapan_kont', 'nomor persiapan kontrak', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_spmk = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_spmk'] ?? '') > 7) {
                                    $tgl_spmk = $validate->setRules('tgl_spmk', 'tanggal spmk', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_spmk = $validate->setRules('no_spmk', 'nomor SPMK', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_undangan = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_undangan'] ?? '') > 7) {
                                    $tgl_spmk = $validate->setRules('tgl_undangan', 'tanggal undangan', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_undangan = $validate->setRules('no_undangan', 'nomor Undangan/Pengumuman', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_penawaran = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_penawaran'] ?? '') > 7) {
                                    $tgl_penawaran = $validate->setRules('tgl_penawaran', 'tanggal penawaran', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_penawaran = $validate->setRules('no_penawaran', 'nomor Penawaran', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_nego = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_nego'] ?? '') > 7) {
                                    $tgl_nego = $validate->setRules('tgl_nego', 'tanggal negoisasi', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_nego = $validate->setRules('no_nego', 'nomor Negoisasi', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_sppbj = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_sppbj'] ?? '') > 7) {
                                    $tgl_sppbj = $validate->setRules('tgl_sppbj', 'tanggal SPPBJ', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_sppbj = $validate->setRules('no_sppbj', 'nomor SPPBJ', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_pho = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_pho'] ?? '') > 7) {
                                    $tgl_pho = $validate->setRules('tgl_pho', 'tanggal PHO', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_pho = $validate->setRules('no_pho', 'nomor PHO', [
                                    'sanitize' => 'string'
                                ]);
                                $tgl_fho = '0000-00-00 00:00:00'; //'0000-00-00 00:00:00'
                                if (strlen($_POST['tgl_fho'] ?? '') > 7) {
                                    $tgl_fho = $validate->setRules('tgl_fho', 'tanggal FHO', [
                                        'sanitize' => 'string',
                                        'required' => true,
                                        'regexp' => '/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$/',
                                        'min_char' => 8
                                    ]);
                                }
                                $no_fho = $validate->setRules('no_fho', 'nomor FHO', [
                                    'sanitize' => 'string'
                                ]);
                                $keterangan = $validate->setRules('keterangan', 'keterangan', [
                                    'sanitize' => 'string'
                                ]);
                                $nama_ppk = $validate->setRules('nama_ppk', 'nama ppk', [
                                    'sanitize' => 'string'
                                ]);
                                $nip_ppk = $validate->setRules('nip_ppk', 'nip ppk', [
                                    'sanitize' => 'string'
                                ]);
                                $nama_pptk = $validate->setRules('nama_pptk', 'nama pptk', [
                                    'sanitize' => 'string'
                                ]);
                                $nip_pptk = $validate->setRules('nip_pptk', 'nip pptk', [
                                    'sanitize' => 'string'
                                ]);
                                $waktu_pelaksanaan = $validate->setRules('waktu_pelaksanaan', 'waktu pelaksanaan', [
                                    'numeric_zero' => true,
                                    'sanitize' => 'string'
                                ]);
                                $waktu_pemeliharaan = $validate->setRules('waktu_pemeliharaan', 'waktu pemeliharaan', [
                                    'numeric_zero' => true,
                                    'sanitize' => 'string'
                                ]);
                                $kd_rup = $validate->setRules('kd_rup', 'Kode RUP', [
                                    'sanitize' => 'string'
                                ]);
                                $kd_paket = $validate->setRules('kd_paket', 'Kode Paket', [
                                    'sanitize' => 'string'
                                ]);
                                $renc_output = $validate->setRules('renc_output', 'Output Rencana', [
                                    'sanitize' => 'string'
                                ]);
                                $output = $validate->setRules('output', 'output paket', [
                                    'sanitize' => 'string'
                                ]);
                                $addendum = '{}';
                                $disable = $validate->setRules('disable', 'disable', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                    'in_array' => ['off', 'on']
                                ]);
                                $disable = ($disable == 'on') ? 1 : 0;
                                break;
                            case 'pengaturan':
                                $tahun = $validate->setRules('tahun', 'tahun anggaran', [
                                    'required' => true,
                                    'numeric' => true,
                                    'min_char' => 4,
                                    'max_char' => 4
                                ]);
                                $tahun_renstra = $validate->setRules('tahun_renstra', 'tahun renstra', [
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
                                $aturan_organisasi = $validate->setRules('aturan_organisasi', 'Peraturan Organisasi', [
                                    'inDB' => ['peraturan_neo', 'id', [['id', '=', (int)$_POST['aturan_organisasi']]]],
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
                                $id_opd_tampilkan = $validate->setRules('id_opd_tampilkan', 'organisasi yang ditampilkan', [
                                    'numeric_zero' => true,
                                ]);
                                $id_opd_tampilkan = (int)$id_opd_tampilkan;
                                if ($id_opd_tampilkan > 0) {
                                    $id_opd_tampilkan = $validate->setRules('id_opd_tampilkan', 'organisasi yang ditampilkan', [
                                        'inDB' => ['organisasi_neo', 'id', [['id', '=', (int)$_POST['id_opd_tampilkan']]]],
                                        'required' => true,
                                        'numeric' => true,
                                        'min_char' => 1
                                    ]);
                                }

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
                            case 'rekanan':
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
                            case 'peraturan':
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
                            case 'organisasi':
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
                            case 'renstra':
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
                            case 'tujuan_sasaran_renstra':
                            case 'sasaran_renstra':
                            case 'tujuan_renstra':
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
                            case 'sub_keg_dpa':
                            case 'sub_keg_renja':
                                $kd_sub_keg = $validate->setRules('kd_sub_keg', 'sub kegiatan', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'inDB' => ['sub_kegiatan_neo', 'kode', [['kode', '=', $_POST['kd_sub_keg']]]],
                                    'min_char' => 4
                                ]);
                                //$kel_rek
                                $tolak_ukur_capaian_keg = $validate->setRules('tolak_ukur_capaian_keg', 'tolak_ukur_capaian_keg', [
                                    'sanitize' => 'string'
                                ]);
                                $target_kinerja_capaian_keg = $validate->setRules('target_kinerja_capaian_keg', 'target_kinerja_capaian_keg', [
                                    'sanitize' => 'string'
                                ]);
                                $tolak_ukur_keluaran = $validate->setRules('tolak_ukur_keluaran', 'tolak_ukur_keluaran', [
                                    'sanitize' => 'string'
                                ]);
                                $target_kinerja_keluaran = $validate->setRules('target_kinerja_keluaran', 'target_kinerja_keluaran', [
                                    'sanitize' => 'string'
                                ]);
                                $tolak_ukur_hasil = $validate->setRules('tolak_ukur_hasil', 'tolak_ukur_hasil', [
                                    'sanitize' => 'string'
                                ]);
                                $target_kinerja_hasil = $validate->setRules('target_kinerja_hasil', 'target_kinerja_hasil', [
                                    'sanitize' => 'string'
                                ]);
                                $tolak_ukur_capaian_keg_p = $validate->setRules('tolak_ukur_capaian_keg_p', 'tolak_ukur_capaian_keg perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $target_kinerja_capaian_keg_p = $validate->setRules('target_kinerja_capaian_keg_p', 'target_kinerja_capaian_keg perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $tolak_ukur_keluaran_p = $validate->setRules('tolak_ukur_keluaran_p', 'tolak_ukur_keluaran perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $target_kinerja_keluaran_p = $validate->setRules('target_kinerja_keluaran_p', 'target_kinerja_keluaran perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $tolak_ukur_hasil_p = $validate->setRules('tolak_ukur_hasil_p', 'tolak_ukur_hasil perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $target_kinerja_hasil_p = $validate->setRules('target_kinerja_hasil_p', 'target_kinerja_hasil perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $keluaran_sub_keg = $validate->setRules('keluaran_sub_keg', 'keluaran_sub_keg', [
                                    'sanitize' => 'string'
                                ]);
                                $keluaran_sub_keg_p = $validate->setRules('keluaran_sub_keg_p', 'keluaran sub keg perubahan', [
                                    'sanitize' => 'string'
                                ]);
                                $sumber_dana_temp = $validate->setRules('sumber_dana', 'sumber dana', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 1
                                ]);
                                $explodeAwal = explode(',', $sumber_dana_temp);
                                foreach ($explodeAwal as $key => $row) {
                                    $dataRslt = $DB->getWhereOnceCustom('sumber_dana_neo', [['kode', '=', trim($row)]]);
                                    if ($dataRslt === false) {
                                        unset($explodeAwal[$key]);
                                    }
                                }
                                $sumber_dana = preg_replace('/(\s\s+|\t|\n)/', ' ', implode(',', $explodeAwal));
                                $jumlah_pagu = $validate->setRules('jumlah_pagu', 'jumlah pagu', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
                                ]);
                                $jumlah_pagu_p = $validate->setRules('jumlah_pagu_p', 'jumlah pagu perubahan', [
                                    'sanitize' => 'string',
                                    'numeric' => true,
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
                            case 'asb':
                            case 'sbu':
                            case 'hspk':
                            case 'ssh':
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
                            case 'dpa':
                            case 'renja':
                            case 'dppa':
                            case 'renja_p':
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
                                    'inLikeConcatDBMultiple' => [$tabel_pakai_temporerSubkeg, 'sumber_dana',  [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]]
                                ]);
                                // var_dump($sumber_dana);
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
                                $untuk_paksa_error = $validate->setRules('inayah_nabiila45557', 'jenis', [
                                    'sanitize' => 'string',
                                    'required' => true,
                                    'min_char' => 200
                                ]);
                                break;
                        }
                        break;
                    case 'add_field_json':
                        switch ($tbl) {
                            case 'sub_keg_dpa':
                            case 'sub_keg_renja':
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
                                # code...
                                break;
                        }
                        break;
                    default:
                        switch ($tbl) {
                            case 'dpa':
                            case 'renja':
                            case 'dppa':
                            case 'renja_p':
                                break;
                            default:
                                $untuk_paksa_error = $validate->setRules('inayah_nabiila45557', 'jenis', [
                                    'required' => true,
                                    'min_char' => 200
                                ]);
                                break;
                        }
                        break;
                }
                switch ($tbl) {
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
                //==============================
                //FINISH PROSES VALIDASI
                //==============================
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
                    //=======================
                    //========JENIS==========
                    //=======================
                    switch ($jenis) {
                        case 'upload':
                            $set_file = [];
                            switch ($tbl) {
                                case 'daftar_paket':
                                    $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'cek_insert';
                                    $set = [];
                                    break;
                                case 'asn':
                                    $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['id', '=', $id_row, 'AND']];
                                    $kodePosting = 'cek_insert';
                                    $set = [];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            $row_sub = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
                            if ($row_sub) {
                                switch ($tbl) {
                                    case 'daftar_paket':
                                        $uraian = "kd_paket({$row_sub->id})_dok({$dok})_wilayah({$kd_wilayah})";
                                        $set_file  = ['nama_file' => $uraian, 'dok' => $dok];
                                        if (strlen($row_sub->$dok ?? '') > 5) {
                                            $nameFileDel = $row_sub->$dok;
                                            $set_file  = ['nama_file' => $uraian, 'nameFileDel' => $nameFileDel, 'dok' => $dok];
                                        }

                                        break;
                                    case 'asn':
                                        $uraian = "nip({$row_sub->nip})_dok({$dok})_wilayah({$kd_wilayah})";
                                        $set_file  = ['nama_file' => $uraian, 'dok' => $dok];
                                        if (strlen($row_sub->$dok ?? '') > 2) {
                                            $nameFileDel = $row_sub->$dok;
                                            $set_file  = ['nama_file' => $uraian, 'nameFileDel' => $nameFileDel, 'dok' => $dok];
                                        }
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                if ($_FILES) {
                                    if (isset($_FILES[$dok])) {
                                        $file = $Fungsi->importFile($tbl, $set_file);
                                        if ($file['result'] == 'ok') {
                                            $set[$dok] = $file[$dok];
                                        } else {
                                            $tambahan_pesan = "(" . $file[$dok] . ")";
                                        }
                                    }
                                }
                                // var_dump($set);
                            } else {
                                $jenis = '';
                                $code = 404;
                            }

                            break;
                        case 'edit':
                            $kondisi = [['id', '=', $id_row]];
                            $kodePosting = 'update_row';
                            switch ($tbl) {
                                case 'value':
                                    break;
                                case 'value':
                                    break;
                                default:
                                    break;
                            }
                            if ($tbl == 2) {
                                break;
                            }
                        case 'add':
                            switch ($tbl) {
                                case 'berita':
                                    $kondisi = [['judul', '=', $judul], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['tanggal', '=', $tanggal, 'AND']];
                                    $kodePosting = 'cek_insert';
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'judul' => $judul,
                                        'kelompok' => $kelompok,
                                        'uraian_html' => $uraian_html,
                                        'urutan' => $urutan,
                                        'tanggal' => $tanggal,
                                        'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                        'disable' => $disable,
                                        'tgl_update' => date('Y-m-d H:i:s'),
                                        'username_update' => $_SESSION["user"]["username"]
                                    ];
                                    if ($jenis === 'add') {
                                        $set['username']=$_SESSION["user"]["username"];
                                        $set['tgl_insert']=date('Y-m-d H:i:s');
                                    }
                                    break;
                                case 'asn':
                                    $kondisi = [['nip', '=', $nip], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND']];
                                    $kodePosting = 'cek_insert';
                                    $set = [
                                        'kd_wilayah' => $kd_wilayah,
                                        'kd_opd' => $kd_opd,
                                        'nama' => $nama,
                                        'gelar' => $gelar,
                                        'kelompok' => $kelompok,
                                        'jabatan' => $jabatan,
                                        'nip' => $nip,
                                        'golongan' => $golongan,
                                        'ruang' => $ruang,
                                        't4_lahir' => $t4_lahir,
                                        'tgl_lahir' => $tgl_lahir,
                                        'agama' => $agama,
                                        'kelamin' => $kelamin,
                                        'jenis_kepeg' => $jenis_kepeg,
                                        'status_kepeg' => $status_kepeg,
                                        'no_ktp' => $no_ktp,
                                        'npwp' => $npwp,
                                        'alamat' => $alamat,
                                        'kontak_person' => $kontak_person,
                                        'email' => $email,
                                        'status' => $status,
                                        // 'no_buku_nikah' => $no_buku_nikah,
                                        // 'tgl_nikah' => $tgl_nikah,
                                        // 'nama_anak' => $nama_anak,
                                        // 'nik_anak' => $nik_anak,
                                        // 'nama_ayah' => $nama_ayah,
                                        // 'nama_ibu' => $nama_ibu,
                                        // 'nama_pasangan' => $nama_pasangan,
                                        // 'no_karpeg' => $no_karpeg,
                                        // 'tgl_karpeg' => $tgl_karpeg,
                                        // 'no_taspen' => $no_taspen,
                                        // 'tgl_taspen' => $tgl_taspen,
                                        // 'no_karsi_karsu' => $no_karsi_karsu,
                                        // 'tgl_karsi_karsu' => $tgl_karsi_karsu,
                                        // 'nmr_sk_terakhir' => $nmr_sk_terakhir,
                                        // 'tgl_sk_terakhir' => $tgl_sk_terakhir,
                                        // 'pj_sk_terakhir' => $pj_sk_terakhir,
                                        // 'nmr_sk_cpns' => $nmr_sk_cpns,
                                        // 'tgl_sk_cpns' => $tgl_sk_cpns,
                                        // 'pj_sk_cpns' => $pj_sk_cpns,
                                        // 'nmr_sk_pns' => $nmr_sk_pns,
                                        // 'tgl_sk_pns' => $tgl_sk_pns,
                                        // 'pj_sk_pns' => $pj_sk_pns,
                                        // 'pend_sekolah_sd' => $pend_sekolah_sd,
                                        // 'pend_ijasah_sd' => $pend_ijasah_sd,
                                        // 'pend_tgl_tmt_sd' => $pend_tgl_tmt_sd,
                                        // 'pend_t4_sd' => $pend_t4_sd,
                                        // 'pend_sekolah_smp' => $pend_sekolah_smp,
                                        // 'pend_ijasah_smp' => $pend_ijasah_smp,
                                        // 'pend_tgl_tmt_smp' => $pend_tgl_tmt_smp,
                                        // 'pend_t4_smp' => $pend_t4_smp,
                                        // 'pend_sekolah_smu' => $pend_sekolah_smu,
                                        // 'pend_ijasah_smu' => $pend_ijasah_smu,
                                        // 'pend_tgl_tmt_smu' => $pend_tgl_tmt_smu,
                                        // 'pend_t4_smu' => $pend_t4_smu,
                                        // 'pend_sekolah_akhir' => $pend_sekolah_akhir,
                                        // 'pend_ijasah_akhir' => $pend_ijasah_akhir,
                                        // 'pend_tgl_tmt_akhir' => $pend_tgl_tmt_akhir,
                                        // 'pend_t4_akhir' => $pend_t4_akhir,
                                        // 'sk_pangkat_terakhir' => $sk_pangkat_terakhir,
                                        // 'tgl_tmt_akhir' => $tgl_tmt_akhir,
                                        'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                        'disable' => $disable,
                                        'tanggal' => date('Y-m-d H:i:s'),
                                        'tgl_update' => date('Y-m-d H:i:s'),
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                    break;
                                case 'daftar_paket':
                                    //
                                    $send = json_decode($id_uraian);
                                    // atur kembali id uraian
                                    $pagu = 0;
                                    $jumlah = 0;
                                    $kd_sub_keg = [];
                                    $kumpulanRowSub = [];
                                    $kumpulanRowSub_del = [];
                                    foreach ($send as $key => $value) {
                                        $tabel_pakaiku = $Fungsi->tabel_pakai($value->dok_anggaran)['tabel_pakai'];
                                        $kondisi1 = [['id', '=', $value->id], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['kel_rek', '=', 'uraian', 'AND']];
                                        $row_sub = $DB->getWhereOnceCustom($tabel_pakaiku, $kondisi1);
                                        if ($row_sub !== false) {
                                            //$kumpulanRowSub[] ini insert atau update  di daftar_uraian_paket atau hapus data tidak ada disini
                                            $klm_jumlah = ($tabel_pakaiku == 'dpa_neo') ? 'jumlah' : 'jumlah_p';

                                            $row_sub->dok = $value->dok_anggaran;
                                            $row_sub->nilai_kont = $row_sub->$klm_jumlah;
                                            $row_sub->vol_kontrak = $value->vol_kontrak;
                                            $row_sub->sat_kontrak = $value->sat_kontrak;



                                            $id_dok_anggaran = $value->id;
                                            $kumpulanRowSub[] = $row_sub;

                                            $pagu += $row_sub->$klm_jumlah;
                                            $jumlah += $value->val_kontrak;
                                            $kd_sub_keg[] = $row_sub->kd_sub_keg;
                                            // tambahkan row di tabel daftar_uraian_paket tiap item disini jika sdh ada update rows
                                        } else {
                                            //hapus key yang tidak mempunyai persyaratan
                                            $kumpulanRowSub_del[] = $value;
                                            unset($send[$key]);
                                        }
                                    }
                                    $kd_sub_keg = implode(',', $kd_sub_keg);
                                    // var_dump($row_sub);
                                    $id_uraian = $send;
                                    if ($jenis == 'add') {
                                        $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['uraian', '=', $uraian, 'AND']];
                                        $kodePosting = 'cek_insert';
                                    } else if ($jenis == 'edit') {
                                        $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['uraian', '=', $uraian, 'AND'], ['id', '=', $id_row, 'AND']];
                                    }
                                    if ($jumlah > $pagu) {
                                        # buat kesalahan bahwa jumlah(kontrak) tidak bisa lebih besar dari pagu
                                        $kodePosting = '';
                                        $code = 405;
                                        $tambahan_pesan = '(nilai jumlah kontrak lebih besar dari besaran pagu)';
                                    } else {
                                        // var_dump(json_encode($id_uraian));
                                        $set = [
                                            'kd_rup' => preg_replace('/(\s\s+|\t|\n)/', '', $kd_rup),
                                            'kd_paket' => preg_replace('/(\s\s+|\t|\n)/', '', $kd_paket),
                                            'kd_wilayah' => $kd_wilayah,
                                            'kd_opd' => $kd_opd,
                                            'tahun' => $tahun,
                                            'uraian' => preg_replace('/(\s\s+|\t|\n)/', ' ', $uraian),
                                            'id_uraian' => json_encode($id_uraian),
                                            'kd_sub_keg' => $kd_sub_keg,
                                            'volume' => $volume,
                                            'satuan' => $satuan,
                                            'jumlah' => $jumlah,
                                            'pagu' => $pagu,
                                            'metode_pengadaan' => $metode_pengadaan,
                                            'metode_pemilihan' => $metode_pemilihan,
                                            'pengadaan_penyedia' => $pengadaan_penyedia,
                                            'jns_kontrak' => $jns_kontrak,
                                            'renc_output' => $renc_output,
                                            'output' => $output,
                                            'id_rekanan' => $id_rekanan,
                                            'nama_rekanan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $nama_rekanan),
                                            'nama_ppk' => preg_replace('/(\s\s+|\t|\n)/', ' ', $nama_ppk),
                                            'nip_ppk' => $nip_ppk,
                                            'nama_pptk' => preg_replace('/(\s\s+|\t|\n)/', ' ', $nama_pptk),
                                            'waktu_pelaksanaan' => $waktu_pelaksanaan,
                                            'waktu_pemeliharaan' => $waktu_pemeliharaan,
                                            'nip_pptk' => $nip_pptk,
                                            'tgl_kontrak' => $tgl_kontrak,
                                            'no_kontrak' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_kontrak),
                                            'tgl_persiapan_kont' => $tgl_persiapan_kont,
                                            'no_persiapan_kont' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_persiapan_kont),
                                            'tgl_spmk' => $tgl_spmk,
                                            'no_spmk' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_spmk),
                                            'addendum' => $addendum,
                                            'tgl_undangan' => $tgl_undangan,
                                            'no_undangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_undangan),
                                            'tgl_penawaran' => $tgl_penawaran,
                                            'no_penawaran' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_penawaran),
                                            'tgl_nego' => $tgl_nego,
                                            'no_nego' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_nego),
                                            'tgl_sppbj' => $tgl_sppbj,
                                            'no_sppbj' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_sppbj),
                                            'tgl_pho' => $tgl_pho,
                                            'no_pho' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_pho),
                                            'tgl_fho' => $tgl_fho,
                                            'no_fho' => preg_replace('/(\s\s+|\t|\n)/', ' ', $no_fho),
                                            'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                            'disable' => $disable,
                                            'username' => $username,
                                            'tanggal' => date('Y-m-d H:i:s'),
                                            'username' => $_SESSION["user"]["username"]
                                        ];
                                    }

                                    break;
                                case 'profil':
                                    switch ($jenis) {
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
                                        'peraturan' => $id_aturan_anggaran,
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
                                    $kode = "$nomor:$tgl_pengundangan";
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
                                        'tahun_renstra' => $tahun_renstra,
                                        'aturan_anggaran' => $aturan_anggaran,
                                        'aturan_pengadaan' => $aturan_pengadaan,
                                        'aturan_organisasi' => $aturan_organisasi,
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
                                        'id_opd_tampilkan' => $id_opd_tampilkan,
                                        'username' => $_SESSION["user"]["username"]
                                    ];
                                    if ($jenis == 'add') {
                                        // $set['']=1;
                                    }
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
                                case 'sub_keg_dpa':
                                case 'sub_keg_renja':
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
                                        'tolak_ukur_capaian_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_capaian_keg),
                                        'target_kinerja_capaian_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_capaian_keg),
                                        'tolak_ukur_keluaran' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_keluaran),
                                        'target_kinerja_keluaran' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_keluaran),
                                        'tolak_ukur_hasil' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_hasil),
                                        'target_kinerja_hasil' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_hasil),
                                        'tolak_ukur_capaian_keg_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_capaian_keg_p),
                                        'target_kinerja_capaian_keg_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_capaian_keg_p),
                                        'tolak_ukur_keluaran_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_keluaran_p),
                                        'target_kinerja_keluaran_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_keluaran_p),
                                        'tolak_ukur_hasil_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $tolak_ukur_hasil_p),
                                        'target_kinerja_hasil_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $target_kinerja_hasil_p),
                                        'keluaran_sub_keg' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keluaran_sub_keg),
                                        'keluaran_sub_keg_p' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keluaran_sub_keg_p),
                                        'sumber_dana' => $sumber_dana,
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
                                case 'dppa':
                                case 'renja_p':
                                case 'dpa':
                                case 'renja':
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
                                            $kolomHarga_satuan = 'harga_satuan';
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
                                    // var_dump($sumber_dana);
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
                                    if ($jenis == 'edit') {
                                        $dinamic['id_row'] = $id_row;
                                    }
                                    $insertKodeRek = $Fungsi->kelolaRekSubKegDanAkun($dinamic);
                                    $data = $insertKodeRek;
                                    $kodePosting = '';
                                    break;
                                case 'asb':
                                case 'sbu':
                                case 'hspk':
                                case 'ssh':
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
                                        'peraturan' => ${"aturan_$tbl"},
                                        'keterangan' => preg_replace('/(\s\s+|\t|\n)/', ' ', $keterangan),
                                        'disable' => 0,
                                        
                                    ];
                                    if ($jenis == 'add') {
                                        $set['tanggal']=date('Y-m-d H:i:s');
                                        $set['username']=$_SESSION["user"]["username"];
                                        $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_aset', '=', $kd_aset, 'AND'], ['tahun', '=', $tahun, 'AND']];
                                        $kodePosting = 'cek_insert';
                                    }
                                    
                                    break;
                                case 'value':
                                    break;
                                case 'value':
                                    break;
                                case 'value':
                                    break;
                                default:
                                    break;
                            }
                            if ($_FILES) {
                                if (isset($_FILES['file'])) {
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
                        case 'unkunci':
                            switch ($tbl) {
                                case 'renstra':
                                case 'renja':
                                case 'renja_p':
                                case 'dpa':
                                case 'dppa':
                                    $set = ["kunci_$tbl" => 0];
                                    break;
                                default:
                                    break;
                            }
                            $tabel_pakai = 'pengaturan_neo';
                            $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun_dokumen, 'AND']];
                            if ($tbl == 2) {
                                break;
                            }
                            $kodePosting = 'update_row';
                            break;
                        case 'unsetujui':
                            
                            $set = ["setujui_$tbl" => 0];
                            $tabel_pakai = 'pengaturan_neo';
                            $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun_dokumen, 'AND']];
                            $kodePosting = 'update_row';
                            break;
                        case 'kunci':
                            $tabel_pakai = 'pengaturan_neo';
                            $set = ["kunci_$tbl" =>1];
                            $kodePosting = 'update_row';
                            $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun_dokumen, 'AND']];
                            break;
                        case 'setujui':
                            $tabel_pakai = 'pengaturan_neo';
                            switch ($tbl) {
                                case 'dppa':
                                    $tablePosting = '';

                                    break;
                                case 'renja_p'://renja p ke dppa
                                    $tabel_from = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                    $tablePosting = 'dppa_neo';
                                    $columnName = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kd_akun`, `kel_rek`, `objek_belanja`, `uraian`, `jenis_kelompok`, `kelompok`, `jenis_standar_harga`, `id_standar_harga`, `komponen`, `spesifikasi`, `tkdn`, `pajak`, `harga_satuan`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `keterangan`, `disable`, `tanggal`, `tgl_update`, `username_input`, `username_update`, `vol_1_p`, `vol_2_p`, `vol_3_p`, `vol_4_p`, `vol_5_p`, `sat_1_p`, `sat_2_p`, `sat_3_p`, `sat_4_p`, `sat_5_p`, `volume_p`, `jumlah_p`, `sumber_dana_p`, `id_dpa`, `id_renja_p`";
                                    $columnSelect = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kd_akun`, `kel_rek`, `objek_belanja`, `uraian`, `jenis_kelompok`, `kelompok`, `jenis_standar_harga`, `id_standar_harga`, `komponen`, `spesifikasi`, `tkdn`, `pajak`, `harga_satuan`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `keterangan`, `disable`, `tanggal`, `tgl_update`, `username_input`, `username_update`, `vol_1_p`, `vol_2_p`, `vol_3_p`, `vol_4_p`, `vol_5_p`, `sat_1_p`, `sat_2_p`, `sat_3_p`, `sat_4_p`, `sat_5_p`, `volume_p`, `jumlah_p`, `sumber_dana_p`, `id_dpa`, `id`";
                                    break;
                                case 'dpa'://dpa ke renja_p
                                    $tabel_from = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                    $tablePosting = 'renja_p_neo';
                                    $columnName = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kd_akun`, `kel_rek`, `objek_belanja`, `uraian`, `jenis_kelompok`, `kelompok`, `jenis_standar_harga`, `id_standar_harga`, `komponen`, `spesifikasi`, `tkdn`, `pajak`, `harga_satuan`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `keterangan`, `disable`, `tanggal`, `tgl_update`, `username_input`, `username_update`, `vol_1_p`, `vol_2_p`, `vol_3_p`, `vol_4_p`, `vol_5_p`, `sat_1_p`, `sat_2_p`, `sat_3_p`, `sat_4_p`, `sat_5_p`, `volume_p`, `jumlah_p`, `sumber_dana_p`, `id_dpa`";
                                    $columnSelect = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kd_akun`, `kel_rek`, `objek_belanja`, `uraian`, `jenis_kelompok`, `kelompok`, `jenis_standar_harga`, `id_standar_harga`, `komponen`, `spesifikasi`, `tkdn`, `pajak`, `harga_satuan`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `keterangan`, `disable`, `tanggal`, `tgl_update`, `username_input`, `username_update`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `id`";
                                    $tablePosting2 = 'sub_keg_dpa_neo';
                                    $tabel_pakai2 = 'sub_keg_renja_neo';
                                    break;
                                case 'renja'://renja ke dpa
                                    $tabel_from = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
                                    $tablePosting = 'dpa_neo';
                                    $columnName = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kd_akun`, `kel_rek`, `objek_belanja`, `uraian`, `jenis_kelompok`, `kelompok`, `jenis_standar_harga`, `id_standar_harga`, `komponen`, `spesifikasi`, `tkdn`, `pajak`, `harga_satuan`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `keterangan`, `disable`, `tanggal`, `tgl_update`, `username_input`, `username_update`, `id_renja`";
                                    $columnSelect = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kd_akun`, `kel_rek`, `objek_belanja`, `uraian`, `jenis_kelompok`, `kelompok`, `jenis_standar_harga`, `id_standar_harga`, `komponen`, `spesifikasi`, `tkdn`, `pajak`, `harga_satuan`, `vol_1`, `vol_2`, `vol_3`, `vol_4`, `vol_5`, `sat_1`, `sat_2`, `sat_3`, `sat_4`, `sat_5`, `volume`, `jumlah`, `sumber_dana`, `keterangan`, `disable`, `tanggal`, `tgl_update`, `username_input`, `username_update`, `id`";
                                    //ut tabel sub_keg_dpa_neo
                                    $tablePosting2 = 'sub_keg_dpa_neo';
                                    $tabel_pakai2 = 'sub_keg_renja_neo';
                                    $columnName2 = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kel_rek`, `uraian`, `tolak_ukur_capaian_keg`, `target_kinerja_capaian_keg`, `tolak_ukur_keluaran`, `target_kinerja_keluaran`, `tolak_ukur_hasil`, `target_kinerja_hasil`, `tolak_ukur_capaian_keg_p`, `target_kinerja_capaian_keg_p`, `tolak_ukur_keluaran_p`, `target_kinerja_keluaran_p`, `tolak_ukur_hasil_p`, `target_kinerja_hasil_p`, `sumber_dana`, `lokasi`, `keluaran_sub_keg`, `keluaran_sub_keg_p`, `awal_pelaksanaan`, `akhir_pelaksanaan`, `jumlah_pagu`, `jumlah_pagu_p`, `jumlah_rincian`, `jumlah_rincian_p`, `disable`, `aksi`, `keterangan`, `kelompok_json`, `keterangan_json`, `tanggal`, `tgl_update`, `username`";
                                    $columnSelect2 = "`kd_wilayah`, `kd_opd`, `tahun`, `kd_sub_keg`, `kel_rek`, `uraian`, `tolak_ukur_capaian_keg`, `target_kinerja_capaian_keg`, `tolak_ukur_keluaran`, `target_kinerja_keluaran`, `tolak_ukur_hasil`, `target_kinerja_hasil`, `tolak_ukur_capaian_keg_p`, `target_kinerja_capaian_keg_p`, `tolak_ukur_keluaran_p`, `target_kinerja_keluaran_p`, `tolak_ukur_hasil_p`, `target_kinerja_hasil_p`, `sumber_dana`, `lokasi`, `keluaran_sub_keg`, `keluaran_sub_keg_p`, `awal_pelaksanaan`, `akhir_pelaksanaan`, `jumlah_pagu`, `jumlah_pagu_p`, `jumlah_rincian`, `jumlah_rincian_p`, `disable`, `aksi`, `keterangan`, `kelompok_json`, `keterangan_json`, `tanggal`, `tgl_update`, `username`";
                                    break;
                                case 'value':
                                    break;
                                default:
                                    break;
                            }
                            $kondisi_delete = [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun_dokumen, 'AND']];
                            $kondisi_insert_select = [['kd_wilayah', '= ?', $kd_wilayah], ['tahun', '= ?', $tahun_dokumen, 'AND']];
                            $set = ["setujui_$tbl" => 1];
                            $kodePosting = 'update_row';
                            $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun_dokumen, 'AND']];
                            // posting ke dpa, renja_p, dppa
                            break;
                        case 'add_field_json':
                            switch ($tbl) {
                                case 'sub_keg_dpa':
                                case 'sub_keg_renja':
                                    $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                                    $kodePosting = $jenis;
                                    break;
                                case 'dppa':
                                case 'renja_p':
                                case 'dpa':
                                case 'renja':
                                    $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                                    $kodePosting = $jenis;
                                    break;
                                case 'value':
                                    break;
                                case 'value':
                                    break;
                                default:
                                    break;
                            }
                            break;
                        default:
                            switch ($tbl) {
                                case 'value':
                                    break;
                                case 'value':
                                    break;
                                default:
                                    break;
                            }
                            break;
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
                            // var_dump($kondisi);
                            //var_dump($DB->count());
                            if ($DB->count()) {
                                $code = 3;
                                $data['update'] = $DB->count();
                                switch ($jenis) {
                                    case 'setujui':
                                    case 'kunci':
                                        // $set = [$jenis . '_' . $tbl => 1];
                                    case 'unkunci':
                                    case 'unsetujui':
                                        // if ($jenis == 'unkunci' || $jenis == 'unsetujui') {
                                        //     $jenisnya = $jenis;
                                        //     $jenisnya = substr($jenisnya, 2); //hilangkan un depan kata
                                        //     $set = [$jenisnya . '_' . $tbl => 0];
                                        // }
                                        // // update di tabel pengaturan
                                        // $tabel_pengaturan = 'pengaturan_neo';
                                        // $resul_pengaturan = $DB->update_array($tabel_pengaturan, $set, $kondisi);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                                switch ($jenis) {
                                    case 'setujui':
                                        switch ($tbl) {
                                            case 'dpa':
                                            case 'renja':
                                            case 'renja_p':
                                                // hapus dahulu sebelum insert
                                                $resul = $DB->delete_array($tablePosting, $kondisi_delete);
                                                $resul = $DB->insert_select($tabel_from, $tablePosting, $columnName, $columnSelect, $kondisi_insert_select);
                                                if ($tbl == 'renja') {
                                                    $resul = $DB->delete_array($tablePosting2, $kondisi_delete);
                                                    $resul = $DB->insert_select($tabel_pakai2, $tablePosting2, $columnName2, $columnSelect2, $kondisi_insert_select);
                                                }
                                                // var_dump($resul);
                                                break;

                                            default:
                                                # code...
                                                break;
                                        }
                                        break;
                                    case 'value1':
                                        #code...
                                        break;
                                    default:
                                        #code...
                                        break;
                                };
                            } else {
                                $code = 33;
                            }
                            break;
                        case 'cek_insert': //cek data klo tidak ada teruskan insert
                            $ListRow = $DB->select_array($tabel_pakai, $kondisi, $columnName);
                            // var_dump($ListRow);
                            $jumlahArray = is_array($ListRow) ? count($ListRow) : 0;
                            if ($jumlahArray) {
                                //update row
                                $DB->update_array($tabel_pakai, $set, $kondisi);
                                //var_dump($hasilUpdate);
                                $id_row_paket = $ListRow[0]->id;
                                if ($DB->count()) {
                                    $code = 3;
                                    $data['update'] = $DB->count(); //$DB->count();

                                } else {
                                    $code = 33;
                                }
                            } else {
                                // inser row
                                switch ($jenis) {
                                    case 'add':
                                        switch ($tbl) {
                                            case 'pengaturan':
                                                $set['kunci_renstra'] = 1;
                                                $set['kunci_renja'] = 1;
                                                $set['kunci_dpa'] = 1;
                                                $set['kunci_renja_p'] = 1;
                                                $set['kunci_dppa'] = 1;
                                                break;
                                            default:
                                                break;
                                        }
                                        break;
                                    default:
                                        break;
                                }
                                $resul = $DB->insert($tabel_pakai, $set);
                                $data['note']['add row'] = $DB->lastInsertId();
                                $id_row_paket = $data['note']['add row'];
                                $code = 2;
                            }
                            switch ($jenis) {
                                case 'edit':
                                    switch ($tbl) {
                                        case 'daftar_paket':
                                            $kondisi1 = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id_paket', '=', $id_row, 'AND']];
                                            //ambil data ruas dengan id paket
                                            $row_sub2 = $DB->getWhereCustom('daftar_uraian_paket', $kondisi1);
                                            if ($row_sub2 !== false) {
                                                // foreach ($row_sub2 as $key => $value) {
                                                // }
                                            }
                                    }
                                case 'add':
                                    switch ($tbl) {
                                        case 'daftar_paket': //@audit sekarang pemaketan
                                            // $id_row_paket = $ListRow->id;
                                            //data paket jika ada
                                            //compare dengan $kumpulanRowSub[] 
                                            foreach ($kumpulanRowSub as $key => $value) {
                                                $id_dok_anggaran = $value->id;
                                                $dok = $value->dok;
                                                $kd_sub_keg = $value->kd_sub_keg;
                                                $kd_akun = $value->kd_akun;
                                                $kel_rek = $value->kel_rek;
                                                $klm_jumlah = ($dok == 'dpa') ? 'jumlah' : 'jumlah_p';
                                                $jumlah_pagu = $value->$klm_jumlah;
                                                $jumlah_kontrak = $value->nilai_kont;
                                                $vol_kontrak = $value->vol_kontrak;
                                                $sat_kontrak = $value->sat_kontrak;
                                                $kondisi_cari = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id_dok_anggaran', '=', $id_dok_anggaran, 'AND']];
                                                $row_cari = $DB->getWhereOnceCustom('daftar_uraian_paket', $kondisi_cari);
                                                // var_dump($row_cari);
                                                //set value 
                                                $set_insert = [
                                                    'id_paket' => $id_row_paket, // $id_row,
                                                    'id_dok_anggaran' => $id_dok_anggaran,
                                                    'dok' => $dok,
                                                    'tahun' => $tahun,
                                                    'kd_wilayah' => $kd_wilayah,
                                                    'kd_opd' => $kd_opd,
                                                    'kd_sub_keg' => $kd_sub_keg,
                                                    'kd_akun' => $kd_akun,
                                                    'kel_rek' => $kel_rek,
                                                    'jumlah_pagu' => $jumlah_pagu,
                                                    'jumlah_kontrak' => $jumlah_kontrak,
                                                    'vol_kontrak' => $vol_kontrak,
                                                    'sat_kontrak' => $sat_kontrak,
                                                    // 'realisasi_vol'=>$realisasi_vol,
                                                    // 'realisasi_jumlah'=>$realisasi_jumlah,
                                                    // 'keterangan'=>$keterangan
                                                ];
                                                //jika false tambahkan baris
                                                if ($row_cari === false) {
                                                    $resul = $DB->insert('daftar_uraian_paket', $set_insert);
                                                } else {
                                                    $resul = $DB->update_array('daftar_uraian_paket', $set_insert, $kondisi_cari);
                                                }
                                            }
                                            break;
                                        case 'value':
                                            break;
                                        default:
                                            break;
                                    }
                                    break;
                                default:
                                    # code...
                                    break;
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
