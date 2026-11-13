@extends('layouts.admin')

@section('page_title', 'Kelola Pembayaran')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Daftar Pembayaran & Bukti Transfer</h2>
        <p class="text-xs text-gray-500 mt-0.5">Pantau dan verifikasi pembayaran transfer atau QRIS dari pelanggan toko</p>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">No. Pesanan</th>
                        <th class="py-3 px-5">Pelanggan</th>
                        <th class="py-3 px-5">Nominal Bayar</th>
                        <th class="py-3 px-5">Metode</th>
                        <th class="py-3 px-5">Bukti Transfer</th>
                        <th class="py-3 px-5">Status</th>
                        <th class="py-3 px-5">Tanggal</th>
                        <th class="py-3 px-5 text-right">Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ rejectOpen: false }">
                            <td class="py-4 px-5">
                                @if($payment->order)
                                    <a href="{{ route('admin.orders.show', $payment->order) }}" class="font-mono font-bold text-indigo-600 hover:text-indigo-800">
                                        {{ $payment->order->order_number }}
                                    </a>
                                @else
                                    <span class="text-gray-400 font-mono">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                <p class="font-bold text-gray-800">{{ $payment->order?->user?->name ?? 'User #' . ($payment->order?->user_id ?? '-') }}</p>
                                <p class="text-gray-400 text-[11px]">{{ $payment->order?->user?->email }}</p>
                            </td>
                            <td class="py-4 px-5 font-bold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5 font-semibold uppercase text-gray-700">
                                {{ $payment->method ?? 'QRIS' }}
                            </td>
                            <td class="py-4 px-5">
                                @if($payment->proof_path)
                                    <a href="{{ asset('storage/' . $payment->proof_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Belum upload</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                @php
                                    $payBadges = [
                                        'waiting_verification' => 'bg-amber-100 text-amber-800',
                                        'verified' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'pending' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $payLabels = [
                                        'waiting_verification' => 'Menunggu Verifikasi',
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        'pending' => 'Belum Bayar',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-full {{ $payBadges[$payment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $payLabels[$payment->status] ?? ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-gray-500 whitespace-nowrap">
                                {{ $payment->updated_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                @if($payment->status === 'waiting_verification')
                                    <div class="inline-flex items-center gap-2">
                                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Verifikasi pembayaran ini? Pesanan akan diubah menjadi Paid.')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition-colors shadow-sm">
                                                Verifikasi
                                            </button>
                                        </form>

                                        <button @click="rejectOpen = true" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 font-bold rounded-lg text-xs transition-colors">
                                            Tolak
                                        </button>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 text-left space-y-4" @click.away="rejectOpen = false">
                                            <div class="flex items-center justify-between">
                                                <h4 class="font-bold text-gray-900 text-base">Tolak Bukti Pembayaran</h4>
                                                <button @click="rejectOpen = false" class="text-gray-400 hover:text-gray-600">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </button>
                                            </div>

                                            <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PATCH')
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                                                    <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai..." class="w-full p-3 border border-gray-200 rounded-xl text-xs outline-none focus:border-red-500"></textarea>
                                                </div>

                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button" @click="rejectOpen = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                                                    <button type="submit" class="px-4 py-2 text-xs font-bold bg-red-600 hover:bg-red-700 text-white rounded-xl">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif($payment->status === 'verified')
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-xs">
                                        <i data-lucide="check-circle" class="w-4 h-4"></i> Sukses
                                    </span>
                                @elseif($payment->status === 'rejected')
                                    <span class="text-xs text-red-600 font-medium line-clamp-1 max-w-[120px]" title="{{ $payment->rejection_reason }}">
                                        {{ $payment->rejection_reason ?? 'Ditolak' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400">
                                <i data-lucide="credit-card" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                Belum ada transaksi pembayaran yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

