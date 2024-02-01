<?php
require '../app/models/script/init.php';
$user = new User();
$user->cekUserSession();
$type_user = $_SESSION["user"]["type_user"];
$id_user = $_SESSION["user"]["id"];
$warna_tbl = $_SESSION["user"]["warna_tbl"];
$classRow = '';
$invertedColor = '';

$keyEnc = $_SESSION["user"]["key_encrypt"];
if ($warna_tbl != '' && $warna_tbl != 'non') {
    $classRow = ' class="' . $warna_tbl . '"';
    $invertedColor = ' inverted';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>seSendok</title>
    <link rel="stylesheet" href="<?= BASEURL; ?>vendor/node_modules/fomantic-ui/dist/semantic.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/admin.css">
    <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.png">

</head>

<body style="overflow: hidden;margin-top: 45px;">

    <!-- MAIN TOOLBAR MENU -->
    <div class="ui teal top fixed inverted main menu">
        <a class="item nabiila">
            <i class="sidebar icon"></i>
        </a>
        <div class="right menu">
            <div class="ui inline inverted dropdown item lain" id="countRow"><span><i class="list icon"></i></span><input type="hidden" name="countRow" value="5">
                <div class="text">5</div>
                <div class="menu">
                    <div class="item" data-value="all">All</div>
                    <div class="item selected" data-value="5">5</div>
                    <div class="item" data-value="10">10</div>
                    <div class="item" data-value="15">15</div>
                    <div class="item" data-value="20">20</div>
                    <div class="item" data-value="30">30</div>
                    <div class="item" data-value="40">40</div>
                    <div class="item" data-value="50">50</div>
                    <div class="item" data-value="100">100</div>
                </div>
            </div>
            <div class="item">
                <div class="ui inverted transparent icon input">
                    <input type="text" placeholder="Search..." name="cari_data" id="cari_data">
                    <i class="search link icon"></i>
                </div>
            </div>
            <div class="right inverted menu">
                <div class="ui dropdown item lain"><span><i class="user icon"></i></span><i class="dropdown icon"></i>
                    <div class="menu"><a class="item" data-tab="wallchat"><i class="circular comments outline icon"></i>Pesan</a><a class="item" name="change_themes"><i class="circular moon icon"></i>Change Themes</a><a class="item" data-tab="profil"><i class="circular qrcode icon"></i>Pengaturan</a><a class="item" onclick="window.location.href='home/logout'"><i class="circular sign out alternate icon"></i>Log Out</a></div>
                </div>
            </div>
        </div>
    </div>

    <div class="ui bottom attached stackable pushable" style="height: calc(100vh - 45px) !important;margin-top: 45px;">
        <!-- sidebar-->
        <div class="ui inverted left vertical sidebar menu">
            <div class="item">
                <h2 class="ui inverted center red aligned icon header dash_header"><i class="circular colored blue building icon"></i>
                    <div class="content">seSendok <span id="kopku" style="color: darkcyan!important;font-style: italic"></span>
                        <div class="sub header">Manage Project</div>
                    </div>
                </h2>
            </div>
            <div class="item">
                <div class="ui inverted transparent icon input">
                    <input type="text" placeholder="Search mail...">
                    <i class="search icon"></i>
                </div>
            </div>
            <a class="item" href="#" data-tab="tab_home"><i class="home icon"></i>Home</a>
            <div class="ui accordion inverted item">
                <div class="title item"><i class="dropdown icon"></i><span></span>Anggaran </div>
                <div class="content">
                    <a class="item nabiila" href="#" data-tab="tab_renstra"><span><i class="toggle on icon"></i></span><i class="purple sitemap icon"></i>RENSTRA</a>
                    <a class="item nabiila" href="#" data-tab="tab_renja"><span><i class="toggle on icon"></i></span><i class="violet users cog icon"></i>RENJA</a>
                    <a class="item nabiila" href="#" data-tab="tab_dpa"><span><i class="toggle on icon"></i></span><i class="yellow snowplow icon"></i>DPA</a>

                </div>
            </div>
            <a class="item" href="#" data-tab="tab_kontrak"><i class="money icon"></i>Kontrak</a>
            <div class="ui accordion inverted item">
                <div class="title item"><i class="dropdown icon"></i><span></span>Realisasi</div>
                <div class="content">
                    <a class="item" href="#" data-tab="tab_input_real"><span><i class="toggle on icon"></i></span><i class="purple sitemap icon"></i>Input Realisasi</a>
                    <a class="item" href="#" data-tab="tab_spj"><span><i class="toggle on icon"></i></span><i class="violet users cog icon"></i>SPJ</a>
                    <a class="item" href="#" data-tab="tab_lap"><span><i class="toggle on icon"></i></span><i class="yellow snowplow icon"></i>Laporan</a>

                </div>
            </div>
            <div class="ui accordion inverted item">
                <div class="title item"><i class="dropdown icon"></i>Referensi</div>
                <div class="content">
                    <a class="item" href="#" data-tab="tab_ref" tbl="bidang_urusan"><span><i class="toggle on blue icon"></i></span><i class="user plus icon"></i>Bidang Urusan</a>
                    <a class="item" href="#" data-tab="tab_ref" tbl="prog"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>Program</a>
                    <a class="item" href="#" data-tab="tab_ref" tbl="keg"><span><i class="toggle on blue icon"></i></span><i class="outdent icon"></i>Kegiatan</a>
                    <a class="item" href="#" data-tab="tab_ref" tbl="sub_keg"><span><i class="toggle on blue icon"></i></span><i class="file icon"></i>Sub Kegiatan</a>
                    <a class="item" href="#" data-tab="tab_ref" tbl="akun_belanja"><span><i class="toggle on blue icon"></i></span><i class="file icon"></i>Akun</a>
                    <a class="item" href="#" data-tab="tab_ref" tbl="sumber_dana"><span><i class="toggle on blue icon"></i></span><i class="file icon"></i>Sumber Dana</a>
                    <a class="item" href="#" data-tab="tab_peraturan" tbl="peraturan"><span><i class="toggle on blue icon"></i></span><i class="gavel icon"></i>Peraturan</a>
                    <a class="item" href="#" data-tab="tab_ref" tbl="rekanan"><span><i class="toggle on blue icon"></i></span><i class="book reader icon"></i>Rekanan</a>
                </div>
            </div>
            <div class="ui accordion inverted item">
                <div class="title item"><i class="dropdown icon"></i>Standar Harga Satuan</div>
                <div class="content">
                    <a class="item" href="#" data-tab="tab_hargasat" tbl="ssh"><span><i class="toggle on blue icon"></i></span><i class="user plus icon"></i>SSH</a>
                    <a class="item" href="#" data-tab="tab_hargasat" tbl="hspk"><span><i class="toggle on blue icon"></i></span><i class="users icon"></i>HSPK</a>
                    <a class="item" href="#" data-tab="tab_hargasat" tbl="asb"><span><i class="toggle on blue icon"></i></span><i class="outdent icon"></i>ASB</a>
                    <a class="item" href="#" data-tab="tab_hargasat" tbl="sbu"><span><i class="toggle on blue icon"></i></span><i class="file icon"></i>SBU</a>
                </div>
            </div>
            <!-- ut admin-->
            <?php echo $retVal = ($type_user == 'admin') ? '<a class="item" href="#" data-tab="reset"><i class="erase icon"></i>Reset Tabel</a><a class="item" href="#" data-tab="pengaturan" tbl="pengaturan"><i class="comments outline icon"></i>Pengaturan</a>' : ''; ?>

            <a class="item" href="#" data-tab="wallchat"><i class="comments outline icon"></i>Pesan</a>
            <a class="item" href="#" data-tab="profil" tbl="list"><i class="user icon"></i>Profil</a>

        </div>
        <!-- flyout-->
        <div class="ui right flyout">
            <i class="close icon"></i>
            <div class="ui header"><i class="folder icon" name="icon_flyout"></i>
                <div class="content" name="content_flyout">Lengkapi Data </div>
            </div>
            <form class="ui form scrolling content" name="form_flyout">
            </form>
            <div class="left actions">
                <div class="ui red cancel button"><i class="remove icon"></i>Tutup </div>
                <div class="ui green ok button"><i class="checkmark icon"></i>Submit </div>
            </div>
        </div>
        <div class="pusher">

            <!-- sticky-->
            <div class="ui sticky">
                <div class="ui icon message dashboard"><i class="home icon"></i>
                    <div class="content">
                        <div class="header">DASHBOARD</div>
                        <div class="pDashboard">seSendok</div>
                    </div>
                </div>
            </div>
            <!-- ============== -->
            <!-- tab home -->
            <!-- ============== -->
            <div class="ui tab basic segment active" data-tab="tab_home">
                <div class="main ui intro container">
                    <h2 class="ui dividing header">Pengantar untuk <?php echo $type_user ?> </h2>
                    <div class="ui large info message">
                        <h2 class="ui header dash_header"><i class="settings icon"></i>
                            <div class="content">seSendok <div class="sub header">merupakan aplikasi perencanaan, angaran dan realisasi Berbasis web</div>
                            </div>
                        </h2>
                    </div>
                    <div class="ui info message">
                        <h3 class="ui header dash_header"><i class="upload icon"></i>
                            <div class="content">menginpor file pada aplikasi ? <div class="sub header">
                                    <div class="ui divided selection list">
                                        <li class="item">file yang di Impor harus extension <a class="ui green label custom csv_format"><i class="file excel icon"></i>xlsx</a>, file template pengimporan dapat di download di <a class="ui teal tag label">menu data umum</a></li>
                                        <li class="item">Format angka menggunakan regional Indonesia <a class="ui blue label"><i class="money check icon"></i>pengelompokan " . "</a><a class="ui blue label"><i class="money check icon"></i>desimal " , "</a><a class="ui blue label"><i class="money check icon"></i>contoh "1.200.000,50"</a></li>
                                    </div>
                                </div>
                            </div>
                        </h3>
                    </div>
                    <div class="ui action input multi">
                        <input type="text" placeholder="Search...">
                        <input type="text" placeholder="Search...">
                        <input type="text" placeholder="Search...">
                        <button class="ui button">Search</button>
                    </div>
                    <h2 class="ui dividing header">Cara menggunakan <a class="anchor"></a></h2>
                    <p>Tutorial cara menggunakan aplikasi AHSP untuk penyusunan anggaran dapat di download <a href="<?= BASEURL; ?>template/tutorial_user.pdf" target="_blank">disini</a></p>
                    <h2 class="ui dividing header">Editorial<a class="anchor"></a></h2>
                    <div class="ui items">
                        <div class="item">
                            <div class="image"><img src="img/bps.png"></div>
                            <div class="content"><a class="header" href="https://mamujuutarakab.bps.go.id" target="_blank">Pasangkayu dalam angka</a>
                                <div class="meta"><span>Description</span></div>
                                <div class="description">
                                    <p>Badan Pusat Statistik adalah Lembaga Pemerintah Non Kementerian yang bertanggung jawab langsung kepada Presiden. Sebelumnya, BPS merupakan Biro Pusat Statistik, yang dibentuk berdasarkan UU Nomor 6 Tahun 1960 tentang Sensus dan UU Nomer 7 Tahun 1960 tentang Statistik. Sebagai pengganti kedua UU tersebut ditetapkan UU Nomor 16 Tahun 1997 tentang Statistik. Berdasarkan UU ini yang ditindaklanjuti dengan peraturan perundangan dibawahnya, secara formal nama Biro Pusat Statistik diganti menjadi Badan Pusat Statistik.</p>
                                </div>
                                <div class="extra">Additional Details </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="image"><img src="img/logo_garuda.png"></div>
                            <div class="content"><a class="header" href="http://lpse.pasangkayukab.go.id" target="_blank">LPSE Kabupaten Pasangkayu</a>
                                <div class="meta"><span>Description</span></div>
                                <div class="description">
                                    <p>Layanan Pengadaan Secara Elektronik adalah layanan pengelolaan teknologi informasi untuk memfasilitasi pelaksanaan Pengadaan Barang/Jasa secara elektronik. UKPBJ/Pejabat Pengadaan pada Kementerian/Lembaga/Perangkat Daerah yang tidak memiliki Layanan Pengadaan Secara Elektronik dapat menggunakan fasilitas Layanan Pengadaan Secara Elektronik yang terdekat dengan tempat kedudukannya untuk melaksanakan pengadaan secara elektronik. Selain memfasilitasi UKPBJ/Pejabat Pengadaan dalam melaksanakan pengadaan barang/jasa secara elektronik Layanan Pengadaan Secara Elektronik juga melayani registrasi penyedia barang dan jasa yang berdomisili di wilayah kerja Layanan Pengadaan Secara Elektronik yang bersangkutan.. </p>
                                </div>
                                <div class="extra">Additional Details </div>
                            </div>
                        </div>
                    </div>
                    <p></p>
                    <p></p>
                </div>
                <div class="ui vertical footer segment">
                    <div class="three column divided stackable center aligned ui grid">
                        <div class="column">
                            <div class="ui icon header"><i class="teal rocket circular icon"></i>AHSP : <a href="javascript: void(0)">efisiensi dan efektif</a></div>
                        </div>
                        <div class="column">
                            <div class="ui icon header"><i class="teal theme circular icon"></i>transparansi, <a href="javascript: void(0)">akuntabilitas</a></div>
                        </div>
                        <div class="column">
                            <div class="ui icon header"><i class="teal food circular icon"></i>serta <a href="javascript: void(0)">partisipatif</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============== -->
            <!-- tab_kontrak -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_kontrak">
            </div>
            <!-- ============== -->
            <!-- tab_input_real -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_input_real">
            </div>
            <!-- ============== -->
            <!-- tab_spj -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_spj">
            </div>
            <!-- ============== -->
            <!-- tab_lap -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_lap">
            </div>
            <!-- ============== -->
            <!-- tab_renstra -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_renstra">
            </div>
            <!-- ============== -->
            <!-- tab_renja -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_renja">
            </div>
            <!-- ============== -->
            <!-- tab_dpa -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="tab_dpa">
            </div>
            <!-- ============== -->
            <!-- tab_referensi -->
            <!-- ============== -->
            <div class="ui tab basic segment container" data-tab="tab_ref" tbl="">
                <div class="ui info message" name="ketref">Nabiilainayah</div>
                <div class="ui hidden divider"></div>
                <div class="ui right floated basic icon buttons">
                    <?php
                    if ($type_user == 'admin') {
                        echo '<button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add"><i class="plus icon"></i></button>
                            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>';
                    }
                    ?>
                    <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="peraturan" type="submit"><i class="alternate download icon"></i></button>
                </div>
                <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
                <div class="ui hidden divider"></div>
                <div class="ui hidden divider"></div>
                <table class="ui very basic table">
                    <thead>
                        <tr>
                            <th>Kode Komponen</th>
                            <th>Uraian Komponen</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>TKDN</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
            </div>

            <!-- ============ -->
            <!-- tab_peraturan -->
            <!-- ============== -->
            <div class="ui tab basic segment container" data-tab="tab_peraturan" tbl="peraturan">
                <div class="ui hidden divider"></div>
                <div class="ui right floated basic icon buttons">
                    <?php
                    if ($type_user == 'admin') {
                        echo '<button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center" jns="add"><i class="plus icon"></i></button>
                            <button class="ui button" name="flyout" jns="import" data-tooltip="Import XLSX" data-position="bottom center" jns="import"><i class="upload icon"></i></button>';
                    }
                    ?>
                    <button class="ui button" data-tooltip="Download" data-position="bottom center" name="ungguh" jns="dok" tbl="peraturan" type="submit"><i class="alternate download icon"></i></button>
                </div>
                <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
                <div class="ui hidden divider"></div>
                <div class="ui hidden divider"></div>
                <table class="ui very basic table">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Uraian</th>
                            <th>Tanggal Pengundangan</th>
                            <th>Keterangan</th>
                            <th>Tautan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">
                                <div class="ui right floated pagination menu">
                                    <a class="icon item">
                                        <i class="left chevron icon"></i>
                                    </a>
                                    <a class="item">1</a>
                                    <a class="item">2</a>
                                    <a class="item">3</a>
                                    <a class="item">4</a>
                                    <a class="icon item">
                                        <i class="right chevron icon"></i>
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- ============== -->
            <!-- tab_hargasat -->
            <!-- ============== -->
            <div class="ui tab basic segment container" data-tab="tab_hargasat">
                <div class="ui info message" name="kethargasat">Nabiilainayah</div>
                <div class="ui hidden divider"></div>
                <div class="ui right floated basic icon buttons">
                    <button class="ui button" data-tooltip="Tambah Data" data-position="bottom center"><i class="plus icon"></i></button>
                    <button class="ui button" data-tooltip="Import XLSX" data-position="bottom center"><i class="upload icon"></i></button>
                    <button class="ui button" data-tooltip="Download" data-position="bottom center"><i class="alternate download icon"></i></button>
                </div>
                <h3 class="ui dividing header"><i class="left align icon"></i>Tabel Dokumen</h3>
                <div class="ui hidden divider"></div>
                <div class="ui hidden divider"></div>
                <table class="ui very basic table">
                    <thead>
                        <tr>
                            <th>Kode Komponen</th>
                            <th>Uraian Komponen</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>TKDN</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- ============== -->
            <!-- reset -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="reset">
                <div class="ui grid stackable container">
                    <div class="column">
                        <div class="ui placeholder segment">
                            <div class="ui two column stackable center aligned grid">
                                <div class="ui vertical divider">Or</div>
                                <div class="middle aligned row">
                                    <div class="column">
                                        <div class="ui icon header">
                                            <i class="world icon"></i>
                                            Backup Tabel
                                        </div>
                                        <div class="inline">
                                            <div class="ui buttons">
                                                <button class="ui blue button">&nbsp;&nbsp;&nbsp;&nbsp;All&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                                <div class="or"></div>
                                                <button class="ui positive button">Proyek</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="ui icon header">
                                            <i class="world icon"></i>
                                            Restore Tabel
                                        </div>
                                        <div class="inline">
                                            <div class="ui buttons">
                                                <button class="ui blue button">&nbsp;&nbsp;&nbsp;&nbsp;All&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                                <div class="or"></div>
                                                <button class="ui positive button">Proyek</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ui three stackable cards">
                            <?php
                            $data_card = new stdClass;
                            $data_card->peraturan = ['header' => 'Peraturan', 'meta' => 'peraturan terkait aplikasi', 'description' => 'ketentuan yang dengan sendirinya memiliki suatu makna normatif; ketentuan yang menyatakan bahwa sesuatu harus (tidak harus) dilakukan, atau boleh (tidak boleh) dilakukan', 'icon' => 'teal road'];
                            $data_card->sumber_dana = ['header' => 'Sumber Dana', 'meta' => 'Sumber dana kegiatan', 'description' => 'Klasifikasi, Kodefikasi, dan Nomenklatur Sumber Pendanaan ditujukan untuk memberikan informasi atas sumber dana berdasarkan tujuan penggunaan dana dari setiap pelaksanaan urusan pemerintahan daerah yang dijabarkan berdasarkan program, kegiatan dan sub kegiatan dalam rangka pengendalian masing-masing kelompok dana meliputi pengawasan/control, akuntabilitas/accountability dan transparansi/transparency (CAT).', 'icon' => 'purple money'];
                            $data_card->akun_belanja = ['header' => 'Akun Belanja', 'meta' => 'Aplikasi Standar Satuan Harga (SSH)', 'description' => 'sebagai perhitungan kebutuhan biaya Tenaga Kerja, bahan, dan peralatan', 'icon' => 'teal money bill alternate outline'];
                            $data_card->sub_keg = ['header' => 'Sub Kegiatan', 'meta' => 'SUB KEGIATAN', 'description' => 'sebagai perhitungan kebutuhan biaya Tenaga Kerja, bahan, dan peralatan', 'icon' => 'violet users cog'];
                            $data_card->analisa_alat = ['header' => 'Analisa Peralatan', 'meta' => 'Aplikasi Standar Satuan Harga (SSH)', 'description' => 'Alat berat merupakan alat yang digunakan untuk mempermudah proses pekerjaan sehingga menjadi lebih cepat, mudah dan hasilnya sesuai dengan harapan', 'icon' => 'yellow snowplow'];
                            $data_card->analisa_quarry = ['header' => 'Analisa Quarry', 'meta' => 'Analisa satuan dasar satuan bahan', 'description' => 'quarry atau lahan tambang terbuka', 'icon' => 'olive truck monster'];
                            $data_card->analisa_bm = ['header' => 'AHSP Bidang Bina Marga', 'meta' => 'Analisis Harga Satuan Pekerjaan', 'description' => 'acuan untuk menghitung harga satuan pekerjaan (HSP) yang menganalisis biaya upah tenaga kerja dan/atau harga bahan-bahan bangunan ataupun peralatan sebagai koefisien kebutuhan penggunaan tenaga kerja, bahan dan peralatan untuk satu satuan kuantitas pekerjaan', 'icon' => 'teal road'];
                            $data_card->analisa_ck = ['header' => 'AHSP Bidang Cipta Karya', 'meta' => 'Analisis Harga Satuan Pekerjaan', 'description' => 'acuan untuk menghitung harga satuan pekerjaan (HSP) yang menganalisis biaya upah tenaga kerja dan/atau harga bahan-bahan bangunan ataupun peralatan sebagai koefisien kebutuhan penggunaan tenaga kerja, bahan dan peralatan untuk satu satuan kuantitas pekerjaan', 'icon' => 'green city'];
                            $data_card->analisa_sda = ['header' => 'AHSP Bidang Sumber Daya Air', 'meta' => 'Analisis Harga Satuan Pekerjaan', 'description' => 'acuan untuk menghitung harga satuan pekerjaan (HSP) yang menganalisis biaya upah tenaga kerja dan/atau harga bahan-bahan bangunan ataupun peralatan sebagai koefisien kebutuhan penggunaan tenaga kerja, bahan dan peralatan untuk satu satuan kuantitas pekerjaan', 'icon' => 'blue water'];
                            $data_card->monev = ['header' => 'Realisasi', 'meta' => 'realisasi fisik dan keuangan', 'description' => 'merupakan realisasi fisik dan keungan AHSP', 'icon' => 'teal money'];
                            if ($type_user == 'admin') {
                                $data_card->satuan = ['header' => 'Satuan', 'meta' => 'Ukuran suatu besaran', 'description' => 'Satuan atau satuan ukur atau unit digunakan untuk memastikan kebenaran pengukuran', 'icon' => 'user plus'];
                                $data_card->divisi = ['header' => 'Divisi', 'meta' => 'Task HSP', 'description' => 'Pembagian divisi pekerjaan', 'icon' => 'teal users cog'];
                                $data_card->chat = ['header' => 'Ruang Chating', 'meta' => 'Chat, message', 'description' => 'ruang di peruntukkan chat', 'icon' => 'comments outline'];
                                $data_card->rekanan = ['header' => 'Rekanan', 'meta' => 'Rekanan', 'description' => 'data rekanan yang terdaftar', 'icon' => 'users'];
                            }


                            foreach ($data_card as $key => $value) {
                                if ($type_user == 'user') {
                                    $button = '<button class="ui fluid orange button" name="del_row" jns="del_proyek" tbl="' . $key . '">Hapus Dokumen</button>';
                                    $nButton = '';
                                } else {
                                    $button = '<div class="ui three buttons"><div class="ui teal button" name="del_row" jns="' . $key . '" tbl="dell_all">All</div>
                                    <button class="ui blue button" name="del_row" jns="del_proyek" tbl="' . $key . '">Dokumen</button>
                                    <div class="ui violet button" name="del_row" jns="reset" tbl="' . $key . '">Reset</div></div>';
                                    $nButton = 'three';
                                }
                                echo '<div class="card">
                                <div class="content"><i class="right floated large ui bordered colored ' . $value['icon'] . ' icon"></i>
                                    <div class="header">' . $value['header'] . '</div>
                                    <div class="meta">' . $value['meta'] . '</div>
                                    <div class="description">' . $value['description'] . '</div>
                                </div><div class="extra content">' . $button . ' </div></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============== -->
            <!-- tab_pengaturan -->
            <!-- ============== -->
            <div class="ui tab basic segment container" data-tab="pengaturan">
                <div class="ui segments">
                    <div class="ui teal inverted segment top attached">
                        <h2 class="ui header left">
                            <i class="settings icon"></i>
                            <div class="content">
                                Sesendok Settings
                                <div class="sub header">Manage your preferences</div>
                            </div>
                        </h2>
                    </div>
                    <form class="ui form scrolling segment" jns="add" tbl="pengaturan" name="form_pengaturan">
                        <div class="field">
                            <label>Tahun</label>
                            <div class="ui fluid search selection dropdown" name="tahun">
                                <input type="hidden" name="tahun">
                                <i class="dropdown icon"></i>
                                <div class="default text">Tahun Anggaran</div>
                                <div class="menu">
                                    <div class="item" data-value="2020"><i class="podcast icon"></i>2020</div>
                                    <div class="item" data-value="2021"><i class="podcast icon"></i>2021</div>
                                    <div class="item" data-value="2022"><i class="podcast icon"></i>2022</div>
                                    <div class="item" data-value="2023"><i class="podcast icon"></i>2023</div>
                                    <div class="item" data-value="2024"><i class="podcast icon"></i>2024</div>
                                    <div class="item" data-value="2025"><i class="podcast icon"></i>2025</div>
                                    <div class="item" data-value="2026"><i class="podcast icon"></i>2026</div>
                                    <div class="item" data-value="2027"><i class="podcast icon"></i>2027</div>
                                </div>
                            </div>
                        </div>
                        <h4 class="ui horizontal divider header">
                            <i class="tag icon"></i>
                            Description
                        </h4>
                        <div class="field">
                            <label>Anggaran</label>
                            <div class="ui fluid search selection dropdown" name="aturan_anggaran">
                                <input type="hidden" name="aturan_anggaran">
                                <i class="dropdown icon"></i>
                                <div class="default text">Anggaran</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Pengadaan Barang/Jasa</label>
                            <div class="ui fluid search selection dropdown" name="aturan_pengadaan">
                                <input type="hidden" name="aturan_pengadaan">
                                <i class="dropdown icon"></i>
                                <div class="default text">aturan pengadaan</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Akun Belanja</label>
                            <div class="ui fluid search selection dropdown" name="aturan_akun">
                                <input type="hidden" name="aturan_akun">
                                <i class="dropdown icon"></i>
                                <div class="default text">Akun Belanja</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Sumber Dana</label>
                            <div class="ui fluid search selection dropdown" name="aturan_sumber_dana">
                                <input type="hidden" name="aturan_sumber_dana">
                                <i class="dropdown icon"></i>
                                <div class="default text">Sumber Dana</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Sub Kegiatan</label>
                            <div class="ui fluid search selection dropdown" name="aturan_sub_kegiatan">
                                <input type="hidden" name="aturan_sub_kegiatan">
                                <i class="dropdown icon"></i>
                                <div class="default text">Sub Kegiatan</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>SSH</label>
                            <div class="ui fluid search selection dropdown" name="aturan_ssh">
                                <input type="hidden" name="aturan_ssh">
                                <i class="dropdown icon"></i>
                                <div class="default text">SSH</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>HSPK</label>
                            <div class="ui fluid search selection dropdown" name="aturan_hspk">
                                <input type="hidden" name="aturan_hspk">
                                <i class="dropdown icon"></i>
                                <div class="default text">HSPK</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>ASB</label>
                            <div class="ui fluid search selection dropdown" name="aturan_asb">
                                <input type="hidden" name="aturan_asb">
                                <i class="dropdown icon"></i>
                                <div class="default text">ASB</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>SBU</label>
                            <div class="ui fluid search selection dropdown" name="aturan_sbu">
                                <input type="hidden" name="aturan_sbu">
                                <i class="dropdown icon"></i>
                                <div class="default text">SBU</div>
                                <div class="menu">

                                </div>
                            </div>
                        </div>
                        <div class="field"><label>Keterangan</label><textarea name="keterangan" rows="4"></textarea></div>
                        <div class="field"><label></label><div class="ui toggle checkbox"><input type="checkbox" name="disable" non_data=""><label>Non Aktif</label></div></div>
                        <div class="ui icon success message"><i class="check icon"></i><div class="content"><div class="header">Form sudah lengkap</div><p>anda bisa submit form</p></div></div><div class="ui error message"></div>
                        <button style="display: none;" type="submit" id="form-atur"></button>
                    </form>
                    <div class="ui yellow segment left actions">
                        <label class="ui green button" for="form-atur" tabindex="0">Simpan</label>
                    </div>
                </div>
            </div>
            <!-- ============== -->
            <!-- wallchat -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="wallchat">
            </div>
            <!-- ============== -->
            <!-- profil -->
            <!-- ============== -->
            <div class="ui tab basic segment" data-tab="profil">
            </div>
            <!-- =========================-->
            <!-- =========================-->
            <!-- =========================-->
            <div class="ui basic modal info">
                <div class="ui icon header" id="kop_notifikasi"><i class="archive icon"></i>Archive Old Messages </div>
                <div class="content">
                    <div class="ui center aligned stackable container grid" id="conten_notifikasi">
                        <p>ini di isi oleh ajax</p>
                    </div>
                </div>
                <div class="actions">
                    <div class="ui green ok inverted button center aligned"><i class="checkmark icon"></i>OK </div>
                </div>
            </div>

            <!-- modal general -->
            <div class="ui modal mdl_general" name="mdl_general">
                <h5 class="ui header dash_header">
                    <i class="big icons">
                        <i class="puzzle icon"></i>
                        <i class="bottom right teal corner add icon"></i>
                    </i></i>
                    <div class="content">AHSP <div class="sub header">Data</div>
                    </div>
                </h5>
                <form class="ui form scrolling content" name="form_modal">form umum ji
                    <div class="ui icon success message">
                        <i class="check icon"></i>
                        <div class="content">
                            <div class="header">Form sudah lengkap</div>
                            <p>anda bisa submit form</p>
                        </div>
                    </div>
                    <div class="ui error message"></div>
                </form>
                <div class="actions">
                    <div class="ui red cancel button"><i class="remove icon"></i>Cancel </div>
                    <div class="ui green ok button add"><i class="checkmark icon"></i>OK </div>
                </div>
            </div>
            <!-- modal kedua -->
            <div class="ui modal kedua" name="mdl_kedua">
                <h5 class="ui header dash_header"><i class="users cog icon"></i>
                    <div class="content">AHSP <div class="sub header">Ubah Data</div>
                    </div>
                </h5>
                <form class="ui form scrolling content" name="form_modal_kedua">
                    <div class="ui icon success message">
                        <i class="check icon"></i>
                        <div class="content">
                            <div class="header">Form sudah lengkap</div>
                            <p>anda bisa submit form</p>
                        </div>
                    </div>
                    <div class="ui error message"></div>
                </form>
                <div class="actions">
                    <div class="ui red cancel button"><i class="remove icon"></i>Cancel </div>
                    <div class="ui green ok button add"><i class="checkmark icon"></i>OK </div>
                </div>
            </div>
            <!-- jangan dihapus file button <label>  -->
            <form hidden action="script/writer_xlsx" method="post" id="form_ungguh_dok">
                <input hidden type="text" name="jenis">
                <input hidden type="text" name="tbl">
                <input hidden type="text" name="dok">
                <input hidden type="text" name="nabiila">
            </form>
            <!-- jangan dihapus file button <label>  -->
            <input type="file" id="invisibleupload1" class="ui invisible file input" name="file_invisible">
            <!-- jangan dihapus untuk download hasil  -->
            <a name="tempat_download" hidden href="" target="_blank"></a>
            <!-- jmodal hapus  -->
            <div class="ui basic mini inverted hapus modal">
                <div class="ui icon header" id="kop_notif_hapus"></div>
                <div id="content_notif" class="ui center content aligned"></div>
                <div class="ui actions center aligned container grid">
                    <div class="ui green basic cancel button"><i class="remove icon"></i>No </div>
                    <div class="ui red basic ok button"><i class="checkmark icon"></i>Yes </div>
                </div>
            </div>

        </div>

    </div>

    <!-- <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>-->
    <script src="<?= BASEURL; ?>vendor/jquery-3.7.1.min.js"></script>
    <script src="<?= BASEURL; ?>vendor/node_modules/fomantic-ui/dist/semantic.js">
    </script>
    <script src="<?= BASEURL; ?>js/accounting.js"></script>
    <script src="<?= BASEURL; ?>js/mathbiila.min.js"></script>
    <script src="<?= BASEURL; ?>js/jqmath-etc-0.4.6.min.js"></script>
    <script src="<?= BASEURL; ?>js/xlsx.js"></script>
    <script src="<?= BASEURL; ?>vendor/node_modules/crypto-js/crypto-js.js"></script>
    <script src="<?= BASEURL; ?>js/Encryption.js"></script>
    <script type="text/javascript">
        const halamanDefault = '<?= $keyEnc; ?>';
    </script>
    <script src="<?= BASEURL; ?>js/index.js"></script>

</html>