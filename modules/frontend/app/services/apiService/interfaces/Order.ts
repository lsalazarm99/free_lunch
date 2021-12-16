import Recipe from "./Recipe";

export interface Order {
  id: number,
  is_in_process: boolean,
  is_completed: boolean,
  is_cancelled: boolean,
  created_at: string,
  updated_at: string,
  recipe: Recipe,
}
