<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Devices</h3>
                <p class="text-subtitle text-muted">Edit Device WhatsApp </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('blast.update', ['blast' => $blastgroup->id]) }}" method="post" class="form form-horizontal">
                                @csrf
                                @method('PUT')
		                        <input type="hidden" name="id" value="{{ $blastgroup->id }}"> <br/>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama Blast</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nama Blast" value="{{$blastgroup->name}}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                                <x-maz-input-error for="name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Grup Kontak</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    {{json_encode($blastgroup)}}
                                                    <select class="form-select" name="id_contactgroup" id="contactgroup">
                                                        <option value="">{{__("Grup Kontak")}}</option>
                                                        @foreach ($contactgroup as $item)
                                                            <option value="{{ $item->id }}" {{ ($blastgroup->id_contactgroup == $item->id?"selected":"")}}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
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
                                            <label>Tipe Pesan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select class="form-select" name="id_type" id="messagetypes">
                                                        <option value="">{{__("Tipe Pesan")}}</option>
                                                        @foreach ($messagetypes as $item)
                                                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
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
    <script>
        document.addEventListener('livewire:load', function () {
        })
    </script>
</x-app-layout>
