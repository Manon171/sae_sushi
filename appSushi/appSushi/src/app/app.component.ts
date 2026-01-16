import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import {RegisterComponent} from './auth/register/register.component';



@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RegisterComponent, ],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})


export class AppComponent {
}
