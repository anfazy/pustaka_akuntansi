<div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium mr-2" style="background: rgba(2, 44, 34, 0.65); border: 1px solid rgba(4, 120, 87, 0.7); color: #ecfdf5;">
    <svg class="w-3.5 h-3.5" style="color: #fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 3.75m-15 0h18m-18 0v11.25a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25V7.5M4.5 19.5h15" />
    </svg>
    <span>{{ \Carbon\Carbon::now(config('app.timezone', 'Asia/Jakarta'))->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
</div>
