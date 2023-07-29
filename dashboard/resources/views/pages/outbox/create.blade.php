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
                <h3>{{ __("Kirim Pesan") }}</h3>
                <p class="text-subtitle text-muted">{{ __("Kirim Pesan Baru") }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
            </div>
        </div>
    </x-slot>

    @if ($errors->count() > 0)
        <div class="alert alert-danger">
            <h4 class="alert-heading">{{ __("Error") }}</h4>
            <ul>
                @foreach ($errors->messages() as $key => $item)
                    @foreach ($item as $child)
                        <li>{{ $child }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('outbox.store') }}" method="post" class="form form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Device</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select class="form-select" name="id_device" id="devices">
                                                        <option value="">{{__("Pilih Device")}}</option>
                                                        @foreach ($devices as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }} | {{ $item->number }} ({{ $item->status }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nomor WhatsApp Tujuan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group d-flex has-icon-left">
                                                <div class="position-relative w-100">
                                                    <input type="number" name="number" class="form-control" placeholder="Nomor WhatsApp">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-phone"></i>
                                                    </div>
                                                </div>
                                                <button class="btn btn-light ms-2 text-nowrap" data-bs-target="#modal-contact" data-bs-toggle="modal" type="button" id="btn-contact">
                                                    <i class="fa bi-person-lines-fill"></i> Contact
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tipe Pesan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select class="form-select" name="id_type" id="messagetypes" required>
                                                        <option value="">{{__("Tipe Pesan")}}</option>
                                                        @foreach ($messagetypes as $item)
                                                            <option value="{{ $item->id }}" {{ $item->id == 1?"selected":"" }}>{{ $item->label }}</option>
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
                                                    {{-- <div class="form-group has-icon-left">
                                                        <div class="position-relative">
                                                            <input type="text" name="link" class="form-control" placeholder="Link Gambar atau Video">
                                                            <div class="form-control-icon">
                                                                <i class="bi bi-link-45deg"></i>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <div class="position-relative">
                                                            <input type="file" name="file" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container-fluid pesan-box">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>{{__("Pesan")}}</label>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="position-relative">
                                                            <div id="text" style="height: 200px"></div>
                                                            <p class="muted mt-1"><small>* Tekan tombol kombinasi Super+. (Windows dan Titik) untuk emoji</small></p>
                                                            <textarea name="text" class="form-control d-none" id="text_real" rows="5" readonly></textarea>
                                                        </div>
                                                    </div>
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
        </div>
    </section>
    <div class="modal fade text-left" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Contact's</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Nama</td>
                                <td>Nomor</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $key => $contact)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->number }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary btn-select-number" data-number="{{ $contact->number }}">{{ __("Select") }}</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
        <script src="{{ asset('/vendors/quill/quill.min.js') }}"></script>
    @livewireScripts
    <script>
        document.addEventListener('livewire:load', function () {
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

            var contactModal = new bootstrap.Modal(document.querySelector('#modal-contact'))

            document.querySelectorAll(".btn-select-number").forEach(item => {
                item.addEventListener("click", function(e) {
                    var number = this.getAttribute("data-number")
                    document.querySelector("input[name=number]").value = number
                    contactModal.hide()
                })
            })


            
            let snow = new Quill('#text', options);

            snow.on('text-change', function(delta, oldDelta, source) {
                const contents = snow.getContents();
                document.querySelector('#text_real').value = JSON.stringify(contents);
            });

            const choices = new Choices(document.querySelector('#devices'));
            const choices2 = new Choices(document.querySelector('#messagetypes'));

        })
    </script>
</x-app-layout>
