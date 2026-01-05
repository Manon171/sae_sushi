import { Component } from '@angular/core';
import { OrderService } from '../../services/order.service';

@Component({
  selector: 'app-cart',
  templateUrl: './cart.component.html'
})
export class CartComponent {

  constructor(private orderService: OrderService) {}

  submitOrder() {

    const payload = {
      user_id: 1, // plus tard : récupéré depuis auth
      items: [
        { box_id: 1, quantity: 2 },
        { box_id: 2, quantity: 1 }
      ]
    };

    this.orderService.createOrder(payload).subscribe({
      next: (res) => {
        console.log('Commande créée', res);
        alert('Commande créée, ID : ' + res.order_id);
      },
      error: (err) => {
        console.error(err);
        alert('Erreur lors de la commande');
      }
    });
  }
}
