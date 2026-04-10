<?php

namespace App\Http\Controllers\ApiControllers;

use CustomCrypt;
use App\Models\User;
use App\Mail\NewPassMail;
use App\Mail\NewUserMail;
use App\Models\Provincia;
use App\Models\UserMetodoPago;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\User\UpdateUser;
use App\Models\GestorDocumental;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Users\UserRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\DraggableList;
use App\Helpers\GestorHelper;
use App\Traits\Files\HandlerFiles;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    protected function pathServer()
    {
        $PATH = $_SERVER['DOCUMENT_ROOT'];
        $pathPublicOut = explode('public', $PATH);
        $res = $pathPublicOut[0];
        return $res;
    }

    public function getUsuarios(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage ?? 15;
        $page = $request->page ?? 1;
        $sortBy = $request->sortBy ?? 'created_at';
        $orderBy = $request->orderBy ?? 'DESC';
        $search = $request->search ?? '';

        $query = User::orderBy('created_at', 'DESC');

        // Filtrar por role si se especifica
        if ($request->has('role')) {
            $roles = is_array($request->role) ? $request->role : [$request->role];
            $query->whereIn('role', $roles);
        }


        if ($search != '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('nombre_fiscal', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('telefono', 'LIKE', '%' . $search . '%');
            });
        }

        $total = $query->count();

        if ($itemsPerPage != -1) {
            $user = $query->paginate($itemsPerPage);
            $data = $user->values();
        } else {
            $data = $query->get();
        }

        $response = [
            'data' => $data,
            'total' => $total,
        ];

        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'users' => $response,
        ]);
    }

    public function getUsuarioByid(Request $request, $user_id = null)
    {
        // Si no viene user_id en la ruta, obtenerlo del helper (cliente_id si es gestor, o user_id del usuario autenticado)
        // Si viene user_id en la ruta, usar el helper para validar y obtener el correcto
        $effectiveUserId = GestorHelper::getUserId($request, $user_id);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $user = User::where('id', $effectiveUserId)->first();

        if (!$user->metodos_pago()->exists()) {
            $this->createMetodoPago($user);
        }

        $provincias = Provincia::orderBy('nombre')->get(['id', 'nombre']);
        $user->avatar = Storage::url($user->avatar);

        // Cargar metodos_pago
        $user->load('metodos_pago');

        // Obtener IDs de asociaciones según el rol y agregarlos al array del usuario
        $userArray = $user->toArray();

        if ($user->role == 2) {
            // Si es cliente, obtener IDs de gestores asociados
            $userArray['gestores_ids'] = $user->gestoresAsociados()->get()->pluck('id')->toArray();
        } elseif ($user->role == 3 || $user->role == 4) {
            // Si es gestor o empleado, obtener IDs de clientes asociados
            $userArray['clientes_ids'] = $user->clientesAsociados()->get()->pluck('id')->toArray();
        }

        return response()->json([
            'status' => 200,
            'message' => 'Usuario Actualizado',
            'user' => $userArray,
            'provincias' => $provincias
        ]);
    }

    public function getMetodosPago(Request $request)
    {
        $metodos_pago = $request->user()->metodos_pago;
        return response()->json($metodos_pago, 200);
    }

    private function createMetodoPago(User $user)
    {
        $user->metodos_pago()->create([
            'pago_uno' => NULL,
            'pago_uno_activo' => false,
            'pago_dos' => '0',
            'pago_dos_activo' => false,
            'pago_tres' => '0€',
            'pago_tres_activo' => false,
            'pago_cuatro' => '',
            'pago_cuatro_activo' => false,
            'pago_cinco' => '',
            'pago_cinco_activo' => false,
            'predeterminado' => ''
        ]);
    }

    public function getEmailUsuario($user_id)
    {
        $user = User::find($user_id);
        return response()->json($user->email, 200);
    }

    public function saveUsuario(UserRequest $request)
    {
        try {
            $usuario = json_decode($request->usuario, true); // Decodificar como array para mejor manejo

            // Verificar si es un nuevo usuario o una actualización
            $id = $usuario['id'] ?? null;

            // Determinar si es un nuevo usuario
            // Es nuevo si: id es null, vacío, 0, o no es un número válido
            $isNewUser = true;
            if ($id !== null && $id !== '' && $id !== 'undefined' && $id !== 'null') {
                // Intentar convertir a entero
                $idInt = filter_var($id, FILTER_VALIDATE_INT);
                if ($idInt !== false && $idInt > 0) {
                    $isNewUser = false;
                    $id = $idInt;
                }
            }

            if ($isNewUser) {
                $user = new User();
            } else {
                // Solo buscar si tenemos un ID válido
                if ($id > 0) {
                    $user = User::findOrFail($id);
                } else {
                    $user = new User();
                }
            }

            // Convertir de array a objeto para compatibilidad con el resto del código
            $usuario = (object) $usuario;

            $user->name = $usuario->name;
            $user->role = $usuario->role;

            // Para empresa (role 2) nueva: email temporal único (dominio interno) hasta asignar el definitivo
            if ($usuario->role == 2 && $isNewUser) {
                $user->email = 'temp-' . uniqid() . '@fidifactu.com'; // temporal único para cumplir unique antes del save
            } else {
                $user->email = $usuario->email;
            }

            // Solo asignar campos adicionales si es cliente (role 2)
            if ($usuario->role == 2) {
                $user->provincia_id = $usuario->provincia_id ?? null;
                $user->nombre_fiscal = $usuario->nombre_fiscal ?? null;
                $user->cif = $usuario->cif ?? null;
                $user->telefono = $usuario->telefono ?? null;
                $user->direccion = $usuario->direccion ?? null;
                $user->ciudad = $usuario->ciudad ?? null;
                $user->cuenta = $usuario->cuenta ?? '00000000000000000000';
                $user->postal_code = $usuario->postal_code ?? null;
                $user->has_electronic_billing = $usuario->has_electronic_billing ?? true;
                $user->email_comercial = $usuario->email_comercial ?? null;
            } else {
                // Para admin y gestor, usar valores por defecto
                // Obtener la primera provincia disponible como valor por defecto
                $defaultProvincia = Provincia::first();
                $user->provincia_id = $defaultProvincia ? $defaultProvincia->id : 1;
                $user->nombre_fiscal = null;
                $user->cif = null;
                $user->telefono = 0; // Campo numérico, usar 0 en lugar de cadena vacía
                $user->direccion = null;
                $user->ciudad = null;
                $user->cuenta = '00000000000000000000';
                $user->postal_code = null;
                $user->has_electronic_billing = false;
            }

            // Solo generar nueva contraseña para usuarios nuevos
            $password = null;
            if ($isNewUser) {
                $password = Str::random(10);
                $user->password = bcrypt($password);
            }

            $user->saveOrFail();

            // Para empresa (role 2) nueva: asignar email definitivo id@dominio-interno
            if ($usuario->role == 2 && $isNewUser) {
                $user->email = $user->id . '@fidifactu.com';
                $user->save();
            }

            // Guardar asociaciones gestor-clientes
            if (isset($usuario->gestores_ids) && $usuario->role == 2) {
                // Si es cliente, asociar gestores
                $user->gestoresAsociados()->sync($usuario->gestores_ids);
            } elseif (isset($usuario->clientes_ids) && ($usuario->role == 3 || $usuario->role == 4)) {
                // Si es gestor o empleado, asociar clientes
                $user->clientesAsociados()->sync($usuario->clientes_ids);
            }

            $this->crearCarpetasPrinciales($user->id);

            $email = $user->email;

            // Enviar email solo si es un usuario nuevo y no es cliente. Recordando que cliente es la empresa
            if ($isNewUser && $usuario->role != 2 && $password !== null) {
                try {
                    Mail::to($email)->send(new NewUserMail($user, $password));
                } catch (\Throwable $e) {
                    Log::error('Error enviando email de acceso al nuevo usuario', [
                        'user_id' => $user->id,
                        'email' => $email,
                        'error' => $e->getMessage(),
                    ]);
                    return response()->json([
                        'user' => $user,
                        'message' => 'Usuario creado correctamente, pero no se pudo enviar el email de acceso. Compruebe la configuración de correo (MAIL_* en .env).',
                        'error' => config('app.debug') ? $e->getMessage() : null,
                    ], 200);
                }
            }

            $destination = storage_path("/app/public/users/userId_{$user->id}/");

            $store = HandlerFiles::store($request,  $destination);

            if (count($store->original['nombresArchivos']) > 0) {
                $imagen = $store->original['nombresArchivos'][0];
                $imagenSave = User::findOrFail($user->id);
                $imagenSave->avatar = $imagen;
                $imagenSave->update();
            } else {
                /*$from = 'C:\\xampp\htdocs\copy\images\*.*';
            $to = 'C:\\xampp\htdocs\copy\copy-images';
            exec('copy '.$from.' '.$to.' /Y');
            $imagenSave = User::findOrFail($user->id);
            $imagenSave->avatar = $imagen;
            $imagenSave->update();*/
            }
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'message' => 'Error al crear el usuario'], 400);
        }
    }

    private function updateMetodosPago(User $user, $metodos_pago)
    {
        $user->metodos_pago()->updateOrCreate(['user_id' => $user['id']], $metodos_pago);
    }

    /**
     * Resetea la contraseña de un usuario y envía el nuevo acceso por email.
     * Solo disponible para administradores (role 1). Aplica a Administrador (1), Gestor (3) y Empleado (4). No a Cliente/Empresa (2).
     */
    public function resetEmployeePassword(Request $request, $usuario_id = null)
    {
        $authUser = Auth::user();

        if ($authUser->role != 1) {
            return response()->json(['error' => 'No tiene permiso para realizar esta acción. Solo administradores.'], 403);
        }

        $targetUserId = filter_var($usuario_id, FILTER_VALIDATE_INT);
        if ($targetUserId === false || $targetUserId <= 0) {
            return response()->json(['error' => 'ID de usuario inválido.'], 400);
        }

        $targetUser = User::find($targetUserId);
        if (!$targetUser) {
            return response()->json(['error' => 'Usuario no encontrado.'], 404);
        }

        $allowedTargetRoles = [1, 3, 4]; // Administrador, Gestor, Empleado
        if (!in_array((int) $targetUser->role, $allowedTargetRoles)) {
            return response()->json(['error' => 'Solo se puede resetear la contraseña de usuarios con perfil Administrador, Gestor o Empleado.'], 403);
        }

        try {
            $password = Str::random(10);
            $targetUser->password = bcrypt($password);
            $targetUser->update();
            Mail::to($targetUser->email)->send(new NewPassMail($targetUser, $password));
            return response()->json([
                'message' => 'Contraseña restablecida correctamente. Se ha enviado un email al usuario con las nuevas credenciales.',
                'user' => $targetUser->only(['id', 'name', 'email']),
            ], 200);
        } catch (\Throwable $th) {
            Log::error('Error al resetear contraseña de usuario', [
                'target_user_id' => $targetUser->id,
                'auth_user_id' => $authUser->id,
                'error' => $th->getMessage(),
            ]);
            return response()->json([
                'error' => 'Error al enviar el email con la nueva contraseña. Compruebe la configuración de correo.',
                'detail' => config('app.debug') ? $th->getMessage() : null,
            ], 500);
        }
    }

    public function updateUsuario(UserUpdateRequest $request, $id = null)
    {
        // Si no viene id en la ruta, obtenerlo del helper (cliente_id si es gestor, o user_id del usuario autenticado)
        // Si viene id en la ruta, usar el helper para validar y obtener el correcto
        $effectiveUserId = GestorHelper::getUserId($request, $id);

        if (!$effectiveUserId || $effectiveUserId === 'null' || $effectiveUserId === 'undefined') {
            return response()->json(['error' => 'No tiene acceso a este recurso o ID inválido'], 403);
        }

        // Asegurarse de que el ID sea numérico
        $effectiveUserId = filter_var($effectiveUserId, FILTER_VALIDATE_INT);
        if ($effectiveUserId === false || $effectiveUserId <= 0) {
            return response()->json(['error' => 'ID de usuario inválido'], 400);
        }

        $usuario = json_decode($request->usuario, true);

        if ($request->isMethod("POST")) {
            // Usar el effectiveUserId en lugar del id de la ruta
            $user = User::findOrFail($effectiveUserId);
            $user->name = $usuario['name'];
            $user->email = $usuario['email'];
            $user->role = $usuario['role'];

            // Solo actualizar campos adicionales si es cliente (role 2)
            if ($usuario['role'] == 2) {
                $user->provincia_id = $usuario['provincia_id'] ?? null;
                $user->nombre_fiscal = $usuario['nombre_fiscal'] ?? null;
                $user->cif = $usuario['cif'] ?? null;
                $user->telefono = $usuario['telefono'] ?? null;
                $user->direccion = $usuario['direccion'] ?? null;
                $user->ciudad = $usuario['ciudad'] ?? null;
                $user->cuenta = $usuario['cuenta'] ?? '00000000000000000000';
                $user->postal_code = $usuario['postal_code'] ?? null;
                $user->has_electronic_billing = $usuario['has_electronic_billing'] ?? true;
                $user->email_comercial = $usuario['email_comercial'] ?? null;
            } else {
                // Para admin y gestor, usar valores por defecto
                // Obtener la primera provincia disponible como valor por defecto
                $defaultProvincia = Provincia::first();
                $user->provincia_id = $defaultProvincia ? $defaultProvincia->id : 1;
                $user->nombre_fiscal = null;
                $user->cif = null;
                $user->telefono = 0; // Campo numérico, usar 0 en lugar de cadena vacía
                $user->direccion = null;
                $user->ciudad = null;
                $user->cuenta = '00000000000000000000';
                $user->postal_code = null;
                $user->has_electronic_billing = false;
            }

            $user->update();

            // Actualizar asociaciones gestor-clientes
            if (isset($usuario['gestores_ids']) && $usuario['role'] == 2) {
                // Si es cliente, actualizar gestores asociados
                $user->gestoresAsociados()->sync($usuario['gestores_ids']);
            } elseif (isset($usuario['clientes_ids']) && ($usuario['role'] == 3 || $usuario['role'] == 4)) {
                // Si es gestor o empleado, actualizar clientes asociados
                $user->clientesAsociados()->sync($usuario['clientes_ids']);
            }

            if (isset($usuario['metodos_pago'])) {
                $metodos_pago = $usuario['metodos_pago'];
                $this->updateMetodosPago($user, $metodos_pago);
            }

            //update user mail
            $email = $usuario['email'];

            if (!isset($request->existeDatosEmpres)) {
                return 1;
                Mail::to($email)->queue(new UpdateUser($user));
            }

            if ($request->hasFile('imagen')) {
                foreach ($request->file('imagen') as $image) {
                    $destination = "public/users/userId_{$user->id}/";
                    $avatarName =  uniqid('avatar_') . '.' . $image->getClientOriginalExtension();
                    $image->storeAs($destination, $avatarName);
                    $user->avatar = $destination . $avatarName;
                    $user->save();
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Usuario Actualizado',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Método no permitido',
            ]);
        }
    }

    public function deleteUsuario($user_id)
    {
        try {
            $user = User::find($user_id);

            $user->systemParams()->delete();
            $user->recibos()->delete();
            $user->series()->delete();

            // Eliminar carpetas y archivos del usuario en storage
            $paths = [
                'public/documentos/userId_' . $user_id,
                'public/users/userId_' . $user_id,
                'public/recibos/userId_' . $user_id,
                'public/lotes/userId_' . $user_id,
                'public/albaranes/enviados/userId_' . $user_id,
                'public/albaranes/recibidos/userId_' . $user_id,
            ];
            foreach ($paths as $path) {
                try {
                    \Illuminate\Support\Facades\Storage::deleteDirectory($path);
                } catch (\Throwable $t) {
                    // continuar con las demás rutas
                }
            }

            $user->delete();

            return response()->json('usuario eliminado con éxito', 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Error eliminando usuario',
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 400);
        }
    }

    //datos que se necesitaran en el formulario
    public function getMethodsForm()
    {
        $provincias = Provincia::orderBy('nombre')->get(['id', 'nombre']);

        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'provincias' => $provincias,
        ]);
    }
    public function crearCarpetasPrinciales($user_id)
    {

        $principal = ["documentacion_general", "factura", "factura_recibidas"];

        foreach ($principal as $value) {
            $path = 'public/documentos/userId_' . $user_id . '/' . $value;
            Storage::makeDirectory($path);
            @chmod(storage_path('app/' . $path), 0775);
        }

        $paths = [
            'public/users/userId_' . $user_id,
            'public/recibos/userId_' . $user_id,
            'public/lotes/userId_' . $user_id,
            'public/albaranes/enviados/userId_' . $user_id,
            'public/albaranes/recibidos/userId_' . $user_id,
        ];
        foreach ($paths as $p) {
            Storage::makeDirectory($p);
            @chmod(storage_path('app/' . $p), 0775);
        }
    }

    public function crearDragPrincipales($user_id)
    {

        $status = ['Pendientes', 'En Progreso', 'Finalizadas'];

        foreach ($status as $value) {
            $gD = new  DraggableList;
            $gD->user_id = $user_id;
            $gD->drag = $value;
            $gD->newTask = false;
            $gD->save();
        }
    }

    // Obtener lista de gestores (para asociar a clientes)
    public function getGestores()
    {
        $gestores = User::where('role', 3)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'gestores' => $gestores,
        ]);
    }

    // Obtener lista de clientes (para asociar a gestores)
    public function getClientes()
    {
        $clientes = User::where('role', 2)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'clientes' => $clientes,
        ]);
    }
}
