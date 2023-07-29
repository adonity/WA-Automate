
@foreach ($allchat as $item)
    <div class="chat {{ $item->me?'':'chat-left' }}" id="{{ $item->id }}">
        <div class="chat-body">
            <div class="chat-message">
                @if ($item->link)
                    <img class="w-100" src="{{ env('URL_WA_SERVER')."/media/".$item->link }}" alt="">
                @endif
                <p class="message">{{ $item->text }}</p>
                <p class="time"><small>{{ $item->created_at }}</small></p>
            </div>
        </div>
    </div>
@endforeach