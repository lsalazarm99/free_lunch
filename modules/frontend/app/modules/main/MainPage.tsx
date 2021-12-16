import { FC } from "react";
import { toast } from "react-toastify";

import ApiService from "../../services/apiService/ApiService";
import { Order } from "../../services/apiService/interfaces/Order";

const MainPage: FC = () => {
  const apiService = ApiService.getInstance();

  const reserveOrder = () => {
    void toast.promise<Order>(
      apiService.requestRandomOrder,
      {
        pending: "Procesando la orden",
        success: {
          render({ data }) {
            return `¡Orden procesada! 👌 Un(a) "${(data as Order).recipe.name}" en camino`;
          },
        },
        error: "No se pudo procesar la orden 🤯",
      }
    );
  };

  return (
    <>
      <h5 className="my-16 text-6xl font-bold">¡Un almuerzo gratis! 🧑‍🍳</h5>
      <p className="text-3xl">
        A ti, querido comensal, queremos brindarte un almuerzo totalmente gratis ❤️. Pero para hacerlo más interesante,
        hay una condición 🤔.
      </p>
      <br />
      <p className="text-4xl">. . .</p>
      <br />
      <p className="text-4xl italic">
        ¡El plato será escogido aleatoriamente <span className="not-italic">😱</span>!
      </p>
      <br />
      <p className="text-3xl">
        ¿Tendrás la suerte de obtener el legendario <span className="italic">Pollo a la Brasa</span> 🍗?
      </p>
      <br />
      <br />
      <p className="text-4xl font-bold">¡Compruébalo presionando el siguiente botón!</p>
      <br />
      <br />
      <button className="btn btn-lg btn-primary" onClick={reserveOrder}>
        Generar una orden
      </button>
    </>
  );
};

export default MainPage;
