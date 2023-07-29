<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-first">
                <h3>{{ __("Balas Otomatis") }}</h3>
                <p class="text-subtitle text-muted">{{ __("Trigger WhatsApp Balas Otomatis") }}</p>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("autoreply.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
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
                                <input name="q" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button href="{{ route('autoreply.index') }}" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                    </form>
                    <a href="{{ route('autoreply.create') }}" class="btn btn-primary mt-auto mb-3"><i class="bi bi-plus"></i> {{ __("Tambah") }}</a>
                </div>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Device</th>
                                            <th>Trigger</th>
                                            <th>Reply</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($autoreply as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                @if ($item->id_device == null)
                                                    <td>All Device</td>
                                                @else
                                                    <td>{{ $item->device_name }} | {{ $item->device_number }}</td>
                                                @endif
                                                <td>{{ $item->trigger }}</td>
                                                <td>
                                                   <table>
                                                        <tr>
                                                            <th>Jenis Pesan</th>
                                                            <th>
                                                                {{ $item->type->label }}
                                                            </th>
                                                        </tr>
                                                        @if ($item->id_type > 1)
                                                            <tr>
                                                                <th>Pesan Tambahan</th>
                                                                <th>
                                                                    @switch($item->id_type)
                                                                        @case(2)
                                                                            <img src="{{ $item->link }}" class="w-100" style="max-width: 600px">
                                                                            @break
                                                                        @case(3)
                                                                            <video src="{{ $item->link }}" class="w-100" style="max-width: 600px" controls>
                                                                            @break
                                                                        @case(4)
                                                                            <table class="w-100">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Teks</th>
                                                                                        <th>Jenis</th>
                                                                                        <th>Content</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @if ($item->buttons)
                                                                                    @foreach (json_decode($item->buttons) as $_item)
                                                                                        @php
                                                                                            if(isset($_item->quickReplyButton)){
                                                                                                $tipe = "Quick Reply";
                                                                                                $teks = $_item->quickReplyButton->displayText;
                                                                                                $content = "";
                                                                                            }elseif(isset($_item->urlButton)){
                                                                                                $tipe = "Url";
                                                                                                $teks = $_item->urlButton->displayText;
                                                                                                $content = $_item->urlButton->url;
                                                                                            }elseif(isset($_item->callButton)){
                                                                                                $tipe = "Call";
                                                                                                $teks = $_item->callButton->displayText;
                                                                                                $content = $_item->callButton->phoneNumber;
                                                                                            }
                                                                                        @endphp
                                                                                        <tr>
                                                                                            <td>{{ $_item->index }}</td>
                                                                                            <td>{{ $teks }}</td>
                                                                                            <td>{{ $tipe }}</td>
                                                                                            <td>{{ $content }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                    @endif
                                                                                </tbody>
                                                                            </table>
                                                                            @break
                                                                        @default
                                                                            
                                                                    @endswitch
                                                                </th>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <th>Pesan</th>
                                                            <th>
                                                                {!! formatToHtml($item->text) !!}
                                                            </th>
                                                        </tr>
                                                        @if ($item->id_type == 4)
                                                            <tr>
                                                                <th>Footer</th>
                                                                <th>
                                                                    {{ ($item->footer) }}
                                                                </th>
                                                            </tr>
                                                        @endif
                                                   </table>
                                                </td>
                                                @if ($item->status != "0")
                                                    <td>
                                                        <span class="badge bg-primary">Terkirim</span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="badge bg-danger">Gagal</span>
                                                    </td>
                                                @endif
                                                <td>
                                                    @if ($item->status == "0")
                                                        <a href="{{ route('autoreply.resend', ['id' => $item->id]) }}" class="btn btn-sm my-1 btn-primary">
                                                            <i class="bi bi-send"></i>
                                                        </a>
                                                    @endif
                                                    {{-- <a href="{{ route('autoreply.show', ['balas_otomati' => $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a> --}}
                                                    <form class="d-inline" action="{{ route('autoreply.show', ['balas_otomati' => $item->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm my-1 btn-danger">
                                                            <i class="bi bi-trash2-fill"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $autoreply->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('livewire:load', function () {
        })
        const submitForm = (e) => {
            document.querySelector("#form-filter").submit()
        }
        document.querySelector("select[name=device]").addEventListener('change', submitForm)
    </script>
</x-app-layout>
