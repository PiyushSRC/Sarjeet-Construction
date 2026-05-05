/* Sarjeet Construction — vanilla JS port of the prototype's React interactivity. */
(function () {
	'use strict';

	const onReady = (fn) => document.readyState !== 'loading'
		? fn()
		: document.addEventListener('DOMContentLoaded', fn);

	onReady(function () {
		initStickyHeader();
		initScrollSpy();
		initReveal();
		initCounters();
		initProjects();
		initProjectsArchiveFilters();
		initClientMarqueeTouch();
		initBackToTop();
		initMobileMenu();
		initContactForm();
		initContactPageStepper();
	});

	/* ---------- Clients marquee — pause on touch ---------- */
	function initClientMarqueeTouch() {
		const m = document.querySelector('.client-marquee');
		if (!m) return;
		const track = m.querySelector('.client-marquee__track');
		if (!track) return;
		let pauseTimer = null;
		const pause = () => {
			track.style.animationPlayState = 'paused';
			if (pauseTimer) clearTimeout(pauseTimer);
			pauseTimer = setTimeout(() => { track.style.animationPlayState = ''; pauseTimer = null; }, 3000);
		};
		m.addEventListener('touchstart', pause, { passive: true });
		// Pause the marquee when the tab is hidden — no point animating when nobody can see it
		document.addEventListener('visibilitychange', () => {
			track.style.animationPlayState = document.hidden ? 'paused' : '';
		});
	}

	/* ---------- Contact page progressive stepper ---------- */
	function initContactPageStepper() {
		const form = document.querySelector('form[data-stepper]');
		if (!form) return;
		const steps = Array.from(form.querySelectorAll('[data-step]'));
		const pills = Array.from(form.querySelectorAll('[data-step-pill]'));
		const prevBtn = form.querySelector('[data-step-prev]');
		const nextBtn = form.querySelector('[data-step-next]');
		const submitBtn = form.querySelector('[data-step-submit]');
		if (!steps.length || !prevBtn || !nextBtn || !submitBtn) return;

		let current = 0;
		const total = steps.length;

		const showStep = (index) => {
			current = Math.max(0, Math.min(total - 1, index));
			steps.forEach((s, i) => s.classList.toggle('is-active', i === current));
			pills.forEach((p, i) => {
				p.classList.toggle('is-active', i === current);
				p.classList.toggle('is-complete', i < current);
			});
			prevBtn.hidden = current === 0;
			nextBtn.hidden = current === total - 1;
			submitBtn.hidden = current !== total - 1;
			// Focus the first invalid/empty input on the new step
			const firstField = steps[current].querySelector('input, select, textarea');
			if (firstField && current > 0) firstField.focus({ preventScroll: true });
		};

		const setFieldError = (el, msg) => {
			const field = el.closest('.field') || el.parentElement;
			if (!field) return;
			let err = field.querySelector('.field-error');
			if (msg) {
				if (!err) {
					err = document.createElement('span');
					err.className = 'field-error';
					field.appendChild(err);
				}
				err.textContent = msg;
			} else if (err) {
				err.remove();
			}
		};

		const validateCurrent = () => {
			const inputs = Array.from(steps[current].querySelectorAll('input, select, textarea'));
			let ok = true;
			inputs.forEach((el) => {
				const name = (el.previousElementSibling && el.previousElementSibling.textContent) || el.name || 'This field';
				const cleanName = name.replace(/\s*\*\s*$/, '').trim();
				if (el.required && !el.value.trim()) {
					el.setAttribute('aria-invalid', 'true');
					setFieldError(el, cleanName + ' is required.');
					if (ok) el.focus({ preventScroll: false });
					ok = false;
				} else if (el.type === 'email' && el.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value.trim())) {
					el.setAttribute('aria-invalid', 'true');
					setFieldError(el, 'Enter a valid email address.');
					if (ok) el.focus({ preventScroll: false });
					ok = false;
				} else {
					el.removeAttribute('aria-invalid');
					setFieldError(el, '');
				}
			});
			return ok;
		};

		nextBtn.addEventListener('click', () => {
			if (validateCurrent()) showStep(current + 1);
		});
		prevBtn.addEventListener('click', () => showStep(current - 1));

		// Block native submit if prior steps' required fields are empty.
		form.addEventListener('submit', (e) => {
			for (let i = 0; i < total; i++) {
				const inputs = Array.from(steps[i].querySelectorAll('input, select, textarea'));
				const missing = inputs.find((el) => el.required && !el.value.trim());
				if (missing) {
					e.preventDefault();
					showStep(i);
					missing.focus();
					return;
				}
			}
		});

		// Allow Enter inside text inputs to advance instead of submitting prematurely.
		form.addEventListener('keydown', (e) => {
			if (e.key !== 'Enter') return;
			if (e.target.tagName === 'TEXTAREA') return;
			if (current < total - 1) {
				e.preventDefault();
				if (validateCurrent()) showStep(current + 1);
			}
		});

		showStep(0);
	}

	/* ---------- Back-to-top floating button ---------- */
	function initBackToTop() {
		if (document.querySelector('.back-to-top')) return;
		const btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'back-to-top';
		btn.setAttribute('aria-label', 'Back to top');
		btn.innerHTML = '↑';
		document.body.appendChild(btn);

		btn.addEventListener('click', () => {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});

		const onScroll = () => {
			btn.classList.toggle('is-visible', window.scrollY > 600);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	/* ---------- Clients carousel (homepage) ---------- */
	function initClientsCarousel() {
		const carousel = document.querySelector('.client-carousel');
		if (!carousel) return;

		const slides = Array.from(carousel.querySelectorAll('.client-slide'));
		const dots   = Array.from(carousel.querySelectorAll('.client-carousel__dot'));
		const bar    = carousel.querySelector('.client-carousel__progress span');
		if (!slides.length) return;

		const total = slides.length;
		let index = 0;
		let timer = null;
		const delay = parseInt(carousel.dataset.autoplay || '4000', 10);

		const restartProgress = () => {
			if (!bar) return;
			bar.style.transition = 'none';
			bar.style.transform = 'scaleX(0)';
			void bar.offsetWidth;
			bar.style.transition = 'transform ' + delay + 'ms linear';
			bar.style.transform = 'scaleX(1)';
		};
		const pauseProgress = () => {
			if (!bar) return;
			const cs = getComputedStyle(bar).transform;
			bar.style.transition = 'none';
			bar.style.transform = cs;
		};

		const show = (n) => {
			index = (n + total) % total;
			slides.forEach((s, i) => {
				const active = i === index;
				s.classList.toggle('is-active', active);
				if (active) s.removeAttribute('aria-hidden');
				else s.setAttribute('aria-hidden', 'true');
			});
			dots.forEach((d, i) => {
				const active = i === index;
				d.classList.toggle('is-active', active);
				d.setAttribute('aria-selected', active ? 'true' : 'false');
			});
		};

		const stop = () => {
			if (timer) { clearInterval(timer); timer = null; }
			pauseProgress();
		};
		const start = () => {
			stop();
			if (delay > 0) {
				restartProgress();
				timer = setInterval(() => { show(index + 1); restartProgress(); }, delay);
			}
		};

		dots.forEach((d) => d.addEventListener('click', () => {
			show(parseInt(d.dataset.index || '0', 10));
			start();
		}));

		carousel.addEventListener('mouseenter', stop);
		carousel.addEventListener('mouseleave', start);
		carousel.addEventListener('focusin', stop);
		carousel.addEventListener('focusout', start);

		// Pause when the tab itself is hidden (saves CPU/battery in background tabs)
		document.addEventListener('visibilitychange', () => {
			if (document.hidden) stop();
			else start();
		});

		if ('IntersectionObserver' in window) {
			const io = new IntersectionObserver((entries) => {
				entries.forEach((e) => { e.isIntersecting ? start() : stop(); });
			}, { threshold: 0.05 });
			io.observe(carousel);
		} else {
			start();
		}
		start();

		carousel.addEventListener('keydown', (e) => {
			if (e.key === 'ArrowLeft')  { show(index - 1); start(); }
			if (e.key === 'ArrowRight') { show(index + 1); start(); }
		});
	}

	/* ---------- All-projects archive page: category filters ---------- */
	function initProjectsArchiveFilters() {
		const grid = document.querySelector('.all-projects #proj-grid');
		if (!grid) return;
		const cards = Array.from(grid.querySelectorAll('.proj-card'));
		const btns  = document.querySelectorAll('.all-projects [data-filter]');
		btns.forEach((b) => {
			b.addEventListener('click', () => {
				const f = b.dataset.filter;
				btns.forEach((x) => x.setAttribute('aria-pressed', x === b ? 'true' : 'false'));
				cards.forEach((card) => {
					const show = f === 'All' || card.dataset.cat === f;
					card.style.display = show ? '' : 'none';
				});
			});
		});
	}

	/* ---------- Sticky header (solid white from page load) ---------- */
	function initStickyHeader() {
		const header = document.getElementById('site-header');
		if (!header) return;
		const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 60 || true); // always solid per spec
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	/* ---------- Scroll-spy ---------- */
	function initScrollSpy() {
		const ids = ['hero', 'about', 'projects', 'clients', 'contact'];
		const linkBy = {};
		document.querySelectorAll('[data-spy]').forEach((a) => {
			const id = a.dataset.spy;
			(linkBy[id] = linkBy[id] || []).push(a);
		});
		if (!Object.keys(linkBy).length) return;

		const setActive = (id) => {
			Object.keys(linkBy).forEach((k) => {
				linkBy[k].forEach((a) => a.classList.toggle('active', k === id));
			});
		};

		const obs = new IntersectionObserver(
			(entries) => {
				entries.forEach((e) => { if (e.isIntersecting) setActive(e.target.id); });
			},
			{ threshold: 0.2, rootMargin: '-30% 0px -50% 0px' }
		);
		ids.forEach((id) => {
			const el = document.getElementById(id);
			if (el) obs.observe(el);
		});
	}

	/* ---------- Reveal-on-scroll ---------- */
	function initReveal() {
		const els = document.querySelectorAll('.reveal, .reveal-stagger');
		if (!els.length) return;
		const obs = new IntersectionObserver(
			(entries) => {
				entries.forEach((e) => {
					if (e.isIntersecting) {
						e.target.classList.add('in');
						obs.unobserve(e.target);
					}
				});
			},
			{ threshold: 0.12, rootMargin: '0px 0px -8% 0px' }
		);
		els.forEach((el) => obs.observe(el));
	}

	/* ---------- Animated counters ---------- */
	function initCounters() {
		const els = document.querySelectorAll('[data-count-to]');
		if (!els.length) return;

		const animate = (el) => {
			const target = parseFloat(String(el.dataset.countTo).replace(/[^0-9.]/g, '')) || 0;
			const dur = 1600;
			const start = performance.now();
			const fmt = (n) => Math.round(n).toLocaleString('en-IN');
			const tick = (t) => {
				const k = Math.min(1, (t - start) / dur);
				const eased = 1 - Math.pow(1 - k, 3);
				el.textContent = fmt(target * eased);
				if (k < 1) requestAnimationFrame(tick);
				else el.textContent = fmt(target);
			};
			requestAnimationFrame(tick);
		};

		const obs = new IntersectionObserver(
			(entries) => {
				entries.forEach((e) => {
					if (e.isIntersecting && !e.target.dataset.counted) {
						e.target.dataset.counted = '1';
						animate(e.target);
						obs.unobserve(e.target);
					}
				});
			},
			{ threshold: 0.3 }
		);
		els.forEach((el) => obs.observe(el));
	}

	/* ---------- Projects: homepage carousel (3-up sliding) ---------- */
	function initProjects() {
		const carousel = document.querySelector('.proj-carousel');
		if (!carousel) return;

		const track  = carousel.querySelector('.proj-carousel__track');
		const slides = Array.from(carousel.querySelectorAll('.proj-slide'));
		const dots   = Array.from(carousel.querySelectorAll('.proj-carousel__dot'));
		const bar    = carousel.querySelector('.proj-carousel__progress span');
		if (!track || !slides.length) return;

		const total       = slides.length;
		const visible     = parseInt(carousel.dataset.visible || '3', 10);
		let   maxIndex    = Math.max(0, total - visible);   // recomputed on resize
		let   index       = 0;
		let   timer       = null;     // setInterval handle (full cycles)
		let   resumeTo    = null;     // setTimeout handle (resumes a partial cycle)
		let   resumeFrac  = 0;        // 0..1 — how much of the current cycle elapsed at last pause
		const delay       = parseInt(carousel.dataset.autoplay || '4000', 10);

		// Run the progress bar from `fromFrac` (0..1) to 1, taking the remaining time.
		const startProgressFrom = (fromFrac) => {
			if (!bar) return;
			bar.style.transition = 'none';
			bar.style.transform = 'scaleX(' + fromFrac + ')';
			void bar.offsetWidth;
			const remainingMs = delay * (1 - fromFrac);
			bar.style.transition = 'transform ' + remainingMs + 'ms linear';
			bar.style.transform = 'scaleX(1)';
		};

		// Capture the current visual position of the bar so we can resume.
		const captureProgress = () => {
			if (!bar) { resumeFrac = 0; return; }
			const cs = getComputedStyle(bar).transform;
			let scaleX = 0;
			if (cs && cs !== 'none') {
				// Computed transform is `matrix(a, b, c, d, tx, ty)`; a is scaleX.
				const m = cs.match(/matrix\(([^)]+)\)/);
				if (m) {
					const parts = m[1].split(',').map((s) => parseFloat(s.trim()));
					if (parts.length >= 1 && !isNaN(parts[0])) scaleX = parts[0];
				}
			}
			resumeFrac = Math.max(0, Math.min(1, scaleX));
			bar.style.transition = 'none';
			bar.style.transform = 'scaleX(' + resumeFrac + ')';
		};

		const clearTimers = () => {
			if (timer)    { clearInterval(timer); timer = null; }
			if (resumeTo) { clearTimeout(resumeTo); resumeTo = null; }
		};

		// Cache the per-card step (width + gap) so we only call getBoundingClientRect()
		// on init + resize — not on every autoplay tick or dot click. Avoids forced reflow.
		let _cachedStep = 0;
		const measureCardStep = () => {
			const card = slides[0];
			if (!card) { _cachedStep = 0; return 0; }
			const rect = card.getBoundingClientRect();
			const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || '24') || 24;
			_cachedStep = rect.width + gap;
			return _cachedStep;
		};
		const cardStep = () => _cachedStep || measureCardStep();

		const updateDotVisibility = () => {
			dots.forEach((d, i) => {
				const usable = i <= maxIndex;
				d.style.display = usable ? '' : 'none';
				d.setAttribute('aria-hidden', usable ? 'false' : 'true');
				if (!usable) d.tabIndex = -1; else d.removeAttribute('tabIndex');
			});
		};

		const apply = () => {
			const step = cardStep();
			track.style.transform = 'translate3d(' + (-index * step) + 'px, 0, 0)';
			dots.forEach((d, i) => {
				const active = i === index;
				d.classList.toggle('is-active', active);
				d.setAttribute('aria-selected', active ? 'true' : 'false');
			});
			slides.forEach((s, i) => {
				const inView = i >= index && i < index + visible;
				if (inView) s.removeAttribute('aria-hidden');
				else s.setAttribute('aria-hidden', 'true');
			});
		};

		const show = (n) => {
			const max = maxIndex + 1; // valid range 0..maxIndex inclusive
			index = ((n % max) + max) % max;
			apply();
		};

		const stop = () => {
			clearTimers();
			captureProgress();
		};
		// Advance one slide and immediately restart the bar from 0.
		const advanceCycle = () => {
			show(index + 1);
			resumeFrac = 0;
			startProgressFrom(0);
		};
		const start = () => {
			clearTimers();
			if (delay <= 0 || maxIndex <= 0) return;
			startProgressFrom(resumeFrac);
			const remainingMs = delay * (1 - resumeFrac);
			if (resumeFrac > 0 && resumeFrac < 1 && remainingMs > 0) {
				// Resuming mid-cycle: finish the current cycle first, then continue normally.
				resumeTo = setTimeout(() => {
					resumeTo = null;
					advanceCycle();
					timer = setInterval(advanceCycle, delay);
				}, remainingMs);
			} else {
				// Fresh cycle (resumeFrac is 0 or already complete).
				timer = setInterval(advanceCycle, delay);
			}
		};

		// Click on dot / nav: reset the progress to 0 (this is a fresh cycle).
		const restartFresh = () => {
			resumeFrac = 0;
			start();
		};

		dots.forEach((d) => d.addEventListener('click', () => {
			show(parseInt(d.dataset.index || '0', 10));
			restartFresh();
		}));

		carousel.addEventListener('mouseenter', stop);
		carousel.addEventListener('mouseleave', start);
		carousel.addEventListener('focusin', stop);
		carousel.addEventListener('focusout', start);

		// Pause when the tab itself is hidden (saves CPU/battery in background tabs)
		document.addEventListener('visibilitychange', () => {
			if (document.hidden) stop();
			else start();
		});

		if ('IntersectionObserver' in window) {
			const io = new IntersectionObserver((entries) => {
				entries.forEach((e) => { e.isIntersecting ? start() : stop(); });
			}, { threshold: 0.05 });
			io.observe(carousel);
		} else {
			start();
		}

		// Recompute on resize — visible card count may change with breakpoints.
		let resizeTimer = null;
		window.addEventListener('resize', () => {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(() => {
				const card = slides[0];
				if (!card) return;
				measureCardStep(); // refresh cached step
				const cardRect = card.getBoundingClientRect();
				const trackRect = track.parentElement.getBoundingClientRect();
				const gap = parseFloat(getComputedStyle(track).columnGap || '24') || 24;
				const fits = Math.max(1, Math.floor((trackRect.width + gap) / (cardRect.width + gap)));
				maxIndex = Math.max(0, total - fits);
				if (index > maxIndex) index = maxIndex;
				updateDotVisibility();
				apply();
			}, 120);
		});

		// Initial breakpoint detection so dot count matches the actual visible slides.
		(function initFits() {
			const card = slides[0];
			if (!card) return;
			const cardRect = card.getBoundingClientRect();
			const trackRect = track.parentElement.getBoundingClientRect();
			const gap = parseFloat(getComputedStyle(track).columnGap || '24') || 24;
			const fits = Math.max(1, Math.floor((trackRect.width + gap) / (cardRect.width + gap)));
			maxIndex = Math.max(0, total - fits);
		})();
		measureCardStep(); // prime the cache once before first apply()
		updateDotVisibility();
		apply();
		start();

		// Keyboard arrow nav when focus is inside the carousel.
		carousel.addEventListener('keydown', (e) => {
			if (e.key === 'ArrowLeft')  { show(index - 1); restartFresh(); }
			if (e.key === 'ArrowRight') { show(index + 1); restartFresh(); }
		});

		// Touch swipe — drag left to advance, right to go back.
		let touchStartX = 0;
		let touchStartY = 0;
		let touchActive = false;
		const SWIPE_THRESHOLD = 40;       // px the finger must travel horizontally
		const SWIPE_LOCK_RATIO = 1.4;     // require horizontal travel > vertical × this

		carousel.addEventListener('touchstart', (e) => {
			if (e.touches.length !== 1) return;
			touchStartX = e.touches[0].clientX;
			touchStartY = e.touches[0].clientY;
			touchActive = true;
			stop(); // pause autoplay while interacting
		}, { passive: true });

		carousel.addEventListener('touchend', (e) => {
			if (!touchActive) return;
			touchActive = false;
			const t = e.changedTouches && e.changedTouches[0];
			if (!t) { start(); return; }
			const dx = t.clientX - touchStartX;
			const dy = t.clientY - touchStartY;
			if (Math.abs(dx) >= SWIPE_THRESHOLD && Math.abs(dx) > Math.abs(dy) * SWIPE_LOCK_RATIO) {
				if (dx < 0) { show(index + 1); restartFresh(); }
				else        { show(index - 1); restartFresh(); }
			} else {
				start();
			}
		}, { passive: true });

		carousel.addEventListener('touchcancel', () => {
			if (touchActive) { touchActive = false; start(); }
		}, { passive: true });
	}

	/* ---------- Mobile menu ---------- */
	function initMobileMenu() {
		const header = document.getElementById('site-header');
		const burger = header && header.querySelector('.hamburger');
		const menu = document.querySelector('.mobile-menu'); // sibling of header now
		if (!header || !burger || !menu) return;

		const close = () => {
			header.classList.remove('menu-open');
			menu.classList.remove('open');
			menu.setAttribute('aria-hidden', 'true');
			menu.setAttribute('inert', '');
			burger.setAttribute('aria-expanded', 'false');
			burger.setAttribute('aria-label', 'Open menu');
			document.body.style.overflow = '';
		};
		const open = () => {
			header.classList.add('menu-open');
			menu.classList.add('open');
			menu.setAttribute('aria-hidden', 'false');
			menu.removeAttribute('inert');
			burger.setAttribute('aria-expanded', 'true');
			burger.setAttribute('aria-label', 'Close menu');
			document.body.style.overflow = 'hidden';
		};

		// Focus trap: when the drawer is open, Tab/Shift+Tab cycles within it (a11y standard).
		const trapFocus = (e) => {
			if (!menu.classList.contains('open') || e.key !== 'Tab') return;
			const focusables = menu.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
			if (!focusables.length) return;
			const first = focusables[0];
			const last = focusables[focusables.length - 1];
			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
		};

		burger.addEventListener('click', () => {
			const isOpen = menu.classList.contains('open');
			if (isOpen) {
				close();
				burger.focus();
			} else {
				open();
				const firstLink = menu.querySelector('a, button');
				if (firstLink) firstLink.focus();
			}
		});
		menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', close));
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				if (menu.classList.contains('open')) burger.focus();
				close();
			}
			trapFocus(e);
		});
	}

	/* ---------- Contact form (built-in AJAX) ---------- */
	function initContactForm() {
		const form = document.getElementById('sarjeet-contact-form');
		if (!form || typeof window.SARJEET_BOOT === 'undefined') return;

		const status = form.querySelector('[data-status]');
		const setStatus = (type, msg) => {
			status.className = 'form-status ' + type;
			status.textContent = msg;
		};

		// Live "filled" indicator on label — only target real form fields, skip the honeypot
		form.querySelectorAll('.field input, .field textarea').forEach((el) => {
			const field = el.closest('.field');
			if (!field) return;
			const update = () => field.classList.toggle('field--filled', el.value.length > 0);
			el.addEventListener('input', update);
			update();
		});

		form.addEventListener('submit', (e) => {
			e.preventDefault();
			const fd = new FormData(form);
			const name = (fd.get('name') || '').toString().trim();
			const email = (fd.get('email') || '').toString().trim();
			const message = (fd.get('message') || '').toString().trim();
			if (!name || !email || !message) {
				setStatus('error', 'Please fill name, email and message.');
				return;
			}
			if (!/^\S+@\S+\.\S+$/.test(email)) {
				setStatus('error', 'Enter a valid email address.');
				return;
			}
			setStatus('idle', 'Sending…');

			fd.append('action', 'sarjeet_contact');
			fd.append('nonce', window.SARJEET_BOOT.nonce);

			fetch(window.SARJEET_BOOT.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
				.then((r) => r.json().then((j) => ({ ok: r.ok, body: j })))
				.then(({ ok, body }) => {
					if (ok && body.success) {
						setStatus('success', body.data && body.data.msg ? body.data.msg : 'Brief received.');
						form.reset();
						form.querySelectorAll('.field--filled').forEach((f) => f.classList.remove('field--filled'));
					} else {
						setStatus('error', (body && body.data && body.data.msg) || 'Something went wrong.');
					}
				})
				.catch(() => setStatus('error', 'Network error. Please try again.'));
		});
	}
})();
