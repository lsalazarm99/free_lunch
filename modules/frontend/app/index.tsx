import React from "react";
import ReactDom from "react-dom";
import { BrowserRouter } from "react-router-dom";
import "tailwindcss/tailwind.css";

import App from "./App";

const appElement = document.querySelector<HTMLElement>("#root");

if (appElement !== null) {
  ReactDom.render(
    <React.StrictMode>
      <BrowserRouter>
        <App />
      </BrowserRouter>
    </React.StrictMode>,
    appElement,
  );
}
