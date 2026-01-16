import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AccueilComponent } from '../../accueil/accueil.component';
@Component({
  selector: 'app-register',
  standalone: true,
  imports: [FormsModule, AccueilComponent, ],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {

  form = {
    firstname: '',
    lastname: '',
    email: '',
    password: ''
  };

  submit() {
    console.log(this.form);
  }
}
