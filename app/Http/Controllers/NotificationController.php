<?php

namespace App\Http\Controllers;

use App\Mail\GenericNotificationMail;
use App\Models\Classe;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserAnneeScolaire;
use App\Services\OrangeSMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    /**
     * creating new notifications
     */
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|in:sms,email,whatsapp,in_app',
            'target_group' => 'required|in:eleve,enseignant,personnel,all',
            'receiver_ids' => 'nullable|array',
        ], [
            'title.required' => 'Entrez le titre de la notification',
            'message.required' => 'Entrez le message de la notification',
            'type.required' => 'Selectionnez un type de notification',
            'target_group.required' => 'Selectionnez le groupe cible'
        ]);
        try {
            $notification = Notification::create([
                'title' => $request->title,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'type' => $request->type,
                'target_group' => $request->target_group,
                'receivers' => $request->has('send_to_all') ? [] : $request->receiver_ids,
                'is_mass' => $request->has('send_to_all'),
                'sent_at' => now(),
            ]);
            $result = $this->dispatchNotification($notification);
            return response()->json([
                $result["type"] => $result["message"]
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erreur lors de la sauvegarde : '.$th->getMessage()]);
        }
    }
    /**
     * function do dispatch type notifications and for who
     */
    private function dispatchNotification(Notification $notification)
    {
        $users = collect();
        if ($notification->is_mass) {
            // Envoi groupé selon le type
            switch ($notification->target_group) {
                case 'students':
                    $users = User::where('typeUser', 'eleve')->get();
                    break;
                case 'teachers':
                    $users = User::where('typeUser', 'enseignant')->get();
                    break;
                case 'personnel':
                    $users = User::where('typeUser', 'personnel')->get()->get();
                    break;
                case 'all':
                    $users = User::all();
                    break;
            }
        } else {
            $users = User::whereIn('id', $notification->receivers)->get();
        }
        foreach ($users as $user) {
            match($notification->type) {
                'in_app' => $user->notify(
                    new \App\Notifications\InAppNotification(
                        $notification->title, $notification->message
                    )
                ),
                'email' => $this->sendEmailNotification($user, $notification),
                'sms' => $this->sendSMS($user->phone, $notification->message),
                'whatsapp' => $this->sendWhatsApp($user->phone, $notification->message),
            };
        }
        // Mise à jour de status si besoin (pour tracking plus tard)
    }
    /**
     * send mail
     */
    private function sendEmailNotification(User $user, Notification $notification) {
        try {
            Mail::to($user->email)->send(
                new GenericNotificationMail(
                    $notification->title,
                    $notification->message
                )
            );
            Notification::find($notification->id)->update([
                'status' => 'envoyé',
                'sent_at' => now()
            ]);
            return [
                "type" => "success",
                "message" => "Email(s) envoyé(s) avec succès!"
            ];
        } catch (\Exception $ex) {
            return [
                "type" => "error",
                "message" => "Erreur lors de l'envoi du mail : ".$ex->getMessage()
            ];
        }
    }

    /**
     * send sms
     */
    private function sendSMS($phone, $message)
    {
        $orangeService = app(OrangeSMSService::class);
        return $orangeService->sendSMS($phone, $message);
    }
    /**
     * send whatsapp
     */
    private function sendWhatsApp($phone, $message)
    {
        // Intégration API WhatsApp (ex: Twilio, Meta Cloud API)
    }
    /**
     * all notifications
     */
    public function create(Request $request) {
        $query = Notification::query();
        $userYearIds = UserAnneeScolaire::all()->where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('user_id');
        $users = User::all()->whereIn('id', $userYearIds);
        // filter vars
        $searchNotification = $request->input('searchNotification');
        $typeFilter = $request->input('typeFilter');
        $groupFilter = $request->input('groupFilter');
        if(!empty($searchNotification)) {
            $query->where('title', 'LIKE', "%{$searchNotification}%")
                ->orWhere('message', 'LIKE', "%{$searchNotification}%");
        }
        if(!empty($groupFilter)) {
            $query->where('target_group', $groupFilter);
        }
        if (!empty($typeFilter)) {
            $query->where('type', $groupFilter);
        }
        $notifications = $query->latest()->paginate(10);
        if($request->ajax()) {
            return view('partials._notifications_table', compact('notifications'));
        } else {
            return view('notifications.notifications', compact('notifications', 'users'));
        }
    }

    /**
     * when the notification is open update it before show to the user
     */
    public function view($id) {
        $notif = Notification::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();
        //$notif->update(['read_at' => now()]);
        return view('notifications.show', compact('notif'));
    }

    /**
     * sending notifications
     */
    public function index(Request $request) {
        $user = User::findOrFail(Auth::id());
        return view('notifications.show', compact('user'));
    }

    /**
     * getting user by group [teachers, students, personnel, all]
     */
    public function getUsers($type) {
        $userYearIds = UserAnneeScolaire::all()->where('annee_scolaire_id', getCurrentYear()->id)
            ->pluck('user_id');
        if($type !== "all") {
            $users = User::where('typeUser', $type)->whereIn('id', $userYearIds)->get();
        } else {
            $users = User::whereIn('id', $userYearIds)->get();
        }
        return response()->json($users);
    }

    /**
     * function to controlate who send notifications
     */
    public function controles() {
        return view('notifications.controles');
    }

    /**
     * destroy notification
     */
    public function destroy($id) {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            return redirect()->route('notification.create')->with('deleteSuccess', 'Notification suprimée avec succès!');
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'Erreur lors de la supression : '.$ex->getMessage()
            ]);
        }
    }
}
