<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>User</h3>
                <p class="text-subtitle text-muted">User Details</p>
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
                                            <th>Nama</th>
                                            <th>
                                                {{ $user->name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <th>
                                                {{ $user->email }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Devices</th>
                                            <th>
                                                <p class="mb-0">Total : {{ $devices->count() }}</p>
                                                <p class="mb-0">Total Connected : {{ $devices->filter(function ($item) {
                                                        return $item->status == "connected";
                                                    })->values()->count() }}</p>
                                                <p class="mb-0">Total Disconnected : {{ $devices->filter(function ($item) {
                                                        return $item->status != "connected";
                                                    })->values()->count() }}</p>
                                                    <hr>
                                                <ol>
                                                    @foreach ($devices as $item)
                                                        <li>{{$item->name}} | {{$item->number}} 
                                                            @if ($item->status == "connected")
                                                                <span class="badge bg-primary">Connected</span>
                                                            @else
                                                                <span class="badge bg-danger">Disconnected</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex h-100">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary ms-auto mt-auto mb-3"><i class="bi bi-arrow-left"></i> Back</a>
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
        })
    </script>
</x-app-layout>
