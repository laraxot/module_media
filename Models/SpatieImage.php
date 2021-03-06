<?php

declare(strict_types=1);

namespace Modules\Media\Models;

// use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Lang\Models\Traits\LinkedTrait;
use Modules\Mediamonitor\Models\Traits\HasDomains;
use Modules\Xot\Traits\Updater;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Spatie\ModelStatus\HasStatuses;
use Spatie\Tags\HasTags; // spatie tags

/**
 * Modules\Media\Models\SpatieImage
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int $user_id
 * @property string $time_from
 * @property string $time_to
 * @property string|null $guid
 * @property string|null $image_src
 * @property-read string|null $lang
 * @property-read string|null $post_type
 * @property-read string $status
 * @property string|null $subtitle
 * @property string|null $title
 * @property string|null $txt
 * @property-read string|null $user_handle
 * @property-read string|null $video_url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Xot\Models\Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $model
 * @property-read \Modules\Lang\Models\Post|null $post
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Lang\Models\Post[] $posts
 * @property-read int|null $posts_count
 * @property \Illuminate\Database\Eloquent\Collection|\Modules\Tag\Models\Tag[] $tags
 * @property-write mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\ModelStatus\Status[] $statuses
 * @property-read int|null $statuses_count
 * @property-read int|null $tags_count
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage currentStatus(...$names)
 * @method static \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage ofItem(string $guid)
 * @method static Builder|Media ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage otherCurrentStatus(...$names)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereCollectionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereConversionsDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereGeneratedConversions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereManipulations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereResponsiveImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array $tags, ?string $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|SpatieImage withPost(string $guid)
 * @mixin \Eloquent
 */
class SpatieImage extends BaseMedia {
    use Updater;

    // use Searchable;
    // use Cachable;
    use HasFactory;
    use HasTags; // spatie tags
    use HasStatuses; // spatie status
    // use HasDomains; //vecchio, non si usa pi??
    use LinkedTrait;
    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @see  https://laravel-news.com/6-eloquent-secrets
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    protected $perPage = 30;

    protected $fillable = [
        'id', 'model_type', 'model_id', 'uuid', 'collection_name', 'name',
        'file_name', 'mime_type', 'disk', 'conversions_disk', 'size',
        'manipulations',
        'custom_properties', 'generated_conversions', 'responsive_images',
        'order_column', 'user_id',
        'time_from', 'time_to',
        'created_at', 'updated_at', 'created_by', 'updated_by',
        'title', 'subtitle', 'guid',
    ];

    protected $appends = [
        'original_url', 'preview_url',
        'title', 'subtitle',
    ];

    // protected $with = ['tags:id,name'];
    protected $with = ['tags'];

    protected $table = 'spatie_images';

    /**
     * Undocumented function.
     */
    public function getVideoUrlAttribute(?string $value): ?string {
        return url('/streamsnip/'.$this->id);
    }
}
