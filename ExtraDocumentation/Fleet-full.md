erDiagram
	users }o--|| fleets : references
	ressources ||--o{ fleets : references
	locations ||--o{ ressources : references

	users {
		UUID id
		VARCHAR(180) username
		VARCHAR(180) password
		VARCHAR(255) email
		VARCHAR(180) company
		TIMESTAMP created_at
		TIMESTAMP updated_at
	}

	fleets {
		UUID id
		UUID user_id
		VARCHAR(255) label
		TIMESTAMP created_at
		TIMESTAMP updated_at
	}

	ressources {
		UUID id
		UUID fleet_id
		VARCHAR(40) plate_number
		VARCHAR(40) mode
		TIMESTAMP created_at
		TIMESTAMP updated_at
	}

	locations {
		UUID id
		UUID ressource_id
		DECIMAL lat
		DECIMAL lng
		INTEGER place_number
		TIMESTAMP created_at
		TIMESTAMP updated_at
	}