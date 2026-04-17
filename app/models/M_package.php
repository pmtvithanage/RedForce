<?php
class M_package {
    private $db;
    private const DEFAULT_CUSTOM_PRICE_PER_OFFICER = 20000.00;
    private const DEFAULT_CUSTOM_PRICE_PER_SUPERVISOR = 20000.00;
    private const DEFAULT_CUSTOM_PRICE_PER_CARETAKER = 12000.00;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Get all packages
     */
    public function getAllPackages() {
        $this->db->query("SELECT
                            id,
                            package_name,
                            description,
                            number_of_officers,
                            number_of_supervisors,
                            number_of_caretakers,
                            package_price,
                            background_image,
                            status,
                            created_by,
                            created_at,
                            updated_at,
                            is_default,
                            CASE
                                WHEN package_name = 'Custom Package' AND COALESCE(price_per_officer, 0) <= 0 THEN " . self::DEFAULT_CUSTOM_PRICE_PER_OFFICER . "
                                ELSE COALESCE(price_per_officer, 0)
                            END AS price_per_officer,
                            CASE
                                WHEN package_name = 'Custom Package' AND COALESCE(price_per_supervisor, 0) <= 0 THEN " . self::DEFAULT_CUSTOM_PRICE_PER_SUPERVISOR . "
                                ELSE COALESCE(price_per_supervisor, 0)
                            END AS price_per_supervisor,
                            CASE
                                WHEN package_name = 'Custom Package' AND COALESCE(price_per_caretaker, 0) <= 0 THEN " . self::DEFAULT_CUSTOM_PRICE_PER_CARETAKER . "
                                ELSE COALESCE(price_per_caretaker, 0)
                            END AS price_per_caretaker
                         FROM packages
                         WHERE status = :status
                         ORDER BY created_at DESC");
        $this->db->bind(':status', 'Active');
        
        return $this->db->resultSet();
    }

    /**
     * Get all packages including inactive ones (for admin)
     * Custom Package first, then default packages, then others
     */
    public function getAllPackagesForAdmin() {
        $this->db->query("SELECT
                            id,
                            package_name,
                            description,
                            number_of_officers,
                            number_of_supervisors,
                            number_of_caretakers,
                            package_price,
                            background_image,
                            status,
                            created_by,
                            created_at,
                            updated_at,
                            is_default,
                            CASE
                                WHEN package_name = 'Custom Package' AND COALESCE(price_per_officer, 0) <= 0 THEN " . self::DEFAULT_CUSTOM_PRICE_PER_OFFICER . "
                                ELSE COALESCE(price_per_officer, 0)
                            END AS price_per_officer,
                            CASE
                                WHEN package_name = 'Custom Package' AND COALESCE(price_per_supervisor, 0) <= 0 THEN " . self::DEFAULT_CUSTOM_PRICE_PER_SUPERVISOR . "
                                ELSE COALESCE(price_per_supervisor, 0)
                            END AS price_per_supervisor,
                            CASE
                                WHEN package_name = 'Custom Package' AND COALESCE(price_per_caretaker, 0) <= 0 THEN " . self::DEFAULT_CUSTOM_PRICE_PER_CARETAKER . "
                                ELSE COALESCE(price_per_caretaker, 0)
                            END AS price_per_caretaker
                         FROM packages 
                         ORDER BY 
                         CASE WHEN package_name = 'Custom Package' THEN 0 ELSE 1 END,
                         is_default DESC, 
                         created_at DESC");
        
        return $this->db->resultSet();
    }

    /**
     * Get package by ID
     */
    public function getPackageById($id) {
        $this->db->query('SELECT * FROM packages WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    /**
     * Get package by name
     */
    public function getPackageByName($name) {
        $this->db->query('SELECT * FROM packages WHERE package_name = :name');
        $this->db->bind(':name', $name);
        
        return $this->db->single();
    }

    /**
     * Create new package
     */
    public function createPackage($data) {
        $this->db->query('INSERT INTO packages (
            package_name, 
            description, 
            number_of_officers, 
            number_of_supervisors, 
            number_of_caretakers, 
            package_price, 
            price_per_officer, 
            price_per_supervisor, 
            price_per_caretaker, 
            background_image, 
            status, 
            created_by
        ) VALUES (
            :package_name, 
            :description, 
            :number_of_officers, 
            :number_of_supervisors, 
            :number_of_caretakers, 
            :package_price, 
            :price_per_officer, 
            :price_per_supervisor, 
            :price_per_caretaker, 
            :background_image, 
            :status, 
            :created_by
        )');

        // Bind values
        $this->db->bind(':package_name', $data['package_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':number_of_officers', $data['number_of_officers']);
        $this->db->bind(':number_of_supervisors', $data['number_of_supervisors']);
        $this->db->bind(':number_of_caretakers', $data['number_of_caretakers']);
        $this->db->bind(':package_price', $data['package_price']);
        $this->db->bind(':price_per_officer', $data['price_per_officer']);
        $this->db->bind(':price_per_supervisor', $data['price_per_supervisor']);
        $this->db->bind(':price_per_caretaker', $data['price_per_caretaker']);
        $this->db->bind(':background_image', $data['background_image']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':created_by', $data['created_by']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update package
     */
    public function updatePackage($data) {
        $this->db->query('UPDATE packages SET 
            package_name = :package_name,
            description = :description,
            number_of_officers = :number_of_officers,
            number_of_supervisors = :number_of_supervisors,
            number_of_caretakers = :number_of_caretakers,
            package_price = :package_price,
            price_per_officer = :price_per_officer,
            price_per_supervisor = :price_per_supervisor,
            price_per_caretaker = :price_per_caretaker,
            background_image = :background_image,
            status = :status
            WHERE id = :id
        ');

        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':package_name', $data['package_name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':number_of_officers', $data['number_of_officers']);
        $this->db->bind(':number_of_supervisors', $data['number_of_supervisors']);
        $this->db->bind(':number_of_caretakers', $data['number_of_caretakers']);
        $this->db->bind(':package_price', $data['package_price']);
        $this->db->bind(':price_per_officer', $data['price_per_officer']);
        $this->db->bind(':price_per_supervisor', $data['price_per_supervisor']);
        $this->db->bind(':price_per_caretaker', $data['price_per_caretaker']);
        $this->db->bind(':background_image', $data['background_image']);
        $this->db->bind(':status', $data['status']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete package
     */
    public function deletePackage($id) {
        $this->db->query('DELETE FROM packages WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update package status (Active/Inactive)
     */
    public function updatePackageStatus($id, $status) {
        $this->db->query('UPDATE packages SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Toggle package status
     */
    public function toggleStatus($id, $status) {
        $this->db->query('UPDATE packages SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if package name exists (excluding specific ID for updates)
     */
    public function packageNameExists($name, $excludeId = null) {
        if ($excludeId) {
            $this->db->query('SELECT id FROM packages WHERE package_name = :name AND id != :id');
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->query('SELECT id FROM packages WHERE package_name = :name');
        }
        
        $this->db->bind(':name', $name);
        
        if ($this->db->single()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Custom Package pricing (unit prices for officers, supervisors, caretakers)
     */
    public function getCustomPackagePricing() {
        $this->db->query('SELECT price_per_officer, price_per_supervisor, price_per_caretaker 
                         FROM packages 
                         WHERE package_name = :name');
        $this->db->bind(':name', 'Custom Package');
        
        $result = $this->db->single();
        
        if ($result) {
            return $this->normalizeCustomPricing([
                'price_per_officer' => floatval($result->price_per_officer),
                'price_per_supervisor' => floatval($result->price_per_supervisor),
                'price_per_caretaker' => floatval($result->price_per_caretaker)
            ]);
        }
        
        return $this->defaultCustomPricing();
    }

    /**
     * Get unit pricing for a package; fallback to Custom Package pricing when missing.
     */
    public function getPackagePricingByName($packageName) {
        $this->db->query('SELECT price_per_officer, price_per_supervisor, price_per_caretaker FROM packages WHERE package_name = :name LIMIT 1');
        $this->db->bind(':name', $packageName);
        $row = $this->db->single();

        if ($row) {
            $pricing = [
                'price_per_officer' => floatval($row->price_per_officer ?? 0),
                'price_per_supervisor' => floatval($row->price_per_supervisor ?? 0),
                'price_per_caretaker' => floatval($row->price_per_caretaker ?? 0)
            ];

            if (strcasecmp((string)$packageName, 'Custom Package') === 0) {
                return $this->normalizeCustomPricing($pricing);
            }

            return $pricing;
        }

        return $this->getCustomPackagePricing();
    }

    private function defaultCustomPricing() {
        return [
            'price_per_officer' => self::DEFAULT_CUSTOM_PRICE_PER_OFFICER,
            'price_per_supervisor' => self::DEFAULT_CUSTOM_PRICE_PER_SUPERVISOR,
            'price_per_caretaker' => self::DEFAULT_CUSTOM_PRICE_PER_CARETAKER
        ];
    }

    private function normalizeCustomPricing(array $pricing) {
        $defaults = $this->defaultCustomPricing();

        if (($pricing['price_per_officer'] ?? 0) <= 0) {
            $pricing['price_per_officer'] = $defaults['price_per_officer'];
        }
        if (($pricing['price_per_supervisor'] ?? 0) <= 0) {
            $pricing['price_per_supervisor'] = $defaults['price_per_supervisor'];
        }
        if (($pricing['price_per_caretaker'] ?? 0) <= 0) {
            $pricing['price_per_caretaker'] = $defaults['price_per_caretaker'];
        }

        return $pricing;
    }

    /**
     * Calculate package price based on Custom Package unit prices
     */
    public function calculatePackagePrice($numOfficers, $numSupervisors, $numCaretakers) {
        $customPricing = $this->getCustomPackagePricing();
        
        $totalPrice = ($numOfficers * $customPricing['price_per_officer']) +
                     ($numSupervisors * $customPricing['price_per_supervisor']) +
                     ($numCaretakers * $customPricing['price_per_caretaker']);
        
        return round($totalPrice, 2);
    }

    /**
     * Update all package prices based on Custom Package unit prices
     * Called when Custom Package pricing is updated to recalculate all existing packages
     */
    public function updateAllPackagePrices() {
        // Get Custom Package pricing
        $customPricing = $this->getCustomPackagePricing();
        
        // Update all non-custom packages with calculated prices
        $this->db->query('
            UPDATE packages 
            SET package_price = (
                number_of_officers * :price_per_officer +
                number_of_supervisors * :price_per_supervisor +
                number_of_caretakers * :price_per_caretaker
            ),
            price_per_officer = :price_per_officer,
            price_per_supervisor = :price_per_supervisor,
            price_per_caretaker = :price_per_caretaker
            WHERE package_name != :custom_package_name
        ');
        
        $this->db->bind(':price_per_officer', $customPricing['price_per_officer']);
        $this->db->bind(':price_per_supervisor', $customPricing['price_per_supervisor']);
        $this->db->bind(':price_per_caretaker', $customPricing['price_per_caretaker']);
        $this->db->bind(':custom_package_name', 'Custom Package');
        
        if ($this->db->execute()) {
            return $this->db->rowCount();
        } else {
            return false;
        }
    }
}
