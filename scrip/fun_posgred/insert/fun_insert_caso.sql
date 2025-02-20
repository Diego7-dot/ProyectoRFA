-- Keren Morgado
-- Insercion de casos
-- SELECT * FROM usuario;
-- SELECT * FROM caso; 
-- SELECT fun_insert_caso('keren@gmail.com', 'Descripción del caso', 'Testimonio del caso', 'Recomendación del caso', '2024-11-08','Pendiente');

CREATE OR REPLACE FUNCTION fun_insert_caso(
    wcor_usu_cas caso.cor_usu_cas%TYPE,  -- Correo del usuario
    wdes_cas caso.des_cas%TYPE,          -- Descripción del caso
    wtes_cas caso.tes_cas%TYPE,          -- Testimonio del caso
    wrec_cas caso.rec_cas%TYPE,          -- Recomendación del caso
    wfec_cas caso.fec_cas%TYPE,          -- Fecha del caso
    west_cas caso.est_cas%TYPE           -- Estado del caso
) RETURNS BOOLEAN AS
$$
DECLARE
    wcod_cas caso.cod_cas%TYPE;
BEGIN
    -- Obtener el máximo código de caso existente
    SELECT MAX(cod_cas) INTO wcod_cas FROM caso;
  
    -- Incrementar el código si ya existe algún caso
    IF wcod_cas IS NOT NULL THEN
        wcod_cas := wcod_cas + 1;
    ELSE
        wcod_cas := 1; -- Si no hay casos, el primer código será 1
    END IF;

    -- Insertar el nuevo caso
    INSERT INTO caso (cod_cas, cor_usu_cas, des_cas, tes_cas, rec_cas, fec_cas, est_cas)
    VALUES (wcod_cas, wcor_usu_cas, wdes_cas, wtes_cas, wrec_cas, wfec_cas, west_cas);

    -- Verificar si la inserción fue exitosa
    IF FOUND THEN
        RAISE NOTICE 'Insertado exitosamente el caso con codigo % y referenciando al usuario %.' , wcod_cas, wcor_usu_cas;
        RETURN TRUE;
    ELSE
        RAISE NOTICE 'No se pudo insertar el caso, por favor verifique los datos.';
        RETURN FALSE;
    END IF;
END;
$$ LANGUAGE plpgsql;
