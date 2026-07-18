import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Institutional navy — authority, trust (primary brand).
                shield: {
                    50: '#eef2fb',
                    100: '#d9e1f6',
                    200: '#b6c6ec',
                    300: '#8aa2de',
                    400: '#5c79cc',
                    500: '#3a56b8',
                    600: '#2c4199',
                    700: '#25357c',
                    800: '#1c2860',
                    900: '#131c47',
                    950: '#0b1230',
                },
                // Peace green — reintegration, growth (accent).
                peace: {
                    50: '#ecfdf3',
                    100: '#d1fadf',
                    200: '#a6f4c5',
                    300: '#6ce9a6',
                    400: '#32d583',
                    500: '#12b76a',
                    600: '#039855',
                    700: '#027a48',
                    800: '#05603a',
                    900: '#054f31',
                },
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(16 24 40 / 0.05), 0 1px 3px 0 rgb(16 24 40 / 0.06)',
            },
        },
    },

    plugins: [forms],
};
