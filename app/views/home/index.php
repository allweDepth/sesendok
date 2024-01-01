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

<body>
    <div class="ui teal top attached inverted stackable menu">
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
            <a class="ui item" onclick="window.location.href='home/logout'">
                Logout
            </a>
        </div>
    </div>
    <div class="ui bottom attached segment stackable pushable">

        <!-- sidebar-->
        <div class="ui inverted left vertical sidebar menu" style="">
            <div class="item">
                <div class="ui transparent icon input">
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
                    <a class="item" href="#" data-tab="tab_ref" tbl="peraturan"><span><i class="toggle on blue icon"></i></span><i class="book reader icon"></i>Peraturan</a>
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
            <a class="item" href="#" data-tab="reset"><i class="erase icon"></i>Reset Tabel</a>
            <a class="item" href="#" data-tab="wallchat"><i class="comments outline icon"></i>Pesan</a>
            <a class="item" href="#" data-tab="profil" tbl="list"><i class="user icon"></i>Profil</a>

        </div>

        <!-- flyout-->
        <div class="ui flyout right" tabindex="-1">
            <i class="close icon"></i>
            <div class="ui header">
                <i class="question icon"></i>
                <div class="content">
                    Archive Old Messages
                </div>
            </div>
            <div class="scrolling content" style="min-height: 240.4375px;">
                <p>Your inbox is getting full, would you like us to enable automatic archiving of old messages?</p>
            </div>
            <div class="actions">
                <div class="ui red cancel button">
                    <i class="remove icon"></i>
                    No
                </div>
                <div class="ui green ok button">
                    <i class="checkmark icon"></i>
                    Yes
                </div>
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
            <div class="ui basic segment">
                <!-- ============== -->
                <!-- tab home -->
                <!-- ============== -->
                <div class="ui tab basic segment active" data-tab="home">
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
                    <div class="ui red vertical footer segment">
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
                <div class="ui tab basic segment container" data-tab="tab_ref">
                    <div class="ui info message" name="ketref">Nabiilainayah</div>
                    <div class="ui hidden divider"></div>
                    <div class="ui right floated basic icon buttons">
                        <button class="ui button" name="flyout" data-tooltip="Tambah Data" data-position="bottom center"><i class="plus icon"></i></button>
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
                <!-- wallchat -->
                <!-- ============== -->
                <div class="ui tab basic segment" data-tab="wallchat">
                </div>
                <!-- ============== -->
                <!-- profil -->
                <!-- ============== -->
                <div class="ui tab basic segment" data-tab="profil">
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