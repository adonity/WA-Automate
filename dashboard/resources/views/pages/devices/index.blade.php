<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Devices</h3>
                <p class="text-subtitle text-muted">List Device WhatsApp </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
                <div class="d-flex h-100">
                    {{-- <form id="form-filter" action="{{ route("devices.index") }}" class="row g-1 ms-auto mt-auto mb-3">
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="q" type="text" class="form-control" value="{{ request()->q }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                    </form> --}}

                    <a href="{{ route('devices.create') }}" class="btn btn-primary ms-auto mt-auto mb-3"><i class="bi bi-plus"></i> Tambah</a>
                    <form method="POST" enctype="multipart/form-data" action="{{ route('devices.import') }}" class="mt-auto ms-2">
                        {{csrf_field()}}
                        <input type="file" name="fileimport" class="d-none" id="fileimport" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="javascript:this.form.submit();">
                        <button type="button" class="btn btn-primary mb-3" id="btn-import"><i class="bi bi-files"></i> Import</button>
                    </form>
                    <a href="{{ route('devices.delete-all') }}" class="btn ms-2 btn-danger mt-auto mb-3" onclick="return confirm('Are you sure to delete all device ?');"><i class="bi bi-trash3"></i> Delete All</a>
                    <a href="{{ route('devices.delete-disconnected') }}" class="btn ms-2 btn-danger mt-auto mb-3" onclick="return confirm('Are you sure to delete all disconnected device ?');"><i class="bi bi-wifi-off"></i> Delete Disconnected Device</a>
                </div>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <p class="mb-0"><b>Total Device : {{ $total }}</b></p>
                            <p class="mb-0"><b>Connected Device : {{ $connected->count() }}</b></p>
                            <p class="mb-0"><b>Disconnected Device : {{ $disconnected->count() }}</b></p>
                            @php
                                $categories = $devices->groupBy('category')->all();
                            @endphp
                            <hr>
                            <div class="container-fluid px-0">
                                <div class="row">
                                    @foreach ($categories as $category => $device)
                                    @php
                                        $countDevice = $device->count();
                                        $conntectedDevice = $device->filter(function ($value, $key) { return $value->status == "connected"; })->count();
                                        $diconnectedDevice = $countDevice - $conntectedDevice;
                                    @endphp
                                        <div class="col-md-3 col-6 my-3">
                                            <p class="mb-0"><b>Category : {{ $category }}</b></p>
                                            <p class="mb-0"><b>Total Device : {{ $countDevice }}</b></p>
                                            <p class="mb-0"><b>Connected Device : {{ $conntectedDevice }}</b></p>
                                            <p class="mb-0"><b>Disconnected Device : {{ $diconnectedDevice }}</b></p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Nomor WhatsApp</th>
                                            <th>Kategori</th>
                                            <th>Proxy</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Owner</th>
                                            <th>Team</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($devices as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->number }}</td>
                                                <td>{{ $item->category }}</td>
                                                <td>{{ $item->proxy }}</td>
                                                <td>{{ $item->description }}</td>
                                                @if ($item->status == "connected")
                                                    <td>
                                                        <span class="badge bg-primary">Connected</span>
                                                    </td>
                                                @else
                                                    <td>
                                                        <span class="badge bg-danger">Disconnected</span>
                                                    </td>
                                                @endif
                                                <td>{{ $item->owner_name }}</td>
                                                <td>{{ $item->parent_name }}</td>
                                                <td>
                                                    @if ($item->status != "connected")
                                                        <a href="{{ url("/devices/scan/".$item->id) }}" class="btn btn-sm my-1 btn-primary">
                                                            <i class="bi bi-qr-code"></i>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('devices.show', ['device' => $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    <a href="{{ route('devices.edit', ['device' => $item->id]) }}" class="btn btn-sm my-1 btn-warning">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </a>
                                                    <form class="d-inline" action="{{ route('devices.destroy', ['device' => $item->id]) }}" method="post">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('livewire:load', function () {
            document.querySelector("#btn-import").addEventListener("click", (e) => {
                document.querySelector("#fileimport").click()
            })
        })
    </script>
</x-app-layout>
