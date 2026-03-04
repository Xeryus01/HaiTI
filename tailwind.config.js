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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // TailAdmin Brand Colors
                brand: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c3d66',
                    950: '#051e3e',
                },
                gray: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
                dark: {
                    900: '#0f172a',
                    800: '#1e293b',
                    700: '#334155',
                },
            },
            spacing: {
                '4.5': '1.125rem',
                '5.5': '1.375rem',
                '6.5': '1.625rem',
                '7.5': '1.875rem',
                '8.5': '2.125rem',
                '11': '2.75rem',
                '13': '3.25rem',
                '15': '3.75rem',
                '16': '4rem',
                '17': '4.25rem',
                '18': '4.5rem',
                '19': '4.75rem',
                '20': '5rem',
            },
            fontSize: {
                'theme-xs': ['12px', { lineHeight: '18px' }],
                'theme-sm': ['14px', { lineHeight: '20px' }],
                'theme-base': ['16px', { lineHeight: '24px' }],
                'theme-lg': ['18px', { lineHeight: '28px' }],
                'theme-xl': ['20px', { lineHeight: '30px' }],
            },
            boxShadow: {
                'theme-xs': '0px 1px 2px rgba(16, 24, 48, 0.06), 0px 1px 0px rgba(16, 24, 48, 0.08)',
                'theme-sm': '0px 1px 2px rgba(16, 24, 48, 0.06), 0px 4px 6px rgba(16, 24, 48, 0.1)',
                'theme-md': '0px 4px 6px rgba(16, 24, 48, 0.1), 0px 10px 15px rgba(16, 24, 48, 0.1)',
                'theme-lg': '0px 10px 15px rgba(16, 24, 48, 0.1), 0px 20px 25px rgba(16, 24, 48, 0.1)',
            },
            borderRadius: {
                'theme-xs': '6px',
                'theme-sm': '8px',
                'theme-md': '10px',
            },
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
            },
        },
    },

    plugins: [forms],

    safelist: ['dark'],
    darkMode: 'class',
};
