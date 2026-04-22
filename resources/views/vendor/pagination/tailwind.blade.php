@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.75rem 0;">
    {{-- Info hasil --}}
    <div style="font-size:.8rem;color:var(--text-muted);">
        Menampilkan <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
        dari <strong>{{ $paginator->total() }}</strong> data
    </div>

    {{-- Tombol navigasi --}}
    <div style="display:flex;gap:.35rem;align-items:center;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);color:var(--text-muted);font-size:.8rem;cursor:not-allowed;opacity:.5;">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);color:var(--text-secondary);font-size:.8rem;text-decoration:none;transition:all .15s;" onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">‹</a>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:.35rem .5rem;font-size:.8rem;color:var(--text-muted);">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:.35rem .7rem;border-radius:8px;background:var(--primary);color:white;font-size:.8rem;font-weight:600;min-width:2rem;text-align:center;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);color:var(--text-secondary);font-size:.8rem;text-decoration:none;min-width:2rem;text-align:center;transition:all .15s;" onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);color:var(--text-secondary);font-size:.8rem;text-decoration:none;transition:all .15s;" onmouseover="this.style.background='var(--primary)';this.style.color='white';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='';this.style.color='var(--text-secondary)';this.style.borderColor='var(--border)'">›</a>
        @else
            <span style="padding:.35rem .7rem;border-radius:8px;border:1px solid var(--border);color:var(--text-muted);font-size:.8rem;cursor:not-allowed;opacity:.5;">›</span>
        @endif
    </div>
</nav>
@endif
