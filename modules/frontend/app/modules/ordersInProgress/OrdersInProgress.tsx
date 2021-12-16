import { FC, useEffect, useState } from "react";

import LoaderIndicator from "../../components/LoaderIndicator";
import ApiService from "../../services/apiService/ApiService";
import { Order } from "../../services/apiService/interfaces/Order";
import OrderInProgressCard from "./OrderInProgressCard";

const OrdersInProgress: FC = () => {
  const apiService = ApiService.getInstance();
  const [orders, setOrders] = useState<Order[] | null>(null);

  useEffect(() => {
    void apiService.searchOrders({ in_process: 1 }).then((paginatedOrders) => {
      setOrders(paginatedOrders.data);
    });
  }, [apiService]);

  useEffect(() => {
    const interval = setInterval(
      () =>
        void apiService.searchOrders({ in_process: 1 }).then((paginatedOrders) => {
          setOrders(paginatedOrders.data);
        }),
      1000 * 60,
    );
    return () => {
      clearInterval(interval);
    };
  }, [apiService]);

  return (
    <>
      <>
        <h5 className="text-6xl font-bold">Órdenes en proceso</h5>
        <br />
        {orders === null ? (
          <LoaderIndicator />
        ) : (
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            {orders.map((order) => (
              <OrderInProgressCard order={order} key={order.id} />
            ))}
          </div>
        )}
      </>
    </>
  );
};

export default OrdersInProgress;
