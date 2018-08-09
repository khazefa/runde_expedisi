<?php
/**
 * Common Helpers
 *
 * @package		PHP Native
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Sigit Prayitno
 * 
 */

// ------------------------------------------------------------------------

/**
 * Get Current Page URL
 *
 * Get Current Page URL by protocol http or https
 *
 * @return	current page url
 */
function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

/**
 * Count Cart
 *
 * Count Cart Items
 *
 * @return	Cart Total
 */
function countCart() {
    $jml = 0;
    if (isset($_SESSION['cart_total'])) {
        foreach ($_SESSION['cart_total'] as $key=>$value) {
            $jml = $jml + $value['quantity'];
        }
    }
    return $jml;
}

/**
 * Upload File
 *
 * Function for uploading media files, currently only supported for jpg, jpeg, gif, png
 *
 * @return	File Uploaded
 */
function uploadFile($file, $prefix, $destination){
    $uploadDirectory = ABSPATH . UPLOADS_DIR;

    $errors = []; // Store all foreseen and unforseen errors here

    $fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

    $fileName = $prefix.$file['name'];
    $fileSize = $file['size'];
    $fileTmpName  = $file['tmp_name'];
    $fileType = $file['type'];
    $fileExtension = strtolower(end(explode('.',$fileName)));
    
    $uploadPath = $uploadDirectory . $destination . DIRECTORY_SEPARATOR . basename($fileName);

    if (! in_array($fileExtension,$fileExtensions)) {
        $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
    }

    if ($fileSize > 2000000) {
        $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
    }

    if (empty($errors)) {
        $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

        if ($didUpload) {
//            echo "The file " . basename($fileName) . " has been uploaded";
             //original file identity
            $im_src = imagecreatefromjpeg($uploadPath);
            $src_width = imageSX($im_src);
            $src_height = imageSY($im_src);

            //Remove image from memory
            imagedestroy($uploadPath);
        } else {
            echo "An error occurred somewhere. Try again or contact the admin <a href='javascript:history.back()'>Back</a>";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
        }
    }
}

/**
 * Upload Files
 *
 * Function for uploading media files, currently only supported for jpg, jpeg, gif, png
 *
 * @return	Files Uploaded
 */
function uploadFiles() {
    $num_args = func_num_args();
    $arg_list = func_get_args();
    
    $valReturn = false;
    $i = 0;
    $unlinkElement = array();
    foreach($arg_list as $key=>$value) {
        if(is_array($value) AND is_array($value[0])) {
            if($value[0]['error'] == 0 AND isset($value[1])) {
                if($value[0]['size'] > 0 AND $value[0]['size'] < 500000) {
                    $typeAccepted = array("image/jpeg", "image/gif", "image/png");
                    if(in_array($value[0]['type'],$typeAccepted)) {    
                        $destination = $value[1];
                        if(isset($value[2])) {
                            $extension = substr($value[0]['name'] , strrpos($value[0]['name'] , '.') +1);
                            $destination .= (str_replace(" ","-",$value[2])).".".$extension;
                        } else {
                            $destination .= $value[0]['name'];
                        }
                        
                        if(move_uploaded_file($value[0]['tmp_name'],$destination)) {
                            $i++;
                            $unlinkElement[] = $destination;
                        }
                    }
                }
            }
        }
    }
    if($i == $num_args) {
        $valReturn = true;
    } else {
        foreach($unlinkElement as $value) {
            unlink($value);
        }
    }
    return $valReturn;
}

/**
 * Combo Date
 *
 * Generate list of date to combo box 
 *
 * @return	List of combo box
 */
function combotgl($awal, $akhir, $var, $terpilih){
    echo "<select name=$var class='form-control'>";
    for ($i=$awal; $i<=$akhir; $i++){
      $lebar=strlen($i);
      switch($lebar){
        case 1:
        {
          $g="0".$i;
          break;     
        }
        case 2:
        {
          $g=$i;
          break;     
        }      
      }  
      if ($i==$terpilih)
        echo "<option value=$g selected>$g</option>";
      else
        echo "<option value=$g>$g</option>";
    }
    echo "</select> ";
}


/**
 * Combo Month
 *
 * Generate list of month to combo box 
 *
 * @return	List of combo box
 */
function combobln($awal, $akhir, $var, $terpilih){
    echo "<select name=$var class='form-control'>";
    for ($bln=$awal; $bln<=$akhir; $bln++){
      $lebar=strlen($bln);
      switch($lebar){
        case 1:
        {
          $b="0".$bln;
          break;     
        }
        case 2:
        {
          $b=$bln;
          break;     
        }      
      }  
        if ($bln==$terpilih)
           echo "<option value=$b selected>$b</option>";
        else
          echo "<option value=$b>$b</option>";
    }
    echo "</select> ";
}


/**
 * Combo Year
 *
 * Generate list of year to combo box 
 *
 * @return	List of combo box
 */
function combothn($awal, $akhir, $var, $terpilih){
    echo "<select name=$var class='form-control'>";
    for ($i=$awal; $i<=$akhir; $i++){
      if ($i==$terpilih)
        echo "<option value=$i selected>$i</option>";
      else
        echo "<option value=$i>$i</option>";
    }
    echo "</select> ";
}


/**
 * Combo Month Name
 *
 * Generate list of month name to combo box 
 *
 * @return	List of combo box
 */
