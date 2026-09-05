const toggle = document.querySelector('.nav-toggle');
const nav = document.querySelector('#nav');
if (toggle && nav) toggle.addEventListener('click', () => { const open = toggle.getAttribute('aria-expanded') === 'true'; toggle.setAttribute('aria-expanded', String(!open)); nav.classList.toggle('is-open', !open); });
const cursorDot = document.querySelector('.custom-cursor-dot');
const cursorRing = document.querySelector('.custom-cursor-ring');
const precisePointer = window.matchMedia('(pointer: fine)').matches;
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (cursorDot && cursorRing && precisePointer && !reducedMotion) {
	document.body.classList.add('custom-cursor-active');
	let pointerX = -100; let pointerY = -100; let ringX = pointerX; let ringY = pointerY;
	const renderCursor = () => { ringX += (pointerX - ringX) * 0.2; ringY += (pointerY - ringY) * 0.2; cursorDot.style.transform = `translate(${pointerX}px, ${pointerY}px) translate(-50%, -50%)`; cursorRing.style.transform = `translate(${ringX}px, ${ringY}px) translate(-50%, -50%)`; requestAnimationFrame(renderCursor); };
	window.addEventListener('pointermove', (event) => { pointerX = event.clientX; pointerY = event.clientY; }, { passive: true });
	document.querySelectorAll('a, button, input, textarea, select, [data-lightbox]').forEach((element) => { element.addEventListener('pointerenter', () => cursorRing.classList.add('is-hovering')); element.addEventListener('pointerleave', () => cursorRing.classList.remove('is-hovering')); });
	renderCursor();
}
const lightbox = document.querySelector('#lightbox');
document.querySelectorAll('[data-lightbox]').forEach((item) => item.addEventListener('click', () => { if (!lightbox) return; lightbox.querySelector('img').src = item.dataset.src; lightbox.querySelector('img').alt = item.dataset.alt || ''; lightbox.showModal(); }));
lightbox?.addEventListener('click', (event) => { if (event.target === lightbox || event.target.closest('[data-close]')) lightbox.close(); });
document.querySelectorAll('[data-confirm]').forEach((form) => form.addEventListener('submit', (event) => { if (!window.confirm(form.dataset.confirm)) event.preventDefault(); }));
const adminForm = document.querySelector('#new-record');
const adminEntity = adminForm?.querySelector('input[name="entity"]')?.value;
if (adminForm && ['officers', 'merchandise'].includes(adminEntity) && !adminForm.querySelector('input[name="image"]')) {
	const imageInput = document.createElement('input');
	imageInput.type = 'file'; imageInput.name = 'image'; imageInput.accept = 'image/jpeg,image/png,image/webp';
	adminForm.insertBefore(imageInput, adminForm.querySelector('button[type="submit"]'));
}
