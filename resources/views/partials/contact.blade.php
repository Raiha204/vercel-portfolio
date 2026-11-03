<section class="section compact {{ request()->routeIs('home') ? '' : 'section-tint' }}" id="contact" aria-labelledby="contact-title">
    <div class="wrap">
        <div class="section-head reveal">
            <div>
                <p class="eyebrow">Contact</p>
                <h2 id="contact-title">Let&rsquo;s talk</h2>
            </div>
            <p class="section-note">Message me on WhatsApp or Viber, send an email, or find me on GitHub and LinkedIn. Each number, address and link can be copied with one tap.</p>
        </div>

        @php
            // WhatsApp and Viber on the same number are one card with two buttons; anything else stays as it is.
            $digitsOf = fn ($c) => preg_replace('/\D+/', '', (string) ($c['value'] ?? ''));
            $byKey = collect($channels)->keyBy('key');
            $whatsapp = $byKey->get('whatsapp');
            $viber = $byKey->get('viber');
            $sharedChat = $whatsapp && $viber && $whatsapp['href'] && $viber['href'] && $digitsOf($whatsapp) === $digitsOf($viber);
            $cards = [];
            foreach ($channels as $channel) {
                if ($sharedChat && $channel['key'] === 'viber') { continue; }
                $cards[] = ($sharedChat && $channel['key'] === 'whatsapp') ? ['key' => 'chat'] + $channel : $channel;
            }
            $ordered = collect($cards)->sortBy(fn ($c) => $c['key'] === 'email' ? 0 : 1)->values();
            $others = max(1, $ordered->where('key', '!=', 'email')->count());
            $span = max(3, intdiv(12, $others));
        @endphp
        <ul class="contact-grid" style="--span: {{ $span }}">
            @foreach ($ordered as $channel)
                <li class="contact-card reveal{{ $channel['key'] === 'email' ? ' is-primary' : '' }}" style="--d: {{ $loop->index }}">
                    @if ($channel['key'] === 'chat')
                        <span class="contact-icons">
                            <span class="contact-icon is-whatsapp"><x-brand-icon name="whatsapp" /></span>
                            <span class="contact-icon is-viber"><x-brand-icon name="viber" /></span>
                        </span>
                        <div class="contact-text">
                            <h3>WhatsApp &amp; Viber</h3>
                            <p class="contact-value" title="{{ $channel['value'] }}">{{ $channel['value'] }}</p>
                        </div>
                        <div class="contact-actions">
                            <a class="btn btn-primary" href="{{ $whatsapp['href'] }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
                            <a class="btn btn-ghost" href="{{ $viber['href'] }}" data-app-link data-app-name="Viber">Viber</a>
                            <button type="button" class="btn btn-ghost btn-copy" title="Copy number" data-copy="{{ $channel['value'] }}" data-copy-label="Number">
                                <x-icon name="copy" /><span class="visually-hidden">Copy</span>
                            </button>
                        </div>
                        <p class="contact-hint" data-app-hint hidden>Could not open Viber on this device, so the number was copied. Paste it into the app.</p>
                    @else
                        <span class="contact-icon is-{{ $channel['key'] }}">
                            @if ($channel['key'] === 'email')
                                <x-icon name="mail" />
                            @else
                                <x-brand-icon :name="$channel['key']" />
                            @endif
                        </span>
                        <div class="contact-text">
                            <h3>{{ $channel['label'] }}</h3>
                            <p class="contact-value" title="{{ $channel['value'] }}">@if ($channel['key'] === 'email'){!! str_replace('@', '@<wbr>', e($channel['value'])) !!}@else{{ $channel['value'] }}@endif</p>
                        </div>
                        <div class="contact-actions">
                            @if ($channel['href'])
                                @if (str_starts_with($channel['href'], 'http'))
                                    <a class="btn btn-primary" href="{{ $channel['href'] }}" target="_blank" rel="noopener noreferrer">{{ $channel['cta'] }}</a>
                                @else
                                    <a class="btn btn-primary" href="{{ $channel['href'] }}" data-app-link data-app-name="{{ $channel['label'] }}">{{ $channel['cta'] }}</a>
                                @endif
                            @endif
                            <button type="button" class="btn btn-ghost btn-copy" title="Copy {{ $channel['label'] }}" data-copy="{{ $channel['copy'] ?? $channel['value'] }}" data-copy-label="{{ $channel['label'] }}">
                                <x-icon name="copy" /><span class="visually-hidden">Copy</span>
                            </button>
                        </div>
                        @if ($channel['href'] && ! str_starts_with($channel['href'], 'http'))
                        <p class="contact-hint" data-app-hint hidden>Could not open {{ $channel['label'] }} on this device, so the {{ $channel['key'] === 'email' ? 'address' : 'number' }} was copied. Paste it into the app.</p>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
        <p class="visually-hidden" id="copy-status" role="status" aria-live="polite"></p>
    </div>
</section>
