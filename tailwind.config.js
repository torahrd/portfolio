import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "src/resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Noto Sans JP",
                    "Hiragino Kaku Gothic ProN",
                    "Hiragino Sans",
                    "Meiryo",
                    ...defaultTheme.fontFamily.sans,
                ],
            },
            colors: {
                // メインカラーパレット
                primary: {
                    50: "#FFF5F5",
                    100: "#FFE5E5",
                    500: "#D64045", // メインアクセント色
                    600: "#C13136",
                    700: "#A42328",
                },
                neutral: {
                    50: "#FAFAFA", // 背景色
                    100: "#F5F5F5",
                    200: "#E5E5E5",
                    300: "#D4D4D4",
                    400: "#A3A3A3",
                    500: "#737373",
                    600: "#525252",
                    700: "#404040",
                    800: "#262626",
                    900: "#171717",
                },
                cream: {
                    100: "#FFF8ED",
                    200: "#F7E8D0", // サブカラー
                    300: "#F0DDB5",
                    400: "#E8D29F",
                    500: "#DFC688",
                },
                success: {
                    50: "#F0FDF4",
                    100: "#DCFCE7",
                    200: "#BBF7D0",
                    500: "#22C55E",
                    600: "#16A34A",
                    700: "#15803D",
                },
                warning: {
                    50: "#FFFBEB",
                    100: "#FEF3C7",
                    200: "#FDE68A",
                    500: "#F59E0B",
                    600: "#D97706",
                    700: "#B45309",
                },
                error: {
                    50: "#FEF2F2",
                    100: "#FEE2E2",
                    200: "#FECACA",
                    500: "#EF4444",
                    600: "#DC2626",
                    700: "#B91C1C",
                },
            },
            fontSize: {
                // モバイル/PC対応のフォントサイズ
                xs: ["0.75rem", { lineHeight: "1rem" }],
                sm: ["0.875rem", { lineHeight: "1.25rem" }],
                base: ["1rem", { lineHeight: "1.5rem" }],
                lg: ["1.125rem", { lineHeight: "1.75rem" }],
                xl: ["1.25rem", { lineHeight: "1.75rem" }],
                "2xl": ["1.5rem", { lineHeight: "2rem" }],
                "3xl": ["1.875rem", { lineHeight: "2.25rem" }],
                "4xl": ["2.25rem", { lineHeight: "2.5rem" }],
            },
            container: {
                center: true,
                padding: {
                    DEFAULT: "1rem",
                    sm: "2rem",
                    lg: "4rem",
                    xl: "5rem",
                    "2xl": "6rem",
                },
                screens: {
                    sm: "640px",
                    md: "768px",
                    lg: "1024px",
                    xl: "1200px", // 最大幅を1200pxに設定
                },
            },
            boxShadow: {
                card: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                "card-hover":
                    "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            },
        },
    },

    plugins: [forms],
};
