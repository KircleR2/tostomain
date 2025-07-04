/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
    container: {
      center: true
    },
    fontFamily: {
      'action': ['Bebas Neue'],
      'write': ['Hey August'],
      'body': ['Poppins'],
    }
  },
  corePlugins: {
    container: false
  },
  plugins: [
    function ({ addComponents }) {
      addComponents({
        '.container': {
          maxWidth: '100%',
          '@screen sm': {
            maxWidth: '600px',
          },
          '@screen md': {
            maxWidth: '800px',
          },
          '@screen lg': {
            maxWidth: '900px',
          },
          '@screen xl': {
            maxWidth: '1200px',
          },
        }
      })
    }
  ]
}
