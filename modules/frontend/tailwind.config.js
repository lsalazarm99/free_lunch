// noinspection JSValidateTypes
/** @type {import("tailwindcss/tailwind-config").TailwindConfig} */
const config = {
  purge: ["./app/**/*.html", "./app/**/*.{js,jsx,ts,tsx}"],
  darkMode: "media",
  plugins: [
    require('daisyui'),
  ],
};

module.exports = config;
