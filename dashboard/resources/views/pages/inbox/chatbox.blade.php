<div class="row">
    <div class="col-12 col-md-6 order-md-1 order-first">
        <input type="hidden" id="id-inbox" value="{{$inbox->id}}">
        <p class="text-primary fw-bold mb-0">Device : {{ $inbox->device_name }} ( {{ $inbox->device_status }} )</p>
    </div>
</div>
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
                            <h6 class="mb-0">{{ $inbox->push_name }}</h6>
                            <span class="text-xs chat-number">{{ $inbox->number }}</span>
                        </div>
                        <a class="btn text-primary" href="{{ route("inbox.show", ['pesan_masuk' => $inbox->id]) }}">
                            <i class="bi bi-arrow-repeat"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body pt-4 bg-grey custom-scroll" style="max-height: 350px;overflow-y:scroll">
                    <div class="chat-content" id="chat-list">
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
                    </div>
                </div>
                <div class="card-footer">
                    <form action="{{ route('outbox.store') }}" method="post" class="form form-horizontal form-kirim-chat" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-body">
                            <div class="row">
                                <input type="hidden" class="form-select" name="id_device" id="devices" value="{{ $inbox->device_id }}">
                                <input type="hidden" name="number" class="form-control" value="{{ $inbox->number }}">
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
                                                    <div id="text" style="height: 150px"></div>
                                                    <p class="muted mt-1"><small>* Tekan tombol kombinasi Super+. (Windows dan Titik) untuk emoji</small></p>
                                                    <textarea name="text" class="form-control d-none" id="text_real" rows="3" readonly></textarea>
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
                                    <a href="{{ url("/inbox") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    <button type="submit" class="btn btn-primary me-1 mb-1 submit-button">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>