-- SEQUENCE: public.componente_id_seq

-- DROP SEQUENCE public.componente_id_seq;

CREATE SEQUENCE public.componente_id_seq
    INCREMENT 1
    START 1
    MINVALUE 1
    MAXVALUE 2147483647
    CACHE 1;

ALTER SEQUENCE public.componente_id_seq
    OWNER TO postgres;

-- Table: public.componente

-- DROP TABLE public.componente;

CREATE TABLE public.componente
(
    id integer NOT NULL DEFAULT nextval('componente_id_seq'::regclass),
    nombre character varying COLLATE pg_catalog."default" NOT NULL,
    id_tipocomponente integer NOT NULL,
    serie character varying COLLATE pg_catalog."default",
    observacion text COLLATE pg_catalog."default",
    baja boolean NOT NULL DEFAULT false,
    CONSTRAINT componente_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE public.componente
    OWNER to postgres;

    -- SEQUENCE: public.tipocomponente_id_seq

    -- DROP SEQUENCE public.tipocomponente_id_seq;

    CREATE SEQUENCE public.tipocomponente_id_seq
        INCREMENT 1
        START 1
        MINVALUE 1
        MAXVALUE 2147483647
        CACHE 1;

    ALTER SEQUENCE public.tipocomponente_id_seq
        OWNER TO postgres;
    -- Table: public.tipocomponente

    -- DROP TABLE public.tipocomponente;

    CREATE TABLE public.tipocomponente
    (
        id integer NOT NULL DEFAULT nextval('tipocomponente_id_seq'::regclass),
        nombre character varying COLLATE pg_catalog."default" NOT NULL,
        descripcion character varying COLLATE pg_catalog."default",
        CONSTRAINT tipocomponente_pkey PRIMARY KEY (id)
    )

    TABLESPACE pg_default;

    ALTER TABLE public.tipocomponente
        OWNER to postgres;
