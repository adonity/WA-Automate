<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Proxy</h3>
                <p class="text-subtitle text-muted">Edit Proxy</p>
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
                            <form action="{{ route('proxy.update', ['proxy' => $proxy->id]) }}" method="post" class="form form-horizontal">
                                @csrf
                                @method('PUT')
		                        <input type="hidden" name="id" value="{{ $proxy->id }}"> <br/>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Host</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="host" class="form-control" placeholder="Host" value="{{ $proxy->host }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Port</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="port" class="form-control" placeholder="Port" value="{{ $proxy->port }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Username</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="username" class="form-control" placeholder="Username" value="{{ $proxy->username }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="password" class="form-control" placeholder="Username" value="{{ $proxy->password }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Expired</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="expired" class="form-control" placeholder="Username" value="{{ $proxy->expired }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <a href="{{ url("/proxy") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
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
