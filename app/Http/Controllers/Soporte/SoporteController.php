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
    public function index()
    {
        return view('Soporte.index');
    }

    public function solucion()
    {
        return view('Soporte.solucion');
    }

    public function admin()
    {
        return view('Soporte.admin');
    }

    public function estadisticas()
    {
        $labels = [];
        $califications = [5, 4, 3, 2, 1];
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
        $ticket_especial = [];
        $startDate = null;
        $endDate = null;
        $Ticket_especial = Ticket::where('special','>','00:00:00')->count();
        $prioridad = SoporteTiempo::whereIn('id', [2, 3, 4, 5])->get();
        $namePriority = $prioridad->pluck('priority')->toArray();
        $ticket = Ticket::all();
        $prioritys = [2, 3, 4, 5];

        foreach ($prioritys as $priority) {
            $conteo = Ticket::where('priority_id', $priority)->count();
            $ticketsPriority[] = $conteo;
        }

        $calificaciones = encuesta::all();
        $estrellas = [5, 4, 3, 2, 1];
        $TotalEstrellas = [];

        foreach ($estrellas as $estrella) {
            $conteo = encuesta::where('score', $estrella)->count();
            $TotalEstrellas[] = $conteo;
        }

        $Priority_especial = SoporteTiempo::whereIn('id', [5])->get();
        $prioridad = [5];

        foreach ($prioridad as $especial) {
            $conteo = Ticket::where('priority_id', $prioridad)->count();
            $ticket_especial[] = $conteo;
        }

        $usuarios = User::has('tickets')->get();
        $name = $usuarios->pluck('name')->toArray();
        $totalTicket = [];
        foreach ($usuarios as $usuario) {
            $ticket = Ticket::where('user_id', $usuario->id)->count();
            $totalTicket[] = $ticket;
        }

        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        $usuario = $users->pluck('name')->toArray();

        foreach ($users as $user) {
            $ticket = Ticket::where('support_id', $user->id)->count();
            $soporte[] = $ticket;
        }

        $category = Categoria::all();
        $labels = $category->pluck('name')->toArray();

        foreach ($category as $categoria) {
            $ticketsCount = Ticket::where('status_id', 4)->where('category_id', $categoria->id)->count();
            $values[] = $ticketsCount;
        }
        $values = collect($values)->toArray();

        $tickets = Ticket::where('status_id', 4)->get();

        $ticketsByMonth = $tickets->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('F Y');
        });

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
            'Ticket_especial'
        ));
    }


    public function filterTicket(Request $request)
    {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $califications = [5, 4, 3, 2, 1];
        $labels = [];
        $meses = [];
        $usuario = [];
        $name = [];
        $soporte = [];
        $values = [];
        $ticketsPorMes = [];
        $ticketCounts = [];
        $totalTicket = [];


        $usuarios = User::has('tickets')->get();
        $name = $usuarios->pluck('name')->toArray();
        $totalTicket = [];

        foreach ($usuarios as $usuario) {
            $ticket = Ticket::where('user_id', $usuario->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            $totalTicket[] = $ticket;
        }


        $users = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '=', 'systems')
            ->select('users.*')
            ->get();

        $usuario = $users->pluck('name')->toArray();
        $soporte = [];

        foreach ($users as $user) {
            $ticket = Ticket::where('support_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            $soporte[] = $ticket;
        }

        $category = Categoria::all();
        $labels = $category->pluck('name')->toArray();

        foreach ($category as $categoria) {
            $ticketsCount = Ticket::where('status_id', 4)->where('category_id', $categoria->id)->whereBetween('created_at', [$startDate, $endDate])->count();
            $values[] = $ticketsCount;
        }
        $values = collect($values)->toArray();
        $ticketsQuery = Ticket::where('status_id', 4);

        if ($startDate && $endDate) {
            $ticketsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $tickets = $ticketsQuery->get();

        $ticketsByMonth = $tickets->groupBy(function ($ticket) {
            return Carbon::parse($ticket->created_at)->format('F Y');
        });

        foreach ($ticketsByMonth as $month => $monthTickets) {
            $meses[] = $month;
            $ticketsPorMes[] = $monthTickets->count();
        }

        $prioridad = SoporteTiempo::where('id', '>', 1)->get();
        $namePriority = $prioridad->pluck('priority')->toArray();
        $prioritys = [2, 3, 4, 5];
        foreach ($prioritys as $priority) {
            $conteo = Ticket::where('priority_id', $priority)->whereBetween('created_at', [$startDate, $endDate])->count();
            $ticketsPriority[] = $conteo;
        }

        $calificaciones = encuesta::all();
        $estrellas = [5, 4, 3, 2, 1];
        $TotalEstrellas = [];
        foreach ($estrellas as $estrella) {
            $conteo = encuesta::where('score', $estrella)->whereBetween('created_at', [$startDate, $endDate])->count();
            $TotalEstrellas[] = $conteo;
        }

        $Ticket_especial = Ticket::where('special','>','00:00:00')->whereBetween('created_at', [$startDate, $endDate])->count();
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
            'califications',
            'Ticket_especial'
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

            //para mover el tamaño en el que guarda y muestra la imagen para enviar
            echo json_encode([
                'default' => asset('storage/uploads/' . $filenametostore),
                '700' => asset('storage/uploads/thumbnail/' . $filenametostore)
            ]);
        }
    }
}
