<x-app-layout>
    @livewireStyles
        <link rel="stylesheet" href="{{ asset('/vendors/quill/quill.bubble.css') }}">
        <link rel="stylesheet" href="{{ asset('/vendors/quill/quill.snow.css') }}">
        <style>
        </style>
    @livewireStyles
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Chat Room</h3>
                <p class="text-subtitle text-muted">WhatsApp Chat Room</p>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="media d-flex align-items-center">
                            <div class="avatar me-3">
                                <img src="{{ asset('images/faces/1.jpg') }}" alt="" srcset="">
                                <span class="avatar-status bg-success"></span>
                            </div>
                            <div class="name flex-grow-1">
                                <h6 class="mb-0">{{ isset($inbox->push_name)? $inbox->push_name: "" }}</h6>
                                <span class="text-xs">{{ explode("@", $id)[0] }}</span>
                            </div>
                            <a class="btn text-primary" href="{{ route("inbox.show", ['pesan_masuk' => $id]) }}">
                                <i class="bi bi-arrow-repeat"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-4 bg-grey custom-scroll" style="max-height: 250px;overflow-y:scroll">
                        <div class="chat-content">
                            @foreach ($allchat as $item)
                                <div class="chat {{ $item->key->fromMe?'':'chat-left' }}">
                                    <div class="chat-body">
                                        <div class="chat-message">
                                            @if (isset($item->message->audioMessage))
                                                <audio src="{{ $item->message->audioMessage->url }}" type="{{ $item->message->audioMessage->mimetype }}" controls>
                                            @elseif (isset($item->message->imageMessage))
                                                @if (isset($item->message->imageMessage->jpegThumbnail))
                                                    <img src="data:{{$item->message->imageMessage->mimetype}};base64, {{$item->message->imageMessage->jpegThumbnail}}">
                                                    <p class="message">{{ isset($item->message->imageMessage->caption)? $item->message->imageMessage->caption: "" }}</p>
                                                @else
                                                    <img src="{{ $item->message->imageMessage->url }}">
                                                    <p class="message">{{ isset($item->message->imageMessage->caption)? $item->message->imageMessage->caption: "" }}</p>
                                                @endif
                                            @elseif (isset($item->message->videoMessage))
                                                @if (isset($item->message->videoMessage->jpegThumbnail))
                                                    <img src="data:image/jpeg;base64, {{$item->message->videoMessage->jpegThumbnail }}">
                                                    <p class="message">{{ isset($item->message->videoMessage->caption)? $item->message->videoMessage->caption: "" }}</p>
                                                @else
                                                    <video src="{{ $item->message->videoMessage->url }}" type="{{ $item->message->videoMessage->mimetype }}" controls>
                                                    <p class="message">{{ isset($item->message->videoMessage->caption)? $item->message->videoMessage->caption: "" }}</p>
                                                @endif
                                            @elseif (isset($item->message->documentMessage))
                                                    <p class="message">File Dokument</p>
                                            @elseif (isset($item->message->templateMessage->hydratedTemplate))
                                                    <p class="message">Tombol : {{ $item->message->templateMessage->hydratedTemplate->hydratedContentText }}</p>
                                            @elseif (isset($item->message->extendedTextMessage))
                                                <p class="message">{{ isset($item->message->extendedTextMessage->text)? $item->message->extendedTextMessage->text: "" }}</p>
                                            @else
                                                <p class="message">{{ isset($item->message->conversation)? $item->message->conversation: "" }}</p>
                                            @endif
                                            <p class="time"><small>{{ $item->messageTimestamp }}</small></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('outbox.store') }}" method="post" class="form form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-body">
                                <div class="row">
                                    <input type="hidden" class="form-select" name="id_device" id="devices" value="{{ $id_device }}">
                                    <input type="hidden" name="number" class="form-control" value="{{ explode("@", $id)[0] }}">
                                    <input type="hidden" name="back" class="form-control" value="true">

                                    <div class="col-md-4">
                                        <label>Tipe Pesan</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-select" name="id_type" id="messagetypes" required>
                                                    <option value="">{{__("Tipe Pesan")}}</option>
                                                    @foreach ($messagetypes as $item)
                                                        <option value="{{ $item->id }}">{{ $item->label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid media-link d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Link</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group has-icon-left">
                                                    <div class="position-relative">
                                                        <input type="text" name="link" class="form-control" placeholder="Link Gambar atau Video">
                                                        <div class="form-control-icon">
                                                            <i class="bi bi-link-45deg"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>{{__("Pesan")}}</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="position-relative">
                                                <div id="text" style="height: 150px"></div>
                                                <p class="muted mt-1"><small>* Tekan tombol kombinasi Super+. (Windows dan Titik) untuk emoji</small></p>
                                                <textarea name="text" class="form-control d-none" id="text_real" rows="3" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid button-message d-none">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>{{__("Buttons")}}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="border p-2">
                                                    <div class="d-flex">
                                                        <button type="button" class="btn btn-sm btn-success mb-2 ms-auto btn-add-btn"><i class="bi bi-plus-circle"></i> Add Button</button>
                                                    </div>
                                                    <div id="buttons">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid footer-message d-none mt-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>{{__("Footer")}}</label>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <div class="position-relative">
                                                        <textarea name="footer" class="form-control" rows="2"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <a href="{{ url("/devices") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @livewireScripts
        <script src="{{ asset('/vendors/quill/quill.min.js') }}"></script>
    @livewireScripts
    <script>
        document.addEventListener('livewire:load', function () {
            
            socket.on("pesan-baru", function (data) {
                // window.location.href = window.location.href
            });

            const els = document.querySelectorAll('.message');
            els.forEach(el => {
                let text = el.innerText
                el.innerHTML = format_text( text );
            });

            setTimeout(() => {
                document.querySelector(".chat-content").scrollIntoView(false)
            }, 200);

            
            const options = {
                placeholder: 'Tulis pesan yang akan dikirim...',
                modules: { 
                    toolbar: [
                        ["bold", "italic", "strike"], 
                    ]
                }, 
                theme: 'snow'
            };


            document.querySelector('.btn-add-btn').addEventListener("click", event  => {
                htmlText = `<div class="item-button d-flex border p-1">
                            <div class="p-1">
                                <input type="text" class="form-control form-control-sm" name="button_title[]" placeholder="{{__("Button Title")}}" aria-label="{{__("Button Title")}}">
                            </div>
                            <div class="p-1">
                                <select class="form-select form-select-sm" name="button_type[]" id="devices">
                                    <option value="">{{__("Choose Button Type")}}</option>
                                    <option value="normal">Quick Reply</option>
                                    <option value="call">Call</option>
                                    <option value="url">URL</option>
                                </select>
                            </div>
                            <div class="p-1 d-none">
                                <input type="text" class="form-control form-control-sm" name="button_url[]" placeholder="{{__("URL")}}" aria-label="{{__("Button URL")}}">
                            </div>
                            <div class="p-1 d-none">
                                <input type="text" class="form-control form-control-sm" name="button_phone[]" placeholder="{{__("Call Number")}}" aria-label="{{__("Button Phone Number")}}">
                            </div>
                            <div class="p-1">
                                <button type="button" class="btn btn-sm btn-danger btn-remove-btn"><i class="bi bi-trash-fill"></i></button>
                            </div>
                        </div>`;
                document.querySelector("#buttons").insertAdjacentHTML( 'beforeend', htmlText );
            })

            document.querySelector('.button-message').addEventListener("click", event  => {
                if(event.target.className.indexOf('btn-remove-btn') >= 0 || event.target.parentElement.className.indexOf('btn-remove-btn') >= 0 ){
                    event.target.closest(".item-button").remove()
                }
            })
            
            document.querySelector('.button-message').addEventListener("change", event => {
                console.log(event.target.value)
                if(event.target.name === "button_type[]"){
                    const value = event.target.value
                    const itemButton = event.target.closest(".item-button")
                    if(value === "url"){
                        itemButton.querySelector(`input[name="button_url[]"]`).parentElement.classList.remove("d-none");
                        itemButton.querySelector(`input[name="button_phone[]"]`).parentElement.classList.add("d-none");
                    }else if(value === "call"){
                        itemButton.querySelector(`input[name="button_url[]"]`).parentElement.classList.add("d-none");
                        itemButton.querySelector(`input[name="button_phone[]"]`).parentElement.classList.remove("d-none");
                    }else{
                        itemButton.querySelector(`input[name="button_url[]"]`).parentElement.classList.add("d-none");
                        itemButton.querySelector(`input[name="button_phone[]"]`).parentElement.classList.add("d-none");
                    }
                }
            })

            
            document.querySelector('#messagetypes').addEventListener("change", event => {
                const value = event.target.value
                
                switch (value) {
                    case "1":
                        document.querySelector(`.media-link`).classList.add("d-none");
                        document.querySelector(`.button-message`).classList.add("d-none");
                        document.querySelector(`.footer-message`).classList.add("d-none");
                        document.querySelector(`.pesan-box`).classList.remove("d-none");
                        break;
                    case "2":
                        document.querySelector(`.media-link input`).placeholder = "Link Gambar";
                        document.querySelector(`.media-link`).classList.remove("d-none");
                        document.querySelector(`.pesan-box`).classList.remove("d-none");
                        document.querySelector(`.button-message`).classList.add("d-none");
                        document.querySelector(`.footer-message`).classList.add("d-none");
                        break;
                    case "3":
                        document.querySelector(`.media-link input`).placeholder = "Link Video";
                        document.querySelector(`.media-link`).classList.remove("d-none");
                        document.querySelector(`.pesan-box`).classList.remove("d-none");
                        document.querySelector(`.button-message`).classList.add("d-none");
                        document.querySelector(`.footer-message`).classList.add("d-none");
                        break;
                    case "4":
                        document.querySelector(`.media-link input`).placeholder = "Link Gambar/Video";
                        document.querySelector(`.button-message`).classList.remove("d-none");
                        document.querySelector(`.footer-message`).classList.remove("d-none");
                        document.querySelector(`.pesan-box`).classList.remove("d-none");
                        document.querySelector(`.media-link`).classList.remove("d-none");
                        break;
                    case "5":
                        document.querySelector(`.media-link`).classList.remove("d-none");
                        document.querySelector(`.media-link input`).placeholder = "Link PDF";
                        document.querySelector(`.button-message`).classList.add("d-none");
                        document.querySelector(`.footer-message`).classList.add("d-none");
                        document.querySelector(`.pesan-box`).classList.add("d-none");
                        break;
                    default:
                        break;
                }
            })

            let snow = new Quill('#text', options);

            snow.on('text-change', function(delta, oldDelta, source) {
                const contents = snow.getContents();
                document.querySelector('#text_real').value = JSON.stringify(contents);
            });

            const choices2 = new Choices(document.querySelector('#messagetypes'));
        })

        function format_text(text){
            return text.replace(/(?:\*)(?:(?!\s))((?:(?!\*|\n).)+)(?:\*)/g,'<b>$1</b>')
            .replace(/(?:_)(?:(?!\s))((?:(?!\n|_).)+)(?:_)/g,'<i>$1</i>')
            .replace(/(?:~)(?:(?!\s))((?:(?!\n|~).)+)(?:~)/g,'<s>$1</s>')
            .replace(/(?:--)(?:(?!\s))((?:(?!\n|--).)+)(?:--)/g,'<u>$1</u>')
            .replace(/(?:```)(?:(?!\s))((?:(?!\n|```).)+)(?:```)/g,'<tt>$1</tt>');
        }
    </script>
</x-app-layout>
