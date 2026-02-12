<?php
/**
 * Debug script to check what data is being fetched for payments page
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/bootloader.php';
require_once APP_ROOT . '/models/M_client.php';

// Simulate logged in client (replace with actual client_id)
$client_id = 41; // Change this to your test client ID

echo "=== DEBUGGING PAYMENTS DATA ===\n\n";

try {
    // Load models
    $clientModel = new M_client();

    echo "1. ACTIVE SITES WITH PACKAGES:\n";
    echo "================================\n";
    $activeSites = $clientModel->getActiveSitesWithPackages($client_id);

    if (empty($activeSites)) {
        echo "No active sites found.\n\n";
    } else {
        foreach ($activeSites as $site) {
            echo "Site: " . $site->site_name . "\n";
            echo "  Package: " . ($site->package_name ?? 'N/A') . "\n";
            echo "  Package Price: LKR " . number_format($site->package_price ?? 0, 2) . "\n";
            echo "  Personnel:\n";
            echo "    - Officers: " . ($site->number_of_officers ?? 0) . "\n";
            echo "    - Supervisors: " . ($site->number_of_supervisors ?? 0) . "\n";
            echo "    - Caretakers: " . ($site->number_of_caretakers ?? 0) . "\n";
            echo "  Pricing per unit:\n";
            echo "    - Officer Price: " . (isset($site->officer_price) ? "LKR " . number_format($site->officer_price, 2) : "NOT SET") . "\n";
            echo "    - Supervisor Price: " . (isset($site->supervisor_price) ? "LKR " . number_format($site->supervisor_price, 2) : "NOT SET") . "\n";
            echo "    - Caretaker Price: " . (isset($site->caretaker_price) ? "LKR " . number_format($site->caretaker_price, 2) : "NOT SET") . "\n";
            
            // Calculate detailed costs
            if (isset($site->officer_price)) {
                $officerCost = ($site->number_of_officers ?? 0) * $site->officer_price;
                $supervisorCost = ($site->number_of_supervisors ?? 0) * ($site->supervisor_price ?? 0);
                $caretakerCost = ($site->number_of_caretakers ?? 0) * ($site->caretaker_price ?? 0);
                $totalCalculated = $officerCost + $supervisorCost + $caretakerCost;
                
                echo "  Calculated Costs:\n";
                echo "    - Officer Cost: LKR " . number_format($officerCost, 2) . "\n";
                echo "    - Supervisor Cost: LKR " . number_format($supervisorCost, 2) . "\n";
                echo "    - Caretaker Cost: LKR " . number_format($caretakerCost, 2) . "\n";
                echo "    - Total Calculated: LKR " . number_format($totalCalculated, 2) . "\n";
                echo "    - Package Price: LKR " . number_format($site->package_price ?? 0, 2) . "\n";
            }
            echo "\n";
        }
    }

    echo "\n2. PENDING PACKAGE REQUESTS:\n";
    echo "===============================\n";
    $pendingRequests = $clientModel->getPendingPackageRequests($client_id);

    if (empty($pendingRequests)) {
        echo "No pending requests found.\n\n";
    } else {
        foreach ($pendingRequests as $request) {
            echo "Request ID: " . $request->id . "\n";
            echo "  Site: " . $request->site_name . "\n";
            echo "  Package: " . $request->package_name . "\n";
            echo "  Package Price: LKR " . number_format($request->package_price ?? 0, 2) . "\n";
            echo "  Personnel:\n";
            echo "    - Officers: " . ($request->number_of_officers ?? 0) . "\n";
            echo "    - Supervisors: " . ($request->number_of_supervisors ?? 0) . "\n";
            echo "    - Caretakers: " . ($request->number_of_caretakers ?? 0) . "\n";
            echo "  Pricing per unit:\n";
            echo "    - Officer Price: " . (isset($request->officer_price) ? "LKR " . number_format($request->officer_price, 2) : "NOT SET") . "\n";
            echo "    - Supervisor Price: " . (isset($request->supervisor_price) ? "LKR " . number_format($request->supervisor_price, 2) : "NOT SET") . "\n";
            echo "    - Caretaker Price: " . (isset($request->caretaker_price) ? "LKR " . number_format($request->caretaker_price, 2) : "NOT SET") . "\n";
            echo "\n";
        }
    }

    echo "\n3. PACKAGES TABLE DATA:\n";
    echo "=========================\n";
    $db = new Database();
    $db->query("SELECT package_name, price_per_officer, price_per_supervisor, price_per_caretaker FROM packages");
    $packages = $db->resultSet();

    foreach ($packages as $package) {
        echo "Package: " . $package->package_name . "\n";
        echo "  Officer Price: " . (isset($package->price_per_officer) ? "LKR " . number_format($package->price_per_officer, 2) : "NOT SET") . "\n";
        echo "  Supervisor Price: " . (isset($package->price_per_supervisor) ? "LKR " . number_format($package->price_per_supervisor, 2) : "NOT SET") . "\n";
        echo "  Caretaker Price: " . (isset($package->price_per_caretaker) ? "LKR " . number_format($package->price_per_caretaker, 2) : "NOT SET") . "\n";
        echo "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== END DEBUG ===\n";
