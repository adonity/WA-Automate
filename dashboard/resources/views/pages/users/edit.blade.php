@php
    $_user = \Auth::user();
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Users</h3>
                <p class="text-subtitle text-muted">Edit User </p>
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
                            <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post" class="form form-horizontal">
                                {{ csrf_field() }}
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Nama" value="{{ $user->name }}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                                <x-maz-input-error for="name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email" value="{{ $user->email }}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-phone"></i>
                                                    </div>
                                                </div>
                                                <x-maz-input-error for="email" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-key"></i>
                                                    </div>
                                                </div>
                                                <x-maz-input-error for="password" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Password Confirmation</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" placeholder="Konfirmasi Password">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-key"></i>
                                                    </div>
                                                </div>
                                                <x-maz-input-error for="password_confirmation" />
                                            </div>
                                        </div>
                                        @if ($_user->role == 2)
                                            <input type="hidden" name="role" value="3">
                                            <input type="hidden" name="parent" value="{{ $_user->id }}">
                                        @else
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Role</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <select name="role" class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}">
                                                                <option value="2" {{$user->role == 2?"selected":""}}>Super Admin</option>
                                                                <option value="3" {{$user->role == 3?"selected":""}}>Team</option>
                                                            </select>
                                                            <x-maz-input-error for="role" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container-fluid super-admin {{ $user->role == 3?"":"d-none" }}">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Super Admin</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <select name="parent" class="form-select {{ $errors->has('parent') ? 'is-invalid' : '' }}">
                                                                @foreach ($users as $item)
                                                                    <option value="{{$item->id}}" {{$item->id == $user->parent?"selected":""}}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <x-maz-input-error for="parent" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-12 d-flex justify-content-end">
                                            <a href="{{ url("/users") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
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
            
            document.querySelector('select[name="role"]').addEventListener("change", event => {
                const value = event.target.value
                switch (value) {
                    case "2":
                        document.querySelector(`.super-admin`).classList.add("d-none");
                        break
                    case "3":
                        document.querySelector(`.super-admin`).classList.remove("d-none");
                        break
                }
            })
        })
    </script>
</x-app-layout>
