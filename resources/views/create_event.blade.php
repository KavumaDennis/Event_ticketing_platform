<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-black/80 bg-[url('/public/bg-img.png')] bg-cover bg-center bg-fixed bg-blend-multiply relative p-0.5 border border-green-400/10">

    <section class="grid grid-cols-6 gap-3 p-0.5 h-screen">
        <!-- LEFT SECTION -->
        <div class="lg:col-span-4 col-span-6 h-full overflow-y-auto border border-green-400/10 bg-green-400/10">
            <div class="w-full h-full overflow-y-auto ">
                @if (session('success'))
                    <div
                        class="mb-4 p-3 text-green-400 bg-green-900/30 border border-green-400/40 rounded-xl text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form x-data="{ imagePreview: null, fileCount: 0 }" action="{{ route('events.store') }}" method="POST"
                    enctype="multipart/form-data" class="flex flex-col mx-auto  gap-6 w-[90%] p-2 lg:p-4 pt-8">
                    @csrf
                    <h1 class="text-orange-400/70 text-2xl mb-5">Create an Event</h1>

                    <!-- Upload Event Media -->
                    <div>
                        <p class="text-white/60 font-medium ml-1 text-sm mb-2">Upload event media (images/videos)</p>

                        <label for="media" class="cursor-pointer flex items-center gap-5">
                            <div class="p-8 border-dotted border-2 rounded-xl border-orange-400/70 w-fit text-white/60 flex flex-col items-center justify-center relative overflow-hidden"
                                :class="{ 'border-green-400/70': imagePreview }">
                                <template x-if="!imagePreview">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path
                                            d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21" />
                                        <path d="m14 19.5 3-3 3 3" />
                                        <path d="M17 22v-5.5" />
                                        <circle cx="9" cy="9" r="2" />
                                    </svg>
                                </template>

                                <template x-if="imagePreview">
                                    <div class="relative w-20 h-20">
                                        <img :src="imagePreview" alt="Preview"
                                            class="rounded-xl w-full h-full object-cover border border-orange-400/50">
                                        <div x-show="fileCount > 1" class="absolute -top-2 -right-2 bg-orange-400 text-black text-[10px] font-bold px-2 py-0.5 rounded-full z-10" x-text="'+' + (fileCount - 1)"></div>
                                    </div>
                                </template>
                            </div>

                            <div
                                class="border border-white/60 p-1 px-2 rounded-lg text-white/70 flex items-center gap-1">
                                <p class="text-sm" x-text="fileCount > 0 ? fileCount + ' files selected' : 'Upload media'"></p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-cloud-upload">
                                    <path d="M12 13v8" />
                                    <path d="M4 14.9A7 7 0 1 1 15.7 8h1.8a4.5 4.5 0 0 1 2.5 8.24" />
                                    <path d="m8 17 4-4 4 4" />
                                </svg>
                            </div>
                        </label>

                        <input type="file" id="media" name="media[]" multiple class="hidden" accept="image/*,video/*"
                            @change="
                                const files = $event.target.files;
                                fileCount = files.length;
                                if(files.length > 0 && files[0].type.startsWith('image/')){
                                    const reader = new FileReader();
                                    reader.onload = e => imagePreview = e.target.result;
                                    reader.readAsDataURL(files[0]);
                                } else if(files.length > 0) {
                                    imagePreview = 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' fill=\'none\' stroke=\'white\' stroke-width=\'2\'><rect x=\'20\' y=\'20\' width=\'60\' height=\'60\' rx=\'10\'/><path d=\'M35 35l30 30m0-30L35 65\'/></svg>'; // Placeholder for video
                                } else {
                                    imagePreview = null;
                                }
                            ">

                        @error('media')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                        @error('media.*')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs mt-2 text-orange-400/60 font-mono font-light">Pick up to 5 photos or videos.</p>
                    </div>

                    <!-- Event Name -->
                    <div class="flex flex-col gap-2">
                        <label for="event_name" class="text-white/60 font-medium ml-1 text-sm">Event name</label>
                        <input id="event_name" name="event_name" type="text" value="{{ old('event_name') }}"
                            placeholder="Enter event name"
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        @error('event_name')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="flex flex-col gap-2">
                        <label for="category" class="text-white/60 font-medium ml-1 text-sm">Event Category</label>
                        <input id="category" name="category" type="text" value="{{ old('category') }}"
                            list="category-list" placeholder="Pick a category or type your own"
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        <datalist id="category-list">
                            @foreach (($categories ?? []) as $category)
                                <option value="{{ $category }}"></option>
                            @endforeach
                        </datalist>
                        <p class="text-xs text-white/50 font-mono">Choose from existing categories or type a new one.</p>
                        @error('category')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Organizer -->
                    <div class="flex flex-col gap-2">
                        <label for="organizer" class="text-white/70 font-medium ml-1 text-sm">
                            Event Organizer
                        </label>

                        @if (!empty($organizerName))
                            <!-- Fixed organizer name from profile -->
                            <input id="organizer" name="organizer" type="text" value="{{ $organizerName }}" readonly
                                class="p-3 rounded-xl outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70 cursor-not-allowed">
                            <p class="text-xs mt-1 text-green-400/70 italic">
                                Organizer: <span class="font-semibold">{{ $organizerName }}</span> (from your profile)
                            </p>
                        @else
                            <!-- Editable input if user has no profile -->
                            <input id="organizer" name="organizer" type="text"
                                placeholder="Enter event organizer name" value="{{ old('organizer') }}"
                                class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            <p class="text-xs mt-1 text-white/60 italic">
                                You can type the organizer name if you don’t have a profile yet.
                            </p>
                        @endif

                        @error('organizer')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="flex flex-col gap-2 relative geo-ac-wrap">
                        <label for="location" class="text-white/60 font-medium ml-1 text-sm">Event location</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}"
                            placeholder="City, town, area (search as you type)"
                            data-geocode-autocomplete
                            autocomplete="street-address"
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        @error('location')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Venue -->
                    <div class="flex flex-col gap-2">
                        <label for="venue" class="text-white/60 font-medium ml-1 text-sm">Event Venue</label>
                        <input id="venue" name="venue" type="text" value="{{ old('venue') }}"
                            placeholder="Enter event venue"
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        @error('venue')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Date -->
                    <div class="flex flex-col gap-2">
                        <label for="event_date" class="text-white/60 font-medium ml-1 text-sm">Event Date</label>
                        <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}"
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                        @error('event_date')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Date and Time -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-2">
                            <label for="start_time" class="text-white/60 font-medium ml-1 text-sm">Start time</label>
                            <input id="start_time" name="start_time" type="time" value="{{ old('start_time') }}"
                                class="p-3 rounded-l-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @error('start_time')
                                <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="end_time" class="text-white/60 font-medium ml-1 text-sm">End time</label>
                            <input id="end_time" name="end_time" type="time" value="{{ old('end_time') }}"
                                class="p-3 rounded-r-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @error('end_time')
                                <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Ticket Types -->
                    <div class="flex flex-col gap-4">
                        @php
                            $ticketTypes = ['regular', 'vip', 'vvip'];
                        @endphp

                        @foreach ($ticketTypes as $type)
                            <div class="">
                                <h2 class="text-orange-400/80 font-mono text-lg mb-3">{{ strtoupper($type) }} Ticket
                                </h2>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex flex-col gap-2">
                                        <label for="{{ $type }}_price"
                                            class="text-white/60 font-medium ml-1 text-sm">Price (UGX)</label>
                                        <input type="number" id="{{ $type }}_price"
                                            name="{{ $type }}_price" step="0.01"
                                            value="{{ old($type . '_price') }}"
                                            placeholder="e.g. {{ $type == 'regular' ? '2000' : ($type == 'vip' ? '5000' : '10000') }}"
                                            class="p-3 rounded-l-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                        @error($type . '_price')
                                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="{{ $type }}_quantity"
                                            class="text-white/60 font-medium ml-1 text-sm">Quantity</label>
                                        <input type="number" id="{{ $type }}_quantity"
                                            name="{{ $type }}_quantity" value="{{ old($type . '_quantity') }}"
                                            placeholder="e.g. {{ $type == 'regular' ? '100' : ($type == 'vip' ? '50' : '20') }}"
                                            class="p-3 rounded-r-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                                        @error($type . '_quantity')
                                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Event Description -->
                    <div class="flex flex-col gap-2">
                        <label for="description" class="text-white/60 font-medium ml-1 text-sm">Event
                            description</label>
                        <textarea id="description" name="description" placeholder="Describe your event..."
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ticket Special Instructions (Optional) -->
                    <div class="flex flex-col gap-2">
                        <label for="ticket_instructions" class="text-white/60 font-medium ml-1 text-sm">
                            Ticket special instructions (optional)
                        </label>
                        <textarea id="ticket_instructions" name="ticket_instructions" rows="3"
                            placeholder="e.g. Please arrive 30 minutes early, bring an ID, dress code..."
                            class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">{{ old('ticket_instructions') }}</textarea>
                        <p class="text-xs text-white/40 font-mono">Shown on tickets for this event only.</p>
                        @error('ticket_instructions')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full p-3 bg-black/80 border border-green-400/20 rounded-3xl text-white/80 text-sm font-semibold hover:bg-green-400/10 transition-all mt-4">
                        Create Event
                    </button>
                </form>


            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="col-span-2 hidden h-full overflow-y-auto lg:flex flex-col justify-between gap-3 p-1">
            <div class="rounded-4xl p-1 border border-green-400/10 bg-green-400/10 flex justify-between items-center">
                <p class="ml-2 text-orange-400/80 font-medium font-mono text-sm">AKAVAAKO</p>
                <div class="flex gap-3 items-center">
                    <a href="{{ route('home') }}">
                        <p
                            class="border border-green-400/10 bg-green-400/10 font-mono p-1.5 px-3 rounded-[20px] text-white/70 text-xs font-medium">
                            Home
                        </p>
                    </a>
                    <a href="{{ route('events') }}">
                        <p
                            class="border border-green-400/10 bg-green-400/10 font-mono p-1.5 px-3 rounded-[20px] text-white/70 text-xs font-medium">
                            Events
                        </p>
                    </a>
                    <a href="{{ route('trends') }}">
                        <p
                            class="border border-green-400/10 bg-green-400/10 font-mono p-1.5 px-3 rounded-[20px] text-white/70 text-xs font-medium">
                            Trends
                        </p>
                    </a>

                </div>
            </div>
            <div class="flex flex-col justify-between gap-3">
                <p
                    class="bg-orange-400 border border-green-400/20 text-xs rounded-2xl p-0.5 px-1 text-black/90 font-mono w-fit">
                    Top organizers
                </p>
                <div class="flex flex-col gap-2 ">
                    <div class="flex flex-col gap-2">
                        @foreach ($topOrganizers as $topOrganizer)
                            <div
                                class="flex items-center gap-5 p-2 border border-green-400/5 bg-green-400/10 rounded-2xl">
                                <div class="border border-green-400/10  w-fit rounded-[50%]">
                                    <img src="{{ $topOrganizer->organizer_image ? asset('storage/' . $topOrganizer->organizer_image) : asset('default.png') }}"
                                        alt="{{ $topOrganizer->business_name }}" class='size-12 rounded-[50%]'
                                        alt="" />
                                </div>
                                <div class="flex items-center justify-between w-4/5 ">
                                    <div>
                                        <p class="font-medium text-sm text-white/80">
                                            {{ $topOrganizer->business_name }}</p>
                                        <div class="text-xs text-white/60">
                                            {{ $topOrganizer->followers_count ?? $topOrganizer->followers->count() }}
                                            followers</div>
                                    </div>
                                    <a href="{{ route('organizer.details', $topOrganizer->id) }}"
                                        class="text-xs text-black/90 font-medium font-mono bg-orange-400 rounded-3xl px-2 py-1">Details</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-4 p-2 bg-green-400/10 border border-green-400/5 rounded-[20px]">
                    <div class="flex flex-col p-2">
                        <h1 class="text-orange-400/70 font-medium text-lg">Trends help events reach bigger audiences
                        </h1>
                        <p class="text-white/60 font-medium ml-1 text-sm font-mono mt-3">
                            Stay on top of event trends and boost attendance with creative promotions.
                        </p>
                    </div>
                    <a href="{{ route('trends.create') }}"
                        class="w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl font-mono text-white/70 text-center text-sm font-medium hover:bg-green-400/10 transition-all">
                        Write your own trend
                    </a>
                </div>
            </div>
        </div>
    </section>

@include('partials.location-autocomplete')

</body>

</html>
