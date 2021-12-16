import { FC } from "react";

import Ingredient from "../../services/apiService/interfaces/Ingredient";

type Props = {
  ingredient: Ingredient;
};

const IngredientCard: FC<Props> = ({ ingredient }) => {
  return (
    <div className="card border">
      <div className="card-body">
        <h2 className="card-title">{ingredient.name}</h2>
        <p>
          <span className="font-bold">Cantidad disponible:</span> {ingredient.amount}
        </p>
      </div>
    </div>
  );
};

export default IngredientCard;
