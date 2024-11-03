/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  // darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        'nc-main-bg': 'var(--color-main-background)',
        'nc-main-text': 'var(--color-main-text)',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}