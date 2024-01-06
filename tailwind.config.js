import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.{php,md}',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        gray: colors.stone,
        primary: colors.lime,
        secondary: colors.violet,
        accent: colors.orange,
        success: colors.emerald,
        info: colors.cyan,
        warning: colors.amber,
        error: colors.rose,
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.stone.500'),
            h1: {
              'margin-top': 0,
            },
            h2: {
              'margin-top': 0,
            },
            h3: {
              'margin-top': 0,
            },
            h4: {
              'margin-top': 0,
            },
          },
        },
      }),
    },
  },

  plugins: [forms, typography],
};
