import CopyPlugin from "copy-webpack-plugin";
import HtmlWebpackPlugin from "html-webpack-plugin";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import path from "path";
import { Configuration, EnvironmentPlugin } from "webpack";

const config: Configuration = {
  target: ["web"],
  resolve: {
    extensions: [".ts", ".tsx", ".js", ".jsx"],
  },
  entry: {
    main: path.resolve(__dirname, "app/index.tsx"),
  },
  output: {
    filename: "js/[name].js",
    path: path.resolve(__dirname, "dist"),
    library: {
      type: "umd",
    },
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        exclude: /node_modules/,
        use: "ts-loader",
      },
      {
        test: /\.css$/,
        use: [
          process.env.NODE_ENV === "production"
            ? MiniCssExtractPlugin.loader
            : {
                loader: "style-loader",
              },
          {
            loader: "css-loader",
            options: {
              url: false,
              importLoaders: 1,
            },
          },
          {
            loader: "postcss-loader",
          },
        ],
      },
    ],
  },
  plugins: [
    new EnvironmentPlugin({
      NODE_ENV: undefined,
    }),
    new HtmlWebpackPlugin({
      filename: "index.html",
      template: path.join(__dirname, "app/index.ejs"),
      minify: {
        collapseWhitespace: true,
        removeAttributeQuotes: true,
        removeComments: true,
      },
      isBrowser: false,
      env: process.env.NODE_ENV,
      isDevelopment: process.env.NODE_ENV !== "production",
    }),
    /*new CopyPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, "app/assets/images/"),
          to: path.resolve(__dirname, "dist/assets/images/"),
        },
      ],
    }),*/
  ],
};

export default config;
