<div>
    <button
        id="dark-mode-toggle"
        class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors duration-200"
        title="테마 변경"
    >
        <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
        </svg>
        <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('dark-mode-toggle');
    const htmlElement = document.documentElement;

    // 초기 다크 모드 상태 설정
    function initDarkMode() {
        const savedMode = localStorage.getItem('dark-mode');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedMode === 'true' || (savedMode === null && prefersDark)) {
            htmlElement.classList.add('dark');
        } else {
            htmlElement.classList.remove('dark');
        }
    }

    // 다크 모드 토글
    function toggleDarkMode() {
        const isDark = htmlElement.classList.contains('dark');

        if (isDark) {
            htmlElement.classList.remove('dark');
            localStorage.setItem('dark-mode', 'false');
        } else {
            htmlElement.classList.add('dark');
            localStorage.setItem('dark-mode', 'true');
        }
    }

    // 초기화
    initDarkMode();

    // 토글 버튼 이벤트 리스너
    toggleButton.addEventListener('click', toggleDarkMode);
});
</script>