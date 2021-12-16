import { FC } from "react";

import { Order } from "../../services/apiService/interfaces/Order";

type Props = {
  order: Order;
};

const OrderInProgressCard: FC<Props> = ({ order }) => {
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
      </div>
    </div>
  );
};

export default OrderInProgressCard;
