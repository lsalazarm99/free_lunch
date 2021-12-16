import ReactRefreshWebpackPlugin from "@pmmmwh/react-refresh-webpack-plugin";
import path from "path";
import { EnvironmentPlugin } from "webpack";
import { merge } from "webpack-merge";

import rendererConfig from "./webpack.base";

const config = merge(rendererConfig, {
  mode: "development",
  devtool: "inline-source-map",
  plugins: [
    new EnvironmentPlugin({
      NODE_ENV: "development",
    }),
    new ReactRefreshWebpackPlugin(),
  ],
  devServer: {
    hot: true,
    static: {
      directory: path.join(__dirname, "dist"),
      watch: true,
    },
  },
});

export default config;
