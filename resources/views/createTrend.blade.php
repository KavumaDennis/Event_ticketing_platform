<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trend</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .auth-slide {
            opacity: 0;
            transform: scale(1.06) translateY(8px);
            transition: opacity 1200ms cubic-bezier(0.22, 1, 0.36, 1), transform 2400ms cubic-bezier(0.22, 1, 0.36, 1);
            will-change: opacity, transform;
        }

        .auth-slide.is-active {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        .auth-dots {
            position: absolute;
            bottom: 18px;
            right: 18px;
            display: flex;
            gap: 8px;
            z-index: 20;
        }

        .auth-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.35);
            transition: width 350ms ease, background 350ms ease, box-shadow 350ms ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .auth-dot.is-active {
            width: 24px;
            background: oklch(75% 0.183 55.934);
            /* box-shadow: 0 0 12px rgba(251, 146, 60, 0.35); */
        }

        .pw-toggle-wrap {
            position: relative;
        }

        .pw-toggle-btn {
            position: absolute;
            right: 0.3rem;
            top: 70%;
            transform: translateY(-50%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: rgba(251, 146, 60, 0.8);
            border: 1px solid rgba(34, 197, 94, 0.15);
            transition: background 200ms ease, color 200ms ease, transform 200ms ease;
        }

        .pw-toggle-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            color: rgba(251, 146, 60, 1);
            transform: translateY(-50%) scale(1.05);
        }

        .pw-toggle-input {
            padding-right: 3rem !important;
        }
    </style>
</head>

