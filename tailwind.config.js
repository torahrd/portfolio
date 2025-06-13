import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],

    theme: {
        extend: {
            // 2025年トレンドカラーパレット
            colors: {
                // メインカラー: Mocha Mousse (Pantone 2025)
                mocha: {
                    50: "#FAF8F6",
                    100: "#F5F1ED",
                    200: "#EBE1D7",
                    300: "#E0D1C1",
                    400: "#D5C1AB",
                    500: "#A47864", // メインカラー
                    600: "#936B59",
                    700: "#7A5847",
                    800: "#614536",
                    900: "#5D3E2B",
                    950: "#3D2A1C",
                },

                // アクセントカラー: セージグリーン
                sage: {
                    50: "#F4F7F0",
                    100: "#E9F0E1",
                    200: "#D3E1C3",
                    300: "#BDD2A5",
                    400: "#A7C387",
                    500: "#87A96B",
                    600: "#6D8A55",
                    700: "#5A7346",
                    800: "#475C37",
                    900: "#344528",
                    950: "#212E1A",
                },

                // アクセントカラー: エレクトリックブルー
                electric: {
                    50: "#EBF8FF",
                    100: "#D6F1FF",
                    200: "#ADE3FF",
                    300: "#85D5FF",
                    400: "#5CC7FF",
                    500: "#1E90FF",
                    600: "#0A73CC",
                    700: "#085A99",
                    800: "#064166",
                    900: "#042833",
                    950: "#021419",
                },

                // アクセントカラー: コーラル
                coral: {
                    50: "#FFF4F2",
                    100: "#FFE8E5",
                    200: "#FFCCC6",
                    300: "#FFB0A7",
                    400: "#FF9488",
                    500: "#FF6B6B",
                    600: "#E55555",
                    700: "#CC4444",
                    800: "#B33333",
                    900: "#992222",
                    950: "#661111",
                },

                // ニュートラルカラー（温かみのあるグレー）
                neutral: {
                    50: "#FDFCFB",
                    100: "#F9F7F4",
                    200: "#F1EDE6",
                    300: "#E8E2D8",
                    400: "#D4C7B8",
                    500: "#B8A694",
                    600: "#9B8570",
                    700: "#7D6A57",
                    800: "#625145",
                    900: "#4A3E35",
                    950: "#312B25",
                },

                // システムカラー
                success: {
                    50: "#F0FDF4",
                    100: "#DCFCE7",
                    200: "#BBF7D0",
                    300: "#86EFAC",
                    400: "#4ADE80",
                    500: "#22C55E",
                    600: "#16A34A",
                    700: "#15803D",
                    800: "#166534",
                    900: "#14532D",
                },
                warning: {
                    50: "#FFFBEB",
                    100: "#FEF3C7",
                    200: "#FDE68A",
                    300: "#FCD34D",
                    400: "#FBBF24",
                    500: "#F59E0B",
                    600: "#D97706",
                    700: "#B45309",
                    800: "#92400E",
                    900: "#78350F",
                },
                error: {
                    50: "#FEF2F2",
                    100: "#FEE2E2",
                    200: "#FECACA",
                    300: "#FCA5A5",
                    400: "#F87171",
                    500: "#EF4444",
                    600: "#DC2626",
                    700: "#B91C1C",
                    800: "#991B1B",
                    900: "#7F1D1D",
                },

                // レガシーサポート（既存のprimary/secondaryを維持）
                primary: {
                    50: "#FAF8F6",
                    100: "#F5F1ED",
                    200: "#EBE1D7",
                    300: "#E0D1C1",
                    400: "#D5C1AB",
                    500: "#A47864",
                    600: "#936B59",
                    700: "#7A5847",
                    800: "#614536",
                    900: "#5D3E2B",
                },
                secondary: {
                    50: "#F4F7F0",
                    100: "#E9F0E1",
                    200: "#D3E1C3",
                    300: "#BDD2A5",
                    400: "#A7C387",
                    500: "#87A96B",
                    600: "#6D8A55",
                    700: "#5A7346",
                    800: "#475C37",
                    900: "#344528",
                },
            },

            // フォントファミリー（日本語最適化）
            fontFamily: {
                sans: [
                    "Inter",
                    "Hiragino Sans",
                    "Hiragino Kaku Gothic ProN",
                    "Yu Gothic",
                    "Meiryo",
                    "sans-serif",
                    ...defaultTheme.fontFamily.sans,
                ],
                ja: [
                    "Hiragino Sans",
                    "Hiragino Kaku Gothic ProN",
                    "Yu Gothic Medium",
                    "Meiryo",
                    "Inter",
                    "sans-serif",
                ],
                mono: [
                    "JetBrains Mono",
                    "SF Mono",
                    "Monaco",
                    "Cascadia Code",
                    ...defaultTheme.fontFamily.mono,
                ],
            },

            // スペーシングシステム（8pxベース）
            spacing: {
                18: "4.5rem", // 72px
                72: "18rem", // 288px
                84: "21rem", // 336px
                96: "24rem", // 384px
            },

            // アイコンサイズ
            width: {
                18: "4.5rem",
                72: "18rem",
                84: "21rem",
                96: "24rem",
            },
            height: {
                18: "4.5rem",
                72: "18rem",
                84: "21rem",
                96: "24rem",
            },

            // ボーダーラディウス（モダンな丸み）
            borderRadius: {
                xl: "0.75rem",
                "2xl": "1rem",
                "3xl": "1.5rem",
                "4xl": "2rem",
            },

            // シャドウ（ニューモーフィズム対応）
            boxShadow: {
                "inner-lg":
                    "inset 0 10px 15px -3px rgba(0, 0, 0, 0.1), inset 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                neumorphism:
                    "8px 8px 16px rgba(164, 120, 100, 0.2), -8px -8px 16px rgba(255, 255, 255, 0.8)",
                "neumorphism-inset":
                    "inset 8px 8px 16px rgba(164, 120, 100, 0.2), inset -8px -8px 16px rgba(255, 255, 255, 0.8)",
                glass: "0 8px 32px 0 rgba(164, 120, 100, 0.15)",
                glow: "0 0 20px rgba(164, 120, 100, 0.4)",
            },

            // アニメーション
            animation: {
                "fade-in": "fadeIn 0.6s ease-out",
                "slide-up": "slideUp 0.6s ease-out",
                "slide-down": "slideDown 0.6s ease-out",
                "scale-in": "scaleIn 0.3s ease-out",
                "bounce-gentle": "bounceGentle 0.6s ease-out",
                float: "float 3s ease-in-out infinite",
                shimmer: "shimmer 2s infinite",
            },

            // キーフレーム
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0", transform: "translateY(10px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideUp: {
                    "0%": { opacity: "0", transform: "translateY(20px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideDown: {
                    "0%": { opacity: "0", transform: "translateY(-20px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                scaleIn: {
                    "0%": { opacity: "0", transform: "scale(0.9)" },
                    "100%": { opacity: "1", transform: "scale(1)" },
                },
                bounceGentle: {
                    "0%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-5px)" },
                    "100%": { transform: "translateY(0)" },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-10px)" },
                },
                shimmer: {
                    "0%": { backgroundPosition: "-200% 0" },
                    "100%": { backgroundPosition: "200% 0" },
                },
            },

            // ブレークポイント（レスポンシブ）
            screens: {
                xs: "475px",
                "3xl": "1680px",
            },

            // バックドロップフィルター（グラスモーフィズム）
            backdropBlur: {
                xs: "2px",
                sm: "4px",
                md: "8px",
                lg: "12px",
                xl: "16px",
                "2xl": "24px",
                "3xl": "40px",
            },
        },
    },

    plugins: [
        forms,
        // カスタムプラグイン: グラスモーフィズム
        function ({ addUtilities }) {
            const newUtilities = {
                ".glass": {
                    background: "rgba(255, 255, 255, 0.15)",
                    "backdrop-filter": "blur(10px)",
                    "-webkit-backdrop-filter": "blur(10px)",
                    border: "1px solid rgba(255, 255, 255, 0.2)",
                },
                ".glass-dark": {
                    background: "rgba(0, 0, 0, 0.15)",
                    "backdrop-filter": "blur(10px)",
                    "-webkit-backdrop-filter": "blur(10px)",
                    border: "1px solid rgba(255, 255, 255, 0.1)",
                },
                ".neumorphism": {
                    background: "linear-gradient(145deg, #f0f0f0, #cacaca)",
                    "box-shadow":
                        "8px 8px 16px #bebebe, -8px -8px 16px #ffffff",
                },
                ".neumorphism-inset": {
                    background: "linear-gradient(145deg, #cacaca, #f0f0f0)",
                    "box-shadow":
                        "inset 8px 8px 16px #bebebe, inset -8px -8px 16px #ffffff",
                },
            };
            addUtilities(newUtilities);
        },
    ],
};
