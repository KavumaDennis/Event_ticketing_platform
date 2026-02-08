@extends('layouts.dashboard')

@section('title','Overview')

@section('content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-8">
        <h2 class="text-2xl text-white/70 mb-3">Manage Your Events</h2>
        <div class="flex items-center gap-3 w-fit mb-4">
            <a href="{{ route('events.create') }}" class="text-xs px-10 text-black/90 font-medium font-mono bg-orange-400/70 text-center rounded-3xl p-2 w-full">
                Create an Event
            </a>
        </div>

        {{-- Organizer Events --}}
        @if($organizer && $organizer->events->count())
        <div class="mt-4 bg-green-400/10 p-3 rounded-3xl">
            <h4 class="text-orange-400/70 text-sm font-semibold mb-2">Events you organize</h4>



            <div class="grid grid-cols-2 gap-5">
                @foreach($organizer->events as $ev)
                <div class="flex items-center gap-3 p-2 bg-green-400/10 rounded-xl mb-2">
                    <img src="{{ asset('storage/'.$ev->event_image) }}" class="w-20 h-14 object-cover rounded-xl">
                    <div class="w-full">
                        <p class="text-sm font-medium text-white/80">{{ $ev->event_name }}</p>
                        <p class="text-xs text-white/50">{{ $organizer->business_name }}</p>

                        <div class="mt-5 flex justify-between items-center w-full">
                            <div class="flex items-center gap-2">
                                {{-- EDIT BUTTON --}}
                                <button onclick="openEditModal({{ json_encode($ev) }})" class="px-2 py-1 text-xs bg-yellow-500 text-black rounded-2xl font-mono hover:bg-yellow-600" title="Edit Event">
                                    Edit
                                </button>


                                {{-- DELETE BUTTON --}}
                                <form action="{{ route('events.destroy', $ev->id) }}" method="POST" onsubmit="return confirm('Delete this event?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 text-xs bg-red-500 text-black rounded-2xl font-mono hover:bg-red-600" title="Delete Event">
                                        Delete
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('event.show', $ev->id) }}" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">
                                Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Right sidebar --}}
    <aside class="col-span-4 relative">
        <div class="absolute top-0 right-0 w-full h-full flex flex-col justify-between gap-5">
            {{-- Saved Events --}}
            <div class="p-3 pr-1 border border-green-400/5 bg-green-400/10  rounded-2xl ">
                <h4 class="font-semibold text-orange-400/70 text-sm mb-2">Saved events</h4>
                <div class="h-[300px] overflow-hidden overflow-y-scroll flex flex-col gap-3 rounded-2xl pr-2">
                    @foreach($saved as $s)
                    @php $ev = $s->event; @endphp
                    <div class="flex bg-green-400/10 rounded-2xl p-2 relative">
                        <div class="pb-5">
                            <div class="font-medium text-white/80 text-sm">{{ $ev->event_name }}</div>
                            <div class="text-white/60 text-xs font-mono">{{ $ev->organizer?->business_name ?? 'Unknown' }}</div>
                        </div>
                        <div class="flex items-center p-0.5 w-fit bg-orange-400/70 gap-1 rounded-3xl absolute bottom-2 right-2">
                            <a href="{{ route('event.show', $ev->id) }}" class='flex gap-1 items-center'>
                                <span class='p-2 rounded-[50%] bg-black/95 text-orange-400/80'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-icon lucide-ticket">
                                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                        <path d="M13 5v2" />
                                        <path d="M13 17v2" />
                                        <path d="M13 11v2" />
                                    </svg>
                                </span>
                                <span class='text-xs pr-2 font-semibold text-black/90'>Get Tickets</span>
                            </a>
                        </div>
                    </div>

                    @endforeach
                </div>
            </div>

            {{-- Organizer Profile --}}
            @if($organizer)
            <div class="p-3 bg-green-400/10 border border-green-400/5 rounded-2xl mt-3">
                <h3 class="text-orange-400/70 text-sm font-semibold">Your Organizer Profile</h3>
                <div class="flex items-center gap-3 mt-2">
                    <img src="{{ $organizer->organizer_image ? asset('storage/'.$organizer->organizer_image) : asset('default.jpg') }}" class="w-12 h-12 rounded-full object-cover">
                    <div class="w-full">
                        <p class="text-white/80 font-medium">{{ $organizer->business_name }}</p>
                        <div class="flex justify-between items-center">
                            <p class="text-xs text-white/50">{{ $organizer->business_email }}</p>
                            <a href="{{ route('user.dashboard.organizerProfile', $organizer->id) }}" class="text-xs text-black/90 font-medium font-mono bg-orange-400/70 rounded-3xl px-2 py-1">Details</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </aside>
</div>

