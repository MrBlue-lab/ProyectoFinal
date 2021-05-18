import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './componentes/login/login.component';
import { HomeComponent } from './componentes/home/home.component';
import { RegistroComponent } from './componentes/registro/registro.component';
import { TablerosComponent } from './componentes/tableros/tableros.component';
import { CalendarioComponent } from './componentes/calendario/calendario.component';
import { ChatComponent } from './componentes/chat/chat.component';
import { HelpComponent } from './componentes/help/help.component';

const routes: Routes = [
  {path: '', component: LoginComponent},
  {path: 'login', component: LoginComponent},
  {path: 'home', component: HomeComponent},
  {path: 'registro', component: RegistroComponent},
  {path: 'tableros', component: TablerosComponent},
  {path: 'calendario', component: CalendarioComponent},
  {path: 'chat', component: ChatComponent},
  {path: 'help', component: HelpComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
