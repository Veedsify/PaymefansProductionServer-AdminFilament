<div class="bg-gray-50 rounded-lg p-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm font-medium text-gray-500">Ticket ID</p>
            <p class="text-sm text-gray-900">{{ $ticket->ticket_id }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">User</p>
            <p class="text-sm text-gray-900">{{ $ticket->user->name }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Subject</p>
            <p class="text-sm text-gray-900">{{ $ticket->subject }}</p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Status</p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                   ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst($ticket->status) }}
            </span>
        </div>
    </div>
</div>
