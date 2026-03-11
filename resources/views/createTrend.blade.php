<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trend</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="w-full">
        <div class="grid grid-cols-12 w-full h-screen overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-purple-400/10">

            {{-- Left Section - Form --}}
            <div class="col-span-6 w-full p-1 h-full flex flex-col justify-between">
                <div class="bg-green-400/10 border border-green-400/10 p-5 h-full flex items-center ">
                    <form id="trend-form" x-data="{ 
                        title: '{{ old('title', '') }}',
                        body: '{{ old('body', '') }}',
                        taggedEvent: null
                    }" action="{{ route('trends.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-5 text-white/30 w-full" 
                        @event-selected.window="taggedEvent = $event.detail.title">
                        @csrf

                        {{-- Header --}}
                        <div class="flex gap-2 items-center justify-center text-xs p-1 font-mono font-extrabold bg-orange-400 rounded-2xl">
                            <p class="font-medium text-black/80 ml-1">Ready to start a new trend?</p>
                            <a href="{{ route('trends') }}" class="bg-black rounded-xl p-1 px-2 text-orange-400/80 font-medium font-mono">View Feed</a>
                        </div>

                        {{-- Success Message --}}
                        @if (session('success'))
                        <div class="w-full text-center bg-green-500/20 text-green-300 border border-green-500/30 rounded-2xl p-2">
                            {{ session('success') }}
                        </div>
                        @endif

                        {{-- Image Upload --}}
                        <div class="w-full">
                            <p class="text-white/60 font-medium ml-1 text-sm mb-2">Upload media (Choose up to 5)</p>

                            <div class="grid grid-cols-5 gap-3 mb-4 p-2 border-2 border-dashed border-orange-400/30 rounded-2xl bg-black/10" 
                                x-data="{ 
                                    slots: [null, null, null, null, null],
                                    handleFile(e, index) {
                                        const file = e.target.files[0];
                                        if (!file) return;
                                        
                                        const reader = new FileReader();
                                        const type = file.type.startsWith('video') ? 'video' : 'image';
                                        
                                        reader.onload = evt => {
                                            this.slots.splice(index, 1, {
                                                file: file,
                                                type: type,
                                                preview: evt.target.result
                                            });
                                            this.syncInput();
                                        };
                                        reader.readAsDataURL(file);
                                    },
                                    removeSlot(index) {
                                        this.slots.splice(index, 1, null);
                                        this.syncInput();
                                        const input = document.getElementById('file-' + index);
                                        if (input) input.value = '';
                                    },
                                    syncInput() {
                                        const dt = new DataTransfer();
                                        this.slots.forEach(slot => {
                                            if (slot && slot.file) dt.items.add(slot.file);
                                        });
                                        document.getElementById('media').files = dt.files;
                                    }
                                }">
                                <input type="file" id="media" name="media[]" class="hidden" accept="image/*,video/*" multiple>

                                <template x-for="(slot, index) in slots" :key="index">
                                    <div class="relative aspect-square rounded-xl w-full h-15  overflow-hidden border border-green-400/10 bg-green-400/10  group hover:border-orange-500 transition-all flex items-center justify-center shadow-lg">
                                        {{-- Empty Slot --}}
                                        <template x-if="!slot">
                                            <label :for="'file-' + index" class="cursor-pointer  w-full h-full flex flex-col items-center justify-center text-white/20 hover:text-orange-400 hover:bg-orange-400/5 transition-all">
                                                <i class="fa-solid fa-plus text-2xl mb-1"></i>
                                                <span class="text-[9px] font-black uppercase tracking-tighter">Add</span>
                                                <input type="file" :id="'file-' + index" class="hidden" accept="image/*,video/*" @change="handleFile($event, index)">
                                            </label>
                                        </template>

                                        {{-- Filled Slot --}}
                                        <template x-if="slot">
                                            <div class="w-full h-full relative group">
                                                <template x-if="slot.type === 'image'">
                                                    <img :src="slot.preview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="slot.type === 'video'">
                                                    <video :src="slot.preview" class="w-full h-full object-cover" muted autoplay loop></video>
                                                </template>
                                                
                                                <button type="button" @click="removeSlot(index)" class="absolute top-1 right-1 bg-black/80 text-white rounded-full size-6 flex items-center justify-center hover:bg-red-500 transition-all z-20 shadow-xl opacity-0 group-hover:opacity-100">
                                                    <i class="fa-solid fa-xmark text-[12px]"></i>
                                                </button>
                                                
                                                <div class="absolute bottom-1 right-1 bg-orange-400 text-black text-[9px] font-black px-1.5 rounded-md z-10 shadow-lg" x-text="index + 1"></div>
                                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            @error('media')
                            <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Title Field --}}
                        <div class="flex flex-col gap-2 w-full">
                            <label for="title" class="text-white/60 font-medium ml-1 text-sm">Trend Title</label>
                            <input id="title" type="text" name="title" x-model="title" placeholder="Enter your trend title" class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @error('title')
                            <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TAG EVENT FIELD --}}
                        <div class="flex flex-col gap-2 w-full relative">
                            <label for="event_tag" class="text-white/60 font-medium ml-1 text-sm">Tag an Event (optional)</label>
                            <div class="relative">
                                <input type="text" id="event_tag" placeholder="Type to search events..." class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70" autocomplete="off">
                                <i class="fa-solid fa-at absolute right-4 top-1/2 -translate-y-1/2 text-orange-400/30"></i>
                            </div>
                            <input type="hidden" name="event_id" id="event_id">

                            <ul id="event_results" class="absolute z-50 top-full left-0 bg-zinc-900 border border-white/10 p-2 rounded-2xl max-h-60 overflow-auto hidden w-full shadow-2xl backdrop-blur-xl"></ul>
                        </div>

                        {{-- Body Field --}}
                        <div class="flex flex-col gap-2 w-full">
                            <label for="body" class="text-white/60 font-medium ml-1 text-sm">Trend Body</label>
                            <textarea id="body" name="body" rows="4" x-model="body" placeholder="Write your trend..." class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"></textarea>
                            @error('body')
                            <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button class="w-full p-3 bg-black/80 border border-green-400/20 rounded-3xl text-white/80 text-sm font-semibold hover:bg-green-400/10 transition-all mt-4">
                            Post Trend
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Section - Image / Hero --}}
            <div class="col-span-6 w-full h-full relative p-1">
                <div class="w-full h-full bg-[url('/public/img5.jpg')] bg-center bg-contain bg-blend-darken pt-5">
                    <!-- Add a creative message or inspirational quote here -->
                </div>
            </div>

        </div>



        <script>
            const eventInput = document.getElementById('event_tag');
            const eventResults = document.getElementById('event_results');
            const eventIdInput = document.getElementById('event_id');
            const searchRoute = "{{ route('events.search') }}";

            let debounceTimer;
            let selectedIndex = -1;

            eventInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (!query) {
                    eventResults.innerHTML = '';
                    eventResults.classList.add('hidden');
                    eventIdInput.value = '';
                    selectedIndex = -1;
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    try {
                        const res = await fetch(`${searchRoute}?q=${encodeURIComponent(query)}`);
                        const events = await res.json();

                        if (events.length === 0) {
                            eventResults.innerHTML = `<li class="px-3 py-2 text-white/50">No events found</li>`;
                        } else {
                            eventResults.innerHTML = events.map(ev => `
                            <li class="p-1 hover:bg-green-400/10 hover:text-white/60 border border-green-400/20 mb-1 text-xs font-mono font-medium rounded-2xl w-fit cursor-pointer bg-orange-400 text-black/90" data-id="${ev.id}">
                                # ${ev.title}
                            </li>
                        `).join('');

                        }

                        eventResults.classList.remove('hidden');
                        selectedIndex = -1;
                    } catch (err) {
                        console.error('Search error:', err);
                    }
                }, 300);
            });

            eventResults.addEventListener('click', function(e) {
                if (e.target.tagName === 'LI' && e.target.dataset.id) {
                    selectEvent(e.target);
                }
            });

            document.addEventListener('click', function(e) {
                if (!eventResults.contains(e.target) && e.target !== eventInput) {
                    eventResults.classList.add('hidden');
                }
            });

            // Keyboard navigation
            eventInput.addEventListener('keydown', function(e) {
                const items = eventResults.querySelectorAll('li[data-id]');
                if (items.length === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex + 1) % items.length;
                    highlightItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                    highlightItem(items);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (selectedIndex >= 0) selectEvent(items[selectedIndex]);
                }
            });

            function highlightItem(items) {
                items.forEach((el, idx) => {
                    if (idx === selectedIndex) {
                        el.classList.add('bg-orange-400/40');
                        el.scrollIntoView({
                            block: 'nearest'
                        });
                    } else {
                        el.classList.remove('bg-orange-400/40');
                    }
                });
            }

            function selectEvent(el) {
                const eventTitle = el.textContent.trim().replace('# ', '');
                eventInput.value = el.textContent.trim();
                eventIdInput.value = el.dataset.id;
                eventResults.classList.add('hidden');
                selectedIndex = -1;
                
                // Dispatch event for Alpine.js mockup
                window.dispatchEvent(new CustomEvent('event-selected', { 
                    detail: { title: eventTitle }
                }));
            }

        </script>




</body>
</html>
