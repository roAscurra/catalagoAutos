import './bootstrap';

const adminToggle = document.querySelector('.mobile-nav-toggle');
const adminBody = document.body;

if (adminToggle) {
	adminToggle.addEventListener('click', () => {
		const isOpen = adminBody.classList.toggle('admin-nav-open');
		adminToggle.setAttribute('aria-expanded', String(isOpen));
		adminToggle.setAttribute('aria-label', isOpen ? 'Cerrar menú' : 'Abrir menú');
	});

	document.querySelectorAll('.admin-nav a').forEach((link) => {
		link.addEventListener('click', () => {
			adminBody.classList.remove('admin-nav-open');
			adminToggle.setAttribute('aria-expanded', 'false');
			adminToggle.setAttribute('aria-label', 'Abrir menú');
		});
	});

	document.addEventListener('click', (event) => {
		if (!adminBody.classList.contains('admin-nav-open')) return;
		const clickedOnToggle = event.target.closest('.mobile-nav-toggle');
		const clickedInsideNav = event.target.closest('.admin-nav');
		if (!clickedOnToggle && !clickedInsideNav) {
			adminBody.classList.remove('admin-nav-open');
			adminToggle.setAttribute('aria-expanded', 'false');
			adminToggle.setAttribute('aria-label', 'Abrir menú');
		}
	});

	window.addEventListener('resize', () => {
		if (window.innerWidth > 820) {
			adminBody.classList.remove('admin-nav-open');
			adminToggle.setAttribute('aria-expanded', 'false');
			adminToggle.setAttribute('aria-label', 'Abrir menú');
		}
	});
}

const topbarToggle = document.querySelector('.topbar-toggle');
const topbarNav = document.querySelector('.topbar nav');

if (topbarToggle && topbarNav) {
	const closeTopbarNav = () => {
		topbarNav.classList.remove('is-open');
		topbarToggle.setAttribute('aria-expanded', 'false');
		topbarToggle.setAttribute('aria-label', 'Abrir menú');
	};

	topbarToggle.addEventListener('click', () => {
		const isOpen = topbarNav.classList.toggle('is-open');
		topbarToggle.setAttribute('aria-expanded', String(isOpen));
		topbarToggle.setAttribute('aria-label', isOpen ? 'Cerrar menú' : 'Abrir menú');
	});

	topbarNav.querySelectorAll('a').forEach((link) => {
		link.addEventListener('click', closeTopbarNav);
	});

	document.addEventListener('click', (event) => {
		if (!topbarNav.classList.contains('is-open')) return;
		const clickedOnToggle = event.target.closest('.topbar-toggle');
		const clickedInsideNav = event.target.closest('.topbar nav');
		if (!clickedOnToggle && !clickedInsideNav) {
			closeTopbarNav();
		}
	});

	window.addEventListener('resize', () => {
		if (window.innerWidth > 750) {
			closeTopbarNav();
		}
	});
}

const sellerLanding = document.querySelector('.seller-landing');

if (sellerLanding) {
	sellerLanding.style.setProperty('--seller-primary', sellerLanding.dataset.sellerPrimary);
	sellerLanding.style.setProperty('--seller-secondary', sellerLanding.dataset.sellerSecondary);

	const coverImage = sellerLanding.querySelector('[data-cover-image]');
	if (coverImage) {
		coverImage.style.backgroundImage = `url("${coverImage.dataset.coverImage}")`;
	}
}

const landingEditor = document.querySelector('#landing-editor-form');

