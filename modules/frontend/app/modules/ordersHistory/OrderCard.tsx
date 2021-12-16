import { FC } from "react";

import { Order } from "../../services/apiService/interfaces/Order";

type Props = {
  order: Order;
};

const OrderCard: FC<Props> = ({ order }) => {
  return (
    <div className="card border">
      <div className="card-body">
        <h2 className="card-title">{order.recipe.name}</h2>
        <p>
          <span className="font-medium">ID de la orden:</span> {order.id}
        </p>
        <p>
          <span className="font-medium">Hora de solicitud de la orden:</span>{" "}
          {new Date(order.created_at).toLocaleString()}
        </p>
        {order.is_in_process ? (
          <></>
        ) : (
          <p>
            <span className="font-medium">Hora de {order.is_completed ? "entrega" : "cancelación"} de la orden:</span>{" "}
            {new Date(order.updated_at).toLocaleString()})
          </p>
        )}
      </div>
    </div>
  );
};

export default OrderCard;
