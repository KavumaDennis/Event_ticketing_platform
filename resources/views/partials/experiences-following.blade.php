@php
    $experienceUsersPayload = $experienceUsers->map(function($u) use ($seenExperienceIds) {
        $hasUnseen = $u->experiences->contains(function ($exp) use ($seenExperienceIds) {
            return !in_array($exp->id, $seenExperienceIds);
        });

        return [
            'user_id' => $u->id,
            'user_name' => trim($u->first_name . ' ' . $u->last_name),
            'user_photo' => $u->profile_photo_url,
            'has_unseen' => $hasUnseen,
            'experiences' => $u->experiences->map(function($exp) {
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

<div class="mb-6">
    <h2 class="text-sm font-bold text-zinc-500 uppercase tracking-widest mb-2">Experiences</h2>
    @if($experienceUsers->isEmpty())
        <div class="p-4 bg-zinc-900/40 border border-dashed border-zinc-800 rounded-3xl text-center">
            <p class="text-zinc-600 text-sm">No experiences from people you follow.</p>
        </div>
    @else
        <div class="flex gap-4 overflow-x-auto pb-2">
            @foreach($experienceUsersPayload as $expUser)
                <button type="button" class="experience-follow-user flex flex-col items-center gap-2 min-w-[72px]" data-user-id="{{ $expUser['user_id'] }}">
                    <div class="p-[2px] rounded-full {{ $expUser['has_unseen'] ? 'bg-orange-400' : 'bg-white/10' }}">
                        <img src="{{ $expUser['user_photo'] }}" class="w-14 h-14 rounded-full object-cover" alt="">
                    </div>
                    <span class="text-white/70 text-[10px] font-mono text-center line-clamp-1 w-16">{{ $expUser['user_name'] }}</span>
                </button>
            @endforeach
        </div>
    @endif
</div>

<div id="experience-viewer-follow" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
    <div class="w-full max-w-md bg-black/95 border border-green-400/20 rounded-3xl overflow-hidden">
        <div class="flex items-center justify-between p-3">
            <div id="experience-progress-follow" class="flex gap-1 w-full"></div>
            <button id="close-experiences-follow" class="text-white/70 hover:text-white px-2">X</button>
        </div>
        <div class="relative w-full h-[460px] bg-black">
            <img id="experience-image-follow" class="w-full h-full object-contain hidden" alt="Experience">
            <video id="experience-video-follow" class="w-full h-full object-contain hidden" playsinline></video>
            <button id="prev-experience-follow" class="absolute left-0 top-0 w-1/3 h-full"></button>
            <button id="next-experience-follow" class="absolute right-0 top-0 w-1/3 h-full"></button>
        </div>
        <div class="p-3 text-white/70 text-sm" id="experience-caption-follow"></div>
    </div>
</div>

<script>
    const experienceUsersFollow = @json($experienceUsersPayload);
    const viewerFollow = document.getElementById('experience-viewer-follow');
    const closeFollow = document.getElementById('close-experiences-follow');
    const imgFollow = document.getElementById('experience-image-follow');
    const videoFollow = document.getElementById('experience-video-follow');
    const captionFollow = document.getElementById('experience-caption-follow');
    const progressFollow = document.getElementById('experience-progress-follow');
    const prevFollow = document.getElementById('prev-experience-follow');
    const nextFollow = document.getElementById('next-experience-follow');
    let currentUserIndexFollow = 0;
    let currentExperienceIndexFollow = 0;
    let timerFollow = null;

    function buildProgressFollow(experiences) {
        progressFollow.innerHTML = '';
        experiences.forEach((_, i) => {
            const bar = document.createElement('div');
            bar.className = 'h-1 flex-1 bg-white/10 rounded-full overflow-hidden';
            const fill = document.createElement('div');
            fill.className = 'h-full bg-orange-400/80 transition-all';
            fill.style.width = i < currentExperienceIndexFollow ? '100%' : '0%';
            bar.appendChild(fill);
            progressFollow.appendChild(bar);
        });
    }

    function setProgressFollow(durationMs) {
        const bars = progressFollow.querySelectorAll('div > div');
        if (!bars[currentExperienceIndexFollow]) return;
        bars[currentExperienceIndexFollow].style.transition = `width ${durationMs}ms linear`;
        requestAnimationFrame(() => {
            bars[currentExperienceIndexFollow].style.width = '100%';
        });
    }

    function showFollowExperience(userIndex, expIndex) {
        const user = experienceUsersFollow[userIndex];
        if (!user || !user.experiences.length) return;

        if (expIndex < 0) expIndex = user.experiences.length - 1;
        if (expIndex >= user.experiences.length) expIndex = 0;

        currentUserIndexFollow = userIndex;
        currentExperienceIndexFollow = expIndex;

        const exp = user.experiences[currentExperienceIndexFollow];
        captionFollow.textContent = exp.caption || '';

        imgFollow.classList.add('hidden');
        videoFollow.classList.add('hidden');
        videoFollow.pause();
        videoFollow.removeAttribute('src');

        buildProgressFollow(user.experiences);

        if (exp.media_type === 'video') {
            videoFollow.src = exp.media_url;
            videoFollow.classList.remove('hidden');
            videoFollow.play();
            videoFollow.onloadedmetadata = () => {
                const durationMs = 30000;
                setProgressFollow(durationMs);
                clearTimeout(timerFollow);
                timerFollow = setTimeout(() => showFollowExperience(userIndex, currentExperienceIndexFollow + 1), durationMs);
            };
            videoFollow.onended = () => showFollowExperience(userIndex, currentExperienceIndexFollow + 1);
        } else {
            imgFollow.src = exp.media_url;
            imgFollow.classList.remove('hidden');
            clearTimeout(timerFollow);
            setProgressFollow(30000);
            timerFollow = setTimeout(() => showFollowExperience(userIndex, currentExperienceIndexFollow + 1), 30000);
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

    document.querySelectorAll('.experience-follow-user').forEach((btn) => {
        btn.addEventListener('click', () => {
            const userId = parseInt(btn.getAttribute('data-user-id'), 10);
            const userIndex = experienceUsersFollow.findIndex(u => u.user_id === userId);
            if (userIndex === -1) return;
            viewerFollow.classList.remove('hidden');
            viewerFollow.classList.add('flex');
            showFollowExperience(userIndex, 0);
        });
    });

    closeFollow?.addEventListener('click', () => {
        viewerFollow.classList.add('hidden');
        viewerFollow.classList.remove('flex');
        clearTimeout(timerFollow);
        videoFollow.pause();
    });

    prevFollow?.addEventListener('click', () => showFollowExperience(currentUserIndexFollow, currentExperienceIndexFollow - 1));
    nextFollow?.addEventListener('click', () => showFollowExperience(currentUserIndexFollow, currentExperienceIndexFollow + 1));
</script>
