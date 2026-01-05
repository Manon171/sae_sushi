import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface OrderItem {
  box_id: number;
  quantity: number;
}

export interface CreateOrderPayload {
  user_id: number;
  items: OrderItem[];
}

@Injectable({
  providedIn: 'root'
})
export class OrderService {

  private apiUrl = 'http://localhost/SAE_301/TD3/sushi_box/api/users/create.php';

  constructor(private http: HttpClient) {}

  createOrder(data: CreateOrderPayload): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }
}
