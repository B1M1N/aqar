@extends('layouts.app')
@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')
@section('breadcrumb')
    <span>الرئيسية</span><span class="mx-1">/</span><span class="text-gray-700">الإشعارات</span>
@endsection

@php
$typeIcon = [
    'rent_reminder'      => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
    'lease_expiry'       => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    'payment_received'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'maintenance_update' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
    'monthly_report'     => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
];
$typeColor = [
    'rent_reminder'      => 'bg-amber-100 text-amber-600',
    'lease_expiry'       => 'bg-red-100 text-red-600',
    'payment_received'   => 'bg-emerald-100 text-emerald-600',
    'maintenance_update' => 'bg-blue-100 text-blue-600',
    'monthly_report'     => 'bg-indigo-100 text-indigo-600',
];
$defaultIcon  = 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9';
$defaultColor = 'bg-gray-100 text-gray-600';
@endphp

@section('content')
<div class="space-y-5">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">{{ $notifications->total() }} إشعار</p>
        <form method="POST" action="{{ route('notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="btn-secondary btn-sm">تعيين الكل كمقروء</button>
        </form>
    </div>

    <div class="card divide-y divide-gray-100">
        @forelse($notifications as $notification)
        <div class="flex items-start gap-4 p-4 {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50/30' }} hover:bg-gray-50 transition">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $typeColor[$notification->type] ?? $defaultColor }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="{{ $typeIcon[$notification->type] ?? $defaultIcon }}"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 {{ $notification->read_at ? 'font-normal' : '' }}">
                    {{ $notification->title }}
                </p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $notification->body }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(!$notification->read_at)
            <form method="POST" action="{{ route('notifications.markRead', $notification) }}">
                @csrf
                <button type="submit" class="text-xs text-indigo-500 hover:text-indigo-700 whitespace-nowrap">
                    تعيين كمقروء
                </button>
            </form>
            @endif
        </div>
        @empty
        <div class="p-16 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="mt-3 text-gray-500">لا توجد إشعارات</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="p-4">{{ $notifications->links() }}</div>
    @endif

</div>
@endsection
