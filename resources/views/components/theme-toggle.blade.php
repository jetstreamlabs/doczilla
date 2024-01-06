<button type="button" x-cloak x-data="themeToggle" @click="toggleDarkMode()"
  class="ml-3 text-sm text-gray-600 transition-opacity duration-300 ease-in-out rounded-lg hover:text-primary-500 focus:outline-none focus:ring-0 dark:text-gray-400 dark:hover:text-primary-500"
  aria-label="Change Theme">
  <svg x-show="isDark" class="w-auto h-6 stroke-primary-500 fill-transparent hover:fill-primary-500"
    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" width="22"
    height="22" stroke-width="1.2">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
    <circle cx="12" cy="12" r="4" class="fill:primary/.2"></circle>
    <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path>
  </svg>
  <svg x-show="!isDark" class="w-auto h-5 stroke-primary-500 fill-transparent hover:fill-primary-500"
    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" width="22"
    height="22" stroke-width="1.2">
    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
    <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" class="fill:primary/.2">
    </path>
    <path d="M17 4a2 2 0 0 0 2 2a2 2 0 0 0 -2 2a2 2 0 0 0 -2 -2a2 2 0 0 0 2 -2"></path>
    <path d="M19 11h2m-1 -1v2"></path>
  </svg>
</button>

@script
  <script>
    window.themeToggle = () => ({
      isDark: document.documentElement.classList.contains('dark'),
      init() {
        this.listenForPrefChanges();
      },
      toggleDarkMode() {
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        let newTheme;

        this.isDark = !this.isDark;
        document.documentElement.classList.toggle('dark', this.isDark);

        if (systemPrefersDark) {
          newTheme = this.isDark ? 'system' : 'light';
        } else {
          newTheme = this.isDark ? 'dark' : 'system';
        }

        localStorage.setItem('theme', newTheme);
      },
      listenForPrefChanges() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
          const currentTheme = localStorage.getItem('theme');
          let newTheme;

          this.isDark = e.matches;
          document.documentElement.classList.toggle('dark', this.isDark);

          switch (currentTheme) {
            case 'system':
              newTheme = this.isDark ? 'system' : 'light';
              break;
            case 'dark':
              newTheme = this.isDark ? 'system' : 'light';
              break;
            case 'light':
              newTheme = this.isDark ? 'dark' : 'system';
              break;
          }

          localStorage.setItem('theme', newTheme);
        });
      },
    });
  </script>
@endscript
