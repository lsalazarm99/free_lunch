import { FC, useEffect, useState } from "react";

import LoaderIndicator from "../../components/LoaderIndicator";
import ApiService from "../../services/apiService/ApiService";
import Recipe from "../../services/apiService/interfaces/Recipe";
import RecipeCard from "./RecipeCard";

const Recipes: FC = () => {
  const apiService = ApiService.getInstance();
  const [recipes, setRecipes] = useState<Recipe[] | null>(null);

  useEffect(
    function () {
      void apiService.getRecipes().then(function (recipes) {
        setRecipes(recipes);
      });
    },
    [apiService],
  );

  return (
    <>
      <h5 className="text-6xl font-bold">Recetas</h5>
      <br />
      {recipes === null ? (
        <LoaderIndicator />
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
          {recipes.map((recipe) => (
            <RecipeCard recipe={recipe} key={recipe.id} />
          ))}
        </div>
      )}
    </>
  );
};

export default Recipes;
