import { FC, useEffect, useState } from "react";

import LoaderIndicator from "../../components/LoaderIndicator";
import ApiService from "../../services/apiService/ApiService";
import Ingredient from "../../services/apiService/interfaces/Ingredient";
import IngredientCard from "./IngredientCard";

const Ingredients: FC = () => {
  const apiService = ApiService.getInstance();
  const [ingredients, setIngredients] = useState<Ingredient[] | null>(null);

  useEffect(
    function () {
      void apiService.getIngredients().then(function (ingredients) {
        setIngredients(ingredients);
      });
    },
    [apiService],
  );

  return (
    <>
      <h5 className="text-6xl font-bold">Ingredientes</h5>
      <br />
      {ingredients === null ? (
        <LoaderIndicator />
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
          {ingredients.map((ingredient) => (
            <IngredientCard ingredient={ingredient} key={ingredient.id} />
          ))}
        </div>
      )}
    </>
  );
};

export default Ingredients;
