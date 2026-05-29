@extends('layouts.dashboard')

@section('title', 'Messages')

@section('content')
<div class="max-w-7xl mx-auto h-full flex gap-5" x-data="chatSystem()">
    
    {{-- Conversations Sidebar --}}
    <div class="w-1/3 bg-green-400/10 border border-green-400/10 rounded-2xl p-3 flex flex-col h-full">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-xs p-1 font-mono font-medium bg-orange-400 rounded-2xl w-fit text-black/90 tracking-tighter">Direct messages</h2>
            <button @click="openNewMessage()" class="size-9 rounded-full bg-black/60 border border-white/15 hover:border-orange-400/40 hover:bg-white/5 text-orange-400 flex items-center justify-center transition" title="New message">
                <i class="fa-regular fa-pen-to-square"></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto space-y-2 custom-scrollbar">
            @forelse($conversations as $conv)
                @php
                    $otherUser = $conv->is_group ? null : $conv->participants->where('user_id', '!=', auth()->id())->first()?->user;
                    $name = $conv->is_group ? $conv->name : ($otherUser ? $otherUser->first_name . ' ' . $otherUser->last_name : 'Unknown');
                    $avatar = $conv->is_group 
                        ? ($conv->event?->event_image ? asset('storage/'.$conv->event->event_image) : asset('default.png'))
                        : ($otherUser?->profile_pic ? asset('storage/'.$otherUser->profile_pic) : asset('default.png'));
                @endphp
                <button 
                    @click="loadConversation({{ $conv->id }}, '{{ $name }}', '{{ $avatar }}', {{ $conv->is_group ? 'true' : 'false' }}, {{ $conv->is_group ? 'null' : (int)($otherUser?->id ?? 0) }})"
                    :class="activeConversationId === {{ $conv->id }} ? 'bg-orange-400/25 border-orange-400/30' : 'border-orange-400/30 bg-white/5'"
                    class="w-full flex items-center gap-3 p-3 rounded-xl border transition-all text-left">
                    
                    <div class="relative size-12 rounded-full overflow-hidden shrink-0 border border-orange-400/70">
                        <img src="{{ $avatar }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="text-orange-400 font-medium text-sm truncate">{{ $name }}</h4>
                            @if($conv->lastMessage)
                                <span class="text-[10px] text-zinc-500 font-mono">{{ $conv->lastMessage->created_at->shortAbsoluteDiffForHumans() }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-white/50 truncate font-mono">
                            @if($conv->lastMessage)
                                @if($conv->lastMessage->sender_id === auth()->id())
                                    <span class="text-orange-400/70">You:</span> 
                                @endif
                                {{ $conv->lastMessage->type === 'text' ? $conv->lastMessage->body : '[' . ucfirst($conv->lastMessage->type) . ']' }}
                            @else
                                No messages yet
                            @endif
                        </p>
                    </div>
                </button>
            @empty
                <div class="text-center py-10 opacity-50">
                    <i class="fa-solid fa-comments text-4xl mb-3 text-zinc-600"></i>
                    <p class="text-xs font-mono uppercase tracking-widest text-zinc-400">No conversations</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Area --}}
    <div class="flex-1 bg-green-400/10 border border-green-400/10 rounded-2xl flex flex-col h-full overflow-hidden relative">
        
        <template x-if="!activeConversationId">
            <div class="flex-1 flex flex-col items-center justify-center opacity-50">
                <i class="fa-regular fa-paper-plane text-5xl mb-4 text-orange-400/50"></i>
                <h3 class="text-white font-bold text-xl">Your Messages</h3>
                <p class="text-sm font-mono text-zinc-500 mt-2">Select a conversation to start chatting</p>
            </div>
        </template>

        <template x-if="activeConversationId">
            <div class="flex flex-col h-full">
                {{-- Chat Header --}}
                <div class="p-4 border-b z-50 flex items-center justify-between bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border-green-400/30 backdrop-blur-[1px] overflow-hidden shadow-2xl relative">
                    <div class="flex items-center gap-3">
                        <button type="button" class="relative" @click="openProfilePreview()">
                            <img :src="activeConversationAvatar" class="size-10 rounded-full object-cover border border-orange-400/30">
                        </button>
                        <div>
                            <h3 class="text-white text-[11px] font-bold uppercase" x-text="activeConversationName"></h3>
                            <div class="text-[10px] text-zinc-500 font-mono uppercase">
                                <template x-if="isPeerTyping">
                                    <div class="flex items-center gap-1">
                                        <span class="text-white/40">typing</span>
                                        <span class="inline-flex gap-1">
                                            <span class="size-1.5 rounded-full bg-white/40 animate-bounce [animation-delay:-0.2s]"></span>
                                            <span class="size-1.5 rounded-full bg-white/40 animate-bounce [animation-delay:-0.1s]"></span>
                                            <span class="size-1.5 rounded-full bg-white/40 animate-bounce"></span>
                                        </span>
                                    </div>
                                </template>
                                <template x-if="!isPeerTyping">
                                    <span x-text="presenceText || (activeConversationIsGroup ? 'Group Chat' : 'Direct Message')"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                    <button @click="toggleHeaderMenu()" class="size-8 flex items-center justify-center rounded-full hover:bg-white/10 text-white/50 transition-colors">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>

                    <div x-show="headerMenuOpen" x-transition.opacity x-cloak class="absolute right-6 top-16 z-[10000] w-56 bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] rounded-2xl overflow-hidden shadow-2xl">
                        <button type="button" @click="closeChat()" class="w-full px-4 py-3 text-left hover:bg-white/5 transition text-sm text-white/80 flex items-center gap-3">
                            <i class="fa-solid fa-circle-xmark text-white/40"></i>
                            <span>Close chat</span>
                        </button>
                        <button type="button" @click="openEventPicker()" class="w-full px-4 py-3 text-left hover:bg-white/5 transition text-sm text-white/80 flex items-center gap-3">
                            <i class="fa-solid fa-calendar-plus text-white/40"></i>
                            <span>Recommend event</span>
                        </button>
                        <button type="button" @click="$refs.fileInput.click()" class="w-full px-4 py-3 text-left hover:bg-white/5 transition text-sm text-white/80 flex items-center gap-3">
                            <i class="fa-solid fa-paperclip text-white/40"></i>
                            <span>Send attachment</span>
                        </button>
                        <button type="button" @click="clearMessages()" class="w-full px-4 py-3 text-left hover:bg-white/5 transition text-sm text-white/80 flex items-center gap-3">
                            <i class="fa-solid fa-broom text-white/40"></i>
                            <span>Clear messages</span>
                        </button>
                        <button type="button" @click="deleteChat()" class="w-full px-4 py-3 text-left hover:bg-white/5 transition text-sm text-red-300 flex items-center gap-3">
                            <i class="fa-solid fa-trash text-red-300/70"></i>
                            <span x-text="activeConversationIsGroup ? 'Leave group' : 'Delete chat'"></span>
                        </button>
                    </div>
                </div>

                {{-- Messages List --}}
                <div id="messages-container" @scroll="onMessagesScroll($event)" class="flex-1 overflow-y-auto p-4 space-y-4 z-40 custom-scrollbar">
                    {{-- Messages will be dynamically loaded here. For now, we mock the UI for the active state --}}
                    <div class="flex flex-col items-center justify-center h-full text-zinc-500 text-sm font-mono" x-show="isLoadingMessages && messages.length === 0">
                        <div class="size-8 border-2 border-orange-400/20 border-t-orange-400 rounded-full animate-spin"></div>
                    </div>

                    <template x-for="(msg, idx) in messages" :key="msg.id">
                        <div>
                            <template x-if="shouldShowDateSeparator(idx)">
                                <div class="flex items-center justify-center py-2">
                                    <div class="text-[10px] font-mono uppercase tracking-widest text-white/40 px-3 py-1 rounded-full border border-white/10 bg-black/30" x-text="formatDateSeparator(messages[idx].created_at)"></div>
                                </div>
                            </template>

                            <div :class="msg.sender_id === {{ auth()->id() }} ? 'flex justify-end' : 'flex justify-start'">
                                <div :class="msg.sender_id === {{ auth()->id() }} ? 'bg-white/10 border border-orange-400/50 text-orange-400 rounded-tl-xl rounded-tr-xl rounded-bl-xl' : 'bg-white/10 border border-green-400/50 text-green-400 rounded-tl-xl rounded-tr-xl rounded-br-xl'" class="max-w-[70%] p-2 px-4 shadow-md relative group">
                                <div class="absolute -top-4 right-2 hidden group-hover:flex items-center gap-1 bg-black/70 border border-white/10 rounded-full px-2 py-1">
                                    <template x-for="e in quickReactions" :key="e">
                                        <button type="button" @click="reactToMessage(msg, e)" class="text-sm hover:scale-110 transition" x-text="e"></button>
                                    </template>
                                </div>
                                <template x-if="msg.reply_to">
                                    <div class="mb-2 px-3 py-2 rounded-xl border border-white/10 bg-black/20">
                                        <div class="text-[10px] font-mono opacity-70" x-text="'Replying to ' + msg.reply_to.sender_name"></div>
                                        <div class="text-xs opacity-80 truncate" x-text="msg.reply_to.type === 'text' ? msg.reply_to.body : '[' + msg.reply_to.type + ']'"></div>
                                    </div>
                                </template>
                                <template x-if="msg.type === 'text'">
                                    <p class="text-sm" x-text="msg.body"></p>
                                </template>
                                <template x-if="msg.type === 'event_recommendation'">
                                    <div class="flex flex-col gap-2">
                                        <p class="text-xs font-bold mb-1 opacity-70">Recommended an Event:</p>
                                        <a :href="msg.event?.url || ('/events/' + (msg.event?.id || ''))" class="bg-black/40 rounded-2xl overflow-hidden border border-white/10 hover:border-orange-400/50 transition block">
                                            <div class="flex gap-3 p-3">
                                                <img :src="msg.event?.banner" class="size-14 rounded-xl object-cover border border-white/10" />
                                                <div class="min-w-0">
                                                    <h4 class="font-bold text-sm truncate" x-text="msg.event?.title || 'Event'"></h4>
                                                    <p class="text-[10px] font-mono opacity-70 truncate" x-text="(msg.event?.date || '') + (msg.event?.location ? ' • ' + msg.event.location : '')"></p>
                                                    <div class="mt-2 inline-flex items-center gap-2 text-[10px] font-mono px-3 py-1 rounded-full bg-orange-400 text-black">
                                                        <span>View</span>
                                                        <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </template>
                                <template x-if="(msg.attachments || []).length">
                                    <div class="mt-2 space-y-2">
                                        <template x-for="a in msg.attachments" :key="a.id">
                                            <div>
                                                <template x-if="(a.type || '').startsWith('image/')">
                                                    <button type="button" class="block w-full" @click="openMediaViewer(a.view_url || a.url, 'image')">
                                                        <img :src="a.view_url || a.url" class="w-full max-h-72 object-cover border border-white/10 hover:border-orange-400/40 transition" />
                                                    </button>
                                                </template>

                                                <template x-if="(a.type || '').startsWith('video/')">
                                                    <div class="rounded-2xl overflow-hidden border border-white/10 hover:border-orange-400/40 transition bg-black/30" x-data="videoPlayer(a.view_url || a.url)">
                                                        <div class="relative">
                                                            <video x-ref="video" class="w-full max-h-80 object-contain" playsinline :src="src" @click="toggle()"></video>
                                                            <button type="button" class="absolute inset-0 flex items-center justify-center" @click.stop="toggle()">
                                                                <div class="size-14 rounded-full bg-black/60 border border-white/10 flex items-center justify-center">
                                                                    <i class="fa-solid text-white/80" :class="playing ? 'fa-pause' : 'fa-play'"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="px-3 py-2 flex items-center gap-3 bg-black/40">
                                                            <span class="text-[10px] font-mono text-white/60 w-10 text-right" x-text="formatTime(current)"></span>
                                                            <input type="range" min="0" :max="duration" step="0.1" x-model.number="current" @input="seek()" class="flex-1 accent-orange-400" />
                                                            <span class="text-[10px] font-mono text-white/40 w-10" x-text="formatTime(duration)"></span>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="(a.type || '').startsWith('audio/')">
                                                    <div class="p-3 rounded-2xl border border-white/10 bg-black/20 w-[320px] max-w-full" x-data="voiceNotePlayer(a.view_url || a.url)">
                                                        <div class="flex items-center gap-3">
                                                            <button type="button" @click="toggle()" class="size-10 rounded-full bg-white/10 hover:bg-white/15 border border-white/10 flex items-center justify-center">
                                                                <i class="fa-solid text-white/80" :class="playing ? 'fa-pause' : 'fa-play'"></i>
                                                            </button>
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-1 h-6">
                                                                    <template x-for="(h, i) in bars" :key="i">
                                                                        <div class="w-1 rounded-full" :class="i <= activeBar ? 'bg-white/90' : 'bg-white/30'" :style="`height:${h}px`"></div>
                                                                    </template>
                                                                </div>
                                                                <input type="range" min="0" :max="duration" step="0.05" x-model.number="current" @input="seek()" class="w-full accent-orange-400 mt-1">
                                                            </div>
                                                            <div class="text-[11px] font-mono text-white/70 w-12 text-right" x-text="formatTime(remaining)"></div>
                                                        </div>
                                                        <div class="mt-2 text-[10px] font-mono opacity-60 truncate">
                                                            <button type="button" class="hover:underline" @click="openMediaViewer(src,'audio')">Open</button>
                                                            <span class="opacity-40">•</span>
                                                            <a :href="a.url" class="hover:underline">Download</a>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="!((a.type || '').startsWith('image/') || (a.type || '').startsWith('video/') || (a.type || '').startsWith('audio/'))">
                                                    <a :href="a.url" class="flex items-center gap-3 p-2 rounded-xl border border-white/10 hover:border-orange-400/40 bg-black/20 transition">
                                                        <div class="size-9 rounded-xl bg-white/10 flex items-center justify-center">
                                                            <i class="fa-solid fa-file-lines text-orange-400"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="text-xs font-bold truncate">Attachment</div>
                                                            <div class="text-[10px] font-mono opacity-60 truncate" x-text="a.type"></div>
                                                        </div>
                                                        <i class="fa-solid fa-download opacity-70"></i>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <template x-if="(msg.reactions || []).length">
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <template x-for="r in msg.reactions" :key="r.emoji">
                                            <button type="button" @click="reactToMessage(msg, r.emoji)" class="px-2 py-1 rounded-full border border-white/10 bg-black/20 text-[10px] font-mono hover:border-orange-400/40 transition">
                                                <span x-text="r.emoji"></span>
                                                <span class="opacity-70" x-text="r.count"></span>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                                <span class="text-[9px] opacity-50 block mt-1 text-right font-mono" x-text="msg.time"></span>
                            </div>
                        </div>
                        </div>
                    </template>
                </div>

                {{-- Input Area --}}
                <div class="p-4 border-t bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border-green-400/30 backdrop-blur-[1px] overflow-hidden shadow-2xl">
                    <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                        <input x-ref="fileInput" type="file" multiple class="hidden" @change="sendAttachments($event)">
                        <button type="button" @click="$refs.fileInput.click()" class="size-10 shrink-0 rounded-full bg-white/5 hover:bg-white/10 text-orange-400 flex items-center justify-center transition">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                        <button type="button" @click="openEventPicker()" class="size-10 shrink-0 rounded-full bg-white/5 hover:bg-white/10 text-orange-400 flex items-center justify-center transition" title="Recommend event">
                            <i class="fa-solid fa-calendar-plus"></i>
                        </button>
                        <button type="button" @click="toggleEmojiPicker()" class="size-10 shrink-0 rounded-full bg-white/5 hover:bg-white/10 text-orange-400 flex items-center justify-center transition" title="Emoji">
                            <i class="fa-regular fa-face-smile"></i>
                        </button>
                        <input type="text" x-model="newMessage" @input.debounce.250ms="notifyTyping()" placeholder="Type a message..." class="flex-1 bg-white/5 border border-white/10 rounded-full px-5 py-3 text-sm text-white focus:outline-none focus:border-orange-400/50 transition">
                        <button type="button" @click="toggleRecording()" class="size-10 shrink-0 rounded-full bg-white/5 hover:bg-white/10 text-orange-400 flex items-center justify-center transition" :class="isRecording ? 'bg-red-500/20 border border-red-500/40 text-red-400' : ''" title="Voice note">
                            <i class="fa-solid fa-microphone"></i>
                        </button>
                        <button type="submit" :disabled="!newMessage.trim()" class="size-10 shrink-0 rounded-full bg-orange-400 hover:bg-orange-500 text-black flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </template>

        {{-- Emoji picker popover --}}
        <div x-show="emojiOpen" x-transition.opacity x-cloak class="absolute bottom-20 left-6 z-40 bg-zinc-950/95 border border-white/10 rounded-2xl p-3 w-[260px] shadow-2xl">
            <div class="grid grid-cols-7 gap-2">
                <template x-for="e in emojis" :key="e">
                    <button type="button" @click="insertEmoji(e)" class="size-8 rounded-xl hover:bg-white/10 transition text-lg flex items-center justify-center">
                        <span x-text="e"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Event picker modal --}}
        <div x-show="eventPickerOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closeEventPicker()"></div>

            <div class="relative w-full max-w-2xl bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] overflow-hidden shadow-2xl">
                <div class="px-5 py-4 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-white font-bold">Recommend Event</h3>
                    <button @click="closeEventPicker()" class="size-9 rounded-full hover:bg-white/10 text-white/60 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="px-5 py-4 border-b border-white/10 flex items-center gap-3">
                    <i class="fa-solid fa-magnifying-glass text-white/40"></i>
                    <input x-model="eventSearch" @input.debounce.250ms="fetchEvents()" type="text" placeholder="Search events..." class="flex-1 bg-transparent outline-none text-white/90 placeholder:text-white/30 text-sm">
                </div>

                <div class="max-h-[55vh] overflow-y-auto custom-scrollbar">
                    <template x-if="eventLoading">
                        <div class="px-5 py-6 space-y-3">
                            <div class="h-14 rounded-2xl bg-white/5 animate-pulse"></div>
                            <div class="h-14 rounded-2xl bg-white/5 animate-pulse"></div>
                            <div class="h-14 rounded-2xl bg-white/5 animate-pulse"></div>
                        </div>
                    </template>

                    <template x-if="!eventLoading && eventResults.length === 0">
                        <div class="px-5 py-10 text-center text-white/40 text-sm">No events found</div>
                    </template>

                    <template x-for="ev in eventResults" :key="ev.id">
                        <button type="button" @click="chooseEvent(ev.id)" class="w-full px-5 py-3 flex items-center gap-3 hover:bg-white/5 transition text-left">
                            <img :src="ev.banner" class="size-12 rounded-2xl object-cover border border-white/10">
                            <div class="flex-1 min-w-0">
                                <div class="text-orange-400 text-sm font-medium truncate" x-text="ev.title"></div>
                                <div class="text-white/40 text-xs font-mono truncate" x-text="(ev.date || '') + (ev.location ? ' • ' + ev.location : '')"></div>
                            </div>
                            <div class="text-[10px] font-mono px-3 py-1 rounded-full bg-white/5 border border-white/10 text-white/60">
                                <span x-text="'❤ ' + (ev.likes ?? 0)"></span>
                            </div>
                            <div class="size-6 rounded-full border border-white/20 flex items-center justify-center ml-2">
                                <div class="size-3 rounded-full bg-orange-400" x-show="selectedEventId === ev.id"></div>
                            </div>
                        </button>
                    </template>
                </div>

                <div class="p-4 border-t border-white/10 bg-black/30">
                    <button @click="sendEventRecommendation()" :disabled="!selectedEventId || eventLoading" class="w-full rounded-lg p-3 bg-orange-400 text-black border border-orange-400 hover:bg-orange-500 hover:text-black transition-all text-[10px] font-bold uppercase disabled:opacity-50 disabled:cursor-not-allowed">
                        Send Recommendation
                    </button>
                </div>
            </div>
        </div>

        {{-- Media viewer modal --}}
        <div x-show="mediaViewerOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeMediaViewer()"></div>
            <div class="relative w-full max-w-4xl">
                <button @click="closeMediaViewer()" class="absolute -top-12 right-0 size-10 rounded-full bg-black/60 border border-white/10 hover:bg-black/80 text-white/70 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <template x-if="mediaViewerType === 'image'">
                    <img :src="mediaViewerUrl" class="w-full max-h-[80vh] object-contain rounded-3xl border border-white/10 bg-black/30" />
                </template>

                <template x-if="mediaViewerType === 'audio'">
                    <div class="w-full bg-zinc-950/90 border border-white/10 rounded-3xl p-4">
                        <audio class="w-full" controls :src="mediaViewerUrl"></audio>
                    </div>
                </template>
            </div>
        </div>

        {{-- Profile / Group info modal --}}
        <div x-show="profileModalOpen" x-transition.opacity class="fixed inset-0 z-[10050] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeProfilePreview()"></div>
            <div class="relative w-full max-w-xl bg-zinc-950/90 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
                <div class="px-5 py-4 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-white font-bold" x-text="profileInfo?.is_group ? 'Group info' : 'Profile'"></h3>
                    <button @click="closeProfilePreview()" class="size-9 rounded-full hover:bg-white/10 text-white/60 transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="p-5" x-show="profileInfoLoading">
                    <div class="h-14 rounded-2xl bg-white/5 animate-pulse"></div>
                    <div class="mt-3 h-10 rounded-2xl bg-white/5 animate-pulse"></div>
                </div>

                <div class="p-5 space-y-4" x-show="!profileInfoLoading && profileInfo">
                    <div class="flex items-center gap-4">
                        <img :src="activeConversationAvatar" class="size-16 rounded-3xl object-cover border border-white/10" />
                        <div class="min-w-0">
                            <div class="text-white font-black text-lg truncate" x-text="activeConversationName"></div>
                            <template x-if="profileInfo?.event">
                                <a :href="profileInfo.event.url" class="text-[10px] font-mono uppercase tracking-widest text-orange-400 hover:underline" x-text="'Event: ' + profileInfo.event.title"></a>
                            </template>
                            <template x-if="!profileInfo?.is_group && profileInfo?.participants?.length">
                                <a :href="profileInfo.participants.find(p => p.id !== {{ auth()->id() }} )?.profile_url" class="text-[10px] font-mono uppercase tracking-widest text-white/40 hover:text-white/70 transition">View full profile</a>
                            </template>
                        </div>
                    </div>

                    <template x-if="profileInfo?.is_group">
                        <div class="rounded-2xl border border-white/10 overflow-hidden">
                            <div class="px-4 py-3 bg-black/30 text-[10px] font-mono uppercase tracking-widest text-white/50">
                                Participants (<span x-text="profileInfo.participants.length"></span>)
                            </div>
                            <div class="max-h-72 overflow-y-auto custom-scrollbar">
                                <template x-for="p in profileInfo.participants" :key="p.id">
                                    <a :href="p.profile_url" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition">
                                        <img :src="p.avatar" class="size-10 rounded-full object-cover border border-white/10" />
                                        <div class="min-w-0 flex-1">
                                            <div class="text-white/90 text-sm font-bold truncate" x-text="p.name"></div>
                                            <div class="text-white/40 text-xs font-mono truncate" x-text="p.username ? '@' + p.username : ''"></div>
                                        </div>
                                        <div class="text-[10px] font-mono px-2 py-1 rounded-full bg-white/5 border border-white/10 text-white/60" x-text="p.role"></div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    {{-- New message modal --}}
    <div x-show="newMessageModalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closeNewMessage()"></div>

        <div class="relative w-full max-w-xl bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] overflow-hidden shadow-2xl">
            <div class="px-5 py-4 flex items-center justify-between border-b border-white/10">
                <h3 class="text-white font-bold">New message</h3>
                <button @click="closeNewMessage()" class="size-9 rounded-full hover:bg-white/10 text-white/60 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="px-5 py-4 border-b border-white/10 flex items-center gap-3">
                <span class="text-white/70 text-sm font-bold">To:</span>
                <input x-model="recipientSearch" @input.debounce.200ms="filterRecipients()" type="text" placeholder="Search..." class="flex-1 bg-transparent outline-none text-white/90 placeholder:text-white/30 text-sm">
            </div>

            <div class="max-h-[45vh] overflow-y-auto custom-scrollbar">
                <div class="px-5 py-3 text-[10px] font-mono uppercase tracking-widest text-white/40">Suggested</div>

                <template x-if="recipientsLoading">
                    <div class="px-5 pb-5 space-y-3">
                        <div class="h-10 rounded-xl bg-white/5 animate-pulse"></div>
                        <div class="h-10 rounded-xl bg-white/5 animate-pulse"></div>
                        <div class="h-10 rounded-xl bg-white/5 animate-pulse"></div>
                    </div>
                </template>

                <template x-if="!recipientsLoading && filteredRecipients.length === 0">
                    <div class="px-5 pb-6 text-center text-white/40 text-sm">No matches</div>
                </template>

                <template x-for="u in filteredRecipients" :key="u.id">
                    <button type="button" @click="toggleRecipient(u.id)" class="w-full px-5 py-3 flex items-center gap-3 hover:bg-white/5 transition text-left">
                        <img :src="u.avatar" class="size-10 rounded-full object-cover border border-white/10">
                        <div class="flex-1 min-w-0">
                            <div class="text-white/90 text-sm font-bold truncate" x-text="u.name"></div>
                            <div class="text-white/40 text-xs font-mono truncate" x-text="u.username ? '@' + u.username : ''"></div>
                        </div>
                        <div class="size-6 rounded-full border border-white/20 flex items-center justify-center">
                            <div class="size-3 rounded-full bg-orange-400" x-show="selectedRecipientId === u.id"></div>
                        </div>
                    </button>
                </template>
            </div>

            <div class="p-4 border-t border-white/10 bg-black/30">
                <button @click="startChatFromModal()" :disabled="!selectedRecipientId || recipientsLoading || startingChat" class="w-full rounded-lg p-3 bg-orange-400 text-black border border-orange-400 hover:bg-orange-500 hover:text-black transition-all text-[10px] font-bold uppercase disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!startingChat">Chat</span>
                    <span x-show="startingChat">Starting…</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.voiceNotePlayer = function(src) {
        return {
            src,
            audio: null,
            playing: false,
            duration: 0,
            current: 0,
            bars: Array.from({ length: 24 }, () => 6 + Math.round(Math.random() * 18)),
            raf: null,
            get remaining() {
                return Math.max(0, (this.duration || 0) - (this.current || 0));
            },
            get activeBar() {
                if (!this.duration) return -1;
                return Math.min(this.bars.length - 1, Math.floor((this.current / this.duration) * this.bars.length));
            },
            init() {
                this.audio = new Audio(this.src);
                this.audio.preload = 'metadata';
                this.audio.addEventListener('loadedmetadata', () => {
                    this.duration = this.audio.duration || 0;
                });
                this.audio.addEventListener('ended', () => {
                    this.playing = false;
                    this.current = this.duration || 0;
                    cancelAnimationFrame(this.raf);
                });
            },
            toggle() {
                if (!this.audio) this.init();
                if (this.playing) {
                    this.audio.pause();
                    this.playing = false;
                    cancelAnimationFrame(this.raf);
                    return;
                }
                this.audio.play();
                this.playing = true;
                this.tick();
            },
            tick() {
                this.current = this.audio?.currentTime || 0;
                if (this.playing) {
                    this.raf = requestAnimationFrame(() => this.tick());
                }
            },
            seek() {
                if (!this.audio) this.init();
                this.audio.currentTime = this.current || 0;
            },
            formatTime(s) {
                const sec = Math.max(0, Math.floor(s || 0));
                const m = Math.floor(sec / 60);
                const r = sec % 60;
                return `${m}:${String(r).padStart(2,'0')}`;
            },
        };
    };

    window.videoPlayer = function(src) {
        return {
            src,
            playing: false,
            duration: 0,
            current: 0,
            raf: null,
            init() {
                const v = this.$refs.video;
                if (!v) return;
                v.addEventListener('loadedmetadata', () => {
                    this.duration = v.duration || 0;
                });
                v.addEventListener('ended', () => {
                    this.playing = false;
                    cancelAnimationFrame(this.raf);
                });
            },
            toggle() {
                const v = this.$refs.video;
                if (!v) return;
                if (v.paused) {
                    v.play();
                    this.playing = true;
                    this.tick();
                } else {
                    v.pause();
                    this.playing = false;
                    cancelAnimationFrame(this.raf);
                }
            },
            tick() {
                const v = this.$refs.video;
                if (!v) return;
                this.current = v.currentTime || 0;
                if (this.playing) {
                    this.raf = requestAnimationFrame(() => this.tick());
                }
            },
            seek() {
                const v = this.$refs.video;
                if (!v) return;
                v.currentTime = this.current || 0;
            },
            formatTime(s) {
                const sec = Math.max(0, Math.floor(s || 0));
                const m = Math.floor(sec / 60);
                const r = sec % 60;
                return `${m}:${String(r).padStart(2,'0')}`;
            },
        };
    };

    document.addEventListener('alpine:init', () => {
        Alpine.data('chatSystem', () => ({
            activeConversationId: null,
            activeConversationName: '',
            activeConversationAvatar: '',
            activeConversationIsGroup: false,
            messages: [],
            newMessage: '',
            nextPageUrl: null,
            isLoadingMessages: false,
            isPeerTyping: false,
            typingTimeout: null,
            presenceText: '',
            presenceUserId: null,
            presenceTimer: null,
            headerMenuOpen: false,

            // profile previews
            profileModalOpen: false,
            profileInfoLoading: false,
            profileInfo: null,

            // New message modal state
            newMessageModalOpen: false,
            recipients: [],
            filteredRecipients: [],
            recipientsLoading: false,
            recipientSearch: '',
            selectedRecipientId: null,
            startingChat: false,

            echoChannelName: null,

            // Event picker
            eventPickerOpen: false,
            eventSearch: '',
            eventResults: [],
            eventLoading: false,
            selectedEventId: null,

            // Emoji picker
            emojiOpen: false,
            emojis: ['😀','😁','😂','🤣','😅','😊','😍','😘','😎','🤝','🙏','🔥','💯','❤️','🎉','🥳','🤩','😢','😡','👍','👎'],
            quickReactions: ['❤️','😂','🔥','👍'],

            // Voice notes
            isRecording: false,
            mediaRecorder: null,
            recordChunks: [],

            // Media viewer
            mediaViewerOpen: false,
            mediaViewerUrl: '',
            mediaViewerType: 'image',

            loadConversation(id, name, avatar, isGroup, otherUserId = null) {
                this.activeConversationId = id;
                this.activeConversationName = name;
                this.activeConversationAvatar = avatar;
                this.activeConversationIsGroup = isGroup;
                this.presenceUserId = otherUserId;
                this.presenceText = '';
                this.headerMenuOpen = false;
                this.messages = [];
                this.nextPageUrl = `/user/dashboard/messages/conversations/${id}/messages`;

                this.subscribeToConversation(id);
                this.fetchMessages(true);
                this.refreshPresence();
            },

            async fetchMessages(reset = false) {
                if (!this.activeConversationId || !this.nextPageUrl || this.isLoadingMessages) return;
                this.isLoadingMessages = true;

                try {
                    const response = await fetch(this.nextPageUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();

                    const incoming = (data.data || []).reverse(); // API returns latest-first

                    if (reset) {
                        this.messages = incoming;
                        this.scrollToBottom();
                        this.markConversationRead();
                    } else {
                        const container = document.getElementById('messages-container');
                        const previousScrollHeight = container ? container.scrollHeight : 0;
                        this.messages = [...incoming, ...this.messages];
                        this.$nextTick(() => {
                            if (container) {
                                container.scrollTop = container.scrollHeight - previousScrollHeight;
                            }
                        });
                    }

                    this.nextPageUrl = data.next_page_url;
                } catch (err) {
                    console.error(err);
                } finally {
                    this.isLoadingMessages = false;
                }
            },

            async sendMessage() {
                if (!this.activeConversationId) return;
                if (!this.newMessage.trim()) return;

                const msgText = this.newMessage;
                this.newMessage = '';

                const currentUser = {
                    id: {{ auth()->id() ?? 0 }},
                    name: @json(trim((auth()->user()?->first_name ?? '') . ' ' . (auth()->user()?->last_name ?? ''))),
                    avatar: @json(auth()->user()?->profile_pic ? asset('storage/'.auth()->user()->profile_pic) : asset('default.png')),
                };

                const optimistic = {
                    id: 'tmp_' + Date.now(),
                    sender_id: {{ auth()->id() ?? 0 }},
                    body: msgText,
                    type: 'text',
                    time: 'Now',
                    is_mine: true,
                    sender: {
                        id: currentUser.id,
                        name: currentUser.name || 'You',
                        avatar: currentUser.avatar,
                    },
                    attachments: [],
                    event: null,
                    reply_to: null,
                };

                this.messages.push(optimistic);
                this.scrollToBottom();

                const formData = new FormData();
                formData.append('type', 'text');
                formData.append('body', msgText);

                try {
                    const response = await fetch(`/messages/${this.activeConversationId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data && data.message) {
                        const idx = this.messages.findIndex(m => m.id === optimistic.id);
                        if (idx !== -1) this.messages[idx] = data.message;
                        this.markConversationRead();
                    }
                } catch (err) {
                    console.error(err);
                }
            },

            async sendAttachments(e) {
                const input = e?.target;
                const files = input?.files ? Array.from(input.files) : [];
                if (!this.activeConversationId || files.length === 0) return;

                // reset input so selecting same file twice works
                input.value = '';

                const optimistic = {
                    id: 'tmp_file_' + Date.now(),
                    sender_id: {{ auth()->id() ?? 0 }},
                    body: null,
                    type: 'attachment',
                    time: 'Now',
                    is_mine: true,
                    attachments: files.map((f, idx) => ({ id: 'tmp_a_' + idx, type: f.type || 'application/octet-stream', size: f.size, url: '#', view_url: URL.createObjectURL(f) })),
                    event: null,
                    reply_to: null,
                };

                this.messages.push(optimistic);
                this.scrollToBottom();

                const formData = new FormData();
                formData.append('type', 'attachment');
                files.forEach(f => formData.append('files[]', f));

                try {
                    const response = await fetch(`/messages/${this.activeConversationId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data && data.message) {
                        const idx = this.messages.findIndex(m => m.id === optimistic.id);
                        if (idx !== -1) this.messages[idx] = data.message;
                        this.markConversationRead();
                    }
                } catch (err) {
                    console.error(err);
                }
            },

            async openEventPicker() {
                if (!this.activeConversationId) return;
                this.eventPickerOpen = true;
                this.eventSearch = '';
                this.selectedEventId = null;
                await this.fetchEvents();
            },

            closeEventPicker() {
                this.eventPickerOpen = false;
                this.eventSearch = '';
                this.selectedEventId = null;
                this.eventResults = [];
            },

            async fetchEvents() {
                this.eventLoading = true;
                try {
                    const url = new URL('/user/dashboard/messages/events', window.location.origin);
                    if (this.eventSearch.trim()) url.searchParams.set('q', this.eventSearch.trim());
                    const response = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await response.json();
                    this.eventResults = data.data || [];
                } catch (err) {
                    console.error(err);
                } finally {
                    this.eventLoading = false;
                }
            },

            chooseEvent(id) {
                this.selectedEventId = (this.selectedEventId === id) ? null : id;
            },

            async sendEventRecommendation() {
                if (!this.activeConversationId || !this.selectedEventId) return;

                const selected = this.eventResults.find(e => e.id === this.selectedEventId) || null;
                const optimistic = {
                    id: 'tmp_event_' + Date.now(),
                    sender_id: {{ auth()->id() ?? 0 }},
                    type: 'event_recommendation',
                    body: null,
                    time: 'Now',
                    is_mine: true,
                    event: selected,
                    attachments: [],
                    reply_to: null,
                };
                this.messages.push(optimistic);
                this.scrollToBottom();
                this.closeEventPicker();

                const formData = new FormData();
                formData.append('type', 'event_recommendation');
                formData.append('event_id', this.selectedEventId);

                try {
                    const response = await fetch(`/messages/${this.activeConversationId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data && data.message) {
                        const idx = this.messages.findIndex(m => m.id === optimistic.id);
                        if (idx !== -1) this.messages[idx] = data.message;
                        this.markConversationRead();
                    }
                } catch (err) {
                    console.error(err);
                }
            },

            toggleEmojiPicker() {
                this.emojiOpen = !this.emojiOpen;
            },

            insertEmoji(e) {
                this.newMessage = (this.newMessage || '') + e;
                this.emojiOpen = false;
            },

            async toggleRecording() {
                if (this.isRecording) {
                    this.mediaRecorder?.stop();
                    this.isRecording = false;
                    return;
                }

                if (!navigator.mediaDevices?.getUserMedia) return;

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.recordChunks = [];
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.mediaRecorder.ondataavailable = (ev) => {
                        if (ev.data && ev.data.size > 0) this.recordChunks.push(ev.data);
                    };
                    this.mediaRecorder.onstop = async () => {
                        stream.getTracks().forEach(t => t.stop());
                        const blob = new Blob(this.recordChunks, { type: this.mediaRecorder.mimeType || 'audio/webm' });
                        await this.sendVoiceNote(blob);
                    };
                    this.mediaRecorder.start();
                    this.isRecording = true;
                } catch (err) {
                    console.error(err);
                }
            },

            async sendVoiceNote(blob) {
                if (!this.activeConversationId || !blob) return;

                const file = new File([blob], `voice-note-${Date.now()}.webm`, { type: blob.type || 'audio/webm' });

                const optimistic = {
                    id: 'tmp_voice_' + Date.now(),
                    sender_id: {{ auth()->id() ?? 0 }},
                    body: null,
                    type: 'attachment',
                    time: 'Now',
                    is_mine: true,
                    attachments: [{ id: 'tmp_voice_a', type: file.type, size: file.size, url: '#', view_url: URL.createObjectURL(blob) }],
                    event: null,
                    reply_to: null,
                };

                this.messages.push(optimistic);
                this.scrollToBottom();

                const formData = new FormData();
                formData.append('type', 'attachment');
                formData.append('files[]', file);

                try {
                    const response = await fetch(`/messages/${this.activeConversationId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await response.json();
                    if (data && data.message) {
                        const idx = this.messages.findIndex(m => m.id === optimistic.id);
                        if (idx !== -1) this.messages[idx] = data.message;
                        this.markConversationRead();
                    }
                } catch (err) {
                    console.error(err);
                }
            },

            async reactToMessage(msg, emoji) {
                if (!msg || !msg.id || String(msg.id).startsWith('tmp_')) return;
                try {
                    const response = await fetch(`/user/dashboard/messages/messages/${msg.id}/reactions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ emoji })
                    });
                    const data = await response.json();
                    if (data && data.reactions) {
                        msg.reactions = data.reactions;
                    }
                } catch (err) {
                    console.error(err);
                }
            },

            openMediaViewer(url, type) {
                if (!url) return;
                this.mediaViewerUrl = url;
                this.mediaViewerType = type || 'image';
                this.mediaViewerOpen = true;
            },

            closeMediaViewer() {
                this.mediaViewerOpen = false;
                this.mediaViewerUrl = '';
                this.mediaViewerType = 'image';
            },

            shouldShowDateSeparator(idx) {
                const cur = this.messages[idx]?.created_at;
                if (!cur) return false;
                if (idx === 0) return true;
                const prev = this.messages[idx - 1]?.created_at;
                if (!prev) return true;
                const d1 = new Date(cur);
                const d0 = new Date(prev);
                return d1.toDateString() !== d0.toDateString();
            },

            formatDateSeparator(iso) {
                if (!iso) return '';
                const d = new Date(iso);
                return d.toLocaleString(undefined, { month: 'short', day: '2-digit', year: 'numeric' });
            },

            onMessagesScroll(e) {
                const el = e?.target;
                if (!el) return;
                if (el.scrollTop < 60 && this.nextPageUrl && !this.isLoadingMessages) {
                    this.fetchMessages(false);
                }
            },

            subscribeToConversation(conversationId) {
                try {
                    if (this.echoChannelName && window.Echo) {
                        window.Echo.leave(this.echoChannelName);
                    }

                    this.echoChannelName = `conversation.${conversationId}`;

                    if (!window.Echo) return;

                    window.Echo.private(`conversation.${conversationId}`)
                        .listen('.MessageSent', (payload) => {
                            if (!payload || !payload.id) return;
                            this.messages.push({ ...payload, is_mine: payload.sender_id === {{ auth()->id() ?? 0 }} });
                            this.scrollToBottom();
                            this.markConversationRead();
                        })
                        .listenForWhisper('typing', (e) => {
                            if (!e) return;
                            this.isPeerTyping = true;
                            clearTimeout(this.typingTimeout);
                            this.typingTimeout = setTimeout(() => this.isPeerTyping = false, 1200);
                        });
                } catch (err) {
                    console.error(err);
                }
            },

            toggleHeaderMenu() {
                this.headerMenuOpen = !this.headerMenuOpen;
            },

            closeChat() {
                this.activeConversationId = null;
                this.activeConversationName = '';
                this.activeConversationAvatar = '';
                this.activeConversationIsGroup = false;
                this.messages = [];
                this.nextPageUrl = null;
                this.headerMenuOpen = false;
                this.presenceText = '';
                this.presenceUserId = null;
            },

            async clearMessages() {
                if (!this.activeConversationId) return;
                if (!confirm('Clear all messages in this chat?')) return;
                try {
                    await fetch(`/user/dashboard/messages/conversations/${this.activeConversationId}/clear`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    this.messages = [];
                } catch (err) {
                    console.error(err);
                } finally {
                    this.headerMenuOpen = false;
                }
            },

            async deleteChat() {
                if (!this.activeConversationId) return;
                const label = this.activeConversationIsGroup ? 'leave this group' : 'delete this chat';
                if (!confirm(`Are you sure you want to ${label}?`)) return;
                try {
                    await fetch(`/user/dashboard/messages/conversations/${this.activeConversationId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    window.location.href = '/user/dashboard/messages';
                } catch (err) {
                    console.error(err);
                }
            },

            async openProfilePreview() {
                if (!this.activeConversationId) return;
                this.profileModalOpen = true;
                this.profileInfoLoading = true;
                this.profileInfo = null;
                try {
                    const resp = await fetch(`/user/dashboard/messages/conversations/${this.activeConversationId}/info`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await resp.json();
                    this.profileInfo = data.data || null;
                } catch (err) {
                    console.error(err);
                } finally {
                    this.profileInfoLoading = false;
                }
            },

            closeProfilePreview() {
                this.profileModalOpen = false;
                this.profileInfoLoading = false;
                this.profileInfo = null;
            },

            async pingPresence() {
                try {
                    await fetch('/user/presence/ping', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                } catch (_) {}
            },

            async refreshPresence() {
                if (!this.presenceUserId || this.activeConversationIsGroup) return;
                try {
                    const resp = await fetch(`/user/presence/${this.presenceUserId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await resp.json();
                    if (data && typeof data.online !== 'undefined') {
                        this.presenceText = data.online ? 'Online' : (data.last_seen_human ? ('Active ' + data.last_seen_human) : 'Offline');
                    }
                } catch (_) {}
            },

            notifyTyping() {
                if (!this.activeConversationId || !window.Echo) return;
                window.Echo.private(`conversation.${this.activeConversationId}`)
                    .whisper('typing', { name: '{{ auth()->user()?->first_name }}' });
            },

            async markConversationRead() {
                if (!this.activeConversationId) return;
                try {
                    await fetch(`/user/dashboard/messages/conversations/${this.activeConversationId}/read`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                } catch (_) {}
            },

            scrollToBottom() {
                setTimeout(() => {
                    const container = document.getElementById('messages-container');
                    if(container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 50);
            },

            async openNewMessage() {
                this.newMessageModalOpen = true;
                this.recipientSearch = '';
                this.selectedRecipientId = null;
                if (this.recipients.length === 0) {
                    await this.fetchRecipients();
                } else {
                    this.filteredRecipients = this.recipients;
                }
            },

            closeNewMessage() {
                this.newMessageModalOpen = false;
                this.recipientSearch = '';
                this.selectedRecipientId = null;
            },

            async fetchRecipients() {
                this.recipientsLoading = true;
                try {
                    const response = await fetch('/user/dashboard/messages/recipients', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    this.recipients = data.data || [];
                    this.filteredRecipients = this.recipients;
                } catch (err) {
                    console.error(err);
                } finally {
                    this.recipientsLoading = false;
                }
            },

            filterRecipients() {
                const q = (this.recipientSearch || '').toLowerCase().trim();
                if (!q) {
                    this.filteredRecipients = this.recipients;
                    return;
                }
                this.filteredRecipients = this.recipients.filter(u =>
                    (u.name || '').toLowerCase().includes(q) ||
                    (u.username || '').toLowerCase().includes(q)
                );
            },

            toggleRecipient(id) {
                this.selectedRecipientId = (this.selectedRecipientId === id) ? null : id;
            },

            async startChatFromModal() {
                if (!this.selectedRecipientId) return;
                this.startingChat = true;
                try {
                    const response = await fetch('/user/dashboard/messages/conversations', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ recipient_id: this.selectedRecipientId })
                    });
                    const data = await response.json();
                    if (data && data.conversation_id) {
                        window.location.href = `/user/dashboard/messages?conversation=${data.conversation_id}`;
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    this.startingChat = false;
                }
            },

            init() {
                this.pingPresence();
                this.presenceTimer = setInterval(() => {
                    this.pingPresence();
                    this.refreshPresence();
                }, 25000);

                const openId = {{ (int)($openConversationId ?? 0) }};
                if (openId) {
                    // Find from existing list by clicking if present
                    // If not in sidebar yet, still load and rely on header data from API messages
                    this.loadConversation(openId, 'Conversation', '{{ asset('default.png') }}', false);
                }
            }
        }));
    });
</script>
@endpush
@endsection
