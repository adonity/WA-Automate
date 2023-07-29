<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>IP Whitelist</h3>
                <p class="text-subtitle text-muted">List IP Whitelist </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("ipwhitelist.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="q" type="text" class="form-control" value="{{ request()->q }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                    </form>
                    <a href="{{ route('ipwhitelist.create') }}" class="btn ms-2 btn-primary mt-auto mb-3"><i class="bi bi-plus"></i> Tambah</a>
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
                                            <th>IP Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ipwhitelist as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->ip }}</td>
                                                <td>
                                                    <a href="{{ route('ipwhitelist.edit', ['ipwhitelist' => $item->id]) }}" class="btn btn-sm my-1 btn-warning">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </a>
                                                    <form class="d-inline" action="{{ route('ipwhitelist.destroy', ['ipwhitelist' => $item->id]) }}" method="post">
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
                                
                                {{ $ipwhitelist->links('vendor.pagination.bootstrap-5') }}
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
    </script>
</x-app-layout>