if (landingEditor) {
	const preview = document.querySelector('#landing-preview');
	const bindText = (selector, field, fallback) => {
		const input = landingEditor.querySelector(`[data-preview="${field}"]`);
		const target = preview.querySelector(selector);
		if (!input || !target) return;
		const update = () => { target.textContent = input.value.trim() || fallback; };
		input.addEventListener('input', update);
		update();
	};

	bindText('#preview-agency', 'agency', 'Tu agencia');
	bindText('#preview-title', 'title', 'Tu próximo vehículo empieza acá');
	bindText('#preview-subtitle', 'subtitle', 'Vehículos seleccionados para acompañar tu próximo destino.');

	const primary = landingEditor.querySelector('[data-preview="primary"]');
	const secondary = landingEditor.querySelector('[data-preview="secondary"]');
	const template = landingEditor.querySelector('[data-preview="template"]');
	const applyTheme = () => {
		preview.style.setProperty('--preview-primary', primary.value);
		preview.style.setProperty('--preview-secondary', secondary.value);
		preview.dataset.template = template.value;
	};
	[primary, secondary, template].forEach((input) => input.addEventListener('input', applyTheme));
	applyTheme();

	landingEditor.querySelectorAll('[data-preview-section]').forEach((toggle) => {
		const block = preview.querySelector(`[data-preview-block="${toggle.dataset.previewSection}"]`);
		const updateSection = () => {
			if (block) block.hidden = !toggle.checked;
		};
		toggle.addEventListener('change', updateSection);
		updateSection();
	});

	const sectionList = landingEditor.querySelector('[data-sortable-sections]');
	let draggedSection = null;
	sectionList?.querySelectorAll('[data-section-item]').forEach((item) => {
		item.addEventListener('dragstart', () => {
			draggedSection = item;
			item.classList.add('is-dragging');
		});
		item.addEventListener('dragend', () => {
			draggedSection = null;
			item.classList.remove('is-dragging');
			updatePreviewOrder();
		});
		item.addEventListener('dragover', (event) => {
			event.preventDefault();
			if (!draggedSection || draggedSection === item) return;
			const bounds = item.getBoundingClientRect();
			const insertAfter = event.clientY > bounds.top + bounds.height / 2;
			sectionList.insertBefore(draggedSection, insertAfter ? item.nextSibling : item);
		});
	});

	const updatePreviewOrder = () => {
		const preview = document.querySelector('#landing-preview');
		sectionList?.querySelectorAll('[data-section-item]').forEach((item) => {
			const block = preview.querySelector(`[data-preview-block="${item.dataset.sectionItem}"]`);
			if (block) preview.appendChild(block);
		});
	};
	updatePreviewOrder();

	const coverInput = landingEditor.querySelector('[data-preview="cover"]');
	const cover = preview.querySelector('.preview-cover');
	coverInput?.addEventListener('change', () => {
		const file = coverInput.files?.[0];
		if (!file) return;
		const reader = new FileReader();
		reader.addEventListener('load', () => { cover.style.backgroundImage = `url("${reader.result}")`; });
		reader.readAsDataURL(file);
	});
}

document.querySelectorAll('[data-gallery]').forEach((gallery) => {

	const main = gallery.querySelector('[data-gallery-main]');
	const thumbs = [...gallery.querySelectorAll('[data-gallery-thumb]')];
	const counter = gallery.querySelector('[data-gallery-counter]');
	let current = 0;

	const showImage = (index) => {
		if (!thumbs.length) return;
		current = (index + thumbs.length) % thumbs.length;
		main.style.backgroundImage = `url("${thumbs[current].dataset.galleryThumb}")`;
		thumbs.forEach((thumb, thumbIndex) => thumb.classList.toggle('is-active', thumbIndex === current));
		if (counter) counter.textContent = `${current + 1} / ${thumbs.length}`;
	};

	thumbs.forEach((thumb, index) => thumb.addEventListener('click', () => showImage(index)));
	gallery.querySelector('[data-gallery-prev]')?.addEventListener('click', () => showImage(current - 1));
	gallery.querySelector('[data-gallery-next]')?.addEventListener('click', () => showImage(current + 1));
});

document.querySelectorAll('.password-toggle').forEach(button => {
    button.addEventListener('click', () => {
        const input = button.parentElement.querySelector('input');

        const visible = input.type === 'text';

        input.type = visible ? 'password' : 'text';

        button.setAttribute(
            'aria-label',
            visible ? 'Mostrar contraseña' : 'Ocultar contraseña'
        );

        button.innerHTML = visible
            ? `
                <svg class="eye-icon" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z" />
                    <circle cx="12" cy="12" r="2.5" />
                </svg>
            `
            : `
                <svg class="eye-icon" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3l18 18" />
                    <path d="M10.6 5.1A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18.3 18.3 0 0 1-3.2 4.1" />
                    <path d="M6.2 6.2C3.5 8.3 2 12 2 12s3.5 7 10 7c1.5 0 2.8-.3 4-.8" />
                    <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                </svg>
            `;
    });
});

document.querySelectorAll('.stat-counter').forEach(counter => {
    const target = Number(counter.dataset.target);

    if (!target) {
        counter.textContent = '0';
        return;
    }

    const duration = 1200;
    const start = performance.now();

    const animate = (currentTime) => {
        const progress = Math.min(
            (currentTime - start) / duration,
            1
        );

        const easedProgress = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(target * easedProgress);

        counter.textContent = current.toLocaleString('es-AR');

        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            counter.textContent = target.toLocaleString('es-AR');
        }
    };

    requestAnimationFrame(animate);
});