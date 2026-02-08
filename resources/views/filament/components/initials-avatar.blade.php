@php
    $user = filament()->auth()->user();
    $profilePhotoUrl = $user->profile_photo_url ?? $user->avatar ?? null;
    $initials = \App\Support\UserInitials::generate($user->name ?? null);
@endphp

<div class="flex items-center rtl:mr-2 ltr:ml-2">
    @if($profilePhotoUrl)
        <img 
            src="{{ $profilePhotoUrl }}" 
            alt="{{ $user->name }}"
            class="w-8 h-8 rounded-full object-cover"
        />
    @else
        <div class="w-8 h-8 rounded-full bg-neutral-900 dark:bg-neutral-900 flex items-center justify-center text-white text-xs font-semibold">
            {{ $initials }}
        </div>
    @endif
</div>
