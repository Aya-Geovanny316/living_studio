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
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
                display: ['Sora', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'ls-navy': '#2B2B2B',
                'ls-indigo': '#EF1C14',
                'ls-blue': '#EF1C14',
                'ls-light': '#E6E6E6',
                'ls-white': '#ffffff',
                'ls-mid': '#7A7676',
                'ls-ink': '#5F5B5B',
            },
        },
    },

    plugins: [forms],
};
