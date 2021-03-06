<?php
/**
 * Description of VideoStream
 *
 * @author Rana
 * @link http://codesamplez.com/programming/php-html5-video-streaming-tutorial
 * @link https://code-pocket.info/20200624304/
 */


//declare(strict_types=1);

namespace Modules\Media\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

/**
 * Undocumented class
 */
class VideoStream {
    //private $stream = "";

    //private string $path = "";
    private int $buffer = 102400;
    private int $start  = -1;
    private int $end    = -1;
    private int $size   = 0;

    private array $vars=[];
 
    /**
     * Undocumented function
     *
     * @param string $filePath
     * @return void
     */
    function __construct(string $disk,string $path) {
        //$this->path = $path;
        $storage=Storage::disk($disk);
        $this->vars['stream'] = $storage->readStream($path);
        $this->mime = $storage->mimeType($path);
        $this->filemtime = $storage->lastModified($path);
        $this->size = $storage->size($path);
    }
     
    /**
     * Open stream
     * @return void
     */
    private function open() {
        /*
        if (!($this->vars['stream'] = fopen($this->path, 'rb'))) {
            die('Could not open stream for reading');
        }
        */
         
    }
     
    /**
     * Set proper header to serve the video content
     * @return void
     */
    private function setHeader() {
        ob_get_clean();
        //header("Content-Type: video/mp4");
        header("Content-Type: ".$this->mime);

        header("Cache-Control: max-age=2592000, public");
        header("Expires: ".gmdate('D, d M Y H:i:s', time()+2592000) . ' GMT');
        /*
        $time=@filemtime($this->path);
        if($time==false){
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');
        */
        header("Last-Modified: ".gmdate('D, d M Y H:i:s', $this->filemtime) . ' GMT');
        $this->start = 0;
        /*
        $size=filesize($this->path);
        if($size==false){
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        $this->size = $size;
        */
        $this->end   = $this->size - 1;
        header("Accept-Ranges: 0-".$this->end);
         
        if (isset($_SERVER['HTTP_RANGE'])) {
  
            $c_start = $this->start;
            $c_end = $this->end;
 
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                exit;
            }
            if ($range == '-') {
                $c_start = $this->size - substr($range, 1);
            }else{
                $range = explode('-', $range);
                $c_start = $range[0];
                 
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $c_end;
            }
            $c_end = ($c_end > $this->end) ? $this->end : $c_end;
            if ($c_start > $c_end || $c_start > $this->size - 1 || $c_end >= $this->size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $this->start-$this->end/$this->size");
                exit;
            }
            $this->start = intval($c_start);
            $this->end = intval($c_end);
            $length = $this->end - $this->start + 1;
            fseek($this->vars['stream'], $this->start);
            header('HTTP/1.1 206 Partial Content');
            header("Content-Length: ".$length);
            header("Content-Range: bytes $this->start-$this->end/".$this->size);
        }else{
            header("Content-Length: ".$this->size);
        }  
         
    }
    
    /**
     * close curretly opened stream
     * @return void
     */
    private function end(){
        fclose($this->vars['stream']);
        exit;
    }
     
    /**
     * perform the streaming of calculated range
     * @return void
     */
    private function stream() {
        $i = $this->start;
        set_time_limit(0);
        while(!feof($this->vars['stream']) && $i <= $this->end) {
            $bytesToRead = $this->buffer;
            if(($i+$bytesToRead) > $this->end) {
                $bytesToRead = $this->end - $i + 1;
            }
            $data = fread($this->vars['stream'], $bytesToRead);
            echo $data;
            flush();
            $i += $bytesToRead;
        }
    }
     
    /**
     * Start streaming video content
     * @return void
     */
    function start() {
        //$this->open();
        $this->setHeader();
        $this->stream();
        $this->end();
    }
}