/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/tw-elements/dist/js/**/*.js",
  ],
  theme: {
    colors: {
      black: '#070600',
      red: '#f76c6c',
      white: '#eee',
    },
    fontFamily: {
      sans: ["Open Sans", "sans-serif"],
      serif: ['merriweather', "serif"],
    },
    extend: {
    },
  },
  plugins: [
    require('tw-elements/dist/plugin')
  ],
}
