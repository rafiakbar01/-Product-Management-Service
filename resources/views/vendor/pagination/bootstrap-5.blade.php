@if ($paginator->hasPages())
    <nav style="display:flex;align-items:center;gap:4px;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--clr-border);color:var(--clr-muted);font-size:0.8rem;opacity:0.4;cursor:not-allowed;">
                <i class="bi bi-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--clr-border);color:var(--clr-muted);font-size:0.8rem;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.borderColor='var(--clr-primary)';this.style.color='var(--clr-primary)';" onmouseout="this.style.borderColor='var(--clr-border)';this.style.color='var(--clr-muted)';">
                <i class="bi bi-chevron-left"></i>
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;color:var(--clr-muted);font-size:0.8rem;">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:var(--clr-primary);color:#fff;font-size:0.8rem;font-weight:700;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--clr-border);color:var(--clr-muted);font-size:0.8rem;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.borderColor='var(--clr-primary)';this.style.color='var(--clr-primary)';" onmouseout="this.style.borderColor='var(--clr-border)';this.style.color='var(--clr-muted)';">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--clr-border);color:var(--clr-muted);font-size:0.8rem;text-decoration:none;transition:all 0.15s;" onmouseover="this.style.borderColor='var(--clr-primary)';this.style.color='var(--clr-primary)';" onmouseout="this.style.borderColor='var(--clr-border)';this.style.color='var(--clr-muted)';">
                <i class="bi bi-chevron-right"></i>
            </a>
        @else
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid var(--clr-border);color:var(--clr-muted);font-size:0.8rem;opacity:0.4;cursor:not-allowed;">
                <i class="bi bi-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
