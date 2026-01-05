import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [FormsModule],
  templateUrl: './register.component.html'
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
