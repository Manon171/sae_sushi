import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import {RegisterComponent} from './auth/register/register.component';
import { AccueilComponent } from './accueil/accueil.component';


@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RegisterComponent, AccueilComponent ],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})


export class AppComponent {
}
