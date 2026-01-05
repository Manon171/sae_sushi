import { Component, OnInit } from '@angular/core';
import { BoxesService, Box } from '../../services/boxes.service';

@Component({
  selector: 'app-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

  boxes: Box[] = [];

  constructor(private boxesService: BoxesService) { }

  ngOnInit(): void {
 this.boxesService.getAll().subscribe(
  (data: Box[]) => {
    this.boxes = data;
  },
  (err: any) => {
    console.error(err);
  }
);

  }

}
