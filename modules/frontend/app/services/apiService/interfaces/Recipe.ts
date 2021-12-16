import RecipeIngredient from "./RecipeIngredient";

export default interface Recipe {
  id: number,
  name: string,
  description: string,
  recipe_ingredients: RecipeIngredient[],
}
