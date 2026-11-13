@extends('layouts.pegawai')

@section('page_title', 'Verifikasi Pembayaran Masuk')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Antrean Pembayaran Menunggu Verifikasi</h2>
        <p class="text-xs text-gray-500 mt-0.5">Periksa keabsahan bukti transfer dari customer sebelum memproses pesanan</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 uppercase text-gray-400 font-semibold border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5">No. Pesanan</th>
                        <th class="py-3 px-5">Pelanggan</th>
                        <th class="py-3 px-5">Nominal Bayar</th>
                        <th class="py-3 px-5">Bukti Transfer</th>
                        <th class="py-3 px-5">Tanggal Upload</th>
                        <th class="py-3 px-5 text-right">Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50" x-data="{ rejectOpen: false }">
                            <td class="py-4 px-5 font-mono font-bold text-gray-900">
                                {{ $payment->order?->order_number ?? '-' }}
                            </td>
                            <td class="py-4 px-5">
                                <p class="font-bold text-gray-800">{{ $payment->order?->user?->name ?? '-' }}</p>
                                <p class="text-gray-400 text-[11px]">{{ $payment->order?->user?->email }}</p>
                            </td>
                            <td class="py-4 px-5 font-bold text-sky-600 text-sm">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-5">
                                @if($payment->proof_path)
                                    <a href="{{ asset('storage/' . $payment->proof_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-gray-500">
                                {{ $payment->updated_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Verify Form -->
                                    <form action="{{ route('pegawai.payments.verify', $payment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('Verifikasi pembayaran ini? Status pesanan akan berubah menjadi Paid.')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs transition-colors shadow-sm">
                                            Verifikasi
                                        </button>
                                    </form>

                                    <!-- Reject Toggle -->
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

                                        <form action="{{ route('pegawai.payments.reject', $payment) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                                                <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Bukti transfer buram / nominal tidak sesuai / nama pengirim berbeda..." class="w-full p-3 border border-gray-200 rounded-xl text-xs outline-none focus:border-red-500"></textarea>
                                            </div>

                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" @click="rejectOpen = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                                                <button type="submit" class="px-4 py-2 text-xs font-bold bg-red-600 hover:bg-red-700 text-white rounded-xl">Kirim Penolakan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <i data-lucide="check-circle-2" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                Semua bukti pembayaran telah selesai diverifikasi.
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

