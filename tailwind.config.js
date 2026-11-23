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
            colors: {
                cream: {
                    DEFAULT: '#F3EEE3',
                    light: '#FAF7F2',
                    dark: '#E5DDD0',
                },
                'int-black': {
                    DEFAULT: '#111111',
                    soft: '#222222',
                    muted: '#3A3A3A',
                },
                burnt: {
                    DEFAULT: '#D9541E',
                    hover: '#B84214',
                    light: '#FCEEE8',
                    muted: '#F7DCD0',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                '2xl': '16px',
                '3xl': '24px',
            },
        },
    },

    plugins: [forms],
};
