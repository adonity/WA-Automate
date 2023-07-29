<x-app-layout>
    @livewireStyles
        <link rel="stylesheet" href="{{ asset('/vendors/quill/quill.bubble.css') }}">
        <link rel="stylesheet" href="{{ asset('/vendors/quill/quill.snow.css') }}">
        <style>
            #main-content{
                margin-top: -1rem !important;
                padding-top: 0 !important;
            }
        </style>
    @livewireStyles
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-first">
                <h3>{{ __("Inbox") }}</h3>
                <p class="text-subtitle text-muted">{{ __("WhatsApp Inbox") }}</p>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("inbox.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
                        <div class="col-auto">
                            <div class="input-group">
                                <select name="device" class="form-constrol form-select">
                                    <option value="">-- Pilih Device --</option>
                                    @foreach ($device as $item)
                                        <option value="{{ $item->key }}" {{ $item->key == request()->device ?"selected":"" }}>{{ $item->name }} | {{ $item->number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <select name="read" class="form-constrol form-select">
                                    <option value="">-- Status --</option>
                                    <option value="1" {{ "1" == request()->read ?"selected":"" }}>Telah Dibaca</option>
                                    <option value="0" {{ "0" == request()->read ?"selected":"" }}>Belum Dibaca</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="q" type="text" class="form-control" value="{{ request()->q }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button href="{{ route('inbox.index') }}" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                        
                    </form>
                    <a href="{{ route('inbox.set-all-read') }}" class="btn ms-2 btn-warning mt-auto mb-3" onclick="return confirm('Are you sure to read all Inbox ?');"><i class="bi bi-eye"></i> Set All Read</a>
                    <a href="{{ route('inbox.delete-all') }}" class="btn ms-2 btn-danger mt-auto mb-3" onclick="return confirm('Are you sure to delete all Inbox ?');"><i class="bi bi-trash2-fill"></i> Delete All</a>

                </div>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="container-chatlist">

                                </div>
                                <div class="flex justify-end">
                                    <form action="" class="ms-auto">
                                        <div class="input-group ms-auto" style="width:150px">
                                            <input class="form-control" type="number" name="page" value="{{ request()->page }}">
                                            <button class="btn btn-primary">Go</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="chatbox"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-slot name="script">
        <script src="{{ asset('/vendors/quill/quill.min.js') }}"></script>
        <script>
            let snow
            const submitForm = (e) => {
                document.querySelector("#form-filter").submit()
            }
            $(document).delegate("select[name=device]", 'change', submitForm)
            $(document).delegate("select[name=read]", 'change', submitForm)
            $(document).delegate(".page-link", 'click', (e) => {
                e.preventDefault()
                var href = e.currentTarget.getAttribute('href') 
                console.log(href)
                window.location.href = href.replace('/get-all', "")
            })
            
            $(document).delegate('.btn-add-btn', "click", event  => {
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

            $(document).delegate('.button-message', "click", event  => {
                if(event.target.className.indexOf('btn-remove-btn') >= 0 || event.target.parentElement.className.indexOf('btn-remove-btn') >= 0 ){
                    event.target.closest(".item-button").remove()
                }
            })
            
            $(document).delegate('.button-message', "change", event => {
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

            
            $(document).delegate('#messagetypes', "change", event => {
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
                        document.querySelector(`.button-message`).classList.remove("d-none");
                        document.querySelector(`.footer-message`).classList.remove("d-none");
                        document.querySelector(`.pesan-box`).classList.remove("d-none");
                        document.querySelector(`.media-link`).classList.add("d-none");
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
            function format_text(text){
                return text.replace(/(?:\*)(?:(?!\s))((?:(?!\*|\n).)+)(?:\*)/g,'<b>$1</b>')
                .replace(/(?:_)(?:(?!\s))((?:(?!\n|_).)+)(?:_)/g,'<i>$1</i>')
                .replace(/(?:~)(?:(?!\s))((?:(?!\n|~).)+)(?:~)/g,'<s>$1</s>')
                .replace(/(?:--)(?:(?!\s))((?:(?!\n|--).)+)(?:--)/g,'<u>$1</u>')
                .replace(/(?:```)(?:(?!\s))((?:(?!\n|```).)+)(?:```)/g,'<tt>$1</tt>');
            }
            function updateChatList(){
                $.ajax({url: "/pesan-masuk/get-all", data: '{!! request()->getQueryString() !!}', success: function(result){
                    $('.container-chatlist').html(result)
                }});
            }
            function getChatbox(id){
                $.ajax({url: "/pesan-masuk/get-chat/"+id, success: function(result){
                    updateChatList()
                    $('.chatbox').html(result)
                    setup()
                    setTimeout(() => {
                        document.querySelector(".chat-content").scrollIntoView(false)
                        $((".chat-content")).scrollTop = $((".chat-content")).scrollHeight;
                    }, 100);

                }});
                
            }
            
            function getChatlist(id){
                $.ajax({url: "/pesan-masuk/get-chat-list/"+id, success: function(result){
                    updateChatList()
                    $('#chat-list').html(result)
                    setupFormat()
                    setTimeout(() => {
                        document.querySelector(".chat-content").scrollIntoView(false)
                        $((".chat-content")).scrollTop = $((".chat-content")).scrollHeight;
                    }, 100);
                }});
                
            }
            function setupFormat(){
                const els = document.querySelectorAll('.message');
                
                els.forEach(el => {
                    let text = el.innerText
                    el.innerHTML = format_text( text );
                });
            }

            function setup(){
                const options = {
                    placeholder: 'Tulis pesan yang akan dikirim...',
                    modules: { 
                        toolbar: [
                            ["bold", "italic", "strike"], 
                        ]
                    }, 
                    theme: 'snow'
                };

                snow = new Quill('#text', options);

                snow.keyboard.bindings[13].unshift({
                    key: 13,
                    handler: (range, context) => {
                        $('.form-kirim-chat').submit()
                        return true;
                    }
                });
                snow.on('text-change', function(delta, oldDelta, source) {
                    const contents = snow.getContents();
                    document.querySelector('#text_real').value = JSON.stringify(contents);
                });

                new Choices(document.querySelector('#messagetypes'));
            }
        
            updateChatList()

            $('#sidebar').removeClass("active")

            $('.container-chatlist').delegate('.chat-list', 'click', function(e) {
                e.preventDefault()
                getChatbox($(this).data("id"))
            })

            $(document).delegate('.form-kirim-chat', 'submit', function(e) {
                e.preventDefault()
                var formData = new FormData(e.target);
                $(".submit-button").html("Loading...").attr("disabled", true)
                $.ajax({
                    type:'POST',
                    url:'{{ route('outbox.store') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $(".submit-button").html("Submit").attr("disabled", false)
                        snow.setText("")
                        if($('.chat-number').text() === data.number)
                            getChatlist($('#id-inbox').val())
                    },
                    error: function() {
                        $(".submit-button").html("Submit").attr("disabled", false)
                        snow.setText("")
                        if($('.chat-number').text() === data.number)
                            getChatlist($('#id-inbox').val())
                    }
                });
            })

            socket.on("pesan-baru", function (data) {
                updateChatList()
                if($('.chat-number').text() === data.message.number)
                    getChatlist(data.message.id)
            });
            
        </script>
    </x-slot>
</x-app-layout>