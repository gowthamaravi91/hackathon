<?php
/**
 * Created by PhpStorm.
 * User: Gowthamaravi
 * Date: 2/24/14
 * Time: 8:02 AM
 */




class Photo {


    public $filename;
    private  $type;
    private  $size;
    private $width;
    private $height;
    public $is_true;
    public $errors = array();
    private $temp_path;
    private $upload_dir;
    private $ext;

    protected $upload_errors = array(
        // http://www.php.net/manual/en/features.file-upload.errors.php
        UPLOAD_ERR_OK 				=> "No errors.",
        UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
        UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
        UPLOAD_ERR_NO_FILE 		=> "No file.",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
    );

    // Pass in $_FILE(['uploaded_file']) as an argument
    function __construct($file) {

        $this->upload_dir ="../../../upload";
        // Perform error checking on the form parameters
        if(!$file || empty($file) || !is_array($file)) {
            // error: nothing uploaded or wrong argument usage
            $this->errors[] = "No file was uploaded.";


            return false;

        } elseif($file['error'] != 0) {
            // error: report what PHP says went wrong
            $this->errors[] = $this->upload_errors[$file['error']];

            return false;

        } else {
            // Set object attributes to the form parameters.
            $this->temp_path  = $file['tmp_name'];

            $this->type       = $file['type'];

            $this->size       = $file['size'];

            $this->getExtension($file['name']);

            $this->filename   = time().".".$this->ext;


            // Don't worry about saving anything to the database yet.
            $this->is_true = $this->save()? true : false;
        }
    }

    public static function is_single($file){

        $i = 0;

        foreach ($file as $file1):

            $i++;

        endforeach;

        return $i;
    }

    public function save(){



        if (getimagesize($this->temp_path)){

            list($width, $height)= getimagesize($this->temp_path);

            $this->width = $width;

            $this->height = $height;

            if($this->resize(600,800,$this->upload_dir)){

                return $this->resize(270,300,$this->upload_dir."/imagetemp")? true : false ;

            }
            //unlink($this->upload_dir.$this->filename);
            return false;


        }

        return false;
    }

    private function resize($no1,$no2,$path){

        if ($this->width >= $this->height) {

            $new_height = $no1;

            $new_width = @(floor(($this->width / $this->height) * $new_height));

        } else {

            $new_height = $no2;

            $new_width = @(floor(($this->width / $this->height) * $new_height));
        }


        return $this->convert_image($new_height,$new_width,$path)? true : false ;
    }

    private function getExtension($str) {

        $i = strrpos($str, ".");

        if (!$i) {

            return "";

        }
        $l = strlen($str) - $i;

        $this->ext = substr($str, $i + 1, $l);

    }


    private function convert_image($new_height,$new_width,$loc){

        if ($this->ext == "png" || $this->ext == "PNG") {

            $png = imagecreatefrompng($this->temp_path);

            $targetImage = imagecreatetruecolor($new_width, $new_height);

            imagealphablending($targetImage, false);

            imagesavealpha($targetImage, true);

            imagecopyresampled($targetImage, $png, 0, 0, 0, 0, $new_width, $new_width, $this->width, $this->height);

            $new_name = $loc . "/" . $this->filename;

            if ($copied = copy($this->temp_path, $new_name)) {

                imagepng($targetImage, $new_name, 9);

                return true;

            }

        } elseif ($this->ext == "jpg" || $this->ext == "JPG" || $this->ext == "jpeg" || $this->ext == "JPEG" ) {

            $src = imagecreatefromjpeg($this->temp_path);

            $tmp = imagecreatetruecolor($new_width, $new_height);

            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height);

            $new_name = $loc . "/" . $this->filename;

            if ($copied = copy($this->temp_path, $new_name)) {

                imagejpeg($tmp, $new_name, 100);

                imagedestroy($tmp);

                imagedestroy($src);

                return true;

            } else {

                return false;

            }
//
        }elseif ($this->ext == "gif" || $this->ext == "GIF") {

            $src = imagecreatefromgif($this->temp_path);

            $tmp = imagecreatetruecolor($new_width, $new_height);

            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height);

            $new_name = $loc . "/" . $this->filename;

            if ($copied = copy($this->temp_path, $new_name)) {

                imagegif($tmp, $new_name, 100);

                imagedestroy($tmp);

                imagedestroy($src);

                return true;

            } else {

                return false;
            }
        }

    }

    public function destroy() {

        if($this->delete()) {

            return unlink($this->temp_path."/".$this->filename) ? true : false;
        } else {
            // database delete failed
            return false;
        }
    }


    private function delete(){
        // do delete code under here
    }

    public function imagecreatefrombmp($p_sFile)
    {
        //    Load the image into a string
        $file    =    fopen($p_sFile,"rb");
        $read    =    fread($file,10);
        while(!feof($file)&&($read<>""))
            $read    .=    fread($file,1024);

        $temp    =    unpack("H*",$read);
        $hex    =    $temp[1];
        $header    =    substr($hex,0,108);

        //    Process the header
        //    Structure: http://www.fastgraph.com/help/bmp_header_format.html
        if (substr($header,0,4)=="424d")
        {
            //    Cut it in parts of 2 bytes
            $header_parts    =    str_split($header,2);

            //    Get the width        4 bytes
            $width            =    hexdec($header_parts[19].$header_parts[18]);

            //    Get the height        4 bytes
            $height            =    hexdec($header_parts[23].$header_parts[22]);

            //    Unset the header params
            unset($header_parts);
        }

        //    Define starting X and Y
        $x                =    0;
        $y                =    1;

        //    Create newimage
        $image            =    imagecreatetruecolor($width,$height);

        //    Grab the body from the image
        $body            =    substr($hex,108);

        //    Calculate if padding at the end-line is needed
        //    Divided by two to keep overview.
        //    1 byte = 2 HEX-chars
        $body_size        =    (strlen($body)/2);
        $header_size    =    ($width*$height);

        //    Use end-line padding? Only when needed
        $usePadding        =    ($body_size>($header_size*3)+4);

        //    Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption
        //    Calculate the next DWORD-position in the body
        for ($i=0;$i<$body_size;$i+=3)
        {
            //    Calculate line-ending and padding
            if ($x>=$width)
            {
                //    If padding needed, ignore image-padding
                //    Shift i to the ending of the current 32-bit-block
                if ($usePadding)
                    $i    +=    $width%4;

                //    Reset horizontal position
                $x    =    0;

                //    Raise the height-position (bottom-up)
                $y++;

                //    Reached the image-height? Break the for-loop
                if ($y>$height)
                    break;
            }

            //    Calculation of the RGB-pixel (defined as BGR in image-data)
            //    Define $i_pos as absolute position in the body
            $i_pos    =    $i*2;
            $r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]);
            $g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]);
            $b        =    hexdec($body[$i_pos].$body[$i_pos+1]);

            //    Calculate and draw the pixel
            $color    =    imagecolorallocate($image,$r,$g,$b);
            imagesetpixel($image,$x,$height-$y,$color);

            //    Raise the horizontal position
            $x++;
        }

        //    Unset the body / free the memory
        unset($body);

        //    Return image-object
        return $image;
    }

} 