/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./**/*.php', './assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        ink: '#0b0b0b', coal: '#111111', graphite: '#181818', steel: '#2a2a2a',
        bone: '#e8e4dc', muted: '#a3a3a3', crimson: '#7a1f1f', 'crimson-dark': '#4b1212'
      },
      fontFamily: { display: ['Barlow Condensed', 'sans-serif'], sans: ['DM Sans', 'sans-serif'] },
      maxWidth: { page: '1180px' }
    }
  },
  plugins: []
};
