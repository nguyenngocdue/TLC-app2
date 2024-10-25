/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue', 'node_modules/preline/dist/*.js'],
    safelist: [
        //  variants: ['lg', 'hover', 'focus', 'lg:hover'],
        {
            pattern:
                /(border|text|bg)-(slate|zinc|neutral|stone|amber|yellow|lime|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose|green|orange|red|gray)-(50|100|200|300|400|500|600|700|800|900|950)/,
            variants: ['hover', 'dark', 'focus', 'active', 'peer-checked'],
        },
        {
            pattern: /(grid-cols|col-span)-(1|2|3|4|5|6|7|8|9|10|11|12)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl'],
        },
        {
            pattern: /(opacity|bg-opacity)-(0|5|10|20|25|30|40|50|60|70|80|90)/,
            variants: ['disabled'],
        },
        {
            pattern: /top-(0|1|2|3|4|5|6|7|8|9|10|11|12|14|16|20|24|28|32|36|40)/,
        },
        {
            pattern:
                /(w|h|m|p|mx|my|px|py|mt|mb|ml|mr|pt|pb|pl|pr)-(0|px|0.5|1|1.5|2|2.5|3|3.5|4|5|6|7|8|9|10|11|12|14|16|20|24|28|32|36|40|44|48|52|56|60|64|72|80|96)/,
        },
        {
            pattern: /z-(10|20|30|40|50)/,
        },
    ],
    theme: {
        extend: {
            //No Compiled
            // fontSize: {
            //     custom: '999px',
            //     'xs-vw': '0.625vw',
            //     'sm-vw': '0.729vw',
            //     'md-vw': '0.833vw',
            //     'lg-vw': '0.938vw',
            //     'xl-vw': '1.042vw',
            //     '2xl-vw': '1.25vw',
            //     '3xl-vw': '1.563vw',
            //     '4xl-vw': '1.875vw',
            //     '5xl-vw': '2.5vw',
            //     '6xl-vw': '3.125vw',
            //     '7xl-vw': '3.75vw',
            //     '8xl-vw': '5vw',
            //     '9xl-vw': '6.67vw',
            // },

            boxShadow: {
                1: '1px 1px 10px #000', // Compiled to shadow-1
            },

            // Adding custom text shadow utilities
            textShadow: {
                // Define your custom text shadow styles here
                default: '2px 2px 4px rgba(0, 0, 0, 1)', //No Compiled
                // Add more styles as needed
                md: '4px 4px 5px rgba(0, 0, 0, 1)', //Compiled
                lg: '6px 6px 7px rgba(0, 0, 0, 1)', //No Compiled
                xl: '8px 8px 9px rgba(0, 0, 0, 1)', //Compiled
            },

            //Compiled
            screens: {
                // 'sm': '640px',
                // 'md': '768px',
                // 'lg': '1024px',
                // 'xl': '1280px',
                '2xl': '1536px',
                '3xl': '1920px',
                '4xl': '2304px',
                // '5xl': '2688px',
                // '6xl': '3072px',
                // '7xl': '3456px',
                // '8xl': '3840px',
            },

            //Ony Compiled when used
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
                },
            },
        },
    },
    variants: {
        backgroundColor: ['hover', 'focus', 'active', 'odd', 'dark', 'dark:hover', 'dark:focus', 'dark:active', 'dark:odd'],
        display: ['responsive', 'dark'],
        textColor: ['focus-within', 'hover', 'active', 'dark', 'dark:focus-within', 'dark:hover', 'dark:active'],
        placeholderColor: ['focus', 'dark', 'dark:focus'],
        borderColor: ['focus', 'hover', 'dark', 'dark:focus', 'dark:hover'],
        divideColor: ['dark'],
        boxShadow: ['focus', 'dark:focus'],
    },
    plugins: [
        require('preline/plugin'),
        require('tailwind-scrollbar-hide'),
        require('tailwind-scrollbar'),
        require('tailwindcss-textshadow'),
    ],
}
