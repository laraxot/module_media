<?php
/**
 * --.
 */
declare(strict_types=1);

namespace Modules\Media\Models\Panels\Actions;

// -------- services --------

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Modules\Media\Services\VideoStream;
use Modules\Theme\Services\ThemeService;
use Modules\Xot\Models\Panels\Actions\XotBasePanelAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

// -------- bases -----------

/**
 * Class TestAction.
 */
class TryStreamAction extends XotBasePanelAction {
    public bool $onItem = true;
    public string $icon = '<i class="fab fa-youtube"></i>';

    public string $video_path;

    public function __construct() {
        $this->video_path = base_path('/../media/videos/Internazionali-BBCnews-20220312-070000-075959.mp4');
    }

    /**
     * @return mixed
     */
    public function handle() {
        $drivers = [
            'stream',
            'stream1',
            'stream2',
            'stream3',
            'stream4',
        ];
        $i = request('i');
        $driver = null;

        if (isset($drivers[$i])) {
            $driver = $drivers[$i];
        }

        $view = ThemeService::getView();

        $view_params = [
            'view' => $view,
            'drivers' => $drivers,
            'driver' => $driver,
        ];
        if (null == $driver) {
            return view()->make($view, $view_params);
        }

        return $this->{$driver}();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function stream() {
        $stream = new VideoStream($this->video_path);
        $stream->start();
    }
    /*
    public function stream1() {
        $stream = $filesystem->readStream($location);
        $headers = [
            'Content-Type' => $fs->getMimetype($location),
            'Content-Length' => $fs->getSize($location),
            'Content-disposition' => 'attachment; filename="'.basename($file).'"',
        ];

        return Response::stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, $headers);
    }
    */

    /**
     * Undocumented function
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream2() { // download not stream
        $headers = [
            'Content-Type' => 'video/mp2t',
            'Content-Length' => File::size($this->video_path),
            'Content-Disposition' => 'attachment; filename="'.basename($this->video_path).'.ts"',
        ];

        return Response::stream(function () {
            try {
                $stream = fopen($this->video_path, 'r');
                if($stream==false){
                    throw new Exception('['.__LINE__.']['.__FILE__.']');
                }
                fpassthru($stream);
            } catch (Exception $e) {
                //    Log::error($e);
                dddx($e);
            }
        }, 200, $headers);
    }

    /**
     * Undocumented function
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function stream3() { // download not stream
        $response = new BinaryFileResponse($this->video_path, 200, [
            'Content-Type' => 'video/mp4',
        ]);
        $name = basename($this->video_path);
        $response->setContentDisposition('attachment', $name, str_replace('%', '', Str::ascii($name)));

        return $response;
    }
}