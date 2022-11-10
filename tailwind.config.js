/** @type {import('tailwindcss').Config} */
// const plugin = require("tailwindcss");
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
            sans: ['"Open Sans"', "sans-serif"],
            serif: ['merriweather', "serif"],
        },
        listStyleType: {
            none: 'none',
            disc: 'disc',
            decimal: 'decimal',
        },
        extend: {
            textShadow: {
                sm: '0 1px 2px var(--tw-shadow-color)',
                DEFAULT: '0 2px 4px var(--tw-shadow-color)',
                lg: '10px 7px 4px var(--tw-shadow-color)',
            },
        },
    },
    plugins: [
        require('tw-elements/dist/plugin'),
        function ({ matchUtilities, theme }) {
            matchUtilities(
                {
                    'text-shadow': (value) => ({
                        textShadow: value,
                    }),
                },
                { values: theme('textShadow') }
            )
        },
    ],
}
