/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./dist/**/*.{html,js,php}"],
  theme: {
    extend: {
      colors: {
        'ISwhite': '#FEFEFE',
        'ISshade1': '#8B8B8B',
        'ISshade2': '#383838',
        'ISshade3': '#222222',
        'ISorange': '#F84502',
        'ISyellow': '#F9C91F',
        'ISorange0': '#F8450255',
        'ISyellow0': '#F9C91F55',
        'IScyan': '#11CBFE',
        'ISblue': '#4862FF',
        'IScyan0': '#11CBFE55',
        'ISblue0': '#4862FF55'
      }
    },
  },
  plugins: [],
}

