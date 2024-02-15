//=============================
//=========== CryptoJS ========
//=============================
class Encryption {//@audit encryption
	/**
	 * @var integer Return mengenkripsi metode atau nomor metode Cipher. (128, 192, 256)
	 */
	get encryptMethodLength() {
		var encryptMethod = this.encryptMethod;
		// get only number from string.
		// @link https://stackoverflow.com/a/10003709/128761 Reference.
		var aesNumber = encryptMethod.match(/\d+/)[0];
		return parseInt(aesNumber);
	}// encryptMethodLength


	/**
	 * @var integer Return/Kembalikan metode cipher membagi 8. example: AES number 256 will be 256/8 = 32.
	 */
	get encryptKeySize() {
		var aesNumber = this.encryptMethodLength;
		return parseInt(aesNumber / 8);
	}// encryptKeySize


	/**
	 * @link http://php.net/manual/en/function.openssl-get-cipher-methods.php Lihat metode yang tersedia di PHP jika kita bekerja antara enkripsi JS & PHP
	 * @var string Cipher method. 
	 *              rekomendasi AES-128-CBC, AES-192-CBC, AES-256-CBC 
	 *              karena tidak ada `openssl_cipher_iv_length()` function di JavaScript 
	 *              dan semua metode ini dikenal sebagai 16 in iv_length.
	 */
	get encryptMethod() {
		return 'AES-256-CBC';
	}// encryptMethod


	/**
	 * Decrypt string.
	 * 
	 * @link https://stackoverflow.com/questions/41222162/encrypt-in-php-openssl-and-decrypt-in-javascript-cryptojs Reference.
	 * @link https://stackoverflow.com/questions/25492179/decode-a-base64-string-using-cryptojs Crypto JS base64 encode/decode reference.
	 * @param string encryptedString String terenkripsi yang akan didekripsi.
	 * @param string key dari key.
	 * @return string Return decrypted string.
	 */
	decrypt(encryptedString, key) {
		var json = JSON.parse(CryptoJS.enc.Utf8.stringify(CryptoJS.enc.Base64.parse(encryptedString)));

		var salt = CryptoJS.enc.Hex.parse(json.salt);
		var iv = CryptoJS.enc.Hex.parse(json.iv);

		var encrypted = json.ciphertext;// no need to base64 decode.

		var iterations = parseInt(json.iterations);
		if (iterations <= 0) {
			iterations = 999;
		}
		var encryptMethodLength = (this.encryptMethodLength / 4);// example: AES number is 256 / 4 = 64
		var hashKey = CryptoJS.PBKDF2(key, salt, { 'hasher': CryptoJS.algo.SHA512, 'keySize': (encryptMethodLength / 8), 'iterations': iterations });

		var decrypted = CryptoJS.AES.decrypt(encrypted, hashKey, { 'mode': CryptoJS.mode.CBC, 'iv': iv });

		return decrypted.toString(CryptoJS.enc.Utf8);
	}// decrypt
	/**
	 * Encrypt string.
	 * 
	 * @link https://stackoverflow.com/questions/41222162/encrypt-in-php-openssl-and-decrypt-in-javascript-cryptojs Reference.
	 * @link https://stackoverflow.com/questions/25492179/decode-a-base64-string-using-cryptojs Crypto JS base64 encode/decode reference.
	 * @param string string String asli yang akan dienkripsi.
	 * @param string key dari key.
	 * @return string Mengembalikan string terenkripsi
	 */
	encrypt(string, key) {
		var iv = CryptoJS.lib.WordArray.random(16);// the reason to be 16, please read on `encryptMethod` property.

		var salt = CryptoJS.lib.WordArray.random(256);
		var iterations = 999;
		var encryptMethodLength = (this.encryptMethodLength / 4);// contoh: AES number is 256 / 4 = 64
		var hashKey = CryptoJS.PBKDF2(key, salt, { 'hasher': CryptoJS.algo.SHA512, 'keySize': (encryptMethodLength / 8), 'iterations': iterations });

		var encrypted = CryptoJS.AES.encrypt(string, hashKey, { 'mode': CryptoJS.mode.CBC, 'iv': iv });
		var encryptedString = CryptoJS.enc.Base64.stringify(encrypted.ciphertext);

		var output = {
			'ciphertext': encryptedString,
			'iv': CryptoJS.enc.Hex.stringify(iv),
			'salt': CryptoJS.enc.Hex.stringify(salt),
			'iterations': iterations
		};

		return CryptoJS.enc.Base64.stringify(CryptoJS.enc.Utf8.parse(JSON.stringify(output)));
	}// encrypt
}