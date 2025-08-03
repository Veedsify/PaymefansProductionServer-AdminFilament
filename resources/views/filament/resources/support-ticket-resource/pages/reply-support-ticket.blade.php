<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ticket Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Ticket ID</p>
                    <p class="text-sm text-gray-900">{{ $ticket->ticket_id }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($ticket->status === 'open') bg-green-100 text-green-800
                        @elseif($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">User</p>
                    <p class="text-sm text-gray-900">{{ $ticket->user->name }} ({{ $ticket->user->email }})</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Created</p>
                    <p class="text-sm text-gray-900">{{ $ticket->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Subject</p>
                <p class="text-sm text-gray-900">{{ $ticket->subject }}</p>
            </div>
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-500">Original Message</p>
                <div class="mt-2 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700">{{ $ticket->message }}</p>
                </div>
            </div>
        </div>

        @if($ticket->supportTicketReplies->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Previous Replies</h3>
            <div class="space-y-4">
                @foreach($ticket->supportTicketReplies as $reply)
                <div class="border-l-4 @if($reply->user->admin) border-blue-500 @else border-gray-300 @endif pl-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $reply->user->name }}
                            @if($reply->user->admin)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Admin</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $reply->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-700">{{ $reply->message }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <form wire:submit="sendReply">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament::button type="submit" size="lg">
                    Send Reply
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
