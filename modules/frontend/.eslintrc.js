module.exports = {
  root: true,
  env: {
    browser: true,
    commonjs: true,
  },
  parser: "@typescript-eslint/parser",
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
    },
    sourceType: "module",
    project: ["./tsconfig.eslint.json"],
  },
  plugins: ["react", "@typescript-eslint"],
  extends: [
    "eslint:recommended",
    "plugin:@typescript-eslint/recommended",
    "plugin:@typescript-eslint/recommended-requiring-type-checking",
    "plugin:react/recommended",
    "plugin:react/jsx-runtime",
    "plugin:react-hooks/recommended",
    "prettier",
  ],
  ignorePatterns: ["node_modules", "dist", "coverage", "/*.js"],
  rules: {
    "@typescript-eslint/ban-ts-comment": "off",
  },
  overrides: [
    {
      files: ["**/*.tsx"],
      rules: {
        /** Typescript is already checking types so... Unless you want
         *  runtime validation. */
        "react/prop-types": "off",
      },
    },
  ],
};
