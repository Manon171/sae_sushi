import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Box {
  id: number;
  name: string;
  price: number;
  flavors: string[];
}

@Injectable({
  providedIn: 'root'
})
export class BoxesService {

  private apiUrl = 'http://localhost/sushi_box/api/boxes/index.php';

  constructor(private http: HttpClient) {}

  getAll(): Observable<Box[]> {
    return this.http.get<Box[]>(this.apiUrl);
  }

  getById(id: number): Observable<Box[]> {
    const params = new HttpParams().set('id', id);
    return this.http.get<Box[]>(this.apiUrl, { params });
  }
}