<body>
    <div class="w-full">
        <div
            class="grid grid-cols-6 w-full h-screen overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-purple-400/10">

            {{-- Left Section - Form --}}
            <div class="col-span-3 w-full p-0.5 h-full flex flex-col justify-between">
                <div class="bg-green-400/10 border border-green-400/10 p-5 h-full flex items-center ">
                    <form id="trend-form" x-data="{
                        title: '{{ old('title', '') }}',
                        body: '{{ old('body', '') }}',
                        taggedEvent: null
                    }" action="{{ route('trends.store') }}" method="POST"
                        enctype="multipart/form-data" class="flex flex-col items-center gap-5 text-white/30 w-full"
                        @event-selected.window="taggedEvent = $event.detail.title">
                        @csrf

                        {{-- Header --}}
                        <div
                            class="flex gap-2 items-center justify-center text-xs p-1 font-mono font-extrabold bg-orange-400 rounded-2xl">
                            <p class="font-medium text-black/80 ml-1">Ready to start a new trend?</p>
                            <a href="{{ route('trends') }}"
                                class="bg-black rounded-xl p-1 px-2 text-orange-400/80 font-medium font-mono">View
                                Feed</a>
                        </div>

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div
                                class="w-full text-center bg-green-500/20 text-green-300 border border-green-500/30 rounded-2xl p-2">
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
                                <input type="file" id="media" name="media[]" class="hidden"
                                    accept="image/*,video/*" multiple>

                                <template x-for="(slot, index) in slots" :key="index">
                                    <div
                                        class="relative aspect-square rounded-xl w-full h-15  overflow-hidden border border-green-400/10 bg-green-400/10  group hover:border-orange-500 transition-all flex items-center justify-center shadow-lg">
                                        {{-- Empty Slot --}}
                                        <template x-if="!slot">
                                            <label :for="'file-' + index"
                                                class="cursor-pointer  w-full h-full flex flex-col items-center justify-center text-white/20 hover:text-orange-400 hover:bg-orange-400/5 transition-all">
                                                <i class="fa-solid fa-plus text-2xl mb-1"></i>
                                                <span
                                                    class="text-[9px] font-black uppercase tracking-tighter">Add</span>
                                                <input type="file" :id="'file-' + index" class="hidden"
                                                    accept="image/*,video/*" @change="handleFile($event, index)">
                                            </label>
                                        </template>

                                        {{-- Filled Slot --}}
                                        <template x-if="slot">
                                            <div class="w-full h-full relative group">
                                                <template x-if="slot.type === 'image'">
                                                    <img :src="slot.preview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="slot.type === 'video'">
                                                    <video :src="slot.preview" class="w-full h-full object-cover"
                                                        muted autoplay loop></video>
                                                </template>

                                                <button type="button" @click="removeSlot(index)"
                                                    class="absolute top-1 right-1 bg-black/80 text-white rounded-full size-6 flex items-center justify-center hover:bg-red-500 transition-all z-20 shadow-xl opacity-0 group-hover:opacity-100">
                                                    <i class="fa-solid fa-xmark text-[12px]"></i>
                                                </button>

                                                <div class="absolute bottom-1 right-1 bg-orange-400 text-black text-[9px] font-black px-1.5 rounded-md z-10 shadow-lg"
                                                    x-text="index + 1"></div>
                                                <div
                                                    class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors">
                                                </div>
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
                            <input id="title" type="text" name="title" x-model="title"
                                placeholder="Enter your trend title"
                                class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                            @error('title')
                                <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TAG EVENT FIELD --}}
                        <div class="flex flex-col gap-2 w-full relative">
                            <label for="event_tag" class="text-white/60 font-medium ml-1 text-sm">Tag an Event
                                (optional)</label>
                            <div class="relative">
                                <input type="text" id="event_tag" placeholder="Type to search events..."
                                    class="w-full p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"
                                    autocomplete="off">
                                <i
                                    class="fa-solid fa-at absolute right-4 top-1/2 -translate-y-1/2 text-orange-400/30"></i>
                            </div>
                            <input type="hidden" name="event_id" id="event_id">

                            <ul id="event_results"
                                class="absolute z-50 top-full left-0 bg-zinc-900 border border-white/10 p-2 rounded-2xl max-h-60 overflow-auto hidden w-full shadow-2xl backdrop-blur-xl">
                            </ul>
                        </div>

                        {{-- Body Field --}}
                        <div class="flex flex-col gap-2 w-full">
                            <label for="body" class="text-white/60 font-medium ml-1 text-sm">Trend Body</label>
                            <textarea id="body" name="body" rows="4" x-model="body" placeholder="Write your trend..."
                                class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"></textarea>
                            @error('body')
                                <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button
                            class="w-full p-3 bg-black/80 border border-green-400/20 rounded-3xl text-white/80 text-sm font-semibold hover:bg-green-400/10 transition-all mt-4">
                            Post Trend
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Section - Image / Hero --}}
            <div class="col-span-3 w-full h-full relative border border-green-400/10">
                <div class="w-full h-full relative overflow-hidden border border-green-400/10">
                    <div class="absolute inset-0" data-auth-slider>
                        <img src="{{ asset('img1.jpg') }}"
                            class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 1">
                        <img src="{{ asset('img2.jpg') }}"
                            class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 2">
                        <img src="{{ asset('img4.jpg') }}"
                            class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 3">
                        <img src="{{ asset('img5.jpg') }}"
                            class="auth-slide absolute inset-0 w-full h-full object-cover" alt="Akavaako slide 4">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/80"></div>
                    <div class="auth-dots" aria-hidden="true"></div>

                    <div class="relative z-10 w-full h-full pt-3">
                        <div class="flex justify-between items-center px-3">
                            <div class="flex overflow-hidden gap-3 text-sm text-black font-medium">
                                <a href="{{ route('home') }}"
                                    class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Home</a>
                                <a href="{{ route('events') }}"
                                    class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Events</a>
                                <a href="{{ route('contact') }}"
                                    class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Contact</a>
                                <a href="{{ route('organizers') }}"
                                    class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Organizer</a>
                                <a href="{{ route('trends') }}"
                                    class="bg-orange-400 py-2 px-3 rounded-lg border border-green-400/20 hover:bg-black hover:text-white">Trends</a>
                            </div>

                            <div class="flex  gap-3 bg-orange-400 p-1 rounded-3xl">
                                <span
                                    class='size-8 flex justify-center items-center text-md bg-black text-orange-400 p-2 rounded-[50%]'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-arrow-big-left-dash-icon lucide-arrow-big-left-dash">
                                        <path
                                            d="M13 9a1 1 0 0 1-1-1V5.061a1 1 0 0 0-1.811-.75l-6.835 6.836a1.207 1.207 0 0 0 0 1.707l6.835 6.835a1 1 0 0 0 1.811-.75V16a1 1 0 0 1 1-1h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1z" />
                                        <path d="M20 9v6" />
                                    </svg>
                                </span>
                                <span
                                    class='size-8 flex justify-center items-center text-md bg-black text-orange-400 p-2 rounded-[50%]'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-arrow-big-right-dash-icon lucide-arrow-big-right-dash">
                                        <path
                                            d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                                        <path d="M4 9v6" />
                                    </svg>
                                </span>
                            </div>

                        </div>
                        <div class="absolute bottom-3 left-3 flex flex-col gap-3 z-10">

                            <div class="text-white font-bold tracking-tighter text-2xl uppercase mb-5">
                                Discover Events.
                                Anywhere, Anytime.
                            </div>

                            <div class="flex items-center p-1 w-fit gap-1 rounded-3xl">
                                <h1 class="uppercase text-lg font-semibold text-orange-400">AKAVAAKO</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



        <script>
            document.querySelectorAll('[data-auth-slider]').forEach((slider) => {
                const slides = Array.from(slider.querySelectorAll('.auth-slide'));
                if (!slides.length) return;

                const dotsWrap = slider.parentElement.querySelector('.auth-dots');
                let dots = [];
                if (dotsWrap) {
                    dotsWrap.innerHTML = '';
                    dots = slides.map((_, i) => {
                        const dot = document.createElement('button');
                        dot.type = 'button';
                        dot.className = 'auth-dot';
                        dot.addEventListener('click', () => {
                            index = i;
                            show(index, true);
                        });
                        dotsWrap.appendChild(dot);
                        return dot;
                    });
                }

                let index = 0;
                let timer = null;

                const show = (i, manual = false) => {
                    slides.forEach((img, idx) => img.classList.toggle('is-active', idx === i));
                    dots.forEach((dot, idx) => dot.classList.toggle('is-active', idx === i));
                    if (manual) {
                        clearInterval(timer);
                        timer = setInterval(next, 4800);
                    }
                };

                const next = () => {
                    index = (index + 1) % slides.length;
                    show(index);
                };

                show(index);
                timer = setInterval(next, 4800);
            });


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
                            eventResults.innerHTML =
                                `<li class="px-3 py-2 text-white/50">No events found</li>`;
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
                    detail: {
                        title: eventTitle
                    }
                }));
            }
        </script>




</body>

</html>
