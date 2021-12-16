import { FC } from "react";
import { Route, Routes } from "react-router-dom";

import BaseLayout from "./modules/base/BaseLayout";
import MainPage from "./modules/main/MainPage";
import OrdersInProgress from "./modules/ordersInProgress/OrdersInProgress";
import Recipes from "./modules/recipes/Recipes";
import OrdersHistory from "./modules/ordersHistory/OrdersHistory";
import Ingredients from "./modules/ingredients/Ingredients";
import IngredientPurchases from "./modules/ingredientsPurchases/IngredientPurchases";

const App: FC = () => {
  return (
    <>
      <BaseLayout>
        <Routes>
          <Route path="/" element={<MainPage />} />
          <Route path="/recipes" element={<Recipes />} />
          <Route path="/orders_in_progress" element={<OrdersInProgress />} />
          <Route path="/orders_history" element={<OrdersHistory />} />
          <Route path="/ingredients" element={<Ingredients />} />
          <Route path="/ingredient_purchases" element={<IngredientPurchases />} />
        </Routes>
      </BaseLayout>
    </>
  );
};

export default App;
