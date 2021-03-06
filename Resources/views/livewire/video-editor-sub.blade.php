<?php declare(strict_types=1);
header('Accept-Ranges: bytes'); ?>
<div>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.5.1/nouislider.min.css">
    <link href="{{ Theme::asset('media::lib/video-editor-sub/style.css') }}" rel="stylesheet">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" />
    <link href="https://vjs.zencdn.net/7.17.0/video-js.css" rel="stylesheet" />

    <div class="loading-wrapper hide">
        <img src="{{ Theme::asset('media::lib/video-editor-sub/spinner.gif') }}" class="loading-image">
    </div>

    <div class="video-editor-wrapper w-full p-10">
        <div class="flex flex-col sm:flex-row">
            @if ($src)
                <div class="w-full sm:w-1/2 p-3 ">
                    <div wire:ignore>
                        <video id="video-editor-player" track="{{ $vtt }}" class="video-js" controls
                            preload="auto" data-setup="{}">
                            <source src="{{ $src }}" type="video/mp4" />

                            {{-- <track kind="captions" src="{{$vtt}}" srclang="en" label="English" default> --}}

                        </video>
                        <div class="mt-16 mb-10">
                            <div id="slider-range"></div>
                        </div>
                    </div>
                    <div
                        class="grid xl:grid-cols-3 grid-cols-2 items-center justify-around shadow rounded-xl button-grid">
                        <button class="py-2 editor-btn play-full">
                            <i class="fa fa-play mr-2"></i>Play
                        </button>
                        <button class="py-2 editor-btn play-slider">
                            <i class="fa fa-play mr-2"></i>Play Slider
                        </button>
                        <button class="py-2 editor-btn" id="save-video">
                            <i class="fa fa-save mr-2"></i>Save in DB
                        </button>
                        <button class="py-2 editor-btn" id="get-snap">
                            <i class="fa fa-camera mr-2"></i>Snap
                        </button>
                        <a id="downlink" style="display:none;" download="screenshot"> </a>
                        <button class="py-2 editor-btn" id="cut-video">
                            <i class="fa fa-cut mr-2"></i>Cut
                        </button>
                        <button wire:click="mergeEpisodes"
                            class="py-2 editor-btn {{ count($episodes) > 0 ? '' : 'cursor-no-drop' }}"
                            id="merge-video" {{ count($episodes) > 0 ? '' : 'disabled' }}>
                            <i class="fa fa-compress mr-2"></i>Merge
                        </button>
                        {{-- <button class="py-2 editor-btn {{$selectedSubtitle?'':'cursor-no-drop'}}" --}}
                        {{-- id="save-subtitle-range" {{$selectedSubtitle?'':'disabled'}}> --}}
                        {{-- <i class="fa fa-save mr-2 "></i>Subtitle --}}
                        {{-- </button> --}}
                    </div>
                    @if (count($episodes) > 0)
                        <div class="grid items-center w-full grid-cols-2 md:grid-cols-4 gap-2 my-10">
                            @foreach ($episodes as $ek => $episode)
                                <div class="bg-white p-2 shadow rounded-xl ">
                                    <div src="{{ $src }}" time="{{ implode(',', $episode['time']) }}"
                                        class="video-episode rounded-xl relative"
                                        style="background-image: url('{{ $episode['image'] }}')">
                                        <i class="fa fa-play play-episode absolute text-white"></i>
                                        <div class="absolute right-2 rounded-sm  episode-icon  text-white bg-gray-400"
                                            wire:click="deleteEpisode({{ $ek }})"><i
                                                class="fa fa-times"></i></div>
                                        <div class="absolute right-10 rounded-sm episode-icon right-8 text-white bg-gray-400 download-episode"
                                            wire:click="downloadEpisode({{ $ek }})"><i
                                                class="fa fa-download"></i></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if (count($snaps) > 0)
                        <h2 class="w-full text-lg text-black font-semibold mb-3 mt-10">Snaps</h2>
                    @endif
                    <div class="grid items-center w-full grid-cols-2 md:grid-cols-4 gap-2 mb-10">

                        @foreach ($snaps as $sk => $snap)
                            <div class="bg-white p-2 shadow rounded-xl ">
                                <div class="video-episode rounded-xl relative"
                                    style="background-image: url('{{ url($snap) }}')">

                                    <div class="absolute right-2 rounded-sm  episode-icon  text-white bg-gray-400"
                                        wire:click="deleteSnap({{ $sk }})"><i class="fa fa-times"></i>
                                    </div>
                                    <div class="absolute right-10 rounded-sm episode-icon right-8 text-white bg-gray-400 download-episode"
                                        wire:click="downloadSnap({{ $sk }})"><i class="fa fa-download"></i>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center">
                                    <input wire:model="primary_image" value="{{ $snap }}"
                                        class="mr-2" id="primary-image-{{ $sk }}" type="radio"
                                        name="primary_image" {{ $sk == 0 ? 'checked' : '' }}>
                                    <label for="primary-image-{{ $sk }}">Primary Image</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="w-full sm:w-1/2  p-3 subtitle-wrapper subtitle-text">
                    <h2 class="text-xl font-semibold mb-3 text-black ">Sub Titles</h2>
                    <div class="p-5 shadow  bg-white float-left {{-- card --}}">
                        @foreach ($subtitles as $sk => $subtitle)
                            <span id="subtitle-{{ $sk }}" time="{{ implode(',', $subtitle['time']) }}"
                                class="{{-- flex items-center justify-between --}}  font-semibold subtitle-item rounded-xl  {{ $selectedSubtitle == $subtitle['id'] ? 'selected' : '' }}"
                                {{-- wire:click="setSelectedSubtitle({{ $subtitle['id'] }})" --}}>
                                {{ implode(' ', $subtitle['text']) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @else
                <p>Please add :src attribute to livewire component</p>
            @endif

            <template><span id="subtitle-control">

                    <b id="set-slider">set slider from sub</b>
                    <b id="set-slider-math">set slider from math</b>
                    <b id="edit-subtitle">Edit</b>
                </span></template>

            <div class="modal" tabindex="-1" role="dialog" id="video-model" wire:ignore>
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-semibold">Save Video</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="video-form" action="">
                                <div class="flex flex-col mb-3">
                                    <label class="w-full mb-2">Video Name</label>
                                    <input type="text" class="form-control w-full" placeholder="Video Name"
                                        name="video_name">
                                </div>
                                <div class="flex flex-col mb-3">
                                    <label class="mb-2">Video Description</label>
                                    <textarea type="text" class="form-control" placeholder="Video Description" name="video_description"></textarea>
                                </div>
                                @if (config('video.use_category'))
                                    <div class="flex flex-col mb-3">
                                        <label class="mb-2">Video Category</label>
                                        <select class="form-control" placeholder="Category" name="video_category">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category[config('video.category_pk')] }}">
                                                    {{ $category[config('video.category_title_field')] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if (!empty($tags))
                                    @foreach ($tags as $tag)
                                        <div class="flex flex-col mb-3">
                                            <label class="mb-2">{{ $tag['entity'] }}</label>

                                            <select class="form-control" placeholder="{{ $tag['entity'] }}"
                                                name="tags[{{ $tag['entity'] }}]">
                                                @foreach ($tag['data'] as $data)
                                                    <option value="{{ $data[$tag['tag_pk']] }}">
                                                        {{ $data[$tag['tag_title']] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                @endif
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary save-video-db">Save Video</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal" tabindex="-1" role="dialog" id="subtitle-model">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-semibold">Edit Subtitle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="subtitle-form" action="">
                                @foreach ($this->selectedSubtitles as $subti)
                                    <div class="flex flex-col mb-3">
                                        <label class="w-full mb-2">Title</label>
                                        <textarea type="text" class="form-control w-full" placeholder="" name="subtitles[{{ $subti }}]"
                                            id="subtitle_text">{{ implode(PHP_EOL, $this->subtitles[$subti]['text']) }}</textarea>
                                    </div>
                                @endforeach

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary save-subtitle-model">Save Subtitle</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://vjs.zencdn.net/7.17.0/video.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/videojs-offset@2.1.3/dist/videojs-offset.min.js"></script>

    <script src="{{ Theme::asset('media::lib/video-editor-sub/scripts.js') }}"></script>

    <script>
        window.addEventListener('edit-subtitle-modal', event => {
            setTimeout(function() {
                $('#subtitle-model').modal('show');
            }, 1000)

        })
        window.addEventListener('subtitle-saved', event => {
            $('#subtitle-model').modal('hide');
            refreshTrack()
        })
        window.addEventListener('done', event => {
            $('.loading-wrapper').addClass('hide');
            $('#video-model').modal('hide');
            alert('Video saved successfully.')
        })
        window.addEventListener('download-file', event => {
            $('.loading-wrapper').addClass('hide');
            for (var d in event.detail) {
                var link = document.getElementById("downlink");
                link.download = basename(event.detail[d]);
                link.href = event.detail[d];
                link.click();
            }
        })
    </script>


</div>
