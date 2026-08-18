/**
 * Добрая стоматология — v3 (светлая клиника).
 */
(function () {
	'use strict';
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	document.addEventListener('DOMContentLoaded', function () {

		/* Мобильное меню */
		var burger = document.querySelector('[data-nav-toggle]');
		var nav = document.querySelector('.main-nav');
		if (burger && nav) {
			burger.addEventListener('click', function () {
				var open = nav.classList.toggle('is-open');
				burger.setAttribute('aria-expanded', open ? 'true' : 'false');
				document.body.style.overflow = open ? 'hidden' : '';
			});
			nav.addEventListener('click', function (e) {
				if (e.target.closest('a')) {
					nav.classList.remove('is-open');
					burger.setAttribute('aria-expanded', 'false');
					document.body.style.overflow = '';
				}
			});
		}

		/* Тень шапки при прокрутке */
		var head = document.querySelector('[data-header]');
		if (head) {
			var onScroll = function () { head.classList.toggle('is-stuck', window.scrollY > 6); };
			onScroll();
			window.addEventListener('scroll', onScroll, { passive: true });
		}

		/* Плавный скролл к якорям */
		document.querySelectorAll('a[href^="#"], a[href*="/#"]').forEach(function (link) {
			link.addEventListener('click', function (e) {
				var href = link.getAttribute('href');
				var hash = href.indexOf('#') > -1 ? href.slice(href.indexOf('#')) : '';
				if (hash.length < 2) return;
				var target = document.querySelector(hash);
				if (!target) return;
				var path = link.pathname || '';
				if (path && path !== window.location.pathname && href.indexOf('#') !== 0) return;
				e.preventDefault();
				var top = target.getBoundingClientRect().top + window.scrollY - 96;
				window.scrollTo({ top: top, behavior: reduce ? 'auto' : 'smooth' });
			});
		});

		/* Модальное окно записи */
		var modal = document.querySelector('[data-booking-modal]');
		if (modal) {
			var lastFocus = null;
			var subEl = modal.querySelector('[data-modal-sub]');
			var subDefault = subEl ? subEl.innerHTML : '';
			var clinicField = modal.querySelector('select[name="clinic"], input[name="clinic"]');

			var openModal = function (clinic) {
				lastFocus = document.activeElement;
				modal.classList.add('is-open');
				modal.setAttribute('aria-hidden', 'false');
				document.body.style.overflow = 'hidden';
				if (clinic && clinicField) {
					if (clinicField.tagName === 'SELECT') {
						Array.prototype.forEach.call(clinicField.options, function (o) {
							if (o.value === clinic || o.text === clinic) { clinicField.value = o.value; }
						});
					} else {
						clinicField.value = clinic;
					}
				}
				if (subEl) {
					subEl.innerHTML = clinic
						? 'Запись в клинику: <b>' + clinic.replace(/[<>]/g, '') + '</b>'
						: subDefault;
				}
				var focusable = modal.querySelector('input, select, textarea, button');
				if (focusable) { setTimeout(function () { focusable.focus(); }, 60); }
			};
			var closeModal = function () {
				modal.classList.remove('is-open');
				modal.setAttribute('aria-hidden', 'true');
				document.body.style.overflow = '';
				if (lastFocus) { lastFocus.focus(); }
			};

			document.addEventListener('click', function (e) {
				var trigger = e.target.closest('[data-booking], a[href$="#zapis"], a[href*="/#zapis"]');
				if (trigger) {
					e.preventDefault();
					openModal(trigger.getAttribute('data-clinic') || '');
					return;
				}
				if (e.target.closest('[data-modal-close]')) { closeModal(); }
			});
			document.addEventListener('keydown', function (e) {
				if (e.key === 'Escape' && modal.classList.contains('is-open')) { closeModal(); }
			});
			// Закрыть после успешной отправки CF7.
			document.addEventListener('wpcf7mailsent', function () { setTimeout(closeModal, 1400); });
		}

		/* Появление блоков */
		var revealEls = document.querySelectorAll('[data-anim], [data-stagger]');
		if (revealEls.length) {
			if (reduce || !('IntersectionObserver' in window)) {
				revealEls.forEach(function (el) { el.classList.add('in'); });
			} else {
				var io = new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (!entry.isIntersecting) return;
						var el = entry.target;
						if (el.hasAttribute('data-stagger')) {
							Array.prototype.forEach.call(el.children, function (c, i) { c.style.transitionDelay = (i * 70) + 'ms'; });
						}
						el.classList.add('in');
						io.unobserve(el);
					});
				}, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });
				revealEls.forEach(function (el) { io.observe(el); });
			}
		}

		/* Счётчики */
		function animateCount(el) {
			var raw = el.textContent.trim();
			var m = raw.match(/^(\D*)(\d{1,3}(?:\s\d{3})+|\d+)([\s\S]*)$/);
			if (!m) return;
			var prefix = m[1], numStr = m[2].replace(/\s/g, ''), suffix = m[3];
			var target = parseInt(numStr, 10);
			if (isNaN(target)) return;
			if (numStr.length === 4 && target >= 1900 && target <= 2100) return;
			var thousands = target >= 1000;
			var fmt = function (n) { return thousands ? n.toLocaleString('ru-RU') : String(n); };
			var dur = 1200, t0 = performance.now();
			function tick(now) {
				var p = Math.min((now - t0) / dur, 1);
				var e = 1 - Math.pow(1 - p, 3);
				el.textContent = prefix + fmt(Math.round(target * e)) + suffix;
				if (p < 1) { requestAnimationFrame(tick); } else { el.textContent = prefix + fmt(target) + suffix; }
			}
			requestAnimationFrame(tick);
		}
		var counters = document.querySelectorAll('[data-count]');
		if (counters.length && !reduce && 'IntersectionObserver' in window) {
			var cio = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) { animateCount(entry.target); cio.unobserve(entry.target); }
				});
			}, { threshold: 0.6 });
			counters.forEach(function (el) { cio.observe(el); });
		}
	});
})();
