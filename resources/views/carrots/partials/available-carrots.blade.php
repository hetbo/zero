@if($availableCarrots->count() > 0)
    <div class="space-y-2">
        @foreach($availableCarrots as $carrot)
            <div class="flex justify-between items-center bg-blue-50 p-3 rounded border">
                <div>
                    <span class="font-medium">{{ $carrot->name }}</span>
                    <span class="text-gray-600 text-sm ml-2">({{ $carrot->length }}cm)</span>
                </div>
                <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors"
                        hx-post="{{ route('carrots.attach', [
                            'model_type' => $modelType,
                            'model_id' => $modelId
                        ]) }}"
                        hx-vals='{"carrot_id": {{ $carrot->id }}, "role": "{{ $role }}"}'
                        hx-confirm="Attach this carrot?"
                        hx-target="closest .htmx-modal-content"
                        hx-swap="innerHTML">
                    Attach
                </button>
            </div>
        @endforeach
    </div>

    @if($hasMore)
        <div class="mt-4 text-center">
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded transition-colors"
                    hx-get="{{ route('carrots.load-more', [
                        'model_type' => $modelType,
                        'model_id' => $modelId,
                        'role' => $role
                    ]) }}?page={{ $page + 1 }}"
                    hx-target="#available-carrots-container-{{ $modelId }}-{{ $role }}"
                    hx-swap="beforeend">
                Load More
            </button>
        </div>
    @endif
@else
    <p class="text-gray-500 italic">No more carrots available to attach.</p>
@endif