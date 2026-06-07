/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                    950: '#172554',
                },
                eec: {
                    teal: '#005a6a',
                    'teal-dark': '#004552',
                    'teal-light': '#007a8f',
                    cyan: '#22b2da',
                    'cyan-light': '#4dc4e3',
                    'cyan-dark': '#1a8fad',
                },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
