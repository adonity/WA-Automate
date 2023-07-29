<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Users</h3>
                <p class="text-subtitle text-muted">List User </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
                <div class="d-flex h-100">
                    @if (\Auth::user()->role == 1)
                        <form id="form-filter" action="{{ route("users.index") }}" class="row g-2 ms-auto mt-auto mb-3">
                            <div class="col-auto">
                                <div class="input-group">
                                    <select name="super" class="form-select">
                                        <option value="">-- Pilih Super Admin --</option>
                                        @foreach ($user_super as $item)
                                            <option value="{{ $item->id }}" {{ request()->super == $item->id?"selected":"" }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    @endif
                    <a href="{{ route('users.create') }}" class="btn btn-primary {{ \Auth::user()->role == 1?"ms-3":"ms-auto" }} mt-auto mb-3"><i class="bi bi-plus"></i> Tambah</a>
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
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Team</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ getRoleText($item->role) }}</td>
                                                <td>{{ getParent($item->parent)->name ?? "" }}</td>
                                                <td>
                                                    <a href="{{ route('users.show', ['user' => $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', ['user' => $item->id]) }}" class="btn btn-sm my-1 btn-warning">
                                                        <i class="bi bi-pen-fill"></i>
                                                    </a>
                                                    <form class="d-inline" action="{{ route('users.show', ['user' => $item->id]) }}" method="post" onsubmit="return confirm('Apakah anda yakin akan menghapus akun ini ?');">
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
            const submitForm = (e) => {
                document.querySelector("#form-filter").submit()
            }
            document.querySelector("select[name=super]").addEventListener('change', submitForm)
        })
    </script>
</x-app-layout>
