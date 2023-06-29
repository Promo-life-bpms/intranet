<?php

namespace App\Http\Controllers\Soporte;

use App\Models\Access;
use Illuminate\Http\Request;
use App\Models\Soporte\Categoria;
use App\Http\Controllers\Controller;
use App\Models\Soporte\Ticket;
use App\Models\User;
use App\Models\Soporte\encuesta;
use App\Models\SoporteTiempo;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class SoporteController extends Controller
{
    //


    public function index()
    {
        return view('soporte.index');
    }

    public function solucion()
    {
        return view('soporte.solucion');
    }

    public function admin()
    {
        return view('soporte.admin');
    }


    //Me trae todos los tickets sin filtrado
    public function estadisticas()
    {
        $labels = [];
        $califications=[5,4,3,2,1];
        $meses = [];
        $usuario = [];
        $name = [];
        $soporte = [];
        $values = [];
        $ticketsPorMes = [];
        $ticketsPriority = [];
        $ticketCounts = [];
        $totalTicket = [];
        $namePriority = [];
        $ticket_especial=[];
        //variables para guardar la fecha que se quiere filtrar
        $startDate = null;
        $endDate = null;
        //Trae los nombres de las prioridaes
        // $prioridad = SoporteTiempo::where('id', '>',1)->and('id','<',5)->get();
        $prioridad= SoporteTiempo::whereIn('id',[2,3,4,5])->get();
        $namePriority = $prioridad->pluck('priority')->toArray();

        //traer los tickets
        $ticket=Ticket::all();
        $prioritys=[2,3,4,5];
        //Contar los tickets
        foreach($prioritys as $priority){
            $conteo=Ticket::where('priority_id',$priority)->count();
            $ticketsPriority[]=$conteo;
        }
        //me trae todos los tickets que tienen encuesta realizadas
        $calificaciones = encuesta::all();
        //creo un arreglo para que busque los datos que quiero
        $estrellas = [5, 4, 3, 2, 1];
        //creo un arreglo que almacenara el conteo
        $TotalEstrellas = [];

        foreach ($estrellas as $estrella) {
            $conteo = encuesta::where('score', $estrella)->count();
            $TotalEstrellas[] = $conteo;
        }

        //TRAER LOS TICKETS
        $Priority_especial=SoporteTiempo::whereIn('id',[5])->get();
        // $especial=$Priority_especial->pluck('priority')->toArray();
        $prioridad=[5];

        foreach($prioridad as $especial){
            $conteo=Ticket::where('priority_id',$prioridad)->count();
            $ticket_especial[]=$conteo;
        }


        // dd($ticketsPriority);
        //traer la cantidad de tickets por un usuario
        $usuarios = User::has('tickets')->get();
        $name = $usuarios->pluck('name')->toArray();
        $totalTicket = [];
        foreach ($usuarios as $usuario) {
            $ticket = Ticket::where('user_id', $usuario->id)->count();
            $totalTicket[] = $ticket;
        }
        // aqui me trae a todos los usuarios con el rol de sistemas
        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        //me trae el nombre de los usuarios que dan soporte
        $usuario = $users->pluck('name')->toArray();
        //hace el conteo de los ticktets que reciben los de soporte
        foreach ($users as $user) {
            $ticket = Ticket::where('support_id', $user->id)->count();
            $soporte[] = $ticket;
        }

        //Trae todas las categorias
        $category = Categoria::all();
        $labels = $category->pluck('name')->toArray();


        //cuenta los tickets con status ticket cerrado
        foreach ($category as $categoria) {
            $ticketsCount = Ticket::where('status_id', 4)->where('category_id', $categoria->id)->count();
            $values[] = $ticketsCount;
        }
        $values = collect($values)->toArray();
        //Traer todas las categorías
        // trae los tickets con status ticket cerrado
        $tickets = Ticket::where('status_id', 4)->get();
        // Agrupar los tickets con status cerrado por mes
        $ticketsByMonth = $tickets->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('F Y');
        });

        // Obtener los meses y contar los tickets por mes
        foreach ($ticketsByMonth as $month => $monthTickets) {
            $meses[] = $month;
            $ticketsPorMes[] = $monthTickets->count();
        }



        $ticketsResueltos = Ticket::where('status_id', 4)->count();
        $ticketsEnProceso = Ticket::where('status_id', 2)->count();
        $ticketsCreados = Ticket::all()->count();

        return view('Soporte.estadisticas', compact(
            'ticketsResueltos',
            'ticketsEnProceso',
            'ticketsCreados',
            'labels',
            'values',
            'meses',
            'ticketsPorMes',
            'usuario',
            'soporte',
            'name',
            'totalTicket',
            'startDate',
            'endDate',
            'namePriority',
            'ticketsPriority',
            'califications',
            'TotalEstrellas',
        ));
    }

    //Me trae los tickets filtrados por mes
    public function filterTicket(Request $request)
    {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $califications=[5,4,3,2,1];
        $labels = [];
        $meses = [];
        $usuario = [];
        $name = [];
        $soporte = [];
        $values = [];
        $ticketsPorMes = [];
        $ticketCounts = [];
        $totalTicket = [];

        // Traer la cantidad de tickets por un usuario
        $usuarios = User::has('tickets')->get();
        $name = $usuarios->pluck('name')->toArray();
        $totalTicket = [];

        foreach ($usuarios as $usuario) {
            $ticket = Ticket::where('user_id', $usuario->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            $totalTicket[] = $ticket;
        }

        // Traer a todos los usuarios con el rol de sistemas
        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        // Traer el nombre de los usuarios que dan soporte
        $usuario = $users->pluck('name')->toArray();

        // Hacer el conteo de los tickets que reciben los de soporte
        $soporte = [];
        foreach ($users as $user) {
            $ticket = Ticket::where('support_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            $soporte[] = $ticket;
        }

        // Traer todas las categorías
        $category = Categoria::all();
        $labels = $category->pluck('name')->toArray();

        // Contar los tickets con status ticket cerrado
        foreach ($category as $categoria) {
            $ticketsCount = Ticket::where('status_id', 4)->where('category_id', $categoria->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            $values[] = $ticketsCount;
        }
        $values = collect($values)->toArray();
        // Traer los tickets con status ticket cerrado
        $ticketsQuery = Ticket::where('status_id', 4);

        // Aplicar filtro de fechas si están definidas
        if ($startDate && $endDate) {
            $ticketsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $tickets = $ticketsQuery->get();

        // Agrupar los tickets con status cerrado por mes
        $ticketsByMonth = $tickets->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('F Y');
        });

        // Obtener los meses y contar los tickets por mes
        foreach ($ticketsByMonth as $month => $monthTickets) {
            $meses[] = $month;
            $ticketsPorMes[] = $monthTickets->count();
        }

        //Tickets por prioridad
        $prioridad = SoporteTiempo::where('id', '>', 1)->get();
        $namePriority = $prioridad->pluck('priority')->toArray();
        $prioritys=[2,3,4,5];
        foreach($prioritys as $priority){
            $conteo=Ticket::where('priority_id',$priority)->whereBetween('created_at',[$startDate,$endDate])->count();
            $ticketsPriority[]=$conteo;
        }
        //Tickets por cantidad de estrellas por fechas
          $calificaciones = encuesta::all();
          $estrellas = [5, 4, 3, 2, 1];
          $TotalEstrellas = [];
          foreach ($estrellas as $estrella) {
              $conteo = encuesta::where('score', $estrella)->whereBetween('created_at',[$startDate,$endDate])->count();
              $TotalEstrellas[] = $conteo;
          }
        //Trae los totales de tickets resueltos, en proceso , creados
        $ticketsResueltos = Ticket::where('status_id', 4)->whereBetween('created_at', [$startDate, $endDate])->count();
        $ticketsEnProceso = Ticket::where('status_id', 2)->whereBetween('created_at', [$startDate, $endDate])->count();
        $ticketsCreados = Ticket::all()->whereBetween('created_at', [$startDate, $endDate])->count();


        return view('Soporte.estadisticas', compact(
            'ticketsResueltos',
            'ticketsEnProceso',
            'ticketsCreados',
            'labels',
            'values',
            'meses',
            'ticketsPorMes',
            'usuario',
            'soporte',
            'name',
            'totalTicket',
            'startDate',
            'endDate',
            'namePriority',
            'ticketsPriority',
            'TotalEstrellas',
            'califications'
        ));
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;

            //Upload File
            $request->file('upload')->storeAs('public/uploads', $filenametostore);
            $request->file('upload')->storeAs('public/uploads/thumbnail', $filenametostore);

            //Resize image here
            $thumbnailpath = public_path('storage/uploads/thumbnail/' . $filenametostore);
            $img = Image::make($thumbnailpath)->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumbnailpath);

            echo json_encode([
                'default' => asset('storage/uploads/' . $filenametostore),
                '500' => asset('storage/uploads/thumbnail/' . $filenametostore)
            ]);
        }
    }
}
