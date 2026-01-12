@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Sistem Laporan Kegiatan
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Lapor kegiatan harian dan pantau data dengan mudah.
            </p>
        </div>
        
        <div class="mt-8 space-y-4">
            <a href="{{ route('activities.index') }}" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-lg font-medium rounded-xl text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm hover:shadow-md">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <!-- Icon: Document Search -->
                    <svg class="h-6 w-6 text-indigo-500 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </span>
                Lihat Laporan
            </a>

            <a href="{{ route('activities.create') }}" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-lg font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <!-- Icon: Plus Circle -->
                    <svg class="h-6 w-6 text-indigo-200 group-hover:text-indigo-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
                Tambah Laporan
            </a>
        </div>
    </div>
</div>
@endsection
