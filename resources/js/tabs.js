export function initializeTabClickHandlers() {
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach((button) => {
    button.addEventListener('click', function () {
      const tabIndex = this.getAttribute('data-tab');

      // Update active state of buttons
      tabButtons.forEach((btn) => {
        if (btn.getAttribute('data-tab') === tabIndex) {
          btn.classList.add('active');
          btn.setAttribute('aria-selected', true);
        } else {
          btn.classList.remove('active');
          btn.removeAttribute('aria-selected');
        }
      });

      // Show the corresponding tab content
      tabContents.forEach((content) => {
        const isContentActive = content.getAttribute('data-index') === tabIndex;
        content.classList.toggle('active', isContentActive);
        if (isContentActive) {
          content.removeAttribute('aria-hidden');
        } else {
          content.setAttribute('aria-hidden', true);
        }
      });
    });
  });
}
