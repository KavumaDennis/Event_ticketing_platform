@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('content')
<div class="bg-green-400/5 border border-green-400/10 rounded-2xl p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-bold text-orange-400/80">Frequently Asked Questions</h2>
        <button onclick="document.getElementById('addFaqModal').classList.remove('hidden')" class="px-4 py-2 bg-orange-400 text-black text-xs font-bold rounded-xl hover:bg-orange-300 transition-colors">
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
                            <button onclick="editFaq({{ json_encode($faq) }})" class="p-2 bg-blue-500/10 text-blue-400 rounded-lg hover:bg-blue-500/20 transition-all">
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
<div id="addFaqModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-lg p-4 shadow-xl relative">
        <button onclick="this.closest('#addFaqModal').classList.add('hidden')" class="absolute top-6 right-6 text-zinc-500 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="text-xl font-bold mb-4 text-orange-400/70">New FAQ</h3>
        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="flex flex-col gap-1.5 mb-3">
                    <label class="text-white/60 font-medium ml-1 text-sm">Question</label>
                    <input type="text" name="question" required class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div class="flex flex-col gap-1.5 mb-3">
                    <label class="text-white/60 font-medium ml-1 text-sm">Answer</label>
                    <textarea name="answer" rows="4" required class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 bg-orange-400/70 rounded-3xl mt-3 text-sm font-mono">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editFaqModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-black/85 bg-[url(/public/bg-img.png)] bg-cover bg-center bg-fixed  bg-blend-multiply border border-green-400/30 backdrop-blur-[1px] w-full max-w-lg p-4 shadow-xl relative">
        <button onclick="this.closest('#editFaqModal').classList.add('hidden')" class="absolute top-6 right-6 text-zinc-500 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="text-xl font-bold mb-4 text-orange-400/70">Edit FAQ</h3>
        <form id="editFaqForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="text-white/60 font-medium ml-1 text-sm">Question</label>
                    <input type="text" name="question" id="edit_question" required class="w-full p-3 rounded-3xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70">
                </div>
                <div>
                    <label class="text-white/60 font-medium ml-1 text-sm">Answer</label>
                    <textarea name="answer" id="edit_answer" rows="4" required class="w-full p-3 rounded-xl bg-[#b0a6df]/10 outline outline-[#b0a6df]/20 backdrop-blur-4xl text-orange-400/70 text-sm font-semibold placeholder-orange-400/70"></textarea>
                </div>
                <button type="submit" class="w-fit bg-orange-400/70 px-4 py-2 rounded-3xl mt-3 text-sm font-mono">Update FAQ</button>
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
