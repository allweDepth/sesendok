<?php
// require_once("class/FormulaParser.php");
// require 'init.php';

use FormulaParser\FormulaParser;


//$jenis = 'koef'(rumus koefisien dan hasil pencarian ke koef semua) $jenis = 'harga'(untuk rumus di tempatkan harga satuan dan pencarian juga di harga satuan) atau $jenis = 'koef_harga'(untuk rumus ke koefisien, dan harga pencarian di harga satuan)
class MasterFungsi
{
    public function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false) {
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
    
    public function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } else if (is_string ($mixed)) {
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
                $userWithIdPengirim = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_pengirim]);
                switch ($type) {
                    case 'outbox':
                        if ($id_tujuan != 'all') {
                            // $userWithId = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_tujuan]);
                            $userWithId = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_pengirim]);
                        }
                        break;
                    default:
                        $userWithId = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_pengirim]);
                        break;
                };
                if ($id_tujuan != 'all') {
                    $namaPengirimTampak = $userWithId->nama;
                    $photo = $userWithId->photo;
                    $photo = explode('/', $photo);
                    ($photo[0] != 'img') ? $photo[0]='img' :  0;
                    $photo = implode('/',$photo);
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

                $userWithIdPengirim = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_pengirim]);
                switch ($jenis) {
                    case 'outbox':
                        if ($id_tujuan != 'all') {
                            $userWithId = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_tujuan]);
                        }
                        break;
                    default:
                        $userWithId = $DB->getWhereOnce('user_ahsp', ['id', '=', $id_pengirim]);
                        break;
                };
                //deskripsi jika jenis=outbox atau inbox
                if ($id_tujuan != 'all') {
                    $namaPengirimTampak = $userWithId->nama;
                    $photo = $userWithId->photo;
                    $photo = explode('/', $photo);
                    ($photo[0] != 'img') ? $photo[0]='img' :  0;
                     $photo = implode('/',$photo);
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
    public function importFile($jenis, $kd_proyek = '', $nameFileDel = '')
    {
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $edit_user = $_SESSION["user"]["aktif_edit"];
        $id_user = $_SESSION["user"]["id"];
        $maxsize = 1024 * 8000;
        $fileName = 'avatar.jpg';
        $nama_files_hapus = '';
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
        switch ($jenis) {
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
                $path1 = 'upload';
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
            if (file_exists($nameFileDel) && strlen($nameFileDel)) {
                unlink($nameFileDel);
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
    //get tabel data
    public function getTabel($jenis, $nama_tabel = '', $get_data = [], $jmlhalaman = 0, $halaman = 1, $jumlah_kolom = 1, $type_user = 'user')
    {
        //var_dump("dmn($get_data)");
        //ambil data user untuk warna
        $user = new User();
        $user->cekUserSession();
        $type_user = $_SESSION["user"]["type_user"];
        $warna_tbl = $_SESSION["user"]["warna_tbl"];
        
        $id_user = $_SESSION["user"]["id"];
        $DB = DB::getInstance();
        $userAktif = $DB->getWhereCustom('user_ahsp', [['id', '=', $id_user]]);
        $jumlahArray = is_array($userAktif) ? count($userAktif) : 0;
        $classRow = '';
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

        $jumlahArray = is_array($get_data) ? count($get_data) : 0;
        if ($jumlahArray > 0) {
            // jika tabel dibuat bersama header
            switch ($tbl) {
                case 'analisa_bm':
                case 'analisa_sda':
                case 'analisa_quarry':
                case 'analisa_alat_custom':
                    $rowData['tbody'] = '<div class="ui scrolling container"><table class="ui first head foot stuck unstackable mini compact celled table" tbl="' . $tbl . '"><thead><tr' . $classRow . '><th class="one wide">No.</th><th class="five wide">URAIAN</th><th>KODE</th><th>KOEF.</th><th>SATUAN</th><th>HARGA SATUAN</th><th>RUMUS KOEF.</th><th>KET</th><th class="center aligned collapsing"><div class="ui mini basic icon buttons"><button class="ui button" name="add_row" jns="' . $tbl . '" tbl="add_row_tabel" data-tooltip="add row" data-position="left center" data-inverted><i class="plus icon"></i></button><label jns="' .$tbl . '" tbl="add_row_tabel" for="invisibleupload1" class="ui button" data-tooltip="ambil data excel" data-position="left center" data-inverted><i class="excel green file outline icon"></i></label></div></th></tr></thead><tbody>';
                    break;
                case 'analisa_ck':
                    $rowData['tbody'] = '<div class="ui scrolling container"><table class="ui first head foot stuck unstackable mini compact celled table" tbl="' . $tbl . '"><thead><tr' . $tbl . '><th class="five wide">URAIAN</th><th>KODE</th><th>SATUAN</th><th>KOEF.</th><th>HARGA SATUAN</th><th>JUMLAH</th><th>RUMUS HARGA SATUAN</th><th class="center aligned collapsing"><div class="ui mini basic icon buttons"><button class="ui button" name="add_row" jns="' . $tbl. '" tbl="add_row_tabel" data-tooltip="add row" data-position="left center" data-inverted><i class="plus icon"></i></button><label jns="' . $tbl. '" tbl="add_row_tabel" for="invisibleupload1" class="ui button" data-tooltip="ambil data excel" data-position="left center" data-inverted><i class="excel green file outline icon"></i></label></div></th></tr></thead><tbody>';
                    break;
                case 'user':
                    break;
                default:
                    # code...
                    break;
            }
            $myrow = 0;
            foreach ($get_data as $row) {
                $myrow++;
                switch ($jenis) {
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
                    case 'monev':
                        switch ($nama_tabel) {
                            case 'lap_range':
                                $rowData['tbody'] .= '<tr>
                                <td>' . $row->kd_analisa  . '</td>
                                <td>' . $row->uraian . '</td>
                                <td class="center aligned">' . $row->satuan . '</td>
                                <td class="right aligned">' . $row->volume . '</td>
                                <td class="right aligned">' . number_format($row->harga_satuan, 2, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->jumlah_harga, 2, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->Bobot, 4, ',', '.') . '</td>
                                <td class="right aligned">' . $row->RealisasiFisikSebelum . '</td>
                                <td class="right aligned">' . number_format($row->RealisasiKeuSebelum, 2, ',', '.') . '</td>
                                <td class="right aligned">' . $row->RealisasiFisikPeriode . '</td>
                                <td class="right aligned">' . number_format($row->RealisasiKeuPeriode, 2, ',', '.')  . '</td>
                                <td class="right aligned">' . $row->proggVolSdPeriode_Fisik . '</td>
                                <td class="right aligned">' . number_format($row->proggVolSdPeriode_Keu, 2, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->BtSdPeriodeLaluFisik, 4, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->BtSdPeriodeLaluKeu, 4, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->BtSdPeriodeIniFisik, 4, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->BtSdPeriodeIniKeu, 4, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->TotalFisik, 4, ',', '.') . '</td>
                                <td class="right aligned">' . number_format($row->TotalKeu, 4, ',', '.') . '</td>
                                </tr>';
                                break;
                        }
                        break;
                    case 'lap-harian':
                        $type = $row->type;
                        $value = $row->value;
                        $value = json_decode($value);
                        $tdAwal = '<tr id_row="' . $row->id . '">
                        <td klm="kd_analisa">' . $row->kd_analisa . '</td>
                        <td klm="kode">' . $row->kode . '</td>
                        <td klm="uraian">' . $row->uraian . '</td>';
                        $tdAwal2 = '<tr id_row="' . $row->id . '">
                        <td>' . $row->uraian . '</td>';
                        $tdAwal3 = '<tr id_row="' . $row->id . '">';
                        $tdButton = '<td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td><td>
                        <div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="monev" tbl="lap-harian_edit"><i class="edit outline blue icon"></i></button>
                            <button class="ui button" name="del_row" jns="' . $jenis . '" tbl="del_row"><i class="trash alternate outline red icon"></i></button>
                            <button class="ui button up_row" jns="' . $jenis . '"><i class="angle up blue icon"></i></button>
                            </div>
                        </td>';
                        $tdButton2 = '<td>
                        <div class="ui icon basic mini buttons">
                            <button class="ui button" name="flyout" name="flyout" jns="monev" tbl="lap-harian_edit"><i class="edit outline blue icon"></i></button>
                            <button class="ui button" name="del_row" jns="' . $jenis . '" tbl="del_row"><i class="trash alternate outline red icon"></i></button>
                            <button class="ui button up_row" jns="' . $jenis . '"><i class="angle up blue icon"></i></button>
                            </div>
                        </td>';
                        switch ($type) {
                            case 'upah':
                                $jumlah = $value->jumlah;
                                $satuan = $value->satuan;
                                $rowDataTemp['tbodyUpah'] .= $tdAwal . '
                                <td klm="value-jumlah">' . $jumlah . '</td>
                                <td klm="value-satuan">' . $satuan . '</td>
                                ' . $tdButton . '</tr>';
                                break;
                            case 'quarry':
                            case 'bahan':
                                $satuan = $value->satuan;
                                $diterima = $value->diterima;
                                $ditolak = $value->ditolak;
                                $rowDataTemp['tbodyBahan'] .= $tdAwal . '
                                <td klm="value-satuan">' . $satuan . '</td>
                                <td klm="value-diterima">' . $diterima . '</td>
                                <td klm="value-ditolak">' . $ditolak . '</td>
                                ' . $tdButton . '</tr>';
                                break;
                            case 'peralatan':
                                $satuan = $value->satuan;
                                $diterima = $value->diterima;
                                $ditolak = $value->ditolak;
                                $merk_type = $value->merk_type;
                                $rowDataTemp['tbodyPeralatan'] .= $tdAwal . '
                                <td klm="value-merk_type">' . $merk_type . '</td>
                                <td klm="value-diterima">' . $diterima . '</td>
                                <td klm="value-ditolak">' . $ditolak . '</td>
                                ' . $tdButton . '</tr>';
                                break;
                            case 'cuaca': //uraian == jam  value=cuaca

                                $rowDataTemp['tbodyCuaca'] .= $tdAwal3 . '
                                <td klm="uraian-jam">' . $row->kode . '</td>
                                <td klm="value-cuaca">' . $row->uraian . '</td>
                                <td klm="keterangan">' . $row->keterangan . '</td>
                                ' . $tdButton2 . '</tr>';
                                break;
                            case 'note':

                                $rowDataTemp['tbodyNote'] .= $tdAwal3 . '
                                <td klm="uraian">' . $row->uraian . '</td>
                                <td klm="keterangan">' . $row->keterangan . '</td>
                                ' . $tdButton2 . '</tr>';
                                break;
                            default:
                                # code...
                                break;
                        }

                        break;
                    case 'divisiBM':
                    case 'divisiCK':
                    case 'divisiSDA':
                        $nama_tabel = $jenis;
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
                    case 'proyek':
                        //$kd_proyek_aktif = $_SESSION["user"]["kd_proyek_aktif"];
                        //var_dump($kd_proyek_aktif);
                        if ($row->kd_proyek == $_SESSION["user"]["kd_proyek_aktif"]) {
                            $warna = 'green';
                        } else {
                            $warna = 'yellow';
                        }
                        $rowData['tbody'] .= '<tr>
                            <td>' . $row->kd_proyek . '</td>
                            <td>' . $row->nama_proyek . '</td>
                            <td>' . $row->tahun_anggaran . '</td>
                            <td>' . $row->tanggal_buat . '</td>
                            <td>' . $row->nama_user . '</td>
                            <td>' . $row->keterangan . '</td>
                            <td>
                                <div class="ui icon basic mini buttons">
                                    <button class="ui button" name="flyout" name="flyout" jns="proyek" tbl="edit" id_row="' . $row->id . '"><i class="edit outline ' . $warna . ' icon"></i></button>
                                    <button class="ui button" name="flyout" jns="copy" tbl="proyek" id_row="' . $row->id . '"  data-tooltip="salin proyek" data-position="left center"><i class="copy icon"></i></button>
                                    <button class="ui button" name="del_row" jns="proyek" tbl="del_row" id_row="' . $row->id . '"><i class="trash alternate outline red icon"></i></button>
                                </div>
                            </td>
                        </tr>';
                        break;
                    case 'rab':
                        //$kd_proyek_aktif = $_SESSION["user"]["kd_proyek_aktif"];
                        //var_dump($kd_proyek_aktif);
                        $desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan); //memanggil fungsi kelas sendiri
                        $harga_satuan = number_format($row->harga_satuan, $desimal, ',', '.');
                        $desimal = ($this->countDecimals($row->jumlah_harga) < 2) ? 2 : $this->countDecimals($row->jumlah_harga); //memanggil fungsi kelas sendiri
                        $jumlah_harga = number_format($row->jumlah_harga, $desimal, ',', '.');
                        $rowData['tbody'] .= '<tr id_row="' . $row->id . '">
                            <td>' . $row->kd_analisa . '</td>
                            <td>' . $row->uraian . '</td>
                            <td klm="volume"><div contenteditable rms onkeypress="return rumus(event);">' . $row->volume . '</div></td>
                            <td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td>
                            <td klm="harga_satuan">' . $harga_satuan . '</td>
                            <td klm="jumlah_harga">' . $jumlah_harga . '</td>
                            <td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>
                            <td>
                                <div class="ui icon basic mini buttons">
                                    <button class="ui button" name="flyout" name="flyout" jns="' . $jenis . '" tbl="edit"><i class="edit outline blue icon"></i></button>
                                    <button class="ui button" name="del_row" jns="' . $jenis . '" tbl="del_row"><i class="trash alternate outline red icon"></i></button>
                                    <button class="ui button up_row" jns="' . $jenis . '"><i class="angle up blue icon"></i></button>
                                </div>
                            </td>
                        </tr>';
                        break;
                    case 'schedule':
                        $desimal = ($this->countDecimals($row->bobot_selesai) < 2) ? 2 : $this->countDecimals($row->bobot_selesai);
                        $bobot_selesai = number_format($row->bobot_selesai, $desimal, ',', '.');
                        $labelSlider = 'labeled ticked';
                        if ($myrow > 1) {
                            $labelSlider = '';
                        }
                        $rowData['tbody'] .= '<tr id_row="' . $row->id . '">
                                <td>' . $row->kd_analisa . '</td>
                                <td>' . $row->uraian . '</td>
                                <td klm="durasi"><div contenteditable rms onkeypress="return rumus(event);">' . $row->durasi . '</div></td>
                                <td klm="mulai"><div contenteditable rms onkeypress="return rumus(event);">' . $row->mulai . '</div></td>
                                <td>' . $row->dependent . '</td>
                                <td class="m-0 p-0">
                                <div class="ui range teal slider m-0 p-0 schedule ' . $row->id . '"></div>
                                <div class="ui range yellow slider m-0 p-0 realisasi ' . $row->id . '"></div>
                                </td>
                                <td>
                                    <div class="ui icon basic mini buttons">
                                        <button class="ui button" name="flyout" name="flyout" jns="' . $jenis . '" tbl="edit" id_row="' . $row->id . '"><i class="edit outline blue icon"></i></button>
                                    </div>
                                </td>
                            </tr>';
                        break;
                    case 'monev[realisasi]':
                        $desimal = ($this->countDecimals($row->realisasi_fisik) < 2) ? 2 : $this->countDecimals($row->realisasi_fisik);
                        $realisasi_fisik = number_format($row->realisasi_fisik, $desimal, ',', '.');
                        $desimal = ($this->countDecimals($row->realisasi_keu) < 2) ? 2 : $this->countDecimals($row->realisasi_keu);
                        $realisasi_keu = number_format($row->realisasi_keu, $desimal, ',', '.');
                        $file = $row->file;
                        $fileTag = '';
                        if (strlen($file)) {
                            $fileTag = '<a class="ui fluid primary basic label" href="' . $file . '" target="_blank"><i class="download icon"></i>Ungguh</a>';
                        }
                        $rowData['tbody'] .= '<tr id_row="' . $row->id . '">
                                    <td>' . $row->uraian . '</td>
                                    <td>' . $row->satuan . '</td>
                                    <td klm="tanggal"><div contenteditable rms onkeypress="return rumus(event);">' . $row->tanggal . '</div></td>
                                    <td klm="realisasi_fisik"><div contenteditable rms onkeypress="return rumus(event);">' . $realisasi_fisik . '</div></td>
                                    <td klm="realisasi_keu"><div contenteditable rms onkeypress="return rumus(event);">' . $realisasi_keu . '</div></td>
                                    <td class="center aligned" klm="file">' . $fileTag . '</td>
                                    <td klm="keterangan">' . $row->keterangan . '</td>
                                    <td>
                                        <div class="ui icon basic mini buttons">
                                            <button class="ui button" name="flyout" name="flyout" jns="monev" tbl="edit"><i class="edit outline blue icon"></i></button>
                                            <button class="ui button" name="del_row" jns="monev" tbl="del_row"><i class="trash alternate outline red icon"></i></button>
                                        </div>
                                    </td>
                                </tr>';
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
                    case 'informasi_umum':
                        //var_dump($myrow);
                        $rowNotVal = [1, 2, 3, 4]; //hanya menampilkan keterangan
                        $nilai = $row->nilai;
                        $type = $row->type; //jika type == 'custom' maka row bisa dihapus
                        $button = '<td></td>';
                        if ($type == 'custom') {
                            $button = '<td><button class="ui icon basic mini button" name="del_row" jns="informasi_umum" tbl="del_row"><i class="trash alternate outline red icon"></i></button></td>';
                        }
                        $keys = array_keys($rowNotVal, $myrow);
                        $desimal = ($this->countDecimals($row->nilai) < 2) ? 2 : $this->countDecimals($row->nilai); //memanggil fungsi kelas sendiri
                        $retVal = ($keys) ? $nilai = '' : '<div contenteditable rms>' . number_format($nilai, $desimal, ',', '.') . '</div>';
                        $nomor = $row->nomor_uraian;
                        $ubah = ($nomor <= 0) ? $nomor = '' : $nomor;
                        if (array_keys($rowNotVal, $myrow)) {
                            $rowData['tbody'] .= '<tr id_row="' . $row->id . '"><td>' . $ubah . '</td><td>' . $row->uraian . '</td><td>' . $row->kode . '</td><td colspan="3" klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>';
                        } else {
                            if ($type == 'custom') {
                                $rowData['tbody'] .= '<tr id_row="' . $row->id . '"><td klm="nomor_uraian"><div contenteditable>' . $ubah . '</div></td><td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td><td>' . $row->kode . '</td><td klm="nilai" rms><div contenteditable>' . $retVal . '</div></td><td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td><td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>';
                            } else {
                                $rowData['tbody'] .= '<tr id_row="' . $row->id . '"><td>' . $ubah . '</td><td>' . $row->uraian . '</td><td>' . $row->kode . '</td><td klm="nilai">' . $retVal . '</td><td>' . $row->satuan . '</td><td>' . $row->keterangan . '</td>';
                            }
                        }
                        $rowData['tbody'] .= $button . '</tr>';
                        break;
                    case 'analisa_alat_custom':
                    case 'analisa_bm':
                    case 'analisa_sda':
                    case 'analisa_quarry':
                        $koef = $row->koefisien;
                        $nomor = $row->nomor;
                        if ($nomor == $row->kd_analisa) {
                            $rowData['row_analisa'] = $row;
                        }
                        if ($row->harga_satuan > 0) {
                            $desimal = ($this->countDecimals($row->harga_satuan) < 2) ? 2 : $this->countDecimals($row->harga_satuan);
                            $harga_sat = number_format($row->harga_satuan, $desimal, ',', '.');
                        } else {
                            $harga_sat = '';
                        }
                        $desimal = ($this->countDecimals($row->koefisien) < 2) ? 2 : $this->countDecimals($row->koefisien);
                        if (strlen($row->rumus) > 0) {
                            $koef = '<td klm="koefisien">' . number_format($row->koefisien, $desimal, ',', '.') . '</td>';
                        } else if ($row->koefisien != 0 || strlen($row->kode) > 0) {
                            $koef = '<td klm="koefisien"><div contenteditable rms>' . number_format($row->koefisien, $desimal, ',', '.') . '</div></td>';
                        } else {
                            $koef = '<td klm="koefisien"><div contenteditable rms></div></td>';
                        }
                        $kd_analisa = $row->kd_analisa;
                        $keterangan = '<td klm="keterangan"><div contenteditable>' . $row->keterangan . '</div></td>';
                        switch ($jenis) {
                            case 'analisa_quarry':
                                # keyterangan untuk lokasi dan tujuan quarry jangan dirubah disini
                                $keterangan = ($nomor == $kd_analisa) ? '<td></td>' : $keterangan;
                                break;
                            default:
                                break;
                        }
                        $rowData['tbody'] .= trim('<tr id_row="' . $row->id . '">
                                    <td klm="nomor"><div contenteditable>' . $nomor . '</div></td>
                                    <td klm="uraian"><div contenteditable>' . $row->uraian . '</div></td>
                                    <td klm="kode"><div contenteditable>' . $row->kode . '</div></td>
                                    ' . $koef . '
                                    <td klm="satuan"><div contenteditable>' . $row->satuan . '</div></td>
                                    <td klm="harga_satuan">' . $harga_sat . '</td>
                                    <td klm="rumus"><div contenteditable>' . $row->rumus . '</div></td>
                                    ' . $keterangan . '
                                    <td> <div class="ui mini basic icon buttons"><button class="ui button" name="modal_2"><i class="edit blue icon"></i></button><button class="ui button" name="del_row" jns="direct" tbl="del_row"><i class="trash alternate outline icon"></i></button><button class="ui button up_row"><i class="angle up icon"></i></button></div></td></tr>');
                        //<button class="ui button" name="del_row" jns="' . $jenis . '" tbl="del_row" id_row="' . $row->id . '"><i class="trash red alternate outline icon"></i></button>
                        //<button class="ui button" name="del_row" jns="direct" tbl="del_row"><i class="trash alternate outline icon"></i></button>

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
                    case 'analisa_alat':
                        $arrayVal = json_decode($row->ketentuan_tambahan, true);
                        //var_dump($arrayVal);
                        //$cekJson = count($arrayVal);
                        if (count($arrayVal) > 0) {
                            foreach ($arrayVal as $key => $val) {
                                foreach ($val as $key2 => $val2) {
                                }
                            }
                        }
                        //tambahan URAIAN PERALATAN
                        $Xhtml = '';
                        $bahan_bakar2 = '';
                        $bahan_bakar3 = '';
                        if (array_key_exists('X', $arrayVal) && $arrayVal['X']['uraian'] && $arrayVal['X']['koef']) {
                            $Xhtml = '<tr><td class="center aligned">5</td><td colspan="2">' . $arrayVal['X']['uraian'] . '</td><td>Ca</td><td klm="ketentuan_tambahanx" class="selectable"><div contenteditable rms>' . number_format($arrayVal['X']['koef'], 2, ',', '.') . '</div></td><td>' . $arrayVal['X']['satuan'] . '</td><td></td></tr>';
                        }
                        if (array_key_exists('bahan_bakar2', $arrayVal) && $arrayVal['bahan_bakar2']['uraian']) {
                            $bahan_bakar2 = '<tr><td class="center aligned"></td><td colspan="2">' . $arrayVal['bahan_bakar2']['uraian'] . '</td><td>' . $arrayVal['bahan_bakar2']['kode'] . '</td><td klm="bahan_bakar2">' . number_format($row->bahan_bakar2, 2, ',', '.') . '</td><td>' . $arrayVal['bahan_bakar2']['satuan'] . '</td><td></td></tr>';
                        }
                        if (array_key_exists('bahan_bakar3', $arrayVal) && $arrayVal['bahan_bakar3']['uraian']) {
                            $bahan_bakar3 = '<tr><td class="center aligned"></td><td colspan="2">' . $arrayVal['bahan_bakar3']['uraian'] . '</td><td>' . $arrayVal['bahan_bakar3']['kode'] . '</td><td klm="bahan_bakar3">' . number_format($row->bahan_bakar3, 2, ',', '.') . '</td><td>' . $arrayVal['bahan_bakar3']['satuan'] . '</td><td></td></tr>';
                        }
                        //var_dump($arrayVal['bahan_bakar3']['uraian']);
                        $rowData['tbody'] = '<div class="ui scrolling container"><table class="ui first head foot stuck unstackable celled table" id_row="' . $row->id . '" tbl="' . $jenis . '"><thead><tr><th class="one wide">No.</th><th class="seven wide" colspan="2">URAIAN</th><th>KODE</th><th>KOEF.</th><th>SATUAN</th><th>KET</th></tr></thead><tbody><tr style="font-weight:bold"><td>A</td><td colspan="2">URAIAN PERALATAN</td><td></td><td></td><td></td><td></td></tr><tr><td class="center aligned">1</td><td colspan="2">Jenis Peralatan</td><td colspan="3" class="center aligned" klm="jenis_peralatan"><div contenteditable>' . $row->jenis_peralatan . '</div></td><td><span class="ui teal large text">' . $row->kode . '</span></td></tr><tr><td class="center aligned">2</td><td colspan="2">Tenaga</td><td>Pw</td><td klm="tenaga" class="selectable"><div contenteditable rms>' . number_format($row->tenaga, 2, ',', '.') . '</div></td><td>HP</td><td></td></tr><tr><td class="center aligned">3</td><td colspan="2">Kapasitas</td><td>Cp</td><td klm="kapasitas" class="selectable"><div contenteditable rms>' . number_format($row->kapasitas, 2, ',', '.') . '</div></td><td>T/Jam</td><td></td></tr><tr><td class="center aligned">4</td><td>Alat</td><td>a. Umur Ekonomis</td><td>A</td><td klm="umur" class="selectable"><div contenteditable rms>' . number_format($row->umur, 2, ',', '.') . '</div></td><td>Tahun</td><td></td></tr><tr><td></td><td></td><td>b. Jam Kerja Dalam 1 Tahun</td><td>W</td><td klm="jam_kerja_1_tahun" class="selectable"><div contenteditable rms>' . number_format($row->jam_kerja_1_tahun, 2, ',', '.') . '</div></td><td>Jam</td><td></td></tr><tr><td></td><td></td><td>c. Harga Alat</td><td>B</td><td klm="harga_pakai" class="selectable"><div contenteditable rms>' . number_format($row->harga_pakai, 2, ',', '.') . '</div></td><td>Jam</td><td></td></tr>';
                        //var_dump('itu'.$Xhtml); 
                        $rowData['tbody'] .= $Xhtml;
                        $rowData['tbody'] .= '<tr style="font-weight:bold"><td>B</td><td colspan="2">BIAYA PASTI PER JAM KERJA</td><td></td><td></td><td></td><td></td></tr><tr><td class="center aligned">1</td><td>Nilai Sisa Alat</td><td>=10 % x B</td><td>C</td><td klm="nilai_sisa">' . number_format($row->nilai_sisa, 2, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr><td class="center aligned">2</td><td>Faktor Angsuran Modal</td><td><math><mrow><mo>=</mo><mfrac><mrow><mrow><mi>i</mi><mi>x</mi></mrow><msup><mrow><mo>(</mo><mrow><mn>1</mn><mo>+</mo><mi>i</mi></mrow><mo>)</mo></mrow><mi>A</mi></msup></mrow><mrow><msup><mrow><mo>(</mo><mrow><mn>1</mn><mo>+</mo><mi>i</mi></mrow><mo>)</mo></mrow><mi>A</mi></msup><mo>−</mo><mn>1</mn></mrow></mfrac></mrow></math></td><td>D</td><td klm="faktor_pengembalian_mdl">' . number_format($row->faktor_pengembalian_mdl, 6, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr><td class="center aligned">3</td><td colspan="2">Biaya Pasti per Jam</td><td></td><td></td><td></td><td></td></tr><tr><td class="center aligned"></td><td>a. Biaya Pengembalian Modal</td><td><math><mrow><mo>=</mo><mfrac><mrow><mrow><mrow><mo>(</mo><mrow><mi>B</mi><mo>−</mo><mi>C</mi></mrow><mo>)</mo></mrow><mi>x</mi></mrow><mi>D</mi></mrow><mi>W</mi></mfrac></mrow></math></td><td>E</td><td klm="biaya_pengembalian_mdl">' . number_format($row->biaya_pengembalian_mdl, 2, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr><td></td><td>b. Asuransi, dll</td><td><math><mrow><mo>=</mo><mfrac><mrow><mn>0</mn><mo>,</mo><mrow><mrow><mn>002</mn><mi>x</mi></mrow><mi>B</mi></mrow></mrow><mi>W</mi></mfrac></mrow></math></td><td>F</td><td klm="asuransi">' . number_format($row->asuransi, 4, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr style="font-weight:bold"><td></td><td>Biaya Pasti per Jam</td><td><math><mrow><mo>=</mo><mrow><mo>(</mo><mrow><mi>E</mi><mo>+</mo><mi>F</mi></mrow><mo>)</mo></mrow></mrow></math></td><td>G</td><td klm="total_biaya_pasti">' . number_format($row->total_biaya_pasti, 2, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr style="font-weight:bold"><td>C</td><td colspan="2">BIAYA OPERASI PER JAM KERJA</td><td></td><td></td><td></td><td></td></tr><tr><td class="center aligned">1</td><td>Bahan Bakar</td><td><math alttext="={(10%-12%)xPwxMs}"><mrow><mo>=</mo><mrow><mrow><mrow><mrow><mrow><mrow><mrow><mo>(</mo><mrow><mrow><mn>10</mn><mo>%</mo></mrow><mo>−</mo><mrow><mn>12</mn><mo>%</mo></mrow></mrow><mo>)</mo></mrow><mi>x</mi></mrow><mi>P</mi></mrow><mi>w</mi></mrow><mi>x</mi></mrow><mi>M</mi></mrow><mi>s</mi></mrow></mrow></math></td><td>H1</td><td klm="bahan_bakar1">' . number_format($row->bahan_bakar1, 4, ',', '.') . '</td><td>Rupiah</td><td></td></tr>';
                        $rowData['tbody'] .= $bahan_bakar2;
                        $rowData['tbody'] .= $bahan_bakar3;
                        $rowData['tbody'] .= '<tr><td class="center aligned">2</td><td>Pelumas</td><td><math><mrow><mo>=</mo><mrow><mrow><mrow><mrow><mrow><mrow><mrow><mo>(</mo><mrow><mrow><mn>0</mn><mo>,</mo><mrow><mrow><mn>25</mn><mo>%</mo></mrow><mo>−</mo><mn>0</mn></mrow></mrow><mo>,</mo><mrow><mn>35</mn><mo>%</mo></mrow></mrow><mo>)</mo></mrow><mi>x</mi></mrow><mi>P</mi></mrow><mi>w</mi></mrow><mi>x</mi></mrow><mi>M</mi></mrow><mi>p</mi></mrow></mrow></math></td><td>I</td><td klm="minyak_pelumas">' . number_format($row->minyak_pelumas, 2, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr><td class="center aligned">3</td><td>Biaya bengkel</td><td><math alttext="={(2,2% - 2,8%) x B}/{W}"><mrow><mo>=</mo><mfrac><mrow><mrow><mrow><mo>(</mo><mrow><mrow><mn>2</mn><mo>,</mo><mrow><mrow><mn>2</mn><mo>%</mo></mrow><mo>−</mo><mn>2</mn></mrow></mrow><mo>,</mo><mrow><mn>8</mn><mo>%</mo></mrow></mrow><mo>)</mo></mrow><mi>x</mi></mrow><mi>B</mi></mrow><mi>W</mi></mfrac></mrow></math></td><td>J</td><td klm="biaya_workshop">' . number_format($row->biaya_workshop, 2, ',', '.') . '</td><td></td><td></td></tr><tr><td class="center aligned">4</td><td>Biaya perbaikan</td><td><math alttext="={(6,4 % - 9 %) x B}/{W}"><mrow><mo>=</mo><mfrac><mrow><mrow><mrow><mo>(</mo><mrow><mn>6</mn><mo>,</mo><mrow><mrow><mn>4</mn><mo>%</mo></mrow><mo>−</mo><mrow><mn>9</mn><mo>%</mo></mrow></mrow></mrow><mo>)</mo></mrow><mi>x</mi></mrow><mi>B</mi></mrow><mi>W</mi></mfrac></mrow></math></td><td>K</td><td klm="biaya_perbaikan">' . number_format($row->biaya_perbaikan, 2, ',', '.') . '</td><td></td><td></td></tr><tr><td class="center aligned">5</td><td>Operator</td><td></td><td>L</td><td klm="upah_operator">' . number_format($row->upah_operator, 2, ',', '.') . '</td><td></td><td></td></tr><tr><td></td><td colspan="2">Jumlah Operator</td><td></td><td klm="jumlah_operator" class="selectable"><div contenteditable rms>' . number_format($row->jumlah_operator, 2, ',', '.') . '</div></td><td>Orang/Jam</td><td></td></tr><tr><td class="center aligned">5</td><td>Pembantu Operator</td><td></td><td>M</td><td klm="upah_pembantu_ope">' . number_format($row->upah_pembantu_ope, 2, ',', '.') . '</td><td></td><td></td></tr><tr><td></td><td colspan="2">Jumlah Pembantu Operator</td><td></td><td klm="jumlah_pembantu_ope" class="selectable"><div contenteditable rms>' . number_format($row->jumlah_pembantu_ope, 2, ',', '.') . '</div></td><td>Orang/Jam</td><td></td></tr><tr style="font-weight:bold"><td></td><td>Biaya Operasi per Jam</td><td><math alttext="={(H+I+J+K+L+M)}"><mrow><mo>=</mo><mrow><mo>(</mo><mrow><mrow><mrow><mrow><mrow><mi>H</mi><mo>+</mo><mi>I</mi></mrow><mo>+</mo><mi>J</mi></mrow><mo>+</mo><mi>K</mi></mrow><mo>+</mo><mi>L</mi></mrow><mo>+</mo><mi>M</mi></mrow><mo>)</mo></mrow></mrow></math></td><td>P</td><td klm="total_biaya_operasi">' . number_format($row->total_biaya_operasi, 2, ',', '.') . '</td><td>Rupiah</td><td></td></tr><tr style="font-weight:bold"><td>D</td><td>TOTAL BIAYA SEWA ALAT / JAM</td><td><math alttext="={( G + P )}"><mrow><mo>=</mo><mrow><mo>(</mo><mrow><mi>G</mi><mo>+</mo><mi>P</mi></mrow><mo>)</mo></mrow></mrow></math></td><td>T</td><td klm="total_biaya_sewa">' . number_format($row->total_biaya_sewa, 2, ',', '.') . '</td><td></td><td></td></tr></tbody><tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot></table></div>';
                        //klm=""
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
            $rowData['tbody'] .= '<tr><td colspan="' . $jumlah_kolom . '"><div class="ui icon info message"><i class="yellow exclamation circle icon"></i><div class="content"><div class="header">Data Tidak ditemukan </div><p>input data baru atau hubungi administrator</p></div></div></td></tr>';
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
                $pagination1 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="prev" tbl="' . $nama_tabel . '"><i class="angle left icon"></i></a>';
            }
            // panah next
            if ($halaman == $jmlhalaman) {
                $pagination2 .= '<a class="disabled icon item"><i class="angle right icon"></i></a>';
            } else {
                $pagination2 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="next" tbl="' . $nama_tabel . '"><i class="angle right icon"></i></a>';
            }
            //var_dump( $pagination );
            $rowData['tfoot'] = '<tr' . $classRow . '><th class="right aligned" colspan="' . $jumlah_kolom . '"><div class="ui center pagination menu">' . $pagination1 . $pagination . $paginationnext . $pagination2 . '</div></th></tr>';
        } else {
            $rowData['tfoot'] = str_replace("\r\n", "", '<tr' . $classRow . '><th class="right aligned" colspan="' . $jumlah_kolom . '"></th></tr>');
        }
        switch ($jenis) {
            case 'lap-harian':
                $rowData['tbody'] = $rowDataTemp;
                break;
            default:
                # code...
                break;
        }
        $rowData['tbody'] = str_replace("\r\n", "", $rowData['tbody']); //trim(preg_replace('/^\p{Z}+|\p{Z}+$/u', '', ($rowData['tbody'])), "\r\n");
        return $rowData;
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
                    case 'analisa_alat':
                        if ($row->keterangan == 'berat') {
                            $ket = "Pek. Berat";
                        } elseif ($row->keterangan == 'sedang') {
                            $ket = "Pek. Sedang";
                        } else {
                            $ket = "Pek. Ringan";
                        }
                        $keterangan = $row->keterangan;
                        if ($keterangan == 'analisa_alat_custom') {
                            $gabung = 'Kapasitas : ' . $row->kapasitas . ' ' . $row->sat_kapasitas . ', ' . $ket . ' (Rp ' . number_format($row->total_biaya_sewa, 2, ',', '.') . ') |<i class="asterisk loading icon"></i><i class="angle double right icon"></i> alat custom';
                            $ketJenis = 'analisa_alat_custom';
                            $id_row = $row->kode;
                        } else {
                            $gabung = 'Kapasitas : ' . $row->kapasitas . ' ' . $row->sat_kapasitas . ', ' . $ket . ' (Rp ' . number_format($row->total_biaya_sewa, 2, ',', '.') . ')';
                            $ketJenis = $jenis;
                            $id_row = $row->id;
                        }
                        $jenisVal = ($keterangan == 'analisa_alat_custom') ? 'analisa_alat_custom' : $jenis;
                        $rowData['list'] .= trim('<div class="item">
                        <div class="right floated content">
                            <div class="ui icon buttons basic compact">
                                <button class="ui icon button" name="modal_show" tbl="edit" jns="' . $ketJenis . '" id_row="' . $id_row . '">
                                    <i class="edit blue icon"></i>
                                </button><button class="ui button" name="flyout" jns="copy" tbl="' . $jenisVal . '" id_row="' . $row->id . '"  data-tooltip="salin analisa" data-position="left center"><i class="copy icon"></i></button>
                                <button class="ui icon button" name="del_row" tbl="del_row" jns="' . $ketJenis . '" id_row="' . $id_row . '">
                                    <i class="trash alternate outline red icon"></i>
                                </button>
                            </div>
                        </div>
                        <img class="ui avatar image" src="img/Alat Berat/default.jpg" alt="Inayah">
                        <div class="content">
                        <div class="header">' . $row->kode  . ' : ' . $row->jenis_peralatan . '</div>' . $gabung . '</div>
                    </div>');
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
                $pagination1 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="prev" tbl="' . $nama_tabel . '"><i class="angle left icon"></i></a>';
            }
            // panah next
            if ($halaman == $jmlhalaman) {
                $pagination2 .= '<a class="disabled icon item"><i class="angle right icon"></i></a>';
            } else {
                $pagination2 .= '<a class="icon item" name="page" hal="' . $id_aktif . '" ret="next" tbl="' . $nama_tabel . '"><i class="angle right icon"></i></a>';
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
