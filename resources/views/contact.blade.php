<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ $dir }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Contact Us') }} | Orphan Prosperity</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.i18n-head')
    <style>
        :root{--primary:#2563EB;--primary-deep:#1D4ED8;--accent:#60A5FA;--accent-deep:#3B82F6;--ink:#1F2937;--ink-soft:#6B7280;--paper:#FFFFFF;--paper-deep:#F9FAFB;--line:#E5E7EB;--dark:#111827;--radius:8px;}
        .contact-scope *{box-sizing:border-box;}
        .contact-scope{font-family:ui-sans-serif,system-ui,-apple-system,'Segoe UI',sans-serif;color:var(--ink);line-height:1.6;}
        .contact-scope h1,.contact-scope h2,.contact-scope h3{font-weight:800;margin:0;letter-spacing:-0.01em;}
        .contact-scope a{color:inherit;text-decoration:none;}
        .contact-scope img{max-width:100%;display:block;}
        .wrap{max-width:1120px;margin:0 auto;padding:0 24px;}
        @media (prefers-reduced-motion: reduce){.contact-scope *{animation-duration:0.01ms !important; transition-duration:0.01ms !important;}}

        .btn{display:inline-flex;align-items:center;justify-content:center;padding:13px 28px;border-radius:999px;font-size:0.95rem;font-weight:600;cursor:pointer;border:1px solid transparent;transition:transform .15s ease, box-shadow .15s ease, background .2s ease;}
        .btn-primary{background:var(--primary);color:#fff;}
        .btn-primary:hover{background:var(--primary-deep);transform:translateY(-1px);box-shadow:0 8px 18px rgba(37,99,235,0.25);}

        /* Hero */
        .hero{padding:72px 0 56px;overflow:hidden;background:var(--paper);}
        .hero-grid{display:grid;grid-template-columns:1.1fr 0.9fr;gap:56px;align-items:center;}
        @media (max-width:860px){.hero-grid{grid-template-columns:1fr;gap:32px;}}
        .eyebrow-line{display:flex;align-items:center;gap:10px;color:var(--primary-deep);font-size:0.9rem;font-weight:600;margin-bottom:18px;}
        .eyebrow-line .dot{width:6px;height:6px;border-radius:50%;background:var(--accent-deep);}
        .hero h1{font-size:clamp(2.1rem,4vw,3.2rem);line-height:1.12;color:var(--dark);}
        .hero h1 em{font-style:normal;color:var(--primary);}
        .hero p.lead{margin-top:20px;font-size:1.08rem;color:var(--ink-soft);max-width:46ch;}

        .mail-illustration{position:relative;height:320px;}
        .mail-illustration svg{width:100%;height:100%;}
        .mail-illustration .stroke{fill:none;stroke:var(--primary);stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:900;stroke-dashoffset:900;animation:draw 2s ease forwards .2s;}
        .mail-illustration .plane{fill:var(--accent-deep);opacity:0;animation:fly 1.4s cubic-bezier(.4,0,.2,1) forwards 1.1s;}
        .mail-illustration .spark{fill:var(--accent-deep);opacity:0;animation:pop .5s ease forwards;}
        .mail-illustration .spark:nth-of-type(1){animation-delay:1.7s;}
        .mail-illustration .spark:nth-of-type(2){animation-delay:1.9s;}
        .mail-illustration .spark:nth-of-type(3){animation-delay:2.1s;}
        @keyframes draw{to{stroke-dashoffset:0;}}
        @keyframes fly{0%{opacity:0;transform:translate(0,0) rotate(0deg);}15%{opacity:1;}100%{opacity:1;transform:translate(70px,-60px) rotate(-14deg);}}
        @keyframes pop{0%{opacity:0;transform:scale(0.3);}60%{opacity:1;transform:scale(1.2);}100%{opacity:1;transform:scale(1);}}

        /* Trust strip */
        .trust{border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:34px 0;background:var(--paper-deep);}
        .trust-grid{display:grid;grid-template-columns:repeat(4,1fr);}
        @media (max-width:700px){.trust-grid{grid-template-columns:repeat(2,1fr);gap:24px 0;}}
        .trust-item{text-align:center;padding:0 12px;border-inline-start:1px solid var(--line);}
        .trust-item:first-child{border-inline-start:none;}
        @media (max-width:700px){.trust-item:nth-child(odd){border-inline-start:none;}}
        .trust-num{font-size:2rem;font-weight:800;color:var(--dark);}
        .trust-label{color:var(--ink-soft);font-size:0.88rem;margin-top:4px;}

        /* Contact section */
        .reach{padding:88px 0 40px;}
        .reach-head{max-width:56ch;margin-bottom:48px;}
        .reach-head h2{font-size:clamp(1.6rem,3vw,2.2rem);color:var(--dark);}
        .reach-head p{color:var(--ink-soft);margin-top:12px;}
        .reach-layout{display:grid;grid-template-columns:1.15fr 0.85fr;gap:44px;align-items:start;}
        @media (max-width:900px){.reach-layout{grid-template-columns:1fr;}}

        .subject-row{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:26px;}
        .subject-pill{border:1px solid var(--line);border-radius:999px;background:#fff;padding:10px 18px;font-size:0.88rem;font-weight:500;color:var(--ink-soft);cursor:pointer;transition:border-color .2s ease, background .2s ease, color .2s ease;}
        .subject-pill:hover{border-color:var(--accent-deep);}
        .subject-pill.active{background:var(--primary);border-color:var(--primary);color:#fff;}

        .field{margin-bottom:16px;}
        .field label{display:block;font-size:0.82rem;color:var(--ink-soft);margin-bottom:6px;}
        .field input,.field textarea{width:100%;border:1px solid var(--line);border-radius:var(--radius);background:#fff;padding:13px 14px;font-family:inherit;font-size:0.95rem;color:var(--ink);transition:border-color .2s ease, box-shadow .2s ease;}
        .field input:focus,.field textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,0.12);}
        .field textarea{resize:vertical;min-height:130px;}

        .contact-panel{position:sticky;top:88px;border-radius:var(--radius);background:var(--dark);color:#F3F4F6;padding:32px 28px;}
        .contact-panel .label{font-size:0.82rem;color:#9CA3AF;margin-bottom:6px;}
        .contact-panel .dept{font-size:1.5rem;font-weight:800;color:#fff;}
        .contact-panel .desc{margin-top:10px;font-size:0.9rem;color:#D1D5DB;min-height:44px;}
        .contact-panel .response{display:flex;align-items:baseline;gap:8px;margin-top:18px;padding-top:18px;border-top:1px solid rgba(255,255,255,0.12);}
        .contact-panel .response .num{font-size:2rem;font-weight:800;color:var(--accent);}
        .contact-panel .response .txt{font-size:0.8rem;color:#9CA3AF;}
        .contact-details{margin-top:26px;padding-top:22px;border-top:1px solid rgba(255,255,255,0.12);display:flex;flex-direction:column;gap:14px;font-size:0.88rem;}
        .contact-details .row{display:flex;gap:10px;align-items:flex-start;}
        .contact-details .icon{width:20px;flex-shrink:0;color:var(--accent);}
        .contact-details a{color:#F3F4F6;border-bottom:1px solid rgba(255,255,255,0.25);transition:border-color .2s ease;}
        .contact-details a:hover{border-color:var(--accent);}

        .map-strip{padding:0 0 96px;}
        .map-frame{border-radius:var(--radius);overflow:hidden;border:1px solid var(--line);height:340px;}
        .map-frame iframe{width:100%;height:100%;border:0;display:block;}

        .reveal{opacity:0;transform:translateY(14px);transition:opacity .6s ease, transform .6s ease;}
        .reveal.in{opacity:1;transform:translateY(0);}

        .toast{position:fixed;top:20px;inset-inline-end:20px;z-index:100;max-width:340px;background:#fff;border:1px solid var(--line);border-radius:var(--radius);box-shadow:0 12px 30px rgba(17,24,39,0.15);padding:16px 18px;display:flex;gap:12px;align-items:flex-start;animation:toastIn .35s ease-out both;}
        .toast .check{width:26px;height:26px;border-radius:50%;background:var(--primary);color:#fff;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.8rem;}
        .toast .msg{font-size:0.88rem;color:var(--ink-soft);}
        .toast .msg strong{display:block;color:var(--ink);font-size:0.92rem;margin-bottom:2px;}
        @keyframes toastIn{from{opacity:0;transform:translateX(20px);}to{opacity:1;transform:translateX(0);}}
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

@php
    // [value, button label, team name, short description, average reply hours]
    $subjects = [
        ['general', 'General Inquiry', 'General Inquiries', "For anything that doesn't fit elsewhere — we'll route it to the right team.", 24],
        ['sponsorship', 'Sponsorship', 'Sponsorship Team', 'Questions about sponsoring a child or an existing sponsorship.', 12],
        ['volunteer', 'Volunteering', 'Amin Program', 'Volunteering, mission assignments, or verification status.', 48],
        ['partnership', 'Partnership', 'Partnerships Desk', 'Organizations, schools, or businesses looking to partner with us.', 72],
        ['press', 'Media / Press', 'Press & Media', 'Interview requests, press kits, and media inquiries.', 24],
    ];

    $trust = [
        [24, 'Hour average reply time'],
        [6, 'People on the response team'],
        [3, 'Languages spoken'],
        [98, '% of messages resolved in one reply'],
    ];
@endphp

    {{-- ============ NAVBAR ============ --}}
    @include('partials.navbar')

    <div class="contact-scope">

        @if (session('success'))
            <div class="toast" id="toast">
                <span class="check">✓</span>
                <div class="msg"><strong>{{ __('Message sent') }}</strong>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Hero --}}
        <section class="hero">
            <div class="wrap hero-grid">
                <div>
                    <div class="eyebrow-line"><span class="dot"></span> {{ __('We read every message ourselves') }}</div>
                    <h1>{!! __("Let's start a :word.", ['word' => '<em>' . e(__('conversation')) . '</em>']) !!}</h1>
                    <p class="lead">{{ __("Question about a program, curious how a donation is used, or want to bring your skills to the field? Tell us what's on your mind — a real person replies, not a template.") }}</p>
                </div>
                <div class="mail-illustration" aria-hidden="true">
                    <svg viewBox="0 0 320 300">
                        <path class="stroke" d="M40 90 L160 170 L280 90" />
                        <path class="stroke" d="M40 90 L40 220 L280 220 L280 90 L160 20 L40 90 Z" />
                        <path class="plane" d="M150 150 L200 130 L170 165 L178 190 L150 150 Z" />
                        <circle class="spark" cx="235" cy="95" r="4"/>
                        <circle class="spark" cx="252" cy="75" r="3"/>
                        <circle class="spark" cx="220" cy="65" r="3.5"/>
                    </svg>
                </div>
            </div>
        </section>

        {{-- Trust strip --}}
        <section class="trust">
            <div class="wrap trust-grid">
                @foreach ($trust as [$count, $label])
                    <div class="trust-item"><div class="trust-num" data-count="{{ $count }}">0</div><div class="trust-label">{{ __($label) }}</div></div>
                @endforeach
            </div>
        </section>

        {{-- Contact form --}}
        <section class="reach">
            <div class="wrap">
                <div class="reach-head reveal">
                    <h2>{{ __('Tell us what you need') }}</h2>
                    <p>{{ __('Pick what this is about — the right person picks it up faster.') }}</p>
                </div>

                <div class="reach-layout">
                    <form method="POST" action="{{ url('/contact') }}" id="contactForm">
                        @csrf
                        <div class="subject-row" id="subjectRow">
                            @foreach ($subjects as [$value, $label, $dept, $desc, $hours])
                                <button type="button" class="subject-pill {{ $loop->first ? 'active' : '' }}"
                                        data-subject="{{ $value }}"
                                        data-dept="{{ __($dept) }}"
                                        data-desc="{{ __($desc) }}"
                                        data-hours="{{ $hours }}">{{ __($label) }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="subject" id="subjectInput" value="general">

                        <div class="field">
                            <label for="name">{{ __('Your name') }}</label>
                            <input type="text" id="name" name="name" required placeholder="{{ __('Jane Doe') }}">
                        </div>
                        <div class="field">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" id="email" name="email" required placeholder="jane@email.com">
                        </div>
                        <div class="field">
                            <label for="message">{{ __('Message') }}</label>
                            <textarea id="message" name="message" required placeholder="{{ __('What would you like to tell us?') }}"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Send Message') }}</button>
                    </form>

                    <div class="contact-panel reveal" id="contactPanel">
                        <div class="label">{{ __('This goes to') }}</div>
                        <div class="dept" id="panelDept">{{ __($subjects[0][2]) }}</div>
                        <div class="desc" id="panelDesc">{{ __($subjects[0][3]) }}</div>
                        <div class="response">
                            <span class="num" id="panelHours">24</span>
                            <span class="txt">{{ __('hour average response time') }}</span>
                        </div>

                        <div class="contact-details">
                            <div class="row">
                                <span class="icon">✉</span>
                                <div><a href="mailto:hello@orphanprosperity.org" dir="ltr">hello@orphanprosperity.org</a></div>
                            </div>
                            <div class="row">
                                <span class="icon">☎</span>
                                <div><a href="tel:+93700000000" dir="ltr">+93 70 000 0000</a></div>
                            </div>
                            <div class="row">
                                <span class="icon">⚑</span>
                                <div>{{ __('Shar-e-Naw, Kabul, Afghanistan') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Map (Afghanistan) --}}
        <section class="map-strip">
            <div class="wrap reveal">
                <div class="map-frame">
                    <iframe src="https://www.google.com/maps?q=Kabul,+Afghanistan&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

    </div>

    {{-- ============ FOOTER ============ --}}
    @include('partials.footer', ['gallery' => true])

    <script>
        function animateNumber(el, from, to, duration = 500) {
            const start = performance.now();
            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(from + (to - from) * eased);
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }

        // Subject selector
        const pills = Array.from(document.querySelectorAll('.subject-pill'));
        const subjectInput = document.getElementById('subjectInput');
        const panelDept = document.getElementById('panelDept');
        const panelDesc = document.getElementById('panelDesc');
        const panelHours = document.getElementById('panelHours');

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                subjectInput.value = pill.dataset.subject;
                panelDept.textContent = pill.dataset.dept;
                panelDesc.textContent = pill.dataset.desc;
                animateNumber(panelHours, parseInt(panelHours.textContent, 10) || 0, parseInt(pill.dataset.hours, 10));
            });
        });

        // Reveal + count-up on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                if (entry.target.classList.contains('trust-num')) {
                    animateNumber(entry.target, 0, parseInt(entry.target.dataset.count, 10), 900);
                }
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.4 });
        document.querySelectorAll('.reveal, .trust-num').forEach(el => observer.observe(el));

        // Auto-dismiss success toast
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity .3s ease, transform .3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>