import { FC, useEffect, useState } from "react";

import LoaderIndicator from "../../components/LoaderIndicator";
import ApiService from "../../services/apiService/ApiService";
import { Order } from "../../services/apiService/interfaces/Order";
import OrderCard from "./OrderCard";

const OrdersHistory: FC = () => {
  const apiService = ApiService.getInstance();
  const [orders, setOrders] = useState<Order[] | null>(null);

  useEffect(() => {
    void apiService.searchOrders().then((paginatedOrders) => {
      setOrders(paginatedOrders.data);
    });
  }, [apiService]);

  useEffect(() => {
    const interval = setInterval(
      () =>
        void apiService.searchOrders().then((paginatedOrders) => {
          setOrders(paginatedOrders.data);
        }),
      1000 * 5,
    );
    return () => {
      clearInterval(interval);
    };
  }, [apiService]);

  return (
    <>
      <>
        <h5 className="text-6xl font-bold">Historial de órdenes</h5>
        <br />
        {orders === null ? (
          <LoaderIndicator />
        ) : (
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            {orders.map((order) => (
              <OrderCard order={order} key={order.id} />
            ))}
          </div>
        )}
      </>
    </>
  );
};

export default OrdersHistory;
