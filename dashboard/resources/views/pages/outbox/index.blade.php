<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-first">
                <h3>{{ __("Outbox") }}</h3>
                <p class="text-subtitle text-muted">{{ __("WhatsApp Outbox") }}</p>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("outbox.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
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
                                <select name="status" class="form-constrol form-select">
                                    <option value="">-- Status --</option>
                                    <option value="1" {{ "1" == request()->status ?"selected":"" }}>Terkirim</option>
                                    <option value="0" {{ "0" == request()->status ?"selected":"" }}>Gagal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="q" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button href="{{ route('outbox.index') }}" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                    </form>
                    <a href="{{ route('outbox.create') }}" class="btn btn-primary mt-auto mb-3"><i class="bi bi-plus"></i> {{ __("Tambah") }}</a>
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
                                            <th>Nomor WhatsApp</th>
                                            <th>Pesan</th>
                                            <th>Status</th>
                                            <th>Owner</th>
                                            <th>Team</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($outbox as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->device_name }} | {{ $item->device_number }}</td>
                                                <td>{{ $item->number }}</td>
                                                <td>
                                                    @switch($item->id_type)
                                                        @case(2)
                                                            <img src="{{ $item->link }}" width="200px">
                                                            @break
                                                        @case(3)
                                                            <video src="{{ $item->link }}" width="200px" controls>
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                    @php
                                                        $html = $item->text;
                                                        try {
                                                            $html = formatToHtml($item->text);
                                                        } catch (\Throwable $th) {
                                                            //throw $th;
                                                        }
                                                        echo $html;
                                                    @endphp
                                                </td>
                                                @if ($item->status != "0")
                                                    <td>
                                                        <p>{{$item->created_at}}</p>
                                                        <span class="badge bg-primary">Terkirim</span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <p>{{$item->created_at}}</p>
                                                        <span class="badge bg-danger">Gagal</span>
                                                    </td>
                                                @endif
                                                <td>
                                                    {{ $item->owner_name }}
                                                </td>
                                                <td>
                                                    {{ $item->parent_name }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('outbox.resend', ['id' => $item->id]) }}" class="btn btn-sm my-1 btn-primary">
                                                        <i class="bi bi-send"></i>
                                                    </a>
                                                    <a href="{{ route('outbox.show', ['kirim_pesan' => $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $outbox->withQueryString()->links('vendor.pagination.bootstrap-5') }}
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
        document.querySelector("select[name=status]").addEventListener('change', submitForm)
    </script>
</x-app-layout>
