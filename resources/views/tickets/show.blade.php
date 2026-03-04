<x-app-layout>
<!-- Main Content -->
<div class="ml-64 min-h-screen">
    <div class="p-5 sm:p-7.5 lg:p-9">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">{{ $ticket->code }} - {{ $ticket->title }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Created {{ $ticket->created_at->diffForHumans() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center gap-2 rounded-lg bg-yellow-600 px-4 py-2 font-medium text-white hover:bg-yellow-700">
                    <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.8754 11.6719C16.5379 11.6719 16.2285 11.9531 16.2285 12.3125V15.8125C16.2285 16.2031 15.9191 16.5 15.5879 16.5H2.41191C2.08066 16.5 1.77129 16.2031 1.77129 15.8125V12.3125C1.77129 11.9531 1.46191 11.6719 1.12441 11.6719C0.7871 11.6719 0.478268 11.9531 0.478268 12.3125V15.8125C0.478268 17.0563 1.41566 18 2.41191 18H15.5879C16.5844 18 17.521 17.0563 17.521 15.8125V12.3125C17.521 11.9531 17.2117 11.6719 16.8754 11.6719Z"></path>
                        <path d="M8.55074 12.3469C8.66785 12.4625 8.83121 12.5313 9.04199 12.5313C9.27441 12.5313 9.43457 12.4625 9.55168 12.3469L13.4457 8.5625C13.6801 8.32813 13.6801 7.90625 13.4457 7.67188C13.2113 7.4375 12.7758 7.4375 12.5414 7.67188L9.27441 10.9313V0.84375C9.27441 0.484375 8.96777 0.203125 8.54199 0.203125C8.12402 0.203125 7.8127 0.484375 7.8127 0.84375V10.9313L4.54762 7.67188C4.3127 7.4375 3.87715 7.4375 3.64258 7.67188C3.40801 7.90625 3.40801 8.32813 3.64258 8.5625L8.55074 12.3469Z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this ticket?')" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">
                        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.7499 2.47502H11.5031L10.3468 0.584399C10.2156 0.389848 9.97851 0.270630 9.72157 0.270630H8.28126C8.02431 0.270630 7.78721 0.389848 7.65605 0.584399L6.49968 2.47502H4.24999C3.54374 2.47502 3.03124 3.05252 3.03124 3.78127C3.03124 4.50627 3.54374 5.08752 4.24999 5.08752H5.42499V14.3531C5.42499 15.3625 6.34374 16.1812 7.38749 16.1812H10.6125C11.6562 16.1812 12.575 15.3625 12.575 14.3531V5.08752H13.75C14.4562 5.08752 14.9687 4.50627 14.9687 3.78127C14.9687 3.05252 14.4562 2.47502 13.7499 2.47502ZM7.67499 14.0062C7.35624 14.0062 7.10624 13.7062 7.10624 13.3875V8.0625C7.10624 7.74375 7.40624 7.49375 7.72499 7.49375C8.04374 7.49375 8.29374 7.79375 8.29374 8.1125V13.4375C8.29374 13.7562 7.99374 14.0062 7.67499 14.0062ZM10.6125 14.0062H9.03124V7.49375C9.03124 7.11252 9.33124 6.80627 9.71249 6.80627C10.0937 6.80627 10.3999 7.10627 10.3999 7.48752V13.3812C10.3999 13.7625 10.0937 14.0062 9.71249 14.0062H10.6125Z"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Description Card -->
                <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Description</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $ticket->description }}</p>
                </div>

                <!-- Attachments -->
                <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h2 class="mb-4 text-lg font-bold text-gray-900 dark:text-white">Attachments</h2>
                    @if($ticket->attachments->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No attachments</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($ticket->attachments as $att)
                                <li>
                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($att->file_path) }}" class="text-brand-600 hover:underline" target="_blank">
                                        {{ $att->file_name }}
                                    </a>
                                    <span class="text-xs text-gray-400">uploaded by {{ optional($att->uploader)->name }} {{ $att->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if(auth()->user()->hasAnyRole(['Admin','Teknisi']) || auth()->user()->id === $ticket->requester_id)
                        <form action="{{ url('/api/tickets/'.$ticket->id.'/attachments') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            <input type="file" name="file" required class="block">
                            <button type="submit" class="mt-2 rounded bg-brand-600 px-4 py-2 text-white">Upload</button>
                        </form>
                    @endif
                </div>

                <!-- Details Grid -->
                <div class="mb-6 grid gap-6 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</p>
                        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ $ticket->category }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Requester</p>
                        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ $ticket->requester->name }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Assignee</p>
                        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ optional($ticket->assignee)->name ?? '—' }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Related Asset</p>
                        <p class="mt-2 text-lg font-bold text-gray-900 dark:text-white">{{ optional($ticket->asset)->name ?? '—' }}</p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h3 class="mb-6 text-lg font-bold text-gray-900 dark:text-white">Comments</h3>
                    
                    <!-- Comments List -->
                    <div class="mb-6 space-y-4">
                        @forelse($ticket->comments as $comment)
                            <div class="rounded-lg border border-l-4 border-gray-200 border-l-brand-600 bg-gray-50 p-4 dark:border-gray-700 dark:bg-white/5 dark:border-l-brand-500">
                                <div class="mb-2 flex items-start justify-between">
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $comment->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if($comment->is_internal)
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">Internal</span>
                                    @endif
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $comment->message }}</p>
                                @if($comment->attachments->isNotEmpty())
                                    <div class="mt-2 space-y-1">
                                        @foreach($comment->attachments as $att)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($att->file_path) }}" class="text-sm text-brand-600 hover:underline" target="_blank">{{ $att->file_name }}</a><br />
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400">No comments yet</p>
                        @endforelse
                    </div>

                    <!-- Comment Form -->
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                        <form method="POST" action="{{ route('tickets.comment', $ticket) }}" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Add a comment</label>
                                <textarea id="message" name="message" rows="3" placeholder="Type your comment..." class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder-gray-500 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:placeholder-gray-400 dark:focus:border-brand-600 dark:focus:ring-brand-900/20 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="attachment" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Add attachment (optional)</label>
                                <input type="file" id="attachment" name="attachment" class="w-full">
                            </div>
                            @if(auth()->user()->hasAnyRole(['Admin','Teknisi']))
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Change status</label>
                                    <select id="status" name="status" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 outline-none transition focus:border-brand-600 focus:ring-2 focus:ring-brand-100 dark:border-gray-600 dark:bg-dark-800 dark:text-white dark:focus:border-brand-600 dark:focus:ring-brand-900/20">
                                        <option value="">-- keep current --</option>
                                        @foreach(\App\Models\Ticket::statuses() as $st)
                                            <option value="{{ $st }}" {{ $ticket->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-6 py-2.5 text-center font-medium text-white hover:bg-brand-700">
                                Post Comment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Status Card -->
                <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h3 class="mb-4 text-sm font-bold uppercase text-gray-500 dark:text-gray-400">Status</h3>
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                        @if($ticket->status === \App\Models\Ticket::STATUS_OPEN) bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                        @elseif($ticket->status === \App\Models\Ticket::STATUS_ASSIGNED_DETECT) bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                        @elseif($ticket->status === \App\Models\Ticket::STATUS_SOLVED_WITH_NOTES) bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400
                        @elseif($ticket->status === \App\Models\Ticket::STATUS_SOLVED) bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                        @else bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-400
                        @endif">
                        {{ $ticket->status }}
                    </span>

                    @if(auth()->user()->hasAnyRole(['Admin','Teknisi']))
                        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-500 dark:text-gray-400">Change status</label>
                                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-600 focus:ring focus:ring-brand-200 focus:ring-opacity-50">
                                    @foreach(\App\Models\Ticket::statuses() as $st)
                                        <option value="{{ $st }}" {{ $ticket->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="assignee_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400">Assignee</label>
                                <select id="assignee_id" name="assignee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-600 focus:ring focus:ring-brand-200 focus:ring-opacity-50">
                                    <option value="">-- none --</option>
                                    @foreach(\App\Models\User::whereHas('roles', function($q){ $q->where('name','Teknisi'); })->get() as $user)
                                        <option value="{{ $user->id }}" {{ $ticket->assignee_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2 text-white">Save</button>
                        </form>
                    @endif
                </div>

                <!-- Priority Card -->
                <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h3 class="mb-4 text-sm font-bold uppercase text-gray-500 dark:text-gray-400">Priority</h3>
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium
                        @if($ticket->priority === 'CRITICAL') bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400
                        @elseif($ticket->priority === 'HIGH') bg-orange-100 text-orange-700 dark:bg-orange-500/15 dark:text-orange-400
                        @elseif($ticket->priority === 'MEDIUM') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400
                        @else bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400
                        @endif">
                        {{ $ticket->priority }}
                    </span>
                </div>

                <!-- Timeline Card -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-dark-800">
                    <h3 class="mb-4 text-sm font-bold uppercase text-gray-500 dark:text-gray-400">Timeline</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div>
                                <svg class="h-5 w-5 flex-shrink-0 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Created</p>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div>
                                <svg class="h-5 w-5 flex-shrink-0 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Last Update</p>
                                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
