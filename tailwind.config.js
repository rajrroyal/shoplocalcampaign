const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require("tailwindcss/plugin");

module.exports = {
    purge: [
        './resources/**/*.php',
        './resources/**/*.js',
        './app/**/*.php'
    ],
    theme: {
        animations: {
            'spin': {
                from: {
                    transform: 'rotate(0deg)',
                },
                to: {
                    transform: 'rotate(360deg)',
                },
            },
            'jump': {
                '0%': {
                    transform: 'translateY(0%)',
                },
                '50%': {
                    transform: 'translateY(-100%)',
                },
                '100%': {
                    transform: 'translateY(0%)',
                },
            },
        },
        aspectRatio: {
            '1/1': [1, 1],
            '4/3': [4, 3],
            '16/9': [16, 9],
            '2/1': [2, 1],
            '21/9': [21, 9],
        },
        truncate: {
            lines: {
                2: '2',
                3: '3',
                5: '5',
                8: '8',
            }
        },
        extend: {
            colors: {
                primary: {
                    '100': 'var(--color-primary-100)',
                    '200': 'var(--color-primary-200)',
                    '300': 'var(--color-primary-300)',
                    '400': 'var(--color-primary-400)',
                    '500': 'var(--color-primary-500)',
                    '600': 'var(--color-primary-600)',
                    '700': 'var(--color-primary-700)',
                    '800': 'var(--color-primary-800)',
                    '900': 'var(--color-primary-900)',
                }
            },
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            minWidth: defaultTheme.width,
            maxWidth: defaultTheme.width,
        },
    },
    variants: {
        aspectRatio: ['responsive'],
        borderColor: ["responsive", "hover", "focus", "focus-visible"],
        boxShadow: ["responsive", "hover", "focus", "focus-visible"],
        zIndex: ["responsive", "focus", "focus-visible"]
    },
    plugins: [
        require('@tailwindcss/ui')({
            layout: 'sidebar',
        }),
        require('tailwindcss-animations'),
        require('tailwindcss-truncate-multiline')(['responsive']),
        require('tailwindcss-aspect-ratio'),
        plugin(function ({ addVariant, e }) {
            addVariant("focus-visible", ({ modifySelectors, separator }) => {
                modifySelectors(({ className }) => {
                    return `.${e(
                        `focus-visible${separator}${className}`
                    )}[data-focus-visible-added]`;
                });
            });
        })
    ],
}
