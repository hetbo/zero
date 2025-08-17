{{-- Partial for just the component content that gets updated --}}
@if($attachedCarrots->count() > 0)
    <div class="space-y-2">
        @foreach($attachedCarrots as $carrot)
            <div class="flex justify-between items-center bg-gray-50 p-3 rounded">
                <div>
                    <span class="font-medium">{{ $carrot->name }}</span>
                    <span class="text-gray-600 text-sm ml-2">({{ $carrot->length }}cm)</span>
                </div>
                <button class="text-red-500 hover:text-red-700 transition-colors"
                        hx-delete="{{ route('carrots.detach', [
                            'model_type' => urlencode(get_class($model)),
                            'model_id' => $model->id,
                            'carrot_id' => $carrot->id,
                            'role' => $role
                        ]) }}"
                        hx-confirm="Remove this carrot?"
                        hx-swap="none"
                        title="Remove carrot">
                    ✕
                </button>
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-500 italic">No carrots attached with role "{{ $role }}"</p>
@endif