function combonamabln($awal, $akhir, $var, $terpilih){
    $nama_bln=array(1=> "Januari", "Februari", "Maret", "April", "Mei", 
                        "Juni", "Juli", "Agustus", "September", 
                        "Oktober", "November", "Desember");
    echo "<select name=$var class='form-control'>";
    for ($bln=$awal; $bln<=$akhir; $bln++){
        if ($bln==$terpilih)
           echo "<option value=$bln selected>$nama_bln[$bln]</option>";
        else
          echo "<option value=$bln>$nama_bln[$bln]</option>";
    }
    echo "</select> ";
}

/**
 * Indonesian Date
 *
 * Convert default date format into Indonesia Format
 *
 * @return	indonesian formated date output
 */
function tgl_indo($tgl){
    $tanggal = substr($tgl,8,2);
    $bulan = getBulan(substr($tgl,5,2));
    $tahun = substr($tgl,0,4);
    return $tanggal.' '.$bulan.' '.$tahun;		 
}	

/**
 * Get Month Name
 *
 * Get month name by index
 *
 * @return	Month Name
 */
function getBulan($bln){
    switch ($bln){
        case 1: 
            return "Januari";
            break;
        case 2:
            return "Februari";
            break;
        case 3:
            return "Maret";
            break;
        case 4:
            return "April";
            break;
        case 5:
            return "Mei";
            break;
        case 6:
            return "Juni";
            break;
        case 7:
            return "Juli";
            break;
        case 8:
            return "Agustus";
            break;
        case 9:
            return "September";
            break;
        case 10:
            return "Oktober";
            break;
        case 11:
            return "November";
            break;
        case 12:
            return "Desember";
            break;
    }
}

/**
 * Get Day Name
 *
 * Convert date into day name
 *
 * @return	formated date output
 */
function get_day_name($tanggal)
{
    $ubah = gmdate($tanggal, time()+60*60*8);
    $pecah = explode("-",$ubah);
    $tgl = $pecah[2];
    $bln = $pecah[1];
    $thn = $pecah[0];

    $nama = date("l", mktime(0,0,0,$bln,$tgl,$thn));
    $nama_hari = "";
    if($nama=="Sunday") {$nama_hari="Minggu";}
    else if($nama=="Monday") {$nama_hari="Senin";}
    else if($nama=="Tuesday") {$nama_hari="Selasa";}
    else if($nama=="Wednesday") {$nama_hari="Rabu";}
    else if($nama=="Thursday") {$nama_hari="Kamis";}
    else if($nama=="Friday") {$nama_hari="Jumat";}
    else if($nama=="Saturday") {$nama_hari="Sabtu";}
    return $nama_hari;
}

/**
 * Generate Random String
 *
 * Generate Random String to give name / code to value (Default length : 10)
 *
 * @return	encrypted string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Currency to IDR
 *
 * Convert Money Value to Indonesian Format String to given value / code to value (Add prefix : Rp)
 *
 * @return	Indonesian Format Currency
 */
function format_IDR($value)
{
    $a=(string)$value; //membuat $value menjadi string
    $len=strlen($a); //menghitung panjang string $a

    if ( $len <=18 )
    {
        $ratril=$len-3-1;
        $ramil=$len-6-1;
        $rajut=$len-9-1; //untuk mengecek apakah ada nilai ratusan juta (9angka dari belakang)
        $juta=$len-12-1; //untuk mengecek apakah ada nilai jutaan (6angka belakang)
        $ribu=$len-15-1; //untuk mengecek apakah ada nilai ribuan (3angka belakang)

        $angka='';
        for ($i=0;$i<$len;$i++)
        {
            if ( $i == $ratril )
            {
                $angka=$angka.$a[$i].".";
            }
            else if ($i == $ramil )
            {
                $angka=$angka.$a[$i].".";
            }
            else if ( $i == $rajut )
            {
                $angka=$angka.$a[$i]."."; //meletakkan tanda titik setelah 3angka dari depan
            }
            else if ( $i == $juta )
            {
                $angka=$angka.$a[$i]."."; //meletakkan tanda titik setelah 6angka dari depan
            }
            else if ( $i == $ribu )
            {
                $angka=$angka.$a[$i]."."; //meletakkan tanda titik setelah 9angka dari depan
            }
            else
            {
                $angka=$angka.$a[$i];
            }
        }
    }
    return $angka.",-";
}

/**
 * formatDatepickerToMySql
 *
 * Convert common date format to SQL based
 *
 * @return	SQL based date format
 */
function formatDatepickerToMySql($date) {
    $newDate = "0000-00-00";
    if ($date != "") {
        $dateArr = explode("/", $date);
        $newDate = $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
        return $newDate;
    }
    return $newDate;
}

/**
 * NoHTML
 *
 * Sanitize Output String
 *
 * @return	Clean Output String
 */
function nohtml($message) 
{
    $message = strip_tags($message);
    $message = htmlspecialchars($message, ENT_QUOTES);
    return $message;
}

/**
 * filterInput
 *
 * Sanitize Input String
 *
 * @return	Clean Input String
 */
function filterInput($content)
{
    $content = trim($content);
    $content = stripslashes($content);

    return $content;
}

/**
 * filterOutput
 *
 * Sanitize Output String for viewing data
 *
 * @return	Clean Output String
 */
function filterOutput($content)
{
    $content = htmlentities($content, ENT_NOQUOTES);
    $content = nl2br($content, false);

    return $content;
}
?>
