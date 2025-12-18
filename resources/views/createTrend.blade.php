<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="w-full">
        <div class="grid grid-cols-12 w-full h-screen overflow-y-auto bg-[url(/public/bg-img.png)] bg-blend-darken bg-black/75 border border-purple-400/10">

            {{-- Left Section - Form --}}
            <div class="col-span-6 w-full p-1 h-full flex flex-col justify-between">
                <div class="bg-green-400/10 border border-green-400/10 p-5 h-full flex items-center ">
                    <form action="{{ route('trends.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center gap-5 text-white/30 w-full">
                        @csrf

                        {{-- Header --}}
                        <div class="flex gap-2 items-center justify-center text-xs w-fit p-1 bg-orange-400/60 rounded-2xl">
                            <p class="font-medium text-black/80 ml-1">Want to see the latest trends?</p>
                            <a href="{{ route('trends') }}" class="bg-black rounded-xl p-1 px-2 text-orange-400/80 font-medium font-mono">View Trends</a>
                        </div>

                        {{-- Success Message --}}
                        @if (session('success'))
                        <div class="w-full text-center bg-green-500/20 text-green-300 border border-green-500/30 rounded-2xl p-2">
                            {{ session('success') }}
                        </div>
                        @endif



                        {{-- Title Field --}}
                        <div class="flex flex-col gap-2 w-full">
                            <label for="title" class="text-white/60 font-medium ml-1 text-sm">Trend Title</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" placeholder="Enter your trend title" class="p-3 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 backdrop-blur-4xl text-white placeholder-white/40">
                            @error('title')
                            <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TAG EVENT FIELD --}}
                        <div class="flex flex-col gap-2 w-full relative">
                            <label for="event_tag" class="text-white/60 font-medium ml-1 text-sm">Tag an Event (optional)</label>
                            <input type="text" id="event_tag" placeholder="Type to search events..." class="p-3 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 backdrop-blur-4xl text-white placeholder-white/40" autocomplete="off">
                            <input type="hidden" name="event_id" id="event_id">

                            <ul id="event_results" class="absolute z-50 top-full left-0  bg-gray-900 p-2  border border-green-200/10 rounded-2xl max-h-60 overflow-auto hidden w-fit"></ul>
                        </div>



                        {{-- Body Field --}}
                        <div class="flex flex-col gap-2 w-full">
                            <label for="body" class="text-white/60 font-medium ml-1 text-sm">Trend Body</label>
                            <textarea id="body" name="body" rows="6" placeholder="Write your trend..." class="p-3 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 backdrop-blur-4xl text-white placeholder-white/40 resize-none">{{ old('body') }}</textarea>
                            @error('body')
                            <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image Upload --}}
                        <div class="flex flex-col gap-2 w-full">
                            <label for="image" class="text-white/60 font-medium ml-1 text-sm">Upload Image (optional)</label>
                            <input id="image" type="file" name="image" class="p-2 w-full rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 text-white/60">
                            @error('image')
                            <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button class="w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl text-white/70 text-sm font-medium">
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
                            <li class="p-1 bg-green-400/10 border border-green-400/20 mb-1 text-xs font-mono font-medium rounded-2xl w-fit cursor-pointer hover:bg-orange-400/70 hover:text-black/90" data-id="${ev.id}">
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
                eventInput.value = el.textContent;
                eventIdInput.value = el.dataset.id;
                eventResults.classList.add('hidden');
                selectedIndex = -1;
            }

        </script>




</body>
</html>
