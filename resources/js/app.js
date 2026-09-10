import './bootstrap';

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
