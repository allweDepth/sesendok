<?php
class Register
{
	public function register()
	{
		require 'init.php';
		$DB = DB::getInstance();
		$keyEncrypt = $_SESSION["key_encrypt"];
		$user = new User();
		$validate = new Validate($_POST);

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
			10 => 'berhasil validasi',
			11 => 'berhasil data posting',
			12 => 'berhasil data jenis tabel',
			15 => 'berhasil reset',
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
			45 => 'tabel yang digunakan tidak ditemukan',
			46 => 'gagal run',
			47 => 'data telah ada',
			48 => 'data telah ada dan telah diupdate',
			49 => 'data telah diproses kembali',
			50 => 'kode bisa digunakan',
			51 => 'data telah di salin',
			56 => 'belum ada dokumen pekerjaan yang aktif',
			100 => 'Continue', #server belum menolak header permintaan dan meminta klien untuk mengirim body (isi/konten) pesan
			200 => 'data telah diproses kembali',
			405 => 'data telah diproses kembali tapi tidak menghasilkan result',
			//File
			701 => 'File Tidak Lengkap',
			702 => 'file yang ada terlalu besar',
			703 => 'type file tidak sesuai',
			704 => 'Gagal Upload',
			705 => 'File Telah dibuat',
			707 => 'File Gagal dibuat',
			401  => 'Unauthorized', #pengunjung website tidak memiliki hak akses untuk file / folder yang diproteksi oleh password (kata kunci).
			403  => 'Forbidden', #pengunjung sama sekali tidak dapat mengakses ke folder tujuan. Angka 403 muncul disebabkan oleh kesalahan pengaturan hak akses pada folder.
			202  => 'Accepted',
			404  => 'Not Found', #bahwa file / folder yang diminta, tidak ditemukan didalam database pada suatu website.
			406  => 'Not Acceptable', #pernyataan bahwa permintaan dari browser tidak dapat dipenuhi oleh server.
			500  => ' Internal Server Error', #menyatakan bahwa ada kesalahan konfigurasi pada akun hosting.
			509  => 'Bandwidth Limit Exceeded', #penggunaan bandwidth pada account hosting sudah melebihi quota yang ditetapkan untuk akun hosting Anda
			//Bahasa Gaul
			530  => 'I Miss You', #I Miss You dalam bahasa Mandarin adalah Wo Xiang Ni
			831  => 'I Love You', #Memiliki jumlah 8 huruf dalam kalimat "I Love You",Kemudian ada 3 jumlah total kata dalam frasa "I Love You",Dan 1 memiliki satu makna, yaitu "Aku Cinta Kamu"
			24434   => 'Sudahkah anda sholat', #diambil dari jumlah rakaat di setiap Sholat lima waktu atau shalat fardhu
			1432  => 'I Love You Too', #1 artinya I, 4 artinya Love, 3 artinya You, 2 artinya Too. bisa diberikan untuk pasangan kekasih.
			224  => 'I Love You Too' #Artinya adalah Today, Tomorrow dan Forever.Angka 2 artinya two yang artinya twoday,today,
		];

		$sukses = false;
		$code = 40;
		// $crypto = new CryptoUtils();
		if (isset($_POST['register']) and isset($_POST['setuju'])) {
			$username = $validate->setRules('username', 'Username', [
				'sanitize' => 'string',
				'uniqueArray' => ['user_ahsp', 'username', [['id', '>', 0]]],
				'required' => true,
				'regexp' => '/^[A-Za-z0-9]+$/',
				'min_char' => 8
			]);
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
			$nama_org = $validate->setRules('organisasi', 'Organisasi', [
				'sanitize' => 'string',
				'required' => true,
				'min_char' => 3
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
				'uniqueArray' => ['user_ahsp', 'email', [['id', '>', 0]]],
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
				// jika tidak ada error, input ke database
				$tgl = date('Y-m-d H:i:s'); //date('Y-m-d H:i:s')
				$thn = date("Y");
				$password = password_hash($password, PASSWORD_DEFAULT);
				$set = [
					'username' => $username,
					'email' => $email,
					'nama' => $nama,
					'password' => $password,
					'nama_org' => $nama_org,
					'type_user' => $type_user,
					'photo' => $photo,
					'tgl_daftar' => date('Y-m-d H:i:s'),
					'tgl_login' => date('Y-m-d H:i:s'),
					'thn_aktif_anggaran' => date('Y'),
					'kd_proyek_aktif' => '',
					'ket' => $bilnay,
					'aktif' => 0,
					'aktif_edit' => 0,
					'kontak_person' => $kontak,
					'font_size' => 80,
					'aktif_chat' => 0,
					'warna_tbl' => 'non',
					'scrolling_table' => 'short'
				];
				$resul = $DB->insert('user_ahsp', $set);
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
				$data = $validate->getError();
			}
		} else if (isset($_POST['jenis'])) {
			// $user = mysqli_real_escape_string($koneksi, $_POST['search']);
			// $sql = "SELECT `username`, `email` FROM `user_ahsp` WHERE username = '$user' OR email = '$user'";
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
						'unique' => ['user_ahsp', 'username', ['id', '!=', 0]],
						'min_char' => 8
					]);
					break;
				case 'email':
					$email = $validate->setRules('search', 'pencarian email', [
						'sanitize' => 'string',
						'email' => true,
						'unique' => ['user_ahsp', 'email', ['id', '!=', 0]]
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
		}
		//$item = array('code' => $code, 'message' => $hasilServer[$code]);
		//echo $pesan;
		//echo json_encode($pesan);
		$item = array('code' => $code, 'message' => $hasilServer[$code]);
		$json = array('success' => $sukses, 'data' => $data, 'error' => $item);
		return json_encode($json);
	}
}
