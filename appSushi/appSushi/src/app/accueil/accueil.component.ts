import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { OffreComponent } from '../offre/offre.component';
import { MenuComponent } from '../menu/menu.component';
import { CommonModule } from '@angular/common';
import { ListComponent } from '../boxes/list/list.component';

@Component({
  selector: 'app-accueil',
  standalone: true,
  imports: [FormsModule, OffreComponent, MenuComponent, CommonModule, ListComponent],
  templateUrl: './accueil.component.html',
  styleUrl: './accueil.component.css'
})
export class AccueilComponent {

}
