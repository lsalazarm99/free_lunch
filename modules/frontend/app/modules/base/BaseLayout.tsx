import { faBars } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { FC } from "react";
import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

import Drawer from "./Drawer";

const BaseLayout: FC = (props) => {
  return (
    <div className="bg-base-200 drawer drawer-mobile h-screen">
      <input id="my-drawer" type="checkbox" className="drawer-toggle" />
      <div className="drawer-content p-8">
        <div className="navbar mb-8 shadow-lg bg-neutral text-neutral-content rounded-box">
          <label htmlFor="my-drawer" className="btn btn-square btn-ghost lg:hidden drawer-button">
            <FontAwesomeIcon icon={faBars} size={"lg"} />
          </label>
          <div className="flex-1 px-2 mx-2">
            <span className="text-lg font-bold">A free lunch!</span>
          </div>
        </div>

        <ToastContainer />

        {props.children}
      </div>
      <Drawer />
    </div>
  );
};

export default BaseLayout;
