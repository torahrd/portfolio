import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./public/js/**/*.js",
    ],

    theme: {
        extend: {
            // ★完全版 2025年トレンドカラーパレット
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
                    950: "#0F2419",
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
                    950: "#451A03",
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
                    950: "#450A0A",
                },

                // レガシーサポート（既存コードとの互換性）
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
                    950: "#3D2A1C",
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
                    950: "#212E1A",
                },
            },

            // フォントファミリー（日本語最適化）
            fontFamily: {
                sans: [
                    "Inter",
                    "Hiragino Sans",
                    "Hiragino Kaku Gothic ProN",
                    "Yu Gothic Medium",
                    "Meiryo",
                    "system-ui",
                    "sans-serif",
                    ...defaultTheme.fontFamily.sans,
                ],
                ja: [
                    "Hiragino Sans",
                    "Hiragino Kaku Gothic ProN",
                    "Yu Gothic Medium",
                    "Meiryo",
                    "Inter",
                    "system-ui",
                    "sans-serif",
                ],
                mono: [
                    "JetBrains Mono",
                    "SF Mono",
                    "Monaco",
                    "Cascadia Code",
                    "Consolas",
                    ...defaultTheme.fontFamily.mono,
                ],
                display: ["Inter", "Hiragino Sans", "system-ui", "sans-serif"],
            },

            // フォントサイズ（タイポグラフィスケール）
            fontSize: {
                "2xs": ["0.625rem", { lineHeight: "0.75rem" }], // 10px
                xs: ["0.75rem", { lineHeight: "1rem" }], // 12px
                sm: ["0.875rem", { lineHeight: "1.25rem" }], // 14px
                base: ["1rem", { lineHeight: "1.5rem" }], // 16px
                lg: ["1.125rem", { lineHeight: "1.75rem" }], // 18px
                xl: ["1.25rem", { lineHeight: "1.75rem" }], // 20px
                "2xl": ["1.5rem", { lineHeight: "2rem" }], // 24px
                "3xl": ["1.875rem", { lineHeight: "2.25rem" }], // 30px
                "4xl": ["2.25rem", { lineHeight: "2.5rem" }], // 36px
                "5xl": ["3rem", { lineHeight: "1" }], // 48px
                "6xl": ["3.75rem", { lineHeight: "1" }], // 60px
                "7xl": ["4.5rem", { lineHeight: "1" }], // 72px
                "8xl": ["6rem", { lineHeight: "1" }], // 96px
                "9xl": ["8rem", { lineHeight: "1" }], // 128px
            },

            // スペーシングシステム（8pxベース）
            spacing: {
                18: "4.5rem", // 72px
                72: "18rem", // 288px
                84: "21rem", // 336px
                96: "24rem", // 384px
                128: "32rem", // 512px
            },

            // ボーダーラディウス（モダンな丸み）
            borderRadius: {
                none: "0",
                sm: "0.125rem", // 2px
                DEFAULT: "0.25rem", // 4px
                md: "0.375rem", // 6px
                lg: "0.5rem", // 8px
                xl: "0.75rem", // 12px
                "2xl": "1rem", // 16px
                "3xl": "1.5rem", // 24px
                "4xl": "2rem", // 32px
                full: "9999px",
            },

            // シャドウ（グラスモーフィズム・ニューモーフィズム対応）
            boxShadow: {
                // グラスモーフィズム
                "glass-sm": "0 2px 8px 0 rgba(31, 38, 135, 0.15)",
                glass: "0 8px 32px 0 rgba(31, 38, 135, 0.25)",
                "glass-lg": "0 12px 40px 0 rgba(31, 38, 135, 0.35)",

                // ニューモーフィズム
                "neu-inset":
                    "inset 4px 4px 8px #c9c9c9, inset -4px -4px 8px #ffffff",
                "neu-outset": "4px 4px 8px #c9c9c9, -4px -4px 8px #ffffff",
                "neu-pressed":
                    "inset 6px 6px 12px #c9c9c9, inset -6px -6px 12px #ffffff",

                // カスタムシャドウ
                soft: "0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)",
                medium: "0 4px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 30px -5px rgba(0, 0, 0, 0.05)",
                hard: "0 10px 40px -10px rgba(0, 0, 0, 0.15), 0 20px 50px -10px rgba(0, 0, 0, 0.1)",

                // カラー付きシャドウ
                mocha: "0 8px 25px -8px rgba(164, 120, 100, 0.3)",
                sage: "0 8px 25px -8px rgba(135, 169, 107, 0.3)",
                electric: "0 8px 25px -8px rgba(30, 144, 255, 0.3)",
                coral: "0 8px 25px -8px rgba(255, 107, 107, 0.3)",
            },

            // ブラー効果（グラスモーフィズム用）
            backdropBlur: {
                xs: "2px",
                sm: "4px",
                DEFAULT: "8px",
                md: "12px",
                lg: "16px",
                xl: "24px",
                "2xl": "40px",
                "3xl": "64px",
            },

            // アニメーション・トランジション
            transitionTimingFunction: {
                "bounce-in": "cubic-bezier(0.68, -0.55, 0.265, 1.55)",
                "bounce-out": "cubic-bezier(0.175, 0.885, 0.32, 1.275)",
                smooth: "cubic-bezier(0.4, 0, 0.2, 1)",
                swift: "cubic-bezier(0.4, 0, 0.6, 1)",
                snappy: "cubic-bezier(0.4, 0, 1, 1)",
            },

            transitionDuration: {
                0: "0ms",
                75: "75ms",
                100: "100ms",
                150: "150ms",
                200: "200ms",
                300: "300ms",
                500: "500ms",
                700: "700ms",
                1000: "1000ms",
                1500: "1500ms",
                2000: "2000ms",
            },

            // アニメーション
            animation: {
                // 基本アニメーション
                "fade-in": "fadeIn 0.5s ease-out",
                "fade-out": "fadeOut 0.5s ease-out",
                "slide-in-left": "slideInLeft 0.6s ease-out",
                "slide-in-right": "slideInRight 0.6s ease-out",
                "slide-in-up": "slideInUp 0.6s ease-out",
                "slide-in-down": "slideInDown 0.6s ease-out",
                "scale-in": "scaleIn 0.4s ease-out",
                "scale-out": "scaleOut 0.4s ease-out",

                // マイクロアニメーション
                "bounce-subtle": "bounceSubtle 0.6s ease-out",
                wiggle: "wiggle 1s ease-in-out infinite",
                "pulse-slow": "pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                "ping-slow": "ping 2s cubic-bezier(0, 0, 0.2, 1) infinite",

                // フローティングアニメーション
                float: "float 6s ease-in-out infinite",
                "float-delayed": "float 6s ease-in-out infinite 2s",
                "float-slow": "float 8s ease-in-out infinite",

                // ホバーアニメーション
                "hover-lift": "hoverLift 0.3s ease-out",
                "hover-scale": "hoverScale 0.2s ease-out",

                // ローディングアニメーション
                "spin-slow": "spin 3s linear infinite",
                "spin-reverse": "spinReverse 1s linear infinite",

                // パルス・グロー効果
                glow: "glow 2s ease-in-out infinite alternate",
                "glow-pulse": "glowPulse 1.5s ease-in-out infinite",
            },

            keyframes: {
                // フェードアニメーション
                fadeIn: {
                    "0%": { opacity: "0", transform: "translateY(10px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                fadeOut: {
                    "0%": { opacity: "1", transform: "translateY(0)" },
                    "100%": { opacity: "0", transform: "translateY(-10px)" },
                },

                // スライドアニメーション
                slideInLeft: {
                    "0%": { opacity: "0", transform: "translateX(-30px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                slideInRight: {
                    "0%": { opacity: "0", transform: "translateX(30px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                slideInUp: {
                    "0%": { opacity: "0", transform: "translateY(30px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideInDown: {
                    "0%": { opacity: "0", transform: "translateY(-30px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },

                // スケールアニメーション
                scaleIn: {
                    "0%": { opacity: "0", transform: "scale(0.9)" },
                    "100%": { opacity: "1", transform: "scale(1)" },
                },
                scaleOut: {
                    "0%": { opacity: "1", transform: "scale(1)" },
                    "100%": { opacity: "0", transform: "scale(0.9)" },
                },

                // マイクロアニメーション
                bounceSubtle: {
                    "0%, 20%, 50%, 80%, 100%": { transform: "translateY(0)" },
                    "40%": { transform: "translateY(-5px)" },
                    "60%": { transform: "translateY(-3px)" },
                },
                wiggle: {
                    "0%, 100%": { transform: "rotate(-3deg)" },
                    "50%": { transform: "rotate(3deg)" },
                },

                // フローティングアニメーション
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-20px)" },
                },

                // ホバーアニメーション
                hoverLift: {
                    "0%": { transform: "translateY(0)" },
                    "100%": { transform: "translateY(-5px)" },
                },
                hoverScale: {
                    "0%": { transform: "scale(1)" },
                    "100%": { transform: "scale(1.05)" },
                },

                // スピンアニメーション
                spinReverse: {
                    "0%": { transform: "rotate(0deg)" },
                    "100%": { transform: "rotate(-360deg)" },
                },

                // グロー効果
                glow: {
                    "0%": { boxShadow: "0 0 20px rgba(164, 120, 100, 0.3)" },
                    "100%": { boxShadow: "0 0 30px rgba(164, 120, 100, 0.6)" },
                },
                glowPulse: {
                    "0%, 100%": {
                        opacity: "1",
                        boxShadow: "0 0 20px rgba(164, 120, 100, 0.4)",
                    },
                    "50%": {
                        opacity: "0.8",
                        boxShadow: "0 0 40px rgba(164, 120, 100, 0.8)",
                    },
                },
            },

            // ブレークポイント（コンテナクエリ対応）
            screens: {
                xs: "475px",
                sm: "640px",
                md: "768px",
                lg: "1024px",
                xl: "1280px",
                "2xl": "1536px",
                "3xl": "1920px",

                // 高さベースのブレークポイント
                "h-sm": { raw: "(min-height: 640px)" },
                "h-md": { raw: "(min-height: 768px)" },
                "h-lg": { raw: "(min-height: 1024px)" },

                // アスペクト比ベース
                landscape: { raw: "(orientation: landscape)" },
                portrait: { raw: "(orientation: portrait)" },

                // 解像度ベース
                retina: { raw: "(-webkit-min-device-pixel-ratio: 2)" },
            },

            // アスペクト比
            aspectRatio: {
                auto: "auto",
                square: "1 / 1",
                video: "16 / 9",
                photo: "4 / 3",
                golden: "1.618 / 1",
                "3/2": "3 / 2",
                "4/5": "4 / 5",
                "9/16": "9 / 16",
            },

            // Z-indexスケール
            zIndex: {
                0: "0",
                10: "10",
                20: "20",
                30: "30",
                40: "40",
                50: "50",
                auto: "auto",
                dropdown: "1000",
                sticky: "1020",
                fixed: "1030",
                "modal-backdrop": "1040",
                modal: "1050",
                popover: "1060",
                tooltip: "1070",
                toast: "1080",
            },
        },
    },

    plugins: [
        forms({
            strategy: "class", // フォームスタイルをクラスベースに
        }),

        // カスタムプラグイン: ユーティリティクラス追加
        function ({ addUtilities, theme }) {
            const newUtilities = {
                // テキスト装飾
                ".text-shadow": {
                    textShadow: "2px 2px 4px rgba(0,0,0,0.1)",
                },
                ".text-shadow-lg": {
                    textShadow: "4px 4px 8px rgba(0,0,0,0.15)",
                },

                // グラスモーフィズム
                ".glass": {
                    background: "rgba(255, 255, 255, 0.15)",
                    backdropFilter: "blur(10px)",
                    border: "1px solid rgba(255, 255, 255, 0.2)",
                },
                ".glass-dark": {
                    background: "rgba(0, 0, 0, 0.15)",
                    backdropFilter: "blur(10px)",
                    border: "1px solid rgba(255, 255, 255, 0.1)",
                },

                // ニューモーフィズム
                ".neu": {
                    background: "#f0f0f0",
                    boxShadow: "4px 4px 8px #c9c9c9, -4px -4px 8px #ffffff",
                },
                ".neu-inset": {
                    background: "#f0f0f0",
                    boxShadow:
                        "inset 4px 4px 8px #c9c9c9, inset -4px -4px 8px #ffffff",
                },

                // スクロールバーのスタイリング
                ".scrollbar-thin": {
                    "&::-webkit-scrollbar": {
                        width: "6px",
                    },
                    "&::-webkit-scrollbar-track": {
                        background: "#f1f1f1",
                        borderRadius: "10px",
                    },
                    "&::-webkit-scrollbar-thumb": {
                        background: "#c1c1c1",
                        borderRadius: "10px",
                    },
                    "&::-webkit-scrollbar-thumb:hover": {
                        background: "#a8a8a8",
                    },
                },

                // フォーカススタイル
                ".focus-ring": {
                    "&:focus": {
                        outline: "2px solid transparent",
                        outlineOffset: "2px",
                        boxShadow: `0 0 0 2px ${theme("colors.mocha.500")}`,
                    },
                },
            };
            addUtilities(newUtilities);
        },

        // カスタムプラグイン: コンポーネントクラス追加
        function ({ addComponents, theme }) {
            addComponents({
                // ボタンベース
                ".btn": {
                    display: "inline-flex",
                    alignItems: "center",
                    justifyContent: "center",
                    paddingLeft: theme("spacing.4"),
                    paddingRight: theme("spacing.4"),
                    paddingTop: theme("spacing.2"),
                    paddingBottom: theme("spacing.2"),
                    fontSize: theme("fontSize.sm[0]"),
                    fontWeight: theme("fontWeight.medium"),
                    borderRadius: theme("borderRadius.lg"),
                    transition: "all 0.2s ease-out",
                    cursor: "pointer",
                    border: "none",
                    textDecoration: "none",

                    "&:focus": {
                        outline: "2px solid transparent",
                        outlineOffset: "2px",
                        boxShadow: `0 0 0 2px ${theme("colors.mocha.500")}`,
                    },

                    "&:disabled": {
                        opacity: "0.5",
                        cursor: "not-allowed",
                    },
                },

                // カードベース
                ".card": {
                    backgroundColor: theme("colors.white"),
                    borderRadius: theme("borderRadius.2xl"),
                    padding: theme("spacing.6"),
                    boxShadow: theme("boxShadow.soft"),
                    border: `1px solid ${theme("colors.neutral.200")}`,
                    transition: "all 0.3s ease-out",

                    "&:hover": {
                        boxShadow: theme("boxShadow.medium"),
                        transform: "translateY(-2px)",
                    },
                },

                // 入力フィールドベース
                ".input": {
                    width: "100%",
                    padding: theme("spacing.3"),
                    fontSize: theme("fontSize.sm[0]"),
                    lineHeight: theme("fontSize.sm[1].lineHeight"),
                    color: theme("colors.neutral.900"),
                    backgroundColor: theme("colors.white"),
                    border: `1px solid ${theme("colors.neutral.300")}`,
                    borderRadius: theme("borderRadius.lg"),
                    transition: "all 0.2s ease-out",

                    "&:focus": {
                        outline: "2px solid transparent",
                        outlineOffset: "2px",
                        borderColor: theme("colors.mocha.500"),
                        boxShadow: `0 0 0 3px ${theme("colors.mocha.500")}33`,
                    },

                    "&::placeholder": {
                        color: theme("colors.neutral.400"),
                    },
                },
            });
        },
    ],

    // ダークモード設定
    darkMode: "class",

    // 本番環境でのCSS最適化
    corePlugins: {
        preflight: true,
    },
};
