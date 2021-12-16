import Ingredient from "./Ingredient";

export default interface IngredientPurchase {
  id: number,
  purchased_amount: number,
  was_successful: boolean,
  created_at: string,
  ingredient: Ingredient,
}
