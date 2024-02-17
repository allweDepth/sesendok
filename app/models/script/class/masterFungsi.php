<?php
// require_once("class/FormulaParser.php");
// require 'init.php';

use FormulaParser\FormulaParser;

class MasterFungsi
{
    //get tabel data
    public function getTabel($tbl = '', $nama_tabel = '', $get_data = [], $jmlhalaman = 0, $halaman = 1, $jumlah_kolom = 1, $type_user = 'user')
    {
        //var_dump("dmn($get_data)");
        //ambil data user untuk warna
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $id_user = $_SESSION["user"]["id"];
        $DB = DB::getInstance();
        $userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
        $jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
        $classRow = '';
        $Fungsi = new MasterFungsi();
        if ($jumlahArray > 0) {
            foreach ($userAktif[0] as $key => $value) {
                ${$key} = $value;
            }
        }
        if ($warna_tbl != '' && $warna_tbl != 'non') {
            $classRow = ' class="' . $warna_tbl . '"';
        }
        $pagination = '';
        $pagination1 = '';
        $pagination = '';
        $paginationnext = '';
        $pagination2 = '';
        $rowData = ['tbody' => '', 'tfoot' => ''];

        //var_dump($nama_tabel,$get_data, $jmlhalaman , $halaman,$jumlah_kolom);
        //var_dump($jumlah_kolom);
        //$rowData['sumData'] =sizeof($get_data);
        $warna = 'green';
        //var_dump("dmn($myrow)");
        // jika tabel mengganti thead
        switch ($tbl) {
            case 'dppa':
            case 'renja_p':
            case 'dpa':
            case 'renja':
                $rowData['thead'] = trim('<tr>
                            <th>KODE KOMPONEN</th>
                            <th>URAIAN</th>
                            <th>KOEFISIEN</th>
                            <th>HARGA SATUAN</th>
                            <th>TOTAL</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
                break;
            case 'sub_keg_dpa':
            case 'sub_keg_renja':
                $rowData['thead'] = trim('<tr>
                            <th>KODE KOMPONEN</th>
                            <th>URAIAN</th>
                            <th>PAGU ANGGARAN</th>
                            <th>JUMLAH RINCIAN</th>
                            <th>PAGU ANGGARAN PERUBAHAN</th>
                            <th>JUMLAH RINCIAN PERUBAHAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
                break;
            case 'renstra':
                $rowData['thead'] = '<tr class="center aligned">
                <th rowspan="3">Tujuan</th>
                <th rowspan="3">Sasaran</th>
                <th rowspan="3">Kode</th>
                <th rowspan="3">Program dan Kegiatan</th>
                <th rowspan="3">Satuan</th>
                <th rowspan="3">Indikator Kinerja Tujuan, Sasaran,Program (Outcome)dan Kegiatan(Output)</th>
                <th rowspan="3">Data Capaian Awal Perenc anaan</th>
                <th colspan="11">Target Kinerja Program dan Kerangka Pendanaan</th>
                <th class="collapsing" rowspan="3">AKSI</th>
            </tr>
            <tr class="center aligned">
                <th colspan="2">Tahun-1</th>
                <th colspan="2">Tahun-2</th>
                <th colspan="2">Tahun-3</th>
                <th colspan="2">Tahun-4</th>
                <th colspan="2">Tahun-5</th>
                <th rowspan="2">Kondisi Kinerja Pada Akhir Periode Renstra Perangkat Daerah</th>
            </tr>
            <tr class="center aligned">
                <th>Target</th>
                <th>Rp.</th>
                <th>Target</th>
                <th>Rp.</th>
                <th>Target</th>
                <th>Rp.</th>
                <th>Target</th>
                <th>Rp.</th>
                <th>Target</th>
                <th>Rp.</th>
            </tr>';
                break;
            case 'tujuan_sasaran_renstra':
                $rowData['thead'] = trim('<tr>
                            <th>KELOMPOK</th>
                            <th>URAIAN</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
                break;
            case 'rekanan':
                $rowData['thead'] = '<tr class="center aligned">
                <th rowspan="2">URAIAN</th>
                <th rowspan="2">ALAMAT</th>
                <th rowspan="2">NPWP</th>
                <th rowspan="2">DIREKTUR</th>
                <th colspan="4">AKTA PENDIRIAN</th>
                <th rowspan="2" class="collapsing">FILE</th>
                <th rowspan="2" class="collapsing">KETERANGAN</th>
                <th class="collapsing" rowspan="2">AKSI</th>
            </tr>';
                break;
            case 'hspk':
            case 'sbu':
            case 'asb':
            case 'ssh':
                //Kode Komponen	Uraian Komponen	Spesifikasi	Satuan	Harga Satuan	TKDN	Aksi
                $rowData['thead'] = trim('<tr>
                                <th class="collapsing">Kode Komponen</th>
                                <th>Uraian Komponen</th>
                                <th>Spesifikasi</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>TKDN</th>
                                <th class="collapsing">AKSI</th>
                            </tr>');
                break;
            case 'wilayah':
                $rowData['thead'] = trim('<tr class="center aligned">
                            <th rowspan="2" class="collapsing">Kode</th>
                            <th rowspan="2">Uraian</th>
                            <th rowspan="2">Status</th>
                            <th colspan="3">Jumlah</th>
                            <th rowspan="2">Luas Wilayah (km2)</th>
                            <th rowspan="2">Jumlah Penduduk (jiwa)</th>
                            <th rowspan="2">keterangan</th>
                            <th rowspan="2" class="collapsing">AKSI</th>
                        </tr>
                        <tr class="center aligned">
                            <th>KEC</th>
                            <th>KEL</th>
                            <th>DESA</th>
                    </tr>');
                break;
            case 'satuan':
                $rowData['thead'] = trim('<tr>
                            <th>VALUE</th>
                            <th>ITEM</th>
                            <th>SEBUTAN LAIN</th>
                            <th>KETERANGAN</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
                break;
            case 'organisasi':
                $rowData['thead'] = trim('<tr>
                            <th class="collapsing">Kode</th>
                            <th>Uraian</th>
                            <th>keterangan</th>
                            <th class="collapsing">AKSI</th>
                        </tr>');
                break;
            case 'mapping':
                $rowData['thead'] = trim('<tr>
                        <th class="collapsing">Kode Neraca</th>
                        <th>Uraian Neraca</th>
                        <th>Kode Akun</th>
                        <th>Uraian Akun</th>
                        <th>kelompok</th>
                        <th>keterangan</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
                break;
            case 'sumber_dana':
                $rowData['thead'] = trim('<tr><th class="collapsing">KODE KOMPONEN</th>
                <th>KOMPONEN</th>
                <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
                break;
            case 'aset':
            case 'akun_belanja':
                $rowData['thead'] = trim('<tr>
                        <th class="collapsing">KODE KOMPONEN</th>
                        <th>KOMPONEN</th>
                        <th>KETERANGAN</th>
                        <th class="collapsing">AKSI</th>
                    </tr>');
                break;
            case 'bidang_urusan':
            case 'prog':
            case 'keg':
            case 'sub_keg':
                $rowData['thead'] = trim('<tr>
                <th class="collapsing">KODE KOMPONEN</th>
                <th>NOMENKLATUR URUSAN</th>
                <th>KINERJA</th>
                <th>INDIKATOR</th>
                <th>SATUAN</th>
                <th>KETERANGAN</th>
                <th class="collapsing">AKSI</th>
            </tr>');
                break;

            case 'user':
                break;
            default:
                # code...
                break;
        }
        if (isset($rowData['thead'])) {
            $rowData['thead'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $rowData['thead']);
        }
        $jumlahArray = is_array($get_data) ? count($get_data) : 0;
        if ($jumlahArray > 0) {

            $myrow = 0;
            foreach ($get_data as $row) {
                $myrow++;
                switch ($tbl) {
                    case 'sub_keg_dpa':
                    case 'sub_keg_renja':
                        $tbl_button = ($tbl == 'sub_keg_renja') ? 'renja' : 'dpa';
                        $tbl_button_p = ($tbl == 'sub_keg_renja') ? 'renja_p' : 'dppa';
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';

                        if ($disable_anggaran <= 0) {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
                            $buttonEdit = ($row->kel_kd_sub_keg == 'kd_sub_keg') ? '<div class="ui icon top left pointing lainnya dropdown button">
                            <i class="wrench icon"></i>
                                <div class="menu">
                                    <div class="item" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i>Edit</div>
                                    <div class="divider"></div>
                                    <a class="item" data-tab="tab_renja" name="get_tbl" jns="rincian_pokok" tbl="' . $tbl_button . '"><div class="ui red empty circular label"></div>Rincian</a>
                                    <a class="item" data-tab="tab_renja" name="get_tbl" jns="rincian_perubahan" tbl="' . $tbl_button_p . '"><div class="ui red empty circular label"></div>Rincian Perubahan</a>
                                    <div class="item">Help</div>
                                </div>
                            </div>' : '<button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>' ;
                            $buttons = '<div class="ui icon basic mini buttons">'.$buttonEdit.'<button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kd_sub_keg">' . $row->kd_sub_keg . '</td>
                                    <td klm="uraian">' .  $row->uraian .  '</td>
                                    <td klm="jumlah_pagu">' . $divAwalAngka  . number_format($row->jumlah_pagu , 2, ',', '.'). $divAkhir .  '</td>
                                    <td klm="jumlah_rincian">' . $divAwalAngka  . number_format($row->jumlah_rincian, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="jumlah_pagu_p">' . $divAwalAngka  . number_format($row->jumlah_pagu, 2, ',', '.') . $divAkhir .  '</td>
                                    <td klm="jumlah_rincian_p">' . $divAwalAngka  . number_format($row->jumlah_rincian, 2, ',', '.') . $divAkhir .  '</td>
                                    
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'renstra':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        $divAwalAngka = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $divAwalAngka  = '<div contenteditable rms onkeypress="return rumus(event);">';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        };
                        // $desimal = ($this->countDecimals($row->koefisien) < 2) ? 2 : $this->countDecimals($row->koefisien);
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="tujuan">' .  $row->tujuan . '</td>
                                    <td klm="sasaran">' .  $row->sasaran . '</td>
                                    <td klm="kode">' .  $row->kode . '</td>
                                    <td klm="uraian_prog_keg">' .  $row->uraian_prog_keg . '</td>
                                    
                                    <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
                                    <td klm="indikator">' . $divAwal . $row->indikator . $divAkhir . '</td>
                                    <td klm="data_capaian_awal">' . $divAwalAngka . number_format($row->data_capaian_awal, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="target_thn_1">' . $divAwalAngka . number_format($row->target_thn_1, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="dana_thn_1">' . $divAwalAngka . number_format($row->dana_thn_1, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="target_thn_2">' . $divAwalAngka . number_format($row->target_thn_2, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="dana_thn_2">' . $divAwalAngka . number_format($row->dana_thn_2, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="target_thn_3">' . $divAwalAngka . number_format($row->target_thn_3, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="dana_thn_3">' . $divAwalAngka . number_format($row->dana_thn_3, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="target_thn_4">' . $divAwalAngka . number_format($row->target_thn_4, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="dana_thn_4">' . $divAwalAngka . number_format($row->dana_thn_4, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="target_thn_5">' . $divAwalAngka . number_format($row->target_thn_5, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="dana_thn_5">' . $divAwalAngka . number_format($row->dana_thn_5, 2, ',', '.') . $divAkhir . '</td>
                                    <td klm="kondisi_akhir">' .  number_format($row->kondisi_akhir, 2, ',', '.') . '</td>
                                    
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'tujuan_sasaran_renstra':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';

                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kelompok">' . $divAwal . $row->kelompok . $divAkhir . '</td>
                                    <td klm="text">' . $divAwal . $row->text . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'rekanan':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';

                        $file = $row->file;
                        $fileTag = '';
                        if (strlen($file)) {
                            $fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
                        }
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row"  jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nama_perusahaan">' . $divAwal . $row->nama_perusahaan . $divAkhir . '</td>
                                    <td klm="alamat">' . $divAwal . $row->alamat . $divAkhir . '</td>
                                    <td klm="npwp">' . $divAwal . $row->npwp . $divAkhir . '</td>
                                    <td klm="direktur">' . $divAwal . $row->direktur . $divAkhir . '</td>
                                    <td klm="no_akta_pendirian">' . $divAwal . $row->no_akta_pendirian . $divAkhir . '</td>
                                    <td klm="tgl_akta_pendirian">' . $row->tgl_akta_pendirian  . '</td>
                                    <td klm="lokasi_notaris_pendirian">' . $divAwal . $row->lokasi_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="nama_notaris_pendirian">' . $divAwal . $row->nama_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'hspk':
                    case 'sbu':
                    case 'asb':
                    case 'ssh':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                                <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }

                        $rowData['tbody'] .= preg_replace('/(\s\s+|\t|\n)/', ' ', '<tr id_row="' . $row->id . '">
                        <td klm="kd_aset">' . $divAwal . $row->kd_aset . $divAkhir . '</td>
                        <td klm="uraian_barang">' . $divAwal . $row->uraian_barang . $divAkhir . '</td>
                        <td klm="spesifikasi">' . $divAwal . $row->spesifikasi . $divAkhir . '</td>
                        <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
                        <td klm="harga_satuan">' . $divAwal . $row->harga_satuan . $divAkhir . '</td>
                        <td klm="tkdn">' . $divAwal . $row->tkdn . $divAkhir . '</td>
                        <td>' . $buttons . '</td>
                    </tr>');
                        break;
                    case 'satuan':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="value">' . $divAwal . $row->value . $divAkhir . '</td>
                                    <td klm="item">' . $divAwal . $row->item . $divAkhir . '</td>
                                    <td klm="sebutan_lain">' . $divAwal . $row->sebutan_lain . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'wilayah':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $file = $row->file;
                        $fileTag = '';
                        if (strlen($file)) {
                            $fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                    <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                    <td klm="status">' . $divAwal . $row->status . $divAkhir . '</td>
                                    <td klm="jml_kec">' . $divAwal . $row->jml_kec . $divAkhir . '</td>
                                    <td klm="jml_kel">' . $divAwal . $row->jml_kel . $divAkhir . '</td>
                                    <td klm="jml_desa">' . $divAwal . $row->jml_desa . $divAkhir . '</td>
                                    <td klm="luas">' . $divAwal . $row->luas . $divAkhir . '</td>
                                    <td klm="penduduk">' . $divAwal . $row->penduduk . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $fileTag . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'peraturan':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';

                        $file = $row->file;
                        $fileTag = '';
                        if (strlen($file)) {
                            $fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
                        }
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nomor">' . $divAwal . $row->nomor . $divAkhir . '</td>
                                    <td klm="judul">' . $divAwal . $row->judul . $divAkhir . '</td>
                                    <td klm="tgl_pengundangan">' . $divAwal . $row->tgl_pengundangan . $divAkhir . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $fileTag . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;
                    case 'sumber_dana':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                                <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        // $k = ((int)$row->kelompok > 0) ? $row->kelompok : '';
                        // $j = ((int)$row->jenis > 0) ? $row->jenis : '';
                        // $o = ((int)$row->objek > 0) ? $Fungsi->zero_pad($row->objek, 2) : '';
                        // $ro = ((int)$row->rincian_objek > 0) ? $Fungsi->zero_pad($row->rincian_objek, 2) : '';
                        // $sro = ((int)$row->sub_rincian_objek > 0) ? $Fungsi->zero_pad($row->sub_rincian_objek, 2) : '';
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                        <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                        <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                        <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                        <td>' . $buttons . '</td>
                                    </tr>');
                        break;
                    case 'aset':
                    case 'akun_belanja':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                                    <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                    <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        // $n = ((int)$row->kelompok > 0) ? $row->kelompok : '';
                        // $g = ((int)$row->jenis > 0) ? $row->jenis : '';
                        // $s = ((int)$row->objek > 0) ? $Fungsi->zero_pad($row->objek, 2) : '';
                        // $se = ((int)$row->rincian_objek > 0) ? $Fungsi->zero_pad($row->rincian_objek, 2) : '';
                        // $sr = ((int)$row->sub_rincian_objek > 0) ? $Fungsi->zero_pad($row->sub_rincian_objek, 2) : '';

                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                            <td klm="sub_rincian_objek">' . $divAwal . $row->kode . $divAkhir . '</td>
                                            <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                            <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                            <td>' . $buttons . '</td>
                                        </tr>');
                        break;
                    case 'bidang_urusan':
                    case 'prog':
                    case 'keg':
                    case 'sub_keg':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                                        <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                        <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                                <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                                <td klm="nomenklatur_urusan">' . $divAwal . $row->nomenklatur_urusan . $divAkhir . '</td>
                                                <td klm="kinerja">' . $divAwal . $row->kinerja . $divAkhir . '</td>
                                                <td klm="indikator">' . $divAwal . $row->indikator . $divAkhir . '</td>
                                                <td klm="satuan">' . $divAwal . $row->satuan . $divAkhir . '</td>
                                                <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                                <td>' . $buttons . '</td>
                                            </tr>');
                        break;
                    case 'mapping':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                                            <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                            <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                                    <td klm="kode_aset">' . $divAwal . $row->kode_aset . $divAkhir . '</td>
                                                    <td klm="uraian_aset">' . $divAwal . $row->uraian_aset . $divAkhir . '</td>
                                                    <td klm="kode_akun">' . $divAwal . $row->kode_akun . $divAkhir . '</td>
                                                    <td klm="uraian_akun">' . $divAwal . $row->uraian_akun . $divAkhir . '</td>
                                                    <td klm="kelompok">' . $divAwal . $row->kelompok . $divAkhir . '</td>
                                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                                    <td>' . $buttons . '</td>
                                                </tr>');
                        break;
                    case 'organisasi':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                                                <button class="ui button" name="flyout" name="flyout" jns="edit" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                                <button class="ui red button" name="del_row" jns="del_row" tbl="' . $tbl . '" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                                        <td klm="kode">' . $divAwal . $row->kode . $divAkhir . '</td>
                                                        <td klm="uraian">' . $divAwal . $row->uraian . $divAkhir . '</td>
                                                        <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                                        <td>' . $buttons . '</td>
                                                    </tr>');
                        break;
                    case 'rekanan':
                        $buttons = '';
                        $divAwal = '';
                        $divAkhir = '';

                        $file = $row->file;
                        $fileTag = '';
                        if (strlen($file)) {
                            $fileTag = '<a class="ui primary label" href="' . $file . '" target="_blank">Ungguh</a>';
                        }
                        if ($type_user == 'admin') {
                            $divAwal = '<div contenteditable>';
                            $divAkhir = '</div>';
                            $buttons = '<div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="' . $jenis . '" tbl="edit" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                            <button class="ui red button" name="del_row" jns="' . $jenis . '" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button></div>';
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nama_perusahaan">' . $divAwal . $row->nama_perusahaan . $divAkhir . '</td>
                                    <td klm="alamat">' . $divAwal . $row->alamat . $divAkhir . '</td>
                                    <td klm="npwp">' . $divAwal . $row->npwp . $divAkhir . '</td>
                                    <td klm="direktur">' . $divAwal . $row->direktur . $divAkhir . '</td>
                                    <td klm="no_akta_pendirian">' . $divAwal . $row->no_akta_pendirian . $divAkhir . '</td>
                                    <td klm="tgl_akta_pendirian">' . $row->tgl_akta_pendirian  . '</td>
                                    <td klm="lokasi_notaris_pendirian">' . $divAwal . $row->lokasi_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="nama_notaris_pendirian">' . $divAwal . $row->nama_notaris_pendirian . $divAkhir . '</td>
                                    <td klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $divAwal . $row->keterangan . $divAkhir . '</td>
                                    <td>' . $buttons . '</td>
                                </tr>');
                        break;

                    case 'divisiSDA':
                        $nama_tabel = $tbl;
                        if ($type_user == 'admin') {
                            $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="kode"><div contenteditable>' . $row->kode . '</div></td>
                                    <td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td>
                                    <td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td>
                                    <td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>
                                    <td>
                                        <div class="ui icon basic mini buttons">
                                            <button class="ui button" name="flyout" name="flyout" jns="' . $jenis . '" tbl="edit" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                            <button class="ui red button" name="del_row" jns="' . $jenis . '" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>
                                        </div>
                                    </td>
                                </tr>');
                        } else {
                            $rowData['tbody'] .= '<tr>
                            <td>' . $row->kode . '</td>
                            <td>' . $row->uraian . '</td>
                            <td>' . $row->satuan . '</td>
                            <td>' . $row->keterangan . '</td>
                            <td></td>
                        </tr>';
                        }
                        break;
                    case 'user':
                        $phpdate = strtotime($row->tgl_daftar);
                        $mysqldate = date('d-m-Y H:i:s', $phpdate);
                        $phpdate2 = strtotime($row->tgl_login);
                        $mysqldate2 = date('d-m-Y H:i:s', $phpdate2);
                        $aktif = ($row->aktif > 0) ? 'checked="checked"' : '';
                        $aktif_edit = ($row->aktif_edit > 0) ? 'checked="checked"' : '';
                        $aktif_chat = ($row->aktif_chat > 0) ? 'checked="checked"' : '';
                        $rowData['tbody'] .= '<tr id_row="' . $row->id . '">
                        <td klm="username">' . $row->username  . '</td>
                        <td klm="email">' . $row->email . '</td>
                        <td klm="nama">' . $row->nama . '</td>
                        <td klm="kontak_person">' . $row->kontak_person . '</td>
                        <td klm="nama_org">' . $row->nama_org . '</td>
                        <td klm="type_user">' . $row->type_user . '</td>
                        <td klm="photo">' . $row->photo . '</td>
                        <td klm="tgl_daftar">' . $mysqldate . '</td>
                        <td klm="tgl_login">' . $mysqldate2 . '</td>
                        <td klm="thn_aktif_anggaran">' . $row->thn_aktif_anggaran . '</td>
                        <td klm="kd_proyek_aktif">' . $row->kd_proyek_aktif . '</td>
                        <td><div class="ui toggle checkbox user" jns="profil" klm="aktif" tbl="edit"><input type="checkbox" tabindex="0" class="hidden" ' . $aktif . '"><label></label></td>
                        <td><div class="ui toggle checkbox user" jns="profil" klm="aktif_edit" tbl="edit"><input type="checkbox" tabindex="0" class="hidden" ' . $aktif_edit . '"><label></label></td>
                        <td><div class="ui toggle checkbox user" jns="profil" klm="aktif_chat" tbl="edit"><input type="checkbox" tabindex="0" class="hidden" ' . $aktif_chat . '"><label></label></td>
                        <td klm="ket">' . $row->ket . '</td>
                        <td> <div class="ui icon basic mini buttons"><button class="ui button" name="flyout" name="flyout" jns="profil" tbl="edit"><i class="edit outline ' . $warna . ' icon"></i></button><button class="ui red button" name="del_row" tbl="del_row" jns="profil"><i class="trash alternate outline red icon"></i></button></div></td></tr>';
                        break;
                    case 'harga_satuan':
                        $desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan); //memanggil fungsi kelas sendiri
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                <td>' . $row->kode . '</td>
                                <td>' . $row->jenis . '</td>
                                <td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td>
                                <td>' . $row->satuan . '</td>
                                <td klm="harga_satuan"><div contenteditable rms onkeypress="return rumus(event);">' . number_format($row->harga_satuan, $desimal, ',', '.') . '</div></td>
                                <td klm="sumber_data"><div contenteditable>' . $row->sumber_data . '</div></td>
                                <td klm="spesifikasi"><div contenteditable>' . $row->spesifikasi . '</div></td>
                                <td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>
                                <td>
                                    <div class="ui icon basic mini buttons">
                                        <button class="ui button" name="flyout" name="flyout" jns="harga_satuan" tbl="edit" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                        <button class="ui button" name="del_row" jns="harga_satuan" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>
                                        <button class="ui button up_row"><i class="angle up icon"></i></button>
                                    </div>
                                </td>
                            </tr>');
                        break;
                    case 'satuan':
                        if ($type_user == 'admin') {
                            $row = (array)$row;
                            $rowData['tbody'] .= '<tr id_row="' . $row['id'] . '"><td klm="value"><div contenteditable>' . $row['value'] . '</div></td><td klm="item"><div contenteditable>' . $row['item'] . '</div></td><td klm="keterangan"><div contenteditable>' . $row['keterangan'] . '</div></td><td klm="sebutan_lain"><div contenteditable>' . $row['sebutan_lain'] . '</div></td><td><div class="ui icon basic mini buttons"><button class="ui button" name="flyout" name="flyout" jns="satuan" tbl="edit" id_row="' . $row['id'] . '"><i class="edit blue outline icon"></i></button><button class="ui button" name="del_row" jns="satuan" tbl="del_row" id_row="' . $row['id'] . '"><i class="trash alternate outline red icon"></i></button></div></td></tr>';
                        } else {
                            $rowData['tbody'] .= '<tr><td>' . $row['value'] . '</td><td>' . $row['item'] . '</td><td>' . $row['keterangan'] . '</td><td>' . $row['sebutan_lain'] . '</td><td></td></tr>';
                        }
                        break;
                    case 'analisa_ck':
                        $koef = $row->koefisien;
                        if ($koef > 0) {
                            $desimal = ($this->countDecimals($row->koefisien) < 2) ? 2 : $this->countDecimals($row->koefisien);
                            $koef = '<td klm="koefisien"><div contenteditable rms>' . number_format($row->koefisien, $desimal, ',', '.') . '</div></td>';
                        } else {
                            $koef = '<td klm="koefisien"></td>';
                        }
                        if ($row->harga_satuan > 0) {
                            $desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan);
                            $harga_sat = number_format($row->harga_satuan, $desimal, ',', '.');
                        } else {
                            $harga_sat = '';
                        }
                        if (strlen($row->rumus) > 0 || ($row->kode) == ($row->kd_analisa)) {
                            $desimal = ($this->countDecimals($row->koefisien) < 2) ? 2 : $this->countDecimals($row->koefisien);
                            $koef = '<td klm="koefisien">' . number_format($row->koefisien, $desimal, ',', '.') . '</td>';
                        }
                        $desimal = ($this->countDecimals($row->jumlah_harga) < 2) ? 2 : $this->countDecimals($row->jumlah_harga);
                        if (($row->kode) == ($row->kd_analisa)) {
                            $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '" class="warning"><td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td><td klm="kode">' . $row->kode . '</td><td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td><td></td><td klm="harga_satuan"></td>' . $koef . '<td klm="jumlah_harga"></td><td></td></tr>');
                        } else {
                            $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                            <td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td>
                            <td klm="kode"><div contenteditable>' . $row->kode . '</div></td>
                            <td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td>
                            ' . $koef . '
                            <td klm="harga_satuan">' . $harga_sat . '</td>
                            <td klm="jumlah_harga">' . number_format($row->jumlah_harga, $desimal, ',', '.') . '</td>
                            <td klm="rumus"><div contenteditable>' . $row->rumus . '</div></td>
                            <td> <div class="ui mini basic icon buttons"><button class="ui button" name="modal_2"><i class="edit blue icon"></i></button><button class="ui button" name="del_row" jns="direct" tbl="del_row"><i class="trash alternate outline icon"></i></button><button class="ui button up_row"><i class="angle up icon"></i></button></div></td></tr>');
                        }
                        break;
                    case 'value2':
                        # code...
                        break;
                    default:
                        # code...
                        break;
                }
            }
        } else { //jika data tidak ditemukan
            $rowData['tbody'] .= '<tr><td colspan="' . $jumlah_kolom . '"><div class="ui icon info message"><i class="yellow exclamation circle icon"></i><div class="content"><div class="header">Data Tidak ditemukan </div><p>input data baru atau hubungi administrator</p></div></div></td></tr>';
            $rowData['tfoot'] = '<tr></tr>';
        }
        // pagination
        if ($jmlhalaman > 1) {
            $id_aktif = 0;
            $batas_bawah = $halaman - 2;
            $batas_atas = $halaman + 2;
            if ($batas_bawah <= 1) {
                $batas_bawah = 1;
            } else {
                $pagination .= '<a class="item" name="page" hal="1" ret="go" tbl="' . $tbl . '"><i class="angle double left chevron icon"></i></a>';
            }
            $paginationnext = "";
            if ($batas_atas < $jmlhalaman) {
                $batas_atas = $halaman + 2;
                $paginationnext = '<a class="item" name="page" hal="' . $jmlhalaman . '" ret="go" tbl="' . $tbl . '"><i class="angle double right chevron icon"></i></a>';
            }
            //var_dump( $paginationnext );
            if ($batas_atas > $jmlhalaman) {
                $batas_atas = $jmlhalaman;
            }
            for ($i = $batas_bawah; $i <= $batas_atas; $i++) {
                if ($i != $halaman) {
                    $pagination .= '<a class="item" name="page" hal="' . $i . '" ret="go" tbl="' . $tbl . '">' . $i . '</a>';
                } else {
                    $pagination .= '<a class="active item" tbl="' . $tbl . '">' . $i . '</a>';
                    $id_aktif = $i;
                }
            }
            // panah preview
            if ($halaman == 1) {
                $pagination1 .= '<a class="disabled icon item"><i class="angle left icon"></i></a>';
            } else {
                $pagination1 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="prev" tbl="' . $tbl . '"><i class="angle left icon"></i></a>';
            }
            // panah next
            if ($halaman == $jmlhalaman) {
                $pagination2 .= '<a class="disabled icon item"><i class="angle right icon"></i></a>';
            } else {
                $pagination2 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="next" tbl="' . $tbl . '"><i class="angle right icon"></i></a>';
            }
            //var_dump( $pagination );
            $rowData['tfoot'] = '<tr' . $classRow . '><th class="right aligned" colspan="' . $jumlah_kolom . '"><div class="ui center pagination menu">' . $pagination1 . $pagination . $paginationnext . $pagination2 . '</div></th></tr>';
        } else {
            //preg_replace('/(\s\s+|\t|\n)/', ' ', $data['tr_sub_keg']);
            $rowData['tfoot'] = str_replace('/(\s\s+|\t|\n)/', "", '<tr' . $classRow . '><th class="right aligned" colspan="' . $jumlah_kolom . '"></th></tr>');
        }
        if (isset($rowData['tbody'])) {
            $rowData['tbody'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $rowData['tbody']);
        }
        // /if (property_exists($object, 'a_property'))
        //cek data ada atau tidak
        if (isset($rowData['tfoot'])) {
            $rowData['tfoot'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $rowData['tfoot']);
        }

        //$rowData['tbody'] = str_replace("\r\n", "", $rowData['tbody']); //trim(preg_replace('/^\p{Z}+|\p{Z}+$/u', '', ($rowData['tbody'])), "\r\n");
        return $rowData;
    }
    public function tabel_pakai($tbl)
    {

        $tabel_pakai = '';
        $jumlah_kolom = 11;
        switch ($tbl) {
            case 'dppa':
                $tabel_pakai = 'dppa_neo';
                $jumlah_kolom = 7;
                break;
            case 'dpa':
                $tabel_pakai = 'dpa_neo';
                $jumlah_kolom = 7;
                break;
            case 'renja_p':
                $tabel_pakai = 'renja_p_neo';
                $jumlah_kolom = 7;
                break;
            case 'renja':
                $tabel_pakai = 'renja_neo';
                $jumlah_kolom = 7;
                break;
            case 'sub_keg_renja':
                $tabel_pakai = 'sub_keg_renja_neo';
                $jumlah_kolom = 8;
                break;
            case 'prog_keg_renstra':
                $tabel_pakai = 'renstra_skpd_neo';
                $jumlah_kolom = 6;
                break;
            case 'renstra':
                $tabel_pakai = 'renstra_skpd_neo';
                $jumlah_kolom = 22;
                break;
            case 'tujuan_renstra':
            case 'sasaran_renstra':
            case 'tujuan_sasaran_renstra':
            case 'tujuan_sasaran':
                $tabel_pakai = 'tujuan_sasaran_renstra_neo';
                $jumlah_kolom = 4;
                break;
            case 'rekanan':
                $tabel_pakai = 'rekanan_neo';
                $jumlah_kolom = 11;
                break;
            case 'peraturan':
                $tabel_pakai = 'peraturan_neo';
                $jumlah_kolom = 4;
                break;
            case "akun":
            case "akun_belanja":
                $tabel_pakai = 'akun_neo';
                $jumlah_kolom = 4;
                break;
            case "aset":
                $tabel_pakai = 'aset_neo';
                $jumlah_kolom = 4;
                break;
            case 'dpa':
                $tabel_pakai = 'dpa_neo';
                break;
            case 'mapping_aset':
            case 'mapping':
                $tabel_pakai = 'mapping_aset_akun';
                $jumlah_kolom = 10;
                break;
            case 'wilayah':
                $tabel_pakai = 'wilayah_neo';
                $jumlah_kolom = 10;
                break;
            case 'satuan':
                $tabel_pakai = 'satuan_neo';
                $jumlah_kolom = 5;
                break;
            case 'organisasi':
                $tabel_pakai = 'organisasi_neo';
                break;
            case "pengaturan":
                $tabel_pakai = 'pengaturan_neo';
                break;
            case "peraturan":
                $tabel_pakai = 'peraturan_neo';
                break;
            case 'program':
                $tabel_pakai = 'program_neo';
                break;
            case 'satuan':
                $tabel_pakai = 'satuan_neo';
                break;
            case 'hspk':
                $tabel_pakai = 'hspk_neo';
                break;
            case 'sbu':
                $tabel_pakai = 'sbu_neo';
                break;
            case 'asb':
                $tabel_pakai = 'asb_neo';
                break;
            case 'ssh':
                $tabel_pakai = 'ssh_neo';
                break;
            case 'bidang_urusan':
            case 'prog':
            case 'keg':
            case 'sub_keg':
            case 'sub_kegiatan':
                $tabel_pakai = 'sub_kegiatan_neo';
                break;
            case 'sumber_dana':
                $tabel_pakai = 'sumber_dana_neo';
                break;
            case 'user':
                $tabel_pakai = 'user_sesendok_biila';
                break;
            case 'inbox':
            case 'outbox':
            case 'wall':
                $tabel_pakai = 'ruang_chat';
                break;
            default:
        }
        return ['tabel_pakai' => $tabel_pakai, 'jumlah_kolom' => $jumlah_kolom];
    }
    //uraikan kode rekening sub kegiatan 
    public function kd_sub_keg($dinamic = [])
    {
        $user = new User();
        $DB = DB::getInstance();
        $user->cekUserSession();
        $tbl = $dinamic['tbl'];
        $kode = $dinamic['kode'];

        $type_user = $_SESSION["user"]["type_user"];
        $id_user = $_SESSION["user"]["id"];

        $userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
        $jumlahArray = is_array($userAktif) ? count($userAktif) : 0;

        if ($jumlahArray > 0) {
            foreach ($userAktif[0] as $key => $value) {
                ${$key} = $value;
            }
        }
        $tabel_pakai = $this->tabel_pakai($tbl)['tabel_pakai'];
        $explodeAwal = explode('.', $kode);
        $count = count($explodeAwal);
        // cari di tabel jika tidak ditemukan tambahkan, jika ada update tabel
        //call methode in class 
        $rek_Proses = $this->kelolaRek($dinamic);
        // var_dump($rek_Proses);
        $insert = [];
        for ($i = 1; $i <= $rek_Proses['sum_rek']; $i++) {
            if ($i == 4) {
                continue;
            }
            switch ($i) {
                case 1:
                    $kd_sub_kegOk = $rek_Proses['kd_urusan'];
                    $columnSUM = $rek_Proses['kd_bidang'];
                    $kel_kd_sub_keg = 'kd_urusan';
                    break;
                case 2:
                    $kel_kd_sub_keg = 'kd_bidang';
                    $kd_sub_kegOk = $rek_Proses['kd_bidang'];
                    $columnSUM = $rek_Proses['kd_prog'];
                    break;
                case 3:
                    $kel_kd_sub_keg = 'kd_prog';
                    $kd_sub_kegOk = $rek_Proses['kd_prog'];
                    $columnSUM = $rek_Proses['kd_keg'];
                    break;
                case 5:
                    $kel_kd_sub_keg = 'kd_keg';
                    $kd_sub_kegOk = $rek_Proses['kd_keg'];
                    $columnSUM = $rek_Proses['kd_sub_keg'];
                    break;
                case 6:
                    $kel_kd_sub_keg = 'kd_sub_keg';
                    $kd_sub_kegOk = $rek_Proses['kd_sub_keg'];
                    $columnSUM = $rek_Proses['kd_sub_keg'];
                    break;
                default:
                    #code...
                    break;
            };
            //var_dump($kd_sub_kegOk);
            $dinamic['set']['kd_sub_keg'] = $kd_sub_kegOk;
            $dinamic['set']['kel_kd_sub_keg'] = $kel_kd_sub_keg;
            // jumlahkan kembali

            // cari uraian
            $progkeg = $DB->getWhereOnceCustom('sub_kegiatan_neo', [['kode', '=', $kd_sub_kegOk]]);
            $uraian_prog_keg = ($progkeg) ? $progkeg->nomenklatur_urusan : 'data tidak ditemukan';
            $dinamic['set']['uraian'] = $uraian_prog_keg;
            switch ($tbl) {
                case 'sub_keg_dpa':
                case 'sub_keg_renja':

                    $dinamic['kondisi'] = [['disable', '<=', 0], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_organisasi, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kd_sub_keg', '=', $kd_sub_kegOk, 'AND']];
                    break;
                case 'dpa':
                case 'renja':
                    break;
                default:
                    break;
            };
            $DB->select('*');
            $insert[$i] = $this->cekInsertUpdate($dinamic);
            // jumlahkan kembali
            $DB->select('SUM(jumlah_pagu), SUM(jumlah_pagu_p)');
            $kondisi = [['disable', '<=', 0], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_organisasi, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kd_sub_keg', '=', $columnSUM, 'AND']];
            $Sumprogkeg = $DB->getWhereCustom($tabel_pakai, $kondisi);
            // var_dump($Sumprogkeg);
        }
        return $insert;
    }
    // ambil data row bidang s/d sub kegiatan
    public function get_bidang_sd_sub_keg($dinamic = [])
    {
        $user = new User();
        $DB = DB::getInstance();
        $user->cekUserSession();
        $tbl = $dinamic['tbl'];
        $kode = $dinamic['kode'];

        
        $id_user = $_SESSION["user"]["id"];

        $userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
        $jumlahArray = is_array($userAktif) ? count($userAktif) : 0;

        if ($jumlahArray > 0) {
            foreach ($userAktif[0] as $key => $value) {
                ${$key} = $value;
            }
        }
        $column =  ($dinamic['column']) ? $dinamic['column'] : '*';
        $DB->select($column);
        $tabel_pakai = $this->tabel_pakai($tbl)['tabel_pakai'];
        $explodeAwal = explode('.', $kode);
        // cari di tabel jika tidak ditemukan tambahkan, jika ada update tabel
        //call methode in class 
        $Sumprogkeg =[];
        $rek_Proses = $this->kelolaRek($dinamic);
        // var_dump($rek_Proses);
        for ($i = 1; $i <= $rek_Proses['sum_rek']; $i++) {
            if ($i == 4) {
                continue;
            }
            switch ($i) {
                case 1:
                    $kd_sub_kegOk = $rek_Proses['kd_urusan'];
                    $kel_kd_sub_keg = 'kd_urusan';
                    break;
                case 2:
                    $kd_sub_kegOk = $rek_Proses['kd_bidang'];
                    $kel_kd_sub_keg = 'kd_bidang';
                    break;
                case 3:
                    $kd_sub_kegOk = $rek_Proses['kd_prog'];
                    $kel_kd_sub_keg = 'kd_prog';
                    break;
                case 5:
                    $kd_sub_kegOk = $rek_Proses['kd_keg'];
                    $kel_kd_sub_keg = 'kd_keg';
                    break;
                case 6:
                    $kd_sub_kegOk = $rek_Proses['kd_sub_keg'];
                    $kel_kd_sub_keg = 'kd_sub_keg';
                    break;
                default:
                    #code...
                    break;
            };
            $DB->select($column);
            $kondisi = [['disable', '<=', 0], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_organisasi, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kd_sub_keg', '=', $kd_sub_kegOk, 'AND']];
            $Sumprogkeg[$kel_kd_sub_keg] = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
            // $Sumprogkeg[$kel_kd_sub_keg] = $DB->getWhereCustom($tabel_pakai, $kondisi);
            // var_dump($Sumprogkeg);
        }
        return $Sumprogkeg;
    }

    public function cekInsertUpdate($dinamic = [])
    {
        $user = new User();
        $DB = DB::getInstance();
        $tbl = $dinamic['tbl'];
        $kondisi = $dinamic['kondisi'];
        $set = $dinamic['set'];
        $user->cekUserSession();
        $id_user = $_SESSION["user"]["id"];
        $userAktif = $DB->getWhereCustom('user_sesendok_biila', [['id', '=', $id_user]]);
        $jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
        if ($jumlahArray > 0) {
            foreach ($userAktif[0] as $key => $value) {
                ${$key} = $value;
            }
        }
        $tabel_pakai = $this->tabel_pakai($tbl)['tabel_pakai'];
        $resul = $DB->getWhereCustom($tabel_pakai, $kondisi);
        $jumlahArray = is_array($resul) ? count($resul) : 0;
        $data = [];
        if ($jumlahArray) {
            $DB->update_array($tabel_pakai, $set, $kondisi);
            if ($DB->count()) {
                $data['update'] = $DB->count();
            }
        } else {
            $resul = $DB->insert($tabel_pakai, $set);
            $data['add_row'] = $DB->lastInsertId();
        }
        return $data;
    }
    public function kelolaRek($dinamic = [])
    {
        $kode = $dinamic['kode'];
        // var_dump($kode);
        $explodeAwal = explode('.', $kode);
        $count = count($explodeAwal);

        // cari di tabel jika tidak ditemukan tambahkan, jika ada update tabel
        $kd_rek = '';
        $dataRek = [];
        switch ($count) {
            case 6: //sub keg
                if ((int)$explodeAwal[5]) {
                    $dataRek['sub_keg'] = (int)$explodeAwal[5];
                    $kd_rek = $this->zero_pad((int)$explodeAwal[5], 4);
                }
            case 5: //keg
                if ((int)$explodeAwal[4]) {
                    $bantuan = (strlen($kd_rek) > 0) ? "." : "";
                    $kd_rek = $this->zero_pad((int)$explodeAwal[4], 2) . $bantuan . $kd_rek;
                }
            case 4: //keg
                if ((int)$explodeAwal[3]) {
                    $bantuan = (strlen($kd_rek) > 0) ? "." : "";
                    $kd_rek = $this->zero_pad((int)$explodeAwal[3], 1) . $bantuan . $kd_rek;
                    $dataRek['keg'] = $this->zero_pad((int)$explodeAwal[3], 1) . "." . $this->zero_pad((int)$explodeAwal[4], 2);
                }
            case 3: //prog
                if ((int)$explodeAwal[2]) {
                    $bantuan = (strlen($kd_rek) > 0) ? "." : "";
                    $kd_rek = $this->zero_pad((int)$explodeAwal[2], 2) . $bantuan . $kd_rek;
                    $dataRek['prog'] = (int)$explodeAwal[2];
                }
            case 2: //bidang
                if ($explodeAwal[1]) {
                    $bantuan = (strlen($kd_rek) > 0) ? "." : "";
                    if (is_numeric($explodeAwal[1])) {
                        $bidang = $this->zero_pad((int)$explodeAwal[1], 1);
                    } elseif (is_string($explodeAwal[1])) {
                        $bidang = $explodeAwal[1];
                    }
                    $kd_rek = $bidang . $bantuan . $kd_rek;
                    $dataRek['bidang'] = $bidang;
                }
            case 1: //urusan
                if ($explodeAwal[0]) {
                    $bantuan = (strlen($kd_rek) > 0) ? "." : "";
                    if (is_numeric($explodeAwal[0])) {
                        $urusan = $this->zero_pad((int)$explodeAwal[0], 1);
                    } elseif (is_string($explodeAwal[0])) {
                        $urusan = $explodeAwal[0];
                    }
                    $kd_rek = $urusan . $bantuan . $kd_rek;
                    $dataRek['urusan'] = $urusan;
                }
                $dataRek['kode'] = $kd_rek;
                break;
            default:
                break;
        };
        $explodeAwal = explode('.', $dataRek['kode']);
        $dataRek['sum_rek'] = count($explodeAwal);
        if ($explodeAwal[0]) {
            $dataRek['kd_urusan'] = $explodeAwal[0];
        }
        if ($explodeAwal[1]) {
            $dataRek['kd_bidang'] = $explodeAwal[0] . "." . $explodeAwal[1];
        }
        if ($explodeAwal[2]) {
            $dataRek['kd_prog'] = $explodeAwal[0] . "." . $explodeAwal[1] . "." . $explodeAwal[2];
        }
        if ($explodeAwal[4] && $explodeAwal[3]) {
            $dataRek['kd_keg'] = $explodeAwal[0] . "." . $explodeAwal[1] . "." . $explodeAwal[2] . "." . $explodeAwal[3] . "." . $explodeAwal[4];
        }
        if ($explodeAwal[5]) {
            $dataRek['kd_sub_keg'] = $explodeAwal[0] . "." . $explodeAwal[1] . "." . $explodeAwal[2] . "." . $explodeAwal[3] . "." . $explodeAwal[4] . "." . $explodeAwal[5];
        }

        return $dataRek;
    }

    /*
    * Copyright (c) 2011-2013 Philipp Tempel
    *
    * Permission is hereby granted, free of charge, to any person obtaining a copy
    * of this software and associated documentation files (the "Software"), to deal
    * in the Software without restriction, including without limitation the rights
    * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    * copies of the Software, and to permit persons to whom the Software is
    * furnished to do so, subject to the following conditions:
    *
    * The above copyright notice and this permission notice shall be included in
    * all copies or substantial portions of the Software.
    *
    * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    * THE SOFTWARE.
    */

    /**
     * Get the user's operating system.
     *
     * @param string $userAgent The user's user agent
     *
     * @return string returns the user's operating system as human readable string,
     *                if it cannot be determined 'n/a' is returned
     */
    public function getOS($userAgent)
    {
        // Create list of operating systems with operating system name as array key
        $oses = array(
            'iPhone' => '(iPhone)',
            'Windows 3.11' => 'Win16',
            'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
            'Windows 98' => '(Windows 98)|(Win98)',
            'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
            'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
            'Windows 2003' => '(Windows NT 5.2)',
            'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
            'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
            'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
            'Windows ME' => 'Windows ME',
            'Open BSD' => 'OpenBSD',
            'Sun OS' => 'SunOS',
            'Linux' => '(Linux)|(X11)',
            'Safari' => '(Safari)',
            'Mac OS' => '(Mac_PowerPC)|(Macintosh)',
            'QNX' => 'QNX',
            'BeOS' => 'BeOS',
            'OS/2' => 'OS/2',
            'Search Bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
        );

        // Loop through $oses array
        foreach ($oses as $os => $preg_pattern) {
            // Use regular expressions to check operating system type
            if (preg_match('@' . $preg_pattern . '@', $userAgent)) {
                // Operating system was matched so return $oses key
                return $os;
            }
        }
        return 'n/a';
    }

    public function getBrowser()
    {
        global $user_agent;

        $browser = 'Unknown Browser';

        $browser_array = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser',
        );

        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $browser = $value;
            }
        }

        return $browser;
    }
    // mencari file di folder dan sub folder

    public function cariFile($f, $p = null, $l = 1000)
    { // Recursively find a file $f in directory $p (compare up to $l files)
        // Returns the full path of the first occurrence (relative to current directory)
        //$fileku = cariFile('renja.pdf','../dokumen_upl');
        //var_dump($f);
        //var_dump($p);
        $cd = $p == null ? getcwd() : $p;
        if (substr($cd, -1, 1) != '/') {
            $cd .= '/';
        }
        if (is_dir($cd)) {
            $dh = opendir($cd);
            while ($fn = readdir($dh)) { // traverse directories and compare files:
                if (is_file($cd . $fn) && $fn == $f) {
                    closedir($dh);

                    return $cd . $fn;
                }
                if ($fn != '.' && $fn != '..' && is_dir($cd . $fn)) {
                    $m = $this->cariFile($f, $cd . $fn, $l);
                    if ($m) {
                        closedir($dh);

                        return $m;
                    }
                }
            }
            closedir($dh);
        }

        return false;
    }

    public function getFileAll($dir, $pola, $results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if ($value != '.' && $value != '..' && !is_dir($path)) {
                $this->getDirContents($path, $results);
                var_dump($value);
                if ($value == $pola) {
                    $results[] = $path;
                }
                if (preg_match("/$pola/", $value)) {
                    $results[] = $path;
                }
            }
        }

        return $results;
    }

    public function getDirContents($dir, &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } elseif ($value != '.' && $value != '..') {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }
    // fungsi zero padding
    /*
    1. %s = String.
    2. %d = desimal.
    3. %x = hexadesimal.
    4. %o = Octal.
    5. %f = Float.
    */
    public function zero_pad($angka, $jumlahChar)
    {
        $fzeropadded = sprintf('%0' . $jumlahChar . 'd', $angka);

        return  $fzeropadded;
    }
    public function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false)
    {
        $encoded = json_encode($value, $options, $depth);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
            case JSON_ERROR_UTF8:
                $clean = $this->utf8ize($value);
                if ($utfErrorFlag) {
                    return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
                }
                return $this->safe_json_encode($clean, $options, $depth, true);
            default:
                return 'Unknown error'; // or trigger_error() or throw new Exception()

        }
    }

    public function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8");
        }
        return $mixed;
    }
    public function enskripsiText($simple_string, $encryption_key = "AlwiMansyur", $encryption_iv = '1107807891011121')
    {
        $ciphering = "AES-128-CTR"; //AES-256-GCM//AES-256-GCM
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = OPENSSL_RAW_DATA;
        //$tag_length: It holds the length of the authentication tag. The length of authentication tag lies between 4 to 16 for GCM mode.

        $encryption = openssl_encrypt(
            $simple_string,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );
        return $encryption;
    }
    public function deskripsiText($encryption, $decryption_key = "AlwiMansyur", $decryption_iv = '1107807891011121')
    {
        $ciphering = "AES-128-CTR"; //"aes-256-cbc" ."AES-128-CTR"//AES-256-GCM
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = OPENSSL_RAW_DATA; //OPENSSL_RAW_DATA,0

        $decryption = openssl_decrypt(
            $encryption,
            $ciphering,
            $decryption_key,
            $options,
            $decryption_iv
        );
        return $decryption;
    }
    public function selisihWaktu($awal, $akhir)
    {
        //date('Y-m-d H:i:s')
        $awal  = date_create($awal);
        $akhir = date_create($akhir); // waktu sekarang
        $diff  = date_diff($awal, $akhir);

        return ['tahun' => $diff->y, 'bulan' => $diff->m, 'hari' => $diff->d, 'jam' => $diff->h, 'menit' => $diff->i, 'detik' => $diff->s];
    }
    public function recursiveChat($id_row, $type = '')
    {
        $DB = DB::getInstance();
        $user = new User();
        $user->cekUserSession();
        $Fungsi = new MasterFungsi();
        $dataWall = '';
        $id_user = $_SESSION["user"]["id"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $DB->orderBy('waktu_input', 'DESC');
        $replyBtn = '<a class="reply" name="chat" jns="wall" tbl="reply">Reply</a>';
        switch ($type) {
            case 'inbox':
                $condition = [['id_reply', '=', $id_row]];
                //$condition = [['id_reply', '=', $id_row], ['id_tujuan', '=', $id_user, 'AND']];
                break;
            case 'outbox':
                $condition = [['id_reply', '=', $id_row]];
                $replyBtn = '';
                //$condition = [['id_reply', '=', $id_row], ['id_pengirim', '=', $id_user, 'AND']];
                break;
            default:
                $condition = [['id_reply', '=', $id_row], ['id_tujuan', '<=', 0, 'AND']];
                break;
        };
        //$sql = "SELECT * FROM ruang_chat WHERE id_reply = $id_chat AND id_tujuan = $id_user_session ORDER by waktu ASC";
        //$sql = "SELECT * FROM ruang_chat WHERE id_reply = $id_chat AND id_tujuan <= 0 ORDER by waktu ASC";

        $rowWall = $DB->getWhereCustom('ruang_chat', $condition);
        //var_dump($rowWall);
        $jumlahArray = is_array($rowWall) ? count($rowWall) : 0;
        if ($jumlahArray) {
            //var_dump($rowWall);
            $dataWall .= '<div class="comments">';
            //jika ada data sebelum post
            //tidak ada data sebelum post
            foreach ($rowWall as $key => $value) {
                $id = $value->id;
                $waktu_input = $value->waktu_input;
                $waktu_edit = $value->waktu_edit;
                $uraian = $value->uraian;
                $id_pengirim = $value->id_pengirim;
                //deskripsi jika jenis=outbox atau inbox
                $id_tujuan = $value->id_tujuan;
                //var_dump($value);
                $userWithIdPengirim = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
                switch ($type) {
                    case 'outbox':
                        if ($id_tujuan != 'all') {
                            // $userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_tujuan]);
                            $userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
                        }
                        break;
                    default:
                        $userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
                        break;
                };
                if ($id_tujuan != 'all') {
                    $namaPengirimTampak = $userWithId->nama;
                    $photo = $userWithId->photo;
                    $photo = explode('/', $photo);
                    ($photo[0] != 'img') ? $photo[0] = 'img' :  0;
                    $photo = implode('/', $photo);
                } else {
                    $namaPengirimTampak = 'admin';
                    $photo = './img/avatar/default.jpeg';
                }

                $namaPengirim = $userWithIdPengirim->nama;
                $uraian = $this->deskripsiText($uraian, $namaPengirim);

                $dibaca = $value->dibaca;
                $id_reply = $value->id_reply;
                $like = $value->like;
                $selisihWaktu = $Fungsi->selisihWaktu($waktu_input, date('Y-m-d H:i:s'));
                $dateSelisih = $waktu_input;
                if ($selisihWaktu['bulan'] > 0 || $selisihWaktu['tahun'] > 0) {
                    $dateSelisih = $waktu_input;
                } else if ($selisihWaktu['hari'] > 0) {
                    $dateSelisih = $selisihWaktu['hari'] . ' days ' . $selisihWaktu['jam'] . ' ago';
                } else if ($selisihWaktu['jam'] > 0) {
                    $dateSelisih = $selisihWaktu['jam'] . ' hours ago ' . $selisihWaktu['menit'] . " minutes";
                } else if ($selisihWaktu['menit'] > 0) {
                    $dateSelisih = $selisihWaktu['menit'] . " minutes ago";
                }

                $btnDel = '';
                if ($id_user == $id_pengirim) {
                    $btnDel = '<a class="edit" name="chat" jns="wall" tbl="edit"><i class="edit icon"></i>Edit</a><a class="trash" name="del_row" jns="chat" tbl="del_row"><i class="trash icon"></i>Delete</a>';
                }

                $dataWall .= '<div class="comment" id_row="' . $id . '"><a class="avatar"><img src="' . $photo . '"></a><div class="content"><a class="author">' . $namaPengirimTampak . '</a><div class="metadata"><div class="date">' . $dateSelisih . '</div><div class="rating"><i class="star icon"></i>5 Faves </div></div><div class="text">' . $uraian . '</div><div class="actions"><a class="reply" name="chat" jns="wall" tbl="reply">Reply</a><a class="hide" name="chat" jns="wall" tbl="hide">Hide</a>' . $btnDel . '</div></div>';
                $dataWall .= $this->recursiveChat($id);
                $dataWall .= '</div>';
            }
            $dataWall .= '</div>';
        }
        return $dataWall;
    }
    //chat function
    public function panggilChat($id_row, $posisi, $limit, $jenis, $tabel_pakai = 'ruang_chat')
    {
        $DB = DB::getInstance();
        $user = new User();
        $user->cekUserSession();
        $Fungsi = new MasterFungsi();
        $dataWall = '';
        $id_user = $_SESSION["user"]["id"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $dataWall = '';
        $replyBtn = '<a class="reply" name="chat" jns="wall" tbl="reply">Reply</a>';
        if ($id_row > 0) {
            //ambil dahulu row id
            switch ($jenis) {
                case 'inbox':
                    $condition = [['id', '=', $id_row], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND']]; //, ['id_tujuan', '=', 'all', 'OR']
                    $DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
                    break;
                case 'outbox':
                    $replyBtn = '';
                    $condition = [['id', '=', $id_row]];
                    break;
                default:
                    $condition = [['id', '=', $id_row], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
                    break;
            };
            $result = $DB->getWhereCustom($tabel_pakai, $condition);

            $jumlahArray = is_array($result) ? count($result) : 0;
            if ($jumlahArray) {
                $waktu_inputId = $result[0]->waktu_input;
                //jalankan
                if ($posisi == 'top') {
                    switch ($jenis) {
                        case 'inbox':
                            $condition = [['id', '>', 0], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
                            $DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
                            break;
                        case 'outbox':
                            $condition = [['id', '>', 0], ['id_pengirim', '=', $id_user, 'AND'], ['id_tujuan', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
                            break;
                        default:
                            $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '>', $waktu_inputId, 'AND']];
                            break;
                    };
                } else {
                    switch ($jenis) {
                        case 'inbox':
                            $condition = [['id', '=', $id_row], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
                            $DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
                            break;
                        case 'outbox':
                            $condition = [['id', '>', 0], ['id_pengirim', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
                            break;
                        default:
                            $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND'], ['waktu_input', '<', $waktu_inputId, 'AND']];
                            break;
                    };
                }
            } else {

                switch ($jenis) {
                    case 'inbox':
                        $condition = [['id', '>', 0], ['id_pengirim', '>', 0, 'AND'], ['id_tujuan', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND']];
                        $DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
                        break;
                    case 'outbox':
                        $replyBtn = '';
                        $condition = [['id', '>', 0], ['id_tujuan', '>', 0, 'AND'], ['id_pengirim', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND']];
                        break;
                    default:
                        $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
                        break;
                };
            }
        } else {
            switch ($jenis) {
                case 'inbox':
                    $condition = [['id', '>', 0], ['id_pengirim', '>', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
                    $DB->tambahQuery("AND (id_tujuan = 'all' OR id_tujuan = $id_user)");
                    break;
                case 'outbox':
                    $replyBtn = '';
                    $condition = [['id', '>', 0], ['id_tujuan', '>', 0, 'AND'], ['id_pengirim', '=', $id_user, 'AND'], ['id_reply', '<=', 0, 'AND']];
                    break;
                default:
                    $condition = [['id', '>', 0], ['id_tujuan', '<=', 0, 'AND'], ['id_reply', '<=', 0, 'AND']];
                    break;
            };
        }

        $rowWall = $DB->getWhereCustom($tabel_pakai, $condition);
        $posisiLimit = $DB->posisilimit($limit, $rowWall, $halaman = 1);
        $DB->limit([$posisiLimit, $limit]);

        // if ($posisi == 'top') {
        //     $DB->orderBy('waktu_input', 'DESC');
        // } else {
        //     $DB->orderBy('waktu_input', 'ASC');
        // }
        $DB->orderBy('waktu_input', 'DESC');
        $rowWall = $DB->getWhereCustom($tabel_pakai, $condition);
        $jumlahArray = is_array($rowWall) ? count($rowWall) : 0;

        if ($jumlahArray) {
            //jika ada data sebelum post
            //tidak ada data sebelum post
            foreach ($rowWall as $key => $value) {
                $id = $value->id;
                $waktu_input = $value->waktu_input;
                $waktu_edit = $value->waktu_edit;
                $uraian = $value->uraian;
                $id_pengirim = $value->id_pengirim;
                $id_tujuan = $value->id_tujuan;

                $userWithIdPengirim = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
                switch ($jenis) {
                    case 'outbox':
                        if ($id_tujuan != 'all') {
                            $userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_tujuan]);
                        }
                        break;
                    default:
                        $userWithId = $DB->getWhereOnce('user_sesendok_biila', ['id', '=', $id_pengirim]);
                        break;
                };
                //deskripsi jika jenis=outbox atau inbox
                if ($id_tujuan != 'all') {
                    $namaPengirimTampak = $userWithId->nama;
                    $photo = $userWithId->photo;
                    $photo = explode('/', $photo);
                    ($photo[0] != 'img') ? $photo[0] = 'img' :  0;
                    $photo = implode('/', $photo);
                } else {
                    $namaPengirimTampak = 'untuk semua user';
                    $photo = './img/avatar/default.jpeg';
                }

                $namaPengirim = $userWithIdPengirim->nama;
                $uraian = $this->deskripsiText($uraian, $namaPengirim);
                $dibaca = $value->dibaca;
                $id_reply = $value->id_reply;
                $like = $value->like;

                $btnDel = '';
                $selisihWaktu = $Fungsi->selisihWaktu($waktu_input, date('Y-m-d H:i:s'));
                $dateSelisih = $waktu_input;
                if ($selisihWaktu['bulan'] > 0 || $selisihWaktu['tahun'] > 0) {
                    $dateSelisih = $waktu_input;
                } else if ($selisihWaktu['hari'] > 0) {
                    $dateSelisih = $selisihWaktu['hari'] . ' days ' . $selisihWaktu['jam'] . ' ago';
                } else if ($selisihWaktu['jam'] > 0) {
                    $dateSelisih = $selisihWaktu['jam'] . ' hours ' . $selisihWaktu['menit'] . " minutes ago";
                } else if ($selisihWaktu['menit'] > 0) {
                    $dateSelisih = $selisihWaktu['menit'] . " minutes ago";
                } else if ($selisihWaktu['detik'] > 0) {
                    $dateSelisih = "just now";
                }

                if ($id_user == $id_pengirim) {
                    $btnDel = '<a class="edit" name="chat" jns="wall" tbl="edit"><i class="edit icon"></i>Edit</a><a class="trash" name="del_row" jns="chat" tbl="del_row"><i class="trash icon"></i>Delete</a>';
                }
                $dataWall .= '<div class="comment" id_row="' . $id . '"><a class="avatar"><img src="' . $photo . '"></a><div class="content"><a class="author">' . $namaPengirimTampak . '</a><div class="metadata"><div class="date">' . $dateSelisih . '</div><div class="rating"><i class="star icon"></i>5 Faves </div></div><div class="text">' . $uraian . '</div><div class="actions">' . $replyBtn . '<a class="hide" name="chat" jns="wall" tbl="hide">Hide</a>' . $btnDel . '</div></div>';

                switch ($jenis) {
                    case 'inbox':
                        $dataWall .= $this->recursiveChat($id, 'inbox');
                        break;
                    case 'outbox':
                        $dataWall .= $this->recursiveChat($id, 'outbox');
                        break;
                    default:
                        $dataWall .= $this->recursiveChat($id);
                        break;
                };

                $dataWall .= '</div>';
            }

            //$data['dataWall'] = $dataWall;
            //var_dump($dataWall);
        }

        //svar_dump($dataWall);
        return $dataWall;
    }
    public function importFile($tbl, $nameFileDel = '')
    {
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];

        $id_user = $_SESSION["user"]["id"];
        $maxsize = 1024 * 15000; //15 MB
        $fileName = 'avatar.jpg';

        //jenis os
        /*
        unlink($fileee . $fileee1 . $nama_files_hapus);
        */
        $valid_extension = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'ppt' => 'application/vnd.ms-powerpoint',
            'doc' => 'application/msword',
            'pdf' => 'application/pdf'
        );
        $path1 = 'upload';
        switch ($tbl) {

            case 'profil':
                $path1 = 'img';
                $path2 = 'avatar';
                $valid_extension = array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                );
                $fileName = 'avatar.jpg';
                $nama_files_hapus = '';
                break;
            case 'peraturan':
                //$targetPath = "upload{$slash}peraturan{$slash}";
                $path1 = 'upload';
                $path2 = 'peraturan';
                break;
            case 'monev':

                $path2 = 'realisasi';
                break;
            case 'rekanan':
                $path1 = 'upload';
                $path2 = 'rekanan';
                break;
            default:
                break;
        }
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $os_c = 'windows';
            $fileee1 = "\\script"; //untuk windows
            $slash = '\\';
            $targetPath = "$path1\\$path2\\";
            $folder = "$path1/$path2/"; //delete file
        } else {
            $os_c = 'os_xx';
            $fileee1 = "/script"; // akali ut linux osx
            $slash = '/';
            $targetPath = "$path1/$path2/";
            $folder = "\\$path1\\$path2\\";
        }
        try {

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (
                !isset($_FILES['file']['error']) ||
                is_array($_FILES['file']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            // You should also check filesize here. 
            if ($_FILES['file']['size'] > $maxsize) {
                throw new RuntimeException('Exceeded filesize limit.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                $finfo->file($_FILES['file']['tmp_name']),
                $valid_extension,
                true
            )) {
                throw new RuntimeException('Invalid file format.');
            }
            // You should name it uniquely.
            // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
            // On this example, obtain safe unique name from its binary data.
            $namaFile = sprintf(
                "$targetPath%s.%s", //"./uploads/%s.%s",
                sha1_file($_FILES['file']['tmp_name']) . "_{$id_user}", //sha1_file($_FILES['file']['tmp_name']),
                $ext
            );
            $fileNamePath = "$namaFile";/*sprintf(
                "..$targetPath%s.%s", //"./uploads/%s.%s",
                sha1_file($_FILES['file']['tmp_name']), //sha1_file($_FILES['file']['tmp_name']),
                $ext
            );*/
            //var_dump($_FILES);
            //var_dump($fileNamePath);
            if (!move_uploaded_file(
                $_FILES['file']['tmp_name'],
                $fileNamePath
            )) {
                throw new RuntimeException('Failed to move uploaded file.');
            }
            //var_dump($nameFileDel);
            //===============
            //hapus file
            //===============
            if (file_exists($nameFileDel) && strlen($nameFileDel)) {
                unlink($nameFileDel);
                $status  = unlink($nameFileDel) ? 'The file ' . $nameFileDel . ' has been deleted' : 'Error deleting ' . $nameFileDel;
                return ['result' => 'ok', 'file' => $nameFileDel, 'status' => $status];
            } else {
                $status = 'The file ' . $nameFileDel . ' doesnot exist';
                //return ['result' => 'ok', 'file' => $nameFileDel, 'status' => $status];
            }
            //unlink($nameFileDel);
            return ['result' => 'ok', 'file' => $namaFile]; //'File is uploaded successfully.'
        } catch (RuntimeException $e) {
            //var_dump($e->getMessage());
            return ['result' => 'error', 'file' => $e->getMessage()]; //$e->getMessage();
        }
    }
    //get dropdown
    public function getDropdownItem($get_data = [], $nama_tabel, $nama_dropdown, $jenisdropdown = 'list', $jumlah_kolom_dropdown = 1, $type_user = 'user')
    {
        $hasil = '';
        switch ($jenisdropdown) {
            case 'search':
                $hasil .= '<div class="ui floating dropdown labeled search icon button">
                <i class="world icon"></i>
                <span class="text">Select Language</span>
                <div class="menu">';
                break;
            case 'input':
                $hasil .= '<div class="ui floating dropdown labeled search icon button">
                                <i class="world icon"></i>
                                <span class="text">Select</span>
                                <div class="menu">';
                break;
            default:
                //list
                $hasil .= '<div class="ui dropdown fluid search selection">
                        <input type="hidden" name="' . $nama_dropdown . '">
                        <div class="default text"></div>
                        <i class="dropdown icon"></i>
                        <div class="menu">';
                break;
        }
        if (sizeof($get_data) > 0) {
            foreach ($get_data as $row) {
                switch ($nama_tabel) {
                    case "harga_sat_upah_bahan":
                        $hasil .= '<div class="item" data-value="' . $row->kode . '"><span class="text">' . ucwords($row->uraian) . '</span><span class="description">' . $row->kode . '</span></div>';
                        break;
                    case "renja_p":
                        if (count($row) == 2) { //jika kegiatan
                            //$kdProkeg = str_pad($row, 2, 0, STR_PAD_LEFT) . '.' . str_pad($row, 2, 0, STR_PAD_LEFT);
                            $hasil .= '<div class="item" data-value="' . $row . '"><i class="list icon"></i><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
                        } else { //jika program
                            $kdProkeg = str_pad($row->kd_prog, 2, 0, STR_PAD_LEFT);
                            $hasil .= '<div class="header">' . ucwords($row) . '</div>';
                        }
                        break;
                    case "list_2kolom": //dua item
                        @$hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . $row . '</span><span class="description">' . $row . '</span></div>';
                        break;
                    case "kd_urusan":
                        $count = count(explode('.', $row));
                        if ($count > 1) {
                            $hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
                        } else {
                            $hasil .= '<div class="header"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
                        }
                        break;
                    case "kd_prog":
                        $hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . $row . '</span></div>';
                        break;
                    case "lokasi":
                        if ($row->status == 'kecamatan') {
                            $hasil .= '<div class="divider"></div><div class="header">KECAMATAN ' . $row->kecamatan . '</div><div class="divider"></div>';
                        } else {
                            $hasil .= '<div class="item" data-value="' . $row->desa . '"><i class="list icon"></i>' . $row->desa . '</div>';
                        }
                        break;
                    default:
                        $hasil .= '<div class="item" data-value="' . $row . '"><span class="text">' . ucwords($row) . '</span><span class="description">' . $row . '</span></div>';
                }
            }
        } else {
            $hasil = '<div class="header"><i class="tags icon"></i>Data tidak ditemukan</div>';
        }
        $hasil .= '</div></div>';
        return str_replace("\r\n", "", $hasil);
    }

    //============================
    //===========BUAT LIST========
    //============================
    public function getList($jenis, $nama_tabel = '', $get_data = [], $jmlhalaman = 0, $halaman = 1)
    {
        $pagination = '';
        $pagination1 = '';
        $pagination = '';
        $paginationnext = '';
        $pagination2 = '';
        $rowData = ['list' => '', 'foot' => ''];
        $warna = 'green';
        //var_dump($get_data);
        //var_dump($jenis);
        //var_dump($nama_tabel);
        //var_dump($get_data);
        $jumlahArray = is_array($get_data) ? count($get_data) : 0;
        if ($jumlahArray > 0) {
            foreach ($get_data as $row) {
                //var_dump($row);
                switch ($jenis) {
                    case 'analisa_ck':
                    case 'analisa_bm':
                    case 'analisa_sda':
                    case 'analisa_quarry':
                        $row = (array)$row;
                        //var_dump($row['keterangan']);
                        $content = '<div class="header">' . $row['kd_analisa']  . ' : ' . $row['uraian'] . '</div>' . $row['kode']  . ' (Rp ' . number_format($row['koefisien'], 2, ',', '.')  . ')';
                        if ($jenis == 'analisa_quarry' && strlen($row['keterangan']) > 0) {
                            $ket = json_decode($row['keterangan'], TRUE);
                            $lokasi = $ket['lokasi'];
                            $tujuan = $ket['tujuan'];
                            $content = '<div class="header">' . $row['kd_analisa']  . ' : ' . $row['uraian'] . ' : lokasi =>:' . $lokasi . ' : tujuan =>:' . $tujuan . '</div>' . $row['kode']  . ' (Rp ' . number_format($row['koefisien'], 2, ',', '.')  . ')';
                        }
                        $rowData['list'] .= '<div class="item">
                            <div class="right floated content">
                                <div class="ui icon buttons basic compact">
                                    <button class="ui icon button" name="modal_show" tbl="edit" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="edit blue icon"></i>
                                    </button>
                                    <button class="ui button" name="flyout" jns="copy" tbl="' . $jenis  . '" id_row="' . $row['kd_analisa'] . '"  data-tooltip="salin dan tempel" data-position="left center"><i class="copy icon"></i></button>
                                    <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="trash alternate outline red icon"></i>
                                    </button>
                                </div>
                            </div>
                            <i class="teal large money check middle aligned icon"></i>
                            <div class="content">' . $content . '</div>
                        </div>';
                        break;
                    case 'analisa_ckxx':
                        //$desimal = ($this->countDecimals($row->harga_sat) < 2) ? 2 : $this->countDecimals($row->harga_sat);
                        $rowData['list'] .= '<div class="item">
                            <div class="right floated content">
                                <div class="ui icon buttons compact ">
                                    <button class="green ui icon button" name="modal_show" tbl="edit" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="edit blue icon"></i>
                                    </button>
                                    <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $jenis  . '" id_row="' . $row['kd_analisa']  . '">
                                        <i class="trash alternate outline red icon"></i>
                                    </button>
                                </div>
    
                            </div>
                            <i class="large teal money check middle aligned icon"></i>
                            <div class="content">
                                <div class="header">' . $row['kd_analisa']  . ' : ' . $row['keterangan'] . '</div>
                                (Rp ' . number_format($row['jumlah_harga'], 2, ',', '.')  . ')
                            </div>
                        </div>';
                        break;

                    case 'harga_satuan':
                        break;
                    case 'daftar_satuan':
                        $rowData['list'] .= '<tr>
                                    <td>' . $row->value . '</td>
                                    <td>' . $row->item . '</td>
                                    <td>' . $row->sketerangan . '</td>
                                    <td>
                                        <div class="ui icon basic mini buttons">
                                            <button class="ui ' . $warna . ' button" name="flyout" name="flyout" jns="satuan" tbl="edit" id_row="' . $row->id . '"><i class="edit blue icon"></i></button>
                                            <button class="ui red button" name="del_row" jns="satuan" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>
                                        </div>
                                    </td>
                                </tr>';
                        break;
                    case 'peraturan':
                        $file = $row->file;
                        $fileTag = '<a class="ui blue icon button" ><i class="checkmark icon"></i></a>';
                        if (strlen($file)) {
                            $fileTag = '<a class="ui  icon button" href="' . $file . '" target="_blank"><i class="blue download icon"></i></a>';
                        }
                        $gabung = $row->keterangan . ' (' . $row->status . ')';
                        $rowData['list'] .= trim('<div class="item">
                            <div class="right floated content">
                                <div class="ui icon buttons basic compact">
                                    <button class="ui icon button" name="flyout" tbl="edit" jns="' . $jenis . '" id_row="' . $row->id . '">
                                        <i class="edit blue icon"></i>
                                    </button>' . $fileTag  . '
                                    <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $jenis . '" id_row="' . $row->id . '">
                                        <i class="trash alternate outline red icon"></i>
                                    </button>
                                </div>
                            </div>
                            <i class="teal large money check middle aligned icon"></i>
                            <div class="content">
                            <div class="header">' . $row->uraian . '</div>' . $gabung . '</div>
                        </div>');
                        break;
                    case 'informasi_umum':
                        break;
                    case 'y':
                        # code...
                        break;
                    case 'list':
                        break;
                    case 'value2':
                        # code...
                        break;
                    default:
                        # code...
                        break;
                }
            }
        } else { //jika data tidak ditemukan
            $rowData['list'] .= '<div class="ui icon info message"><i class="yellow exclamation circle icon"></i><div class="content"><div class="header">Data Tidak ditemukan </div><p>input data baru atau hubungi administrator</p></div></div></td></tr>';
        }
        // pagination
        if ($jmlhalaman > 1) {
            $id_aktif = 0;
            $batas_bawah = $halaman - 2;
            $batas_atas = $halaman + 2;
            if ($batas_bawah <= 1) {
                $batas_bawah = 1;
            } else {
                $pagination .= '<a class="item" name="page" hal="1" ret="go" tbl="' . $nama_tabel . '"><i class="angle double left chevron icon"></i></a>';
            }
            $paginationnext = "";
            if ($batas_atas < $jmlhalaman) {
                $batas_atas = $halaman + 2;
                $paginationnext = '<a class="item" name="page" hal="' . $jmlhalaman . '" ret="go" tbl="' . $nama_tabel . '"><i class="angle double right chevron icon"></i></a>';
            }
            //var_dump( $paginationnext );
            if ($batas_atas > $jmlhalaman) {
                $batas_atas = $jmlhalaman;
            }
            for ($i = $batas_bawah; $i <= $batas_atas; $i++) {
                if ($i != $halaman) {
                    $pagination .= '<a class="item" name="page" hal="' . $i . '" ret="go" tbl="' . $nama_tabel . '">' . $i . '</a>';
                } else {
                    $pagination .= '<a class="active item" tbl="' . $nama_tabel . '">' . $i . '</a>';
                    $id_aktif = $i;
                }
            }
            // panah preview
            if ($halaman == 1) {
                $pagination1 .= '<a class="disabled icon item"><i class="angle left icon"></i></a>';
            } else {
                $pagination1 .= '<a class="icon item"  jns="get_tbl" name="page" hal="' . $id_aktif . '" ret="prev" tbl="' . $nama_tabel . '"><i class="angle left icon"></i></a>';
            }
            // panah next
            if ($halaman == $jmlhalaman) {
                $pagination2 .= '<a class="disabled icon item"><i class="angle right icon"></i></a>';
            } else {
                $pagination2 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="next" jns="get_tbl" tbl="' . $nama_tabel . '"><i class="angle right icon"></i></a>';
            }
            //var_dump( $pagination );
            $rowData['foot'] = trim('<div class="ui center pagination menu">' . $pagination1 . $pagination . $paginationnext . $pagination2 . '</div>');
        } else {
            $rowData['foot'] = '';
        }
        return $rowData;
    }
    #menghitung jumlah decimal
    public function countDecimals($fNumber)
    {
        $fNumber = floatval($fNumber);
        for ($iDecimals = 0; $fNumber != round($fNumber, $iDecimals); $iDecimals++);
        return $iDecimals;
    }
    //
    public static function sortin($a, $b)
    {
        return strlen($b['kode']) - strlen($a['kode']);
    }
    public static function sortinNo_Sortir($a, $b)
    {
        //return $b->Rate <=> $a->Rate;
        return $a['no_sortir'] - $b['no_sortir'];
        /*
        usort($objects, function($a, $b) {
            return $b->Rate <=> $a->Rate;
        });
        */
    }

    public function backup_tables($tables = '*')
    {
        $DB = DB::getInstance();
        $data = "\n/*---------------------------------------------------------------" .
            "\n  TABLES: {$tables}" .
            "\n  ---------------------------------------------------------------*/\n";
        if ($tables == '*') { //get all of the tables
            $tables = array();
            $result = $DB->runQuery2("SHOW TABLES");
            foreach ($result as $row) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        foreach ($tables as $table) {
            $data .= "\n/*---------------------------------------------------------------" .
                "\n  TABLE: `{$table}`" .
                "\n  ---------------------------------------------------------------*/\n";
            $data .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $res = $DB->runQuery2("SHOW CREATE TABLE `{$table}`");
            $row = $res[0];
            $data .= $row[1] . ";\n";

            $result = $DB->runQuery2("SELECT * FROM `{$table}`");
            $num_rows = count($result[0]);

            if ($num_rows > 0) {
                $vals = array();
                $z = 0;
                for ($i = 0; $i < $num_rows; $i++) {
                    $items = $result[0];
                    $vals[$z] = "(";
                    for ($j = 0; $j < count($items); $j++) {
                        if (isset($items[$j])) {
                            $vals[$z] .= "'" . $DB->quote($items[$j]) . "'";
                        } else {
                            $vals[$z] .= "NULL";
                        }
                        if ($j < (count($items) - 1)) {
                            $vals[$z] .= ",";
                        }
                    }
                    $vals[$z] .= ")";
                    $z++;
                }
                $data .= "INSERT INTO `{$table}` VALUES ";
                $data .= "  " . implode(";\nINSERT INTO `{$table}` VALUES ", $vals) . ";\n";
            }
        }

        return $data;
    }
    public function tanggal($tanggal, $add = 0)
    {
        $nama_hari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
        $nama_bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $phpdate = strtotime($tanggal);
        $date = date_create($tanggal);

        $mysqldate = date('Y-m-d', $phpdate);
        $jam = date("h:i:s A", $phpdate);
        $hari = date("w", $phpdate);
        $tanggal_output = date("j", $phpdate);
        $bulan = date("n", $phpdate);
        $tahun = date("Y", $phpdate);
        $add = (int) $add - 1;

        //$phpdate = strtotime(date_format($date_add, "Y-m-d"));
        // $date_add = date_create($tanggal);
        // $phpdate = strtotime(date_format($date_add, "Y-m-d"));
        // date_add($phpdate, date_interval_create_from_date_string("$add days"));
        // $phpdate = strtotime(date_format($date_add, "Y-m-d"));
        $tanggal_add = strtotime($tanggal . " + $add days"); //date('Y-m-d', strtotime($tanggal . ' + $add days'));
        $tanggal_add2 = date('Y-m-d', $tanggal_add);
        $hari_add = date("w", $tanggal_add);
        //$tanggal_add = date("j", $phpdate);
        $bulan_add = date("n", $tanggal_add);
        $tahun_add = date("Y", $tanggal_add);
        return ['hari' => $nama_hari[$hari], 'tanggal' => $tanggal_output, 'bulan' => $nama_bulan[$bulan - 1], 'tahun' => $tahun, 'jam' => $jam, 'tanggalMysql' => $mysqldate, 'tgl' => "$nama_hari[$hari], $tanggal_output {$nama_bulan[$bulan - 1]} $tahun", 'tanggal_plus_add' => $tanggal_add2, 'tgl_plus_add' => "$nama_hari[$hari_add], $tanggal_output {$nama_bulan[$bulan_add - 1]} $tahun_add"];
    }
    public function selisihTanggal($tanggal1, $tanggal2) //$tanggal1 = '2000-01-25';$tanggal2 = '2010-02-20';
    {
        $ts1 = strtotime($tanggal1);
        $ts2 = strtotime($tanggal2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff_moon = (int) (($year2 - $year1) * 12) + ($month2 - $month1);
        $diff_moon = ($diff_moon <= 0) ? 1 : $diff_moon;
        //cari bulan
        // $weekends = 0;
        // $startDate = strtotime($tanggal1);
        // $endDate = strtotime($tanggal2);
        // var_dump($startDate);
        // while ($startDate < $endDate) {
        //     var_dump($startDate);
        //     //"N" gives ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 
        //     $day = date("N", $startDate);
        //     if ($day == 6 || $day == 7) {
        //         $weekends++;
        //     }
        //     $startDate = 24 * 60 * 60; //1 day
        // }
        // $interval = $ts1->diff($ts2);
        // echo (int)(($interval->days) / 7);
        $HowManyWeeks = (int) ((strtotime($tanggal2) - strtotime($tanggal1)) / 604800); //date('W', strtotime($tanggal2)) - date('W', strtotime($tanggal1));
        $HowManyWeeks = ($HowManyWeeks <= 0) ? 1 : $HowManyWeeks;
        return ['bulan' => $diff_moon, 'weekends' => $HowManyWeeks, 'tanggal1' => strtotime($tanggal1), 'tanggal2' => strtotime($tanggal2)];
    }
}
