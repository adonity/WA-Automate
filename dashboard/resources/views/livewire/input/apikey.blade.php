<div>
    <div class="input-group mb-3">
        <input type="{{$show?'text':'password'}}" class="form-control" value="{{ $key }}" readonly>
        <button class="btn btn-{{$show?'primary':'secondary'}}" wire:click="togglefield">
            <i class="bi bi-eye"></i>
        </button>
    </div>
</div>
