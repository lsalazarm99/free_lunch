import {
  faBook,
  faFileInvoiceDollar,
  faHistory,
  faHome,
  faHourglassHalf,
  faPepperHot,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { FC } from "react";
import { Link } from "react-router-dom";

const Drawer: FC = () => {
  return (
    <div className="drawer-side">
      <label htmlFor="my-drawer" className="drawer-overlay" />
      <ul className="menu p-4 overflow-y-auto w-80 bg-base-100 text-base-content">
        <li>
          <Link to="/">
            <a>
              <FontAwesomeIcon icon={faHome} size={"lg"} className="mr-8" />
              <span>Inicio</span>
            </a>
          </Link>
        </li>
        <li className="menu-title">
          <span>Cocina</span>
        </li>
        <li>
          <Link to="/recipes">
            <a>
              <FontAwesomeIcon icon={faBook} size={"lg"} className="mr-8" />
              <span>Recetas</span>
            </a>
          </Link>
        </li>
        <li>
          <Link to="/orders_in_progress">
            <a>
              <FontAwesomeIcon icon={faHourglassHalf} size={"lg"} className="mr-8" />
              <span>Órdenes en progreso</span>
            </a>
          </Link>
        </li>
        <li>
          <Link to="/orders_history">
            <a>
              <FontAwesomeIcon icon={faHistory} size={"lg"} className="mr-8" />
              <span>Historial de órdenes</span>
            </a>
          </Link>
        </li>

        <div className="divider" />

        <li className="menu-title">
          <span>Almacén</span>
        </li>
        <li>
          <Link to="/ingredients">
            <a>
              <FontAwesomeIcon icon={faPepperHot} size={"lg"} className="mr-8" />
              <span>Ingredientes</span>
            </a>
          </Link>
        </li>
        <li>
          <Link to="/ingredient_purchases">
            <a>
              <FontAwesomeIcon icon={faFileInvoiceDollar} size={"lg"} className="mr-8" />
              <span>Historial de compra de ingredientes</span>
            </a>
          </Link>
        </li>
      </ul>
    </div>
  );
};

export default Drawer;
