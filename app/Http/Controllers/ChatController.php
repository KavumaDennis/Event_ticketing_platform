<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Event;
use App\Models\MessageAttachment;
use App\Models\MessageReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get conversations the user is part of
        $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereNull('deleted_at');
        })
        ->with(['participants.user', 'lastMessage'])
        ->get()
        ->sortByDesc(function ($conv) {
            return $conv->lastMessage ? $conv->lastMessage->created_at : $conv->created_at;
        });

        $openConversationId = request()->integer('conversation');

        return view('dashboard.messages', compact('conversations', 'openConversationId'));
    }

    public function recipients(Request $request)
    {
        $user = $request->user();

        $followedUserIds = $user->following()->pluck('following_id')->all();

        $followedOrganizerOwnerIds = $user->followedOrganizers()
            ->whereNotNull('organizers.user_id')
            ->pluck('organizers.user_id')
            ->all();

        $eligibleIds = collect([...$followedUserIds, ...$followedOrganizerOwnerIds])
            ->filter()
            ->unique()
            ->reject(fn ($id) => (int) $id === (int) $user->id)
            ->values();

        $recipients = User::query()
            ->whereIn('id', $eligibleIds)
            ->select(['id', 'first_name', 'last_name', 'username', 'profile_pic', 'avatar'])
            ->orderBy('first_name')
            ->limit(200)
            ->get()
            ->map(function (User $u) {
                $avatarPath = $u->profile_pic ?? $u->avatar;
                $avatarUrl = $avatarPath ? asset('storage/' . ltrim($avatarPath, '/')) : asset('default.png');

                return [
                    'id' => $u->id,
                    'name' => trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->username ?? 'Unknown'),
                    'username' => $u->username,
                    'avatar' => $avatarUrl,
                ];
            });

        return response()->json([
            'data' => $recipients,
        ]);
    }

    public function eventSearch(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $q = trim((string) $request->input('q', ''));

        $events = \App\Models\Event::query()
            ->with('organizer:id,business_name,user_id,organizer_image')
            ->withCount('likes')
            ->whereDate('event_date', '>=', now()->toDateString())
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('event_name', 'like', "%{$q}%")
                        ->orWhere('location', 'like', "%{$q}%")
                        ->orWhere('venue', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('likes_count')
            ->limit(25)
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->event_name,
                    'date' => $e->event_date,
                    'location' => $e->location,
                    'venue' => $e->venue,
                    'banner' => $e->event_image ? asset('storage/' . $e->event_image) : asset('default.png'),
                    'organizer' => $e->organizer?->business_name,
                    'price' => $e->regular_price,
                    'url' => route('event.show', $e->id),
                    'likes' => (int) $e->likes_count,
                ];
            })
            ->values();

        return response()->json(['data' => $events]);
    }

    public function createConversation(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'recipient_id' => ['required', 'integer', Rule::exists('users', 'id')],
        ]);

        $recipient = User::findOrFail($validated['recipient_id']);

        if (! $this->canMessage($user, $recipient)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $conversation = DB::transaction(function () use ($user, $recipient) {
            $existing = Conversation::query()
                ->where('is_group', false)
                ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
                ->whereHas('participants', fn ($q) => $q->where('user_id', $recipient->id))
                ->first();

            if ($existing) {
                return $existing;
            }

            $conv = Conversation::create([
                'is_group' => false,
                'name' => null,
                'event_id' => null,
            ]);

            $conv->participants()->createMany([
                ['user_id' => $user->id, 'role' => 'member'],
                ['user_id' => $recipient->id, 'role' => 'member'],
            ]);

            return $conv;
        });

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
        ]);
    }

    public function conversationInfo(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        if (! $this->isParticipant($conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $conversation->loadMissing([
            'event',
            'participants.user:id,first_name,last_name,username,profile_pic,avatar',
        ]);

        $participants = $conversation->participants->map(function ($p) {
            $u = $p->user;
            $avatarPath = $u?->profile_pic ?? $u?->avatar;
            $avatarUrl = $avatarPath ? asset('storage/' . ltrim($avatarPath, '/')) : asset('default.png');

            return [
                'id' => $u?->id,
                'name' => $u ? (trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->username ?? 'User')) : 'User',
                'username' => $u?->username,
                'avatar' => $avatarUrl,
                'role' => $p->role,
                'profile_url' => $u ? route('user.profile', $u->id) : null,
            ];
        })->values();

        $event = $conversation->event ? [
            'id' => $conversation->event->id,
            'title' => $conversation->event->event_name,
            'date' => $conversation->event->event_date,
            'location' => $conversation->event->location,
            'venue' => $conversation->event->venue,
            'banner' => $conversation->event->event_image ? asset('storage/' . $conversation->event->event_image) : asset('default.png'),
            'url' => route('event.show', $conversation->event->id),
        ] : null;

        return response()->json([
            'data' => [
                'id' => $conversation->id,
                'is_group' => (bool) $conversation->is_group,
                'name' => $conversation->name,
                'event' => $event,
                'participants' => $participants,
            ],
        ]);
    }

    public function messages(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        if (! $this->isParticipant($conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $clearedAt = $participant->cleared_at;

        $with = ['sender:id,first_name,last_name,username,profile_pic,avatar', 'attachments', 'event', 'replyTo.sender:id,first_name,last_name,username'];
        if (Schema::hasTable('message_reactions')) {
            $with[] = 'reactions';
        }

        $messagesQuery = $conversation->messages();
        if ($clearedAt) {
            $messagesQuery->where('created_at', '>', $clearedAt);
        }

        $messages = $messagesQuery
            ->with($with)
            ->latest()
            ->paginate(25);

        $data = $messages->getCollection()->map(function (Message $m) use ($user) {
            return $this->formatMessage($m, $user);
        })->values();

        return response()->json([
            'data' => $data,
            'next_page_url' => $messages->nextPageUrl(),
        ]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        if (! $this->isParticipant($conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'body' => ['nullable', 'string'],
            'type' => ['required', 'string', Rule::in(['text', 'event_recommendation', 'attachment', 'system'])],
            'event_id' => ['nullable', Rule::exists('events', 'id')],
            'reply_to_id' => ['nullable', Rule::exists('messages', 'id')],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:51200'], // 50MB per file
        ]);

        if ($validated['type'] === 'event_recommendation' && empty($validated['event_id'])) {
            return response()->json(['message' => 'event_id is required for event recommendation'], 422);
        }

        if ($validated['type'] === 'attachment' && ! $request->hasFile('files')) {
            return response()->json(['message' => 'files are required for attachment messages'], 422);
        }

        $message = DB::transaction(function () use ($conversation, $user, $request, $validated) {
            $m = $conversation->messages()->create([
                'sender_id' => $user->id,
                'body' => $validated['body'] ?? null,
                'type' => $validated['type'],
                'event_id' => $validated['event_id'] ?? null,
                'reply_to_id' => $validated['reply_to_id'] ?? null,
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('private/message_attachments', 'local');
                    $m->attachments()->create([
                        'path' => $path,
                        'type' => $file->getMimeType() ?: 'application/octet-stream',
                        'size' => $file->getSize(),
                    ]);
                }
            }

            return $m;
        });

        $message->load(['sender', 'attachments', 'event', 'replyTo.sender']);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $this->formatMessage($message, $user),
        ]);
    }

    public function markRead(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        if (! $this->isParticipant($conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $latestId = $conversation->messages()->max('id');
        if (! $latestId) {
            return response()->json(['success' => true]);
        }

        $unread = $conversation->messages()
            ->where('id', '<=', $latestId)
            ->where('sender_id', '!=', $user->id)
            ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
            ->pluck('id');

        if ($unread->isEmpty()) {
            return response()->json(['success' => true]);
        }

        $rows = $unread->map(fn ($id) => [
            'message_id' => $id,
            'user_id' => $user->id,
            'read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        DB::table('message_reads')->insert($rows);

        return response()->json(['success' => true]);
    }

    public function clearConversation(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        if (! $this->isParticipant($conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $conversation->participants()
            ->where('user_id', $user->id)
            ->update(['cleared_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function deleteConversation(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        if (! $this->isParticipant($conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Per-user soft delete: hide conversation for the current user only
        $conversation->participants()
            ->where('user_id', $user->id)
            ->update(['deleted_at' => now()]);

        return response()->json(['success' => true, 'deleted' => true]);
    }

    public function downloadAttachment(Request $request, MessageAttachment $attachment)
    {
        $user = $request->user();

        $attachment->loadMissing('message.conversation.participants');
        $conversation = $attachment->message?->conversation;

        if (! $conversation || ! $this->isParticipant($conversation, $user)) {
            abort(403);
        }

        if (! Storage::disk('local')->exists($attachment->path)) {
            abort(404);
        }

        $filename = basename($attachment->path);

        return Storage::disk('local')->download($attachment->path, $filename);
    }

    public function viewAttachment(Request $request, MessageAttachment $attachment)
    {
        $user = $request->user();

        $attachment->loadMissing('message.conversation.participants');
        $conversation = $attachment->message?->conversation;

        if (! $conversation || ! $this->isParticipant($conversation, $user)) {
            abort(403);
        }

        if (! Storage::disk('local')->exists($attachment->path)) {
            abort(404);
        }

        return Storage::disk('local')->response($attachment->path, null, [
            'Content-Type' => $attachment->type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline',
        ]);
    }

    public function toggleReaction(Request $request, Message $message)
    {
        $user = $request->user();
        $message->loadMissing('conversation.participants');

        if (! $message->conversation || ! $this->isParticipant($message->conversation, $user)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'emoji' => ['required', 'string', 'max:32'],
        ]);

        $exists = MessageReaction::query()
            ->where('message_id', $message->id)
            ->where('user_id', $user->id)
            ->where('emoji', $validated['emoji'])
            ->exists();

        if ($exists) {
            MessageReaction::query()
                ->where('message_id', $message->id)
                ->where('user_id', $user->id)
                ->where('emoji', $validated['emoji'])
                ->delete();
        } else {
            MessageReaction::create([
                'message_id' => $message->id,
                'user_id' => $user->id,
                'emoji' => $validated['emoji'],
            ]);
        }

        $message->loadMissing('reactions.user:id,first_name,last_name,username');

        $grouped = $message->reactions->groupBy('emoji')->map(function ($items, $emoji) {
            return [
                'emoji' => $emoji,
                'count' => $items->count(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'reactions' => $grouped,
        ]);
    }

    public function joinGroup(Request $request, Event $event)
    {
        // Must be a paid ticket holder
        $isBuyer = \App\Models\TicketPurchase::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->where('status', 'paid')
            ->exists();

        if (!$isBuyer && $event->organizer_id !== auth()->user()->organizer?->id) {
            return redirect()->back()->withErrors(['message' => 'You must be a ticket holder to join the event group.']);
        }

        $conversation = Conversation::firstOrCreate([
            'is_group' => true,
            'event_id' => $event->id,
        ], [
            'name' => $event->event_name . ' Group',
        ]);

        $conversation->participants()->firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('user.dashboard.messages')->with('success', 'Joined group chat successfully!');
    }

    private function isParticipant(Conversation $conversation, User $user): bool
    {
        return $conversation->participants()->where('user_id', $user->id)->whereNull('deleted_at')->exists();
    }

    private function canMessage(User $user, User $recipient): bool
    {
        if ((int) $user->id === (int) $recipient->id) {
            return false;
        }

        $followsUser = $user->isFollowing($recipient->id);

        $followsOrganizerOwner = $user->followedOrganizers()
            ->where('organizers.user_id', $recipient->id)
            ->exists();

        return $followsUser || $followsOrganizerOwner;
    }

    private function formatMessage(Message $m, User $viewer): array
    {
        $sender = $m->sender;
        $avatarPath = $sender?->profile_pic ?? $sender?->avatar;
        $avatarUrl = $avatarPath ? asset('storage/' . ltrim($avatarPath, '/')) : asset('default.png');

        return [
            'id' => $m->id,
            'conversation_id' => $m->conversation_id,
            'sender_id' => $m->sender_id,
            'sender' => $sender ? [
                'id' => $sender->id,
                'name' => trim(($sender->first_name ?? '') . ' ' . ($sender->last_name ?? '')) ?: ($sender->username ?? 'User'),
                'username' => $sender->username,
                'avatar' => $avatarUrl,
            ] : null,
            'body' => $m->body,
            'type' => $m->type,
            'event' => $m->event ? [
                'id' => $m->event->id,
                'title' => $m->event->event_name,
                'date' => $m->event->event_date,
                'location' => $m->event->location,
                'venue' => $m->event->venue,
                'banner' => $m->event->event_image ? asset('storage/' . $m->event->event_image) : asset('default.png'),
                'price' => $m->event->regular_price,
                'url' => route('event.show', $m->event->id),
            ] : null,
            'attachments' => $m->attachments?->map(fn ($a) => [
                'id' => $a->id,
                'type' => $a->type,
                'size' => $a->size,
                'url' => route('messages.attachments.download', $a->id),
                'view_url' => route('messages.attachments.view', $a->id),
            ])->all() ?? [],
            'reactions' => $m->relationLoaded('reactions')
                ? $m->reactions->groupBy('emoji')->map(fn ($items, $emoji) => ['emoji' => $emoji, 'count' => $items->count()])->values()
                : [],
            'reply_to' => $m->replyTo ? [
                'id' => $m->replyTo->id,
                'sender_name' => trim(($m->replyTo->sender?->first_name ?? '') . ' ' . ($m->replyTo->sender?->last_name ?? '')) ?: ($m->replyTo->sender?->username ?? 'User'),
                'body' => $m->replyTo->body,
                'type' => $m->replyTo->type,
            ] : null,
            'created_at' => $m->created_at?->toIso8601String(),
            'time' => $m->created_at?->shortAbsoluteDiffForHumans(),
            'is_mine' => (int) $m->sender_id === (int) $viewer->id,
        ];
    }
}
