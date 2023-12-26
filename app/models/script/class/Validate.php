<?php
class Validate
{
  private $_errors = array();
  private $_formMethod = null;

  public function __construct($formMethod)
  {
    $this->_formMethod = $formMethod;
  }
  public function encrypt($formValue)
  {
    // var_dump(isset($_SESSION["user"]["key_encrypt"]));
    if (isset($_SESSION["user"]["key_encrypt"])) {
      $keyEncrypt = $_SESSION["user"]["key_encrypt"];
    } else if (isset($_SESSION["key_encrypt"])) {
      $keyEncrypt = $_SESSION["key_encrypt"];
    } else {
      $real_path = realpath(dirname(__FILE__));
      if (strpos($real_path, 'script')) {
        header("Location: login");
      } else {
        header("Location: login");
      }
    }
    //var_dump($_SESSION);
    // var_dump($formValue);
    if ($formValue != null) {
      require_once 'CryptoUtils.php';
      $crypto = new CryptoUtils();
      return $crypto->decrypt($formValue, $keyEncrypt);
    }
  }

  public function setRules($item, $itemLabel, $rules)
  {
    // kondisi if isset() diperlukan untuk form checkbox atau radio yang kemungkinan kosong
    if (isset($this->_formMethod[$item])) {
      $formValue = trim($this->_formMethod[$item]);
      //var_dump($formValue);
    } else {
      $formValue = "";
    }

    // jalankan proses sanitize untuk setiap item (jika disyaratkan)
    if (array_key_exists('sanitize', $rules)) {
      $formValue = Input::runSanitize($formValue, $rules['sanitize']);
    }
    //var_dump($this->_formMethod[$item]);
    if (array_key_exists('cry', $this->_formMethod)) {
      if ($this->_formMethod['cry']) {
        $formValue = $this->encrypt($formValue);
      }
    }
    //var_dump($formValue);
    foreach ($rules as $rule => $ruleValue) {
      // var_dump($rule);
      // var_dump($ruleValue);
      switch ($rule) {
        case 'error': //langsung error
          $this->_errors[$item] = "$itemLabel";
          break;
        case 'required':
          if ($ruleValue === TRUE && empty($formValue)) {
            $this->_errors[$item] = "$itemLabel tidak boleh kosong";
          }
          break;

        case 'min_char':
          if (strlen($formValue) < $ruleValue) {
            $this->_errors[$item] = "$itemLabel minimal $ruleValue karakter";
          }
          break;
          // encrypt
        case 'min_char_enc':
          $formValue = $this->encrypt($formValue);
          if (strlen($formValue) < $ruleValue) {
            $this->_errors[$item] = "$itemLabel minimal $ruleValue karakter";
          }
          break;
        case 'max_char':
          if (strlen($formValue) > $ruleValue) {
            $this->_errors[$item] = "$itemLabel maksimal $ruleValue karakter";
          }
          break;
          // encrypt
        case 'numeric_enc':
          $formValue = $this->encrypt($formValue);
          if ($ruleValue === TRUE) { //|| !is_int($formValue)//($ruleValue === TRUE && (!is_numeric($formValue) ))
            if ($formValue != 0) {
              if (is_numeric((float)$formValue)) {
                $formValue = $formValue; // numeric
              } else { // is not numeric 
                $this->_errors[$item] = "$itemLabel harus diisi angka";
              }
            } else {
              $formValue = $formValue; // 0 as numeric
            }
            //var_dump($formValue);

          }
          break;
        case 'numeric':
          if ($ruleValue === TRUE) { //|| !is_int($formValue)//($ruleValue === TRUE && (!is_numeric($formValue) ))
            if ($formValue != 0) {
              if (is_numeric((float)$formValue)) {
                $formValue = $formValue; // numeric
              } else { // is not numeric 
                $this->_errors[$item] = "$itemLabel harus diisi angka";
              }
            } else {
              $formValue = $formValue; // 0 as numeric
            }
            //var_dump($formValue);

          }
          break;

        case 'min_value':
          if ($formValue < $ruleValue) {
            $this->_errors[$item] = "$itemLabel minimal $ruleValue";
          }
          break;

        case 'max_value':
          if ($formValue > $ruleValue) {
            $this->_errors[$item] = "$itemLabel maksimal $ruleValue";
          }
          break;

        case 'matches':
          if ($formValue != $this->_formMethod[$ruleValue]) {
            $this->_errors[$item] = "$itemLabel tidak sama";
          }
          break;

        case 'sama_dengan_enc':
          $formValue = $this->encrypt($formValue);
          if ($formValue != $ruleValue) {
            $this->_errors[$item] = "$itemLabel tidak sama dengan";
          }
          break;
        case 'sama_dengan':
          if ($formValue != $ruleValue) {
            $this->_errors[$item] = "$itemLabel tidak sama dengan";
          }
          break;
        case 'email':
          if ($ruleValue === TRUE && !filter_var($formValue, FILTER_VALIDATE_EMAIL)) {
            $this->_errors[$item] = "Format $itemLabel tidak sesuai";
          }
          break;

        case 'url':
          if ($ruleValue === TRUE && !filter_var($formValue, FILTER_VALIDATE_URL)) {
            $this->_errors[$item] = "Format $itemLabel tidak sesuai";
          }
          break;
        case 'url_enc':
          $formValue = $this->encrypt($formValue);
          if ($ruleValue === TRUE && !filter_var($formValue, FILTER_VALIDATE_URL)) {
            $this->_errors[$item] = "Format $itemLabel tidak sesuai";
          }
          break;
          //tambahan alwi
        case 'in_array':
          //var_dump(in_array($formValue, $ruleValue,  TRUE));
          $formValue = (gettype($formValue) == 'string') ? strtolower($formValue) : $formValue;
          if (!in_array($formValue, $ruleValue, TRUE)) {
            $this->_errors[$item] = "Periksa baris $itemLabel";
          }
          break;
          //tambahan alwi buat validasi json
        case 'json_enc':
          $formValue = $this->encrypt($formValue);
          if (!@json_decode($formValue)) {
            $this->_errors[$item] = (json_last_error() === JSON_ERROR_NONE) . " Pola $itemLabel format json tidak sesuai";
          }
          break;
        case 'json':
          if (!@json_decode($formValue)) {
            $this->_errors[$item] = (json_last_error() === JSON_ERROR_NONE) . " Pola $itemLabel format json tidak sesuai";
          }
          break;
        case 'regexp_enc':
          $formValue = $this->encrypt($formValue);
          if (!preg_match($ruleValue, $formValue)) {
            $this->_errors[$item] = "Pola $itemLabel tidak sesuai";
          }
          break;
        case 'regexp_enc':
          $formValue = $this->encrypt($formValue);
          if (!preg_match($ruleValue, $formValue)) {
            $this->_errors[$item] = "Pola $itemLabel tidak sesuai";
          }
          break;
        case 'regexp':
          if (!preg_match($ruleValue, $formValue)) {
            $this->_errors[$item] = "Pola $itemLabel tidak sesuai";
          }
          break;
        case 'unique_enc':
          $formValue = $this->encrypt($formValue);
          require_once 'DB.php';
          $DB = DB::getInstance();
          if ($DB->check($ruleValue[0], $ruleValue[1], $formValue)) {
            $this->_errors[$item] = "$itemLabel sudah terpakai, silahkan pilih nama lain";
          }
          break;
        case 'unique':
          require_once 'DB.php';
          $DB = DB::getInstance();
          if ($DB->check($ruleValue[0], $ruleValue[1], $formValue)) {
            $this->_errors[$item] = "$itemLabel sudah terpakai, silahkan pilih nama lain";
          }
          break;
        case 'uniqueArray':
          require_once 'DB.php';
          $DB = DB::getInstance();
          //checkArray( $tableName, $columnName, $condition )
          //['user','username',[[ 'id', '=', $id_user][ 'id', '=', $id_user , 'AND']]]
          if ($DB->checkArray($ruleValue[0], $ruleValue[1], $ruleValue[2], $formValue)) {
            $this->_errors[$item] = "$itemLabel sudah terpakai, silahkan pilih nama lain";
          }
          break;
      }

      // cek jika sudah ada error di item yang sama, langsung keluar dari looping
      if (!empty($this->_errors[$item])) {
        break;
      }
    }
    // kembalikan nilai yang sudah lewat proses sanitize
    return $formValue;
  }

  public function getError()
  {
    return $this->_errors;
  }

  public function passed()
  {
    return empty($this->_errors) ? true : false;
  }
}
