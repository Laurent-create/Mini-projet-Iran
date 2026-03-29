// backOffice2 - JS (minimal)
// backOffice2 - JS

(() => {
	const modal = document.getElementById('image-modal');
	const img = document.getElementById('image-modal-img');
	const closeBtn = document.getElementById('image-modal-close');
	const dialog = modal?.querySelector('.modal-dialog');

	if (!modal || !img || !closeBtn || !dialog) return;

	const open = (src, alt) => {
		img.src = src;
		img.alt = alt || 'Aperçu image';
		modal.hidden = false;
		modal.setAttribute('aria-hidden', 'false');
		document.body.style.overflow = 'hidden';
		closeBtn.focus();
	};

	const close = () => {
		modal.hidden = true;
		modal.setAttribute('aria-hidden', 'true');
		img.src = '';
		img.alt = '';
		document.body.style.overflow = '';
	};

	document.addEventListener('click', (event) => {
		const trigger = event.target.closest('[data-image-popup]');
		if (trigger) {
			event.preventDefault();
			const src = trigger.getAttribute('data-image-src');
			if (!src) return;
			const alt = trigger.getAttribute('data-image-alt') || trigger.textContent?.trim();
			open(src, alt);
			return;
		}

		if (!modal.hidden && event.target === modal) {
			close();
		}
	});

	dialog.addEventListener('click', (event) => {
		event.stopPropagation();
	});

	closeBtn.addEventListener('click', () => close());

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && !modal.hidden) {
			close();
		}
	});
})();
