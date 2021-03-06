<?php

declare(strict_types=1);

namespace Modules\Media\Services;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * SubtitleService.
 */
class SubtitleService {
    private static ?self $instance = null;

    public string $disk = 'media'; // nome che usa storage
    public string $file_path; // siamo in subtitle, percio' il file e' dei subtitle

    public string $field_name = 'txt';

    public Model $model;

    public array $subtitles;

    /**
     * ---.
     */
    public function __construct() {
    }

    /**
     * ---.
     */
    public static function getInstance(): self {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * ---.
     */
    public static function make(): self {
        return static::getInstance();
    }

    public function setFilePath(string $file_path): self {
        $this->file_path = $file_path;
        return $this;
    }

    public function setModel(Model $model): self {
        $this->model = $model;

        return $this;
    }

    public function getModel(): Model {
        return $this->model;
    }

    public function upateModel(): self {
        $txt = $this->getPlain();
        $up = [$this->field_name => $txt];
        $this->model = tap($this->model)->update($up);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getPlain(): string {
        $content = $this->getContent();
        $xmlObject = simplexml_load_string($content);
        if($xmlObject==false){
            return '';
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        $txt = '';
        foreach ($xmlObject->annotation->type->sentence as $sentence) {
            foreach ($sentence->item as $item) {
                $txt .= $item->__toString().' ';
            }
        }

        return $txt;
    }

    /**
     * restituisce i sottotitoli, dal file ..
     */
    public function get(): array {
        $info = pathinfo($this->file_path);
        if(!isset($info['extension'])){
            return [];;
        }
        $func = 'getFrom'.Str::studly($info['extension']);
        
        return $this->{$func}();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function getContent(): string {
        
        //$path = Storage::disk($this->disk)->path('videos/'.$this->file_path);
        //$path = Storage::path($this->file_path);
        //$path = realpath($path);
        $path = realpath($this->file_path);
        if($path==false){
            return '';
            throw new Exception('path:['.$path.']'.PHP_EOL.'
                file_path:['.$this->file_path.']'.PHP_EOL.'
                ['.__LINE__.']['.__FILE__.']'.PHP_EOL);
        }
        $content = File::get($path);
        return $content;
    }

    public function getFromXml(): array {
        $this->subtitles = [];
        $content = $this->getContent();
        $xmlObject = simplexml_load_string($content);
        if($xmlObject==false){
            throw new Exception('content:['.$content.']'.PHP_EOL.'['.__LINE__.']['.__FILE__.']');
        }
       
        $data = [];
        $i = 0;
        $sentence_i = 0;
        foreach ($xmlObject->annotation->type->sentence as $sentence) {
            $item_i = 0;
            foreach ($sentence->item as $item) {
                $attributes=$item->attributes();
                if($attributes==null){
                    throw new Exception('['.__LINE__.']['.__FILE__.']');
                }
                // 00:06:35,360
                $start = intval($attributes->start->__toString()) / 1000;
                $end = intval($attributes->end->__toString()) / 1000;
                // dddx([$start,$this->secondsToHms($start),$end,$this->secondsToHms($end)]);
                $tmp = [
                    // 'id' => $i++,
                    'sentence_i' => $sentence_i,
                    'item_i' => $item_i,
                    'start' => $start,
                    'end' => $end,
                    'time' => secondsToHms($start).','.secondsToHms($end),
                    'text' => $item->__toString(),
                ];
                $data[] = $tmp;
                ++$item_i;
            }
            ++$sentence_i;
        }
        
        return $data;
    }

    /**
     * Undocumented function
     *
     * @param string $srtFile
     * @param string $webVttFile
     * @return void
     */
    public function srtToVtt($srtFile, $webVttFile) {
        $fileHandle = fopen(public_path($srtFile), 'r');
        $lines = [];
        if ($fileHandle) {
            // $lines = [];
            while (false !== ($line = fgets($fileHandle, 8192))) {
                $lines[] = $line;
            }
            if (! feof($fileHandle)) {
                exit("Error: unexpected fgets() fail\n");
            } else {
                //($fileHandle);
            }
        }

        $length = count($lines);
        for ($index = 1; $index < $length; ++$index) {
            if (1 === $index || '' === trim($lines[$index - 2])) {
                $lines[$index] = str_replace(',', '.', $lines[$index]);
            }
        }
        $header = "WEBVTT\n\n";
        file_put_contents(public_path($webVttFile), $header.implode('', $lines));
    }
}