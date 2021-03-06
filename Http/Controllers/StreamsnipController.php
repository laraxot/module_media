<?php
/**
 * @link https://code-pocket.info/20200624304/
 */

declare(strict_types=1);

namespace Modules\Media\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Modules\Xot\Services\ArrayService;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Services\VideoStream;

/**
 * ---.
 */
class StreamsnipController extends Controller {
    /**
     * ---.
     *
     * @return void
     */
    public function __invoke(int $media_id) {
        try {
            $media_class = \Modules\Media\Models\SpatieImage::class;
            $media = $media_class::find($media_id);
            //dddx($media->toArray());
            /*
            $methods=collect(get_class_methods($media))
                ->filter(function($item){
                    $exclude=[
                        'getVideoUrlAttribute',
                        'getTemporaryUrl',
                        'getUrlGenerator',
                        'getAvailableUrl',
                        'getAvailableFullUrl',
                        'getAvailablePath',
                        'getCustomProperty',
                        'getAttribute',
                        'getAttributeValue',
                        'getRelationValue',
                        'getGlobalScope',
                        'getActualClassNameForMorph',
                        'getRelation',
                        'getTableMorph',
                        'getUserHandleAttribute',
                        'getPostTypeAttribute',
                        'getLangAttribute',
                        'getPostAttr',
                        'getTitleAttribute',
                        'getSubtitleAttribute',
                        'getGuidAttribute',
                        'getImageSrcAttribute',
                        'getTxtAttribute',

                        'getSrcset',
                        
                    ];
                    return (Str::startsWith($item,'get') && !in_array($item,$exclude));
                })->map(function($item) use($media){
                    $value='---';
                    try{
                        $value=$media->{$item}();
                    }catch(Exception $e){
                        $value=$e->getMessage();
                    }

                    return [
                        'name'=>$item,
                        'value'=>$value,
                    ];
                })->all();
            
            echo ArrayService::make()->setArray($methods)->toHtml();
            
            dddx([
                'media'=>$media,
                //'getUrlGenerator'=>$media->getUrlGenerator(),
                'getPath'=>$media->getPath(),
                'methods'=>$methods,
            ]);
            */
            //dddx([$media->disk,$media->original_url,Storage::disk($media->disk)->exists($media->original_url)]);

            $stream = new VideoStream($media->disk,$media->original_url);
            $stream->start();
            //return response()->stream(function () use ($stream) {
            //$stream->start();
            //});
        }

        // catch exception
        catch (Exception $e) {
            dddx($e->getMessage());
        }
    }
}