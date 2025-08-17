{{-- Modal content that gets loaded via HTMX --}}
<div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-hidden"
     hx-trigger="carrotDetached from:body"
     hx-get="{{ route('carrots.modal', [
         'model_type' => $modelType,
         'model_id' => $model->id,
         'role' => $role
     ]) }}"
     hx-target="closest .htmx-modal-content"
     hx-swap="innerHTML">
    <h2 class="text-xl font-semibold">
        Manage {{ ucfirst($role) }} Carrots
    </h2>
    <a href="#" class="text-gray-500 hover:text-gray-700 text-2xl" aria-label="Close modal">
        ×
    </a>
</div>

<div class="p-6 overflow-y-auto max-h-[70vh]">
    <!-- Attached Carrots Section -->
    <div class="mb-8">
        <h3 class="text-lg font-medium mb-4 text-green-700">Currently Attached ({{ $attachedCarrots->count() }})</h3>
        <div id="attached-carrots-list-{{ $model->id }}-{{ $role }}">
            @if($attachedCarrots->count() > 0)
                <div class="space-y-2">
                    @foreach($attachedCarrots as $carrot)
                        <div class="flex justify-between items-center bg-green-50 p-3 rounded border">
                            <div>
                                <span class="font-medium">{{ $carrot->name }}</span>
                                <span class="text-gray-600 text-sm ml-2">({{ $carrot->length }}cm)</span>
                            </div>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                    hx-delete="{{ route('carrots.detach', [
                                            'model_type' => $modelType,
                                            'model_id' => $model->id,
                                            'carrot_id' => $carrot->id,
                                            'role' => $role
                                        ]) }}"
                                    hx-confirm="Remove this carrot?"
                                    hx-swap="none">
                                Remove
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">No carrots currently attached.</p>
            @endif
        </div>
    </div>

    <!-- Available Carrots Section -->
    <div>
        <h3 class="text-lg font-medium mb-4 text-blue-700">Available to Attach</h3>
        <div id="available-carrots-container-{{ $model->id }}-{{ $role }}">
            @include('zero::carrots.partials.available-carrots', [
                'availableCarrots' => $availableCarrots,
                'modelType' => $modelType,
                'modelId' => $model->id,
                'role' => $role,
                'page' => 1,
                'hasMore' => $hasMore
            ])
        </div>
    </div>
</div>