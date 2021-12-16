import axios, { AxiosInstance } from "axios";

import Ingredient from "./interfaces/Ingredient";
import IngredientPurchase from "./interfaces/IngredientPurchase";
import { Order } from "./interfaces/Order";
import { Pagination } from "./interfaces/Pagination";
import Recipe from "./interfaces/Recipe";

export default class ApiService {
  private static instance: ApiService;
  private client: AxiosInstance;

  constructor() {
    this.client = axios.create({
      baseURL: "https://free-lunch.lsalazar.dev",
    });
  }

  public static getInstance(): ApiService {
    return this.instance || (this.instance = new this());
  }

  public getRecipes = async (): Promise<Recipe[]> => {
    return (await this.client.get<Recipe[]>("/api/kitchen/recipe/all")).data;
  };

  public requestRandomOrder = async (): Promise<Order> => {
    return (await this.client.post<Order>("api/kitchen/order/random")).data;
  };

  public searchOrders = async (
    parameters: {
      in_process?: 1 | 0;
      completed?: 1 | 0;
      cancelled?: 1 | 0;
      recipe_id?: number;
      max_items_number?: number;
    } = {},
  ): Promise<Pagination<Order>> => {
    return (await this.client.get<Pagination<Order>>("/api/kitchen/order/search", { params: parameters })).data;
  };

  public getIngredients = async (): Promise<Ingredient[]> => {
    return (await this.client.get<Ingredient[]>("/api/warehouse/ingredient/all")).data;
  };

  public searchIngredientPurchases = async (
    parameters: {
      ingredient_id?: number;
      date_from?: string;
      date_to?: string;
      max_items_number?: number;
    } = {},
  ): Promise<Pagination<IngredientPurchase>> => {
    return (
      await this.client.get<Pagination<IngredientPurchase>>("/api/warehouse/ingredient_purchase/search", {
        params: parameters,
      })
    ).data;
  };

  public searchPaginated = async <T>(url: string): Promise<Pagination<T>> => {
    return (await this.client.get<Pagination<T>>(url)).data;
  };
}
