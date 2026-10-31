document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-theme-toggle]')) return;
    const dark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', dark ? 'dark' : 'light');
});
