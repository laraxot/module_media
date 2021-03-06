<div wire:ignore>
    <div class="hadow-lg p-3 mb-5 bg-body rounded subtitle-text">
        {!! $subtitles_html !!}
    </div>
    <span id="highlightmenu" class="subtitle-control" >
            <button wire:click="setSlider()" class="btn btn-primary">set slider</button>
            <button wire:click="editSubtitle()" class="btn btn-primary">Edit</button>
    </span>
</div>
@push('scripts')
<script>
    
    //document.querySelector('.subtitle-text').onpointerup = ()=>{
    $('.subtitle-text').on('mouseup',function(){
        var supportPageOffset = window.pageXOffset !== undefined;
        var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");

        let selection = document.getSelection();
        let text = selection.toString();

        if (selection.rangeCount > 0) {
            range = selection.getRangeAt(0);
            var clonedSelection = range.cloneContents();
            var div = document.createElement('div');
            div.appendChild(clonedSelection);
            var start=$(div).children().first().data('start');
            var end=$(div).children().last().data('end');
            //window.livewire.emit('setSubRange',start,end);
            @this.setSubRange(start,end);
        }
        
        if (text !== "") {
            var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
            var top = window.event.clientY + y;
            var left = window.event.clientX;
            //console.log(top,left);
            $('#highlightmenu').css('position','absolute');
            $('#highlightmenu').css('top',top);
            $('#highlightmenu').css('left',left);
        }
    //}
    });
</script>
@endpush