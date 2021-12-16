import CssMinimizerPlugin from "css-minimizer-webpack-plugin";
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import TerserPlugin from "terser-webpack-plugin";
import merge from "webpack-merge";
import rendererConfig from "./webpack.base";

const config = merge(rendererConfig, {
  mode: "production",
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        parallel: true,
      }),
      new CssMinimizerPlugin(),
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "style.css",
    }),
  ],
});

export default config;
