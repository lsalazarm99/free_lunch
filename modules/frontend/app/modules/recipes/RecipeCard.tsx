import { FC } from "react";

import Recipe from "../../services/apiService/interfaces/Recipe";
import RecipeIngredient from "../../services/apiService/interfaces/RecipeIngredient";

type Props = {
  recipe: Recipe;
};

const RecipeCard: FC<Props> = ({ recipe }) => {
  return (
    <div className="card border">
      <div className="card-body">
        <h2 className="card-title">{recipe.name}</h2>
        <p>{recipe.description}</p>
        <div className="divider" />
        <p>Ingredientes requeridos:</p>
        <ul>
          {recipe.recipe_ingredients.map((recipeIngredient: RecipeIngredient) => (
            <p key={recipeIngredient.id}>
              <span className="font-bold">{recipeIngredient.ingredient.name}:</span>{" "}
              {recipeIngredient.ingredients_amount} unidades
            </p>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default RecipeCard;
