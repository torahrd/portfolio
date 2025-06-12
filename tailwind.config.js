import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js", // JavaScript監視を追加
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // カスタムカラーパレット（既存のデザインに合わせて調整）
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6", // メインのプライマリカラー
                    600: "#2563eb",
                    700: "#1d4ed8",
                    800: "#1e40af",
                    900: "#1e3a8a",
                },
                secondary: {
                    50: "#f8fafc",
                    100: "#f1f5f9",
                    200: "#e2e8f0",
                    300: "#cbd5e1",
                    400: "#94a3b8",
                    500: "#64748b",
                    600: "#475569",
                    700: "#334155",
                    800: "#1e293b",
                    900: "#0f172a",
                },
                success: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    200: "#bbf7d0",
                    300: "#86efac",
                    400: "#4ade80",
                    500: "#22c55e",
                    600: "#16a34a",
                    700: "#15803d",
                    800: "#166534",
                    900: "#14532d",
                },
                warning: {
                    50: "#fffbeb",
                    100: "#fef3c7",
                    200: "#fde68a",
                    300: "#fcd34d",
                    400: "#fbbf24",
                    500: "#f59e0b",
                    600: "#d97706",
                    700: "#b45309",
                    800: "#92400e",
                    900: "#78350f",
                },
                danger: {
                    50: "#fef2f2",
                    100: "#fee2e2",
                    200: "#fecaca",
                    300: "#fca5a5",
                    400: "#f87171",
                    500: "#ef4444",
                    600: "#dc2626",
                    700: "#b91c1c",
                    800: "#991b1b",
                    900: "#7f1d1d",
                },
            },
            spacing: {
                18: "4.5rem",
                88: "22rem",
                128: "32rem",
            },
            animation: {
                "fade-in": "fadeIn 0.5s ease-in-out",
                "slide-up": "slideUp 0.3s ease-out",
                "bounce-gentle": "bounceGentle 0.6s ease-in-out",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                slideUp: {
                    "0%": { transform: "translateY(10px)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
                bounceGentle: {
                    "0%, 20%, 53%, 80%, 100%": {
                        transform: "translate3d(0,0,0)",
                    },
                    "40%, 43%": { transform: "translate3d(0, -15px, 0)" },
                    "70%": { transform: "translate3d(0, -7px, 0)" },
                    "90%": { transform: "translate3d(0, -2px, 0)" },
                },
            },
        },
    },

    plugins: [
        forms,
        function ({ addComponents, theme }) {
            addComponents({
                // ボタンコンポーネント
                ".btn": {
                    "@apply px-4 py-2 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2":
                        {},
                },
                ".btn-primary": {
                    "@apply bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500":
                        {},
                },
                ".btn-secondary": {
                    "@apply bg-secondary-500 text-white hover:bg-secondary-600 focus:ring-secondary-500":
                        {},
                },
                ".btn-success": {
                    "@apply bg-success-500 text-white hover:bg-success-600 focus:ring-success-500":
                        {},
                },
                ".btn-warning": {
                    "@apply bg-warning-500 text-white hover:bg-warning-600 focus:ring-warning-500":
                        {},
                },
                ".btn-danger": {
                    "@apply bg-danger-500 text-white hover:bg-danger-600 focus:ring-danger-500":
                        {},
                },
                ".btn-outline": {
                    "@apply border-2 bg-transparent": {},
                },
                ".btn-outline-primary": {
                    "@apply border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white focus:ring-primary-500":
                        {},
                },
                ".btn-outline-secondary": {
                    "@apply border-secondary-500 text-secondary-500 hover:bg-secondary-500 hover:text-white focus:ring-secondary-500":
                        {},
                },

                // カードコンポーネント
                ".card": {
                    "@apply bg-white rounded-lg shadow-md p-6": {},
                },
                ".card-header": {
                    "@apply border-b border-gray-200 pb-4 mb-4": {},
                },
                ".card-body": {
                    "@apply space-y-4": {},
                },
                ".card-footer": {
                    "@apply border-t border-gray-200 pt-4 mt-4": {},
                },

                // アバターコンポーネント
                ".avatar": {
                    "@apply rounded-full object-cover border-2 border-gray-200":
                        {},
                },
                ".avatar-sm": {
                    "@apply w-8 h-8": {},
                },
                ".avatar-md": {
                    "@apply w-12 h-12": {},
                },
                ".avatar-lg": {
                    "@apply w-16 h-16": {},
                },
                ".avatar-xl": {
                    "@apply w-24 h-24": {},
                },

                // バッジコンポーネント
                ".badge": {
                    "@apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium":
                        {},
                },
                ".badge-primary": {
                    "@apply bg-primary-100 text-primary-800": {},
                },
                ".badge-success": {
                    "@apply bg-success-100 text-success-800": {},
                },
                ".badge-warning": {
                    "@apply bg-warning-100 text-warning-800": {},
                },
                ".badge-danger": {
                    "@apply bg-danger-100 text-danger-800": {},
                },

                // フォーム要素
                ".form-input": {
                    "@apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500":
                        {},
                },
                ".form-textarea": {
                    "@apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500":
                        {},
                },
                ".form-select": {
                    "@apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500":
                        {},
                },
                ".form-error": {
                    "@apply text-danger-600 text-sm mt-1": {},
                },

                // アラートコンポーネント
                ".alert": {
                    "@apply p-4 rounded-lg border": {},
                },
                ".alert-success": {
                    "@apply bg-success-50 border-success-200 text-success-800":
                        {},
                },
                ".alert-warning": {
                    "@apply bg-warning-50 border-warning-200 text-warning-800":
                        {},
                },
                ".alert-danger": {
                    "@apply bg-danger-50 border-danger-200 text-danger-800": {},
                },
                ".alert-info": {
                    "@apply bg-blue-50 border-blue-200 text-blue-800": {},
                },
            });
        },
    ],
};
