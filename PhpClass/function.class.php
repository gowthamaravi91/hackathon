<?php
/**
 * Created by PhpStorm.
 * User: Gowthamaravi
 * Date: 2/24/14
 * Time: 6:22 AM
 */
function redirect_to($location=NULL){
    if($location != NULL){
        echo "<META HTTP-EQUIV='Refresh' Content='0; URL=".$location."'>";
        exit;
    }
}

function find_isset($test){
    if(empty($test)){
        return false;
    }else
        return $test;
}

function findUrl() {

    $webune_s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";

    $WebuneProtocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $webune_s;

    $WebunePort = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);

    return $WebuneProtocol . "://" . $_SERVER['SERVER_NAME'] . $WebunePort . $_SERVER['REQUEST_URI'];

}

$url=findUrl();

$host_name=substr(strval($url),(strpos(strval($url),"localhost")),9);

function after ($this, $inthat)
{
    $inthat = urlReplaceRemove($inthat);
    if (!is_bool(strpos($inthat, $this)))
        return substr($inthat, strpos($inthat,$this)+strlen($this));
}

function before ($this, $inthat)
{
    $inthat = urlReplaceRemove($inthat);
    return substr($inthat, 0, strpos($inthat, $this));
}


function alert($val){
    echo "<script> alert('".parseToXML($val)."') </script>";
}

function urlReplace($url){
    return returnToXML(str_replace(' ','-',$url)) ;
}

function urlReplaceRemove($url){
    return str_replace('-',' ',$url);
}


function parseToXML($htmlStr)
{
    $xmlStr=str_replace('<','&lt;',$htmlStr);
    $xmlStr=str_replace('>','&gt;',$xmlStr);
    $xmlStr=str_replace('"','&quot;',$xmlStr);
    $xmlStr=str_replace("'",'&#39;',$xmlStr);
    $xmlStr=str_replace("&",'&amp;',$xmlStr);
    return $xmlStr;
}

function returnToXML($htmlStr)
{
    $xmlStr=str_replace('&lt;','<',$htmlStr);
    $xmlStr=str_replace('&gt;','>',$xmlStr);
    $xmlStr=str_replace('&quot;','"',$xmlStr);
    $xmlStr=str_replace('&#39;',"'",$xmlStr);
    $xmlStr=str_replace('&amp;',"&",$xmlStr);
    return $xmlStr;
}

function closetags ( $html )
{
    #put all opened tags into an array
    preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
    $openedtags = $result[1];
    #put all closed tags into an array
    preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
    $closedtags = $result[1];
    $len_opened = count ( $openedtags );
    # all tags are closed
    if( count ( $closedtags ) == $len_opened )
    {
        return $html;
    }
    $openedtags = array_reverse ( $openedtags );
    # close tags
    for( $i = 0; $i < $len_opened; $i++ )
    {
        if ( !in_array ( $openedtags[$i], $closedtags ) )
        {
            $html .= "</" . $openedtags[$i] . ">";
        }
        else
        {
            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
        }
    }
    return $html;
}







//==================================   Pageination     ============================================
define('PAGE_PER_NO',4); // number of results per page.



//============================================ Pagination End ===================================================






if($host_name!="localhost"){
    error_reporting(0);
    define('HTTP_SERVER', 'http://thineshbakehouse.com/site1');

}else{

    define('HTTP_SERVER', 'http://localhost/thineshbakehouse.com');
}

define('SERVER_PATH', HTTP_SERVER.'/');
define('ADMIN', SERVER_PATH.'admin/');
define('ADMIN_CSS',ADMIN."css/");
define('ADMIN_JS',ADMIN."js/");

define('HOME', SERVER_PATH.'Home');
define('ABOUT', SERVER_PATH.'About_Us');
define('CONTACT', SERVER_PATH.'Contact_Us');
define('CAKE', SERVER_PATH.'Cake');
define('CAKE_DETAILS', SERVER_PATH.'Cake_Details');

define('JS',SERVER_PATH."js/");
define('CSS',SERVER_PATH."css/");
define('IMAGES',SERVER_PATH."images");
define('UPLOAD',SERVER_PATH."upload/");
define('TEMP',UPLOAD."imagetemp/");

//define('HTTP_SERVER_IMAGES', HTTP_SERVER_PATH.'images/');
//
//define('HTTP_SERVER_ADMIN_IMAGES', HTTP_SERVER_PATH.'image/');
//define('HTTP_SERVER_ADMIN_IMAGES_TEMP', HTTP_SERVER_PATH.'image/imagetemp/');
//define('HTTP_SERVER_CSS', HTTP_SERVER_PATH.'css/');
//define('HTTP_SERVER_ADMIN_CSS', HTTP_SERVER_PATH.'admin/css/');
//
//define('HTTP_SERVER_JS', HTTP_SERVER_PATH.'js/');
//define('HTTP_SERVER_ADMIN_JS', HTTP_SERVER_PATH.'admin/js/');
//
//
//define('HTTP_SERVER_HOME', HTTP_SERVER_PATH.'Home');
//define('HTTP_SERVER_ABOUT', HTTP_SERVER_PATH.'AboutUs');
//define('HTTP_SERVER_SERVICES', HTTP_SERVER_PATH.'Services');
//define('HTTP_SERVER_INDUSTRIAL', HTTP_SERVER_PATH.'Industrial');
//define('HTTP_SERVER_CONTACT', HTTP_SERVER_PATH.'ContactUs');
//
//define('HTTP_SERVER_PRODUCT', HTTP_SERVER_PATH.'Product');
//define('HTTP_SERVER_PRODUCT_DETAILS', HTTP_SERVER_PATH.'Product_Details/');
//
//define('HTTP_SERVER_BRAND', HTTP_SERVER_PATH.'Brand');