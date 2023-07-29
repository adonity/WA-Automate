@foreach ($inbox as $key => $item)
    <a href="#" class="chat-list text-dark" data-id="{{$item->id}}">
        <p class="fw-bold mb-0">{{ $item->push_name }} | {{ formatNumber(explode('@', $item->number)[0]) }}</p>
        <div>
            {{ $item->text }}
        </div>
        <p class="text-sm">{{ date('H:i d-m-Y', strtotime($item->created_at)); }}</p>
        
        <p class="fw-bold mb-0">Device :</p>
        <p>{{ $item->device_name }} | {{ formatNumber($item->device_number) }}</p>
        <p>Status :  <i class="bi bi-eye-fill {{ $item->read >= 1?"text-secondary":"text-info" }}"></i></p>
        <hr>
    </a>
@endforeach
{{ $inbox->withQueryString()->links('vendor.pagination.bootstrap-5') }}