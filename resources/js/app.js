import { Livewire, Alpine } from 'livewire';
import { initializeTabClickHandlers } from './tabs.js';
import axios from 'axios';
import '../css/app.css';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Livewire.hook('element.init', ({ component, el }) => {
  if (el.matches('.code-group')) {
    initializeTabClickHandlers();
  }

  Alpine.magic('clipboard', () => {
    return function (content) {
      const element = this.$el;
      navigator.clipboard.writeText(content).then(() => {
        element.classList.add('copied');
        setTimeout(() => {
          element.classList.remove('copied');
        }, 3000);
      });
    };
  });

  const spans = document.querySelectorAll('.language-md .shiki .line span');

  spans.forEach((span) => {
    span.removeAttribute('style');
    span.setAttribute('class', 'text-gray-700 dark:text-gray-400');
  });
});

Livewire.start();
