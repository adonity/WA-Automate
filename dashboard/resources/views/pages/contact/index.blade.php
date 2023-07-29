<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Kontak</h3>
                <p class="text-subtitle text-muted">List Kontak </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("kontak.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="q" type="text" class="form-control" value="{{ request()->q }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                    </form>
                    <form method="POST" enctype="multipart/form-data" action="{{ route('kontak.import') }}" class="mt-auto ms-2">
                        {{csrf_field()}}
                        <input type="file" name="fileimport" class="d-none" id="fileimport" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="javascript:this.form.submit();">
                        <button type="button" class="btn btn-primary mb-3" id="btn-import"><i class="bi bi-file-earmark-excel"></i> Import</button>
                    </form>
                    <a href="{{ route('kontak.create') }}" class="btn ms-2 btn-primary mt-auto mb-3"><i class="bi bi-plus"></i> Tambah</a>
                    <a href="{{ route('kontak.delete-all') }}" class="btn ms-2 btn-danger mt-auto mb-3" onclick="return confirm('Are you sure to delete all contact ?');"><i class="bi bi-trash2-fill"></i> Delete All</a>
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
                                            <th>Nama</th>
                                            <th>No WhatsApp</th>
                                            <th>Info 1</th>
                                            <th>Info 2</th>
                                            <th>Info 3</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contact as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->number }}</td>
                                                <td>{{ $item->info1 }}</td>
                                                <td>{{ $item->info2 }}</td>
                                                <td>{{ $item->info3 }}</td>
                                                <td>
                                                    <a href="{{ route('kontak.edit', ['kontak' => $item->id]) }}" class="btn btn-sm my-1 btn-warning">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </a>
                                                    <form class="d-inline" action="{{ route('kontak.destroy', ['kontak' => $item->id]) }}" method="post">
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
                                
                                {{ $contact->links('vendor.pagination.bootstrap-5') }}
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
