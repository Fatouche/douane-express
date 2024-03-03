<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderServiceController extends Controller
{
    public function addServiceToOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $service = Service::findOrFail($request->serviceId);

        $totalPrice =  $service->price;
        $order->services()->attach($service->id, [
            'total_quantity' => 0,
            'total_price' => $totalPrice
        ]);

        return response()->json(['message' => 'Service ajouté avec succès à la commande.'], 200);
    }

    public function updateServiceToOrder(Request $request, string $orderId, string $serviceId)
    {
        $service = Service::findOrFail($serviceId);

        // Appel récursif pour accumuler les quantités des sous-services
        $quantities = $this->accumulateQuantities($service, $request->quantity, $request->quantity);

        // Récupérer tous les services existants pour la commande en cours
        $existingOrderServices = DB::table('order_service')
            ->where('order_id', $orderId)
            ->get()
            ->keyBy('service_id');

        // Enregistrer dans la table pivot avec les quantités accumulées
        foreach ($quantities as $subServiceId => $subServiceQuantity) {
            $totalPrice = (float)Service::findOrFail($subServiceId)->price * $subServiceQuantity;
            // Vérifier si le service existe déjà dans la commande
            if (isset($existingOrderServices[$subServiceId])) {
                // Mettre à jour la quantité et le prix total dans la table pivot
                DB::table('order_service')
                    ->where('order_id', $orderId)
                    ->where('service_id', $subServiceId)
                    ->update([
                        'total_quantity' => $subServiceQuantity,
                        'total_price' => $totalPrice,
                    ]);
                // Supprimer ce service de la liste des services existants
                unset($existingOrderServices[$subServiceId]);
            } else {
                // Créer une nouvelle entrée dans la table pivot
                DB::table('order_service')->insert([
                    'order_id' => $orderId,
                    'service_id' => $subServiceId,
                    'total_quantity' => $subServiceQuantity,
                    'total_price' => $totalPrice,
                ]);
            }
        }
        return response()->json(['message' => 'mofification réussie.'], 200);
    }

    private function accumulateQuantities($service, $requestedQuantity, $defaultQuantity, $alreadyAssignedQuantities = [])
    {
        // Initialiser les quantités accumulées
        $accumulatedQuantities = [];

        // Récupérer les sous-services associés à ce service
        $subServices = Service::where('serviceId', $service->id)->get();

        if ($subServices->isEmpty()) {
            $accumulatedQuantities[$service->id] = $defaultQuantity;
        };

        // Parcourir les sous-services pour calculer les quantités
        foreach ($subServices as $subService) {
            if ($subService->parentServices->isEmpty()) {
                $totalAssignedQuantity = $defaultQuantity - array_sum($alreadyAssignedQuantities);
                if ($totalAssignedQuantity <= 0) {
                    return $accumulatedQuantities;
                }
                $accumulatedQuantities[$subService->id] = $totalAssignedQuantity;
                return $accumulatedQuantities;
            }

            if (!isset($alreadyAssignedQuantities[$subService->id])) {
                $alreadyAssignedQuantities[$subService->id] = 0;
            }
            // Calculer la quantité maximale pouvant être attribuée à ce sous-service en tenant compte de la quantité déjà attribuée
            $maxQuantityForSubService = $subService->maxQuantity - ($alreadyAssignedQuantities[$subService->id]);

            // Déterminer la quantité à attribuer pour ce sous-service
            $subServiceQuantity = min($requestedQuantity, $maxQuantityForSubService);

            // Vérifier si la clé existe dans le tableau avant de l'utiliser
            if (!isset($accumulatedQuantities[$subService->id])) {
                $accumulatedQuantities[$subService->id] = 0;
            }

            // Ajouter la quantité au sous-service
            $accumulatedQuantities[$subService->id]++;

            // Appel récursif pour les sous-services associés
            if ($subService->parentServices->isNotEmpty()) {
                // Mettre à jour la quantité déjà attribuée pour le sous-service actuel
                $alreadyAssignedQuantities[$subService->id] += $subServiceQuantity;

                // Appeler récursivement accumulateQuantities avec la quantité déjà attribuée mise à jour
                $subServiceQuantities = $this->accumulateQuantities($subService, $subServiceQuantity, $defaultQuantity, $alreadyAssignedQuantities);

                // Fusionner les quantités des sous-services enfants avec les quantités accumulées
                foreach ($subServiceQuantities as $subServiceId => $quantity) {
                    if (isset($accumulatedQuantities[$subServiceId])) {
                        $accumulatedQuantities[$subServiceId] += $quantity;
                    } else {
                        $accumulatedQuantities[$subServiceId] = $quantity;
                    }
                }
            }
        }

        return $accumulatedQuantities;
    }

    public function deleteServiceToOrder(string $orderId, string $serviceId)
    {
        $order = Order::findOrFail($orderId);
        $service = Service::findOrFail($serviceId);
        $descendants = $service->getAllDescendants();
        $servicesIds = $descendants->pluck('id')->toArray();
        $servicesIds[] = $serviceId;

        $order->services()->detach($servicesIds);


        return response()->json([], 200);
    }
}
