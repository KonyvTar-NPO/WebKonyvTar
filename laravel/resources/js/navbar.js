export function setupNavbarToggles() {
    function setupToggle(toggleSelector, targetId) {
        const toggleBtn = document.querySelector(toggleSelector);
        const target = document.getElementById(targetId);
        if (!toggleBtn || !target) return;

        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = target.classList.contains('hidden');
            target.classList.toggle('hidden');
            toggleBtn.setAttribute('aria-expanded', isHidden);
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!target.contains(e.target) && !toggleBtn.contains(e.target)) {
                target.classList.add('hidden');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    setupToggle('[data-dropdown-toggle="user-dropdown"]', 'user-dropdown');

    setupToggle('[data-collapse-toggle="navbar-user"]', 'navbar-user');
}
