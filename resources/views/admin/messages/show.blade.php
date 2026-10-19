@extends('admin.layout')

@section('title', 'Message from ' . $contactMessage->name)

@section('content')
<a href="{{ route('admin.messages.index') }}" class="text-sm text-blue-600 hover:text-blue-800 mb-6 inline-block">&larr; Back to messages</a>

<div class="max-w-2xl bg-white rounded-lg border border-gray-200 p-8">
    <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-100">
        <div>
            <div class="font-semibold text-gray-900 text-lg">{{ $contactMessage->name }}</div>
            <a href="mailto:{{ $contactMessage->email }}" class="text-blue-600 text-sm">{{ $contactMessage->email }}</a>
        </div>
        <div class="text-right text-sm text-gray-400">
            <div class="capitalize">{{ $contactMessage->subject }}</div>
            <div>{{ $contactMessage->created_at->format('M j, Y g:ia') }}</div>
        </div>
    </div>
    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $contactMessage->message }}</p>

    <div class="mt-8 pt-6 border-t border-gray-100">
        <a href="mailto:{{ $contactMessage->email }}?subject=Re: your message to Orphan Prosperity"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-full transition inline-block">
            Reply by Email
        </a>
    </div>
</div>
@endsection
