@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showModal: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Buku Alamat</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar alamat pengiriman untuk proses checkout yang lebih cepat</p>
        </div>

        <button @click="showModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-2xl shadow-md shadow-indigo-200 transition-all self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah Alamat Baru
        </button>
    </div>

    <!-- Address Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($addresses as $addr)
            <div class="bg-white p-6 rounded-3xl border {{ $addr->is_default ? 'border-indigo-500 ring-2 ring-indigo-50' : 'border-gray-100' }} shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 text-xs font-bold uppercase rounded-lg bg-gray-100 text-gray-700">{{ $addr->label }}</span>
                            @if($addr->is_default)
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-100 text-indigo-700">Utama</span>
                            @endif
                        </div>

                        <form action="{{ route('user.addresses.destroy', $addr) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Alamat">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                    <h3 class="font-bold text-gray-900 text-base">{{ $addr->recipient_name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $addr->phone }}</p>

                    <div class="mt-3 text-xs text-gray-600 leading-relaxed space-y-0.5">
                        <p>{{ $addr->address }}</p>
                        <p>{{ $addr->district }}, {{ $addr->city }}</p>
                        <p>{{ $addr->province }}, {{ $addr->postal_code }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-dashed border-gray-200 p-12 text-center max-w-lg mx-auto">
                <i data-lucide="map-pin-off" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <h3 class="text-base font-bold text-gray-800">Belum Ada Alamat Tersimpan</h3>
                <p class="text-xs text-gray-400 mt-1 mb-6">Tambahkan alamat pengiriman pertama Anda untuk mulai berbelanja.</p>
                <button @click="showModal = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition-all">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Alamat Sekarang
                </button>
            </div>
        @endforelse
    </div>

    <!-- Modal Form Tambah Alamat -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 space-y-5" @click.away="showModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-900 text-lg">Tambah Alamat Baru</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('user.addresses.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Label Alamat</label>
                        <input type="text" name="label" required placeholder="Contoh: Rumah, Kantor" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nama Penerima</label>
                        <input type="text" name="recipient_name" required placeholder="Nama lengkap" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" required placeholder="08xxxxxxxxxx" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Alamat Lengkap (Jalan, No. Rumah, RT/RW)</label>
                    <textarea name="address" required rows="2" placeholder="Nama jalan, nomor rumah, patokan..." class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600 resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kecamatan</label>
                        <input type="text" name="district" required placeholder="Kecamatan" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kota / Kabupaten</label>
                        <input type="text" name="city" required placeholder="Kota" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Provinsi</label>
                        <input type="text" name="province" required placeholder="Provinsi" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" name="postal_code" required placeholder="12345" class="w-full p-2.5 border border-gray-200 rounded-xl outline-none focus:border-indigo-600">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_default" name="is_default" value="1" class="w-4 h-4 text-indigo-600 rounded">
                    <label for="is_default" class="text-xs text-gray-700 cursor-pointer">Jadikan sebagai alamat utama</label>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

