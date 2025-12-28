@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('content')
<div class="bg-green-400/5 border border-green-400/10 rounded-2xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-orange-400/80">Frequently Asked Questions</h2>
        <button onclick="document.getElementById('addFaqModal').classList.remove('hidden')" 
                class="px-4 py-2 bg-orange-400 text-black text-xs font-bold rounded-xl hover:bg-orange-300 transition-colors">
            Add New FAQ
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-zinc-800 text-zinc-500 text-xs uppercase">
                    <th class="px-4 py-3 font-medium">Question</th>
                    <th class="px-4 py-3 font-medium">Answer</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @foreach($faqs as $faq)
                <tr class="text-sm text-zinc-300 hover:bg-white/5 transition-colors">
                    <td class="px-4 py-4 font-medium">{{ Str::limit($faq->question, 50) }}</td>
                    <td class="px-4 py-4 text-zinc-500">{{ Str::limit($faq->answer, 80) }}</td>
                    <td class="px-4 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button onclick="editFaq({{ json_encode($faq) }})" 
                                    class="p-2 bg-blue-500/10 text-blue-400 rounded-lg hover:bg-blue-500/20 transition-all">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.faqs.delete', $faq) }}" method="POST" onsubmit="return confirm('Delete this FAQ?')">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $faqs->links() }}
    </div>
</div>

<!-- Add Modal -->
<div id="addFaqModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-zinc-900 border border-zinc-800 w-full max-w-lg rounded-3xl p-8 relative">
        <button onclick="this.closest('#addFaqModal').classList.add('hidden')" class="absolute top-6 right-6 text-zinc-500 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="text-2xl font-bold text-white mb-6">New FAQ</h3>
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-2">Question</label>
                    <input type="text" name="question" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-orange-500/50">
                </div>
                <div>
                    <label class="block text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-2">Answer</label>
                    <textarea name="answer" rows="4" required
                              class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-orange-500/50"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-orange-500 text-black font-black uppercase tracking-widest rounded-2xl hover:bg-orange-400 transition-all">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editFaqModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-zinc-900 border border-zinc-800 w-full max-w-lg rounded-3xl p-8 relative">
        <button onclick="this.closest('#editFaqModal').classList.add('hidden')" class="absolute top-6 right-6 text-zinc-500 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="text-2xl font-bold text-white mb-6">Edit FAQ</h3>
        <form id="editFaqForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-2">Question</label>
                    <input type="text" name="question" id="edit_question" required
                           class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-orange-500/50">
                </div>
                <div>
                    <label class="block text-zinc-500 text-[10px] font-black uppercase tracking-widest mb-2">Answer</label>
                    <textarea name="answer" id="edit_answer" rows="4" required
                              class="w-full bg-zinc-950 border border-zinc-800 rounded-2xl py-4 px-6 text-white text-sm focus:outline-none focus:border-orange-500/50"></textarea>
                </div>
                <button type="submit" class="w-full py-4 bg-blue-500 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-blue-400 transition-all">Update FAQ</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function editFaq(faq) {
        document.getElementById('edit_question').value = faq.question;
        document.getElementById('edit_answer').value = faq.answer;
        document.getElementById('editFaqForm').action = `/admin/faqs/${faq.id}`;
        document.getElementById('editFaqModal').classList.remove('hidden');
    }
</script>
@endpush
@endsection
