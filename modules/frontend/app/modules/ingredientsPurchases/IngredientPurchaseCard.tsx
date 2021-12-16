import { FC } from "react";
import IngredientPurchase from "../../services/apiService/interfaces/IngredientPurchase";

type Props = {
  ingredientPurchase: IngredientPurchase;
};

const IngredientPurchaseCard: FC<Props> = ({ingredientPurchase}) => {
  return (
    <div className="card border">
      <div className="card-body">
        <h2 className="card-title">{ingredientPurchase.ingredient.name}</h2>
        <p>
          <span className="font-medium">ID de la compra:</span> {ingredientPurchase.id}
        </p>
        <p>
          <span className="font-medium">Hora de compra:</span>{" "}
          {new Date(ingredientPurchase.created_at).toLocaleString()}
        </p>
        <p>
          <span className="font-medium">Cantidad comprada:</span>{" "}
          {ingredientPurchase.purchased_amount}
        </p>
      </div>
    </div>
  );
};

export default IngredientPurchaseCard;
