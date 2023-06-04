import colours from "tailwindcss/colors";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

module.exports = {
    content: [
        "./storage/framework/views/*.php",
        "./resources/**/*.html",
        "./resources/**/*.vue",
        "./resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                green: colours.emerald,
                gray: colours.stone,
                opaque: "rgba(255,255,255,.8)",
                "tb-green": "#10986E",
                "tb-red": "#DA080B",
                "tb-red-light": "#FA4143",
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
                "16/9": "56.25%",
            },
            inset: {
                16: "4rem",
                50: "50%",
                100: "100%",
            },
            minHeight: {
                80: "20rem",
            },
            backgroundImage: {
                footer: "url(/images/footer_pattern.png)",
                "mobile-banner": "url(/images/home/banner_noodles.jpg)",
                squiggly: "url(/images/squiggly.jpg)",
                colours: "url(/images/colour_and_shape.jpg)",
            },
            animation: {
                wiggle: "wiggle 2s linear infinite",
            },
            keyframes: {
                wiggle: {
                    "0%": { transform: "rotate(0deg)" },
                    "10%": { transform: "rotate(-30deg)" },
                    "20%": { transform: "rotate(0deg)" },
                    "30%": { transform: "rotate(30deg)" },
                    "40%": { transform: "rotate(0deg)" },
                },
            },
        },
    },
    variants: {},
    plugins: [forms, typography],
};
