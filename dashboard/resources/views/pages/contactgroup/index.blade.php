<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Contact Group</h3>
                <p class="text-subtitle text-muted">List Contact Group WhatsApp </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
                <div class="d-flex h-100">
                    <a href="{{ route('contactgroup.create') }}" class="btn btn-primary ms-auto mt-auto mb-3"><i class="bi bi-plus"></i> Tambah</a>
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
                                            <th>Description</th>
                                            <th>Jumlah Kontak</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contactgroup as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    {{$item->count_contact}}
                                                </td>
                                                <td>
                                                    <a href="{{ route('contactgroup.show', ['grup_kontak' => $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    <a href="{{ route('contactgroup.edit', ['grup_kontak' => $item->id]) }}" class="btn btn-sm my-1 btn-warning">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </a>
                                                    <form class="d-inline" action="{{ route('contactgroup.show', ['grup_kontak' => $item->id]) }}" method="post">
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
                                {{ $contactgroup->links('vendor.pagination.bootstrap-5') }}
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
