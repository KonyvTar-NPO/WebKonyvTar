window.openDialog = (id) => {
    document.querySelectorAll('dialog[open]').forEach(d => d.close());
    document.getElementById(id)?.showModal();
};

document.addEventListener('DOMContentLoaded', () => {
    const dialog = document.getElementById('login-dialog');
    if (!dialog) return;
    const tabs = dialog.querySelectorAll('[data-tab]');
    const panels = dialog.querySelectorAll('[data-panel]');
    tabs.forEach(tab => tab.addEventListener('click', () => {
        tabs.forEach(t => t.setAttribute('aria-selected', t === tab));
        panels.forEach(p => p.hidden = p.dataset.panel !== tab.dataset.tab);
    }));
});