{{-- EDIT EVENT MODAL --}}
<div id="editModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="w-[600px] max-h-[600px] overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-lg p-4 shadow-xl relative">
        <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-600 text-lg font-bold">✖</button>
        <h2 class="text-xl font-bold mb-4 text-white">Edit Event</h2>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            {{-- Event Name --}}
            <div class="mb-2">
                <label class="text-white/60 font-medium ml-1 text-sm">Event Name</label>
                <input type="text" id="edit_name" name="event_name" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" required>
            </div>

            {{-- Category --}}
            <div class="mb-2">
                <label class="block font-semibold text-white/80 text-sm">Category</label>
                <input type="text" id="edit_category" name="category" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
            </div>

            {{-- Location --}}
            <div class="mb-2 flex flex-col gap-1.5">
                <label class="block font-semibold text-white/80 text-sm">Location</label>
                <input type="text" id="edit_location" name="location" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
            </div>

            {{-- Venue --}}
            <div class="mb-2 flex flex-col gap-1.5">
                <label class="block font-semibold text-white/80 text-sm">Venue</label>
                <input type="text" id="edit_venue" name="venue" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
            </div>

            {{-- Event Date --}}
            <div class="mb-2 col-span-2 flex flex-col gap-1.5">
                <label class="block font-semibold text-white/80 text-sm">Event Date</label>
                <input type="date" id="edit_event_date" name="event_date" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
            </div>

            {{-- Start & End Time --}}
            <div class="mb-2 flex gap-2 col-span-2">
                <div class="flex-1">
                    <label class="block font-semibold text-white/80 text-sm">Start Time</label>
                    <input type="time" id="edit_start_time" name="start_time" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div class="flex-1">
                    <label class="block font-semibold text-white/80 text-sm">End Time</label>
                    <input type="time" id="edit_end_time" name="end_time" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
            </div>

            {{-- Description --}}
            <div class="mb-2 col-span-2 flex flex-col gap-1.5">
                <label class="block font-semibold text-white/80 text-sm">Description</label>
                <textarea id="edit_description" name="description" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" rows="3"></textarea>
            </div>

            {{-- Event Image --}}
            <div class="mb-2 col-span-2 flex flex-col gap-1.5">
                <label class="block font-semibold text-white/80 text-sm">Replace Image</label>
                <input type="file" name="event_image" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
            </div>

            {{-- Prices & Quantities --}}
            <div class="mb-2 grid grid-cols-2 gap-4 col-span-2">
                <div>
                    <label class="block font-semibold text-white/80 text-sm">Regular Price</label>
                    <input type="number" id="edit_regular_price" name="regular_price" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block font-semibold text-white/80 text-sm">Regular Quantity</label>
                    <input type="number" id="edit_regular_quantity" name="regular_quantity" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block font-semibold text-white/80 text-sm">VIP Price</label>
                    <input type="number" id="edit_vip_price" name="vip_price" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block font-semibold text-white/80 text-sm">VIP Quantity</label>
                    <input type="number" id="edit_vip_quantity" name="vip_quantity" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block font-semibold text-white/80 text-sm">VVIP Price</label>
                    <input type="number" id="edit_vvip_price" name="vvip_price" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="block font-semibold text-white/80 text-sm">VVIP Quantity</label>
                    <input type="number" id="edit_vvip_quantity" name="vvip_quantity" class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
            </div>

            {{-- Submit Button --}}
            <button class="w-full mt-4 px-4 py-2 bg-black/90 text-orange-400 font-mono font-bold rounded-3xl border border-green-400/20 hover:bg-black transition">
                Update Event
            </button>
        </form>
    </div>
</div>




@push('scripts')
<script>
    window.openEditModal = function(event) {
        document.getElementById("edit_name").value = event.event_name || "";
        document.getElementById("edit_category").value = event.category || "";
        document.getElementById("edit_location").value = event.location || "";
        document.getElementById("edit_venue").value = event.venue || "";
        document.getElementById("edit_event_date").value = event.event_date || "";
        document.getElementById("edit_start_time").value = event.start_time || "";
        document.getElementById("edit_end_time").value = event.end_time || "";
        document.getElementById("edit_description").value = event.description || "";
        document.getElementById("edit_regular_price").value = event.regular_price || "";
        document.getElementById("edit_regular_quantity").value = event.regular_quantity || "";
        document.getElementById("edit_vip_price").value = event.vip_price || "";
        document.getElementById("edit_vip_quantity").value = event.vip_quantity || "";
        document.getElementById("edit_vvip_price").value = event.vvip_price || "";
        document.getElementById("edit_vvip_quantity").value = event.vvip_quantity || "";

        // Set the form action dynamically
        document.getElementById("editForm").action = "/events/" + event.id;
        document.getElementById("editModal").classList.remove("hidden");
    }

    window.closeEditModal = function() {
        document.getElementById("editModal").classList.add("hidden");
    }

</script>
@endpush

@endsection
