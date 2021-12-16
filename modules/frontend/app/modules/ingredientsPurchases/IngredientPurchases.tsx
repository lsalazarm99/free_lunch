import { FC, useEffect, useState } from "react";

import LoaderIndicator from "../../components/LoaderIndicator";
import ApiService from "../../services/apiService/ApiService";
import IngredientPurchase from "../../services/apiService/interfaces/IngredientPurchase";
import IngredientPurchaseCard from "./IngredientPurchaseCard";

const IngredientPurchases: FC = () => {
  const apiService = ApiService.getInstance();
  const [ingredientPurchases, setIngredientPurchases] = useState<IngredientPurchase[] | null>(null);

  useEffect(() => {
    void apiService.searchIngredientPurchases().then((paginatedIngredientPurchases) => {
      setIngredientPurchases(paginatedIngredientPurchases.data);
    });
  }, [apiService]);

  useEffect(() => {
    const interval = setInterval(
      () =>
        void apiService.searchIngredientPurchases().then((paginatedIngredientPurchases) => {
          setIngredientPurchases(paginatedIngredientPurchases.data);
        }),
      1000 * 60,
    );
    return () => {
      clearInterval(interval);
    };
  }, [apiService]);

  return (
    <>
      <h5 className="text-6xl font-bold">Historial de compras de ingredientes</h5>
      <br />
      {ingredientPurchases === null ? (
        <LoaderIndicator />
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
          {ingredientPurchases.map((ingredientPurchases) => (
            <IngredientPurchaseCard ingredientPurchase={ingredientPurchases} key={ingredientPurchases.id} />
          ))}
        </div>
      )}
    </>
  );
};

export default IngredientPurchases;
