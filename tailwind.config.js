import filamentPreset from './vendor/filament/support/tailwind.config.preset.js';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [filamentPreset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/views/filament/**/*.blade.php",
        "./app/Filament/**/*.php",
        "./vendor/filament/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#ecf2ff',
                    100: '#ecf2ff',
                    200: '#becfff',
                    300: '#90afff',
                    400: '#638fff',
                    500: '#5D87FF', // Modernize Primary
                    600: '#4a6ccc',
                    700: '#385199',
                    800: '#253666',
                    900: '#131b33',
                    950: '#090d1a',
                },
                gray: {
                    50: '#f6f9fc',
                    100: '#f6f9fc',
                    200: '#eaeff4',
                    300: '#dfe5ef',
                    400: '#7c8fac',
                    500: '#5a6a85',
                    600: '#2a3547',
                    700: '#2a3547',
                    800: '#212529',
                    900: '#000000',
                }
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
            },
            boxShadow: {
                'modernize': '0px 15px 30px rgba(0, 0, 0, 0.12)',
            }
        },
    },
    plugins: [],
}
