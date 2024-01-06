@script
  <script>
    Alpine.data('scrollTopButton', () => ({
      isVisible: false,
      watchDisplay() {
        this.isVisible = window.scrollY > 600;
      },
      backToTop() {
        document.querySelector('#docs').scrollIntoView({
          behavior: 'smooth'
        });
      },
      init() {
        this.$watch('isVisible', (value) => {
          if (value) {
            // You can handle additional logic when the button becomes visible
          }
        });

        document.addEventListener('scroll', () => this.watchDisplay(), {
          passive: true
        });
      },
      destroy() {
        document.removeEventListener('scroll', this.watchDisplay);
      }
    }));
  </script>
@endscript

@push('styles')
  <style>
    .fade-enter-active,
    .fade-leave-active {
      transition: opacity 0.5s;
    }
  </style>
@endpush

<div x-data="scrollTopButton()" x-init="init()" x-on:destroy="destroy()">
  <button x-show="isVisible" @click.prevent="backToTop()" type="button"
    class="fixed bottom-0 right-0 flex items-center w-10 h-10 mb-10 mr-10 text-white rounded-full shadow-xl transition:all bg-primary-500 hover:bg-warning-500 focus:bg-warning-600 focus:outline-none"
    aria-label="Scroll to top">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto" fill="none" viewBox="0 0 24 24"
      stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
    </svg>
  </button>
</div>
