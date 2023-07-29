<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Contact Group</h3>
                <p class="text-subtitle text-muted">Detail Contact Group</p>
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
                                    <tbody>
                                        <tr>
                                            <th>ID</th>
                                            <th>
                                                {{ $contactgroup->id }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <th>
                                                {{ $contactgroup->name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <th>
                                                {{ $contactgroup->description }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Kontak</th>
                                            <th>
                                                {{$contactgroup->count_contact}}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="d-flex">
            <h3 class="mb-3">Contact List</h3> 
            <div class="ms-auto">
                <div>
                    <form action="{{ route('contact.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control d-none" onchange="this.form.submit()">
                    </form>
                    <button class="btn btn-info btn-import">
                        Import Kontak
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>
                                                No
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Number
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contactlist as $key => $item)
                                            <tr>
                                                <td>
                                                    {{$key + 1}}
                                                </td>
                                                <td>
                                                    {{$item->name}}
                                                </td>
                                                <td>
                                                    {{$item->number}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $contactlist->links('vendor.pagination.bootstrap-5') }}
                                <div class="d-flex h-100">
                                    <a href="{{ route('contactgroup.index') }}" class="btn btn-secondary ms-auto mt-auto mb-3"><i class="bi bi-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('livewire:load', function () {
            const btnImport = document.querySelector('.btn-import')
            btnImport.addEventListener('click', (e)=>{
                const inputfile = document.querySelector('input[type=file]')
                inputfile.click()
            })
        })
    </script>
</x-app-layout>
