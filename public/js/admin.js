document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('[data-admin-sidebar]');
  const backdrop = document.querySelector('[data-admin-backdrop]');
  const toggleBtn = document.querySelector('[data-admin-menu-toggle]');

  const closeSidebar = () => {
    sidebar?.classList.remove('is-open');
    backdrop?.classList.remove('is-open');
  };

  toggleBtn?.addEventListener('click', () => {
    sidebar?.classList.toggle('is-open');
    backdrop?.classList.toggle('is-open');
  });

  backdrop?.addEventListener('click', closeSidebar);

  document.querySelectorAll('[data-open-dialog]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-open-dialog');
      const dialog = document.getElementById(id);
      if (dialog && typeof dialog.showModal === 'function') {
        dialog.showModal();
      }
    });
  });

  document.querySelectorAll('[data-close-dialog]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const dialog = btn.closest('dialog');
      dialog?.close();
    });
  });
});
