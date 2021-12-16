import IngredientMinimal from "./IngredientMinimal";

export default interface RecipeIngredient {
  id: number,
  ingredient: IngredientMinimal,
  ingredients_amount: number,
}
