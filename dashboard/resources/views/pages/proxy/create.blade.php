<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Proxy</h3>
                <p class="text-subtitle text-muted">Tambah Proxy</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
            </div>
        </div>
    </x-slot>
    @if ($errors->count() > 0)
        <div class="alert alert-danger">
            <h4 class="alert-heading">{{ __("Error") }}</h4>
            <ul>
                @foreach ($errors->messages() as $key => $item)
                    @foreach ($item as $child)
                        <li>{{ $child }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('proxy.store') }}" method="post" class="form form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Host</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="host" class="form-control {{ $errors->has('host') ? 'is-invalid' : '' }}" placeholder="Host">
                                                </div>
                                                <x-maz-input-error for="host" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Port</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="port" class="form-control {{ $errors->has('port') ? 'is-invalid' : '' }}" placeholder="Port">
                                                </div>
                                                <x-maz-input-error for="port" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Username</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="Username">
                                                </div>
                                                <x-maz-input-error for="username" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password">
                                                </div>
                                                <x-maz-input-error for="password" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Expired</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="expired" class="form-control {{ $errors->has('expired') ? 'is-invalid' : '' }}" placeholder="Expired">
                                                </div>
                                                <x-maz-input-error for="expired" />
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <a href="{{ route("proxy.index") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
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
