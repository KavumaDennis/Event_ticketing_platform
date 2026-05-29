<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Organizer</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
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

<body
    class="overflow-y-auto bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-purple-400/10">

    <section class="w-full grid-cols-1 grid md:grid-cols-2 gap-2 h-screen p-0.5">
        <!-- Left Section -->
        <div class="h-full w-full px-10 flex items-center bg-green-400/10 border border-green-400/10">

            <form action="{{ route('organizer_store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col gap-5 w-full" x-data="{ imagePreview: null }">
                @csrf

                <div>
                    <h1 class="text-orange-400/70 text-4xl font-semibold">Create Organizer Profile</h1>
                    <p class="font-light font-mono text-white/60">Let’s get to know you better so we can help you.</p>
                </div>


                <!-- Upload business icon -->
                <div>
                    <p class="text-white/60 font-medium ml-1 text-sm mb-2">Upload business icon</p>

                    <label for="organizer_image" class="cursor-pointer flex items-center gap-5">
                        <div class="p-8 border-dotted border-2 rounded-xl border-orange-400/70 w-fit text-white/60 flex flex-col items-center justify-center"
                            :class="{ 'border-green-400/70': imagePreview }">
                            <template x-if="!imagePreview">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-image-up">
                                    <path
                                        d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21" />
                                    <path d="m14 19.5 3-3 3 3" />
                                    <path d="M17 22v-5.5" />
                                    <circle cx="9" cy="9" r="2" />
                                </svg>
                            </template>

                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview"
                                    class="rounded-xl w-20 h-20 object-cover border border-orange-400/50">
                            </template>
                        </div>
                        <div class="border border-white/60 p-1 px-2 rounded-lg text-white/70 flex items-center gap-1">
                            <p class="text-sm">Upload image</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.9A7 7 0 1 1 15.7 8h1.8a4.5 4.5 0 0 1 2.5 8.24" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>
                        </div>
                    </label>

                    <input type="file" id="organizer_image" name="organizer_image" class="hidden" accept="image/*"
                        @change="const reader = new FileReader(); reader.onload = e => imagePreview = e.target.result; reader.readAsDataURL($event.target.files[0])">

                    @error('organizer_image')
                        <p class="text-red-400 text-xs ml-1 mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-xs mt-2 text-orange-400/60 font-mono font-light">
                        Pick a photo up to 2MB (optional). Your profile photo will be public.
                    </p>
                </div>

                <!-- Business Name -->
                <div class="flex flex-col gap-2">
                    <label for="business_name" class="text-white/60 font-medium ml-1 text-sm">Business name</label>
                    <input type="text" id="business_name" name="business_name" placeholder="Enter business name"
                        value="{{ old('business_name') }}"
                        class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    @error('business_name')
                        <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Email -->
                <div class="flex flex-col gap-2">
                    <label for="business_email" class="text-white/60 font-medium ml-1 text-sm">Business email</label>
                    <input type="email" id="business_email" name="business_email" placeholder="Enter business email"
                        value="{{ old('business_email') }}"
                        class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    @error('business_email')
                        <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Website -->
                <div class="flex flex-col gap-2">
                    <label for="business_website" class="text-white/60 font-medium ml-1 text-sm">Business
                        website</label>
                    <input type="text" id="business_website" name="business_website"
                        placeholder="e.g., www.organizer.com" value="{{ old('business_website') }}"
                        class="p-3 rounded-xl bg-white/5 outline outline-white/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                    @error('business_website')
                        <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl text-white/70 text-sm font-medium hover:bg-black/60 transition">
                    Create Organizer
                </button>
            </form>

        </div>

        <!-- Right Section -->
        <div class="col-span-1 w-full h-full relative border border-green-400/10">
            <div class="w-full h-full relative overflow-hidden border border-green-400/10">
                <div class="absolute inset-0" data-auth-slider>
                    <img src="{{ asset('img1.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 1">
                    <img src="{{ asset('img2.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 2">
                    <img src="{{ asset('img4.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 3">
                    <img src="{{ asset('img5.jpg') }}" class="auth-slide absolute inset-0 w-full h-full object-cover"
                        alt="Akavaako slide 4">
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
    </section>

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
    </script>

</body>

</html>
