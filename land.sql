CREATE TABLE IF NOT EXISTS lands (
    id INT(11) NOT NULL AUTO_INCREMENT,
    farmer_id INT(11) NOT NULL,

    land_name VARCHAR(150),
    land_number VARCHAR(100),

    land_area DECIMAL(12,3),
    area_unit VARCHAR(30),

    location_text VARCHAR(255),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),

    soil_type VARCHAR(100),

    current_crop VARCHAR(150),
    previous_crop VARCHAR(150),
    future_crop VARCHAR(150),

    farming_goal VARCHAR(100),
    avoid_items TEXT,

    water_availability VARCHAR(50),
    water_quantity DECIMAL(12,3),
    water_unit VARCHAR(30),

    nearby_river VARCHAR(30),
    nearby_wastewater VARCHAR(30),
    flood_risk VARCHAR(30),

    environmental_notes TEXT,
    additional_notes TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_farmer_id (farmer_id),

    CONSTRAINT fk_lands_farmer
    FOREIGN KEY (farmer_id)
    REFERENCES farmers(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;