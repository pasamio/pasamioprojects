<?php
/**
* @version $Id: class.migrator.php 2006-05-27 23:00
* @package Migrator
* @copyright Copyright (C) 2006 by Mambobaer.de. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/



// no direct access
defined('_VALID_MOS') or die('Restricted access');

class Dump_File{
  var $pathname = NULL;
  var $filename = NULL;

  function Dump_File($pathname, $filename){
    $this->pathname = $pathname;
    $this->filename = $filename;
  }

  function download($inline = false){
	$HTTP_USER_AGENT = '';
    $user_agent = (isset($_SERVER["HTTP_USER_AGENT"]) ) ? $_SERVER["HTTP_USER_AGENT"] : $HTTP_USER_AGENT;
    while (@ob_end_clean());
    $filesize = filesize($this->pathname."/".$this->filename);
    $filename = $this->pathname."/".$this->filename;

    header("HTTP/1.1 200 OK");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Expires: 0");
    header("Content-Length: ".$filesize);
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$this->filename);
    header("Content-Transfer-Encoding: binary");

    $this->readfile_chunked($filename);

  }

  function readfile_chunked($filename, $retbytes=true){
    $chunksize = 1*(1024*1024); // how many bytes per chunk
    $buffer = '';
    $cnt =0;
    $handle = fopen($filename, 'rb');
    if ($handle === false){
        return false;
    }
    while (!feof($handle)){
          $buffer = fread($handle, $chunksize);
          echo $buffer;
          if ($retbytes){
             $cnt += strlen($buffer);
          }
    }
    $status = fclose($handle);
    if ($retbytes && $status) {
       return $cnt; // return num. bytes delivered like readfile() does.
    }
    return $status;
  }
}
class JFiler{

  var $zipped   = false;
  var $filename = '';
  var $isopen   = false;
  var $fp       = 0;
  var $reopen   = false;


  function JFiler($zipped = false){
    $this->zipped = $zipped;
  }

  function compressFile($source, $level=false){
     $dest=$source.'.gz';
     $mode='wb'.$level;
     $error=false;
     if ($fp_out=gzopen($dest,$mode)){
        if ($fp_in=fopen($source,'rb')){
           while (!feof($fp_in)){
                 gzwrite($fp_out,fread($fp_in,1024*512));
           }
           fclose($fp_in);
        }else{
           $error=true;
        }
        gzclose($fp_out);
     }else{
        $error=true;
     }
     if ($error){
        return false;
     }else{
        return $dest;
     }
  }

  function createFile($filename){
    $this->filename = $filename;
    if ($this->fp = @fopen($this->filename, "wb")){ //stop fopen spamming us
       @chmod ($this->filename, 0777);
       $this->isopen = true;
       return $this->filename;
    }else{
       $this->isopen = false;
       return FALSE;
    }
  }

  function openFile($filename){
    $this->filename = $filename;
    if ($this->fp = @fopen($this->filename, "ab")){ //stop fopen spamming us
       $this->isopen = true;
       return $this->filename;
    }else{
       $this->isopen = false;
       return false;
    }
  }

  function writeFile($data){
    fwrite($this->fp, $data);
  }

  function closeFile(){
    if ($this->zipped){
       $this->compressFile($this->filename, 9);
       fclose($this->fp);
       unlink($this->filename);
       $this->isopen = false;
    }else{
       fclose($this->fp);
       $this->isopen = false;
    }
  }

  function getFileSize(){
    $size = 0;
    if (!$this->isopen){
       if ($this->zipped){
          $size = filesize($this->filename.".gz");
       }else{
          $size = filesize($this->filename);
       }
    }
    return $size;
  }

  function getFileInfo($filename){
    $info       = "";
    $path_parts = pathinfo($filename);
    if (strtolower($path_parts["extension"]) == "gz"){
       $file = gzopen($filename, "r");
       while (!gzeof($file)){
             $buffer = trim(gzgets($file, MAX_LINE_LENGTH));
             if (strlen($buffer)== 0){
                break;
             }else{
                $info.= str_replace(' ', "&nbsp;", $buffer)."<br />";
             }
       }
       gzclose($file);
       return $info;
    }else{
       $file = fopen($filename, "r");
       while (!feof($file)){
             $buffer = trim(fgets($file, MAX_LINE_LENGTH));
             if (strlen($buffer)== 0){
                break;
             }else{
                $info.= str_replace(' ', "&nbsp;", $buffer)."<br />";
             }
       }
       fclose($file);
       return $info;
    }

  }
}

