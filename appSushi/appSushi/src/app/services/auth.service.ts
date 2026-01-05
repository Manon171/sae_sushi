import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface RegisterPayload {
  firstname: string;
  lastname: string;
  email: string;
  password: string;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = 'http://localhost/sushi_box/api/users/add_user.php';

  constructor(private http: HttpClient) {}

  register(data: RegisterPayload): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }
}
