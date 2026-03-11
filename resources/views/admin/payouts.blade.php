@extends('layouts.admin')

@section('title', 'Payout Management')

@section('content')
<div class="space-y-6">
    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-400/10 p-6 rounded-2xl border border-white/5">
            <div class="text-zinc-500 text-sm mb-1 uppercase tracking-wider">Pending Payouts</div>
            <div class="text-2xl font-bold text-orange-400">UGX {{ number_format($requests->where('status', 'pending')->sum('amount')) }}</div>
            <div class="text-xs text-zinc-600 mt-2">{{ $requests->where('status', 'pending')->count() }} active requests</div>
        </div>
    </div>

    {{-- Payout Requests Table --}}
    <div class="bg-green-400/10 rounded-2xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-orange-400 text-black/90 font-mono text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-4">Organizer</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Method</th>
                        <th class="px-6 py-4">Requested At</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($requests as $req)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $req->organizer->business_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $req->organizer->business_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-white font-mono">UGX {{ number_format($req->amount) }}</td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-zinc-400 uppercase">{{ $req->method }}</div>
                            <div class="text-xs text-zinc-500">{{ $req->account_details }}</div>
                        </td>
                        <td class="px-6 py-4 text-zinc-500 text-sm">{{ $req->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                    'approved' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                    'rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded border {{ $statusClasses[$req->status] ?? 'bg-zinc-800 text-zinc-400 border-zinc-700' }}">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($req->status === 'pending')
                            <div x-data="{ open: false }" class="inline-block relative">
                                <button @click="open = !open" class="text-orange-400 hover:text-orange-300 text-sm font-medium">Process</button>
                                
                                {{-- Simple Action Modal --}}
                                <div x-show="open" x-cloak @click.away="open = false" 
                                     class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
                                    <div class="bg-zinc-900 border border-zinc-800 p-6 rounded-2xl w-full max-w-md text-left shadow-2xl">
                                        <h3 class="text-lg font-bold text-white mb-4">Process Payout Request</h3>
                                        <form action="{{ route('admin.payouts.update-status', $req->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-zinc-400 mb-2">Action</label>
                                                <select name="status" class="w-full bg-black border border-zinc-800 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-orange-500">
                                                    <option value="approved">Approve & Mark as Paid</option>
                                                    <option value="rejected">Reject Request</option>
                                                </select>
                                            </div>
                                            <div class="mb-6">
                                                <label class="block text-sm font-medium text-zinc-400 mb-2">Internal Note</label>
                                                <textarea name="admin_notes" rows="3" class="w-full bg-black border border-zinc-800 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-orange-500" placeholder="Transaction ID, reason for rejection, etc."></textarea>
                                            </div>
                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="open = false" class="px-4 py-2 text-zinc-500 hover:text-white transition-colors">Cancel</button>
                                                <button type="submit" class="px-6 py-2 bg-orange-500 text-black font-bold rounded-xl hover:bg-orange-400 transition-colors">Confirm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                                <span class="text-zinc-600 text-xs italic">Processed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-zinc-500 italic">No payout requests found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-zinc-800">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection
