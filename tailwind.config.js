export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",

    // ADD THESE (VERY IMPORTANT)
    "./app/View/Components/**/*.php",
    "./resources/views/components/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
