<div class="carrot-manager" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
    <h4>Carrot Manager (Role: {{ $role }})</h4>

    {{-- List Existing Carrots --}}
    @if($carrots->isEmpty())
        <p>No carrots assigned to this role.</p>
    @else
        <ul style="list-style: none; padding: 0;">
            @foreach($carrots as $carrot)
                <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                    <span>{{ $carrot->name }} ({{ $carrot->length }}cm)</span>

                    {{-- Detach Form --}}
                    <form action="{{ route('carrots-package.detach') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="model_type" value="{{ get_class($model) }}">
                        <input type="hidden" name="model_id" value="{{ $model->id }}">
                        <input type="hidden" name="role" value="{{ $role }}">
                        <input type="hidden" name="carrot_id" value="{{ $carrot->id }}">
                        <button type="submit" style="color: red; background: none; border: none; cursor: pointer;">&times; Detach</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

    <hr>

    {{-- Add Existing Carrot Form --}}
    <div>
        <h5>Add Existing Carrot</h5>
        <form action="{{ route('carrots-package.attach') }}" method="POST">
            @csrf
            <input type="hidden" name="model_type" value="{{ get_class($model) }}">
            <input type="hidden" name="model_id" value="{{ $model->id }}">
            <input type="hidden" name="role" value="{{ $role }}">

            <label for="carrot_id">Carrot ID:</label>
            <input type="number" name="carrot_id" id="carrot_id" required>
            <button type="submit">Add Carrot</button>
        </form>
    </div>
</div>