@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Back to order -->
    <a href="{{ route('user.orders.show', $order) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Pesanan #{{ $order->order_number }}
    </a>

    <!-- Header Section -->
    <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm text-center space-y-3">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Instruksi Pembayaran</span>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Tagihan Pesanan #{{ $order->order_number }}</h1>
        
        <div class="inline-block bg-indigo-50 border border-indigo-100 rounded-2xl py-3 px-6 mt-2">
            <span class="text-xs font-medium text-indigo-700 block">Total yang Harus Dibayar:</span>
            <span class="text-2xl sm:text-3xl font-black text-indigo-600">
                Rp {{ number_format($order->payment?->amount ?? $order->total_amount, 0, ',', '.') }}
            </span>
        </div>

        @php
            $payStatus = $order->payment?->status ?? 'pending';
        @endphp

        <div class="pt-2">
            @if($payStatus === 'verified')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i> Pembayaran Terverifikasi (Lunas)
                </span>
            @elseif($payStatus === 'waiting_verification')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                    <i data-lucide="clock" class="w-4 h-4"></i> Menunggu Verifikasi Staf
                </span>
            @elseif($payStatus === 'rejected')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i> Pembayaran Ditolak
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> Belum Dibayar
                </span>
            @endif
        </div>
    </div>

    @if($payStatus === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-3xl p-6 flex items-start gap-3.5 text-red-800">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Bukti Pembayaran Anda Ditolak</h4>
                <p class="text-xs text-red-700 mt-1">Alasan dari staf: <span class="font-semibold italic">"{{ $order->payment->rejection_reason ?? 'Bukti transfer tidak valid atau tidak terbaca.' }}"</span></p>
                <p class="text-xs text-red-600 mt-1">Silakan upload kembali bukti transfer yang benar di bawah ini.</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- QRIS & Bank Transfer Section -->
        <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="text-center space-y-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Metode 1</span>
                <h3 class="font-bold text-gray-900 text-base">Scan QRIS Instan</h3>
                <p class="text-xs text-gray-500">Mendukung GoPay, OVO, Dana, ShopeePay, BCA, Mandiri, & Seluruh Mobile Banking</p>
            </div>

            <!-- Stylized QRIS Box -->
            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200 text-center flex flex-col items-center">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 inline-block">
                    <!-- SVG QR Code Placeholder -->
                    <svg class="w-44 h-44 text-gray-900" viewBox="0 0 200 200" fill="currentColor">
                        <!-- Outer Border Markers -->
                        <rect x="10" y="10" width="60" height="60" rx="8" fill="none" stroke="currentColor" stroke-width="12"/>
                        <rect x="25" y="25" width="30" height="30" rx="4"/>
                        <rect x="130" y="10" width="60" height="60" rx="8" fill="none" stroke="currentColor" stroke-width="12"/>
                        <rect x="145" y="25" width="30" height="30" rx="4"/>
                        <rect x="10" y="130" width="60" height="60" rx="8" fill="none" stroke="currentColor" stroke-width="12"/>
                        <rect x="25" y="145" width="30" height="30" rx="4"/>
                        <!-- Data Pattern Mockup -->
                        <rect x="85" y="15" width="15" height="15"/>
                        <rect x="105" y="30" width="15" height="25"/>
                        <rect x="85" y="55" width="30" height="15"/>
                        <rect x="15" y="85" width="20" height="15"/>
                        <rect x="45" y="90" width="30" height="15"/>
                        <rect x="85" y="85" width="30" height="30" rx="4"/>
                        <rect x="130" y="85" width="25" height="15"/>
                        <rect x="165" y="85" width="25" height="15"/>
                        <rect x="130" y="110" width="15" height="25"/>
                        <rect x="155" y="110" width="35" height="15"/>
                        <rect x="85" y="130" width="20" height="25"/>
                        <rect x="115" y="140" width="25" height="15"/>
                        <rect x="85" y="165" width="45" height="20"/>
                        <rect x="140" y="145" width="20" height="40"/>
                        <rect x="170" y="165" width="15" height="20"/>
                    </svg>
                </div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mt-3">NMID: ID102003892011</span>
                <span class="text-xs font-semibold text-gray-800">AGNO STORE OFFICIAL</span>
            </div>

            <!-- Alternative: Bank Transfer -->
            <div class="space-y-3 pt-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block text-center">Atau Transfer Manual</span>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-bold text-gray-800">Bank Central Asia (BCA)</span>
                        <p class="font-mono text-gray-600 font-bold mt-0.5">800-1234-5678</p>
                        <p class="text-[11px] text-gray-400">a.n. PT Agno Digital Store</p>
                    </div>
                </div>

                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-bold text-gray-800">Bank Mandiri</span>
                        <p class="font-mono text-gray-600 font-bold mt-0.5">137-00-1234567-8</p>
                        <p class="text-[11px] text-gray-400">a.n. PT Agno Digital Store</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proof Upload Section -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-6" x-data="{ fileName: null, filePreview: null }">
                <div>
                    <h3 class="font-bold text-gray-900 text-base">Konfirmasi & Bukti Transfer</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Unggah struk atau screenshot bukti berhasil transfer Anda</p>
                </div>

                @if($order->payment?->proof_path)
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                        <span class="text-xs font-bold text-gray-700 block">Bukti yang Terakhir Diunggah:</span>
                        <div class="relative group rounded-xl overflow-hidden border border-gray-200 max-h-48 flex items-center justify-center bg-gray-100">
                            @if(str_ends_with(strtolower($order->payment->proof_path), '.pdf'))
                                <div class="py-8 text-center text-xs text-gray-600">
                                    <i data-lucide="file-text" class="w-10 h-10 mx-auto text-indigo-600 mb-2"></i>
                                    <span>File PDF Bukti Pembayaran</span>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $order->payment->proof_path) }}" alt="Bukti Transfer" class="w-full object-cover max-h-48">
                            @endif
                            <a href="{{ asset('storage/' . $order->payment->proof_path) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5">
                                <i data-lucide="external-link" class="w-4 h-4"></i> Buka Ukuran Penuh
                            </a>
                        </div>
                    </div>
                @endif

                @if($payStatus !== 'verified')
                    <form action="{{ route('user.payment.proof', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2">Pilih File Bukti Pembayaran</label>
                            <div class="border-2 border-dashed border-gray-200 hover:border-indigo-500 rounded-2xl p-6 text-center transition-colors cursor-pointer bg-gray-50/50" @click="$refs.fileInput.click()">
                                <input 
                                    type="file" 
                                    name="proof" 
                                    x-ref="fileInput"
                                    required
                                    accept="image/jpeg,image/png,image/jpg,application/pdf"
                                    class="hidden"
                                    @change="
                                        const file = $refs.fileInput.files[0];
                                        if (file) {
                                            fileName = file.name;
                                            if (file.type.startsWith('image/')) {
                                                const reader = new FileReader();
                                                reader.onload = (e) => { filePreview = e.target.result; };
                                                reader.readAsDataURL(file);
                                            } else {
                                                filePreview = null;
                                            }
                                        }
                                    "
                                >
                                <div class="space-y-2">
                                    <i data-lucide="upload-cloud" class="w-8 h-8 text-indigo-600 mx-auto"></i>
                                    <template x-if="!fileName">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-700">Klik untuk memilih file</p>
                                            <p class="text-[11px] text-gray-400">JPG, PNG, atau PDF (Maks. 5 MB)</p>
                                        </div>
                                    </template>
                                    <template x-if="fileName">
                                        <div class="space-y-2">
                                            <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold truncate max-w-xs" x-text="fileName"></span>
                                            <template x-if="filePreview">
                                                <img :src="filePreview" class="w-24 h-24 object-cover rounded-xl mx-auto border border-gray-200 mt-2">
                                            </template>
                                            <p class="text-[11px] text-gray-400">Klik lagi jika ingin mengganti file</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200 transition-all"
                        >
                            <i data-lucide="send" class="w-4 h-4"></i>
                            {{ $order->payment?->proof_path ? 'Kirim Ulang Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                        </button>
                    </form>
                @else
                    <div class="p-6 bg-emerald-50 border border-emerald-200 rounded-2xl text-center space-y-3">
                        <i data-lucide="check-circle" class="w-10 h-10 text-emerald-600 mx-auto"></i>
                        <h4 class="font-bold text-emerald-900 text-base">Pembayaran Selesai!</h4>
                        <p class="text-xs text-emerald-700">Terima kasih atas pembayaran Anda. Tim gudang kami sedang memproses persiapan pengiriman barang.</p>
                        <a href="{{ route('user.orders.show', $order) }}" class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors">
                            Kembali ke Rincian Pesanan
                        </a>
                    </div>
                @endif
            </div>

            <!-- Steps Guide -->
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200/60 text-xs space-y-2.5 text-gray-600">
                <span class="font-bold text-gray-800 block text-xs mb-1">Panduan Singkat Pembayaran:</span>
                <p>1. Buka aplikasi perbankan atau e-wallet pilihan Anda.</p>
                <p>2. Scan kode QRIS atau transfer ke rekening yang tertera di atas.</p>
                <p>3. Masukkan nominal tagihan sesuai <span class="font-bold text-indigo-600">Rp {{ number_format($order->payment?->amount ?? $order->total_amount, 0, ',', '.') }}</span>.</p>
                <p>4. Simpan bukti transfer dan upload melalui formulir di halaman ini.</p>
                <p>5. Tim kami akan memverifikasi bukti dalam waktu 5-15 menit pada jam kerja.</p>
            </div>
        </div>
    </div>
</div>
@endsection

