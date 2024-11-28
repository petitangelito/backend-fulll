CREATE TABLE "users" (
	"id" UUID NOT NULL UNIQUE,
	"username" VARCHAR(180) NOT NULL,
	-- This field is only for demo purpose
	"password" VARCHAR(180) NOT NULL,
	"email" VARCHAR(255) NOT NULL UNIQUE,
	"company" VARCHAR(180),
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY("id")
);
COMMENT ON COLUMN users.password IS 'This field is only for demo purpose';


CREATE TABLE "fleets" (
	"id" UUID NOT NULL UNIQUE,
	"user_id" UUID NOT NULL,
	"label" VARCHAR(255) NOT NULL,
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY("id")
);


CREATE TABLE "ressources" (
	"id" UUID NOT NULL UNIQUE,
	"fleet_id" UUID,
	"plate_number" VARCHAR(40) UNIQUE,
	"mode" VARCHAR(40),
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY("id")
);


CREATE TABLE "locations" (
	"id" UUID NOT NULL UNIQUE,
	"ressource_id" UUID,
	"lat" DECIMAL,
	"lng" DECIMAL,
	"place_number" INTEGER NOT NULL,
	"created_at" TIMESTAMP,
	"updated_at" TIMESTAMP,
	PRIMARY KEY("id")
);


ALTER TABLE "users"
ADD FOREIGN KEY("id") REFERENCES "fleets"("user_id")
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE "ressources"
ADD FOREIGN KEY("fleet_id") REFERENCES "fleets"("id")
ON UPDATE NO ACTION ON DELETE NO ACTION;
ALTER TABLE "locations"
ADD FOREIGN KEY("ressource_id") REFERENCES "ressources"("id")
ON UPDATE NO ACTION ON DELETE NO ACTION;