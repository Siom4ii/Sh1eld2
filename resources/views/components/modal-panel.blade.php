@props(['id', 'title' => '', 'max' => 'max-w-lg'])

<div id="{{ $id }}" data-modal
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div data-modal-panel class="card w-full {{ $max }} max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h3 class="text-base font-semibold text-slate-800">{{ $title }}</h3>
            <button type="button" data-modal-close class="rounded-lg p-1 text-slate-400 hover:bg-slate-100">
                <x-heroicon-o-x-mark class="h-5 w-5" />
            </button>
        </div>
        <div class="px-6 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
