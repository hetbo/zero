{{--
    Carrot Modal Component
    Props: role, model
--}}
@props(['role', 'model'])

@once
    <script src="{{ route('carrot-package.assets.js') }}"></script>
    <style>
        /* Modal styles following the working pattern */
        .htmx-modal-container {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0; right: 0; bottom: 0; left: 0;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .htmx-modal-container:target {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .htmx-modal-backdrop {
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
        }
        .htmx-modal-content {
            background-color: #fefefe;
            margin: auto;
            position: relative;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            max-width: 90%;
            width: 100%;
            max-width: 600px;
        }
        .htmx-modal-content.htmx-request {
            opacity: 0.5;
            transition: opacity 300ms ease-in-out;
        }
    </style>
@endonce

<div class="bg-white rounded-lg shadow p-4 border"
     hx-trigger="carrotAttached from:body, carrotDetached from:body"
     hx-get="{{ route('carrots.component-content', [
         'model_type' => urlencode(get_class($model)),
         'model_id' => $model->id,
         'role' => $role
     ]) }}"
     hx-target="#carrot-content-{{ $model->id }}-{{ $role }}"
     hx-swap="innerHTML">

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold capitalize">{{ $role }} Carrots</h3>

        {{-- Modal trigger button using the working pattern --}}
        <a href="#carrot-modal-{{ $model->id }}-{{ $role }}"
           role="button"
           hx-get="{{ route('carrots.modal', [
               'model_type' => urlencode(get_class($model)),
               'model_id' => $model->id,
               'role' => $role
           ]) }}"
           hx-target="#carrot-modal-{{ $model->id }}-{{ $role }} .htmx-modal-content"
           hx-trigger="click once"
           hx-swap="innerHTML"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
            Manage Carrots
        </a>
    </div>

    <div id="carrot-content-{{ $model->id }}-{{ $role }}">
        @include('zero::carrots.partials.component-content', [
            'attachedCarrots' => $model->getCarrotsByRole($role),
            'role' => $role,
            'model' => $model
        ])
    </div>
</div>

{{-- Modal Container following the working pattern --}}
<div id="carrot-modal-{{ $model->id }}-{{ $role }}" class="htmx-modal-container" role="dialog" aria-modal="true">
    <a href="#" class="htmx-modal-backdrop" aria-label="Close modal"></a>
    <div class="htmx-modal-content">
        <p>Loading...</p>
    </div>
</div>