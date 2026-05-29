@props(['user', 'size' => 'size-10'])

@php
    // We check if the user has active experiences.
    $hasExperience = $user->has_active_experience ?? false;
@endphp

@if($hasExperience)
    <button onclick="openExperienceViewer({{ $user->id }})" class="relative rounded-full p-[2px] bg-gradient-to-tr from-orange-500 via-green-500 to-orange-500 flex items-center justify-center group shrink-0">
        <img src="{{ $user->profile_photo_url }}" class="{{ $size }} rounded-full border-2 border-black object-cover" alt="{{ $user->first_name }}">
    </button>
@else
    <img src="{{ $user->profile_photo_url }}" class="{{ $size }} rounded-full object-cover shrink-0" alt="{{ $user->first_name }}">
@endif
