@once
    {{-- We still serve the JS file automatically. No changes here. --}}
    <script src="{{ route('carrot-package.assets.js') }}" defer></script>
@endonce

{{-- The main component div --}}
<div id="carrot-manager-{{ $role }}-{{ $model->id }}" class="carrot-manager">
    <h4>Carrot Manager (Role: {{ $role }})</h4>

    @if($carrots->isEmpty())
        <p>No carrots assigned to this role.</p>
    @else
        <ul>
            @foreach($carrots as $carrot)
                <li>
                    <span>{{ $carrot->name }}</span>
                    <button
                            hx-post="{{ route('carrot-package.detach') }}"
                            hx-target="#carrot-manager-{{ $role }}-{{ $model->id }}"
                            hx-swap="outerHTML"
                            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                            hx-vals='{
                            "model_type": "{{ addslashes(get_class($model)) }}",
                            "model_id": "{{ $model->id }}",
                            "role": "{{ $role }}",
                            "carrot_id": "{{ $carrot->id }}"
                        }'
                    >&times; Detach</button>
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    <form
            hx-post="{{ route('carrot-package.attach') }}"
            hx-target="#carrot-manager-{{ $role }}-{{ $model->id }}"
            hx-swap="outerHTML"
            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
    >
        @csrf
        <input type="hidden" name="model_type" value="{{ get_class($model) }}">
        <input type="hidden" name="model_id" value="{{ $model->id }}">
        <input type="hidden" name="role" value="{{ $role }}">

        <label>Carrot ID:</label>
        <input type="number" name="carrot_id" required>
        <button type="submit">Add Carrot</button>

        {{--
            THE FINAL PIECE:
            Display any validation errors that the controller sends back.
        --}}
        @if(isset($errors) && $errors->has('carrot_id'))
            <span style="color: red; font-size: 0.9em; display: block; margin-top: 5px;">
                {{ $errors->first('carrot_id') }}
            </span>
        @endif
    </form>
</div>