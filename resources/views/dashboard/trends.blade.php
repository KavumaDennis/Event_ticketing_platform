@extends('layouts.dashboard')

@section('title','Cards')

@section('content')
<div class="max-w-5xl ">

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
    <div class="p-3 mb-6 bg-green-500 text-white rounded">
        {{ session('success') }}
    </div>
    @endif


    {{-- LIST USER TRENDS --}}
    <h2 class="text-2xl mb-3">Your Trends</h2>
    <a href="{{ route('trends.create') }}" id="editProfileBtn" class="text-xs px-10 text-black/90 font-medium font-mono bg-orange-400/70 text-center rounded-3xl p-2 w-full">
        Create Trend
    </a>
    <div class="grid grid-cols-3 mt-5 gap-10">
        @foreach($trends as $trend)
        <div class="col-span-1 h-full flex flex-col gap-2">
            <div class="overflow-hidden w-full h-[100px]">
                <img src="{{ $trend->image ? asset('storage/'.$trend->image) : asset('img1.jpg') }}" class='h-full w-full rounded-3xl object-cover opacity-80' alt="{{ $trend->title }}" />
            </div>

            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <!-- EDIT BUTTON -->
                    <button onclick="openEditModal({ 
            id: {{ $trend->id }}, 
            title: '{{ addslashes($trend->title) }}', 
            body: '{{ addslashes($trend->body ?? '') }}' 
        })" class="px-2 py-1 text-xs bg-yellow-500 text-black rounded-2xl font-mono hover:bg-yellow-600" title="Edit Trend">
                        Edit
                    </button>

                    <!-- DELETE BUTTON -->
                    <form action="{{ route('trends.destroy', $trend->id) }}" method="POST" onsubmit="return confirm('Delete this trend?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-2 py-1 text-xs bg-red-500 text-black rounded-2xl font-mono hover:bg-red-600" title="Delete Trend">
                            Delete
                        </button>
                    </form>
                </div>


                <a href="{{ route('trends.show', $trend->id) }}" class="w-fit bg-orange-400/70 border border-green-400/15 p-0.5 rounded-3xl flex items-center gap-1 cursor-pointer">
                    <p class="size-7 flex items-center justify-center rounded-[50%] text-orange-400/80 bg-black/95 border border-green-400/15">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </p>
                    <span class="text-xs font-mono text-black/90 font-medium mr-1">Read Post</span>
                </a>
            </div>

            <div class="p-3 h-[100px] bg-green-400/10 rounded-3xl">
                <h2 class="text-md font-semibold text-orange-400/70 mb-2">{{ $trend->title }}</h2>
                <p class="text-sm font-light font-mono text-white/70 line-clamp-3">
                    {{ Str::limit($trend->body, 100) }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- EDIT MODAL --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/70 bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-green-400 w-full max-w-lg p-6 rounded-xl shadow-xl relative">

        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-600">✖</button>

        <h2 class="text-xl font-bold mb-4">Edit Trend</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block font-semibold">Title</label>
                <input type="text" id="edit_title" name="title" class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-3">
                <label class="block font-semibold">Body</label>
                <textarea id="edit_body" name="body" class="w-full border p-2 rounded"></textarea>
            </div>

            <div class="mb-3">
                <label class="block font-semibold">Replace Image</label>
                <input type="file" name="image" class="w-full border p-2 rounded">
            </div>

            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Update Trend
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    window.openEditModal = function (trend) {
        document.getElementById("edit_title").value = trend.title ?? "";
        document.getElementById("edit_body").value = trend.body ?? "";

        // Set correct action URL
        document.getElementById("editForm").action = "/trends/" + trend.id;

        document.getElementById("editModal").classList.remove("hidden");
    }

    window.closeEditModal = function () {
        document.getElementById("editModal").classList.add("hidden");
    }
</script>
@endpush


@endsection


