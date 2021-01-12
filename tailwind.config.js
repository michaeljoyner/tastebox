module.exports = {
    purge: [
        "./resources/**/*.html",
        "./resources/**/*.vue",
        "./resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                opaque: "rgba(255,255,255,.8)",
            },
            fontFamily: {
                sans: [
                    "Montserrat",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    '"Segoe UI"',
                    "Roboto",
                    '"Helvetica Neue"',
                    "Arial",
                    '"Noto Sans"',
                    "sans-serif",
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ],
                serif: [
                    "Domine",
                    "ui-serif",
                    "Georgia",
                    "Cambria",
                    "'Times New Roman'",
                    "Times",
                    "serif",
                ],
            },
            fontSize: {
                xxs: "0.625rem",
            },
            spacing: {
                42: "10.7rem",
                80: "20rem",
                96: "24rem",
                100: "25rem",
                150: "30rem",
            },
            inset: {
                16: "4rem",
                50: "50%",
                100: "100%",
            },
            minHeight: {
                "80": "20rem",
            },
        },
    },
    variants: {},
    plugins: [],
};
