<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Organizer</title>
    @vite('resources/css/app.css')
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-black/70 bg-[url('/public/bg-img.png')] bg-cover bg-center bg-fixed bg-blend-multiply relative">

    <section class="w-full grid grid-cols-2 gap-2 h-screen p-1">
        <!-- Left Section -->
        <div class="h-full w-full rounded-4xl pl-20 flex items-center bg-[url('/public/bg-img.png')] bg-blend-darken bg-black/70 border border-purple-400/10">

            <form action="{{ route('organizer_store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5 w-full max-w-md" x-data="{ imagePreview: null }">
                @csrf

                <div>
                    <h1 class="text-orange-400/70 text-4xl font-semibold">Create Organizer Page</h1>
                    <p class="font-light font-mono text-white/60">Let’s get to know you better so we can help you.</p>
                </div>


                <!-- Upload business icon -->
                <div>
                    <p class="text-white/60 font-medium ml-1 text-sm mb-2">Upload business icon</p>

                    <label for="organizer_image" class="cursor-pointer flex items-center gap-5">
                        <div class="p-8 border-dotted border-2 rounded-xl border-orange-400/70 w-fit text-white/60 flex flex-col items-center justify-center" :class="{ 'border-green-400/70': imagePreview }">
                            <template x-if="!imagePreview">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-up">
                                    <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21" />
                                    <path d="m14 19.5 3-3 3 3" />
                                    <path d="M17 22v-5.5" />
                                    <circle cx="9" cy="9" r="2" />
                                </svg>
                            </template>

                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview" class="rounded-xl w-20 h-20 object-cover border border-orange-400/50">
                            </template>
                        </div>
                        <div class="border border-white/60 p-1 px-2 rounded-lg text-white/70 flex items-center gap-1">
                            <p class="text-sm">Upload image</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cloud-upload">
                                <path d="M12 13v8" />
                                <path d="M4 14.9A7 7 0 1 1 15.7 8h1.8a4.5 4.5 0 0 1 2.5 8.24" />
                                <path d="m8 17 4-4 4 4" />
                            </svg>
                        </div>
                    </label>

                    <input type="file" id="organizer_image" name="organizer_image" class="hidden" accept="image/*" @change="const reader = new FileReader(); reader.onload = e => imagePreview = e.target.result; reader.readAsDataURL($event.target.files[0])">

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
                    <input type="text" id="business_name" name="business_name" placeholder="Enter business name" value="{{ old('business_name') }}" class="p-3 w-full text-white/70 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 backdrop-blur-4xl focus:outline-orange-400/50">
                    @error('business_name')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Email -->
                <div class="flex flex-col gap-2">
                    <label for="business_email" class="text-white/60 font-medium ml-1 text-sm">Business email</label>
                    <input type="email" id="business_email" name="business_email" placeholder="Enter business email" value="{{ old('business_email') }}" class="p-3 w-full text-white/70 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 backdrop-blur-4xl focus:outline-orange-400/50">
                    @error('business_email')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Business Website -->
                <div class="flex flex-col gap-2">
                    <label for="business_website" class="text-white/60 font-medium ml-1 text-sm">Business website</label>
                    <input type="text" id="business_website" name="business_website" placeholder="e.g., www.organizer.com" value="{{ old('business_website') }}" class="p-3 w-full text-white/70 rounded-3xl bg-[#b0a6df]/30 outline outline-[#b0a6df]/50 backdrop-blur-4xl focus:outline-orange-400/50">
                    @error('business_website')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full p-3 bg-black/80 border border-green-400/10 rounded-3xl text-white/70 text-sm font-medium hover:bg-black/60 transition">
                    Create Organizer
                </button>
            </form>

        </div>

        <!-- Right Section -->
        <div class="h-full w-full bg-black/50 bg-[url('/public/img4.jpg')] bg-cover bg-center rounded-4xl"></div>
    </section>

</body>
</html>
