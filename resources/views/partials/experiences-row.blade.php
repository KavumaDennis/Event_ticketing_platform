<style>
    @keyframes experienceGlow {
        0% { box-shadow: 0 0 0 rgba(251, 146, 60, 0.25); }
        50% { box-shadow: 0 0 16px rgba(251, 146, 60, 0.6); }
        100% { box-shadow: 0 0 0 rgba(251, 146, 60, 0.25); }
    }
    .experience-ring {
        background: conic-gradient(from 180deg, rgba(251, 146, 60, 0.8), rgba(34, 197, 94, 0.8), rgba(251, 146, 60, 0.8));
        padding: 3px;
        border-radius: 999px;
    }
    .experience-ring.glow {
        animation: experienceGlow 2.5s ease-in-out infinite;
    }
</style>

@php
    $experienceUsersPayload = $experienceUsers->map(function($user) {
        return [
            'user_id' => $user->id,
            'user_name' => trim($user->first_name . ' ' . $user->last_name),
            'user_photo' => $user->profile_photo_url,
            'experiences' => $user->experiences->map(function($exp) {
                return [
                    'id' => $exp->id,
                    'media_url' => asset('storage/' . $exp->media_path),
                    'media_type' => $exp->media_type,
                    'caption' => $exp->caption,
                ];
            })->values(),
        ];
    })->values();
@endphp

<section class="p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90">Experiences</h2>
        <span class="text-white/50 text-xs font-mono">{{ $experienceUsers->count() }} active</span>
    </div>

    @if($experienceUsers->isEmpty())
        <div class="p-4 bg-green-400/5 border border-dashed border-green-400/20 rounded-3xl text-center">
            <p class="text-zinc-500 text-sm">No experiences yet.</p>
        </div>
    @else
        <div class="flex gap-4 overflow-x-auto pb-2">
            @foreach($experienceUsers as $expUser)
                <button type="button" class="experience-user flex flex-col items-center gap-2 min-w-[72px]" data-user-id="{{ $expUser->id }}">
                    <div class="experience-ring glow">
                        <img src="{{ $expUser->profile_photo_url }}" class="w-14 h-14 rounded-full object-cover" alt="{{ $expUser->first_name }}">
                    </div>
                    <span class="text-white/70 text-[10px] font-mono text-center line-clamp-1 w-16">{{ $expUser->first_name }}</span>
                </button>
            @endforeach
        </div>
    @endif
</section>

<div id="experience-viewer-global" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
    <div class="w-full max-w-md bg-black/95 border border-green-400/20 rounded-3xl overflow-hidden">
        <div class="flex items-center justify-between p-3">
            <div id="experience-progress-global" class="flex gap-1 w-full"></div>
            <button id="close-experiences-global" class="text-white/70 hover:text-white px-2">X</button>
        </div>
        <div class="relative w-full h-[460px] bg-black">
            <img id="experience-image-global" class="w-full h-full object-contain hidden" alt="Experience">
            <video id="experience-video-global" class="w-full h-full object-contain hidden" playsinline></video>
            <button id="prev-experience-global" class="absolute left-0 top-0 w-1/3 h-full"></button>
            <button id="next-experience-global" class="absolute right-0 top-0 w-1/3 h-full"></button>
        </div>
        <div class="p-3 text-white/70 text-sm" id="experience-caption-global"></div>
    </div>
</div>

<script>
    const experienceUsers = @json($experienceUsersPayload);
    const viewerGlobal = document.getElementById('experience-viewer-global');
    const closeGlobal = document.getElementById('close-experiences-global');
    const imgGlobal = document.getElementById('experience-image-global');
    const videoGlobal = document.getElementById('experience-video-global');
    const captionGlobal = document.getElementById('experience-caption-global');
    const progressGlobal = document.getElementById('experience-progress-global');
    const prevGlobal = document.getElementById('prev-experience-global');
    const nextGlobal = document.getElementById('next-experience-global');
    let currentUserIndex = 0;
    let currentExperienceIndex = 0;
    let timerGlobal = null;

    function buildProgressGlobal(experiences) {
        progressGlobal.innerHTML = '';
        experiences.forEach((_, i) => {
            const bar = document.createElement('div');
            bar.className = 'h-1 flex-1 bg-white/10 rounded-full overflow-hidden';
            const fill = document.createElement('div');
            fill.className = 'h-full bg-orange-400/80 transition-all';
            fill.style.width = i < currentExperienceIndex ? '100%' : '0%';
            bar.appendChild(fill);
            progressGlobal.appendChild(bar);
        });
    }

    function setProgressGlobal(durationMs) {
        const bars = progressGlobal.querySelectorAll('div > div');
        if (!bars[currentExperienceIndex]) return;
        bars[currentExperienceIndex].style.transition = `width ${durationMs}ms linear`;
        requestAnimationFrame(() => {
            bars[currentExperienceIndex].style.width = '100%';
        });
    }

    function showGlobalExperience(userIndex, expIndex) {
        const user = experienceUsers[userIndex];
        if (!user || !user.experiences.length) return;

        if (expIndex < 0) expIndex = user.experiences.length - 1;
        if (expIndex >= user.experiences.length) expIndex = 0;

        currentUserIndex = userIndex;
        currentExperienceIndex = expIndex;

        const exp = user.experiences[currentExperienceIndex];
        captionGlobal.textContent = exp.caption || '';

        imgGlobal.classList.add('hidden');
        videoGlobal.classList.add('hidden');
        videoGlobal.pause();
        videoGlobal.removeAttribute('src');

        buildProgressGlobal(user.experiences);

        if (exp.media_type === 'video') {
            videoGlobal.src = exp.media_url;
            videoGlobal.classList.remove('hidden');
            videoGlobal.play();
            videoGlobal.onloadedmetadata = () => {
                const durationMs = Math.min((videoGlobal.duration || 8) * 1000, 15000);
                setProgressGlobal(durationMs);
                clearTimeout(timerGlobal);
                timerGlobal = setTimeout(() => showGlobalExperience(userIndex, currentExperienceIndex + 1), durationMs);
            };
            videoGlobal.onended = () => showGlobalExperience(userIndex, currentExperienceIndex + 1);
        } else {
            imgGlobal.src = exp.media_url;
            imgGlobal.classList.remove('hidden');
            clearTimeout(timerGlobal);
            setProgressGlobal(5000);
            timerGlobal = setTimeout(() => showGlobalExperience(userIndex, currentExperienceIndex + 1), 5000);
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            fetch(`/experiences/${exp.id}/view`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            }).catch(() => {});
        }
    }

    document.querySelectorAll('.experience-user').forEach((btn) => {
        btn.addEventListener('click', () => {
            const userId = parseInt(btn.getAttribute('data-user-id'), 10);
            const userIndex = experienceUsers.findIndex(u => u.user_id === userId);
            if (userIndex === -1) return;
            viewerGlobal.classList.remove('hidden');
            viewerGlobal.classList.add('flex');
            showGlobalExperience(userIndex, 0);
        });
    });

    closeGlobal?.addEventListener('click', () => {
        viewerGlobal.classList.add('hidden');
        viewerGlobal.classList.remove('flex');
        clearTimeout(timerGlobal);
        videoGlobal.pause();
    });

    prevGlobal?.addEventListener('click', () => showGlobalExperience(currentUserIndex, currentExperienceIndex - 1));
    nextGlobal?.addEventListener('click', () => showGlobalExperience(currentUserIndex, currentExperienceIndex + 1));
</script>
