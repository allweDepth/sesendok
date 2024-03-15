<?php
class Register
{
	public function register()
	{
		require 'init_no_session.php';
		// var_dump($_POST);
		$DB = DB::getInstance();
		$keyEncrypt = $_SESSION["key_encrypt"];
		// $user = new User();
		$validate = new Validate($_POST);
		$sukses = false;
		$code = 40;
		// $crypto = new CryptoUtils();
		if (isset($_POST['register']) and isset($_POST['setuju'])) {
			$username = $validate->setRules('username', 'Username', [
				'sanitize' => 'string',
				'uniqueArray' => ['user_sesendok_biila', 'username', [['id', '>', 0]]],
				'required' => true,
				'regexp' => '/^[A-Za-z0-9]+$/',
				'min_char' => 8
			]);
			// var_dump($username);
			$password = $validate->setRules('password', 'Pasword', [
				'sanitize' => 'string',
				'required' => true,
				'min_char' => 8
			]);
			$nama = $validate->setRules('nama', 'Nama Lengkap', [
				'sanitize' => 'string',
				'regexp' => '/^[a-zA-Z\s]*$/',
				'required' => true,
				'min_char' => 8
			]);
			$kd_wilayah = $validate->setRules('kd_wilayah', 'kode wilayah', [
				'sanitize' => 'string',
				'required' => true,
				'min_char' => 1
			]);
			$kd_wilayah = $validate->setRules('kd_wilayah', 'kode wilayah', [
				'sanitize' => 'string',
				'required' => true,
				'inDB' => ['wilayah_neo', 'kode', [['kode', '=', $kd_wilayah]]],
				'min_char' => 1
			]);

			$kd_organisasi = $validate->setRules('organisasi', 'Organisasi', [
				'sanitize' => 'string',
				'required' => true,
				'min_char' => 1
			]);
			$kd_organisasi = $validate->setRules('organisasi', 'Organisasi', [
				'inDB' => ['organisasi_neo', 'kode', [['kode', '=', $kd_organisasi],['kd_wilayah', '=', $kd_wilayah,'AND']]]
			]);
			$kontak = $validate->setRules('kontak_person', 'Kontak Person', [
				'sanitize' => 'string',
				'required' => true,
				'min_char' => 3
			]);
			$email = $validate->setRules('email', 'email', [
				'sanitize' => 'string',
				'email' => true,
				'required' => true,
				'uniqueArray' => ['user_sesendok_biila', 'email', [['id', '>', 0]]],
				'min_char' => 3
			]);

			if ($validate->passed()) {
				$a = array('Jika Anda takut gagal, Anda tidak pantas untuk sukses! - Charles Barkley', 'Sukses tidak datang kepadamu, kamu harus pergi ke sana. - Marva Collins', 'Rahasia kesuksesanmu ditentukan oleh agenda harian mu. - John C. Maxwell', 'Jika kamu ingin sukses sebanyak yang kamu inginkan, maka kamu akan sukses. - Eric Thomas', ' Ingin menjadi orang lain adalah menyia-nyiakan dirimu. - Kurt Cobain', 'Miliki cukup keberanian untuk memulai dan cukup hati untuk menyelesaikan. - Jessica NS Yourko', 'Jangan malu dengan kegagalanmu, belajarlah darinya dan mulai lagi. - Richard Branson', 'Untuk berhasil dalam hidup, kamu membutuhkan dua hal: ketidaktahuan dan kepercayaan diri. - Mark Twain', 'Sukses berubah dari kegagalan ke kegagalan tanpa kehilangan antusiasme. - Winston Churchill', 'Hidup bukan tentang menemukan dirimu sendiri. Hidup adalah bagaimana membangun dirimu. - George Bernard Shaw', 'Hidup adalah pertanyaan dan bagaimana kita menjalaninya adalah jawaban kita. - Gary Keller', 'Fokus pada perjalanan, bukan tujuan. - Greg Anderson', 'Kesenangan yang paling jarang kita alami memberi kita kesenangan terbesar. - Epictetus', 'Dua musuh kebahagiaan manusia adalah rasa sakit dan kebosanan. - Arthur Schopenhauer', 'Apa pun yang kamu lakukan, lakukan dengan sekuat tenaga. - Marcus Tullius Cicero', 'Ketekunan menjamin bahwa hasil tidak bisa dihindari. - Paramahansa Yogananda');
				$random_Kata = array_rand($a, 3);
				$bilnay = $a[$random_Kata[0]];
				$bilnay = filter_var($bilnay, FILTER_UNSAFE_RAW);
				$bilnay = preg_replace('/\x00|<[^>]*>?/', '', $bilnay);
				$bilnay = str_replace(["'", '"'], ['&#39;', '&#34;'], $bilnay);

				$type_user = "user";
				$photo = "images/avatar/default.jpeg";
				// menyiapkan query
				$password = password_hash($password, PASSWORD_DEFAULT);
				$set = [
					'kd_wilayah' => $kd_wilayah,
					'username' => $username,
					'email' => $email,
					'nama' => $nama,
					'password' => $password,
					'kd_organisasi' => $kd_organisasi,
					'nama_org' => $nama_org,
					'type_user' => $type_user,
					'photo' => $photo,
					'tgl_daftar' => date('Y-m-d H:i:s'),
					'tgl_login' => date('Y-m-d H:i:s'),
					'tahun' => date('Y'),
					'kontak_person' => $kontak,
					'font_size' => 80,
					'warna_tbl' => 'non',
					'scrolling_table' => 'short',
					'disable_login' => 1,
					'disable_anggaran' => 1,
					'disable_kontrak' => 1,
					'disable_realisasi' => 1,
					'disable_realisasi' => 1,
					'ket' => $bilnay
				];
				$resul = $DB->insert('user_sesendok_biila', $set);
				// jika query simpan berhasil, maka user sudah terdaftar
				// maka alihkan ke halaman login
				//periksa hasil query
				if ($resul) {
					$data = "1";
					$sukses = true;
					$code = 202;
				} else {
					$data = "2";
					$sukses = true;
					$code = 202;
				}
			} else {
				$code = 406;
				$data = $validate->getError();
			}
		} else if (isset($_POST['jenis'])) {
			// $user = mysqli_real_escape_string($koneksi, $_POST['search']);
			// $sql = "SELECT `username`, `email` FROM `user_sesendok_biila` WHERE username = '$user' OR email = '$user'";
			// $result = mysqli_query($koneksi, $sql);'in_array' => ['upah', 'royalty', 'bahan', 'peralatan']
			$klm = $validate->setRules('klm', 'Organisasi', [
				'sanitize' => 'string',
				'required' => true,
				'in_array' => ['username', 'email'],
				'min_char' => 3
			]);
			switch ($klm) {
				case 'username':
					$username = $validate->setRules('search', 'pencarian username', [
						'sanitize' => 'string',
						'required' => true,
						'unique' => ['user_sesendok_biila', 'username', ['id', '!=', 0]],
						'min_char' => 8
					]);
					break;
				case 'email':
					$email = $validate->setRules('search', 'pencarian email', [
						'sanitize' => 'string',
						'email' => true,
						'unique' => ['user_sesendok_biila', 'email', ['id', '!=', 0]]
					]);
					break;
				default:
					#code...
					break;
			};
			//var_dump($validate->getError());
			if ($validate->passed()) {
				$data = '<i class="check circle outline icon"></i><div class="content"><div class="header">username/email dapat digunakan </div><p>silahkan lengkapi data register </p></div>';
				$sukses = true;
				$code = 202;
			} else {
				$data = '<i class="user times icon"></i><div class="content"><div class="header">username/email telah digunakan </div><p>ganti username dan email dengan karakter unik</p></div>';
			}
		} else if ($_POST['jenis'] == 'list_dropdown') {
			var_dump('ok');
		}

		$item = array('code' => $code, 'message' => hasilServer[$code]);
		$json = array('success' => $sukses, 'data' => $data, 'error' => $item);
		return json_encode($json);
	}
	
}
